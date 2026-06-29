<?php

require("class.phpmailer.php"); 

$mail = new PHPMailer(); 

$mail->IsSMTP();
$mail->Host = "localhost";
$mail->SMTPAuth = true;
$mail->Username = "admin@stfnonprofit.org";
$mail->Password = "Cl-71.K4Q+cI";

$mail->From = "admin@stfnonprofit.org";
$mail->FromName = "STF";
$mail->AddReplyTo("kaviyarasu.p@capdigisoft.net");
$mail->AddAddress("kaviyarasu.p@capdigisoft.net");
$mail->IsHTML(true);
$mail->Subject = "Test message sent using the PHPMailer component";
$mail->Body = "This is a test message.";

if($mail->Send())
{
echo "success";
}
else
{
echo "Failed";
}
?>