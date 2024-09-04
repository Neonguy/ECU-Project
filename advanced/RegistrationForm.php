
<?php
	require 'db_connect.php';
	
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
	<h1>Registration Form</h1>
	<center>
		<form name="registrationForm" onsubmit="return validateForm()" method="post" action="Register_User.php">
			<fieldset class="fieldset">
			<center>
				<input type="text" name="mobile_number" required placeholder="Mobile Number">
				<input type="text" name="first_name" required placeholder="First Name">
				<input type="text" name="surname" required placeholder="Surname">
				<input type="password" name="password" placeholder="Password">
				<input type="password" name="confirm_password" placeholder="Confirm Password">
				<div class="input-container"><input type="date" id="date_of_birth" name="date_of_birth" required></div>
				
				
			</center>
				<button type="submit">Register</button>
			</fieldset>
		</form>
		<button onclick="window.location.href='PublicSection.php';">Go Back</button>

		<?php
			// Display the confirmation message
			if ($confirmationMessage) {
				echo '<p style="margin-top: 20px;">' . htmlspecialchars($confirmationMessage) . '</p>';
			}
		?>
	</center>
	</div>
</body>
<script>
function validateForm() {
    var doc = document.registrationForm;
    var mobile_number = doc.mobile_number.value;
    var password = doc.password.value;
    var confirm_password = doc.confirm_password.value;
	
    // Validate mobile number
    var mobilePattern = /^\+?([0-9]{1,3})\)?[-. ]?([0-9]{9,10})$/;
    if (!mobilePattern.test(mobile_number)) {
        alert("Mobile number format is invalid.");
        return false;
    }

    // Validate password
    if (password.length < 5) {
        alert("Password must be at least 5 characters long.");
        return false;
    }
    if (password != confirm_password) {
        alert("Passwords do not match.");
        return false;
    }

    return true;
}
</script>
</html>
