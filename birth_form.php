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


if (isset($_GET['type_request'])) {
    $typeRequest = $_GET['type_request'];
} else {
    // Default value if the parameter is not set
    $typeRequest = "";
}


// Check if the user is logged in or registered
if (!isset($_SESSION['name'])) {
    // Redirect to login page or handle authentication
    header("Location: login.php");
    exit;
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
    $lastname = $_POST["lastname"];
    $firstname = $_POST["firstname"];
    $middlename = $_POST["middlename"];
    $pob_country = $_POST["pob_country"];
    $pob_province = $_POST["pob_province"];
    $pob_municipality = $_POST["pob_municipality"];
    $dob = $_POST["dob"];
    $sex = $_POST["sex"];
    $fath_ln = $_POST["fath_ln"];
    $fath_fn = $_POST["fath_fn"];
    $fath_mn = $_POST["fath_mn"];
    $moth_maiden_ln = $_POST["moth_maiden_ln"];
    $moth_maiden_fn = $_POST["moth_maiden_fn"];
    $moth_maiden_mn = $_POST["moth_maiden_mn"];
    $relationship = $_POST["relationship"];
    $purpose_of_request = $_POST["purpose_of_request"];
    $type_request = $_POST["type_request"];
    $status_request = 'PENDING';
    $id_user = $_POST['id_user']; // Retrieve id_user from the form data


    // Assign values to registration_date and registrar_name
    $registration_date = date('Y-m-d H:i:s'); // Current date and time
    $registrar_name = "$firstname $middlename $lastname"; // Replace with the actual registrar's name

    // SQL query to insert data into the birthceno_tbl table
    $stmt = $conn->prepare("INSERT INTO birthceno_tbl (id_user, lastname, firstname, middlename, pob_country, pob_province, pob_municipality, dob, sex, fath_ln, fath_fn, fath_mn, moth_maiden_ln, moth_maiden_fn, moth_maiden_mn, relationship, purpose_of_request, type_request, status_request) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        // Bind parameters to the placeholders
    $stmt->bind_param("issssssssssssssssss", $id_user, $lastname, $firstname, $middlename, $pob_country, $pob_province, $pob_municipality, $dob, $sex, $fath_ln, $fath_fn, $fath_mn, $moth_maiden_ln, $moth_maiden_fn, $moth_maiden_mn, $relationship, $purpose_of_request, $type_request, $status_request);
    
    if ($stmt->execute()) {
        // Insert data into civ_record table
        $id_birth_ceno = $stmt->insert_id; // Get the ID of the inserted record

        $stmt->close();

        // SQL query to insert data into the civ_record table
        $civRecordSql = "INSERT INTO civ_record (id_birth_ceno, registration_date, registrar_name, type_request) VALUES ('$id_birth_ceno', '$registration_date', '$registrar_name', '$type_request')";

        // Execute the civ_record query
        if ($conn->query($civRecordSql) === TRUE) {
            // Display success message for both insertions
            echo '<div class="alert-popup" id="success-alert">';
            echo 'Request Successfully';
            echo '</div>';
            echo '<script>
                    var successAlert = document.getElementById("success-alert");
                    successAlert.style.display = "block";
                    setTimeout(function(){ successAlert.style.display = "none"; redirectToDashboard(); }, 3000);
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
    <link rel="stylesheet" href="birth.css">
    
     
    <!----===== Iconscout CSS ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

    <!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>



   <title>Birth Certificate Registration Form</title>

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
        Fill up the Request Form
    </header>

        <form method="POST" action="">
            <div class="form first">
                <div class="details personal">

                    <div class="fields">
                        <div class="input-field">
                            <label>Last Name</label>
                            <input type="text" name="lastname" class="form-control" value="<?php echo $u_ln; ?>" required  data-toggle="popover" data-trigger="focus" data-placement="top" title="Instructions">
                        </div>


                        <div class="input-field exclude-popover">
                            <label>First Name</label>
                            <input type="text" name="firstname" class="form-control" value="<?php echo $u_fn; ?>" required>
                        </div>


                        <div class="input-field exclude-popover">
                            <label>Middle Name</label>
                            <input type="text" name="middlename" class="form-control" value="<?php echo $u_mn; ?>" required>
                        </div>

                        <div class="input-field exclude-popover">
                            <label>Place of Birth (Country)</label>
                            <input type="text" name="pob_country" class="form-control" required>
                        </div>

                       <div class="input-field">
                                <label>Place of Birth (Province)</label>
                                <select id="placeOfBirth" name="pob_province" class="form-control" required>
                                    <option value="">Select Province</option>
                                    <!-- Options will be dynamically populated using JavaScript -->
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

                          <div class="input-field exclude-popover">
                            <label>Place of Birth(City/Municipality)</label>
                            <input type="text" name="pob_municipality" class="form-control">
                        </div>


                        <div class="input-field">
                            <label>Father's Last Name</label>
                            <input type="text" name="fath_ln" class="form-control" required data-toggle="popover" data-trigger="focus" data-placement="top" title="Instructions">
                        </div>

                         <div class="input-field exclude-popover">
                            <label>Father's First Name</label>
                            <input type="text" name="fath_fn" class="form-control" required>
                        </div>

                         <div class="input-field exclude-popover">
                            <label>Father's Middle Name</label>
                            <input type="text" name="fath_mn" class="form-control" required>
                        </div>

                         <div class="input-field">
                            <label>Mother's Maiden Last Name</label>
                            <input type="text" name="moth_maiden_ln" class="form-control" required data-toggle="popover" data-trigger="focus" data-placement="top" title="Instructions">
                        </div>


                        <div class="input-field exclude-popover">
                            <label>Mother's Maiden First Name</label>
                            <input type="text" name="moth_maiden_fn" class="form-control" required>
                        </div>


                         <div class="input-field exclude-popover">
                            <label>Mother's Maiden Middle Name</label>
                              <input type="text" name="moth_maiden_mn" class="form-control" required>
                        </div>  

                        <div class="input-field">
                            <label>Sex</label>
                            <select name="sex" class="form-control" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            </select>
                        </div>

                         <div class="input-field second-dob exclude-popover">
                            <label>Date of Birth</label>
                            <input type="date" id="dob" name="dob" class="form-control" required onchange="validateDate(this)">
                        </div>

                        <div class="input-field">
                            <label>Relationship to the Document Owner</label>
                             <select name="relationship" class="form-control" required>
                                <option value="">Select Relationship</option>
                                <option value="Registrant">Registrant</option>
                                <option value="Parent">Parent</option>
                                <option value="Sibling">Sibling</option>
                                <!-- Add other relevant relationship options -->
                            </select>
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

                        <button class="backBtn" style="background-color: #1164f2"  id="backButton">
                            <span class="btnText">Back</span>
                         <i class="uil-arrow-right"></i>
                        </button>
                    </div>
                </div> 
            </div>
        </form>
    </div>

    <!-- Your existing HTML form -->
<!-- ... -->

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
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Select the input fields by their name attribute
        var textOnlyFields = document.querySelectorAll('input[type="text"]');
        
        // Add event listener to each text input field
        textOnlyFields.forEach(function (field) {
            field.addEventListener('keypress', function (event) {
                // Check if the pressed key is a number
                if (event.key >= '0' && event.key <= '9') {
                    // Prevent the input if it's a number
                    event.preventDefault();
                }
            });
        });
    });
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

<!-- <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Modify the function to update the selected municipality display
        function findCities(selectedProvinceCode) {
            $.ajax({
                type: 'POST',
                url: 'get_mun_cip.php',
                data: { province_code: selectedProvinceCode },
                dataType: 'json',
                success: function (data) {
                    var cityDropdown = document.getElementById('pob_municipality');
                    cityDropdown.innerHTML = ''; // Clear all existing options

                    // Add a disabled option at the top
                    var defaultOption = document.createElement('option');
                    defaultOption.value = '';
                    defaultOption.textContent = 'Select City/Municipality';
                    defaultOption.disabled = true;
                    defaultOption.selected = true; // Make it the default selected option
                    cityDropdown.appendChild(defaultOption);

                    if (data && data.length > 0) {
                        data.forEach(function (city) {
                            var option = document.createElement('option');
                            option.value = city.code;
                            option.textContent = city.name;
                            cityDropdown.appendChild(option);
                        });

                        // Trigger a change event to update the input field's value
                        $(cityDropdown).change();
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }

        // Add an event listener to the Province dropdown to call the function when it changes
        document.getElementById('placeOfBirth').addEventListener('change', function () {
            var selectedProvinceCode = this.value;
            if (selectedProvinceCode !== '') {
                findCities(selectedProvinceCode);
            } else {
                // Clear the City/Municipality dropdown if no Province is selected
                var cityDropdown = document.getElementById('pob_municipality');
                cityDropdown.innerHTML = '<option value="" disabled selected>Select City/Municipality</option>';
            }
        });

        // Add an event listener to the City/Municipality dropdown to update the input field's value
        document.getElementById('pob_municipality').addEventListener('change', function () {
            var selectedMunicipality = this.options[this.selectedIndex].textContent;
            document.getElementById('pob_municipality').value = selectedMunicipality;
        });
    });
</script> -->

<script>
    // JavaScript function to redirect the user to the dashboard
    function redirectToDashboard() {
        window.location.href = 'user_dashboard.php';
    }

    // Attach an event listener to the "Back" button
    document.getElementById('backButton').addEventListener('click', redirectToDashboard);
</script>

<script>
    $(document).ready(function () {
        // Initialize Bootstrap popover for common input fields
        $('.input-field:not(.exclude-popover) input').popover({
            trigger: 'manual',
            placement: 'top',
            html: true,
            content: function () {
                return '<div class="popover-content">If the Last Name starts with "DE", "DEL", "DE LA", or "DE LOS", enter these in the Last Name. Use "DE LA" or "DE LOS" (with space) instead of "DELA" or "DELOS".</div>';
            }
        });

        // Show popover when a common input field is focused
        $('.input-field:not(.exclude-popover) input').focus(function () {
            $(this).popover('show');
        });

        // Hide popover when a common input field is blurred or when the user starts typing
        $('.input-field:not(.exclude-popover) input').on('blur input', function () {
            var inputField = $(this);
            setTimeout(function () {
                inputField.popover('hide');
            }, 200); // Adjust the delay time (in milliseconds) as needed
        });
    });
</script>

    <!-- Add this code after the <script src="script.js"></script> line -->

</body>
</html>