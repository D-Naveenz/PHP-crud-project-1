<?php
require_once "config.php";

class Patient
{
    // constants
    const id_prefix = 'PT';
    const cookie_name = 'Patient-Dump';

    // private variables
    protected string $patient_id_num;
    private string $type;
    private bool $in_patient;
    protected bool $exists_in_db;

    // public variables
    public string $name;

    protected function __construct(public bool $has_instance, protected $patient_id = "")
    {
        if (!$this->has_instance) {
            if ($patient_id == "") {
                // Initializing variables
                $this->patient_id = nextId('patient', 'Patient_ID', self::id_prefix);
                $this->patient_id_num = substr($this->patient_id, strlen(self::id_prefix));
                $this->name = "";
                $this->setType("Outpatient");
                $this->exists_in_db = false;
            } else {
                //Checking if the patient is in the database
                $result = $this->patientExists($patient_id);
                if ($result) {
                    $this->patient_id_num = substr($this->patient_id, strlen(self::id_prefix));
                    $this->name = $result['Name'];
                    $this->type = $result['Type'];
                    if ($this->type == "Inpatient") {
                        $this->in_patient = true;
                    } else $this->in_patient = false;
                    $this->exists_in_db = true;
                }
                else $this->exists_in_db = false;
            }
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
     * @return mixed|string
     */
    public function getType(): mixed
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

    public static function getInstance(bool $check_instance, $patient_id = ""): Patient
    {
        if ($check_instance) {
            // get instance from the cookie
            if (isset($_COOKIE[self::cookie_name])) {
                $obj = unserialize($_COOKIE[self::cookie_name]);
                if ($obj) {
                    $obj->has_instance = true;
                    return $obj;
                }
            }
        }
        // forcing destroy the cookie for get rid of errors
        self::destroyInstance();
        return new Patient(false, $patient_id);
    }

    protected static function destroyInstance(): void
    {
        setcookie(self::cookie_name, "", time() - 15 * 60);
    }

    private function dumpInstance(): void
    {
        // Initialize a cookie before loading the html head
        setcookie(self::cookie_name, serialize($this), time() + 15 * 60); // 3600 = 1h
    }

    private function patientExists($patient_id): ?array
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
        $this->dumpInstance();
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
}