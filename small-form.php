<?php 
$your_email ='info@sankometal.com';// <<=== update to your email address

session_start();
$errors = '';
$name = '';
$company = '';
$tel = '';
$visitor_email = '';
$user_message = '';

if(isset($_POST['submit']))
{
	
	$name = $_POST['name'];
	$visitor_email = $_POST['email'];
	$company = $_POST['company'];
	$tel = $_POST['tel'];
	$user_message = $_POST['message'];
	///------------Do Validations-------------
	if(empty($name)||empty($visitor_email))
	{
		$errors .= "\n Name and Email are required fields. ";	
	}
	if(IsInjected($visitor_email))
	{
		$errors .= "\n Bad email value!";
	}
	$turnstile_secret = '0x4AAAAAAEu5po4zHpNukWiLPM9y1FtUw2g';
	$turnstile_response = isset($_POST['cf-turnstile-response']) ? $_POST['cf-turnstile-response'] : '';
	
	if(empty($turnstile_response))
	{
		$errors .= "\n Please complete the security verification!";
	}
	else
	{
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
				$errors .= "\n Security verification failed!";
			}
		} else {
			$errors .= "\n Failed to connect to verification server!";
		}
	}
	
	if(empty($errors))
	{
		//send the email
		$to = $your_email;
		$subject="Quick Inquiry from  www.sankometal.com";
		$from = $your_email;
		$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
		
		$body = "A user  $name submitted the contact form:\n".
		"Name: $name\n".
		"Company Name: $company\n".
		"Email: $visitor_email \n".
		"Message: \n ".
		"$user_message\n".	
		
		$headers = "From: $from \r\n";
		$headers .= "Reply-To: $visitor_email \r\n";
		$headers .= "Cc: sales@ecosteels.com\r\n"; 
		
		mail($to, $subject, $body,$headers);
		
		header('Location: thanks.html');
	}
}

// Function to validate against any email injection attempts
function IsInjected($str)
{
  $injections = array('(\n+)',
              '(\r+)',
              '(\t+)',
              '(%0A+)',
              '(%0D+)',
              '(%08+)',
              '(%09+)'
              );
  $inject = join('|', $injections);
  $inject = "/$inject/i";
  if(preg_match($inject,$str))
    {
    return true;
  }
  else
    {
    return false;
  }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> 
<html>
<head>
	<title>Contact Us</title>
<link href="images/stindiafavicon.gif" type="image/gif" rel="shortcut icon" />
<!-- define some style elements-->
<style>
label,a, body 
{
	font-family : Arial, Helvetica, sans-serif;
	font-size : 15px;
	color:#000000; 
}
.err
{
	font-family : Verdana, Helvetica, sans-serif;
	font-size : 12px;
	color: red;
}
</style>	
<!-- a helper script for vaidating the form-->
<script language="JavaScript" src="scripts/gen_validatorv31.js" type="text/javascript"></script>	
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
</head>

<body>
<?php
if(!empty($errors)){
echo "<p class='err'>".nl2br($errors)."</p>";
}
?>
<div id='contact_form_errorloc' class='err'></div>
<form method="POST" name="contact_form" 
action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>"> 
<p>
<label for='name'>Company Name: </label><br>
<input type="text" name="company" value='<?php echo htmlentities($company) ?>'>
</p>
<p>
<label for='name'>Name: </label><br>
<input type="text" name="name" value='<?php echo htmlentities($name) ?>'>
</p>
<p>
<label for='email'>Email: </label><br>
<input type="text" name="email" value='<?php echo htmlentities($visitor_email) ?>'>
</p>
<label for='message'>Requirement:</label> <br>
<textarea name="message" rows=3 cols=25><?php echo htmlentities($user_message) ?></textarea>
</p>
<p>
<div class="cf-turnstile" data-sitekey="0x4AAAAAAEu5putwAR_2Bces"></div>
</p>
<input type="submit" value="Submit" name='submit'>
</form>
<script language="JavaScript">
// Code for validating the form
// Visit http://www.javascript-coder.com/html-form/javascript-form-validation.phtml
// for details
var frmvalidator  = new Validator("contact_form");
//remove the following two lines if you like error message box popups
frmvalidator.EnableOnPageErrorDisplaySingleBox();
frmvalidator.EnableMsgsTogether();

frmvalidator.addValidation("name","req","Please provide your name"); 
frmvalidator.addValidation("email","req","Please provide your email"); 
frmvalidator.addValidation("email","email","Please enter a valid email address"); 
</script>
</script>
</body>
</html>