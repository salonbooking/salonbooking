<?php

abstract class SLN_Action_Ajax_AbstractImport extends SLN_Action_Ajax_Abstract
{
    protected $type;
    protected $fields   = array();
    protected $required = array();
    protected $errors   = array();

    public function execute()
    {
        $data = array();

        $step   = ucfirst(isset($_POST['step']) ? $_POST['step'] : '');
        $method = "step{$step}";

        if (method_exists($this, $method)) {
            $data = $this->$method();
        }
        else {
            $this->addError(__('Method not found', 'salon-booking-system'));
        }

        if ($errors = $this->getErrors()) {
            $ret = compact('errors');
        } else {
            $ret = array('success' => 1, 'data' => $data);
        }

        return $ret;
    }

    protected function stepStart()
    {
        if (!isset($_FILES['file'])) {
            $this->addError(__('File not found', 'salon-booking-system'));
            return false;
        }

        $fh = fopen($_FILES['file']['tmp_name'], 'r');
        $headers = fgetcsv($fh); // headers

        $items = array();
        while($row = fgetcsv($fh)) {
            $item = array();
            foreach($row as $i => $v) {
                $item[$headers[$i]] = $v;
            }
            $items[] = $item;
        }
        fclose($fh);

	    $items  = array_filter($items);
	    $items  = $this->prepareRows($items);
        $import = array(
            'total' => count($items),
            'items' => $items,
        );

        set_transient($this->getTransientKey(), $import, 60 * 60 * 24);

        $args = compact('headers');
        $args['rows']     = array_slice($items, 0, 5, true);
        $args['columns']  = $this->fields;
        $args['required'] = $this->required;

        $matching = $this->plugin->loadView('admin/_tools_import_matching', $args);

        return array(
            'total'    => $import['total'],
            'left'     => $import['total'],
            'matching' => $matching,
            'rows'     => $args['rows'],
            'columns'  => $args['columns'],
            'headers'  => $args['headers'],
        );
    }

    protected function stepMatching()
    {
        $import = get_transient($this->getTransientKey());

        parse_str($_POST['form'], $form);
        $import['mapping'] = $form['import_matching'];

        set_transient($this->getTransientKey(), $import, 60 * 60 * 24);

        return array(
            'total' => $import['total'],
            'left'  => $import['total'],
        );
    }

    protected function stepFinish()
    {
        delete_transient($this->getTransientKey());

        return true;
    }

    protected function stepProcess()
    {
        $import = get_transient($this->getTransientKey());

        if (empty($import) || empty($import['items'])) {
            $this->addError(__('Data not found', 'salon-booking-system'));
            return false;
        }

        $mapping = $import['mapping'];
        $item    = array_shift($import['items']);
        foreach($this->fields as $field) {
            if (isset($mapping[$field]) && !empty($mapping[$field])) {
                $item[$field] = $item[$mapping[$field]];
            }
        }
        $imported = $this->processRow($item);

        if ($imported === true) {
            set_transient($this->getTransientKey(), $import, 60 * 60 * 24);
            return array(
                'total' => $import['total'],
                'left'  => count($import['items']),
            );
        }
        else {
            $this->addError($imported);
            return false;
        }
    }

	/**
     * @param array $data
     *
     * @return bool|string
     */
    abstract protected function processRow($data);

	protected function prepareRows($rows)
	{
		return $rows;
	}

    protected function getTransientKey()
    {
        return "sln_import_{$this->type}_data";
    }

    protected function addError($err)
    {
        $this->errors[] = $err;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
