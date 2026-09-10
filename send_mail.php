<?PHP 

if($_POST['submit']){

	$message = '<html><body>';

			$message .= '<table rules="all" style="border-color: #666; width:100%; border:1px solid #666;" cellpadding="10">';

			$message .= "<tr style='background: #eee;'><td>Name: </td><td>" .$_POST["name"]. "</td></tr>";

			$message .= "<tr><td>Email: </td><td>" .$_POST["email"]. "</td></tr>";

			$message .= "<tr style='background: #eee;'><td>Mobile No: </td><td>" .$_POST["mobile"]. "</td></tr>";
			
			$message .= "<tr><td>Comment: </td><td>" .$_POST["message"]. "</td></tr>";

			$message .= "</table>";

			$message .= "</body></html>";

			//  MAKE SURE THE "FROM" EMAIL ADDRESS DOESN'T HAVE ANY NASTY STUFF IN IT

			//   CHANGE THE BELOW VARIABLES TO YOUR NEEDS

			$to = 'info@sankometal.com';

			$subject = 'Website Enquiry Details ';

			$rraj="";

			$headers = "From: " . strip_tags($_POST['email']). "\r\n";

			$headers .= "Reply-To: ". strip_tags($_POST['email']) . "\r\n";

			$headers = "BCC: ".$rraj."\r\n";

			$headers .= "MIME-Version: 1.0\r\n";

			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

            if (mail($to, $subject, $message, $headers)) {

            // echo 'Your message has been sent.';

			  echo"<script>window.location='thanks.html'</script>";

            } else {

              echo 'There was a problem sending the email.';

            }

	}

?>