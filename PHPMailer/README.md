** USAGE

    require 'PHPMailer/Exception.php';
	require 'PHPMailer/PHPMailer.php';
	require 'PHPMailer/SMTP.php';

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	function sendNotificationViaEmail($to, $from = 'sender@example.com') {
    
		$subject = 'BatStateU TNEU - Library Reservation System';
		$message = 'This is to notify you that we have cancelled your reservation. Reason: Failure to arrive on time.';
	    $mail = new PHPMailer(true);

	    try {
	        // Server settings
	        $mail->isSMTP();
	        $mail->Host       = 'smtp.gmail.com'; // Set the SMTP server to send through
	        $mail->SMTPAuth   = true; 
	        $mail->Username   = ''; // SMTP username
	        $mail->Password   = ''; // SMTP password
	        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
	        $mail->Port       = 587; // TCP port to connect to

	        // Recipients
	        $mail->setFrom($from, 'Mailer');
	        $mail->addAddress($to); // Add a recipient

	        // Content
	        $mail->isHTML(true); // Set email format to HTML
	        $mail->Subject = $subject;
	        $mail->Body    = $message;

	        $mail->send();
	        return true;
	    } catch (Exception $e) {
	        // Handle error
	        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	        return false;
	    }
	}