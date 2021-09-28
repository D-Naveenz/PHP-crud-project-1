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

// Get the id from last record of the table
function get_last_id($table, $id_col_name) {
    $hospitalDB = createMySQLConn();
    $id = 0;

    $result = $hospitalDB->query("SELECT $id_col_name FROM $table ORDER BY $id_col_name DESC LIMIT 1;");

    if ($result->num_rows > 0) {
        // output data
        while($row = $result->fetch_assoc()) {
            $id = $row[$id_col_name];
        }
    }

    return $id;
}

function next_id($table, $id_col_name, $prefix) {
    $id_number = 1;
    // get the previous id
    $pev_id = get_last_id($table, $id_col_name);
    if ($pev_id != 0) {
        // There is a last record on the table
        if (str_starts_with($pev_id, $prefix)) {
            // get the number part and convert it into integer
            $id_number = (int)substr($pev_id, strlen($prefix)); // substr(string, offset, length)
            return $prefix.str_pad(strval(++$id_number), max_id_nums, "0", STR_PAD_LEFT);
        }
        else die("ERROR: Could not find the last id with given prefix.");
    }
    else {
        // There is no last record on the table
        return $prefix.'0002';
    }
}