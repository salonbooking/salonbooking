<?php

interface SLN_Wrapper_ServiceInterface
{
    function getPrice();
    function getUnitPerHour();
    function getDuration();
    function isSecondary();
    function isExclusive();
    function getPosOrder();
    function getExecOrder();
    function getAttendantsIds();
    function getAttendants();
    function isAttendantsEnabled();
    function isNotAvailableOnDate(SLN_DateTime $date);
    function getNotAvailableString();
    function getName();
    function getContent();
    function getAvailabilityItems();
    function getMeta($key);
}
