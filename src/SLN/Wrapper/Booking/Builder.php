<?php

class SLN_Wrapper_Booking_Builder
{
    protected $plugin;
    protected $data;
    protected $lastId;

    public function __construct(SLN_Plugin $plugin)
    {
        if (session_id() == '') {
            session_start();
        }
        $this->plugin = $plugin;
        $this->data   = isset($_SESSION[__CLASS__]) ? $_SESSION[__CLASS__] : $this->getEmptyValue();
        $this->lastId = isset($_SESSION[__CLASS__ . 'last_id']) ? $_SESSION[__CLASS__ . 'last_id'] : null;
    }

    public function save()
    {
        $_SESSION[__CLASS__]             = $this->data;
        $_SESSION[__CLASS__ . 'last_id'] = $this->lastId;
    }

    public function clear($id = null)
    {
        $this->data   = $this->getEmptyValue();
        $this->lastId = $id;
        $this->save();
    }

    /**
     * @return $this
     */
    public function removeLastId()
    {
        unset($_SESSION[__CLASS__ . 'last_id']);
        $this->lastId = null;

        return $this;
    }

    /**
     * @return SLN_Wrapper_Booking
     */
    public function getLastBooking()
    {
        if ($this->lastId) {
            return $this->plugin->createBooking($this->lastId);
        }
    }

    protected function getEmptyValue()
    {
        $from = $this->plugin->getSettings()->getHoursBeforeFrom();
        $d = new SLN_DateTime(date('Y-m-d H:i:00'));
        $d->modify($from);
        $tmp = $d->format('i');
        $i             = SLN_Plugin::getInstance()->getSettings()->getInterval();
        $diff = $tmp % $i;
        if($diff > 0)
            $d->modify('+'.( $i - $diff).' minutes');
        return array(
            'date'     => $d->format('Y-m-d'),
            'time'     => $d->format('H:i'),
            'services' => array(),
//            'attendants' => array(),
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

    public function getDateTime()
    {
        $ret =  new SLN_DateTime($this->getDate() . ' ' . $this->getTime());
        return $ret;
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

    public function setAttendant(SLN_Wrapper_Attendant $attendant, SLN_Wrapper_Service $service)
    {
        if ($this->hasService($service)) {
            $this->data['services'][$service->getId()] = $attendant->getId();
        }
    }

    public function hasAttendant(SLN_Wrapper_Attendant $attendant, SLN_Wrapper_Service $service = null)
    {
        if (!isset($this->data['services'])) {
            return false;
        }

        if (is_null($service)) {
            return in_array($attendant->getId(), $this->data['services']);
        }
        else {
            return isset($this->data['services'][$service->getId()]) && $this->data['services'][$service->getId()] == $attendant->getId();
        }
    }

    public function removeAttendants()
    {
        $this->data['services'] = array_fill_keys(array_keys($this->data['services']), 0);
    }


    public function hasService(SLN_Wrapper_Service $service)
    {
        return in_array($service->getId(), array_keys($this->data['services']));
    }

	/**
     * @return SLN_Wrapper_Attendant|false
     */
    public function getAttendant()
    {
        $atts = $this->getAttendants();
        return reset($atts);
    }
    /**
     * @return SLN_Wrapper_Attendant[]
     */
    public function getAttendants()
    {
        $ids = $this->data['services'];
        $ret = array();
        foreach ($this->plugin->getAttendants() as $attendant) {
            $service_id = array_search($attendant->getId(), $ids);
            if ($service_id !== false) {
                $ret[$service_id] = $attendant;
            }
        }
        return $ret;
    }
    
    public function addService(SLN_Wrapper_Service $service)
    {
        if((!isset($this->data['services'])) || (!in_array($service->getId(), array_keys($this->data['services'])))){
            $this->data['services'][$service->getId()] = 0;
            uksort($this->data['services'], function($a, $b) {
                $aExecOrder = SLN_Plugin::getInstance()->createService($a)->getExecOrder();
                $bExecOrder = SLN_Plugin::getInstance()->createService($b)->getExecOrder();
                if ($aExecOrder > $bExecOrder)
                    return 1;
                else
                    return -1;
            });
        }
    }

    public function removeService(SLN_Wrapper_Service $service)
    {
        if (isset($this->data['services'])) {
            unset($this->data['services'][$service->getId()]);
        }
    }
    public function clearServices(){
        $this->data['services'] = array();
    }

    /**
     * @return SLN_Wrapper_Service[]
     */
    public function getServices()
    {
        $ids = array_keys($this->data['services']);
        $ret = array();
        foreach ($this->plugin->getServices() as $service) {
            if (in_array($service->getId(), $ids)) {
                $ret[$service->getId()] = $service;
            }
        }

        return $ret;
    }
    
    public function getTotal()
    {
        $ret = 0;
        foreach ($this->getServices() as $s) {
            $ret = $ret + SLN_Func::filter($s->getPrice(), 'float');
        }

        return $ret;
    }

    public function create()
    {
        update_option(SLN_Plugin::F, intval(get_option(SLN_Plugin::F))+1);
        $settings             = $this->plugin->getSettings();
        $datetime             = $this->plugin->format()->datetime($this->getDateTime());
        $name                 = $this->get('firstname') . ' ' . $this->get('lastname');
        $status               = $this->getCreateStatus();
        $id                   = wp_insert_post(
            array(
                'post_type'   => SLN_Plugin::POST_TYPE_BOOKING,
                'post_title'  => $name . ' - ' . $datetime,
            )
        );
        $deposit              = $this->plugin->getSettings()->get('pay_deposit');
        $this->data['amount'] = $this->getTotal();
        if($deposit > 0) {
            $this->data['deposit'] = ($this->data['amount'] / 100) * $deposit;
        }
        foreach ($this->data as $k => $v) {
            add_post_meta($id, '_' . SLN_Plugin::POST_TYPE_BOOKING . '_' . $k, $v, true);
        }
        $this->clear($id);
        $this->getLastBooking()->evalDuration($status);
        $this->getLastBooking()->setStatus($status);

        $userid = $this->getLastBooking()->getUserId();
        $user = new WP_User($userid);
        if (array_search('administrator', $user->roles) === false && array_search('subscriber', $user->roles) !== false) {
            wp_update_user(array(
                'ID' => $userid,
                'role' => SLN_Plugin::USER_ROLE_CUSTOMER,
            ));
        }
    }
    private function getCreateStatus(){
        $settings = $this->plugin->getSettings();
        return $settings->get('confirmation') ?
            SLN_Enum_BookingStatus::PENDING
            : ($settings->get('pay_enabled') ?
                SLN_Enum_BookingStatus::PENDING
                : ($settings->isHidePrices() ? SLN_Enum_BookingStatus::CONFIRMED
                    : SLN_Enum_BookingStatus::PAY_LATER ));
    }

    public function getDuration()
    {
        $i = $this->getServicesDurationMinutes();
        if($i == 0)
            $i = 60;
        $str = SLN_Func::convertToHoursMins($i);
        return $str;
    }

    public function getServicesDurationMinutes(){
        $h = 0;
        $i = 0;
        foreach($this->getServices() as $s){
            $d = $s->getDuration();
            $h = $h + intval($d->format('H'));
            $i = $i + intval($d->format('i'));
        }
        $i += $h*60;
        return $i;
    }
}
