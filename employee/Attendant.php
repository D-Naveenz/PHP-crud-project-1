<?php

final class Attendant extends NonMedical
{

    public float $hr_rate;

    function __construct($id = "")
    {
        parent::__construct($id);
        //Checking if the employee is in the database
        $result = $this->retrieveFromDB($id);
        if ($result) {
            $this->hr_rate = $result['HourlyRate'];
        }
        else {
            $this->hr_rate = "";
        }
    }

    private function retrieveFromDB($id): array|null
    {
        $database = createMySQLConn();
        $result = $database->query("SELECT * FROM `attendant` WHERE `EmpNo` = '$id'");
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
        $sql = "INSERT INTO attendant (EmpNo, HourlyRate) VALUES (?, ?)";
        $sql_statement = $database->prepare($sql);
        // bind param with references : https://www.php.net/manual/en/language.references.whatare.php
        $sql_statement->bind_param("ss", $this->emp_no, $this->hr_rate);
        // Execution
        $sql_statement->execute();
        $sql_statement->close();
    }

    public function updateRow(){
        $database = createMySQLConn();
        parent::updateRow();
        $sql = "UPDATE attendant SET HourlyRate = ? WHERE attendant.EmpNo = ?";
        $sql_statement = $database->prepare($sql);
        // bind param with references : https://www.php.net/manual/en/language.references.whatare.php
        $sql_statement->bind_param("ss", $this->hr_rate, $this->emp_no);
        // Execution
        $sql_statement->execute();
        $sql_statement->close();
    }

    public function deleteRow(): bool
    {
        $database = createMySQLConn();
        $sql = "DELETE FROM attendant WHERE attendant.EmpNo = ?";
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