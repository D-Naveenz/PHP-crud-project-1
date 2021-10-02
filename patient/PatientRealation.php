<?php
require_once "../core/config.php";

class PatientRealation
{
    public string $patient_id;
    public string $fname;
    public string $lname;
    public string $relation;
    public string $address;
    public int $contact;

    protected string $table;

    protected function __construct()
    {
        $this->patient_id = "";
        $this->fname = "";
        $this->lname = "";
        $this->relation = "";
        $this->address = "";
        $this->contact = "";
        $this->table = "";
    }

    public function Build($id, $fname, $relation) {
        $table = $this->table;
        $database = createMySQLConn();
        $result = $database->query("SELECT * FROM $table WHERE `Patient_ID` = '$id' AND `FName` = $fname AND `LName` = $relation");
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $target = new PatientRealation();
            $target->patient_id = $row['Patient_ID'];
            $target->fname = $row['FName'];
            $target->lname = $row['LName'];
            $target->relation = $row['Relationship'];
            $target->address = $row['Address'];
            $target->contact = $row['ContactNo'];

            return $target;
        }
        elseif ($result->num_rows > 1) {
            die("Insurance Subscriber table has many results with the same id: $id! | rows: $result->num_rows");
        }
        return null;
    }

    public function insertToDb()
    {   $table = $this->table;
        $database = createMySQLConn();
        $sql = "INSERT INTO $table (Patient_ID, FName, LName, Relationship, Address, ContactNo) VALUES (?, ?, ?, ?, ?, ?)";
        $sql_statement = $database->prepare($sql);
        // bind param with references : https://www.php.net/manual/en/language.references.whatare.php
        $sql_statement->bind_param("sssssi", $this->patient_id, $this->fname, $this->lname, $this->relation, $this->address, $this->contact);
        // Execution
        $sql_statement->execute();
        $sql_statement->close();
    }

    public function updateRow()
    {   $table = $this->table;
        $database = createMySQLConn();
        $sql = "UPDATE $table SET LName = ?, Address = ?, ContactNo = ? WHERE $table.Patient_ID = ? AND $table.FName = ? AND $table.Relationship = ?";
        $sql_statement = $database->prepare($sql);
        // bind param with references : https://www.php.net/manual/en/language.references.whatare.php
        $sql_statement->bind_param("ssisss", $this->lname, $this->address, $this->contact, $this->patient_id, $this->fname, $this->relation);
        // Execution
        $sql_statement->execute();
        $sql_statement->close();
    }

    public function deleteRow()
    {   $table = $this->table;
        $database = createMySQLConn();
        $sql = "DELETE FROM $table WHERE $table.Patient_ID = ? AND $table.FName = ? AND $table.Relationship = ?";
        $sql_statement = $database->prepare($sql);
        // bind param with references : https://www.php.net/manual/en/language.references.whatare.php
        $sql_statement->bind_param("s", $this->patient_id, $this->fname, $this->relation);
        // Execution
        $sql_statement->execute();
        $sql_statement->close();
    }
}