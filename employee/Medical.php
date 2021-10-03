<?php
require_once "../core/config.php";

class Medical extends Employee
{

    public string $mc_reg_no;
    public string $joined;
    public string $medical_type;

    private ?string $resigned;

    function __construct($id = "")
    {
        parent::__construct($id);
        //Checking if the employee is in the database
        $result = $this->retrieveFromDB($id);
        if ($result) {
            $this->mc_reg_no = $result['MCRegNo'];
            $this->joined = $result['JoinedDate'];
            $this->resigned = $result['ResignedDate'];
            $this->medical_type = $result['Type'];
        }
        else {
            $this->mc_reg_no = "";
            $this->joined = "";
            $this->resigned = null;
            $this->medical_type = "";
        }
    }

    /**
     * @return string|null
     */
    public function getResigned(): ?string
    {
        return $this->resigned;
    }

    /**
     * @param string $resigned
     */
    public function setResigned(string $resigned): void
    {
        $this->resigned = isDateTimeStrNull($resigned);
    }

    public function isDoctor(): bool
    {
        return $this->medical_type == "Doctor";
    }

    private function retrieveFromDB($id): array|null
    {
        $database = createMySQLConn();
        $result = $database->query("SELECT * FROM `medicalstaff` WHERE `EmpNo` = '$id'");
        if ($result->num_rows == 1) {
            return $result->fetch_assoc();
        }
        elseif ($result->num_rows > 1) {
            die("Medical Staff table has many results with the same id: $id! | rows: $result->num_rows");
        }
        return null;
    }

    public function insertToDb() {
        $database = createMySQLConn();
        parent::insertToDb();
        $sql = "INSERT INTO medicalstaff (EmpNo, MCRegNo, JoinedDate, ResignedDate, Type) VALUES (?, ?, ?, ?, ?)";
        $sql_statement = $database->prepare($sql);
        // bind param with references : https://www.php.net/manual/en/language.references.whatare.php
        $sql_statement->bind_param("sssss", $this->emp_no, $this->mc_reg_no, $this->joined, $this->resigned, $this->medical_type);
        // Execution
        $sql_statement->execute();
        $sql_statement->close();
    }

    public function updateRow(){
        $database = createMySQLConn();
        parent::updateRow();
        $sql = "UPDATE medicalstaff SET MCRegNo = ?, JoinedDate = ?, ResignedDate = ?, Type = ? WHERE medicalstaff.EmpNo = ?";
        $sql_statement = $database->prepare($sql);
        // bind param with references : https://www.php.net/manual/en/language.references.whatare.php
        $sql_statement->bind_param("sssss", $this->mc_reg_no, $this->joined, $resigned, $this->medical_type, $this->emp_no);
        // Execution
        $sql_statement->execute();
        $sql_statement->close();
    }

    public function deleteRow(): bool
    {
        $database = createMySQLConn();
        $sql = "DELETE FROM medicalstaff WHERE medicalstaff.EmpNo = ?";
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