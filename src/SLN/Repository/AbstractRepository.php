<?php

abstract class SLN_Repository_AbstractRepository
{
    abstract public function create($data = null);
    abstract public function getBindings();
    abstract public function get($criteria = array());
}
