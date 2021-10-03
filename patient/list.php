<?php
/*
 * PHP CRUD Template v1.0
 * Developed by Naveen Dharmathunga
 * GitHub: https://github.com/D-Naveenz
 */
require_once "../core/config.php";
require_once "Emergency.php";
require_once "Record.php";

// generate session variables to locate the current page
$_SESSION['previous_page'] = getAbsUrl();

// Connect to the database
$database = createMySQLConn();
$res_select = $database->query("SELECT * FROM `patient`");

// Post requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // save patient id in the session
    $_SESSION['patient_id'] = $_POST['Val1'];

    if (isset($_POST['btnView'])) {
        // reload the page
        header("Location: view.php");
    } elseif (isset($_POST['btnUpdate'])) {
        // reload the page
        header("Location: edit.php?update");
    } elseif (isset($_POST['btnDelete'])) {
        $temp_p = new Patient($_GET['id']);

        if ($temp_p->isInPatient()) {
            // create new in-patient object from the parent object
            $temp_p = new InPatient($_SESSION['patient_id']);
            $temp_p->insurance->deleteRow();
            // purge all data before delete the record
            Emergency::deleteAll($_SESSION['patient_id']);
            Record::deleteAll($_SESSION['patient_id']);
        } else {
            // create new out-patient object with the same name
            $temp_p = new OutPatient($_SESSION['patient_id']);
        }

        // delete the record
        $temp_p->deleteRow();
    }
}
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

    <!-- Crud Page Script -->
    <script type="text/javascript" src="../js/crud_page.js"></script>

    <title>Patients</title>
</head>
<body>
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
    </symbol>
    <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
    </symbol>
    <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
    </symbol>
</svg>

<?php if (isset($_SESSION['res_msg']) && isset($_GET['message'])): ?>
    <!-- Display Alert -->
    <div class="alert alert-<?= $_SESSION['res_msg_type'] ?> alert-dismissible d-flex align-items-center fade show mb-0"
         role="alert">
        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
            <use xlink:href="<?php
            echo match ($_SESSION['res_msg_type']) {
                "success" => '#check-circle-fill',
                "danger" => '#exclamation-triangle-fill',
                default => '#info-fill',
            }; ?>"/>
        </svg>
        <div>
            <?php
            echo $_SESSION['res_msg'];
            unset($_SESSION['res_msg']);
            ?>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <!-- Display Alert -->
<?php endif; ?>

<!-- Body Header -->
<div class="container-fluid p-5 bg-primary text-white text-center">
    <h1>List of Patients</h1>
    <p>Suwa Sahana Hospital</p>
</div>
<!-- Body Header -->

<div class="container-fluid" style="margin-top: 20px;" id="nav-bed">
    <div class="container">
        <div class="row justify-content-center">
            <table class="table table-hover">
                <col style="width: 27%;"/>
                <col style="width: 27%;"/>
                <col style="width: 27%;"/>
                <col style="width: 19%;"/>
                <thead style="background-color: blue; color: white">
                <tr>
                    <th scope="col">Patent ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Type</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <?php
                $row_count = 0;
                while ($row = $res_select->fetch_assoc()): ?>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <tr id="row-<?= $row_count ?>">
                            <td>
                                <?= $row['Patient_ID'] ?>
                                <label><input type="hidden" class="form-control" name="Val1"
                                              value="<?= $row['Patient_ID'] ?>"></label>
                            </td>
                            <td><?= $row['Name'] ?></td>
                            <td><?= $row['Type'] ?></td>
                            <td>
                                <button type="submit" class="btn btn-info" name="btnView">View</button>
                                <button type="submit" class="btn btn-primary" name="btnUpdate">Update</button>
                                <button type="submit" class="btn btn-danger" name="btnDelete">Delete</button>
                            </td>
                        </tr>
                    </form>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
    <div class="container">
        <a href="edit.php?New" class="btn btn-info btn-block" id="btn-add">Add</a>
        <a href="../employee/main.php" class="btn btn-secondary btn-block">Close</a>
    </div>
</div>
</body>
</html>
