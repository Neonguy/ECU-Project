<?php
	require 'db_connect.php';
  
	if (!isset($_SESSION['real_name'])) {
		header("Location: PublicSection.php");
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendee Portal</title>
    <link rel="stylesheet" href="styles.css">

    <style>
                <!-- overide css -->
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to Events Listing, the Free Event Website!</h1>
        <div class="content">
            <section class="admin-area">
                <center>
			
					<?php
						if ($_SESSION['real_name'] != '') {
							echo '<p><i>You are logged in as ' . $_SESSION['real_name'] . '</i></p>';
							echo '<button name="logout" href="#">Log Out</button>';
						}
						else
						{
							echo '<p><i>You are not logged in.</i></p>';
							echo '<button name="login"a href="#">Log In</button>';
							
						}
					?>
                </center>
            </section>
            <section class="edit-area">
                <!-- need to modify right hand box size in css -->                
                <center>

              <h3>Upcoming Concerts:</h3>
				<?php            
					// Fetch and display concert details
					$result = $db->query ("SELECT c.concert_id, c.band_id, b.band_name, c.venue_id, v.venue_name, c.concert_date, c.adult
						FROM concert c
						JOIN band b ON c.band_id = b.band_id
						JOIN venue v ON c.venue_id = v.venue_id
						ORDER BY c.concert_id");

						if ($result && $result->rowCount() > 0) 
						{
							echo '<ul>';
							foreach ($result as $row) 
							{   
								displayConcert($row);			
							}
							echo '</ul>';
						}
						else 
						{
							echo '<li><div class="label">No concerts available</div></li>';
						} 
				?>
					
                <h3>Your Bookings:</h3>
                <!-- retrieve login bookings from database -->

            </section>
        </div>
    </div>
    <script>
                /*-- validation alerts --> */
	</script>
</body>
</html>
