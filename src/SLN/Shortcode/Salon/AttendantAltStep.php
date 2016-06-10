<?php

class SLN_Shortcode_Salon_AttendantAltStep extends SLN_Shortcode_Salon_AttendantStep
{
    public function dispatchMultiple($services, $date, $selected)
    {
        $ah = $this->getPlugin()->getAvailabilityHelper();
        $ah->setDate($date);
        $bookingServices = SLN_Wrapper_Booking_Services::build($services, $date);

        $availAtts = null;
        $availAttsForEachService = array();

        foreach ($bookingServices->getItems() as $bookingService) {
            $service = $bookingService->getService();
            if (!$service->isAttendantsEnabled()) {
                continue;
            }
            $tmp = $service->getAttendantsIds();
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
                            __('Attendant %s isn\'t available for %s service', 'salon-booking-system'),
                            $attendant->getName(),
                            $service->getName()
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
            $service = $bookingService->getService();
            if (!$service->isAttendantsEnabled()) {
                continue;
            }
            if (is_null($availAtts)) {
                $availAtts = $service->getAttendantsIds();
            }
            $availAtts = array_intersect($availAtts, $service->getAttendantsIds());
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
            }
            else {
                $attId = 0;
            }
        }
        else {
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

}
