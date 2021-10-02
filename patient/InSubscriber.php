<?php

class InSubscriber extends PatientRealation
{
    function __construct()
    {
        parent::__construct();
        $this->table = "insurance_sub";
    }
}