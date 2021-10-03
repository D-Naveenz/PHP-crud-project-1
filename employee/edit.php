<?php

require_once "Employee.php";
require_once "Medical.php";
require_once "NonMedical.php";
require_once "Doctor.php";
require_once "Nurse.php";
require_once "Attendant.php";
require_once "Cleaner.php";

function load_employee($id): Doctor|Nurse|Attendant|Cleaner|null
{
    // form is preparing to update the employee record
    $employee = new Employee($id);
    if ($employee->isExistsInDb()) {
        if ($employee->isMedical()) {
            // create new medical object from the parent object
            $employee = new Medical($employee->getEmpNo());
            if ($employee->isDoctor()) {
                // create new doctor object from the parent object
                $employee = new Doctor($employee->getEmpNo());
            } else {
                // create new nurse object from the parent object
                $employee = new Nurse($employee->getEmpNo());
            }
        } else {
            // create new non-medical object with the same name
            $employee = new NonMedical($employee->getEmpNo());
            if ($employee->isCleaner()) {
                // create new cleaner object with the same name
                $employee = new Cleaner($employee->getEmpNo());
            } else {
                // create new attendant object with the same name
                $employee = new Attendant($employee->getEmpNo());
            }
        }
        return $employee;
    } else {
        // redirect to previous page
        header("Location: " . $_SESSION['previous_page']);
        return null;
    }
}

// store session data with checking get requests
if (isset($_GET['update'])) {
    // form is preparing to update the employee record
    $employee = load_employee($_SESSION['employee_id']);
} elseif (isset($_GET['new'])) {
    // form is preparing to create the employee record
    $employee = new Employee();
} else {
    // form is loading the current state
    $employee = load_employee($_SESSION['employee_id']);
}

// global variables
$id = $employee->getEmpNo();

// Delete Requests
if (isset($_GET['delete'])) {
    $tmp_emp = load_employee($id);
    $tmp_emp->deleteRow();

    // reload the page
    header("Location: ".$_SERVER["PHP_SELF"]."?update");
}

// Post requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tmp_emp = load_employee($id);
    $tmp_emp->password = $_POST['Val-1-2'];
    $tmp_emp->name = $_POST['Val-1-3'];
    $tmp_emp->address = $_POST['Val-1-4'];
    $tmp_emp->contact = $_POST['Val-1-5'];
    $tmp_emp->status = $_POST['Val-1-6'];
    $tmp_emp->type = $_POST['Val-1-1-2'];

    if ($tmp_emp instanceof Medical) {
        $tmp_emp->mc_reg_no = $_POST['Val-2-1'];
        $tmp_emp->joined = $_POST['Val-2-2'];
        $tmp_emp->setResigned($_POST['Val-2-3']);
        $tmp_emp->medical_type = $_POST['Val-1-1-3'];

        if ($tmp_emp instanceof Doctor) {
            $tmp_emp->dea = $_POST['Val-3-1'];
            $tmp_emp->special = $_POST['Val-3-2'];
        }
    } elseif ($tmp_emp instanceof Cleaner) {
        $tmp_emp->contact = $_POST['Val-4-1'];
        $tmp_emp->start = $_POST['Val-4-2'];
        $tmp_emp->end = $_POST['Val-4-2'];
        $tmp_emp->non_medi_type = $_POST['Val-1-1-3'];
    } elseif ($tmp_emp instanceof Attendant) {
        $tmp_emp->hr_rate = $_POST['Val-5-1'];
        $tmp_emp->non_medi_type = $_POST['Val-1-1-3'];
    }

    if (isset($_POST['btnCreate'])) {
        $tmp_emp->insertToDb();
    } elseif (isset($_POST['btnUpdate'])) {
        $tmp_emp->updateRow();
    }

    $employee = $tmp_emp;

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
        function medicalEvent(trigger) {
            // hide / disable forms when change the radio button
            if (trigger) {
                // disable toolbar
                $('.tb-non-medical').prop('disabled', true);
                $('.tb-medical').prop('disabled', false);
                // hide non-medical sections
                $('#emp-cl').hide("slow");
                $('#emp-att').hide("slow");
                // show non-medical sections
                $('#emp-medi').show("slow");
                // Add value
                $('#emp-type').val("Medical");
            } else {
                // disable toolbar
                $('.tb-medical').prop('disabled', true);
                $('.tb-non-medical').prop('disabled', false);
                // show non-medical sections
                $('#emp-cl').show("slow");
                $('#emp-att').show("slow");
                // hide non-medical sections
                $('#emp-medi').hide("slow");
                // Add value
                $('#emp-type').val("Nonmedical");
            }
        }

        function docEvent(trigger) {
            // hide / disable forms when change the radio button
            if (trigger) {
                // show doctor section
                $('#emp-doc').show("slow");
                // Add value
                $('#sub-type').val("Doctor");
            } else {
                // hide doctor section
                $('#emp-doc').hide("slow");
                // Add value
                $('#sub-type').val("Nurse");
            }
        }

        function cleanerEvent(trigger) {
            // hide / disable forms when change the radio button
            if (trigger) {
                // show cleaner section
                $('#emp-cl').show("slow");
                $('#emp-att').hide("slow");
                // Add value
                $('#sub-type-type').val("Cleaner");
            } else {
                // hide cleaner section
                $('#emp-doc').hide("slow");
                $('#emp-att').show("slow");
                // Add value
                $('#sub-type-type').val("Attendant");
            }
        }

        window.onload = () => {
            medicalEvent(true);
            docEvent(true);

            <?php if (isset($_GET['update'])): ?>
            // Initializing form elements according to the update request
            // hide save button
            $('#btn-emp-create').hide();

            <?php if ($employee instanceof Medical): ?>
                medicalEvent(true);
                // Medical details
                $('#txtMedi1').val("<?= $employee->mc_reg_no ?>");
                $('#txtMedi2').val("<?= $employee->joined ?>");
                $('#txtMedi3').val("<?= $employee->getResigned() ?>");
                <?php if ($employee instanceof Doctor): ?>
                    docEvent(true);
                    $('#txtDoc1').val("<?= $employee->dea?>");
                    $('#txtDoc2').val("<?= $employee->special ?>");
                <?php else: ?>
                    docEvent(false);
                <?php endif; ?>
            <?php elseif ($employee instanceof Cleaner): ?>
                cleanerEvent(true);
                $('#txtCl1').val("<?= $employee->contact ?>");
                $('#txtCl2').val("<?= $employee->start ?>");
                $('#txtCl3').val("<?= $employee->end ?>");
            <?php elseif ($employee instanceof Attendant): ?>
                cleanerEvent(false);
                $('#txtAtt1').val("<?= $employee->hr_rate ?>");
            <?php endif; ?>
            <?php endif; ?>
        };


        $(document).ready(function () {
            $('#toolbar-medical').click(function () {
                medicalEvent(true);
            })

            $('#toolbar-non-medical').click(function () {
                medicalEvent(false);
            })

            $('#toolbar-Doc').click(function () {
                docEvent(true);
            })

            $('#toolbar-Nurse').click(function () {
                docEvent(false);
            })

            $('#toolbar-Cleaner').click(function () {
                cleanerEvent(true);
            })

            $('#toolbar-Attendant').click(function () {
                cleanerEvent(false);
            })
        });
    </script>
    <!-- Custom Javascript -->

    <title>Document</title>
</head>
<body>
<!-- Body Header -->
<div class="container-fluid p-5 bg-primary text-white text-center">
    <?php if (isset($_GET['update']) && !empty($_GET['update'])): ?>
        <h1>Update <?= $employee->name ?></h1>
    <?php else: ?>
        <h1>Add New Employee</h1>
    <?php endif; ?>
    <p>Suwa Sahana Hospital</p>
</div>
<div class="container-fluid">
    <div class="container" style="margin-top: 20px;">
        <div class="container p-4">
            <div class="btn-toolbar" role="toolbar" aria-label="Employee Job Status">
                <div class="btn-group me-2" role="group" aria-label="Employee Type">
                    <button type="button" class="btn btn-primary tb-employee" id="toolbar-medical">Medical</button>
                    <button type="button" class="btn btn-primary tb-employee" id="toolbar-non-medical">Non-medical</button>
                </div>
                <div class="btn-group me-2" role="group" aria-label="Medical Type">
                    <button type="button" class="btn btn-info tb-medical" id="toolbar-Doc">Doctor</button>
                    <button type="button" class="btn btn-info tb-medical" id="toolbar-Nurse">Nurse</button>
                </div>
                <div class="btn-group" role="group" aria-label="Non-medical Type">
                    <button type="button" class="btn btn-warning tb-non-medical" id="toolbar-Cleaner">Cleaner</button>
                    <button type="button" class="btn btn-warning tb-non-medical" id="toolbar-Attendant">Attendant</button>
                </div>
            </div>
        </div>

        <!-- Employee Content -->
        <div class="container">
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                <!-- Employee Relation -->
                <div class="container" id="emp-info">
                    <div class="mb-3">
                        <h2>Employee information</h2>
                        <input type="hidden" name="Val-1-1-1" value="<?php echo $employee->getEmpNo(); ?>" required/>
                        <input type="hidden" name="Val-1-1-2" id="emp-type" required/>
                        <input type="hidden" name="Val-1-1-3" id="sub-type" required/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="empNo">Employee No</label>
                        <div class="input-group mb-3" id="pt-id-field">
                            <span class="input-group-text" id="txtEmp1">E</span>
                            <input type="text" class="form-control"
                                   id="empNo"
                                   placeholder="Employee Number will be generated automatically"
                                   aria-label="Employee Number will be generated automatically" aria-describedby="txtPt1"
                                   value="<?=$employee->getEmpNoNums()?>" readonly/>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="txtEmp2">Password</label>
                        <input type="text" class="form-control" name="Val-1-2" id="txtEmp2"
                               value="<?=$employee->password?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="txtEmp3">Name</label>
                        <input type="text" class="form-control" name="Val-1-3" id="txtEmp3"
                               value="<?=$employee->name?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="txtEmp4">Address</label>
                        <input type="text" class="form-control" name="Val-1-4" id="txtEmp4"
                               value="<?=$employee->address?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="txtEmp5">Contact Number</label>
                        <input type="text" class="form-control" name="Val-1-3" id="txtEmp5"
                               value="<?=$employee->contact?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="txtEmp6">Working Status</label>
                        <input type="text" class="form-control" name="Val-1-3" id="txtEmp6"
                               value="<?=$employee->status?>">
                    </div>
                </div>
                <!-- Employee Relation -->

                <!-- Medical Relation -->
                <div class="container" id="emp-medi">
                    <div class="mb-3">
                        <label class="form-label" for="txtMedi1">MC Register Number</label>
                        <input type="text" class="form-control" name="Val-2-1" id="txtMedi1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="txtMedi2">Joined</label>
                        <div class="input-group">
                            <span class="input-group-text">Date: </span>
                            <input type="date" class="form-control primary-key" name="Val-2-2" id="txtMedi2"
                                   placeholder="Date">
                            <button class="btn btn-outline-secondary" type="button" id="btnMedi2">Today</button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="txtMedi3">Resigned</label>
                        <div class="input-group">
                            <span class="input-group-text">Date: </span>
                            <input type="date" class="form-control" name="Val-2-3" id="txtMedi3" placeholder="Date">
                        </div>
                    </div>
                </div>
                <!-- Medical Relation -->

                <!-- Doctor Relation -->
                <div class="container" id="emp-doc">
                    <div class="mb-3">
                        <label class="form-label" for="txtDoc1">DEA</label>
                        <input type="time" class="form-control" name="Val-3-1" id="txtDoc1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="txtDoc2">Aria of speciality</label>
                        <input type="time" class="form-control" name="Val-3-2" id="txtDoc2">
                    </div>
                </div>
                <!-- Doctor Relation -->

                <!-- Cleaner Relation -->
                <div class="container" id="emp-cl">
                    <div class="mb-3">
                        <label class="form-label" for="txtCl1">Contract Number</label>
                        <input type="time" class="form-control" name="Val-4-1" id="txtCl1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="txtCl2">Start</label>
                        <div class="input-group">
                            <span class="input-group-text">Date: </span>
                            <input type="date" class="form-control primary-key" name="Val-4-2" id="txtCl2"
                                   placeholder="Date">
                            <button class="btn btn-outline-secondary" type="button" id="btnCl2">Today</button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="txtCl3">End</label>
                        <div class="input-group">
                            <span class="input-group-text">Date: </span>
                            <input type="date" class="form-control" name="Val-4-3" id="txtCl3" placeholder="Date">
                        </div>
                    </div>
                </div>
                <!-- Cleaner Relation -->

                <!-- Attendant Relation -->
                <div class="container" id="emp-att">
                    <div class="mb-3">
                        <label class="form-label" for="txtAtt1">Hourly Rate</label>
                        <input type="time" class="form-control" name="Val-5-1" id="txtAtt1">
                    </div>
                </div>
                <!-- Attendant Relation -->

                <div class="mb-3 form-buttonbar container">
                    <button type="submit" class="btn btn-success btn-block" id="btn-emp-create" name="btnCreate">Save
                    </button>
                    <button type="submit" class="btn btn-success btn-block" id="btn-emp-update" name="btnUpdate">
                        Update
                    </button>
                    <a href="list.php" class="btn btn-primary">Close</a>
                </div>
            </form>
        </div>
        <!-- Employee Content -->
    </div>
</div>
</body>
</html>
