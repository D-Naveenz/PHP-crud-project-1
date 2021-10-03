<?php
require_once "../core/config.php";

class Emergency
{
    public string $patient_id;
    public string $fname;
    public string $lname;
    public string $relation;
    public string $address;
    public int $contact;

    function __construct($id, $fname, $lname, $relation, $address, $contact)
    {
        $this->patient_id = $id;
        $this->fname = $fname;
        $this->lname = $lname;
        $this->relation = $relation;
        $this->address = $address;
        $this->contact = $contact;
    }

    public function insertToDb() {
        $database = createMySQLConn();
        $sql = "INSERT INTO emergency_contact (Patient_ID, FName, LName, Relationship, Address, ContactNo) VALUES (?, ?, ?, ?, ?, ?)";
        $sql_statement = $database->prepare($sql);
        // bind param with references : https://www.php.net/manual/en/language.references.whatare.php
        $sql_statement->bind_param("sssssi", $this->patient_id, $this->fname, $this->lname, $this->relation, $this->address, $this->contact);
        // Execution
        $sql_statement->execute();
        $sql_statement->close();
    }

    public function updateRow() {
        $database = createMySQLConn();
        $sql = "UPDATE emergency_contact SET LName = ?, Address = ?, ContactNo = ? WHERE emergency_contact.Patient_ID = ? AND emergency_contact.FName = ? AND emergency_contact.Relationship = ?";
        $sql_statement = $database->prepare($sql);
        // bind param with references : https://www.php.net/manual/en/language.references.whatare.php
        $sql_statement->bind_param("ssisss", $this->lname, $this->address, $this->contact, $this->patient_id, $this->fname, $this->relation);
        // Execution
        $sql_statement->execute();
        $sql_statement->close();
    }

    public static function deleteRow($id, $fname, $relation) {
        $database = createMySQLConn();
        $sql = "DELETE FROM emergency_contact WHERE emergency_contact.Patient_ID = ? AND emergency_contact.FName = ? AND emergency_contact.Relationship = ?";
        $sql_statement = $database->prepare($sql);
        // bind param with references : https://www.php.net/manual/en/language.references.whatare.php
        $sql_statement->bind_param("sss", $id, $fname, $relation);
        // Execution
        $sql_statement->execute();
        $sql_statement->close();
    }

    public static function deleteAll($id) {
        $database = createMySQLConn();
        $sql = "DELETE FROM emergency_contact WHERE emergency_contact.Patient_ID = ?";
        $sql_statement = $database->prepare($sql);
        // bind param with references : https://www.php.net/manual/en/language.references.whatare.php
        $sql_statement->bind_param("s", $id);
        // Execution
        $sql_statement->execute();
        $sql_statement->close();
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