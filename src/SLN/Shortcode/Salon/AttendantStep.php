<?php

class SLN_Shortcode_Salon_AttendantStep extends SLN_Shortcode_Salon_Step
{

    protected function dispatchForm()
    {

        $values                 = isset($_POST['sln']) ? $_POST['sln'] : array();
        $isMultipleAttSelection = $this->getPlugin()->getSettings()->isMultipleAttendantsEnabled();
        $bb                     = $this->getPlugin()->getBookingBuilder();
        $ah                     = $this->getPlugin()->getAvailabilityHelper();
        $ah->setDate($bb->getDateTime());
        $bb->removeAttendants();

        $bservices = $bb->getAttendantsIds();
        $date      = $bb->getDateTime();

        if ($isMultipleAttSelection) {
            $ids = isset($values['attendants']) ? $values['attendants'] : array();

            $ret = $this->dispatchMultiple($bservices, $date, $ids);
        } else {
            $id = isset($values['attendant']) ? $values['attendant'] : null;

            $ret = $this->dispatchSingle($bservices, $date, $id);
        }

        if (is_array($ret)) {
            $bb->setServicesAndAttendants($ret);
        }

        if ($ret) {
            $bb->save();

            return true;
        } else {
            return false;
        }
    }

    public function dispatchMultiple($services, $date, $selected)
    {
        $ah = $this->getPlugin()->getAvailabilityHelper();
        $ah->setDate($date);
        $bookingServices = SLN_Wrapper_Booking_Services::build($services, $date);

        $availAtts               = null;
        $availAttsForEachService = array();

        foreach ($bookingServices->getItems() as $bookingService) {
            $service = $bookingService->getService();
            if (!$service->isAttendantsEnabled()) {
                continue;
            }
            $tmp                                        = $ah->getAvailableAttsIdsForBookingService($bookingService);
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
                $attendantId  = $selected[$service->getId()];
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

        $ret = array();

        foreach ($bookingServices->getItems() as $bookingService) {
            $service = $bookingService->getService();

            if (!$service->isAttendantsEnabled()) {
                $ret[$service->getId()] = 0;
                continue;
            }

            if (!empty($selected[$service->getId()])) {
                $attId = $selected[$service->getId()];
            } else {
                $index = mt_rand(0, count($availAttsForEachService[$service->getId()]) - 1);
                $attId = $availAttsForEachService[$service->getId()][$index];
            }

            $ret[$service->getId()] = $attId;
        }

        return $ret;
    }

    public function dispatchSingle($services, $date, $selected)
    {
        $ah = $this->getPlugin()->getAvailabilityHelper();
        $ah->setDate($date);
        $bookingServices = SLN_Wrapper_Booking_Services::build($services, $date);

        $availAtts = null;
        foreach ($bookingServices->getItems() as $bookingService) {
            if (!$bookingService->getService()->isAttendantsEnabled()) {
                continue;
            }
            $availAtts = $ah->getAvailableAttendantForService($availAtts, $bookingService);

            if (empty($availAtts)) {
                $this->addError(
                    __('No one of the attendants isn\'t available for selected services', 'salon-booking-system')
                );

                return false;
            }
        }

        if (!$selected) {
            if (count($availAtts)) {
                $index = mt_rand(0, count($availAtts) - 1);
                $attId = $availAtts[$index];
            } else {
                $attId = 0;
            }
        } else {
            $attId = $selected;
        }

        $ret = array();
        foreach ($bookingServices->getItems() as $bookingService) {
            $service = $bookingService->getService();

            if (!$service->isAttendantsEnabled()) {
                $ret[$service->getId()] = 0;
                continue;
            }

            $ret[$service->getId()] = $attId;
        }

        return $ret;
    }


    /**
     * @return SLN_Wrapper_Attendant[]
     */
    public function getAttendants()
    {
        if (!isset($this->attendants)) {
            /** @var SLN_Repository_AttendantRepository $repo */
            $repo             = $this->getPlugin()->getRepository(SLN_Plugin::POST_TYPE_ATTENDANT);
            $this->attendants = $repo->sortByPos($repo->getAll());
            $this->attendants = apply_filters('sln.shortcode.salon.AttendantStep.getAttendants', $this->attendants);
        }

        return $this->attendants;
    }

    public function isValid()
    {
        $tmp = $this->getAttendants();

        return (!empty($tmp)) && parent::isValid();
    }
}
