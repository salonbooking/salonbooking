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
        $filename = tempnam('/tmp', 'sln_import');
        if (!$filename) {
            $this->addError(__('Cannot create tmp file', 'salon-booking-system'));
            return false;
        }
        $moved = move_uploaded_file($_FILES['file']['tmp_name'], $filename);
        if (!$moved) {
            $this->addError(__('Cannot write to tmp file', 'salon-booking-system'));
            return false;
        }
        set_transient($this->getTransientKey(), $filename, 60 * 60 * 24);

        $fh = fopen($filename, 'r');
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

        file_put_contents($filename, $this->jsonEncodePartialOnError($import));

        $args             = compact('headers');
        $args['rows']     = $this->getItemsForPreview($items);
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
        $filename = get_transient($this->getTransientKey());
        if (!$filename) {
            $this->addError(__('Filename not found', 'salon-booking-system'));
            return false;
        }
        $import = json_decode(file_get_contents($filename), true);
        if (empty($import)) {
            $this->addError(__('Import data not found', 'salon-booking-system'));
            return false;
        }

        parse_str($_POST['form'], $form);
        $import['mapping'] = $form['import_matching'];

        file_put_contents($filename, $this->jsonEncodePartialOnError($import));

        return array(
            'total' => $import['total'],
            'left'  => $import['total'],
        );
    }

    protected function stepFinish()
    {
        $filename = get_transient($this->getTransientKey());
        if ($filename && file_exists($filename)) {
            unlink($filename);
        }
        delete_transient($this->getTransientKey());

        return true;
    }

    protected function stepProcess()
    {
        $filename = get_transient($this->getTransientKey());
        if (!$filename) {
            $this->addError(__('Filename not found', 'salon-booking-system'));
            return false;
        }
        $import = json_decode(file_get_contents($filename), true);

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
            else {
                $item[$field] = '';
            }
        }
        $imported = $this->processRow($item);

        if ($imported === true) {
            file_put_contents($filename, $this->jsonEncodePartialOnError($import));
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

    protected function getItemsForPreview($items)
    {
        $items = array_slice($items, 0, 4, true);

        $items = json_decode($this->jsonEncodePartialOnError($items), true);

        return $items;
    }

    protected function jsonEncodePartialOnError($data)
    {
        $json = json_encode($data, defined('JSON_PARTIAL_OUTPUT_ON_ERROR') ? JSON_PARTIAL_OUTPUT_ON_ERROR : 0);

        return $json;
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
