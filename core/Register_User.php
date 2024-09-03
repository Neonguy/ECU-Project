
<?php
	require 'db_connect.php';
			
	// Check if the form has been submitted
	if ($_POST && isset($_POST['mobile_number']) && isset($_POST['first_name']) && isset($_POST['surname']) && isset($_POST['password']) && isset($_POST['date_of_birth'])) {
		// Get the details name from the form
		$mobile_number = trim($_POST['mobile_number']);
		$first_name = trim($_POST['first_name']);
		$surname = trim($_POST['surname']);
		$password = trim($_POST['password']);
		$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
		$dob = trim($_POST['date_of_birth']);

		// Check if the venue name is not empty
		if (!empty($mobile_number) && !empty($first_name) && !empty($surname) && !empty($password) && !empty($dob)) {
			try {
				// Prepare the SQL statement to insert the concert details
				$stmt = $db->prepare("INSERT INTO attendee (mobile_number, first_name, surname, password, dob) VALUES (:mobile_number, :first_name, :surname, :password, :dob)");

				// Bind the parameters to the SQL query
				$stmt->bindParam(':mobile_number', $mobile_number);
				$stmt->bindParam(':first_name', $first_name);
				$stmt->bindParam(':surname', $surname);
				$stmt->bindParam(':password', $password);
				// advanced requirement
				//$stmt->bindParam(':password', $hashedPassword);
				$stmt->bindParam(':dob', $dob);

				// Execute the query
				if ($stmt->execute()) {
					// Redirect to RegistrationForm.php with a success message
					header("Location: PublicSection.php?status=success");
					exit();
				} else {
					// Redirect to RegistrationForm.php with an error message
					header("Location: RegistrationForm.php?status=error");
					exit();
				}
			} 
			catch (PDOException $e) {
				// Redirect to RegistrationForm.php with an error message
				header("Location: RegistrationForm.php?status=error");
				exit();
			}
		} else {
			// Redirect to RegistrationForm.php with a validation message
			header("Location: RegistrationForm.php?status=empty");
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
    </style>
</head>
<body>
</body>
<script>
</script>
</html>
