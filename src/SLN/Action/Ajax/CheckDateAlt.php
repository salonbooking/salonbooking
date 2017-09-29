<?php

use Salon\Util\Date;
use Salon\Util\Time;

class SLN_Action_Ajax_CheckDateAlt extends SLN_Action_Ajax_CheckDate
{
	/**
	 * @param array        $services
	 * @param SLN_DateTime $datetime
	 *
	 * @return bool
	 */
	private function checkDayServicesAndAttendants($services, $datetime) {
		$bookingServices = SLN_Wrapper_Booking_Services::build($services, $datetime);
		$date            = Date::create($datetime->format('Y-m-d'));
		foreach ($bookingServices->getItems() as $bookingService) {
			/** @var SLN_Helper_AvailabilityItems $avServiceItems */
			$avServiceItems = $bookingService->getService()->getAvailabilityItems();
			if(!$avServiceItems->isValidDate($date)) {
				return false;
			}

			$attendant = $bookingService->getAttendant();
			if (!empty($attendant)) {
				/** @var SLN_Helper_AvailabilityItems $avAttendantItems */
				$avAttendantItems = $attendant->getAvailabilityItems();
				if(!$avAttendantItems->isValidDate($date)) {
					return false;
				}
			}
		}

		return true;
	}

    public function getIntervalsArray() {
        if ($this->isAdmin()) {
            return parent::getIntervalsArray();
        }

        $fullDays = array();
        $plugin = $this->plugin;
        $ah   = $plugin->getAvailabilityHelper();

        $bb = $plugin->getBookingBuilder();
        $bservices = $bb->getAttendantsIds();
        $this->setDuration(new Time($bb->getDuration()));
        $intervalsArray = parent::getIntervalsArray();
        foreach($intervalsArray['dates'] as $k => $v) {
            $available = false;
            $tmpDate   = new SLN_DateTime($v);

            if ($this->checkDayServicesAndAttendants($bservices, $tmpDate)) {
	            $ah->setDate($tmpDate);
	            $times = $ah->getCachedTimes(Date::create($tmpDate), $this->duration);
	            foreach ($times as $time) {
		            $tmpDateTime = new SLN_DateTime("$v $time");
		            $errors = $this->checkDateTimeServicesAndAttendants($bservices, $tmpDateTime);
		            if (empty($errors)) {
			            $available = true;
			            break;
		            }
	            }
            }

            if (!$available) {
                unset($intervalsArray['dates'][$k]);
                $fullDays[] = $v;
            }
        }

        if(empty($intervalsArray['dates'])) {
            return $intervalsArray;
        }

        $suggestedDate = $intervalsArray['suggestedYear'].'-'.$intervalsArray['suggestedMonth'].'-'.$intervalsArray['suggestedDay'];
        if (array_search($suggestedDate, $intervalsArray['dates']) === false) {
            $suggestedDate = reset($intervalsArray['dates']);
        }
        $tmpDate = new SLN_DateTime($suggestedDate);

        $intervalsArray['suggestedDate']  = $plugin->format()->date($tmpDate);
        $intervalsArray['suggestedYear']  = $tmpDate->format('Y');
        $intervalsArray['suggestedMonth'] = $tmpDate->format('m');
        $intervalsArray['suggestedDay']   = $tmpDate->format('d');

        $ah->setDate($tmpDate);
        $intervalsArray['times'] = $ah->getCachedTimes(Date::create($tmpDate), $this->duration);

        foreach ($intervalsArray['times'] as $k => $t) {
            $tmpDateTime = new SLN_DateTime("$suggestedDate $t");
            $ah->setDate($tmpDateTime);
            $errors = $this->checkDateTimeServicesAndAttendants($bservices, $tmpDateTime);
            if (!empty($errors)) {
                unset($intervalsArray['times'][$k]);
            }
        }

        if (!isset($intervalsArray['times'][date('H:i', strtotime($intervalsArray['suggestedTime']))])) {
            $tmpTime = new SLN_DateTime(reset($intervalsArray['times']));
            $intervalsArray['suggestedTime'] = $plugin->format()->time($tmpTime);
        }
        $intervalsArray['fullDays'] = array_merge($intervalsArray['fullDays'], $fullDays);
        return $intervalsArray;
    }

    public function isAdmin() {
        return isset($_POST['post_ID']);
    }

    public function checkDateTime()
    {
        parent::checkDateTime();
        if ($this->isAdmin()) {
            return;
        }

        $plugin = $this->plugin;
        $errors = $this->getErrors();

        if (empty($errors)) {
            $date   = $this->getDateTime();

            $bb = $plugin->getBookingBuilder();
            $bservices = $bb->getAttendantsIds();

            $errors = $this->checkDateTimeServicesAndAttendants($bservices, $date);

            foreach($errors as $error) {
                $this->addError($error);
            }
        }

    }

    public function checkDateTimeServicesAndAttendants($services, $date) {
        $errors = array();

        $plugin = $this->plugin;
        $ah     = $plugin->getAvailabilityHelper();
        $ah->setDate($date);

        $isMultipleAttSelection = SLN_Plugin::getInstance()->getSettings()->get('m_attendant_enabled');
        $bookingOffsetEnabled   = SLN_Plugin::getInstance()->getSettings()->get('reservation_interval_enabled');
        $bookingOffset          = SLN_Plugin::getInstance()->getSettings()->get('minutes_between_reservation');

        $bookingServices = SLN_Wrapper_Booking_Services::build($services, $date);

        $firstSelectedAttendant = null;


        foreach($bookingServices->getItems() as $bookingService) {
            $serviceErrors   = array();
            $attendantErrors = array();

            if ($bookingServices->isLast($bookingService) && $bookingOffsetEnabled) {
                $offsetStart   = $bookingService->getEndsAt();
                $offsetEnd     = $bookingService->getEndsAt()->modify('+'.$bookingOffset.' minutes');
                $serviceErrors = $ah->validateTimePeriod($offsetStart, $offsetEnd);
            }
            if (empty($serviceErrors)) {
                $serviceErrors = $ah->validateBookingService($bookingService);
            }
            if (!empty($serviceErrors)) {
                $errors[] = $serviceErrors[0];
                continue;
            }

            if ($bookingService->getAttendant() === false) {
                continue;
            }

            if (!$isMultipleAttSelection) {
                if (!$firstSelectedAttendant) {
                    $firstSelectedAttendant = $bookingService->getAttendant()->getId();
                }
                if ($bookingService->getAttendant()->getId() != $firstSelectedAttendant) {
                    $attendantErrors = array(__('Multiple attendants selection is disabled. You must select one attendant for all services.', 'salon-booking-system'));
                }
            }
            if (empty($attendantErrors)) {
                $attendantErrors = $ah->validateAttendantService(
                    $bookingService->getAttendant(),
                    $bookingService->getService()
                );
                if (empty($attendantErrors)) {
                    $attendantErrors = $ah->validateBookingAttendant($bookingService);
                }
            }

            if (!empty($attendantErrors)) {
                $errors[] = $attendantErrors[0];
            }
        }

        return $errors;
    }
}
