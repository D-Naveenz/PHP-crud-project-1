<?php

require_once "edit-helper.php";

// store session data with checking get requests
if (isset($_GET['update'])) {
    // form is preparing to update the employee record
    $employee = load_employee($_SESSION['target_emp_id']);
} elseif (isset($_GET['new'])) {
    // form is preparing to create the employee record
    $employee = new Employee();
} else {
    // form is loading the current state
    $employee = load_employee($_SESSION['target_emp_id']);
}

// global variables
$id = $employee->getEmpNo();

// Delete Requests
if (isset($_GET['delete'])) {
    $tmp_emp = load_employee($id);
    $tmp_emp->deleteRow();

    // reload the page
    header("Location: " . $_SERVER["PHP_SELF"] . "?update");
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
    header("Location: " . $_SERVER["PHP_SELF"] . "?update");
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
        // hide / disable forms when change the radio button
        function medicalEvent() {
            if ($('#rad-medical').is(':checked')) {
                // change radio buttons
                if (!$('#rad-nurse').is(':checked')) {
                    $('#rad-doc').prop('checked', true);
                }
                $(".non-medical").prop('disabled', true);
                $(".medical").prop('disabled', false);
                // show medical sections
                $('#emp-medi').show("slow");
            } else {
                // change radio buttons
                if (!$('#rad-attendant').is(':checked')) {
                    $('#rad-cleaner').prop('checked', true);
                }
                $(".non-medical").prop('disabled', false);
                $(".medical").prop('disabled', true);
                // hide medical sections
                $('#emp-medi').hide("slow");
            }
        }

        function subEvent() {
            if ($('#rad-doc').is(':checked')) {
                // show doctor sub section
                $('#emp-doc').show("slow");
                $('#emp-cl').hide("slow");
                $('#emp-att').hide("slow");
            } else if ($('#rad-nurse').is(':checked')) {
                // hide all sub sections
                $('#emp-doc').hide("slow");
                $('#emp-cl').hide("slow");
                $('#emp-att').hide("slow");
            } else if ($('#rad-cleaner').is(':checked')) {
                // show cleaner sub section
                $('#emp-doc').hide("slow");
                $('#emp-cl').show("slow");
                $('#emp-att').hide("slow");
            } else {
                // show attendant sub section
                $('#emp-doc').hide("slow");
                $('#emp-cl').hide("slow");
                $('#emp-att').show("slow");
            }
        }

        window.onload = () => {
            <?php if (isset($_GET['update'])): ?>
            // Initializing form elements according to the update request
            // hide save button
            $('#btn-emp-create').hide();

            <?php if ($employee instanceof Medical): ?>
            $('#rad-medical').prop('checked', true);
            // Medical details
            $('#txtMedi1').val("<?= $employee->mc_reg_no ?>");
            $('#txtMedi2').val("<?= $employee->joined ?>");
            $('#txtMedi3').val("<?= $employee->getResigned() ?>");
            <?php if ($employee instanceof Doctor): ?>
            $('#rad-doc').prop('checked', true);
            $('#txtDoc1').val("<?= $employee->dea?>");
            $('#txtDoc2').val("<?= $employee->special ?>");
            <?php else: ?>
            $('#rad-nurse').prop('checked', true);
            <?php endif; ?>
            <?php elseif ($employee instanceof Cleaner): ?>
            $('#rad-cleaner').prop('checked', true);
            $('#txtCl1').val("<?= $employee->contact ?>");
            $('#txtCl2').val("<?= $employee->start ?>");
            $('#txtCl3').val("<?= $employee->end ?>");
            <?php elseif ($employee instanceof Attendant): ?>
            $('#rad-attendant').prop('checked', true);
            $('#txtAtt1').val("<?= $employee->hr_rate ?>");
            <?php endif; ?>
            // disable toolbar
            medicalEvent();
            subEvent();
            $('#button-bar input:radio').prop('disabled', true);
            <?php endif; ?>
        };


        $(document).ready(function () {
            medicalEvent();
            subEvent();
            // hide update button
            $('#btn-emp-update').hide();

            // Get today's date
            function today() {
                let currentDate = new Date();
                return currentDate.getFullYear() +
                    "-" + (((currentDate.getMonth()) + 1) < 10 ? '0' : '') + ((currentDate.getMonth()) + 1) +
                    "-" + (currentDate.getDate() < 10 ? '0' : '') + currentDate.getDate();
            }

            // Medical / Non-medical event handler
            $('input:radio[name="empType"]').change(() => {
                medicalEvent();
                subEvent();
            });

            // Sub type event handler
            $('input:radio[name="subType"]').change(() => {
                subEvent();
            });

            $('#btnMedi2').click(() => {
                $('#txtMedi2').val(today());
            })

            $('#btnCl2').click(() => {
                $('#txtCl2').val(today());
            })
        });
    </script>
    <!-- Custom Javascript -->

    <title>Document</title>
</head>
<body>
<!-- Body Header -->
<div class="container-fluid p-5 bg-primary text-white text-center">
    <?php if (isset($_GET['update'])): ?>
        <h1>Update <?= $employee->name ?></h1>
    <?php else: ?>
        <h1>Add New Employee</h1>
    <?php endif; ?>
    <p>Suwa Sahana Hospital</p>
</div>
<div class="container-fluid">
    <div class="container" style="margin-top: 20px;">
        <!-- Employee Content -->
        <div class="container">
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                <!-- Employee Relation -->
                <div class="container" id="emp-info">
                    <div class="mb-3">
                        <h2>Employee Information</h2>
                        <input type="hidden" name="Val-1-1-1" value="<?php echo $employee->getEmpNo(); ?>" required/>
                    </div>

                    <!-- Toggle buttons -->
                    <div class="d-flex flex-row mb-3 align-items-center" id="button-bar">
                        <div class="btn-group me-2" role="group" aria-label="Employee Job Status">
                            <input type="radio" class="btn-check" name="empType" id="rad-medical" autocomplete="off"
                                   value="Medical" checked>
                            <label class="btn btn-outline-primary" for="rad-medical">Medical</label>

                            <input type="radio" class="btn-check" name="empType" id="rad-non-medical" autocomplete="off"
                                   value="Non medical">
                            <label class="btn btn-outline-primary" for="rad-non-medical">Non-Medical</label>
                        </div>
                        <div class="btn-group" role="group" aria-label="Medical Type">
                            <input type="radio" class="btn-check medical" name="subType" id="rad-doc" autocomplete="off"
                                   value="Doctor" checked>
                            <label class="btn btn-outline-info" for="rad-doc">Doctor</label>

                            <input type="radio" class="btn-check medical" name="subType" id="rad-nurse"
                                   autocomplete="off" value="Nurse">
                            <label class="btn btn-outline-info" for="rad-nurse">Nurse</label>

                            <input type="radio" class="btn-check non-medical" name="subType" id="rad-cleaner"
                                   autocomplete="off" value="Cleaner">
                            <label class="btn btn-outline-warning" for="rad-cleaner">Cleaner</label>

                            <input type="radio" class="btn-check non-medical" name="subType" id="rad-attendant"
                                   autocomplete="off" value="Attendant">
                            <label class="btn btn-outline-warning" for="rad-attendant">Attendant</label>
                        </div>
                    </div>
                    <!-- Toggle buttons -->

                    <div class="mb-3">
                        <label class="form-label" for="empNo">Employee No</label>
                        <div class="input-group mb-3" id="pt-id-field">
                            <span class="input-group-text" id="txtEmp1">E</span>
                            <input type="text" class="form-control"
                                   id="empNo"
                                   placeholder="Employee Number will be generated automatically"
                                   aria-label="Employee Number will be generated automatically"
                                   aria-describedby="txtPt1"
                                   value="<?= $employee->getEmpNoNums() ?>" readonly/>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="txtEmp2">Password</label>
                        <input type="text" class="form-control" name="Val-1-2" id="txtEmp2"
                               value="<?= $employee->password ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="txtEmp3">Name</label>
                        <input type="text" class="form-control" name="Val-1-3" id="txtEmp3"
                               value="<?= $employee->name ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="txtEmp4">Address</label>
                        <input type="text" class="form-control" name="Val-1-4" id="txtEmp4"
                               value="<?= $employee->address ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="txtEmp5">Contact Number</label>
                        <input type="text" class="form-control" name="Val-1-3" id="txtEmp5"
                               value="<?= $employee->contact ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="txtEmp6">Working Status</label>
                        <input type="text" class="form-control" name="Val-1-3" id="txtEmp6"
                               value="<?= $employee->status ?>">
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
                            <button class="btn btn-outline-secondary btn-today" type="button" id="btnMedi2">Today
                            </button>
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
                            <button class="btn btn-outline-secondary btn-today" type="button" id="btnCl2">Today</button>
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
