<?php /* Template Name: Messages */ ?>

<h1>Menu Update</h1>

<?php $message = '
Dear Tyler,<br/>
Your menu for Shotgun Willie\'s has been successfully updated and the changes are now reflected in the Srvd app. Please email us at support@nick.hesling.com:81 if you have any questions or concerns.<br/>
Team Srvd';?>
<?php
	echo $message;
	//wp_mail('kyle@nick.hesling.com:81','Srvd Menu Update',$message);
?>

<h1>Welcome Email</h1>

<?php $message = '
Hello John,<br/>
Welcome to Srvd! Here is your login information: http://flwtb.co/Y1lHyM. On this page we have included a link to a Google Doc containing your drink menu. Please update this menu as soon as possible so we can get the changes into the app.<br/>
Your operations manager is Sasha Juliard. If you have any questions, please call him anytime at 408-600-4989 or shoot him an email at <a href="mailto:sasha@nick.hesling.com:81">sasha@nick.hesling.com:81</a>.<br/>
For billing inquiries, please email Wenda Blake at <a href="mailto:wenda.blake@dexone.com">wenda.blake@dexone.com</a>.<br/>
We have found the fastest way to educate your customers about Srvd is by giving them free $10 drink vouchers and letting them test it out.<br/>
We look forward to working with you.<br/>
Kyle Hill<br/>Founder, CEO<br/>';?>
<?php
	echo $message;
	wp_mail('kyle@nick.hesling.com:81','Welcome to Srvd',$message);
/*
	wp_mail('sasha@nick.hesling.com:81','Welcome to Srvd',$message);
	wp_mail('wenda.blake@dexone.com','Welcome to Srvd',$message);
*/
?>

