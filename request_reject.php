<?php
// session_start();

include('includes/header.php'); 
include('includes/navbar.php');

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'phpmailer/vendor/autoload.php';


include 'db.php';

if (isset($_POST['rejectEmail'])) {
    // Fetch necessary data from the form
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $rejectionReason = mysqli_real_escape_string($conn, $_POST['rejectionReason']);
    $registrarName = mysqli_real_escape_string($conn, $_POST['registrarName']); // Add this line to fetch the registrar's name
    $typeOfRequest = mysqli_real_escape_string($conn, $_POST['typeOfRequest']); // Add this line to fetch the type of request


    // Construct and send the rejection email
    $mail = new PHPMailer(true);

    try {
        // Server settings and email configuration
        // Server settings
        // $mail->SMTPDebug = 2; // Enable verbose debug output
        $mail->isSMTP(); // Send using SMTP
        $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'nolibaluyot@pcb.edu.ph'; // SMTP username
        $mail->Password = 'baluyot2020!'; // SMTP password
        $mail->SMTPSecure = 'tls'; // Enable implicit TLS encryption
        $mail->Port = 587; // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        $mail->setFrom('nolibaluyot@pcb.edu.ph');
        $mail->addAddress($email); // Use the captured recipient email address

        // Construct the email content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Your Request has been Rejected! ';
          $mail->Body = 'Dear ' . $registrarName . ' ,<br><br>Your request for <b>' . $typeOfRequest . '</b>  has been rejected due to the following reason: <b>' . $rejectionReason . '</b><br><br>Sincerely,<br>THE MCRO BOTOLAN TEAM';

        // Send the email
        // ... (Your existing code for sending the email)
        
        // Display success message or handle accordingly
        $mail->send();
    echo "Message has been sent";
} catch (Exception $e) {
    // Handle exceptions or errors
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

}

    echo '<script>
      Swal.fire({
        title: "Great!",
        text: "Your message has been sent!",
        icon: "success",
        confirmButtonColor: "#3085d6",
        confirmButtonText: "OK"
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = "manage_request.php";
        }
      });
    </script>';
?>

<?php
include('includes/script.php');
include('includes/footer.php');
?>

