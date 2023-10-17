<?php

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
	exit("Invalid request");
}

function isEmail($email)
{
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}

$name = htmlspecialchars($_POST['name']);
$email = htmlspecialchars($_POST['email']);
$phone = filter_var($_POST['phone'], FILTER_SANITIZE_NUMBER_INT);
$comments = htmlspecialchars($_POST['comments']);
$verify = $_POST['verify'];

if (empty($name) || empty($email) || empty($phone) || empty($comments) || empty($verify)) {
	exit('<div class="error_message">All fields are required.</div>');
}

if (!is_numeric($phone)) {
	exit('<div class="error_message">Phone number can only contain digits.</div>');
}

if (!isEmail($email)) {
	exit('<div class="error_message">Invalid email address, please try again.</div>');
}

if (intval($verify) !== 7) {
	exit('<div class="error_message">The verification number you entered is incorrect.</div>');
}

$address = "taneonoemi@gmail.com";
$e_subject = "You've been contacted by $name.";
$e_body = "You have been contacted by $name with regards. Their message is as follows:" . PHP_EOL . PHP_EOL;
$e_content = "\"$comments\"" . PHP_EOL . PHP_EOL;
$e_reply = "You can contact $name via email, $email or via phone $phone";

$msg = wordwrap($e_body . $e_content . $e_reply, 70);

$headers = "From: $email" . PHP_EOL;
$headers .= "Reply-To: $email" . PHP_EOL;
$headers .= "MIME-Version: 1.0" . PHP_EOL;
$headers .= "Content-type: text/plain; charset=utf-8" . PHP_EOL;
$headers .= "Content-Transfer-Encoding: quoted-printable" . PHP_EOL;

if (mail($address, $e_subject, $msg, $headers)) {
	echo "<fieldset>";
	echo "<div id='success_page'>";
	echo "<h3>Email Sent Successfully.</h3>";
	echo "<p>Thank you <strong>$name</strong>, your message has been submitted to us.</p>";
	echo "</div>";
	echo "</fieldset>";
} else {
	// Log the error for debugging purposes
	error_log("Email sending failed.");
	echo '<div class="error_message">Sorry, we could not send your message. Please try again later.</div>';
}
