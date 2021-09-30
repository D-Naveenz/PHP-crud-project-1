<?php
require_once "config.php";

class InPatient extends Patient
{
    // private variables
    public string $dob;

    // public variables
    public string $pc_doc;
    public string $bed_id;

    // private variables
    private ?string $add_date;
    private ?string $add_time;
    private ?string $dis_date;
    private ?string $dis_time;

    function __construct(string $pid)
    {
        parent::__construct(false, $pid);
        //Checking if the patient is in the database
        $result = $this->getInPatientData($pid);
        if ($result) {
            $this->dob = $result['DOB'];
            $this->add_date = $result['Admitted_Date'];
            $this->add_time = $result['Admitted_Time'];
            $this->dis_date = $result['Discharge_Date'];
            $this->dis_time = $result['Discharge_Time'];
            $this->pc_doc = $result['PC_Doctor'];
            $this->bed_id = $result['Bed_ID'];
        }
        else {
            $this->dob = "";
            $this->add_date = null;
            $this->add_time = null;
            $this->dis_date = null;
            $this->dis_time = null;
            $this->pc_doc = "";
            $this->bed_id = "";
        }
    }

    // getters and setters
    /**
     * @return string|null
     */
    public function getAddDate(): ?string
    {
        return $this->add_date;
    }

    /**
     * @param string $add_date
     */
    public function setAddDate(string $add_date): void
    {
        $this->add_date = isDateTimeStrNull($add_date);
    }

    /**
     * @return string|null
     */
    public function getAddTime(): ?string
    {
        return $this->add_time;
    }

    /**
     * @param string $add_time
     */
    public function setAddTime(string $add_time): void
    {
        $this->add_time = isDateTimeStrNull($add_time);
    }

    /**
     * @return string|null
     */
    public function getDisDate(): ?string
    {
        return $this->dis_date;
    }

    /**
     * @param string $dis_date
     */
    public function setDisDate(string $dis_date): void
    {
        $this->dis_date = isDateTimeStrNull($dis_date);
    }

    /**
     * @return string|null
     */
    public function getDisTime(): ?string
    {
        return $this->dis_time;
    }

    /**
     * @param string $dis_time
     */
    public function setDisTime(string $dis_time): void
    {
        $this->dis_time = isDateTimeStrNull($dis_time);
    }

    public function insertToDb(): bool
    {
        $database = createMySQLConn();
        if ($this->dob != "" && $this->pc_doc != "" && $this->bed_id != "") {
            $sql = "INSERT INTO `in_patient` (`Patient_ID`, `DOB`, `Admitted_Date`, `Admitted_Time`, `Discharge_Date`, `Discharge_Time`, `PC_Doctor`, `Bed_ID`) VALUES (?, ?, ?, ?, ?, ?, ?, ?);";
            $sql_statement = $database->prepare($sql);
            $sql_statement->bind_param("ssssssss", $this->patient_id, $this->dob, $this->add_date, $this->add_time, $this->dis_date, $this->dis_time, $this->pc_doc, $this->bed_id);
            $sql_statement->execute();
            $sql_statement->close();

            return true;
        }
        return false;
    }

    public static function getFreeBeds(): array
    {
        return AvailableBeds();
    }

    private function getInPatientData($patient_id): array|null
    {
        $database = createMySQLConn();
        $result = $database->query("SELECT * FROM `in_patient` WHERE `Patient_ID` = '$patient_id'");
        if ($result->num_rows == 1) {
            return $result->fetch_assoc();
        }
        elseif ($result->num_rows > 1) {
            die("In-Patient table has many results with the same id: $patient_id! | rows: $result->num_rows");
        }
        return null;
    }
}