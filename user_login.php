
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
				$hashedPassword = $user['password'];
				if (password_verify($password, $hashedPassword)) {
					echo "Password is correct!";
					$_SESSION['mobile'] = $user['mobile_number'];
					$_SESSION['real_name'] = $user['first_name'] . ' ' . $user['surname'];
					header('Location: AttendeeSection.php');
				} else {
					header('Location: PublicSection.php?status=error');
				}
				
			}
			else
			{
				header('Location: PublicSection.php?status=error');
			}
		}
		catch (PDOException $e) 
		{
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