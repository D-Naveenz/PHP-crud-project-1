<?php
require_once "../core/Patient.php";
require_once "../core/InPatient.php";
require_once "../core/OutPatient.php";

// start the session
session_start();

// store session data with checking get requests
if (isset($_GET['create'])) {
    // form is preparing to create the patient record
    $_SESSION['patient'] = new Patient();
} elseif (isset($_GET['update']) && !empty($_GET['update'])) {
    // form is preparing to update the patient record
    $_SESSION['patient'] = new Patient($_GET['update']);

    if ($_SESSION['patient']->isInPatient()) {
        // create new in-patient object from the parent object
        $_SESSION['patient'] = new InPatient($_SESSION['patient']->getPatientId());
    } else {
        // create new out-patient object with the same name
        $_SESSION['patient'] = new OutPatient($_SESSION['patient']->getPatientId());
    }
}

$_SESSION['available_beds'] = InPatient::getFreeBeds();

// Post requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['pType'] == 'Inpatient') {
        $temp_p = new InPatient($_POST['pId'], $_SESSION['patient']);
        // Assigning basic information
        $temp_p->name = $_POST['pName'];
        $temp_p->setType($_POST['pType']);
        // Assigning in-patient information
        $temp_p->dob = $_POST['pDOB'];
        $temp_p->setAddDate($_POST['pAddDate']);
        $temp_p->setAddTime($_POST['pAddTime']);
        $temp_p->setDisDate($_POST['pDisDate']);
        $temp_p->setDisTime($_POST['pDisTime']);
        $temp_p->pc_doc = $_POST['pPcDoc'];
        $temp_p->bed_id = $_POST['pBed'];
    }
    else {
        $temp_p = new OutPatient($_POST['pId'], $_SESSION['patient']);
        // Assigning basic information
        $temp_p->name = $_POST['pName'];
        $temp_p->setType($_POST['pType']);
        // Assigning out-patient information
        $temp_p->setArrDate($_POST['pArrDate']);
        $temp_p->setArrTime($_POST['pArrTime']);
    }

    if (isset($_POST['btnCreate'])) {
        // Submitting the 'create' form inputs
        $result = $temp_p->insertToDb();
    } elseif (isset($_POST['btnUpdate'])) {
        // Submitting the 'update' form inputs
        $result = $temp_p->updateDb();
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

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

    <!-- Custom Javascript -->
    <script type="text/javascript">
        function in_out_tabs_visible() {
            if ($('#radPt1').is(':checked')) {
                $('#nav-itm-2').show();
                $('#nav-itm-3').hide();
            } else {
                $('#nav-itm-2').hide();
                $('#nav-itm-3').show();
            }
        }

        window.onload = () => {
            // Initializing form elements
            $('<?php echo $_SESSION['patient']->isInPatient() ? '#radPt1' : '#radPt2'; ?>').prop('checked', true);
            // disable the relevant from according to radio buttons' changes
            in_out_tabs_visible();

            <?php if (isset($_GET['update']) && !empty($_GET['update'])) { ?>
            // Initializing form elements according to the update request
            // hide save button
            $('#btn-req-create').hide();
            <?php
                if ($_SESSION['patient']->isInPatient()) {
            ?>
            $('#txtIn1').val("<?php echo $_SESSION['patient']->dob ?>");
            $('#txtIn2-date').val("<?php echo $_SESSION['patient']->getAddDate() ?>");
            $('#txtIn2-time').val("<?php echo $_SESSION['patient']->getAddTime() ?>");
            $('#txtIn3-date').val("<?php echo $_SESSION['patient']->getDisDate() ?>");
            $('#txtIn3-time').val("<?php echo $_SESSION['patient']->getDisTime() ?>");
            $('#txtIn4').val("<?php echo $_SESSION['patient']->pc_doc ?>");
            let option_text = "<?php echo $_SESSION['patient']->bed_id ?>";
            $('#txtIn5').append(new Option(option_text, option_text, true, true));
            <?php } else { ?>
            $('#txtOut1-date').val("<?php echo $_SESSION['patient']->getArrDate() ?>");
            $('#txtOut1-time').val("<?php echo $_SESSION['patient']->getArrTime() ?>");
            <?php
                }
            } else {
            ?>
            // hide update button
            $('#btn-req-update').hide();
            <?php } ?>
        };

        $(document).ready(function () {
            // In / Out patient event handler
            $('input[name="pType"]').change(() => {
                in_out_tabs_visible()
            });

            // Get today's date
            $('#pt-in-btn-date').click(() => {
                let currentDate = new Date();
                /*
                let dateForDateTimeLocal = currentDate.getFullYear() +
                    "-" + (((currentDate.getMonth())+1)<10?'0':'') + ((currentDate.getMonth())+1) +
                    "-" + (currentDate.getDate()<10?'0':'') + currentDate.getDate() +
                    "T" + (currentDate.getHours()<10?'0':'') + currentDate.getHours() +
                    ":" + (currentDate.getMinutes()<10?'0':'') + currentDate.getMinutes() +
                    ":" + (currentDate.getSeconds()<10?'0':'') + currentDate.getSeconds();
                 */
                let formatDate = currentDate.getFullYear() +
                    "-" + (((currentDate.getMonth()) + 1) < 10 ? '0' : '') + ((currentDate.getMonth()) + 1) +
                    "-" + (currentDate.getDate() < 10 ? '0' : '') + currentDate.getDate();
                $('#txtIn2-date').val(formatDate);
            })

            // Get current time
            $('#pt-in-btn-time').click(() => {
                let currentDate = new Date();
                let formatTime = (currentDate.getHours() < 10 ? '0' : '') + currentDate.getHours() +
                    ":" + (currentDate.getMinutes() < 10 ? '0' : '') + currentDate.getMinutes();
                $('#txtIn2-time').val(formatTime);
            })
        });
    </script>
    <!-- Custom Javascript -->

    <title>Add Patient Details</title>
</head>
<body>
<!-- Body Header -->
<div class="container-fluid p-5 bg-primary text-white text-center">
    <h1>Add New Patient</h1>
    <p>Suwa Sahana Hospital</p>
</div>

<div class="container" style="margin-top: 20px;" id="nav-patient">
    <!-- Tabs navs -->
    <ul class="nav nav-tabs nav-justified mb-3" id="pt-navbar" role="tablist">
        <li class="nav-item" role="presentation" id="nav-itm-1">
            <a class="nav-link active" id="pt-tab-1" data-bs-toggle="tab" href="#pt-tab-panel-1" role="tab"
               aria-controls="pt-tab-panel-1" aria-selected="true">Basic Information</a>
        </li>
        <li class="nav-item" role="presentation" id="nav-itm-2">
            <a class="nav-link id=" id="pt-tab-2" data-bs-toggle="tab" href="#pt-tab-panel-2" role="tab"
               aria-controls="pt-tab-panel-2" aria-selected="false">In-patient status</a>
        </li>
        <li class="nav-item" role="presentation" id="nav-itm-3">
            <a class="nav-link id=" id="pt-tab-3" data-bs-toggle="tab" href="#pt-tab-panel-3" role="tab"
               aria-controls="pt-tab-panel-3" aria-selected="false">Out-patient status</a>
        </li>
    </ul>
    <!-- Tabs navs -->

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <!-- Tabs content -->
        <div class="tab-content" id="nav-patient-content">
            <!-- 1st content: Patient relation -->
            <div class="container tab-pane active" id="pt-tab-panel-1" role="tabpanel" aria-labelledby="pt-tab-panel-1">
                <div class="mb-3">
                    <h2>Patient basic information</h2>
                    <input type="hidden" name="pId" value="<?php echo $_SESSION['patient']->getPatientId(); ?>"/>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="ptID">Patient ID</label>
                    <div class="input-group mb-3" id="pt-id-field">
                        <span class="input-group-text" id="txtPt1">PT</span>
                        <input type="text" class="form-control" placeholder="Patient ID will be generated automatically"
                               aria-label="Patient ID will be generated automatically" aria-describedby="txtPt1"
                               value="<?php echo $_SESSION['patient']->getPatientIdNum(); ?>" readonly/>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="txtPt2">Name</label>
                    <input type="text" class="form-control" name="pName" id="txtPt2" value="<?php echo $_SESSION['patient']->name; ?>" required>
                </div>
                <div class="btn-group mb-3">
                    <input class="btn-check" type="radio" name="pType" id="radPt1" value="Inpatient" autocomplete="off">
                    <label class="btn btn-secondary" for="radPt1">In-patient</label>
                    <input class="btn-check" type="radio" name="pType" id="radPt2" value="Outpatient"
                           autocomplete="off">
                    <label class="btn btn-secondary" for="radPt2">Out-patient</label>
                </div>
            </div>
            <!-- 1st content -->

            <!-- 2nd content: In-patient relation -->
            <div class="container tab-pane fade" id="pt-tab-panel-2" role="tabpanel" aria-labelledby="pt-tab-panel-2">
                <div class="mb-3">
                    <h2>In-patient information</h2>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="txtIn1">Date of Birth</label>
                    <input type="date" class="form-control" name="pDOB" id="txtIn1">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="txtIn2-date">Admitted on</label>
                    <div class="input-group">
                        <span class="input-group-text">Date: </span>
                        <input type="date" class="form-control" name="pAddDate" id="txtIn2-date" placeholder="Date">
                        <button class="btn btn-outline-secondary" type="button" id="pt-in-btn-date">Today</button>
                        <span class="input-group-text">Time: </span>
                        <input type="time" class="form-control" name="pAddTime" id="txtIn2-time" placeholder="Time">
                        <button class="btn btn-outline-secondary" type="button" id="pt-in-btn-time">Now</button>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="txtIn3-date">Discharge at</label>
                    <div class="input-group">
                        <span class="input-group-text">Date: </span>
                        <input type="date" class="form-control" name="pDisDate" id="txtIn3-date" placeholder="Date">
                        <span class="input-group-text">Time: </span>
                        <input type="time" class="form-control" name="pDisTime" id="txtIn3-time" placeholder="Time">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="txtIn4">Primary Care Doctor</label>
                    <input type="text" class="form-control" name="pPcDoc" id="txtIn4">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="txtIn5">Bed ID</label>
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="txtIn6">Available Beds</label>
                        <select class="form-select" name="pBed" id="txtIn5">
                            <?php foreach ($_SESSION['available_beds'] as $value) { ?>
                                <option value="<?php echo $value ?>"><?php echo $value ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <!-- 2nd content -->

            <!-- 3rd content: Out-patient relation -->
            <div class="container tab-pane fade" id="pt-tab-panel-3" role="tabpanel" aria-labelledby="pt-tab-panel-3">
                <div class="mb-3">
                    <h2>Out-patient information</h2>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="txtOut1-date">Arrived at</label>
                    <div class="input-group">
                        <span class="input-group-text">Date: </span>
                        <input type="date" class="form-control" name="pArrDate" id="txtOut1-date" placeholder="Date">
                        <span class="input-group-text">Time: </span>
                        <input type="time" class="form-control" name="pArrTime" id="txtOut1-time" placeholder="Time">
                    </div>
                </div>
            </div>
            <!-- 3rd content -->
        </div>
        <!-- Tabs content -->

        <div class="mb-3 form-buttonbar container">
            <button type="submit" class="btn btn-danger btn-block" name="btnClose">Close</button>
            <button type="submit" class="btn btn-primary btn-block" id="btn-req-create" name="btnCreate">Save</button>
            <button type="submit" class="btn btn-primary btn-block" id="btn-req-update" name="btnUpdate">Update</button>
        </div>
    </form>
</div>
</body>
</html>