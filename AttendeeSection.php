<?php
	require 'db_connect.php';

	$confirmationMessage = '';

	if (!isset($_SESSION['real_name'])) {
		header("Location: PublicSection.php");
	}

	if (isset($_GET['book'])) {
		if ($_GET['book'] == 'success') {
			$confirmationMessage = 'Add Booking Successful.';
		} elseif ($_GET['book'] == 'failed') {
			$confirmationMessage = 'Add Booking Failed.';
		}
	}
	if (isset($_GET['delete'])) {
		if ($_GET['delete'] == 'success') {
			$confirmationMessage = 'Delete Booking Successful.';
		} elseif ($_GET['delete'] == 'failed') {
			$confirmationMessage = 'Delete Booking Failed.';
		}
	}

	// these calls need to be below the add and delete tasks to ensure data is up to date for display
	// Fetch and display concert details
	$concerts = $db->query ("SELECT c.concert_id, c.band_id, b.band_name, c.venue_id, v.venue_name, v.capacity, c.concert_date, c.adult, COUNT(j.concert_id) AS tickets_sold
		FROM concert c
		JOIN band b ON c.band_id = b.band_id
		JOIN venue v ON c.venue_id = v.venue_id
		LEFT JOIN booking j ON c.concert_id = j.concert_id
		GROUP BY c.concert_id, c.band_id, b.band_name, c.venue_id, v.venue_name, v.capacity, c.concert_date, c.adult
		ORDER BY c.concert_date");

	// get bookings by mobile number
	$bookings = $db->prepare("SELECT a.booking_id, a.mobile_number, a.concert_id, c.band_id, b.band_name, c.venue_id, v.venue_name, c.concert_date, c.adult
		FROM booking a
		JOIN concert c ON a.concert_id = c.concert_id
		JOIN band b ON c.band_id = b.band_id
		JOIN venue v ON c.venue_id = v.venue_id
		WHERE a.mobile_number = ?");
	$bookings->execute([$_SESSION['mobile']]);
	// this got messy calling the function to get ids...
	// leave this part alone, we need to fetch all here
	$allBookings = $bookings->fetchAll();	

	$bookedConcertIds = [];
	foreach ($allBookings as $row) {
		$bookedConcertIds[] = $row['concert_id'];
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
		.layout {
			display: none;
		}
		.layout.active {
			display: block;
			margin-top: 20px;
		}

		.actions button {
			margin-left: 10px;
		}
		button[name=delete], button[name=cancel] {
			background-color: red;
		}
		button[name=login] { 
			width: 30%; 
			background-color: green; 
			color: white; 
			padding: 10px; 
			margin: 10px; 
			border-radius: 5px; 
			cursor: pointer; 
		}
		input[type=text], input[type=password], input[type=date], input[type=datetime-local], select {
			margin: 3px;
			width: 70%; 
			padding: 8px; 
			box-sizing: border-box;
			text-align: center;
		}
	</style>
</head>
<body>
	<div class="container">
		<h1>Welcome to Events Listing, the Free Event Website!</h1>
		<div class="content">
			<section class="admin-area">
				<center>
			
					<?php
						// this is always here or we get redirected before this point
						if ($_SESSION['real_name'] != '') {
							echo '<p><i>You are logged in as ' . $_SESSION['real_name'] . '</i></p>';
						}
					?>
					<ul>
						<li>View Concerts <input type="radio" name="attendee" value="concerts" onclick="adminChangeLayout('concerts')"></li>
						<li>Your Bookings <input type="radio" name="attendee" value="bookings" onclick="adminChangeLayout('bookings')"></li>
						<li><a href="logout.php">Log Out</a></li>
					</ul>
					
				</center>
			</section>
			<section class="edit-area">
				<div id="concerts" class="layout">
					<!-- need to modify right hand box size in css -->				
					<center>
					<h3>Upcoming Concerts</h3>
					<hr>
					<div class="current-bands">
					<?php
						if ($concerts && $concerts->rowCount() > 0) 
						{
							echo '<ul>';
							foreach ($concerts as $row) 
							{	
								displayConcert($row);
								displayBookingButton($row,$bookedConcertIds);
							}
							echo '</ul>';
						}
						else 
						{
							echo '<li><div class="label">No concerts available</div></li>';
						} 
					?>
					</div>
					</center>
				</div>
					
				<div id="bookings" class="layout">
					<center>
					<h3>Your Bookings</h3>
					<hr>
					
					<div class="current-bands">
					<?php
						if ($allBookings) {
							
							echo '<ul>';
							foreach ($allBookings as $row) {
								displayConcert($row);
								displayCancelButtons($row);
							}
							echo '</ul>';
							
						} else {
							echo 'No results found';
						}
					?>
					</div>
					</center>
				</div>
			</section>
		</div>
			<?php
				// Display the confirmation message
				if ($confirmationMessage) 
				{
					echo '<p style="margin-top: 20px;">' . htmlspecialchars($confirmationMessage) . '</p>';
				}
			?>
	</div>
	<script>
				
		function adminChangeLayout(layoutType) 
		{
			// Hide all layouts
			var layouts = document.querySelectorAll('.layout');
			layouts.forEach(function(layout) 
			{
				layout.classList.remove('active');
			});

			// Show the selected layout
			var selectedLayout = document.getElementById(layoutType);
			if (selectedLayout) 
			{
				selectedLayout.classList.add('active');
			}

			// Save the selected layout to localStorage
			localStorage.setItem('adminMenu', layoutType);
		}

		document.addEventListener('DOMContentLoaded', function() 
		{
			// Retrieve the selected layout from localStorage
			var adminMenu = localStorage.getItem('adminMenu');
			if (adminMenu) 
			{
				// Set the corresponding radio button as checked
				var radio = document.querySelector('input[name="attendee"][value="' + adminMenu + '"]');
				if (radio) 
				{
					radio.checked = true;
				}

				// Display the selected layout
				adminChangeLayout(adminMenu);
			} 
			else 
			{
				// If no value is stored, default to showing the first layout (optional)
				adminChangeLayout('concerts'); // Set default if no previous selection exists
			}
		});
	</script>
</body>
</html>
