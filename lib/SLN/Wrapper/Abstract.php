<?php

abstract class SLN_Wrapper_Abstract
{
    protected $object;

    function __construct($object)
    {
        $this->object = $object;
    }

    function getId()
    {
        return $this->object->ID;
    }
}