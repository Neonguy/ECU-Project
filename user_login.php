
<?php
	require 'db_connect.php';

	if (isset($_POST['mobile_number']) && isset($_POST['password'])) {
		
		$mobile_number = $_POST['mobile_number'];
		$password = $_POST['password'];
		
		try 
		{
			$stmt = $db->prepare("SELECT * FROM attendee WHERE mobile_number = ? AND password = ?");
			$stmt->execute([$mobile_number, $password]);
			$user = $stmt->fetch();
			
			if ($user) {
				$_SESSION['mobile'] = $user['mobile_number'];
				header('Location: PublicSection.php?success');
			}
			else
			{
				header('Location: PublicSection.php?error');
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