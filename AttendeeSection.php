<?php
	require 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendee Portal</title>
    <link rel="stylesheet" href="styles1.css">

    <style>
                /*-- overide css --> */
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to Events Listing, the Free Event Website!</h1>
        <div class="content">
            <section class="admin-area">
                <center>
			
					<?php
						if ($username != '') {
							echo '<p><i>You are logged in as ' . $username . '<i></p>';
							echo '<button name="logout" href="#">Log Out</button>';
						}
						else
						{
							echo '<p><i>You are not logged in.<i></p>';
							echo '<button name="login"a href="#">Log In</button>';
							
						}
					?>
                </center>
            </section>
            <section class="edit-area">
                <!-- need to modify right hand box size in css -->                
                <center>
                <h3>Upcoming Concerts:</h3>
                <!-- retrieve upcoming concerts from database -->
                <h3>Your Bookings:</h3>
                <!-- retrieve login bookings from database -->
                <center>
            </section>
    <script>
                /*-- validation alerts --> */
	</script>
</body>
</html>
