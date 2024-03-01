<?php 
session_start(); 

if(isset($_SESSION['name']))
{
    header("Location: user_dashboard.php");
    exit;
}
?>

<!doctype html>
<html lang="en">

<head>
  <title>Civil Registrar Portal</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" href="images/civ.png" type="images/png">


  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <link rel="stylesheet" href="style.css">
</head>

<body>

<?php

require('db.php');

if (isset($_POST['login'])) {
    $username = stripslashes($_REQUEST['username']);
    $username = mysqli_real_escape_string($conn, $username);
    $password = stripslashes($_REQUEST['password']);
    $password = mysqli_real_escape_string($conn, $password);

    $query = "SELECT * FROM `users` WHERE username='$username' OR email='$username' AND password='" . md5($password) . "'";
    $result = mysqli_query($conn, $query) or die(mysqli_error($con));
    $rows = mysqli_num_rows($result);

    if ($rows == 1) {
        $row = mysqli_fetch_assoc($result);

        // Regenerate the session ID to prevent session fixation attacks
        session_regenerate_id(true);

        $_SESSION['name'] = $username;
        $_SESSION['fname'] = $row['u_fn'];
        $_SESSION['id_user'] = $row['id_user'];
        $_SESSION['login_success'] = "Login Successful!";

        // Log user activity
        $user_id = $_SESSION['id_user'];
        $first_name = $row['u_fn'];
        $middle_name = $row['u_mn'];
        $last_name = $row['u_ln'];

        if ($row['usertype'] == 'admin') {
            $activity_description = 'Admin logged in';
        } else {
            $activity_description = 'User logged in';
        }

        $insert_query = "INSERT INTO user_activity_logs (user_id, username, middle_name, last_name, activity_description) VALUES ('$user_id', '$first_name', '$middle_name', '$last_name', '$activity_description')";

        if (mysqli_query($conn, $insert_query)) {
            // Log entry successful
        } else {
            echo "Error logging user activity: " . mysqli_error($conn);
        }

        if ($row['usertype'] == 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: user_dashboard.php");
        }

        exit();
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Incorrect email or password.',
                });
              </script>";
    }
}

?>


  <section class="vh-100">
    <div class="container py-5 h-100">
      <div class="row d-flex align-items-center justify-content-center h-100">
        <div class="col-md-8 col-lg-5 col-xl-5">
          <img src="images/civreg.jpg" class="img-fluid" alt="Phone image" height="100px" width="500px">
        </div>
        <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
          <form action="" method="post">
            <p class="text-center h1 fw-bold mb-4 mx-1 mx-md-1 mt-3">Botolan Civil Registry <br>Online Portal </p>
            <!-- Email input -->
            <div class="form-floating mb-4">
              <input type="text" class="form-control" id="floatingInput" name="username" autocomplete="off" placeholder="Enter your Email" style="border-radius:15px ;" />
              <label for="floatingInput"> Email</label>
            </div>

            <!-- Password input -->
            <div class="form-floating">
              <input type="password" class="form-control" id="floatingPassword" name="password" autocomplete="off" placeholder="Enter your Password" style="border-radius:15px ;" />
              <label for="floatingPassword"> Password</label>
            </div>

            <!-- Submit button -->
            <!-- <button type="submit" class="btn btn-primary btn-lg">Login in</button> -->
            <div class="d-flex justify-content-center mx-1 mb-3 mb-lg-1">
              <input type="submit" value="Login" name="login" class="btn btn-primary btn-lg text-light my-2 py-3" style="width:100% ; border-radius: 30px; font-weight:600;" />
            </div>

          </form><br>
          <p align="center">I don't have any account <a href="registration.php" class="text-danger" style="font-weight:600;text-decoration:none;">Register here</a></p>
           </form>
          <p align="center">Forgot password? <a href="forgot_pass.php" class="text-danger" style="font-weight:600;text-decoration:none;">Click here</a></p>
        </div>
      </div>
    </div>
  </section>



  <!-- Bootstrap JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>

  <script src="https://kit.fontawesome.com/1397afa917.js" crossorigin="anonymous"></script>
<?php
    
?>
</body>
</html>