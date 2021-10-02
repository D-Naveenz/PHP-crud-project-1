<?php
require_once "../core/config.php";

class Record
{

    public string $patient_id;
    public string $nurse_id;
    public string $date;
    public string $time;
    public float $weight;
    public string $pressure;
    public int $pulse;
    public int $temperature;
    private ?array $symptoms;

    private function __construct($pId, $nId, $date, $time, $weight, $pressure, $pulse, $temperature)
    {
        $this->patient_id = $pId;
        $this->nurse_id = $nId;
        $this->date = $date;
        $this->time = $time;
        $this->weight = $weight;
        $this->pressure = $pressure;
        $this->pulse = $pulse;
        $this->temperature = $temperature;
        $this->symptoms = $this->sympFind($pId, $date, $time);
    }

    /**
     * @return array
     */
    public function getSymptoms(): array
    {
        return $this->symptoms;
    }

    /**
     * @param array $symptoms
     */
    public function setSymptoms(string $symptoms_str): void
    {
        $symptoms = explode(",", $symptoms_str);
        $trimmed_symps = array();
        foreach ($symptoms as $symp) {
            array_push($trimmed_symps, trim($symp));
        }

        $this->symptoms = $trimmed_symps;
    }

    // Relation: patient_records
    public static function findAll($id): ?array
    {
        $database = createMySQLConn();
        $result = $database->query("SELECT * FROM patient_record WHERE `Patient_ID` = '$id'");
        if ($result) {
            // output data of each row
            $objArray = array();
            while ($row = $result->fetch_assoc()) {
                $obj = new Record($row['Patient_ID'], $row['Nurse_ID'], $row['Date'], $row['Time'], $row['Weight'], $row['Blood_Pressure'], $row['Pulse'], $row['Temperature']);
                array_push($objArray, $obj);
            }
            return $objArray;
        }
        return null;
    }

    public function insertToDb() {
        $database = createMySQLConn();
        $sql = "INSERT INTO patient_record (Patient_ID, Nurse_ID, Date, Time, Weight, Blood_Preasure, Pulse, Temperature) VALUES (?,?,?,?,?,?,?,?)";
        $sql_statement = $database->prepare($sql);
        // bind param with references : https://www.php.net/manual/en/language.references.whatare.php
        $sql_statement->bind_param("ssssdsii", $this->patient_id, $this->nurse_id, $this->date, $this->time, $this->weight, $this->pressure, $this->pulse, $this->temperature);
        // Execution
        $sql_statement->execute();
        $sql_statement->close();
        $this->sympInsertAll();
    }

    public function updateRow() {
        $database = createMySQLConn();
        $sql = "UPDATE patient_record SET Nurse_ID = ?, Weight = ?, Blood_Preasure = ?, Pulse = ?, Temperature = ? WHERE patient_record.Patient_ID = ? AND patient_record.Date = ? AND patient_record.Time = ?";
        $sql_statement = $database->prepare($sql);
        // bind param with references : https://www.php.net/manual/en/language.references.whatare.php
        $sql_statement->bind_param("sdsiisss", $this->nurse_id, $this->weight, $this->pressure, $this->pulse, $this->temperature, $this->patient_id, $this->date, $this->time);
        // Execution
        $sql_statement->execute();
        $sql_statement->close();
        $this->sympUpdate();
    }

    public function deleteRow() {
        $database = createMySQLConn();
        $sql = "DELETE FROM patient_record WHERE patient_record.Patient_ID = ? AND patient_record.Date = ? AND patient_record.Time = ?";
        $sql_statement = $database->prepare($sql);
        // bind param with references : https://www.php.net/manual/en/language.references.whatare.php
        $sql_statement->bind_param("s", $this->patient_id, $this->date, $this->time);
        // Execution
        $sql_statement->execute();
        $sql_statement->close();
    }

    //Relation: patient_symptoms
    private function sympFind($pId, $date, $time): ?array
    {
        $database = createMySQLConn();
        $result = $database->query("SELECT * FROM patient_symptoms WHERE `Patient_ID` = '$pId' AND `Date` = $date AND `Time` = $time");
        if ($result) {
            // output data of each row
            $objArray = array();
            while ($row = $result->fetch_assoc()) {
                array_push($objArray, $row['Symptom']);
            }
            return $objArray;
        }
        return null;
    }

    private function sympInsertAll() {
        $database = createMySQLConn();
        foreach ($this->symptoms as $row) {
            $sql = "INSERT INTO patient_symptoms (Patient_ID, Date, Time, Symptom) VALUES (?,?,?,?)";
            $sql_statement = $database->prepare($sql);
            // bind param with references : https://www.php.net/manual/en/language.references.whatare.php
            $sql_statement->bind_param("ssss", $this->patient_id, $this->date, $this->time, $row);
            // Execution
            $sql_statement->execute();
            $sql_statement->close();
        }
    }

    private function sympUpdate() {
        $database = createMySQLConn();
        // clear all records related to the patient record
        $this->sympDeleteAll();
        //insert new values for symptoms
        $this->sympInsertAll();
    }

    public function sympDeleteAll() {
        $database = createMySQLConn();
        $sql = "DELETE FROM patient_symptoms WHERE patient_symptoms.Patient_ID = ? AND patient_symptoms.Date = ? AND patient_symptoms.Time = ?";
        $sql_statement = $database->prepare($sql);
        // bind param with references : https://www.php.net/manual/en/language.references.whatare.php
        $sql_statement->bind_param("s", $this->patient_id, $this->date, $this->time);
        // Execution
        $sql_statement->execute();
        $sql_statement->close();
    }
}