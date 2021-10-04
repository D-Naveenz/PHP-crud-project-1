<?php
require_once "Employee.php";
require_once "Medical.php";
require_once "NonMedical.php";
require_once "Doctor.php";
require_once "Nurse.php";
require_once "Attendant.php";
require_once "Cleaner.php";

function load_employee($id): Doctor|Nurse|Attendant|Cleaner|null
{
    // form is preparing to update the employee record
    $employee = new Employee($id);
    if ($employee->isExistsInDb()) {
        if ($employee->isMedical()) {
            // create new medical object from the parent object
            $employee = new Medical($employee->getEmpNo());
            if ($employee->isDoctor()) {
                // create new doctor object from the parent object
                $employee = new Doctor($employee->getEmpNo());
            } else {
                // create new nurse object from the parent object
                $employee = new Nurse($employee->getEmpNo());
            }
        } else {
            // create new non-medical object with the same name
            $employee = new NonMedical($employee->getEmpNo());
            if ($employee->isCleaner()) {
                // create new cleaner object with the same name
                $employee = new Cleaner($employee->getEmpNo());
            } else {
                // create new attendant object with the same name
                $employee = new Attendant($employee->getEmpNo());
            }
        }
        return $employee;
    } else {
        // redirect to previous page
        header("Location: " . $_SESSION['previous_page']);
        return null;
    }
}