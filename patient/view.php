<?php
require_once "../core/Patient.php";
require_once "../core/InPatient.php";
require_once "../core/OutPatient.php";

// start the session
session_start();

// check whether patient is logged in to server
if (!$_SESSION['patient']->isExistsInDb()) {
    // Check whether patient id is in the address
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        // form is preparing to update the patient record
        $_SESSION['patient'] = new Patient($_GET['id']);
    }
}

if ($_SESSION['patient']->isInPatient()) {
    // create new in-patient object from the parent object
    $_SESSION['patient'] = new InPatient($_SESSION['patient']->getPatientId());
} else {
    // create new out-patient object with the same name
    $_SESSION['patient'] = new OutPatient($_SESSION['patient']->getPatientId());
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
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ"
            crossorigin="anonymous">
    </script>

    <title>View Patient Details</title>
</head>
<body>
<!-- Body Header -->
<div class="container-fluid p-5 bg-primary text-white text-center">
    <h1>Patient Profile</h1>
    <p>Suwa Sahana Hospital</p>
</div>

<div class="container-fluid" style="margin-top: 20px;" id="nav-patient">
    <!-- Tabs navs -->
    <ul class="nav nav-tabs nav-justified mb-3" id="pt-navbar" role="tablist">
        <li class="nav-item" role="presentation" id="nav-itm-1">
            <a class="nav-link active" id="pt-tab-1" data-bs-toggle="tab" href="#pt-tab-panel-1" role="tab"
               aria-controls="pt-tab-panel-1" aria-selected="true">Basic Information</a>
        </li>
        <?php if ($_SESSION['patient'] instanceof InPatient): ?>
            <li class="nav-item" role="presentation" id="nav-itm-2">
                <a class="nav-link id=" id="pt-tab-2" data-bs-toggle="tab" href="#pt-tab-panel-2" role="tab"
                   aria-controls="pt-tab-panel-2" aria-selected="false">In-patient status</a>
            </li>
        <?php else: ?>
            <li class="nav-item" role="presentation" id="nav-itm-3">
                <a class="nav-link id=" id="pt-tab-3" data-bs-toggle="tab" href="#pt-tab-panel-3" role="tab"
                   aria-controls="pt-tab-panel-3" aria-selected="false">Out-patient status</a>
            </li>
        <?php endif; ?>
    </ul>
    <!-- Tabs navs -->

    <!-- Tabs content -->
    <div class="tab-content container" id="nav-patient-content">

        <!-- 1st content: Patient relation -->
        <div class="container tab-pane active" id="pt-tab-panel-1" role="tabpanel" aria-labelledby="pt-tab-panel-1">
            <div class="mb-3">
                <h2>Patient basic information</h2>
            </div>
            <div class="row justify-content-center mb-3">
                <table class="table">
                    <tr>
                        <td>Patient ID</td>
                        <td><?php echo $_SESSION['patient']->getPatientId(); ?></td>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td><?php echo $_SESSION['patient']->name; ?></td>
                    </tr>
                    <tr>
                        <td>Patient Type</td>
                        <td><?php echo $_SESSION['patient']->getType(); ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- 1st content -->

        <?php if ($_SESSION['patient'] instanceof InPatient): ?>
            <!-- 2nd content: In-patient relation -->
            <div class="container tab-pane fade" id="pt-tab-panel-2" role="tabpanel" aria-labelledby="pt-tab-panel-2">
                <div class="mb-3">
                    <h2>In-patient information</h2>
                </div>
                <div class="row justify-content-center mb-3">
                    <table class="table">
                        <tr>
                            <td>Date of Birth</td>
                            <td><?php echo $_SESSION['patient']->dob; ?></td>
                        </tr>
                        <tr>
                            <td>Admitted Date</td>
                            <td><?php echo $_SESSION['patient']->getAddDate(); ?></td>
                        </tr>
                        <tr>
                            <td>Admitted Time</td>
                            <td><?php echo $_SESSION['patient']->getAddTime(); ?></td>
                        </tr>
                        <tr>
                            <td>Discharge Date</td>
                            <td><?php echo $_SESSION['patient']->getDisDate(); ?></td>
                        </tr>
                        <tr>
                            <td>Discharge Time</td>
                            <td><?php echo $_SESSION['patient']->getDisTime(); ?></td>
                        </tr>
                        <tr>
                            <td>Primary Care Doctor</td>
                            <td><?php echo $_SESSION['patient']->pc_doc; ?></td>
                        </tr>
                        <tr>
                            <td>Bed ID</td>
                            <td><?php echo $_SESSION['patient']->bed_id; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- 2nd content -->
        <?php elseif ($_SESSION['patient'] instanceof OutPatient): ?>
            <!-- 3rd content: Out-patient relation -->
            <div class="container tab-pane fade" id="pt-tab-panel-3" role="tabpanel" aria-labelledby="pt-tab-panel-3">
                <div class="mb-3">
                    <h2>In-patient information</h2>
                </div>
                <div class="row justify-content-center mb-3">
                    <table class="table">
                        <tr>
                            <td>Arrived Date</td>
                            <td><?php echo $_SESSION['patient']->getArrDate(); ?></td>
                        </tr>
                        <tr>
                            <td>Arrived Time</td>
                            <td><?php echo $_SESSION['patient']->getArrTime(); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- 3rd content -->
        <?php endif; ?>
    </div>
    <!-- Tabs content -->
    <div class="container">
        <a href="<?php echo $_SESSION['previous_page']; ?>" class="btn btn-danger btn-block">Close</a>
    </div>
</div>
</body>
</html>
