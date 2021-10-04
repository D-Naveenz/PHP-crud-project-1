<?php
require_once "edit-helper.php";

if (isset($_SESSION['target_emp_id'])) {
    // form is preparing to update the patient record
    $employee = load_employee($_SESSION['target_emp_id']);
} else {
    // redirect to previous page
    $employee = load_employee($_SESSION['employee_id']);
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

    <title>View Employee Details</title>
</head>
<body>
<!-- Body Header -->
<div class="container-fluid p-5 bg-primary text-white text-center">
    <h1>Employee Profile</h1>
    <p>Suwa Sahana Hospital</p>
</div>
<!-- Body Header -->

<div class="container" style="margin-top: 20px;">
    <!-- Employee Content -->
    <div class="container">
        <!-- Employee Relation -->
        <div class="container" id="emp-info">
            <div class="mb-3">
                <h2>Employee Information</h2>
            </div>

            <table class="table table-hover">
                <col style="width: 40%;"/>
                <col style="width: 60%;"/>
                <tr>
                    <td>Employee Number</td>
                    <td><?php echo $employee->getEmpNo(); ?></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><?php echo $employee->password; ?></td>
                </tr>
                <tr>
                    <td>Employee Name</td>
                    <td><?php echo $employee->name; ?></td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td><?php echo $employee->address; ?></td>
                </tr>
                <tr>
                    <td>Contact Number</td>
                    <td><?php echo $employee->contact; ?></td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td><?php echo $employee->status; ?></td>
                </tr>
            </table>
        </div>
        <!-- Employee Relation -->

        <?php if ($employee instanceof Medical): ?>
            <!-- Medical Relation -->
            <div class="container" id="emp-medi">
                <table class="table table-hover">
                    <col style="width: 40%;"/>
                    <col style="width: 60%;"/>
                    <tr>
                        <td>MC Register Number</td>
                        <td><?php echo $employee->mc_reg_no; ?></td>
                    </tr>
                    <tr>
                        <td>Joined Date</td>
                        <td><?php echo $employee->joined; ?></td>
                    </tr>
                    <tr>
                        <td>Resigned Date</td>
                        <td><?php echo $employee->getResigned(); ?></td>
                    </tr>
                </table>
            </div>
            <!-- Medical Relation -->

            <?php if ($employee instanceof Doctor): ?>
                <!-- Doctor Relation -->
                <div class="container" id="emp-doc">
                    <table class="table table-hover">
                        <col style="width: 40%;"/>
                        <col style="width: 60%;"/>
                        <tr>
                            <td>DEA</td>
                            <td><?php echo $employee->dea; ?></td>
                        </tr>
                        <tr>
                            <td>Aria of Speciality</td>
                            <td><?php echo $employee->special; ?></td>
                        </tr>
                    </table>
                </div>
                <!-- Doctor Relation -->
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($employee instanceof Cleaner): ?>
            <!-- Cleaner Relation -->
            <div class="container" id="emp-cl">
                <table class="table table-hover">
                    <col style="width: 40%;"/>
                    <col style="width: 60%;"/>
                    <tr>
                        <td>Contract Number</td>
                        <td><?php echo $employee->contract; ?></td>
                    </tr>
                    <tr>
                        <td>Start Date</td>
                        <td><?php echo $employee->start; ?></td>
                    </tr>
                    <tr>
                        <td>End Date</td>
                        <td><?php echo $employee->end; ?></td>
                    </tr>
                </table>
            </div>
            <!-- Cleaner Relation -->
        <?php endif; ?>

        <?php if ($employee instanceof Attendant): ?>
            <!-- Attendant Relation -->
            <div class="container" id="emp-att">
                <table class="table table-hover">
                    <col style="width: 40%;"/>
                    <col style="width: 60%;"/>
                    <tr>
                        <td>Hourly Rate</td>
                        <td><?php echo $employee->hr_rate; ?></td>
                    </tr>
                </table>
            </div>
            <!-- Attendant Relation -->
        <?php endif; ?>

        <div class="container">
            <a href="<?php echo $_SESSION['previous_page']; ?>" class="btn btn-danger btn-block">Close</a>
        </div>
    </div>
    <!-- Employee Content -->
</div>
</body>
</html>
