<?php

class SLN_Shortcode_Salon_AttendantStep extends SLN_Shortcode_Salon_Step
{

    protected function dispatchForm()
    {
        $bb     = $this->getPlugin()->getBookingBuilder();
        $bb->removeAttendants();
        $values = isset($_POST['sln']) ? $_POST['sln'] : array();
        $isMultipleAttSelection = boolval($this->getPlugin()->getSettings()->get('m_attendant_enabled'));
        foreach ($bb->getServices() as $service) {
            if ($isMultipleAttSelection) {
                if (isset($values['attendants'][$service->getId()])) {
                    $bb->setAttendant(new SLN_Wrapper_Attendant($values['attendants'][$service->getId()]), $service);
                }
            }
            else {
                if (isset($values['attendants'][0])) {
                    $bb->setAttendant(new SLN_Wrapper_Attendant($values['attendants'][0]), $service);
                }
            }
        }
        $bb->save();

        return true;
    }

    /**
     * @return SLN_Wrapper_Attendant[]
     */
    public function getAttendants()
    {
        if (!isset($this->attendants)) {
            $this->attendants = array();
            foreach ($this->getPlugin()->getAttendants() as $attendant) {
                $this->attendants[] = $attendant;
            }
        }

        return $this->attendants;
    }

    public function isValid()
    {
        $tmp = $this->getAttendants();

        return (!empty($tmp)) && parent::isValid();
    }

    public function getViewData()
    {
        $ret = parent::getViewData();
        $ret['isMultipleAttSelection'] = $this->getPlugin()->getSettings()->get('m_attendant_enabled') ? true : false;

        return $ret;
    }
}
