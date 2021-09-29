<?php
// Application constraints
const max_id_nums = 4;

// Database credentials
const DB_SEVER = 'localhost';
const DB_USERNAME = 'root';
const DB_PASSWORD = '';
const DB_NAME = 'hospital';

// Attempt to connect to the MySQL database
function createMySQLConn() {
    $conn = new mysqli(DB_SEVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
// Check connection
    if ($conn->connect_error)
    {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
    return $conn;
}

// Get the next id from the table
function nextId($table, $id_col_name, $prefix) {
    $hospitalDB = createMySQLConn();
    $pev_id = 0;

    // Get the id from last record of the table
    $result = $hospitalDB->query("SELECT $id_col_name FROM $table ORDER BY $id_col_name DESC LIMIT 1;");
    if ($result->num_rows == 1) {
        // output data
        $row = $result->fetch_assoc();
        $pev_id = $row[$id_col_name];

        if ($pev_id != 0) {
            // There is a last record on the table
            if (str_starts_with($pev_id, $prefix)) {
                // get the number part and convert it into integer
                $id_number = (int)substr($pev_id, strlen($prefix)); // substr(string, offset, length)
                return $prefix.str_pad(strval(++$id_number), max_id_nums, "0", STR_PAD_LEFT);
            }
            else die("ERROR: Could not find the last id with given prefix.");
        }
    }
    echo "There is no last record on the $table table.";
    return null;
}

function AvailableBeds(): array
{
    $database = createMySQLConn();
    $available_beds = array();
    $sql = "SELECT `Bed_ID` FROM `bed` WHERE `Availability` = 1";
    $result = $database->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        $count = 0;
        while ($row = $result->fetch_assoc()) {
            $available_beds[$count++] = $row["Bed_ID"];
        }
    }
    return $available_beds;
}

function isDateTimeStrNull(string $date_time_str): ?string
{
    if ($date_time_str == "0000-00-00" || $date_time_str == "00-00") return null;
    else return $date_time_str;
}