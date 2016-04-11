<?php

class SLN_Repository_BookingRepository extends SLN_Repository_AbstractWrapperRepository
{
    public function getWrapperClass()
    {
        return SLN_Wrapper_Booking::_CLASS;
    }

    protected function processCriteria($criteria)
    {
        if (isset($criteria['day'])) {
            $criteria['@wp_query']['meta_query'][] =
                array(
                    'key' => '_sln_booking_date',
                    'value' => $criteria['day']->format('Y-m-d'),
                    'compare' => '=',
                );
        }

        return parent::processCriteria($criteria);
    }
}