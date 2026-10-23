<?php 
include 'db_connect.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['newsletter_email'])) {
    $email = trim($_POST['newsletter_email']);
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<span style='color: red;'>Invalid email address. Please try again.</span>";
        exit();
    }
    
    list(, $domain) = explode('@', $email);
    
    $stmt = $conn->prepare("INSERT INTO newsletter_subscribtion (subEmail, subDate) VALUES (?, NOW())");
    $stmt->bind_param("s", $email);
    
    try {
        $stmt->execute();
        
        // Initialize PHPMailer with debug mode
        $mail = new PHPMailer(true);
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'amarzemmal@gmail.com'; // Your Gmail address
        $mail->Password = 'kcmvbvfhqlayhstc'; // Replace with your new app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        
     
        $mail->Username = 'amarzemmal@gmail.com';

        // Recipients
        $mail->setFrom('amarzemmal@gmail.com', 'Growy website');
        $mail->addAddress($email);
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Welcome to Growy Newsletter!';
        $mail->Body = '
        <body style="font-family: Arial, sans-serif; line-height: 1.6; background-color: #f9f9f9; margin: 0; padding: 0;">
       <div style="max-width: 600px; margin: 20px auto; background: #fff; border: 1px solid #ddd; border-radius: 8px; overflow: hidden;">
        <!-- Header Section -->
        <div style="background-color: #5a9a6e; color: #fff; padding: 20px; text-align: center;">
            <img src="https://i.imgur.com/L7mjpMh.jpg" alt="Growy Logo" style="max-height: 100px; margin-bottom: 5px;">
            <h1 style="margin: 0; font-size: 24px;">Welcome to Growy!</h1>
        </div>

        <!-- Body Content -->
        <div style="padding: 20px; color: #333;">
            <h2 style="color:rgb(9, 12, 10);">Hello, Dear Subscriber!</h2>
            <p>Thank you for joining Growy. We are thrilled to have you as part of our growing community of parents and caregivers. Here’s what you can expect:</p>

            <strong> <h3 style="color: #795548;">Our Services:</h3></strong>
            <ul style="list-style: none; padding: 0;">
                <li style="margin-bottom: 10px;">
                    <strong style="color: #333;"><u>Real-Time Announcements:</u></strong> Post job announcements to find teachers, babysitters, or opportunities shared by other parents.
                </li>
                <li style="margin-bottom: 10px;">
                    <strong style="color: #333;"><u>Contribute:</u></strong> Share your experiences, advice, or cherished memories with your child and engage with others through comments.
                </li>
                <li style="margin-bottom: 10px;">
                    <strong style="color: #333;"><u>Courses:</u></strong> Access a wide variety of courses in multiple languages to enrich your knowledge and skills.
                </li>
            </ul>

            <div style="text-align: center; margin: 20px 0;">
                <img src="https://i.imgur.com/XmtmZIW.jpg" alt="Happy Family" style="max-width: 100%; border-radius: 8px;">
            </div>

            <p>We hope Growy becomes a valuable resource for you and your family. Thank you for trusting us on this journey!</p>

            <p style="margin: 20px 0; font-size: 16px; font-weight: bold;">Warm regards,<br>The Growy Team</p>
        </div><!-- Footer Section -->
        <div style="background-color: #795548; color: #fff; padding: 10px; text-align: center; font-size: 14px;">
            &copy; 2025 Growy. All rights reserved.
        </div>
    </div>
   </body>
   </html>';
        
        // Attempt to send email
        if ($mail->send()) {
            echo "<span style='color: green;'>Thank you for subscribing! Welcome email sent successfully.</span>";
        } else {
            throw new Exception("Email not sent");
        }
        
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            echo "<span style='color: red;'>This email is already subscribed.</span>";
        } else {
            echo "<span style='color: red;'>Database error: " . htmlspecialchars($e->getMessage()) . "</span>";
            error_log("Database Error: " . $e->getMessage());
        }
    } catch (Exception $e) {
        echo "<span style='color: red;'>Email could not be sent. Error: " . htmlspecialchars($mail->ErrorInfo) . "</span>";
        error_log("Mailer Error for $email: " . $mail->ErrorInfo);
    }
    
    $stmt->close();
} else {
    echo "<span style='color: red;'>Invalid request.</span>";
}

$conn->close();
?>