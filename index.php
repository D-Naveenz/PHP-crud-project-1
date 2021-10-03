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

        $_SESSION['patient_id'] = $_POST['userid'];
        //header("Location: patient/list.php");
        header("Location: employee/main.php");
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
    <style>
        body {
            font-family: 'Varela Round', sans-serif;
        }
        div {
            padding-top: 6px;
            padding-bottom: 6px;
        }
        #body-container {
            margin-top: 30px;
        }
        .modal-login {
            color: #636363;
            width: 350px;
            margin: 80px auto 0;
        }
        .modal-login .modal-content {
            padding: 20px;
            border-radius: 5px;
            border: none;
        }
        .modal-login .modal-header {
            border-bottom: none;
            position: relative;
            justify-content: center;
        }
        .modal-login h4 {
            text-align: center;
            font-size: 26px;
            margin: 30px 0 -15px;
        }
        .modal-login .form-control:focus {
            border-color: #70c5c0;
        }
        .modal-login .form-control, .modal-login .btn {
            min-height: 40px;
            border-radius: 3px;
        }
        .modal-login .close {
            position: absolute;
            top: -5px;
            right: -5px;
        }
        .modal-login .modal-footer {
            background: #ecf0f1;
            border-color: #dee4e7;
            text-align: center;
            justify-content: center;
            margin: 0 -20px -20px;
            border-radius: 5px;
            font-size: 13px;
        }
        .modal-login .modal-footer a {
            color: #999;
        }
        .modal-login .avatar {
            position: absolute;
            margin: 0 auto;
            left: 0;
            right: 0;
            top: -70px;
            width: 95px;
            height: 95px;
            border-radius: 50%;
            z-index: 9;
            background: #60c7c1;
            padding: 15px;
            box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.1);
        }
        .modal-login .avatar img {
            width: 100%;
        }
        .modal-login .btn {
            color: #fff;
            border-radius: 4px;
            background: #60c7c1;
            text-decoration: none;
            transition: all 0.4s;
            line-height: normal;
            border: none;
        }
        .modal-login .btn:hover, .modal-login .btn:focus {
            background: #45aba6;
            outline: none;
        }
        .trigger-btn {
            display: inline-block;
        }
    </style>
    <script type="text/javascript">
        let role = "N/A";

        function changeModal() {
            // setting attributes
            $('.modal-title').text(role + ' Login');
            $('input[name="userid"]').attr('placeholder', role + ' ID');

            // show/hide elements
            if (role === "Patient") {
                $('#sec_pass').hide();
                $('#sec_pass input').attr('required', false);
                $('#btn-modal-login').html('Login')
            }
            else {
                $('#sec_pass').show();
                $('#sec_pass input').attr('required', true);
                $('#btn-modal-login').html('Search')
            }
        }

        $(document).ready(() => {
            $('#btnPatient').click(() => {
                role = "Patient";
                $('.avatar img').attr('src', './res/guest.png');
                changeModal();
            });

            $('#btnStaff').click(() => {
                role = "Staff";
                $('.avatar img').attr('src', './res/staff.png');
                changeModal();
            });
        })
    </script>

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
        <div class="col-sm-6 align-items-center" style="text-align: center; padding-right: 4rem!important;">
            <h3>Patients</h3>
            <p>"Healing in a matter of time, but it is sometimes also a matter of opportunity."</p>
            <p class="text-info" align="right">- Hippocrates</p>
            <!-- Button trigger - Patient Modal -->
            <button id="btnPatient" type="button" class="btn btn-primary trigger-btn" data-bs-toggle="modal" data-bs-target="#modalLogin"
            style="margin-top: 6rem">
                Search
            </button>
        </div>
        <div class="col-sm-6 align-items-center" style="text-align: center; padding-left: 4rem!important;">
            <h3>Staff</h3>
            <p>A customer is the most important visitor on our premises, he is not dependent on us. We are dependent on him.
                He is not an interruption in our work. He is the purpose of it. He is not an outsider in our business. He is part of it.
                We are not doing him a favor by serving him. He is doing us a favor by giving us an opportunity to do so.</p>
            <p class="text-info" align="right">- Mahatma Gandhi</p>
            <!-- Button trigger - Patient Modal -->
            <button id="btnStaff" type="button" class="btn btn-primary trigger-btn" data-bs-toggle="modal" data-bs-target="#modalLogin">
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
                <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="modal-body">
                    <div class="p-0">
                        <label for="txtLogin"></label>
                        <input type="text" class="form-control" name="userid" placeholder="?Staff? ID" id="txtLogin" required />
                    </div>
                    <div class="p-0" id="sec_pass">
                        <label for="txtPass"></label>
                        <input type="password" class="form-control" name="password" placeholder="Password" id="txtPass" required />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-lg btn-block" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-lg btn-block login-btn" name="btnLogin" id="btn-modal-login">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
