<?php

final class Doctor extends Medical
{

    public string $dea;
    public string $special;

    function __construct($id = "")
    {
        parent::__construct($id);
        //Checking if the employee is in the database
        $result = $this->retrieveFromDB($id);
        if ($result) {
            $this->dea = $result['DEA'];
            $this->special = $result['Area_of_Speciality'];
            $this->exists_in_db = true;
        }
        else {
            $this->dea = "";
            $this->special = "";
            $this->exists_in_db = false;
        }
    }

    private function retrieveFromDB($id): array|null
    {
        $database = createMySQLConn();
        $result = $database->query("SELECT * FROM doctor WHERE `EmpNo` = '$id'");
        if ($result->num_rows == 1) {
            return $result->fetch_assoc();
        }
        elseif ($result->num_rows > 1) {
            die("Doctor table has many results with the same id: $id! | rows: $result->num_rows");
        }
        return null;
    }

    public function insertToDb() {
        $database = createMySQLConn();
        parent::insertToDb();
        $sql = "INSERT INTO doctor (EmpNo, DEA, Area_of_Speciality) VALUES (?, ?, ?)";
        $sql_statement = $database->prepare($sql);
        // bind param with references : https://www.php.net/manual/en/language.references.whatare.php
        $sql_statement->bind_param("sss", $this->emp_no, $this->dea, $this->special);
        // Execution
        $sql_statement->execute();
        $sql_statement->close();
    }

    public function updateRow(){
        $database = createMySQLConn();
        parent::updateRow();
        $sql = "UPDATE doctor SET DEA = ?, Area_of_Speciality = ? WHERE doctor.EmpNo = ?";
        $sql_statement = $database->prepare($sql);
        // bind param with references : https://www.php.net/manual/en/language.references.whatare.php
        $sql_statement->bind_param("sss", $this->dea, $this->special, $this->emp_no);
        // Execution
        $sql_statement->execute();
        $sql_statement->close();
    }

    public function deleteRow(): bool
    {
        $database = createMySQLConn();
        $sql = "DELETE FROM doctor WHERE doctor.EmpNo = ?";
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