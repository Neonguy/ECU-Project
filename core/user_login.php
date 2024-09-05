
<?php
	require 'db_connect.php';

	if (isset($_POST['mobile_number']) && isset($_POST['password'])) {
		
		$mobile_number = $_POST['mobile_number'];
		$password = $_POST['password'];
		
		try 
		{
			$stmt = $db->prepare("SELECT * FROM attendee WHERE mobile_number = ?");
			$stmt->execute([$mobile_number]);
			$user = $stmt->fetch();
			
			if ($user) {
				$userpassword = $user['password'];
				if ($password == $userpassword) {
					echo "Password is correct!";
					$_SESSION['mobile'] = $user['mobile_number'];
					$_SESSION['real_name'] = $user['first_name'] . ' ' . $user['surname'];
					header('Location: AttendeeSection.php');
				} else {
					header('Location: PublicSection.php?login=passworderror');
				}
			} else {
				header('Location: PublicSection.php?login=usererror');
			}
		}
		catch (PDOException $e) 
		{
			echo 'exception';
			echo '<pre>';
			print_r($e);
			echo '</pre>';
			exit();
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="author" content="Sebbs" />
	<meta charset="UTF-8">
	<title>Registration Form</title>
	<link rel="stylesheet" href="styles.css">
</head>
</html>