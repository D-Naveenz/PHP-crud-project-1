<?php

final class Cleaner extends NonMedical
{

    public int $contract;
    public string $start;
    public string $end;

    function __construct($id = "")
    {
        parent::__construct($id);
        //Checking if the employee is in the database
        $result = $this->retrieveFromDB($id);
        if ($result) {
            $this->contract = $result['ContractNo'];
            $this->start = $result['Start_date'];
            $this->end = $result['End_date'];
        }
        else {
            $this->contract = 0;
            $this->start = "";
            $this->end = "";
        }
    }

    private function retrieveFromDB($id): array|null
    {
        $database = createMySQLConn();
        $result = $database->query("SELECT * FROM cleaner WHERE `EmpNo` = '$id'");
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
        $sql = "INSERT INTO cleaner (EmpNo, ContractNo, Start_date, End_date) VALUES (?, ?, ?, ?)";
        $sql_statement = $database->prepare($sql);
        // bind param with references : https://www.php.net/manual/en/language.references.whatare.php
        $sql_statement->bind_param("siss", $this->emp_no, $this->contract, $this->start, $this->end);
        // Execution
        $sql_statement->execute();
        $sql_statement->close();
    }

    public function updateRow(){
        $database = createMySQLConn();
        parent::updateRow();
        $sql = "UPDATE cleaner SET ContractNo = ?, Start_date = ?, End_date = ? WHERE cleaner.EmpNo = ?";
        $sql_statement = $database->prepare($sql);
        // bind param with references : https://www.php.net/manual/en/language.references.whatare.php
        $sql_statement->bind_param("isss", $this->contract, $this->start, $this->end, $this->emp_no);
        // Execution
        $sql_statement->execute();
        $sql_statement->close();
    }

    public function deleteRow(): bool
    {
        $database = createMySQLConn();
        $sql = "DELETE FROM cleaner WHERE cleaner.EmpNo = ?";
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