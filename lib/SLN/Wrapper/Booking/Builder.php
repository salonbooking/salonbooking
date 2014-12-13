<?php

class SLN_Wrapper_Booking_Builder
{
    protected $plugin;
    protected $data;

    public function __construct(SLN_Plugin $plugin)
    {
        if (session_id() == '') {
            session_start();
        }
        $this->plugin = $plugin;
        $this->data   = isset($_SESSION[__CLASS__]) ? $_SESSION[__CLASS__] : $this->getEmptyValue();
    }

    public function save()
    {
        $_SESSION[__CLASS__] = $this->data;
    }

    public function clear()
    {
        $_SESSION[__CLASS__] = $this->getEmptyValue();
    }

    protected function getEmptyValue()
    {
        return array(
            'date'     => new \DateTime(),
            'time'     => new \DateTime(),
            'services' => array()
        );
    }

    public function get($k)
    {
        return isset($this->data[$k]) ? $this->data[$k] : null;
    }

    public function set($key, $val)
    {
        if (empty($val)) {
            unset($this->data[$key]);
        } else {
            $this->data[$key] = $val;
        }
    }

    public function getDate()
    {
        return $this->data['date'];
    }

    public function getTime()
    {
        return $this->data['time'];
    }

    public function setDate($date)
    {
        $this->data['date'] = $date;

        return $this;
    }

    public function setTime($time)
    {
        $this->data['time'] = $time;

        return $this;
    }

    public function hasService(SLN_Wrapper_Service $service)
    {
        return in_array($service->getId(), $this->data['services']);
    }

    public function addService(SLN_Wrapper_Service $service)
    {
        $this->data['services'][] = $service->getId();
    }

    public function removeService(SLN_Wrapper_Service $service)
    {
        $k = array_search($service->getId(), $this->data['services']);
        unset($this->data['services'][$k]);
    }

    /**
     * @return SLN_Wrapper_Service[]
     */
    public function getServices()
    {
        $ids = $this->data['services'];
        $ret = array();
        foreach ($this->plugin->getServices() as $service) {
            if (in_array($service->getId(), $ids)) {
                $ret[] = $service;
            }
        }

        return $ret;
    }

    public function getTotal()
    {
        $ret = 0;
        foreach ($this->getServices() as $s) {
            $ret += $s->getPrice();
        }

        return $ret;
    }
}