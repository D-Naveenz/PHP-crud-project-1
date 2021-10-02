<?php
require_once "../core/config.php";

class Patient
{
    // constants
    const id_prefix = 'PT';

    // private variables
    protected string $patient_id_num;
    private string $type;
    private bool $in_patient;
    protected bool $exists_in_db;
    protected $patient_id;

    // public variables
    public string $name;

    function __construct($id = "")
    {
        if ($id != "" && $result = $this->retrieveFromDB($id)) {
            //Checking if the patient is in the database
            if ($result) {
                $this->patient_id = $id;
                $this->patient_id_num = substr($this->patient_id, strlen(self::id_prefix));
                $this->name = $result['Name'];
                $this->setType($result['Type']);
                $this->exists_in_db = true;
            }
            else $this->exists_in_db = false;
        } else {
            if ($id == "") {
                $this->patient_id = nextId('patient', 'Patient_ID', self::id_prefix);
            } else {
                $this->patient_id = $id;
            }
            // Initializing other variables
            $this->patient_id_num = substr($this->patient_id, strlen(self::id_prefix));
            $this->name = "";
            $this->setType("Outpatient");
            $this->exists_in_db = false;
        }
    }

    // Getters and setters
    /**
     * @return string
     */
    public function getPatientId(): string
    {
        return $this->patient_id;
    }

    /**
     * @return string
     */
    public function getPatientIdNum(): string
    {
        return $this->patient_id_num;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
        if ($type == 'Inpatient') {
            $this->in_patient = true;
        } else $this->in_patient = false;
    }

    /**
     * @return bool
     */
    public function isInPatient(): bool
    {
        return $this->in_patient;
    }

    /**
     * @return bool
     */
    public function isExistsInDb(): bool
    {
        return $this->exists_in_db;
    }

    private function retrieveFromDB($patient_id): ?array
    {
        $database = createMySQLConn();
        $result = $database->query("SELECT * FROM `patient` WHERE `Patient_ID` = '$patient_id'");
        if ($result->num_rows == 1) {
            return $result->fetch_assoc();
        }
        elseif ($result->num_rows > 1) {
            die("Patient table has many results with the same id: $patient_id! | rows: $result->num_rows");
        }
        return null;
    }

    public function insertToDb(): bool
    {
        $database = createMySQLConn();
        if ($this->name != "" && $this->type != "") {
            $sql = "INSERT INTO `patient` (`Patient_ID`, `Name`, `Type`) VALUES ('$this->patient_id', '$this->name', '$this->type');";

            if (mysqli_query($database, $sql)) {
                $this->exists_in_db = true;
                return true;
            }
        }
        return false;
    }

    public function updateRow(): bool
    {
        $database = createMySQLConn();
        if ($this->name != "" && $this->type != "") {
            $sql = "UPDATE `patient` SET `Name` = ?, `Type` = ? WHERE `patient`.`Patient_ID` = ?;";
            $sql_statement = $database->prepare($sql);
            // bind param with references : https://www.php.net/manual/en/language.references.whatare.php
            $sql_statement->bind_param("sss", $this->name, $this->type, $this->patient_id);
            // Execution
            $sql_statement->execute();
            $sql_statement->close();

            return true;
        }
        return false;
    }

    public function deleteRow(): bool
    {
        $database = createMySQLConn();
        $sql = "DELETE FROM `patient` WHERE `patient`.`Patient_ID` = '$this->patient_id'";

        if ($database->query($sql) === TRUE) {
            echo "Record deleted successfully";
            return true;
        }
        echo "Error deleting record: " . $database->error;
        return false;
    }
}