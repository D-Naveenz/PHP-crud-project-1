<?php
require_once "../core/Patient.php";
require_once "../core/InPatient.php";
require_once "../core/OutPatient.php";

// start the session
session_start();

// store session data with checking get requests
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // form is preparing to delete the patient record
    var_dump($_GET['id']);
    $temp_p = new Patient($_GET['id']);

    if ($temp_p->isInPatient()) {
        // create new in-patient object from the parent object
        $temp_p = new InPatient($temp_p->getPatientId());
    } else {
        // create new out-patient object with the same name
        $temp_p = new OutPatient($temp_p->getPatientId());
    }

    // delete the record
    $temp_p->deleteRow();

    // redirect to previous page
    header("Location: ".$_SESSION['previous_page']);
}
else {
    die("There is nothing to do anymore!");
}
