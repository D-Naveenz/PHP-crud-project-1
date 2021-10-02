<?php
require_once "PatientRealation.php";

use JetBrains\PhpStorm\Pure;

class Emergency extends PatientRealation
{
    #[Pure] private function __construct($id, $fname, $lname, $relation, $address, $contact)
    {
        parent::__construct($id, $fname, $lname, $relation, $address, $contact);
        $this->table = "emergency_contact";
    }

    public static function findAll($id): ?array
    {
        $database = createMySQLConn();
        $result = $database->query("SELECT * FROM emergency_contact WHERE `Patient_ID` = '$id'");
        if ($result) {
            // output data of each row
            $objArray = array();
            while ($row = $result->fetch_assoc()) {
                $obj = new Emergency($row['Patient_ID'], $row['FName'], $row['LName'], $row['Relationship'], $row['Address'], $row['ContactNo']);
                array_push($objArray, $obj);
            }
            return $objArray;
        }
        return null;
    }
}