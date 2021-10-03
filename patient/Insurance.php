<?php
require_once "../core/config.php";

class Insurance
{
    private string $patient_id;
    public string $company;
    public string $branch;
    public string $address;
    public int $contact;

    function __construct($id)
    {
        $this->patient_id = $id;
        $this->company = "";
        $this->branch = "";
        $this->address = "";
        $this->contact = 0;
    }

    public static function Build($id) {
        $database = createMySQLConn();
        $result = $database->query("SELECT * FROM `patient_insurance` WHERE `Patient_ID` = '$id'");
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $target = new Insurance($row['Patient_ID']);
            $target->company = $row['Company_Name'];
            $target->branch = $row['Branch_Name'];
            $target->address = $row['Address'];
            $target->contact = $row['ContactNo'];

            return $target;
        }
        elseif ($result->num_rows > 1) {
            die("Insurance table has many results with the same id: $id! | rows: $result->num_rows");
        }
        return null;
    }

    public function insertToDb()
    {
        $database = createMySQLConn();
        $sql = "INSERT INTO `patient_insurance` (Patient_ID, Company_Name, Branch_Name, Address, ContactNo) VALUES (?, ?, ?, ?, ?)";
        $sql_statement = $database->prepare($sql);
        // bind param with references : https://www.php.net/manual/en/language.references.whatare.php
        $sql_statement->bind_param("sssss", $this->patient_id, $this->company, $this->branch, $this->address, $this->contact);
        // Execution
        $sql_statement->execute();
        $sql_statement->close();
    }

    public function updateRow()
    {
        $database = createMySQLConn();
        $sql = "UPDATE `patient_insurance` SET `Company_Name` = ?, `Branch_Name` = ?, `Address` = ?, `ContactNo` = ? WHERE `patient_insurance`.`Patient_ID` = ?";
        $sql_statement = $database->prepare($sql);
        // bind param with references : https://www.php.net/manual/en/language.references.whatare.php
        $sql_statement->bind_param("sssss", $this->company, $this->branch, $this->address, $this->contact, $this->patient_id);
        // Execution
        $sql_statement->execute();
        $sql_statement->close();
    }

    public function deleteRow(): bool
    {
        $database = createMySQLConn();
        $sql = "DELETE FROM `patient_insurance` WHERE `patient_insurance`.`Patient_ID` = ?";
        $sql_statement = $database->prepare($sql);
        // bind param with references : https://www.php.net/manual/en/language.references.whatare.php
        $sql_statement->bind_param("s", $this->patient_id);
        // Execution
        $sql_statement->execute();
        $sql_statement->close();
        return false;
    }
}