<?php
require_once "../core/config.php";
require_once "Patient.php";
require_once "InPatient.php";
require_once "OutPatient.php";

function load_patient($id): InPatient|OutPatient|null
{
    // form is preparing to update the patient record
    $patient = new Patient($id);
    if ($patient->isExistsInDb()) {
        if ($patient->isInPatient()) {
            // create new in-patient object from the parent object
            $patient = new InPatient($patient->getPatientId());
        } else {
            // create new out-patient object with the same name
            $patient = new OutPatient($patient->getPatientId());
        }
        return $patient;
    } else {
        // redirect to previous page
        header("Location: " . $_SESSION['previous_page']);
        return null;
    }
}

// store session data with checking get requests
if (isset($_GET['update'])) {
    // form is preparing to update the patient record
    $patient = load_patient($_SESSION['patient_id']);
} elseif (isset($_GET['new'])) {
    // form is preparing to create the patient record
    $patient = new Patient();
} else {
    // form is loading the current state
    $patient = load_patient($_SESSION['patient_id']);
}

// global variables
$available_beds = InPatient::getFreeBeds();
$id = $patient->getPatientId();

// Delete Requests
if (isset($_GET['delete'])) {
    if (isset($_GET['ins'])) {
        $target_ins = new Insurance($id);
        $target_ins->deleteRow();
    } elseif (isset($_GET['emg']) && isset($_GET['fname']) && isset($_GET['rel'])) {
        Emergency::deleteRow($id, $_GET['fname'], $_GET['rel']);
    } elseif (isset($_GET['rec']) && isset($_GET['date']) && isset($_GET['time'])) {
        Record::deleteRow($id, $_GET['date'], $_GET['time']);
    }

    // reload the page
    header("Location: ".$_SERVER["PHP_SELF"]."?update");
}

// Post requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['btnInsCreate'])) {
        $temp_ins = new Insurance($id);
        $temp_ins->company = $_POST['Val1'];
        $temp_ins->branch = $_POST['Val2'];
        $temp_ins->address = $_POST['Val3'];
        $temp_ins->contact = $_POST['Val4'];
        // Submitting the 'create' form inputs
        $temp_ins->insertToDb();
        $patient->insurance = $temp_ins;
    } elseif (isset($_POST['btnInsUpdate'])) {
        $temp_ins = $patient->insurance;
        $temp_ins->company = $_POST['Val1'];
        $temp_ins->branch = $_POST['Val2'];
        $temp_ins->address = $_POST['Val3'];
        $temp_ins->contact = $_POST['Val4'];
        // Submitting the 'update' form inputs
        $temp_ins->updateRow();
    } elseif (isset($_POST['btnEmgAdd'])) {
        $temp_emg = new Emergency($id, $_POST['Val1'], $_POST['Val2'], $_POST['Val3'], $_POST['Val4'], $_POST['Val5']);
        // Submitting the 'create' form inputs
        $temp_emg->insertToDb();
    } elseif (isset($_POST['btnEmgUpdate'])) {
        $temp_emg = new Emergency($id, $_POST['Val1'], $_POST['Val2'], $_POST['Val3'], $_POST['Val4'], $_POST['Val5']);
        // Submitting the 'update' form inputs
        $temp_emg->updateRow();
    } elseif (isset($_POST['btnRecAdd'])) {
        $temp_rec = new Record($id, $_POST['Val1'], $_POST['Val2'], $_POST['Val3'], $_POST['Val5'], $_POST['Val6'], $_POST['Val7'], $_POST['Val8']);
        $temp_rec->setSymptoms($_POST['Val4']);
        // Submitting the 'create' form inputs
        $temp_rec->insertToDb();
    } elseif (isset($_POST['btnRecUpdate'])) {
        $temp_rec = new Record($id, $_POST['Val1'], $_POST['Val2'], $_POST['Val3'], $_POST['Val5'], $_POST['Val6'], $_POST['Val7'], $_POST['Val8']);
        $temp_rec->setSymptoms($_POST['Val4']);
        // Submitting the 'update' form inputs
        $temp_rec->updateRow();
    }

    // reload the page
    header("Location: ".$_SERVER["PHP_SELF"]."?update");
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

    <!-- Custom Javascript -->
    <script type="text/javascript">
        let isInPatient = <?=($patient instanceof InPatient) ? 'true' : 'false'?>;

        function in_n_out_event() {
            // hide / disable forms when change the radio button
            if ($('#radPt1').is(':checked')) {
                $('#pt-in').show("slow");
                $('#pt-out').hide("slow");
                $('#tab-2-form :input').prop('disabled', false);
                $('.tab-table-add-elements').prop('disabled', false);
            } else {
                $('#pt-in').hide("slow");
                $('#pt-out').show("slow");
                $('#tab-2-form :input').prop('disabled', true);
                $('.tab-table-add-elements').prop('disabled', true);
            }
        }

        window.onload = () => {
            // $(isInPatient? '#radPt1' : '#radPt2').prop('checked', true);
            // disable the relevant from according to radio buttons' changes
            in_n_out_event();

            <?php if (isset($_GET['update'])): ?>
            // Initializing form elements according to the update request
            // hide save button
            $('#btn-pt-create').hide();
            $('#btn-ins-create').hide();
            // disable in/out radio button
            $('input[name="pType"]').prop('disabled', true);
            // hide all add section
            $('.add-row').hide();

            <?php if ($patient instanceof InPatient): ?>
            // In-patient details
            $('#txtIn1').val("<?= $patient->dob ?>");
            $('#txtIn2-date').val("<?= $patient->getAddDate() ?>");
            $('#txtIn2-time').val("<?= $patient->getAddTime() ?>");
            $('#txtIn3-date').val("<?= $patient->getDisDate() ?>");
            $('#txtIn3-time').val("<?= $patient->getDisTime() ?>");
            $('#txtIn4').val("<?= $patient->pc_doc ?>");
            let option_text = "<?= $patient->bed_id ?>";
            $('#txtIn5').append(new Option(option_text, option_text, true, true));

            <?php if ($patient->insurance): ?>
            //Insurance details
            $('#txt-tab2-1').val("<?= $patient->insurance->company?>");
            $('#txt-tab2-2').val("<?= $patient->insurance->branch?>");
            $('#txt-tab2-3').val("<?= $patient->insurance->address?>");
            $('#txt-tab2-4').val("<?= $patient->insurance->contact?>");
            <?php endif; ?>

            <?php else: ?>
            $('button[name="btnInsUpdate"]').hide();
            $('#txtOut1-date').val("<?= $patient->getArrDate() ?>");
            $('#txtOut1-time').val("<?= $patient->getArrTime() ?>");
            <?php endif; ?>

            <?php else: ?>
            // hide update button
            $('#btn-pt-update').hide();
            $('#btn-ins-update').hide();
            <?php endif; ?>
        };

        $(document).ready(function () {
            // hide all update divs
            $('.update-row').hide();
            // In / Out patient event handler
            $('input[name="pType"]').change(() => {
                in_n_out_event()
            });

            <?php if ($patient instanceof OutPatient): ?>
            $('#pt-navbar').hide();
            <?php endif; ?>

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

            $(".data-row-toggle").click(function (e) {
                e.preventDefault();
                let target = $(this).attr('href');
                let _this = target.replace("-edit", "");
                $(_this).hide();
                $(target).show();
            });

            $(".update-row-toggle").click(function (e) {
                e.preventDefault();
                let target = $(this).attr('href');
                let _this = target.replace("row-", "row-edit-");
                $(_this).hide();
                $(target).show();
            });

            $('.btn-add').click(function () {
                let link = $(this).attr('href');
                $(this).hide("fast", function () {
                    $(link).show("slow");
                });
            })

            $(".btn-form-add-cancel").click(function (e) {
                e.preventDefault();
                let btn_link = $(this).children().attr('href');
                let tr_link = btn_link.replace("btn", "row");
                $(tr_link).hide("slow", function () {
                    $(btn_link).show("fast");
                });
            });
        });
    </script>
    <!-- Custom Javascript -->

    <title>Add / Edit Patient Details</title>
</head>
<body>
<!-- Body Header -->
<div class="container-fluid p-5 bg-primary text-white text-center">
    <?php if (isset($_GET['update']) && !empty($_GET['update'])): ?>
        <h1>Update <?= $patient->name ?></h1>
    <?php else: ?>
        <h1>Add New Patient</h1>
    <?php endif; ?>
    <p>Suwa Sahana Hospital</p>
</div>

<div class="container-fluid" style="margin-top: 20px;" id="nav-patient">
    <!-- Tabs navs -->
    <ul class="nav nav-tabs nav-justified mb-3" id="pt-navbar" role="tablist">
        <li class="nav-item" role="presentation" id="nav-itm-1">
            <a class="nav-link active" id="pt-tab-1" data-bs-toggle="tab" href="#pt-tab-panel-1" role="tab"
               aria-controls="pt-tab-panel-1" aria-selected="true">Information</a>
        </li>
        <li class="nav-item" role="presentation" id="nav-itm-2">
            <a class="nav-link id=" id="pt-tab-2" data-bs-toggle="tab" href="#pt-tab-panel-2" role="tab"
               aria-controls="pt-tab-panel-2" aria-selected="false">Insurance</a>
        </li>
        <li class="nav-item" role="presentation" id="nav-itm-3">
            <a class="nav-link id=" id="pt-tab-3" data-bs-toggle="tab" href="#pt-tab-panel-3" role="tab"
               aria-controls="pt-tab-panel-3" aria-selected="false">Emergency Contact</a>
        </li>
        <li class="nav-item" role="presentation" id="nav-itm-4">
            <a class="nav-link id=" id="pt-tab-4" data-bs-toggle="tab" href="#pt-tab-panel-4" role="tab"
               aria-controls="pt-tab-panel-4" aria-selected="false">Records</a>
        </li>
    </ul>
    <!-- Tabs navs -->

    <!-- Tabs content -->
    <div class="tab-content container-fluid" id="nav-patient-content">

        <!-- 1st content: Patient relation -->
        <div class="container tab-pane active" id="pt-tab-panel-1" role="tabpanel" aria-labelledby="pt-tab-panel-1">
            <form action="edit-helper.php" method="post">
                <div class="container" id="pt-info">
                    <div class="mb-3">
                        <h2>Patient information</h2>
                        <input type="hidden" name="pId" value="<?php echo $patient->getPatientId(); ?>"
                               required/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="ptID">Patient ID</label>
                        <div class="input-group mb-3" id="pt-id-field">
                            <span class="input-group-text" id="txtPt1">PT</span>
                            <input type="text" class="form-control"
                                   placeholder="Patient ID will be generated automatically"
                                   aria-label="Patient ID will be generated automatically" aria-describedby="txtPt1"
                                   value="<?php echo $patient->getPatientIdNum(); ?>" readonly/>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="txtPt2">Name</label>
                        <input type="text" class="form-control" name="pName" id="txtPt2"
                               value="<?php echo $patient->name; ?>" required>
                    </div>
                    <div class="d-flex flex-row mb-3">
                        <div class="p-2" style="padding-left: 0!important;"><label class="mb-0 align-middle">Patient
                                Type</label></div>
                        <div class="btn-group p-2" role="group" aria-label="Update Patient Type">
                            <input type="radio" class="btn-check" name="pType" id="radPt1" autocomplete="off"
                                   value="Inpatient" <?= ($patient->isInPatient()) ? 'checked' : '' ?>>
                            <label class="btn btn-outline-primary" for="radPt1">In-patient</label>

                            <input type="radio" class="btn-check" name="pType" id="radPt2" autocomplete="off"
                                   value="Outpatient" <?= ($patient->isInPatient()) ?: 'checked' ?>>
                            <label class="btn btn-outline-primary" for="radPt2">Out-patient</label>
                        </div>
                    </div>
                </div>
                <!-- In-patient Relation -->
                <div class="container" id="pt-in">
                    <div class="mb-3">
                        <label class="form-label" for="txtIn1">Date of Birth</label>
                        <input type="date" class="form-control" name="pDOB" id="txtIn1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="txtIn2-date">Admitted on</label>
                        <div class="input-group">
                            <span class="input-group-text">Date: </span>
                            <input type="date" class="form-control primary-key" name="pAddDate" id="txtIn2-date"
                                   placeholder="Date">
                            <button class="btn btn-outline-secondary" type="button" id="pt-in-btn-date">Today</button>
                            <span class="input-group-text">Time: </span>
                            <label for="txtIn2-time"></label><input type="time" class="form-control primary-key"
                                                                    name="pAddTime" id="txtIn2-time" placeholder="Time">
                            <button class="btn btn-outline-secondary" type="button" id="pt-in-btn-time">Now</button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="txtIn3-date">Discharge at</label>
                        <div class="input-group">
                            <span class="input-group-text">Date: </span>
                            <input type="date" class="form-control" name="pDisDate" id="txtIn3-date" placeholder="Date">
                            <span class="input-group-text">Time: </span>
                            <label for="txtIn3-time"></label><input type="time" class="form-control" name="pDisTime"
                                                                    id="txtIn3-time" placeholder="Time">
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
                                <?php foreach ($available_beds as $value) { ?>
                                    <option value="<?php echo $value ?>"><?php echo $value ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- In-patient Relation -->
                <!-- Out-patient Relation -->
                <div class="container" id="pt-out">
                    <div class="mb-3">
                        <label class="form-label" for="txtOut1-date">Arrived at</label>
                        <div class="input-group">
                            <span class="input-group-text">Date: </span>
                            <input type="date" class="form-control primary-key" name="pArrDate" id="txtOut1-date"
                                   placeholder="Date">
                            <span class="input-group-text">Time: </span>
                            <label for="txtOut1-time"></label><input type="time" class="form-control primary-key"
                                                                     name="pArrTime" id="txtOut1-time"
                                                                     placeholder="Time">
                        </div>
                    </div>
                </div>
                <!-- Out-patient Relation -->

                <div class="mb-3 form-buttonbar container">
                    <button type="submit" class="btn btn-success btn-block" id="btn-pt-create" name="btnPtCreate">Save
                    </button>
                    <button type="submit" class="btn btn-success btn-block" id="btn-pt-update" name="btnPtUpdate">
                        Update
                    </button>
                    <a href="list.php" class="btn btn-primary">Close</a>
                </div>
            </form>
        </div>
        <!-- 1st content -->

        <!-- 2nd content: Insurance relation -->
        <div class="container tab-pane fade" id="pt-tab-panel-2" role="tabpanel" aria-labelledby="pt-tab-panel-2">
            <form id="tab-2-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="container">
                    <div class="mb-3">
                        <label class="form-label" for="txt-tab2-1">Company Name</label>
                        <input type="text" class="form-control" name="Val1" id="txt-tab2-1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="txt-tab2-2">Branch Name</label>
                        <input type="text" class="form-control" name="Val2" id="txt-tab2-2">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="txt-tab2-3">Address</label>
                        <input type="text" class="form-control" name="Val3" id="txt-tab2-3">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="txt-tab2-4">Contact No</label>
                        <input type="text" class="form-control" name="Val4" id="txt-tab2-4">
                    </div>
                </div>
                <div class="mb-3 form-buttonbar container">
                    <button type="submit" class="btn btn-success btn-block" id="btn-ins-create" name="btnInsCreate">Save
                    </button>
                    <button type="submit" class="btn btn-success btn-block" id="btn-ins-update" name="btnInsUpdate">
                        Update
                    </button>
                    <button type="reset" class="btn btn-primary btn-block" name="btnClose">Reset</button>
                    <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?delete,ins" class="btn btn-danger">Delete</a>
                </div>
            </form>
        </div>
        <!-- 2nd content -->

        <!-- 3rd content: Emergency Contact Relation -->
        <div class="container tab-pane fade" id="pt-tab-panel-3" role="tabpanel" aria-labelledby="pt-tab-panel-3">
            <div class="row justify-content-center">
                <table class="table table-hover">
                    <col style="width: 16%;"/>
                    <col style="width: 16%;"/>
                    <col style="width: 16%;"/>
                    <col style="width: 16%;"/>
                    <col style="width: 16%;"/>
                    <col style="width: 20%;"/>
                    <thead style="background-color: blue; color: white">
                    <tr>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Relationship</th>
                        <th scope="col">Address</th>
                        <th scope="col">Contact Number</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <?php
                    if ($patient instanceof InPatient && $patient->emergency):
                        $row_count = 0;
                        foreach ($patient->emergency as $row):
                            ?>
                            <tr id="tab-3-row-<?= $row_count ?>">
                                <td><?= $row->fname ?></td>
                                <td><?= $row->lname ?></td>
                                <td><?= $row->relation ?></td>
                                <td><?= $row->address ?></td>
                                <td><?= $row->contact ?></td>
                                <td>
                                    <a href="#tab-3-row-edit-<?= $row_count ?>" class="btn btn-info data-row-toggle">Edit</a>
                                    <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?delete=&emg=&fname=<?=$row->fname?>&rel=<?=$row->relation?>" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                                  method="post">
                                <tr id="tab-3-row-edit-<?= $row_count ?>" class="update-row">
                                    <td>
                                        <?= $row->fname ?>
                                        <label>
                                            <input type="hidden" class="form-control" name="Val1"
                                                   value="<?= $row->fname ?>">
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input type="text" class="form-control" name="Val2"
                                                   value="<?= $row->lname ?>">
                                        </label>
                                    </td>
                                    <td>
                                        <?= $row->relation ?>
                                        <label>
                                            <input type="hidden" class="form-control" name="Val3"
                                                   value="<?= $row->relation ?>">
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input type="text" class="form-control" name="Val4"
                                                   value="<?= $row->address ?>">
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input type="text" class="form-control" name="Val5"
                                                   value="<?= $row->contact ?>">
                                        </label>
                                    </td>
                                    <td>
                                        <button type="submit" class="btn btn-success" name="btnEmgUpdate">Done</button>
                                        <a href="#tab-3-row-<?= $row_count++ ?>"
                                           class="btn btn-danger update-row-toggle">Close</a>
                                    </td>
                                </tr>
                            </form>
                        <?php
                        endforeach;
                    endif;
                    ?>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                          method="post">
                        <tr id="tab-3-row-add" class="add-row table-info">
                            <td>
                                <label>
                                    <input type="text" class="form-control tab-table-add-elements" name="Val1"">
                                </label>
                            </td>
                            <td>
                                <label>
                                    <input type="text" class="form-control tab-table-add-elements" name="Val2"">
                                </label>
                            </td>
                            <td>
                                <label>
                                    <input type="text" class="form-control tab-table-add-elements" name="Val3">
                                </label>
                            </td>
                            <td>
                                <label>
                                    <input type="text" class="form-control tab-table-add-elements" name="Val4">
                                </label>
                            </td>
                            <td>
                                <label>
                                    <input type="text" class="form-control tab-table-add-elements" name="Val5">
                                </label>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-success tab-table-add-elements" name="btnEmgAdd">
                                    Done
                                </button>
                                <button type="reset" class="btn btn-danger tab-table-add-elements btn-form-add-cancel"
                                        name="btnCancel">
                                    <a href="#tab-3-btn-add" style="color:#ffffff; text-decoration:none">Cancel</a>
                                </button>
                            </td>
                        </tr>
                    </form>
                </table>
            </div>
            <div class="container p-0">
                <a href="#tab-3-row-add" id="tab-3-btn-add" class="btn btn-primary btn-block btn-add">Add</a>
            </div>
        </div>
        <!-- 3rd content -->

        <!-- 4th content: Patient Records Relation -->
        <div class="container-fluid tab-pane fade" id="pt-tab-panel-4" role="tabpanel" aria-labelledby="pt-tab-panel-4">
            <div class="row justify-content-center">
                <table class="table table-hover">
                    <col style="width: 10%;"/>
                    <col style="width: 12%;"/>
                    <col style="width: 12%;"/>
                    <col style="width: 14%;"/>
                    <col style="width: 10%;"/>
                    <col style="width: 10%;"/>
                    <col style="width: 10%;"/>
                    <col style="width: 10%;"/>
                    <col style="width: 12%;"/>
                    <thead style="background-color: blue; color: white">
                    <tr>
                        <th scope="col">Nurse ID</th>
                        <th scope="col">Date</th>
                        <th scope="col">Time</th>
                        <th scope="col">Symptoms</th>
                        <th scope="col">Weight</th>
                        <th scope="col">Blood Pressure</th>
                        <th scope="col">Pulse</th>
                        <th scope="col">Temperature</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <?php
                    if ($patient instanceof InPatient && $patient->records):
                        $row_count = 0;
                        foreach ($patient->records as $row):
                            ?>
                            <tr id="tab-4-row-<?= $row_count ?>">
                                <td><?= $row->nurse_id ?></td>
                                <td><?= $row->date ?></td>
                                <td><?= $row->time ?></td>
                                <td><?= $row->getSymptoms() ?></td>
                                <td><?= $row->weight ?></td>
                                <td><?= $row->pressure ?></td>
                                <td><?= $row->pulse ?></td>
                                <td><?= $row->temperature ?></td>
                                <td>
                                    <a href="#tab-4-row-edit-<?= $row_count ?>" class="btn btn-info data-row-toggle">Edit</a>
                                    <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?delete=&rec=&date=<?=$row->date?>&time=<?=$row->time?>" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                                  method="post">
                                <tr id="tab-4-row-edit-<?= $row_count ?>" class="update-row align-middle">
                                    <td>
                                        <label>
                                            <input type="text" class="form-control" name="Val1"
                                                   value="<?= $row->nurse_id ?>">
                                        </label>
                                    </td>
                                    <td>
                                        <?= $row->date ?>
                                        <label>
                                            <input type="hidden" class="form-control" name="Val2"
                                                   value="<?= $row->date ?>">
                                        </label>
                                    </td>
                                    <td>
                                        <?= $row->time ?>
                                        <label>
                                            <input type="hidden" class="form-control" name="Val3"
                                                   value="<?= $row->time ?>">
                                        </label>
                                    </td>
                                    <td>
                                        <label style="width: 100%!important;">
                                            <textarea class="form-control" rows="2" name="Val4"><?= $row->getSymptoms() ?></textarea>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input type="text" class="form-control" name="Val5"
                                                   value="<?= $row->weight ?>">
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input type="text" class="form-control" name="Val6"
                                                   value="<?= $row->pressure ?>">
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input type="text" class="form-control" name="Val7"
                                                   value="<?= $row->pulse ?>">
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input type="text" class="form-control" name="Val8"
                                                   value="<?= $row->temperature ?>">
                                        </label>
                                    </td>
                                    <td>
                                        <button type="submit" class="btn btn-success" name="btnRecUpdate">Done</button>
                                        <a href="#tab-4-row-<?= $row_count++ ?>"
                                           class="btn btn-danger update-row-toggle">Close</a>
                                    </td>
                                </tr>
                            </form>
                        <?php
                        endforeach;
                    endif;
                    ?>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                          method="post">
                        <tr id="tab-4-row-add" class="add-row table-info align-middle">
                            <td>
                                <label>
                                    <input type="text" class="form-control tab-table-add-elements" name="Val1"">
                                </label>
                            </td>
                            <td>
                                <label>
                                    <input type="date" class="form-control tab-table-add-elements" name="Val2"">
                                </label>
                            </td>
                            <td>
                                <label style="width: 100%!important;">
                                    <input type="time" class="form-control tab-table-add-elements" name="Val3">
                                </label>
                            </td>
                            <td>
                                <label style="width: 100%!important;">
                                    <textarea class="form-control tab-table-add-elements" rows="2"
                                              name="Val4"></textarea>
                                </label>
                            </td>
                            <td>
                                <label>
                                    <input type="text" class="form-control tab-table-add-elements" name="Val5">
                                </label>
                            </td>
                            <td>
                                <label>
                                    <input type="text" class="form-control tab-table-add-elements" name="Val6">
                                </label>
                            </td>
                            <td>
                                <label>
                                    <input type="text" class="form-control tab-table-add-elements" name="Val7">
                                </label>
                            </td>
                            <td>
                                <label>
                                    <input type="text" class="form-control tab-table-add-elements" name="Val8">
                                </label>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-success tab-table-add-elements" name="btnRecAdd">
                                    Done
                                </button>
                                <button type="reset" class="btn btn-danger tab-table-add-elements btn-form-add-cancel"
                                        name="btnCancel">
                                    <a href="#tab-4-btn-add" style="color:#ffffff; text-decoration:none">Cancel</a>
                                </button>
                            </td>
                        </tr>
                    </form>
                </table>
            </div>
            <div class="container-fluid p-0">
                <a href="#tab-4-row-add" id="tab-4-btn-add" class="btn btn-primary btn-block btn-add">Add</a>
            </div>
        </div>
        <!-- 4th content -->
    </div>
    <!-- Tabs content -->
</div>
</body>
</html>