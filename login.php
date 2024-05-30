<?php
session_start();
include 'header.php';
include 'config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$otpCreated = false;
$otpSent = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['submit']) && $_POST['submit'] == 'Send OTP') {
        $email = $_POST['email'];
        $pass = $_POST['password'];
        $otp = rand(100000, 999999);

        // Check if email and password match
        $sql = "SELECT * FROM students WHERE email = '$email' AND password = '$pass'";
        $result = mysqli_query($link, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['id'] = $row['id'];
            $_SESSION['email'] = $row['email'];
            $firstname = $row['firstname'];
            $_SESSION['role'] = $row['role'];
            $otpCreated = true;
            $otpValidity = time() + 300; // OTP valid for 5 minutes

            // Store OTP and its validity in the session
            $_SESSION['otp'] = $otp;
            $_SESSION['otpValidity'] = $otpValidity;

            // Send OTP via email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'akash03101995@gmail.com';
                $mail->Password   = 'skefelpyaeqtclyq';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;
                $mail->setFrom('akash03101995@gmail.com', 'Student Management System');
                $mail->addAddress($_SESSION['email']);
                $mail->isHTML(true);
                $mail->Subject = 'Dear '.$firstname.'<br />';
                $mail->Body    = '<p>Your One Time Password is :</p>'.$otp.'. This OTP is only valid for 5 minutes.';
                $mail->AltBody = 'Your One Time Password is: '.$otp.'. This OTP is only valid for 5 minutes.';
                $mail->send();
                $otpSent = true;
                echo 'OTP has been sent to your email address.';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "Please enter valid credentials.";
        }
    } elseif (isset($_POST['submit']) && $_POST['submit'] == 'Sign In') {
        $enteredOtp = isset($_POST['otp']) ? $_POST['otp'] : null;
        if ($enteredOtp && $_SESSION['otp'] == $enteredOtp && time() <= $_SESSION['otpValidity']) {
            // OTP is valid, proceed with login
            header("Location: student-listing.php");
            exit();
        } else {
            echo "Invalid or expired OTP.";
        }
    }
}
?>

<div class="container">
    <div class="ManagementSystem">
        <h1 class="form-title">Sign In</h1>
        <div class="signup-content">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-lg-offset-3 col-md-offset-3 col-sm-offset-3">
                    <form id="sample" method="post" action="login.php">
                        <div class="form-group">
                            <label>Email Address <span class="color-danger">*</span></label>
                            <input type="text" id="email" name="email" class="form-control" value="<?php echo $email ?>" data-rule-email="true" />
                        </div>
                        <div class="form-group">
                            <label>Password <span class="color-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password" value="<?php echo $pass?>" data-rule-passwd="true" />
                        </div>
                        <?php if (!$otpCreated) : ?>
                            <div class="form-group">
                                <input name="submit" type="submit" value="Send OTP" class="btn btn-green sign_in">
                            </div>
                        <?php endif; ?>
                    </form>
                    <?php if ($otpCreated) : ?>
                        <form id="otp-form" method="post" action="login.php">
                            <div class="form-group">
                                <label>OTP <span class="color-danger">*</span></label>
                                <input type="text" id="otp" name="otp" class="form-control" value="" />
                            </div>
                            <div class="form-group">
                                <input name="submit" type="submit" value="Sign In" class="btn btn-green sign_in">
                            </div>
                            <div class="form-group" style="display: inline-block; width: 100%;">
                                <div class="rememberme_block pull-left">
                                    <label for="rememberme">
                                        <input type="checkbox" name="rememberme" id="rememberme" class="" value="yes"> Remember me
                                    </label>
                                </div>
                                <div class="forgot_block pull-right"><a href="#">Forgot Password?</a></div>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>