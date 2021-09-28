<?php
include "../core/config.php";

class Patient
{
    // constants
    const id_prefix = 'P';

    // private variables
    protected string $patient_id;
    protected string $patient_id_num;
    private string $type;
    private bool $in_patient;
    private bool $exists_in_db;

    // public variables
    public string $name;

    function __construct($patient_id = null) {
        if (!$patient_id) {
            $this->patient_id = next_id('patient', 'Patient_ID', self::id_prefix);
            $this->patient_id_num = substr($this->patient_id, strlen(self::id_prefix));
            $this->name = "";
            $this->in_patient = false;
            $this->exists_in_db = false;
        }
        else {
            //Checking if the patient is in the database
            $result = $this->patientExists($patient_id);
            var_dump($result);
            if ($result) {
                $this->patient_id = $patient_id;
                $this->patient_id_num = substr($this->patient_id, strlen(self::id_prefix));
                $this->name = $result['Name'];
                $this->type = $result['Type'];
                if ($this->type == "Inpatient") {
                    $this->in_patient = true;
                }
                else $this->in_patient = false;
                $this->exists_in_db = true;
            }
            $this->exists_in_db = false;
        }
    }

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
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
        if ($type == 'Inpatient') {
            $this->in_patient = true;
        }
        else $this->in_patient = false;
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

    private function patientExists($patient_id): ?array
    {
        $database = createMySQLConn();
        $result = $database->query("SELECT * FROM `patient` WHERE `Patient_ID` = '$patient_id' ORDER BY `Patient_ID`;");
        if ($result->num_rows == 1) {
            return $result->fetch_assoc();
        }
        return null;
    }

    public function insertToDb(): bool
    {
        $database = createMySQLConn();
        if ($this->name != "" && $this->type != ""){
            $sql = "INSERT INTO `patient` (`Patient_ID`, `Name`, `Type`) VALUES ('$this->patient_id', '$this->name', '$this->type');";

            if (mysqli_query($database, $sql)) {
                $this->exists_in_db = true;
                return true;
            }
        }
        return false;
    }
}

class InPatient extends Patient
{
    // private variables
    private array $available_beds;

    // public variables
    public string $dob;
    public string $add_date;
    public string $add_time;
    public string $dis_date;
    public string $dis_time;
    public string $pc_doc;
    public string $bed_id;

    function __construct(Patient $patient)
    {
        parent::__construct($patient->patient_id);
        $this->dob = "";
        $this->add_date = "";
        $this->add_time = "";
        $this->dis_date = "";
        $this->dis_time = "";
        $this->pc_doc = "";
        $this->bed_id = "";
        $this->AvailableBeds();
    }

    /**
     * @return array
     */
    public function getAvailableBeds(): array
    {
        return $this->available_beds;
    }

    public function insertToDb(): bool
    {
        $database = createMySQLConn();
        if ($this->dob != "" && $this->pc_doc != "" && $this->bed_id != ""){
            $sql = "INSERT INTO `in_patient` (`Patient_ID`, `DOB`, `Admitted_Date`, `Admitted_Time`, `Discharge_Date`, `Discharge_Time`, `PC_Doctor`, `Bed_ID`) 
VALUES ('$this->patient_id', '$this->dob', '$this->add_date', '$this->add_time', '$this->dis_date', '$this->dis_time', '$this->pc_doc', '$this->bed_id');";

            if (mysqli_query($database, $sql)) {
                //$this->exists_in_db = true;
                return true;
            }
        }
        return false;
    }

    private function AvailableBeds() {
        $database = createMySQLConn();
        $sql = "SELECT `Bed_ID` FROM `bed` WHERE `Availability` = 1";
        $result = $database->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            $count = 0;
            while($row = $result->fetch_assoc()) {
                $this->available_beds[$count++] = $row["Bed_ID"];
            }
        }
    }
}

class OutPatient extends Patient
{
    // private variables

    // public variables
    public string $arr_date;
    public string $arr_time;

    function __construct(Patient $patient)
    {
        parent::__construct($patient->patient_id);
        $this->arr_date = "";
        $this->arr_time = "";
    }

    public function insertToDb(): bool
    {
        $database = createMySQLConn();
        if ($this->arr_date != "" && $this->arr_time != ""){
            $sql = "INSERT INTO `out_patient` (`Patient_ID`, `Arrived_Date`, `Arrived_Time`) VALUES ('$this->patient_id', '$this->arr_date', '$this->arr_time');";

            if (mysqli_query($database, $sql)) {
                //$this->exists_in_db = true;
                return true;
            }
        }
        return false;
    }
}