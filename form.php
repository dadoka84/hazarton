<?php
/* PHP Form Mailer - phpFormMailer v2.1, last updated 30th Nov 2005 - check back often for updates!
   (easy to use and more secure than many cgi form mailers) FREE from:
                  www.TheDemoSite.co.uk
      Should work fine on most Unix/Linux platforms */

// ------- three variables you MUST change below  -------------------------------------------------------
$valid_ref1="http://hazarton.com/kontakt.htm";// chamge "Your--domain" to your domain
$valid_ref2="http://www.hazarton.com/kontakt.htm";// chamge "Your--domain" to your domain
$replyemail="info@buonarte.com";//change to your email address
// ------------------------------------------------------------

//clean input in case of header injection attempts!
function clean_input_4email($value, $check_all_patterns = true)
{
 $patterns[0] = '/content-type:/';
 $patterns[1] = '/to:/';
 $patterns[2] = '/cc:/';
 $patterns[3] = '/bcc:/';
 if ($check_all_patterns)
 {
  $patterns[4] = '/\r/';
  $patterns[5] = '/\n/';
  $patterns[6] = '/%0a/';
  $patterns[7] = '/%0d/';
 }
 //NOTE: can use str_ireplace as this is case insensitive but only available on PHP version 5.0.
 return preg_replace($patterns, "", strtolower($value));
}

$name = clean_input_4email($_POST["name"]);
$email = clean_input_4email($_POST["email"]);
$thesubject = clean_input_4email($_POST["thesubject"]);
$themessage = clean_input_4email($_POST["themessage"], false);

$error_msg='ERROR - not sent. Try again.';

$success_sent_msg='<p align="center"><strong>&nbsp;</strong></p>
                   <p align="center"><strong>Your message has been successfully sent to us<br>
                   </strong> and we will reply as soon as possible.</p>
                   <p align="center">A copy of your query has been sent to you.</p>
                   <p align="center">Thank you for contacting us.</p>';

$replymessage = "Hi $name

Thank you for your email.

We will endeavour to reply to you shortly.

Please DO NOT reply to this email.

Below is a copy of the message you submitted:
--------------------------------------------------
Subject: $thesubject
Query:
$themessage
--------------------------------------------------

Thank you";

// email variable not set - load $valid_ref1 page
if (!isset($_POST['email']))
{
 echo "<script language=\"JavaScript\"><!--\n ";
 echo "top.location.href = \"$valid_ref1\"; \n// --></script>";
 exit;
}

$ref_page=$_SERVER["HTTP_REFERER"];
$valid_referrer=0;
if($ref_page==$valid_ref1) $valid_referrer=1;
elseif($ref_page==$valid_ref2) $valid_referrer=1;
if(!$valid_referrer)
{
 echo "<script language=\"JavaScript\"><!--\n alert(\"$error_msg\");\n";
 echo "top.location.href = \"$valid_ref1\"; \n// --></script>";
 exit;
}
$themessage = "name: $name \nQuery: $themessage";
mail("$replyemail",
     "$thesubject",
     "$themessage",
     "From: $email\nReply-To: $email");
mail("$email",
     "Receipt: $thesubject",
     "$replymessage",
     "From: $replyemail\nReply-To: $replyemail");
echo $success_sent_msg;
/*
  PHP Form Mailer - phpFormMailer (easy to use and more secure than many cgi form mailers)
   FREE from:

    www.TheDemoSite.co.uk       */
?>