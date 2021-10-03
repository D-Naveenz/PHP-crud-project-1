<?php
require_once "../core/config.php";

// generate session variables to locate the current page
$_SESSION['previous_page'] = getAbsUrl();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.js"
            integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ"
            crossorigin="anonymous">
    </script>

    <title>Main Page: Employee</title>
</head>
<body>
<!-- Body Header -->
<div class="container-fluid p-5 bg-primary text-white text-center">
    <h1>Employee Portal</h1>
    <p>Suwa Sahana Hospital</p>
</div>
<div class="container-fluid">
    <div class="container">
        <div class="row justify-content-center">
            <a href="../patient/list.php">Patients List</a>
            <a href="../pages/beds.php">Beds</a>
            <a href="../pages/diags.php">Diagrams</a>
            <a href="../pages/diagunits.php">Diagnosis Units</a>
            <a href="../pages/drugs.php">Drugs</a>
            <a href="../pages/pcus.php">Patient Care Units</a>
            <a href="../pages/supplies.php">Supplies</a>
            <a href="../pages/tests.php">Tests</a>
            <a href="../pages/treatments.php">Treatments</a>
            <a href="../pages/vendors.php">Vendors</a>
            <a href="../pages/wards.php">Wards</a>
        </div>
    </div>
</div>
<!-- Body Header -->
</body>
</html>