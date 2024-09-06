<?php
	require 'db_connect.php';
	
	if (isset($_SESSION['mobile'])) {
		header("Location: AttendeeSection.php");
	}

	$confirmationMessage = '';
	
	if (isset($_GET['status'])) {
		if ($_GET['status'] == 'success') {
			$confirmationMessage = 'Registration Successful.';
		} elseif ($_GET['status'] == 'error') {
			$confirmationMessage = 'Registration Failed.';
		} elseif ($_GET['status'] == 'empty') {
			$confirmationMessage = 'Please fill in all required fields.';
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Public Home Page</title>
	<link rel="stylesheet" href="styles.css">
	<style>
		input[type=text], input[type=mobile], input[type=password] {
			width: 100%; 
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
		<h1>Welcome to Events Listing, the Free Event Website!</h1>
		<div class="content">
			<section class="admin-area">
				<center>
				<p><i>You cannot book tickets</i></p>
				<p><i>unless you are logged in</i></p>
				
				<form id="login" method="post" action="user_login.php">
					<input type="text" name="mobile_number" required placeholder="Mobile Number">
					<input type="password" name="password" placeholder="Password">
					<br/>
					<button name = "login" type = "submit">Log in</button>
				</form>
				
				<p>Click <a href="RegistrationForm.php">here</a> to register.</p>
				<a href="admin_login.php">Admin Login</a>
				</center>  
			</section>
			<section class="edit-area">
				<center>				
				<h2>Upcoming Concerts:</h2>
				</center>
					<?php			
						// Fetch and display concert details
						$result = $db->query ("SELECT c.concert_id, c.band_id, b.band_name, c.venue_id, v.venue_name, v.capacity, c.concert_date, c.adult, COUNT(j.concert_id) AS tickets_sold
							FROM concert c
							JOIN band b ON c.band_id = b.band_id
							JOIN venue v ON c.venue_id = v.venue_id
							LEFT JOIN booking j ON c.concert_id = j.concert_id
							GROUP BY c.concert_id
							ORDER BY c.concert_date");

						if ($result && $result->rowCount() > 0) {
							echo '<ul>';
							foreach ($result as $row) {
								
								displayConcert($row);
							}
							echo '</ul>';
						} else {
							echo '<li><div class="label">No concerts available</div></li>';
						}	
					?>
					
				</center>
			</section>   
		</div>	
		<?php
			// Display the confirmation message
			if ($confirmationMessage) {
				echo '<p style="margin-top: 20px;">' . ($confirmationMessage) . '</p>';
			}
		?>	
	</div>	   
	<script>
				/*-- validation alerts --> */
	</script>
</body>
</html>
