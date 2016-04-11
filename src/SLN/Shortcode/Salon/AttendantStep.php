<?php

class SLN_Shortcode_Salon_AttendantStep extends SLN_Shortcode_Salon_Step
{

    protected function dispatchForm()
    {
        $bb = $this->getPlugin()->getBookingBuilder();
        $ah = $this->getPlugin()->getAvailabilityHelper();
        $ah->setDate($bb->getDateTime());
        $bb->removeAttendants();
        $values = isset($_POST['sln']) ? $_POST['sln'] : array();
        $isMultipleAttSelection = $this->getPlugin()->getSettings()->get('m_attendant_enabled') ? true: false;

        $bookingServices = SLN_Wrapper_Booking_Services::build(array_fill_keys($bb->getServicesIds(), 0), $bb->getDateTime());

        $availAtts               = null;
        $availAttsForEachService = array();

        foreach ($bookingServices->getItems() as $bookingService) {
            $service = $bookingService->getService();
            if ($isMultipleAttSelection) {
                $tmp = $ah->getAvailableAttsIdsForServiceOnTime($service, $bookingService->getStartsAt(), $bookingService->getDuration());
                $availAttsForEachService[$service->getId()] = $tmp;
                if (empty($tmp)) {
                    $this->addError(
                        sprintf(
                            __('No one of the attendants isn\'t available for %s service', 'salon-booking-system'),
                            $service->getName()
                        )
                    );
                    return false;
                }
                elseif (isset($values['attendants'][$service->getId()])) {
                    if (!in_array($values['attendants'][$service->getId()], $availAttsForEachService[$service->getId()])) {
                        $this->addError(
                            sprintf(
                                __('Attendant %s isn\'t available for %s service at %s', 'salon-booking-system'),
                                $this->getPlugin()->createAttendant($values['attendants'][$service->getId()])->getName(),
                                $service->getName(),
                                $ah->getDayBookings()->getTime($bookingService->getStartsAt()->format('H'), $bookingService->getStartsAt()->format('i'))
                            )
                        );
                        return false;
                    }
                }
            }
            else {
                if (is_null($availAtts)) {
                    $availAtts = $ah->getAvailableAttsIdsForServiceOnTime($bookingService->getService(), $bookingService->getStartsAt(), $bookingService->getDuration());
                }
                $availAtts = array_intersect(
                    $availAtts,
                    $ah->getAvailableAttsIdsForServiceOnTime($bookingService->getService(), $bookingService->getStartsAt(), $bookingService->getDuration())
                );
                if (empty($availAtts)) {
                    $this->addError(__('No one of the attendants isn\'t available for selected services', 'salon-booking-system'));
                    return false;
                }

            }
        }

        foreach ($bb->getServices() as $service) {
            if ($isMultipleAttSelection) {
                if (isset($values['attendants'][$service->getId()])) {
                    $attId = $values['attendants'][$service->getId()];
                }
                else {
                    $index = mt_rand(0, count($availAttsForEachService[$service->getId()])-1);
                    $attId = $availAttsForEachService[$service->getId()][$index];
                }
                $bb->setAttendant($this->getPlugin()->createAttendant($attId), $service);
            }
            else {
                if (empty($attId)) {
                    $index = mt_rand(0, count($availAtts)-1);
                    $attId = $availAtts[$index];
                }
                $bb->setAttendant($this->getPlugin()->createAttendant($attId), $service);
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

    public function getViewData()
    {
        $ret = parent::getViewData();
        $ret['isMultipleAttSelection'] = $this->getPlugin()->getSettings()->get('m_attendant_enabled') ? true : false;

        return $ret;
    }
}
