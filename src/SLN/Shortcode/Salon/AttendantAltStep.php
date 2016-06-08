<?php

class SLN_Shortcode_Salon_AttendantAltStep extends SLN_Shortcode_Salon_AttendantStep
{

    protected function dispatchForm()
    {

        $values = isset($_POST['sln']) ? $_POST['sln'] : array();
        $isMultipleAttSelection = $this->getPlugin()->getSettings()->isMultipleAttendantsEnabled();
        $bb = $this->getPlugin()->getBookingBuilder();
        $ah = $this->getPlugin()->getAvailabilityHelper();
        $ah->setDate($bb->getDateTime());
        $bb->removeAttendants();

        if ($isMultipleAttSelection) {
            $ids = isset($values['attendants']) ? $values['attendants'] : array();
            foreach($bb->getServices() as $service) {
                if (isset($ids[$service->getId()])) {
                    $bb->setAttendant($this->getPlugin()->createAttendant($ids[$service->getId()]), $service);
                } else {
                    $bb->clearService($service);
                }
            }
        } else {
            $id = isset($values['attendant']) ? $values['attendant'] : null;
            foreach($bb->getServices() as $service) {
                if ($id) {
                    $bb->setAttendant($this->getPlugin()->createAttendant($id), $service);
                } else {
                    $bb->clearService($service);
                }
            }
        }
        $ret = true;

        if ($ret) {
            $bb->save();

            return true;
        } else {
            return false;
        }
    }

}
