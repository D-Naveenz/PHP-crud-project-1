<?php
require_once "../core/config.php";

class Employee
{
    // constants
    const id_prefix = 'E';

    // public variables
    public string $password;
    public string $name;
    public string $address;
    public int $contact;
    public string $status;
    public string $type;

    // private variables
    protected string $emp_no;
    private  string $emp_no_nums;
    protected bool $exists_in_db;

    function __construct($id = "")
    {
        if ($id != "" && $result = $this->retrieveFromDB($id)) {
            //Checking if the employee is in the database
            if ($result) {
                $this->emp_no = $id;
                $this->emp_no_nums = substr($this->emp_no, strlen(self::id_prefix));
                $this->password = $result['Password'];
                $this->name = $result['Name'];
                $this->address = $result['Address'];
                $this->contact = $result['ContactNo'];
                $this->status = $result['Working_status'];
                $this->type = $result['Type'];
                $this->exists_in_db = true;
            }
            else $this->exists_in_db = false;
        } else {
            if ($id == "") {
                $this->emp_no = nextId('employee', 'EmpNo', self::id_prefix);
            } else {
                $this->emp_no = "";
            }
            // Initializing other variables
            $this->emp_no_nums = substr($this->emp_no, strlen(self::id_prefix));
            $this->password = "";
            $this->name = "";
            $this->address = "";
            $this->contact = 0;
            $this->status = "";
            $this->type = "";
            $this->exists_in_db = false;
        }
    }

    // getters and setters

    /**
     * @return bool
     */
    public function isExistsInDb(): bool
    {
        return $this->exists_in_db;
    }

    /**
     * @return string
     */
    public function getEmpNo(): string
    {
        return $this->emp_no;
    }

    /**
     * @return string
     */
    public function getEmpNoNums(): string
    {
        return $this->emp_no_nums;
    }

    public function isMedical(): bool
    {
        return $this->type == "Medical";
    }

    private function retrieveFromDB($id): ?array
    {
        $database = createMySQLConn();
        $result = $database->query("SELECT * FROM `employee` WHERE `EmpNo` = '$id'");
        if ($result->num_rows == 1) {
            return $result->fetch_assoc();
        }
        elseif ($result->num_rows > 1) {
            die("Patient table has many results with the same id: $id! | rows: $result->num_rows");
        }
        return null;
    }

    public function insertToDb() {
        $database = createMySQLConn();
        $sql = "INSERT INTO employee (EmpNo, Name, Address, ContactNo, Working_status, Type) VALUES (?,?,?,?,?,?)";
        $sql_statement = $database->prepare($sql);
        // bind param with references : https://www.php.net/manual/en/language.references.whatare.php
        $sql_statement->bind_param("sssiss", $this->emp_no, $this->name, $this->address, $this->contact, $this->status, $this->type);
        // Execution
        $sql_statement->execute();
        $sql_statement->close();
    }

    public function updateRow() {
        $database = createMySQLConn();
        $sql = "UPDATE employee SET Name = ?, Address = ?, ContactNo = ?, Working_status = ?, Type = ? WHERE employee.EmpNo = ?";
        $sql_statement = $database->prepare($sql);
        // bind param with references : https://www.php.net/manual/en/language.references.whatare.php
        $sql_statement->bind_param("ssisss", $this->name, $this->address, $this->contact, $this->status, $this->type, $this->emp_no);
        // Execution
        $sql_statement->execute();
        $sql_statement->close();
    }

    public function deleteRow(): bool
    {
        $database = createMySQLConn();
        $sql = "DELETE FROM employee WHERE employee.EmpNo = '$this->emp_no'";

        if ($database->query($sql) === TRUE) {
            echo "Record deleted successfully";
            return true;
        }
        echo "Error deleting record: " . $database->error;
        return false;
    }
}