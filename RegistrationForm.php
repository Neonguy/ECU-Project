<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration Form</title>
    <link rel="stylesheet" href="styles.css">
</head>
<script>
function validateForm() {
    var doc = document.registrationForm;
    var mobileNumber = doc.mobileNumber.value;
    var firstName = doc.firstName.value;
    var surname = doc.surname.value;
    var password = doc.password.value;

    // Validate mobile number
    var mobilePattern = /^\+?([0-9]{1,3})\)?[-. ]?([0-9]{9,10})$/;
    if (!mobilePattern.test(mobileNumber)) {
        alert("Mobile number format is invalid.");
        return false;
    }

    // Validate password
    if (password.length < 8) {
        alert("Password must be at least 8 characters long.");
        return false;
    }
    if (password != confirmPassword) {
        alert("Passwords do not match.");
        return false;
    }

    return true;
}
</script>
<body>
    <div class="container">
	<h1>Registration Form</h1>
	<center>
			<form name="registrationForm" onsubmit="return validateForm()" method="post" action="register.php">
				<fieldset class="fieldset">
					<div class="form-group">
						<label for="mobileNumber">Mobile Number:</label>
						<input type="text" name="mobileNumber">
					</div>
					<div class="form-group">
						<label for="firstName">First Name:</label>
						<input type="text" name="firstName">
					</div>
					<div class="form-group">
						<label for="surname">Surname:</label>
						<input type="text" name="surname">
					</div>
					<div class="form-group">
						<label for="password">Password:</label>
						<input type="password" name="password">
					</div>
					<div class="form-group">
						<label for="password">Confirm Password:</label><input type="password" name="confirmPassword">
					</div>
					<input type="submit" class="submit-button" value="Submit">
				</fieldset>
			</form>
	</center>
	</div>
</body>
</html>
