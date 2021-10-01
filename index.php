<?php
require_once "core/config.php";

// generate session variables to locate the current page
$_SESSION['previous_page'] = getAbsUrl();

// define variables and set to empty values
$role = $userid = $pass = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['btnLogin'])) {
        $role = $_POST['role'];
        $userid = $_POST['userid'];
        $pass = $_POST['password'];

        //header("Location: ./patient/edit.php");
        //header("Location: ./patient/view.php?id=PT0001");
        //header("Location: ./beds.php");
        header("Location: ./diags.php");
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
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

    <!-- Custom Style -->
    <link rel="stylesheet" type="text/css" href="css/login.css"/>
    <script src="js/index.js" type="text/javascript"></script>

    <title>Group 10 - Take home assignment</title>
</head>
<body>
<!-- Body Header -->
<div class="container-fluid p-5 bg-primary text-white text-center">
    <h1>Suwa Sahana Private Hospital</h1>
    <p>Your Hospital for Life...</p>
</div>

<!-- Container -->
<div class="container" id="body-container">
    <div class="row">
        <div class="col-sm-4">
            <h3>Patients</h3>
            <p>Log-in to see your status</p>
            <!-- Button trigger - Patient Modal -->
            <button id="btnPatient" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalLogin">
                Search
            </button>
        </div>
        <div class="col-sm-4">
            <h3>Staff</h3>
            <p>Log-in to see your status</p>
            <!-- Button trigger - Patient Modal -->
            <button id="btnStaff" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalLogin">
                Login
            </button>
        </div>
        <div class="col-sm-4">
            <h3>Admin</h3>
            <p>Log-in to see your status</p>
            <!-- Button trigger - Patient Modal -->
            <button id="btnAdmin" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalLogin">
                Login
            </button>
        </div>
    </div>
</div>

<!-- Login Modal -->
<div id="modalLogin" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-login">
        <div class="modal-content">
            <div class="modal-header">
                <div class="avatar">
                    <img src="/res/guest.png" alt="Avatar">
                </div>
                <h4 class="modal-title">?Account? Login</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="modal-body">
                    <div class="form-group" id="sec_role">
                        <p id="lblRole">User: </p>
                        <input type="hidden" class="form-control" name="role" required />
                    </div>
                    <div class="form-group" id="sec_id">
                        <label>
                            <input type="text" class="form-control" name="userid" placeholder="?Staff? ID" required />
                        </label>
                    </div>
                    <div class="form-group" id="sec_pass">
                        <label>
                            <input type="password" class="form-control" name="password" placeholder="Password" required />
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-lg btn-block" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-lg btn-block login-btn" name="btnLogin">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
