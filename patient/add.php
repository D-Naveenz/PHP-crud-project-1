<?php
require_once "../core/Patient.php";
require_once "../core/InPatient.php";
require_once "../core/OutPatient.php";

// start the session
session_start();

// store session data
$_SESSION['patient'] = Patient::getInstance();
$_SESSION['available_beds'] = InPatient::getFreeBeds();
if ($_SESSION['patient']->isInPatient())
    $_SESSION['verified_patient'] = new InPatient($_SESSION['patient']);
else
    $_SESSION['verified_patient'] = new OutPatient($_SESSION['patient']);
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

    <?php
    // Post requests
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Submitting the patient's basic information
        if (isset($_POST['btnSubPt'])) {
            $_SESSION['patient']->name = $_POST['pName'];
            $_SESSION['patient']->setType($_POST['pType']);

            $result = $_SESSION['patient']->insertToDb();
        }

        //Checking whether the patient's status (in / out) has already been declared
        if ($_SESSION['patient']->isExistsInDb()) {
            if ($_SESSION['patient']->isInPatient()) {
                $_SESSION['in_patient'] = new InPatient($_SESSION['patient']);
            }
            else {
                $_SESSION['out_patient'] = new OutPatient($_SESSION['patient']);
            }
        }

        // Submitting the in-patient's information
        if (isset($_POST['btnSubIn'])) {
            $_SESSION['verified_patient']->dob = $_POST['pDOB'];
            $_SESSION['verified_patient']->setAddDate($_POST['pAddDate']);
            $_SESSION['verified_patient']->setAddTime($_POST['pAddTime']);
            $_SESSION['verified_patient']->setDisDate($_POST['pDisDate']);
            $_SESSION['verified_patient']->setDisTime($_POST['pDisTime']);
            $_SESSION['verified_patient']->pc_doc = $_POST['pPcDoc'];
            $_SESSION['verified_patient']->bed_id = $_POST['pBed'];

            $result = $_SESSION['verified_patient']->insertToDb();
            session_destroy();
        }

        // Submitting the out-patient's information
        if (isset($_POST['btnSubOut'])) {
            $_SESSION['verified_patient']->setArrDate($_POST['pArrDate']);
            $_SESSION['verified_patient']->setArrTime($_POST['pArrTime']);

            $result = $_SESSION['verified_patient']->insertToDb();
            session_destroy();
        }

        if (isset($_POST['btnClose'])) {
            session_destroy();
        }
    }
    ?>

    <!-- Javascript -->
    <script type="text/javascript">
        const prefix = "<?php echo $_SESSION['patient']::id_prefix; ?>";
        let pId = "<?php echo $_SESSION['patient']->getPatientId(); ?>";
        let is_in_patient = <?php echo $_SESSION['patient']->isInPatient()? 'true':'false'; ?>;
        let is_patient_exists = <?php echo  $_SESSION['patient']->isExistsInDb()? 'true':'false'; ?>;
        let p_instance_exists = <?php echo  $_SESSION['patient']->has_instance? 'true':'false'; ?>;


        function check_patient_type() {
            if ($('#radPt1').is(':checked')) {
                return 1;
            }
            else {
                return 2;
            }
        }

        function in_out_pt_event() {
            if (check_patient_type() === 1) {
                $('#nav-itm-2').show();
                $('#nav-itm-3').hide();
            }
            else {
                $('#nav-itm-2').hide();
                $('#nav-itm-3').show();
            }
        }

        function disable_form(form_id, selector) {
            if (selector) {
                $(form_id + ' :input').prop("disabled",true);
            }
            else {
                $(form_id + ' :input').prop("disabled",false);
            }
        }

        window.onload = () => {
            // Initializing
            in_out_pt_event();
            disable_form('#pt-in', !is_in_patient);
            disable_form('#pt-out', !is_in_patient);

            if (is_patient_exists || p_instance_exists) {
                let patient_name = "<?php echo $_SESSION['patient']->name; ?>";
                $('#txtPt2').val(patient_name);
                disable_form('#pt-basic', true);
                if (check_patient_type() === 1) {
                    $('#pt-tab-2').trigger('click');
                    disable_form('#pt-in', false);
                }
                else {
                    $('#pt-tab-3').trigger('click');
                    disable_form('#pt-out', false);
                }
            }
        };

        $(document).ready(function(){
            if (is_in_patient) {
                $('#radPt1').prop('checked', true);
            }
            else $('#radPt2').prop('checked', true);

            // In / Out patient event handler
            $('input[name="pType"]').change(() => {
                in_out_pt_event()
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
                    "-" + (((currentDate.getMonth())+1)<10?'0':'') + ((currentDate.getMonth())+1) +
                    "-" + (currentDate.getDate()<10?'0':'') + currentDate.getDate();
                $('#txtIn2-date').val(formatDate);
            })

            // Get current time
            $('#pt-in-btn-time').click(() => {
                let currentDate = new Date();
                let formatTime = (currentDate.getHours()<10?'0':'') + currentDate.getHours() +
                    ":" + (currentDate.getMinutes()<10?'0':'') + currentDate.getMinutes();
                $('#txtIn2-time').val(formatTime);
            })
        });
    </script>
    <!-- Javascript -->

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

    <!-- Tabs content -->
    <div class="tab-content" id="nav-patient-content">

        <!-- 1st content: Patient relation -->
        <div class="container tab-pane active" id="pt-tab-panel-1" role="tabpanel" aria-labelledby="pt-tab-panel-1">
            <form id="pt-basic" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="mb-3">
                    <h2>Patient basic information</h2>
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
                    <input type="text" class="form-control" name="pName" id="txtPt2" required>
                </div>
                <div class="btn-group mb-3">
                    <input class="btn-check" type="radio" name="pType" id="radPt1" value="Inpatient" autocomplete="off">
                    <label class="btn btn-secondary" for="radPt1">In-patient</label>
                    <input class="btn-check" type="radio" name="pType" id="radPt2" value="Outpatient" autocomplete="off">
                    <label class="btn btn-secondary" for="radPt2">Out-patient</label>
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-danger btn-block login-btn" name="btnClose">Close</button>
                    <button type="submit" class="btn btn-primary btn-block login-btn" name="btnSubPt">Save</button>
                </div>
            </form>
        </div>
        <!-- 1st content -->

        <!-- 2nd content: In-patient relation -->
        <div class="container tab-pane fade" id="pt-tab-panel-2" role="tabpanel" aria-labelledby="pt-tab-panel-2">
            <form id="pt-in" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
                    <label class="form-label" for="txtIn5">Primary Care Doctor</label>
                    <input type="text" class="form-control" name="pPcDoc" id="txtIn5">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="txtIn6">Bed ID</label>
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="txtIn6">Available Beds</label>
                        <select class="form-select" name="pBed" id="txtIn6">
                            <?php foreach($_SESSION['available_beds'] as $value) { ?>
                                <option value="<?php echo $value ?>"><?php echo $value ?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-danger btn-block login-btn" name="btnClose">Close</button>
                    <button type="submit" class="btn btn-primary btn-block login-btn" name="btnSubIn">Save</button>
                </div>
            </form>
        </div>
        <!-- 2nd content -->

        <!-- 3rd content: Out-patient relation -->
        <div class="container tab-pane fade" id="pt-tab-panel-3" role="tabpanel" aria-labelledby="pt-tab-panel-3">
            <form id="pt-out" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="mb-3">
                    <h2>In-patient information</h2>
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
                <div class="mb-3">
                    <button type="submit" class="btn btn-danger btn-block login-btn" name="btnClose">Close</button>
                    <button type="submit" class="btn btn-primary btn-block login-btn" name="btnSubOut">Save</button>
                </div>
            </form>
        </div>
        <!-- 3rd content -->
    </div>
    <!-- Tabs content -->
</div>
</body>
</html>