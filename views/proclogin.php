<?php
declare(strict_types=1);
if (basename($_SERVER['SCRIPT_NAME']) == basename(__FILE__))
{
    header('Location: /anthony1/phprouter');
}

function checklogin()
{
	include_once '/home/stu/anthony1/blurg.inc.php';
	include_once 'inc/passwordfuncs.inc.php';
	# VALIDATE THIS YOU MORON!!
	$email = $_POST['username'];
	$userpassword = $_POST['loginpass'];

	$db1 = new mysqli('localhost', 'anthony1', $password, 'STUanthony1');
	if($db1->connect_errno > 0)
	{
		die('Unable to connect to database [' . $db1->connect_error . ']');
	}

	$query1 = $db1->prepare("select hashpw from grcustomers where email = ?");
	$query1->bind_param('s',$email);

	$query1->execute();
	$query1->store_result();
	$userexists = $query1->num_rows;
	if ($userexists == 1 )
	{
		$query1->bind_result($hashedpw, $status);
		$row = $query1->fetch();
		$query1->free_result();
		if (password_verify($userpassword, $hashedpw))
		{
			echo "You have logged in successfully";
      $_SESSION['loggedIn'] = true;
			$_SESSION['level'] = $status;
		}
		else
		{
			echo "No soup for you!";
		}
	}
	else
	{
		echo "user not found";
	}


}
checklogin();
?>
