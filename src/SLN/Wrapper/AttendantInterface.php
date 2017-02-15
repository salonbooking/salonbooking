<?php

interface SLN_Wrapper_AttendantInterface
{
    function getNotAvailableOn($key);
    function getEmail();
    function getPhone();
    function isNotAvailableOnDate(SLN_DateTime $date);
    function getAvailabilityItems();
    function getHolidayItems();
    function getNotAvailableString();
    function getServicesIds();
    function getServices();
    function hasService(SLN_Wrapper_ServiceInterface $service);
    function hasServices($services);
    function hasAllServices();
    function getGoogleCalendar();
    function getName();
    function getContent();
    function getMeta($key);
}
