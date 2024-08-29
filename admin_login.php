
<?php
	require 'db_connect.php';

	if (isset($_POST['username']) && isset($_POST['password'])) {
		
		$user_name = $_POST['username'];
		$password = $_POST['password'];
		
		try 
		{
			$stmt = $db->prepare("SELECT * FROM admin WHERE username = ? AND password = ?");
			$stmt->execute([$user_name, $password]);
			$user = $stmt->fetch();
			
			if ($user) {
				$_SESSION['uname'] = $user['username'];
				header('Location: AdminSection.php');
			}
			else
			{
				echo '<center><h1>Invalid Credentials</h1></center>';
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
    <style>
        
		input[type=text], input[type=password] {
			width: 70%; 
			margin: 3px; 
			box-sizing: border-box;
			text-align: center;
		}
		button[type=submit] { 
			width: 30%; 
			background-color: green; 
			color: white; 
			padding: 10px; 
			margin: 10px; 
			border-radius: 5px; 
			cursor: pointer; 
		}
    </style>
</head>
<body>

	<div class="container">
		<center>
		<h2>Please log In</h2>
					
		<form id="login" method="post" action="admin_login.php">
			<fieldset class="fieldset">
			<input type="text" name="username" required placeholder="Username">
			<input type="password" name="password" placeholder="Password" required>
			<button type = "submit">Log in</button>
			</fieldset>
		</form>
          <a href="PublicSection.php">Return Home</a>
		</center>
	</div>

</body>
<script>
</script>
</html>