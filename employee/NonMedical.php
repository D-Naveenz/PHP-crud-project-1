<?php
require_once "../core/config.php";

class NonMedical extends Employee
{

    public string $non_medi_type;

    function __construct($id = "")
    {
        parent::__construct($id);
        //Checking if the employee is in the database
        $result = $this->retrieveFromDB($id);
        if ($result) {
            $this->non_medi_type = $result['Type'];
        }
        else {
            $this->non_medi_type = "";
        }
    }

    public function isCleaner(): bool
    {
        return $this->non_medi_type == "Cleaner";
    }

    private function retrieveFromDB($id): array|null
    {
        $database = createMySQLConn();
        $result = $database->query("SELECT * FROM `nonmedical` WHERE `EmpNo` = '$id'");
        if ($result->num_rows == 1) {
            return $result->fetch_assoc();
        }
        elseif ($result->num_rows > 1) {
            die("Non-medical Staff table has many results with the same id: $id! | rows: $result->num_rows");
        }
        return null;
    }

    public function insertToDb() {
        $database = createMySQLConn();
        parent::insertToDb();
        $sql = "INSERT INTO nonmedical (EmpNo, Type) VALUES (?, ?)";
        $sql_statement = $database->prepare($sql);
        // bind param with references : https://www.php.net/manual/en/language.references.whatare.php
        $sql_statement->bind_param("ss", $this->emp_no, $this->non_medi_type);
        // Execution
        $sql_statement->execute();
        $sql_statement->close();
    }

    public function updateRow(){
        $database = createMySQLConn();
        parent::updateRow();
        $sql = "UPDATE nonmedical SET Type = ? WHERE nonmedical.EmpNo = ?";
        $sql_statement = $database->prepare($sql);
        // bind param with references : https://www.php.net/manual/en/language.references.whatare.php
        $sql_statement->bind_param("ss", $this->type, $this->emp_no);
        // Execution
        $sql_statement->execute();
        $sql_statement->close();
    }

    public function deleteRow(): bool
    {
        $database = createMySQLConn();
        $sql = "DELETE FROM nonmedical WHERE nonmedical.EmpNo = ?";
        $sql_statement = $database->prepare($sql);
        // bind param with references : https://www.php.net/manual/en/language.references.whatare.php
        $sql_statement->bind_param("s", $this->emp_no);
        // Execution
        if ($sql_statement->execute()) {
            parent::deleteRow();
            $sql_statement->close();
            return true;
        }
        $sql_statement->close();
        return false;
    }
}