<?php
require_once "config.php";

class OutPatient extends Patient
{
// private variables

    // private variables
    private ?string $arr_date;
    private ?string $arr_time;

    function __construct(Patient $patient)
    {
        parent::__construct(false, $patient->patient_id);
        if (!$this->exists_in_db) {
            $this->arr_date = null;
            $this->arr_time = null;
        }
        else {
            //Checking if the patient is in the database
            $result = $this->outPatientExists($patient->patient_id);
            if ($result) {
                $this->arr_date = $result['Arrived_Date'];
                $this->arr_time = $result['Arrived_Time'];
            }
        }
    }

    // getters and setters

    /**
     * @return string|null
     */
    public function getArrDate(): ?string
    {
        return $this->arr_date;
    }

    /**
     * @param string $arr_date
     */
    public function setArrDate(string $arr_date): void
    {
        $this->arr_date = isDateTimeStrNull($arr_date);
    }

    /**
     * @return string|null
     */
    public function getArrTime(): ?string
    {
        return $this->arr_time;
    }

    /**
     * @param string $arr_time
     */
    public function setArrTime(string $arr_time): void
    {
        $this->arr_time = isDateTimeStrNull($arr_time);
    }

    public function insertToDb(): bool
    {
        $database = createMySQLConn();
        if ($this->arr_date != "" && $this->arr_time != "") {
            $sql = "INSERT INTO `out_patient` (`Patient_ID`, `Arrived_Date`, `Arrived_Time`) VALUES ('$this->patient_id', '$this->arr_date', '$this->arr_time');";

            if (mysqli_query($database, $sql)) {
                // destroy the cookie if result is ok
                Patient::destroyInstance();
                return true;
            }
        }
        return false;
    }

    protected function outPatientExists($patient_id): array|null
    {
        $database = createMySQLConn();
        $result = $database->query("SELECT * FROM `out_patient` WHERE `Patient_ID` = '$patient_id'");
        if ($result->num_rows == 1) {
            return $result->fetch_assoc();
        }
        else if ($result->num_rows > 1) {
            die("Out-Patient table has many results with the same id: $patient_id! | rows: $result->num_rows");
        }
        return null;
    }
}