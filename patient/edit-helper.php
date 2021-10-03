<?php
require_once "InPatient.php";
require_once "OutPatient.php";

// Post requests from In / Out patients
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['pType'] == 'Inpatient') {
        $temp_p = new InPatient($_POST['pId'], $_SESSION['patient']);
        // Assigning basic information
        $temp_p->name = $_POST['pName'];
        $temp_p->setType($_POST['pType']);
        // Assigning in-patient information
        $temp_p->dob = $_POST['pDOB'];
        $temp_p->setAddDate($_POST['pAddDate']);
        $temp_p->setAddTime($_POST['pAddTime']);
        $temp_p->setDisDate($_POST['pDisDate']);
        $temp_p->setDisTime($_POST['pDisTime']);
        $temp_p->pc_doc = $_POST['pPcDoc'];
        $temp_p->bed_id = $_POST['pBed'];
    } else {
        $temp_p = new OutPatient($_POST['pId'], $_SESSION['patient']);
        // Assigning basic information
        $temp_p->name = $_POST['pName'];
        $temp_p->setType($_POST['pType']);
        // Assigning out-patient information
        $temp_p->setArrDate($_POST['pArrDate']);
        $temp_p->setArrTime($_POST['pArrTime']);
    }

    if (isset($_POST['btnPtCreate'])) {
        // Submitting the 'create' form inputs
        $result = $temp_p->insertToDb();
        if ($result) $_SESSION['patient'] = $temp_p;
    } elseif (isset($_POST['btnPtUpdate'])) {
        // Submitting the 'update' form inputs
        $result = $temp_p->updateRow();
        if ($result) $_SESSION['patient'] = $temp_p;
    }

    // redirect to previous page
    header("Location: " . $_SESSION['previous_page']);
}