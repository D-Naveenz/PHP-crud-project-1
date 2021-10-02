<?php

class Emergency extends PatientRealation
{
    function __construct()
    {
        parent::__construct();
        $this->table = "emergency_contact";
    }
}