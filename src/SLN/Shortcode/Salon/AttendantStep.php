<?php

class SLN_Shortcode_Salon_AttendantStep extends SLN_Shortcode_Salon_Step
{

    protected function dispatchForm()
    {

        $values = isset($_POST['sln']) ? $_POST['sln'] : array();
        $isMultipleAttSelection = $this->getPlugin()->getSettings()->isMultipleAttendantsEnabled();
        $bb = $this->getPlugin()->getBookingBuilder();
        $ah = $this->getPlugin()->getAvailabilityHelper();
        $ah->setDate($bb->getDateTime());
        $bb->removeAttendants();


        if ($this->getPlugin()->getSettings()->isFormStepsAltOrder()) {
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
                    if (isset($ids[$service->getId()])) {
                        $bb->setAttendant($this->getPlugin()->createAttendant($id), $service);
                    } else {
                        $bb->clearService($service);
                    }
                }
            }
            $ret = true;
        } else {
            if ($isMultipleAttSelection) {
                $ids = isset($values['attendants']) ? $values['attendants'] : array();
                $ret = $this->dispatchMultiple($ids);
            } else {
                $id = isset($values['attendant']) ? $values['attendant'] : null;
                $ret = $this->dispatchSingle($id);
            }
        }
        
        if ($ret) {
            $bb->save();

            return true;
        } else {
            return false;
        }
    }

    public function dispatchMultiple($selected)
    {
        $ah = $this->getPlugin()->getAvailabilityHelper();
        $bb = $this->getPlugin()->getBookingBuilder();
        $availAtts = null;
        $availAttsForEachService = array();

        foreach ($bb->getBookingServices()->getItems() as $bookingService) {
            $service = $bookingService->getService();
            $tmp = $ah->getAvailableAttsIdsForBookingService($bookingService);
            $availAttsForEachService[$service->getId()] = $tmp;
            if (empty($tmp)) {
                $this->addError(
                    sprintf(
                        __('No one of the attendants isn\'t available for %s service', 'salon-booking-system'),
                        $service->getName()
                    )
                );

                return false;
            } elseif (!empty($selected[$service->getId()])) {
                $attendantId = $selected[$service->getId()];
                $hasAttendant = in_array($attendantId, $availAttsForEachService[$service->getId()]);
                if (!$hasAttendant) {
                    $attendant = $this->getPlugin()->createAttendant($attendantId);
                    $this->addError(
                        sprintf(
                            __('Attendant %s isn\'t available for %s service at %s', 'salon-booking-system'),
                            $attendant->getName(),
                            $service->getName(),
                            $ah->getDayBookings()->getTime(
                                $bookingService->getStartsAt()->format('H'),
                                $bookingService->getStartsAt()->format('i')
                            )
                        )
                    );

                    return false;
                }
            }

        }

        foreach ($bb->getServices() as $service) {
            if (!empty($selected[$service->getId()])) {
                $attId = $selected[$service->getId()];
            } else {
                $index = mt_rand(0, count($availAttsForEachService[$service->getId()]) - 1);
                $attId = $availAttsForEachService[$service->getId()][$index];
            }
            $bb->setAttendant($this->getPlugin()->createAttendant($attId), $service);
        }
        return true;
    }

    public function dispatchSingle($selected)
    {
        $ah = $this->getPlugin()->getAvailabilityHelper();
        $bb = $this->getPlugin()->getBookingBuilder();

        $availAtts = null;
        foreach ($bb->getBookingServices()->getItems() as $bookingService) {
            if (is_null($availAtts)) {
                $availAtts = $ah->getAvailableAttsIdsForBookingService($bookingService);
            }
            $availAtts = array_intersect($availAtts, $ah->getAvailableAttsIdsForBookingService($bookingService));
            if (empty($availAtts)) {
                $this->addError(
                    __('No one of the attendants isn\'t available for selected services', 'salon-booking-system')
                );

                return false;
            }
        }
        if (!$selected) {
            $index = mt_rand(0, count($availAtts) - 1);
            $selected = $availAtts[$index];
        }
        $attendant = $this->getPlugin()->createAttendant($selected);
        foreach ($bb->getServices() as $service) {
            $bb->setAttendant($attendant, $service);
        }
        return true;
    }


    /**
     * @return SLN_Wrapper_Attendant[]
     */
    public function getAttendants()
    {
        if (!isset($this->attendants)) {
            /** @var SLN_Repository_AttendantRepository $repo */
            $repo = $this->getPlugin()->getRepository(SLN_Plugin::POST_TYPE_ATTENDANT);
            $this->attendants = $repo->getAll();
        }

        return $this->attendants;
    }

    public function isValid()
    {
        $tmp = $this->getAttendants();

        return (!empty($tmp)) && parent::isValid();
    }
}
