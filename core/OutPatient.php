<?php
require_once "config.php";

class OutPatient extends Patient
{
// private variables

    // private variables
    private ?string $arr_date;
    private ?string $arr_time;

    function __construct(string $pid)
    {
        parent::__construct(false, $pid);
        //Checking if the patient is in the database
        $result = $this->getOutPatientData($pid);
        if ($result) {
            $this->arr_date = $result['Arrived_Date'];
            $this->arr_time = $result['Arrived_Time'];
        }
        else {
            $this->arr_date = null;
            $this->arr_time = null;
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
            $sql = "INSERT INTO `out_patient` (`Patient_ID`, `Arrived_Date`, `Arrived_Time`) VALUES (?,?,?);";
            $sql_statement = $database->prepare($sql);
            $sql_statement->bind_param("sss", $id, $arr_date, $arr_time);
            $id = $this->patient_id;
            $arr_date = $this->arr_date;
            $arr_time = $this->arr_time;
            $sql_statement->execute();
            $sql_statement->close();

            return true;
        }
        return false;
    }

    private function getOutPatientData($patient_id): array|null
    {
        $database = createMySQLConn();
        $result = $database->query("SELECT * FROM `out_patient` WHERE `Patient_ID` = '$patient_id'");
        if ($result->num_rows == 1) {
            return $result->fetch_assoc();
        }
        elseif ($result->num_rows > 1) {
            die("Out-Patient table has many results with the same id: $patient_id! | rows: $result->num_rows");
        }
        return null;
    }
}