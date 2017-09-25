<?php

/**
 * @method SLN_Wrapper_Booking getOne($criteria = [])
 * @method SLN_Wrapper_Booking[] get($criteria = [])
 * @method SLN_Wrapper_Booking create($data = null)
 */
class SLN_Repository_BookingRepository extends SLN_Repository_AbstractWrapperRepository
{
    public function getWrapperClass()
    {
        return SLN_Wrapper_Booking::_CLASS;
    }

    protected function processCriteria($criteria)
    {
        if (isset($criteria['time@max'])) {
            $criteria['@wp_query']['meta_query'][] =
                array(
                    'key'     => '_sln_booking_time',
                    'value'   => $criteria['time@max']->format('H:i'),
                    'compare' => '<=',
                );
            unset($criteria['time@max']);
        }

        if (isset($criteria['day'])) {
            $criteria['@wp_query']['meta_query'][] =
                array(
                    'key'     => '_sln_booking_date',
                    'value'   => $criteria['day']->format('Y-m-d'),
                    'compare' => isset($criteria['day_compare']) ? $criteria['day_compare'] : '=',
                );
            unset($criteria['day']);
        } else {
            if (isset($criteria['day@min'])) {
                $criteria['@wp_query']['meta_query'][] =
                    array(
                        'key'     => '_sln_booking_date',
                        'value'   => $criteria['day@min']->format('Y-m-d'),
                        'compare' => '>=',
                    );

                unset($criteria['day@min']);
            }
            if (isset($criteria['day@max'])) {
                $criteria['@wp_query']['meta_query'][] =
                    array(
                        'key'     => '_sln_booking_date',
                        'value'   => $criteria['day@max']->format('Y-m-d'),
                        'compare' => '<=',
                    );
                unset($criteria['day@max']);
            }
        }
        
        $criteria = apply_filters('sln.repository.booking.processCriteria', $criteria);

        return parent::processCriteria($criteria);
    }


    /**
     * @param SLN_Wrapper_Booking $a
     * @param SLN_Wrapper_Booking $b
     *
     * @return int
     */
    public static function sortAscByStartsAt($a, $b)
    {
        return (strtotime($a->getStartsAt()->format('Y-m-d H:i:s')) > strtotime(
            $b->getStartsAt()->format('Y-m-d H:i:s')
        ) ? 1 : -1);
    }

    /**
     * @param SLN_Wrapper_Booking $a
     * @param SLN_Wrapper_Booking $b
     *
     * @return int
     */
    public static function sortDescByStartsAt($a, $b)
    {
        return (strtotime($a->getStartsAt()->format('Y-m-d H:i:s')) >= strtotime(
            $b->getStartsAt()->format('Y-m-d H:i:s')
        ) ? -1 : 1);
    }


    /**
     * @todo add in src/SLN/Helper/Availability/AbstractDayBookings.php
     * @param $date
     * @param SLN_Wrapper_Booking|null $currentBooking
     *
     * @return array
     */
    public function getForAvailability($date, SLN_Wrapper_Booking $currentBooking = null)
    {
        $criteria       = array('day' => $date, 'foravailability' => true);
        $noTimeStatuses = SLN_Enum_BookingStatus::$noTimeStatuses;
        $ret            = array();
        foreach ($this->get($criteria) as $b) {
            if (empty($currentBooking) || $b->getId() != $currentBooking->getId()) {
                if ( ! $b->hasStatus($noTimeStatuses)) {
                    $ret[] = $b;
                }
            }
        }

        return $ret;
    }
}
