<?php

session_start();

$u_ln = '';
$u_fn = '';
$u_mn = '';
$username = '';
$email = '';
$contact_no = '';
$house_no = '';
$street_brgy = '';
$city_municipality = '';
$province = '';


if (isset($_GET['type_request'])) {
    $typeRequest = $_GET['type_request'];
} else {
    // Default value if the parameter is not set
    $typeRequest = "";
}

// Check if user data is stored in the session
if (isset($_SESSION['user_data'])) {
    $user_data = $_SESSION['user_data'];

    // Now, you can pre-fill the form fields with the user's data
    $u_ln = $user_data['u_ln'];
    $u_fn = $user_data['u_fn'];
    $u_mn = $user_data['u_mn'];
    $username = $user_data['username'];
    $email = $user_data['email'];
    $contact_no = $user_data['contact_no'];
    $house_no = $user_data['house_no'];
    $street_brgy = $user_data['street_brgy'];
    $city_municipality = $user_data['city_municipality'];
    $province = $user_data['province'];

    // Unset or clear the session data to avoid pre-filling the form on subsequent visits
    unset($_SESSION['user_data']);
}


// Database connection details
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

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    // $id_birth_ceno = $_POST["id_birth_ceno"];
    // $id_user = $_POST["id_user"];
    $deceased_ln = $_POST["deceased_ln"];
    $deceased_fn = $_POST["deceased_fn"];
    $deceased_mn = $_POST["deceased_mn"];
    $dob = $_POST["dob"];
    $dod = $_POST["dod"];
    $place_of_death = $_POST["place_of_death"];
    $purpose_of_request = $_POST["purpose_of_request"];
    $type_request = $_POST["type_request"];
    $status_request = 'PENDING';
    $id_user = $_POST['id_user'];
    // Add other fields here


    $registration_date = date('Y-m-d', strtotime('-1 day'));// Current date and time
    $registrar_name = "$deceased_ln $deceased_fn $deceased_mn"; // Replace with the actual registrar's name

    // SQL query to insert data into the death_tbl table
    $sql = "INSERT INTO death_tbl (id_user, deceased_ln, deceased_fn, deceased_mn, dob, dod, place_of_death, purpose_of_request, type_request, status_request) VALUES ('$id_user', '$deceased_ln', '$deceased_fn', '$deceased_mn', '$dob', '$dod', '$place_of_death', '$purpose_of_request', '$type_request', '$status_request' )";
    
        if ($conn->query($sql) === TRUE) {
        // Insert data into civ_record table
        $id_death = $conn->insert_id; // Get the ID of the inserted record

        // SQL query to insert data into the civ_record table
        $civRecordSql = "INSERT INTO civ_record (id_death, registration_date, registrar_name, type_request) VALUES ('$id_death', '$registration_date', '$registrar_name', '$type_request')";

        // Execute the civ_record query
        if ($conn->query($civRecordSql) === TRUE) {
            // Display success message for both insertions
            echo '<div class="alert-popup" id="success-alert">';
            echo 'Request Successfully';
            echo '</div>';
            echo '<script>
                    var successAlert = document.getElementById("success-alert");
                    successAlert.style.display = "block";
                    setTimeout(function(){ successAlert.style.display = "none"; }, 3000);
                  </script>';
        } else {
            // Display error message for civ_record insertion
            echo '<div class="alert alert-danger mt-3" role="alert">';
            echo 'Error: ' . $civRecordSql . '<br>' . $conn->error;
            echo '</div>';
        }
    } else {
        // Display error message for birthceno_tbl insertion
        echo '<div class="alert alert-danger mt-3" role="alert">';
        echo 'Error: ' . $sql . '<br>' . $conn->error;
        echo '</div>';
    }


 // Now, insert a record into the reqtracking_tbl
    $reqTrackingSql = "INSERT INTO reqtracking_tbl (type_request, registration_date, registrar_name, user_id, status) VALUES ('$type_request', '$registration_date', '$registrar_name', '$id_user', 'Pending')";

    
    if ($conn->query($reqTrackingSql) === TRUE) {
        // Display success message for reqtracking_tbl insertion
        echo '<div class="alert-popup" id="reqtracking-success-alert">';
        echo 'Request Successfully Tracked';
        echo '</div>';
        echo '<script>
                var reqtrackingSuccessAlert = document.getElementById("reqtracking-success-alert");
                reqtrackingSuccessAlert.style.display = "block";
                setTimeout(function(){ reqtrackingSuccessAlert.style display = "none"; }, 3000);
              </script>';
    } else {
        // Display error message for reqtracking_tbl insertion
        echo '<div class="alert alert-danger mt-3" role="alert">';
        echo 'Error: ' . $reqTrackingSql . '<br>' . $conn->error;
        echo '</div>';
    }
}


// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="icon" href="images/civ.png" type="images/png">

    <!----======== CSS ======== -->
    <link rel="stylesheet" href="death.css">
     
    <!----===== Iconscout CSS ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

   <title>Death Certificate Registration Form</title>
</head>
<body>

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

    #type_request {
    background-color: #f0f0f0; /* Change background color */
    cursor: not-allowed; /* Change cursor style to indicate non-editable */
    /* Add more styles as needed */
}

.mcro-logo {
        max-width: 100px; /* Adjust the max-width as needed */
        height: auto; /* Maintain aspect ratio */
        margin-right: 10px; /* Adjust the margin as needed */
    }

    header {
        display: flex;
        align-items: center;
    }
    
   </style>

    <div class="container">
         <header>
            <img src="images/civ.png" alt="" class="mcro-logo">
        Fill up the Death Request Form
        </header>


        <form method="POST" action="">
            <div class="form first">
                <div class="details personal">

                    <div class="fields">
                        <div class="input-field">
                            <label>Deceased Last Name</label>
                            <input type="text" name="deceased_ln" class="form-control" value="<?php echo $u_ln; ?>" required>
                        </div>


                        <div class="input-field">
                            <label>Deceased First Name</label>
                            <input type="text" name="deceased_fn" class="form-control" value="<?php echo $u_fn; ?>" required>
                        </div>


                        <div class="input-field">
                            <label>Deceased Middle Name</label>
                            <input type="text" name="deceased_mn" class="form-control" value="<?php echo $u_mn; ?>" required>
                        </div>

                        <div class="input-field">
                            <label>Date of Birth</label>
                            <input type="date" id="dob" name="dob" class="form-control" required onchange="validateDate(this)">
                        </div>


                         <div class="input-field">
                            <label>Date of Death</label>
                           <input type="date" id="dod" name="dod" class="form-control" required onchange="validateDate(this)">
                        </div>

                         <div class="input-field">
                             <label>Place of Death</label>
                             <input type="text" name="place_of_death" class="form-control" required>
                         </div>


                         <div class="input-field">
                                <label for="purpose_of_request">Purpose of Request</label>
                                <select name="purpose_of_request" id="purpose_of_request" class="form-control" required>
                                    <option value="" disabled selected>Select a purpose</option>
                                    <option value="Registration">Registration</option>
                                    <option value="Credentials Update">Credentials Update</option>
                                    <option value="Record Keeping">Record Keeping</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                        <div class="input-field">
                            <label>Type of Request</label>
                            <input type="text" id="type_request" name="type_request" value="<?php echo $typeRequest; ?>" required readonly>
                        </div> 

                    </div>
                </div>

                <input type="hidden" name="id_user" value="<?php echo $_SESSION['id_user']; ?>">
                        
                        <div class="buttons">
                            <button class="submitBtn">
                                <span class="btnText">Submit</span>
                            <i class="uil uil-navigator"></i>
                            </button>

                        <button class="backBtn" style="background-color: #1164f2" id="backButton">
                            <span class="btnText">Back</span>
                         <i class="uil-arrow-right"></i>
                        </button>
                    </div>
                </div> 
            </div>
        </form>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    // Function to show SweetAlert confirmation modal
    function showConfirmationModal() {
        Swal.fire({
            title: "Are you sure You want to submit it?",
            text: "Please review your information before submitting.",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, submit!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Check if the form is empty before submitting
                var formIsValid = validateForm();
                if (formIsValid) {
                    // If the form is valid, submit it
                    document.querySelector("form").submit();
                } else {
                    // If the form is not valid, show an error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Please fill in all the required fields before submitting.',
                    });
                }
            }
        });
    }

    // Function to validate the form
    function validateForm() {
        // You can customize this function based on your form structure
        var requiredFields = document.querySelectorAll('input[required], select[required]');
        var formIsValid = true;

        requiredFields.forEach(function (field) {
            if (field.value.trim() === '') {
                formIsValid = false;
                return;
            }
        });

        return formIsValid;
    }

    // Attach an event listener to the Submit button
    document.querySelector('.submitBtn').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default form submission
        showConfirmationModal(); // Show SweetAlert confirmation modal
    });
</script>

<script src="birthform.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // JavaScript function to redirect the user to the dashboard
    function redirectToDashboard() {
        window.location.href = 'user_dashboard.php';
    }

    // Attach an event listener to the "Back" button
    document.getElementById('backButton').addEventListener('click', redirectToDashboard);
</script>

<script>
    function validateDate(inputField) {
        // Get the current date
        var currentDate = new Date();

        // Get the selected date from the input field
        var selectedDate = new Date(inputField.value);

        // Check if the selected date is in the future or invalid
        if (isNaN(selectedDate) || selectedDate > currentDate) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Date',
                text: 'Please select a valid date that is not ahead of the current date.',
                confirmButtonText: 'OK'
            });
            inputField.value = ''; // Clear the input field
        } else {
            // Get the year of the selected date
            var selectedYear = selectedDate.getFullYear();

            // Get the current year
            var currentYear = currentDate.getFullYear();

            // Compare the selected year with the current year
            if (selectedYear > currentYear) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Date',
                    text: 'Please select a date in the past.',
                    confirmButtonText: 'OK'
                });
                inputField.value = ''; // Clear the input field
            }
        }
    }
</script>

    <!-- Add this code after the <script src="script.js"></script> line -->

</body>
</html>