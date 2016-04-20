<?php

class SLN_Helper_Availability_ErrorHelper
{
    public static function doLimitParallelBookings(DateTime $time)
    {
        SLN_Plugin::addLog(
            __CLASS__.sprintf(' - limit of parallels bookings at date(%s)', $time->format('Ymd H:i'))
        );

        return array(
            __('Limit of parallels bookings at ', 'salon-booking-system').$time->format('H:i'),
        );
    }

    public static function doServiceNotAvailableOnDate(SLN_Wrapper_Service $service, DateTime $time)
    {
        SLN_Plugin::addLog(
            __CLASS__.sprintf(' - service %s by date(%s) not available', $service, $time->format('Ymd H:i'))
        );

        return array(
            __('This service is unavailable ', 'salon-booking-system').'<br/>'.
            __('Availability: ', 'salon-booking-system').$service->getNotAvailableString(),
        );
    }

    public static function doServiceFull(SLN_Wrapper_Service $service, DateTime $time)
    {
        SLN_Plugin::addLog(
            __CLASS__.sprintf(' - service %s by date(%s) busy', $service, $time->format('Ymd H:i'))
        );

        return array(
            sprintf(__('The service for %s is currently full', 'salon-booking-system'), $time->format('H:i')),
        );
    }

    public static function doServiceAllAttendantsBusy(SLN_Wrapper_Service $service, DateTime $time)
    {
        SLN_Plugin::addLog(
            __CLASS__.sprintf(
                ' - all of the assistants for service %s by date(%s) are busy',
                $service,
                $time->format('Ymd H:i')
            )
        );

        return array(
            __('No assistants available for this service at ', 'salon-booking-system').$time->format('H:i'),
        );
    }

    public static function doAttendantNotAvailable(SLN_Wrapper_Attendant $attendant, DateTime $time)
    {
        SLN_Plugin::addLog(
            __CLASS__.sprintf(' - attendant %s by date(%s) not available', $attendant, $time->format('Ymd H:i'))
        );

        return array(
            __('This attendant is unavailable ', 'salon-booking-system').$attendant->getNotAvailableString(),
        );
    }
    public static function doAttendantBusy(SLN_Wrapper_Attendant $attendant, DateTime $time)
    {
        SLN_Plugin::addLog(
            __CLASS__.sprintf(' - attendant %s by date(%s) busy', $attendant, $time->format('Ymd H:i'))
        );

        return array(
            sprintf(__('This assistant is full at %s', 'salon-booking-system'), $time->format('H:i')),
        );
    }
}