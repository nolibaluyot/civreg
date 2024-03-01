<!DOCTYPE html>
<html lang="en">

<head>
  <title>Register</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" href="images/civ.png" type="images/png">


  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

  <style>
    /* Custom CSS for the pop-up alert */
    .alert-popup {
        position: fixed;
        bottom: 10px;
        right: 10px;
        z-index: 9999;
        padding: 15px;
        border-radius: 5px;
        background-color: #28a745; /* Green background color for success */
        color: #fff; /* White text color */
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        display: none; /* Initially hidden */
    }

    .form-label {
    display: block;
    margin-bottom: 10px;
    }

  .form-control-lg {
    border-radius: 10px;
    margin-bottom: 13px;
    }

 /* .form-outline {
    border: 2px solid #000; /* Change the color to your preference */
    border-radius: 15px; /* Adjust the radius to control the "semi-square" appearance */
    padding: 10px;
  }*/

  .match {
    color: green;
  }

  .no-match {
    color: red;
  }

   </style>

<body>

  <?php
    session_start();

    require('db.php');
    // When form submitted, insert values into the database.
    if (isset($_REQUEST['username'])) {

       // Capture the email address from the registration form
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        // removes backslashes
        $_SESSION['user_email'] = $email;

        $username = stripslashes($_REQUEST['username']);
        $u_ln = $_POST['u_ln'];
        $u_fn = $_POST['u_fn'];
        $u_mn = $_POST['u_mn'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $contact_no = $_POST['contact_no'];
        $create_datetime = date("Y-m-d H:i:s");
        $house_no = $_POST['house_no'];
        $street_brgy = $_POST['street_brgy'];
        $city_municipality = $_POST['city_municipality'];
        $province = $_POST['province'];


        $_SESSION['user_data'] = [
      'u_ln' => $u_ln,
      'u_fn' => $u_fn,
      'u_mn' => $u_mn,
      'username' => $username,
      'email' => $email,
      'contact_no' => $contact_no,
      'house_no' => $house_no,
      'street_brgy' => $street_brgy,
];

       
        //escapes special characters in a string
        // $u_ln = mysqli_real_escape_string($con, $u_ln);
        $u_ln = mysqli_real_escape_string($conn, $u_ln);
        $u_fn = mysqli_real_escape_string($conn, $u_fn);
        $u_mn = mysqli_real_escape_string($conn, $u_mn);
        $username = mysqli_real_escape_string($conn, $username);
        $email    = stripslashes($_REQUEST['email']);
        $email    = mysqli_real_escape_string($conn, $email);
        $password = stripslashes($_REQUEST['password']);
        $password = mysqli_real_escape_string($conn, $password);
        $contact_no = mysqli_real_escape_string($conn, $contact_no);
        $create_datetime = date("Y-m-d H:i:s");
        $house_no = mysqli_real_escape_string($conn, $house_no);
        $street_brgy = mysqli_real_escape_string($conn, $street_brgy);
        $city_municipality = mysqli_real_escape_string($conn, $city_municipality);
        $province = mysqli_real_escape_string($conn, $province);
        // $usertype    = stripslashes($_REQUEST['usertype']);
        // Assuming you have a way to determine if the user is an admin, e.g., through an additional form field or some other method.
      $is_admin = false; // Change this to determine if the user is an admin or not.

      $query = "INSERT INTO `users` (u_ln, u_fn, u_mn, username, email, password, contact_no, create_datetime, house_no, street_brgy, city_municipality, province, usertype)
      VALUES ('$u_ln', '$u_fn', '$u_mn', '$username', '$email', '" . md5($password) . "', '$contact_no', '$create_datetime', '$house_no', '$street_brgy', '$city_municipality', '$province', '" . ($is_admin ? 'admin' : 'user') . "')";

        $result   = mysqli_query($conn, $query);

        if ($result) {

               echo '<script>
                        Swal.fire({
                          title: "Good job!",
                          text: "You are registered successfully",
                          icon: "success",
                          showConfirmButton: false,
                          timer: 3000,
                          timerProgressBar: true,
                          didOpen: (toast) => {
                            toast.addEventListener("mouseenter", Swal.stopTimer);
                            toast.addEventListener("mouseleave", Swal.resumeTimer);
                          },
                        });

                        setTimeout(function () {
                          window.location.href = "login.php"; // Redirect to login page after 3 seconds
                        }, 3000);
                    </script>';


                  mysqli_close($conn);
                  exit;
        } else {
            echo "<div class='form'>
                  <h3>Required fields are missing.</h3><br/>
                  <p class='link'>Click here to <a href='registration.php'>registration</a> again.</p>
                  </div>";

                  mysqli_close($con);
                  exit;
        }
    } else {
?>



  <section class="vh-30" style="background-color: #eee;">
  <div class="container h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-lg-100 col-xl-100">
        <div class="card text-black" style="border-radius: 25px;">
          <div class="card-body p-md-50">
            <div class="row justify-content-center">
              <p class="text-center h2 fw-bold mb-2 mx-1 mx-md-3 mt-3">Register Here</p>
              <div class="col-md-10 col-lg-10 col-xl-10 order-2 order-lg-1">
                <form id="registration-form" class="mx-1 mx-md-4" action="" method="post">
                <div class="row">
  <div class="col-md-4">
    <div class="form-outline">
      <label class="form-label" for="lastName">Last Name</label>
      <input type="text" id="lastName" class="form-control form-control-lg py-3" name="u_ln" autocomplete="off" placeholder="Last Name" required />
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-outline">
      <label class="form-label" for="firstName">First Name</label>
      <input type="text" id="firstName" class="form-control form-control-lg py-3" name="u_fn" autocomplete="off" placeholder="First Name" required />
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-outline">
      <label class="form-label" for="middleName">Middle Name</label>
      <input type="text" id="middleName" class="form-control form-control-lg py-3" name="u_mn" autocomplete="off" placeholder="Middle Name" required />
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-4">
    <div class="form-outline">
      <label class="form-label" for="username">Username</label>
      <input type="text" id="username" class="form-control form-control-lg py-3" name="username" autocomplete="off" placeholder="Username" required />
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-outline">
      <label class="form-label" for="password">Password</label>
      <input type="password" id="password" class="form-control form-control-lg py-3" name="password" autocomplete="off" placeholder="Password" required />
      <div id="password-strength">
      </div>
    </div>
  </div>
  <div class="col-md-4">
  <div class="form-outline">
    <label class="form-label" for="confirmpassword">Confirm Password</label>
    <input type="password" id="confirmpassword" class="form-control form-control-lg py-3" name="confirm_password" autocomplete="off" placeholder="Confirm Password" required />
    <span id="passwordMatch"></span>
  </div>
</div>

<div class="row">
  <div class="col-md-4">
    <div class="form-outline">
      <label class="form-label" for="email">Email</label>
      <input type="email" id="email" class="form-control form-control-lg py-3" name="email" autocomplete="off" placeholder="Email" required />
      <div id="emailWarning" style="color: red;"></div>
    </div>
  </div>
<div class="col-md-4">
  <div class="form-outline">
    <label class="form-label" for="number">Contact number</label>
    <input type="text" id="number" class="form-control form-control-lg py-3" name="contact_no" autocomplete="off" placeholder="Contact number" required />
    <div id="contactNumberWarning" style="color: red;"></div>
  </div>
</div>
   <div class="col-md-4">
    <div class="form-outline">
      <label class="form-label" for="housenumber">House number/Street Name</label>
      <input type="text" id="housenumber" class="form-control form-control-lg py-3" name="house_no" autocomplete="off" placeholder="House number/street name" required />
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-4">
    <div class="form-outline">
      <label class="form-label" for="province">Province</label>
      <select id="province" class="form-select form-select-lg py-3" name="province" required>
        <option value="">Select Province</option>
        <?php 
          // Establish a database connection (replace these values with your database credentials)
         $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "civ_reg";
            					          
          // Create a connection
          $conn = new mysqli($servername, $username, $password, $dbname);

          // Check the connection
          if ($conn->connect_error) {
				    die("Connection failed: " . $conn->connect_error);
				}

          // Fetch data from the 'refprovince' table and populate the dropdown
          $sql = "SELECT provDesc, provCode FROM refprovince ORDER BY provDesc";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              echo "<option value='" . $row['provCode'] . "'>" . $row['provDesc'] . "</option>";
            }
          }

          // Close the database connection
          $conn->close();
        ?>
      </select>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-outline">
      <label class="form-label" for="city">City/Municipality</label>
      <select id="city" class="form-select form-select-lg py-3" name="city_municipality" required>
        <option value="">Select City/Municipality</option>
        <!-- Add your city/municipality options here -->
      </select>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-outline">
      <label class="form-label" for="barangay">Barangay</label>
      <select id="barangay" class="form-select form-select-lg py-3" name="street_brgy" required>
        <option value="">Select Barangay</option>
        <!-- Add your barangay options here -->
      </select>
    </div>
  </div>
</div>
                 
               <div class="d-flex justify-content-center mx-4 mb-2 mb-lg-4">
                    <input type="submit" value="Register" name="register" id="registerButton" class="btn btn-primary btn-lg text-light my-2 py-3" style="width: 100%; border-radius: 30px; font-weight: 600;" />
                  </div>
                </form>
                <p align="center">I already have an account <a href="login.php" class="text-danger" style="font-weight: 600; text-decoration: none;">Login</a></p>
              </div>
             
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </section>

  <!-- Bootstrap JavaScript Libraries -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>


  <script>

$(document).ready(function() {
    var formSubmitted = false; // Track whether the form has been submitted
    
    $('#email').on('input', function() {
        $('#emailWarning').html(''); // Clear the existing error message
        
        if (formSubmitted) {
            // If the form has already been submitted, prevent further submissions
            event.preventDefault();
            return;
        }
        
        var email = $('#email').val();
        
        if (email !== "") {
            $.ajax({
                type: 'POST',
                url: 'check_email.php',
                data: {
                    email: email
                },
                success: function(response) {
                    if (response.trim() === "Email already taken") {
                        $('#emailWarning').html('<i class="bi bi-exclamation-circle-fill" style="color: red;"></i> This email is already taken.');
                        // Disable the submit button
                        $('#registerButton').prop('disabled', true);
                    } else {
                        // Enable the submit button
                        $('#registerButton').prop('disabled', false);
                    }
                    formSubmitted = false; // Reset the form submission flag
                }
            });
            
            return false; // Prevent the form from submitting immediately
        } else {
            // Enable the submit button if the email is empty
            $('#registerButton').prop('disabled', false);
        }
    });
});


// Define a function to fetch and populate the City/Municipality dropdown
function findCities(selectedProvinceCode) {
    $.ajax({
        type: 'POST',
        url: 'get_cities.php',
        data: { province_code: selectedProvinceCode },
        success: function (data) {
            var cityDropdown = document.getElementById('city');
            cityDropdown.innerHTML = '<option value="">Select City/Municipality</option>';
            var cities = JSON.parse(data);

            if (cities.length > 0) {
                cities.forEach(function (city) {
                    var option = document.createElement('option');
                    option.value = city.code;
                    option.textContent = city.name;
                    cityDropdown.appendChild(option);
                });
            }
        }
    });
}

// Add an event listener to the Province dropdown to call the function when it changes
document.getElementById('province').addEventListener('change', function () {
    var selectedProvinceCode = this.value;
    if (selectedProvinceCode !== '') {
        findCities(selectedProvinceCode);
    } else {
        // Clear the City/Municipality dropdown if no Province is selected
        document.getElementById('city').innerHTML = '<option value="">Select City/Municipality</option>';
        document.getElementById('barangay').innerHTML = '<option value="">Select Barangay</option>';
    }
});

// Define a function to fetch and populate the Barangay dropdown
function findBarangays(selectedCityCode) {
    $.ajax({
        type: 'POST',
        url: 'get_barangay.php',
        data: { city_code: selectedCityCode }, // Use 'city_code' here
        success: function (data) {
            var barangayDropdown = document.getElementById('barangay');
            barangayDropdown.innerHTML = '<option value="">Select Barangay</option>';
            var barangays = JSON.parse(data);

            if (barangays.length > 0) {
                barangays.forEach(function (barangay) {
                    var option = document.createElement('option');
                    option.value = barangay; // Use 'barangay' directly
                    option.textContent = barangay; // Use 'barangay' directly
                    barangayDropdown.appendChild(option);
                });
            }
        }
    });
}

// Add an event listener to the City/Municipality dropdown to call the function when it changes
document.getElementById('city').addEventListener('change', function () {
    var selectedCityCode = this.value;
    if (selectedCityCode !== '') {
        findBarangays(selectedCityCode);
    } else {
        // Clear the Barangay dropdown if no City/Municipality is selected
        document.getElementById('barangay').innerHTML = '<option value="">Select Barangay</option>';
    }
});



</script>


<script>
// Function to check password strength
function checkPasswordStrength(password) {
    // Minimum password length
    var minLength = 8;
    
    // Regular expressions for password strength
    var regex = {
        lowerCase: /[a-z]/,
        upperCase: /[A-Z]/,
        numbers: /[0-9]/,
        specialChars: /[!@#$%^&*()_+\-=[\]{};':"\\|,.<>/?]/
    };
    
    var strength = 0;

    // Check if password meets minimum length requirement
    if (password.length >= minLength) {
        strength++;
    }

    // Check if password contains lowercase, uppercase, numbers, and special characters
    if (regex.lowerCase.test(password)) strength++;
    if (regex.upperCase.test(password)) strength++;
    if (regex.numbers.test(password)) strength++;
    if (regex.specialChars.test(password)) strength++;

    return strength;
}

// Event listener for the password field
document.getElementById('password').addEventListener('input', function() {
    var password = this.value.trim(); // Trim spaces from the password input

    if (password === '') {
        // If password is empty, hide the password strength indicator
        document.getElementById('password-strength').innerHTML = '';
        return; // Exit the function
    }

    var strength = checkPasswordStrength(password);

    var strengthText;
    var indicatorColor;
    switch (strength) {
        case 0:
        case 1:
            strengthText = 'Weak';
            indicatorColor = 'red'; // Weak password indicator color
            break;
        case 2:
        case 3:
            strengthText = 'Medium';
            indicatorColor = 'orange'; // Medium password indicator color
            break;
        case 4:
            strengthText = 'Strong';
            indicatorColor = 'green'; // Strong password indicator color
            break;
        default:
            strengthText = 'Weak';
            indicatorColor = 'red'; // Default color for weak passwords
            break;
    }

    // Update the password strength indicator with color
    document.getElementById('password-strength').innerHTML = '<span style="color: ' + indicatorColor + ';">' + strengthText + '</span>';
});
</script>

<style>
  .match {
    color: green;
  }

  .no-match {
    color: red;
  }
</style>

<script>
  document.getElementById("confirmpassword").addEventListener("input", function() {
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirmpassword").value;
    var passwordMatch = document.getElementById("passwordMatch");

    if (confirmPassword === '') {
      passwordMatch.innerHTML = "";
    } else if (password !== confirmPassword) {
      passwordMatch.innerHTML = "Password do not match!";
      passwordMatch.classList.remove("match");
      passwordMatch.classList.add("no-match");
    } else {
      passwordMatch.innerHTML = "Password match!";
      passwordMatch.classList.remove("no-match");
      passwordMatch.classList.add("match");
    }
  });
</script>

<script>
  document.getElementById("number").addEventListener("input", function() {
    var contactNumber = this.value;
    var contactNumberWarning = document.getElementById("contactNumberWarning");

    // Remove any non-numeric characters
    contactNumber = contactNumber.replace(/\D/g, '');

    // Check if the contact number is exactly 11 digits
    if (contactNumber.length === 11) {
      contactNumberWarning.innerHTML = "";
    } else {
      contactNumberWarning.innerHTML = "Please enter a valid 11-digit contact number.";
    }

    // Limit the input to numbers only and truncate to 11 digits
    this.value = contactNumber.substring(0, 11);
  });
</script>
<?php
    } 
?>


</body>

</html>