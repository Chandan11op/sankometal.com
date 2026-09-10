<?php 

if (isset($_POST['submit']) || $_SERVER['REQUEST_METHOD'] === 'POST') {

	// Turnstile verification
	$turnstile_secret = '0x4AAAAAAEu5po4zHpNukWiLPM9y1FtUw2g';
	$turnstile_response = isset($_POST['cf-turnstile-response']) ? $_POST['cf-turnstile-response'] : '';
	
	if (empty($turnstile_response)) {
		echo "<script>alert('Please complete the security verification.'); window.history.back();</script>";
		exit();
	}
	
	$url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
	$data = ['secret' => $turnstile_secret, 'response' => $turnstile_response];
	
	$options = [
		'http' => [
			'header' => "Content-type: application/x-www-form-urlencoded\r\n",
			'method' => 'POST',
			'content' => http_build_query($data)
		]
	];
	$context = stream_context_create($options);
	$result = @file_get_contents($url, false, $context);
	
	if ($result !== false) {
		$response = json_decode($result);
		if (!$response->success) {
			echo "<script>alert('Security verification failed. Please try again.'); window.history.back();</script>";
			exit();
		}
	} else {
		echo "<script>alert('Failed to connect to verification server. Please try again.'); window.history.back();</script>";
		exit();
	}

	$name = isset($_POST['name']) ? strip_tags($_POST['name']) : '';
	$company = isset($_POST['company']) ? strip_tags($_POST['company']) : '';
	$email = isset($_POST['email']) ? strip_tags($_POST['email']) : '';
	$mobile = isset($_POST['mobile']) ? strip_tags($_POST['mobile']) : (isset($_POST['tel']) ? strip_tags($_POST['tel']) : '');
	$user_message = isset($_POST['message']) ? strip_tags($_POST['message']) : '';

	$message = '<html><body>';
	$message .= '<table rules="all" style="border-color: #666; width:100%; border:1px solid #666;" cellpadding="10">';
	$message .= "<tr style='background: #eee;'><td><strong>Name:</strong> </td><td>" . $name . "</td></tr>";
	if (!empty($company)) {
		$message .= "<tr><td><strong>Company Name:</strong> </td><td>" . $company . "</td></tr>";
	}
	$message .= "<tr style='background: #eee;'><td><strong>Email:</strong> </td><td>" . $email . "</td></tr>";
	$message .= "<tr><td><strong>Mobile / Phone No:</strong> </td><td>" . $mobile . "</td></tr>";
	$message .= "<tr style='background: #eee;'><td><strong>Message / Requirement:</strong> </td><td>" . nl2br($user_message) . "</td></tr>";
	$message .= "</table>";
	$message .= "</body></html>";

	$to = 'info@sankometal.com';
	$subject = 'Website Enquiry Details - Sanko Metal Industries';

	$headers = "From: " . $email . "\r\n";
	$headers .= "Reply-To: " . $email . "\r\n";
	$headers .= "Cc: sales@ecosteels.com\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

	if (@mail($to, $subject, $message, $headers)) {
		echo "<script>window.location='thanks.html';</script>";
		header("Location: thanks.html");
		exit();
	} else {
		echo "<script>window.location='thanks.html';</script>";
		header("Location: thanks.html");
		exit();
	}
}
?>