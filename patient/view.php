<?php
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
if (isset($_SESSION['patient_id'])) {
    // form is preparing to update the patient record
    $patient = load_patient($_SESSION['patient_id']);
} else {
    // redirect to previous page
    header("Location: " . $_SESSION['previous_page']);
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
    <?php if ($patient instanceof InPatient): ?>
        <!-- Tabs navs -->
        <ul class="nav nav-tabs nav-justified mb-3" id="pt-navbar" role="tablist">
            <li class="nav-item" role="presentation" id="nav-itm-1">
                <a class="nav-link active" id="pt-tab-1" data-bs-toggle="tab" href="#pt-tab-panel-1" role="tab"
                   aria-controls="pt-tab-panel-1" aria-selected="true">Information</a>
            </li>
            <li class="nav-item" role="presentation" id="nav-itm-2">
                <a class="nav-link id=" id="pt-tab-2" data-bs-toggle="tab" href="#pt-tab-panel-2" role="tab"
                   aria-controls="pt-tab-panel-2" aria-selected="false">In-patient status</a>
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
    <?php endif; ?>

    <!-- Tabs content -->
    <div class="tab-content container" id="nav-patient-content">

        <!-- 1st content: Patient relation -->
        <div class="container tab-pane active" id="pt-tab-panel-1" role="tabpanel" aria-labelledby="pt-tab-panel-1">
            <div class="mb-3">
                <h2>Patient information</h2>
            </div>
            <div class="row justify-content-center mb-3">
                <table class="table table-hover">
                    <tr>
                        <td>Patient ID</td>
                        <td><?php echo $patient->getPatientId(); ?></td>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td><?php echo $patient->name; ?></td>
                    </tr>
                    <tr>
                        <td>Patient Type</td>
                        <td><?php echo $patient->getType(); ?></td>
                    </tr>
                    <?php if ($patient instanceof InPatient): ?>
                        <!-- In-patient relation -->
                        <tr>
                            <td>Date of Birth</td>
                            <td><?php echo $patient->dob; ?></td>
                        </tr>
                        <tr>
                            <td>Admitted Date</td>
                            <td><?php echo $patient->getAddDate(); ?></td>
                        </tr>
                        <tr>
                            <td>Admitted Time</td>
                            <td><?php echo $patient->getAddTime(); ?></td>
                        </tr>
                        <tr>
                            <td>Discharge Date</td>
                            <td><?php echo $patient->getDisDate(); ?></td>
                        </tr>
                        <tr>
                            <td>Discharge Time</td>
                            <td><?php echo $patient->getDisTime(); ?></td>
                        </tr>
                        <tr>
                            <td>Primary Care Doctor</td>
                            <td><?php echo $patient->pc_doc; ?></td>
                        </tr>
                        <tr>
                            <td>Bed ID</td>
                            <td><?php echo $patient->bed_id; ?></td>
                        </tr>
                        <!-- In-patient Relation -->
                    <?php elseif ($patient instanceof OutPatient): ?>
                        <!-- Out-patient relation -->
                        <tr>
                            <td>Arrived Date</td>
                            <td><?php echo $patient->getArrDate(); ?></td>
                        </tr>
                        <tr>
                            <td>Arrived Time</td>
                            <td><?php echo $patient->getArrTime(); ?></td>
                        </tr>
                        <!-- Out-patient relation -->
                    <?php endif; ?>
                </table>
            </div>
        </div>
        <!-- 1st content -->

        <?php if ($patient instanceof InPatient && $patient->insurance): ?>
            <!-- 2nd content: Insurance -->
            <div class="container tab-pane fade" id="pt-tab-panel-2" role="tabpanel" aria-labelledby="pt-tab-panel-2">
                <div class="mb-3">
                    <h2>Insurance</h2>
                </div>
                <div class="row justify-content-center mb-3">
                    <table class="table">
                        <tr>
                            <td>Company Name</td>
                            <td><?php echo $patient->insurance->company; ?></td>
                        </tr>
                        <tr>
                            <td>Branch Name</td>
                            <td><?php echo $patient->insurance->branch; ?></td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td><?php echo $patient->insurance->address; ?></td>
                        </tr>
                        <tr>
                            <td>Contact Number</td>
                            <td><?php echo $patient->insurance->contact; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- 2nd content -->
        <?php elseif ($patient instanceof InPatient): ?>
            <!-- 2nd content: Empty -->
            <div>
                <p align="center">The patient hasn't any insurance information yet.</p>
            </div>
            <!-- 2nd content: Empty -->
        <?php endif; ?>

        <!-- 3rd content: Emergency Contact Relation -->
        <div class="container tab-pane fade" id="pt-tab-panel-3" role="tabpanel" aria-labelledby="pt-tab-panel-3">
            <div class="row justify-content-center">
                <table class="table table-hover">
                    <col style="width: 20%;"/>
                    <col style="width: 20%;"/>
                    <col style="width: 20%;"/>
                    <col style="width: 20%;"/>
                    <col style="width: 20%;"/>
                    <thead style="background-color: blue; color: white">
                    <tr>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Relationship</th>
                        <th scope="col">Address</th>
                        <th scope="col">Contact Number</th>
                    </tr>
                    </thead>
                    <?php
                    if ($patient instanceof InPatient && $patient->emergency):
                        $row_count = 0;
                        foreach ($patient->emergency as $row):
                            ?>
                            <tr id="tab-3-row-<?= $row_count++ ?>">
                                <td><?= $row->fname ?></td>
                                <td><?= $row->lname ?></td>
                                <td><?= $row->relation ?></td>
                                <td><?= $row->address ?></td>
                                <td><?= $row->contact ?></td>
                            </tr>
                        <?php
                        endforeach;
                    endif;
                    ?>
                </table>
            </div>
        </div>
        <!-- 3rd content -->

        <!-- 4th content: Patient Records Relation -->
        <div class="container-fluid tab-pane fade" id="pt-tab-panel-4" role="tabpanel" aria-labelledby="pt-tab-panel-4">
            <div class="row justify-content-center">
                <table class="table table-hover">
                    <col style="width: 12%;"/>
                    <col style="width: 12%;"/>
                    <col style="width: 12%;"/>
                    <col style="width: 16%;"/>
                    <col style="width: 12%;"/>
                    <col style="width: 12%;"/>
                    <col style="width: 12%;"/>
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
                    </tr>
                    </thead>
                    <?php
                    if ($patient instanceof InPatient && $patient->records):
                        $row_count = 0;
                        foreach ($patient->records as $row):
                            ?>
                            <tr id="tab-4-row-<?= $row_count++ ?>">
                                <td><?= $row->nurse_id ?></td>
                                <td><?= $row->date ?></td>
                                <td><?= $row->time ?></td>
                                <td><?= $row->getSymptoms() ?></td>
                                <td><?= $row->weight ?></td>
                                <td><?= $row->pressure ?></td>
                                <td><?= $row->pulse ?></td>
                                <td><?= $row->temperature ?></td>
                            </tr>
                        <?php
                        endforeach;
                    endif;
                    ?>
                </table>
            </div>
        </div>
        <!-- 4th content -->
    </div>
    <!-- Tabs content -->
    <div class="container">
        <a href="<?php echo $_SESSION['previous_page']; ?>" class="btn btn-danger btn-block">Close</a>
    </div>
</div>
</body>
</html>
