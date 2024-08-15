
<?php
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
	
	try
	{
		$db = new PDO('mysql:host=localhost;port=6033;dbname=csg2431: interactive web development', 'root', '');
	}
	catch (PDOException $e)
	{
		echo 'error connecting to database server:<br/>';
		echo $e->getMessage();
		exit;
	}
	
	// Check if the form has been submitted
	if ($_POST && isset($_POST['mobile_number']) && isset($_POST['first_name']) && isset($_POST['surname']) && isset($_POST['password'])) 
	{
		// Get the details name from the form
		$mobile_number = trim($_POST['mobile_number']);
		$first_name = trim($_POST['first_name']);
		$surname = trim($_POST['surname']);
		$password = trim($_POST['password']);

		// Check if the venue name is not empty
		if (!empty($mobile_number) && !empty($first_name) && !empty($surname) && !empty($password)) 
		{
			try 
			{
				// Prepare the SQL statement to insert the concert details
				$stmt = $db->prepare("INSERT INTO attendee (mobile_number, first_name, surname, password) VALUES (:mobile_number, :first_name, :surname, :password)");

				// Bind the parameters to the SQL query
				$stmt->bindParam(':mobile_number', $mobile_number);
				$stmt->bindParam(':first_name', $first_name);
				$stmt->bindParam(':surname', $surname);
				$stmt->bindParam(':password', $password);

				// Execute the query
				if ($stmt->execute()) 
				{
					// Redirect to RegistrationForm.php with a success message
					header("Location: RegistrationForm.php?status=success");
					exit();
				} 
				else 
				{
					// Redirect to RegistrationForm.php with an error message
					header("Location: RegistrationForm.php?status=error");
					exit();
				}
			} 
			catch (PDOException $e) 
			{
				// Redirect to RegistrationForm.php with an error message
				header("Location: RegistrationForm.php?status=error");
				exit();
			}
		} 
		else 
		{
			// Redirect to RegistrationForm.php with a validation message
			header("Location: RegistrationForm.php?status=empty");
			exit();
		}
	}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration Form</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        
		input[type=password] {
			width: 70%; 
			margin: 3px; 
			box-sizing: border-box;
			text-align: center;
		}
		.submit-button { 
			width: 30%; 
			background-color: #4CAF50; 
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
			<form name="registrationForm" onsubmit="return validateForm()" method="post" action="RegistrationForm.php">
				<fieldset class="fieldset">
				<center>
					<input type="text" name="mobile_number" required placeholder="Mobile Number">
					<input type="text" name="first_name" required placeholder="First Name">
					<input type="text" name="surname" required placeholder="Surname">
					<input type="password" name="password" placeholder="Password">
					<input type="password" name="confirm_password" placeholder="Confirm Password">
					
				</center>
					<input type="submit" class="submit-button" value="Submit">
				</fieldset>
			</form>
			<button onclick="history.back()">Go Back</button>
	<?php
		// Display the confirmation message
		if ($confirmationMessage) 
		{
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
    var first_name = doc.first_name.value;
    var surname = doc.surname.value;
    var password = doc.password.value;
    var confirm_password = doc.confirm_password.value;

    // Validate mobile number
    var mobilePattern = /^\+?([0-9]{1,3})\)?[-. ]?([0-9]{9,10})$/;
    if (!mobilePattern.test(mobile_number)) {
        alert("Mobile number format is invalid.");
        return false;
    }

    // Validate password
    if (password.length < 8) {
        alert("Password must be at least 8 characters long.");
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
