<?php

use PHPMailer\PHPMailer\PHPMailer;

require_once '../vendor/PHPMailer/PHPMailer.php';
require_once '../vendor/PHPMailer/Exception.php';
require_once '../vendor/PHPMailer/SMTP.php';

require_once '../class/ContactSubmission.php';

require_once '../config.php';

$ContactSubmission = new ContactSubmission($config);
$mailer = new PHPMailer();

$ContactSubmission->sanitize_input();
$input_saved = $ContactSubmission->save_input();

$success = false;
$message = 'There is an issue on our side. Email us in the meantime. We will send you pie for your trouble.';

if($input_saved) {

	$response = $ContactSubmission->send_email($mailer);
	
	if($response) {
		
		$success = true;
		$message = 'Contact Form Submitted. Expect to hear from us soon.';

	} else {

		$success = false;
		$message = 'There is an issue on our side. Email us in the meantime. We will send you pie for your trouble.';
		
	}

} else {

	$success = false;
	$message = 'There is an issue saving the data on our side. Email us in the meantime. We will send you pie for your trouble.';

}

$json_response = json_encode(array( 'success' => $success, 'msg' => $message ));

echo $json_response;
die();