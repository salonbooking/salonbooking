<?php

abstract class SLN_Action_Ajax_AbstractImport extends SLN_Action_Ajax_Abstract
{
    protected $type;
    protected $fields = array();
    protected $errors = array();

    public function execute()
    {
        $data = array();

        $step = isset($_POST['step']) ? $_POST['step'] : '';
        $method = "step_{$step}";

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

    protected function step_start()
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
                if (array_search($headers[$i], $this->fields) !== false) {
                    $item[$headers[$i]] = $v;
                }
            }
            $items[] = $item;
        }
        fclose($fh);

        $import = array(
            'total' => count($items),
            'items' => $items,
        );

        set_transient($this->getTransientKey(), $import, 60 * 60 * 24);

        return array(
            'total' => $import['total'],
            'left'  => $import['total'],
        );
    }

    protected function step_finish()
    {
        delete_transient($this->getTransientKey());

        return true;
    }

    protected function step_process()
    {
        $import = get_transient($this->getTransientKey());

        if (empty($import) || empty($import['items'])) {
            $this->addError(__('Data not found', 'salon-booking-system'));
            return false;
        }


        $item = array_shift($import['items']);
        $imported = $this->process_row($item);

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
    abstract protected function process_row($data);

    protected function getTransientKey() {
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
