
<?php
	try
	{
		$db = new PDO('mysql:host=localhost;port=6033;dbname=csg2431: interactive web development', 'root', '');
	}
	catch (PDOException $e)
	{
		echo '"error connecting to database server:<br/>';
		echo $e->getMessage();
		exit;
	}
	
	// Check if the form has been submitted
	if ($_POST && $_POST['bandName']) 
	{
		// Get the band name from the form
		$bandName = trim($_POST['bandName']);

		// Check if the band name is not empty
		if (!empty($bandName)) 
		{
			try 
			{
				// Prepare the SQL statement to insert the band name
				$stmt = $db->prepare("INSERT INTO band (band_name) VALUES (:band_name)");

				// Bind the parameter to the SQL query
				$stmt->bindParam(':band_name', $bandName);

				// Execute the query
				if ($stmt->execute()) 
				{
					// Redirect to AdminSection.php with a success message
					header("Location: AdminSection.php?status=success");
					exit();
				} 
				else 
				{
					// Redirect to AdminSection.php with an error message
					header("Location: AdminSection.php?status=error");
					exit();
				}
			} 
			catch (PDOException $e) 
			{
				// Redirect to AdminSection.php with an error message
				header("Location: AdminSection.php?status=error");
				exit();
			}
		} 
		else 
		{
			// Redirect to AdminSection.php with a validation message
			header("Location: AdminSection.php?status=empty");
			exit();
		}
	}
	// Check if the form has been submitted
	if ($_POST && $_POST['venueName']) 
	{
		// Get the venue name from the form
		$venueName = trim($_POST['venueName']);

		// Check if the venue name is not empty
		if (!empty($venueName)) 
		{
			try 
			{
				// Prepare the SQL statement to insert the venue name
				$stmt = $db->prepare("INSERT INTO venue (venue_name) VALUES (:venue_name)");

				// Bind the parameter to the SQL query
				$stmt->bindParam(':venue_name', $venueName);

				// Execute the query
				if ($stmt->execute()) 
				{
					// Redirect to AdminSection.php with a success message
					header("Location: AdminSection.php?status=success");
					exit();
				} 
				else 
				{
					// Redirect to AdminSection.php with an error message
					header("Location: AdminSection.php?status=error");
					exit();
				}
			} 
			catch (PDOException $e) 
			{
				// Redirect to AdminSection.php with an error message
				header("Location: AdminSection.php?status=error");
				exit();
			}
		} 
		else 
		{
			// Redirect to AdminSection.php with a validation message
			header("Location: AdminSection.php?status=empty");
			exit();
		}
	}
	// Check if the form has been submitted
	if ($_POST && $_POST['bandSelect']&& $_POST['venueSelect']&& $_POST['concert_date']) 
	{
		// Get the venue name from the form
		$band_id = trim($_POST['bandSelect']);
		$venue_id = trim($_POST['venueSelect']);
		$concert_date = trim($_POST['concert_date']);

		// Check if the venue name is not empty
		if (!empty($band_id) && !empty($venue_id) && !empty($concert_date)) 
		{
			try 
			{
				// Prepare the SQL statement to insert the concert details
				$stmt = $db->prepare("INSERT INTO concert (band_id, venue_id, concert_date) VALUES (:band_id, :venue_id, :concert_date)");

				// Bind the parameters to the SQL query
				$stmt->bindParam(':band_id', $band_id);
				$stmt->bindParam(':venue_id', $venue_id);
				$stmt->bindParam(':concert_date', $concert_date);

				// Execute the query
				if ($stmt->execute()) 
				{
					// Redirect to AdminSection.php with a success message
					header("Location: AdminSection.php?status=success");
					exit();
				} 
				else 
				{
					// Redirect to AdminSection.php with an error message
					header("Location: AdminSection.php?status=error");
					exit();
				}
			} 
			catch (PDOException $e) 
			{
				// Redirect to AdminSection.php with an error message
				header("Location: AdminSection.php?status=error");
				exit();
			}
		} 
		else 
		{
			// Redirect to AdminSection.php with a validation message
			header("Location: AdminSection.php?status=empty");
			exit();
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Free-Gigs</title>
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to Free-Gigs, the Free Concert Website!</h1>
        <div class="content">
            <section class="admin-area">
                <center>
                    <h2>Admin Menu</h2>
                    <ul>
                        <li>Manage Bands <input type="radio" name="menu" value="bands" onclick="changeLayout('bands')"></li>
                        <li>Manage Venues <input type="radio" name="menu" value="venues" onclick="changeLayout('venues')"></li>
                        <li>Add Concert <input type="radio" name="menu" value="concert" onclick="changeLayout('concert')"></li>
                        <li><a href="#">Log Out</a></li>
                    </ul>
                </center>
            </section>
            <section class="edit-area">
                <div id="bands" class="layout">
                    <div class="current-bands">
                        <center>
                        <h2>Current Bands</h2>
                        </center>
                        <ul>
                            <?php
								$result = $db->query("SELECT * FROM band ORDER BY band_id");

								if ($result && $result->rowCount() > 0) {
									foreach ($result as $row) {
										echo '<li><div class="label">' . $row['band_name'] . '</div>';
										echo '<div class="actions">';
										echo '<button>Edit</button>';
										echo '<button>Delete</button>';
										echo '</div></li>';
									}
								} else {
									echo '<li><div class="label">No bands available</div></li>';
								}
							?>
                        </ul>
                    </div>
                    <div class="add-new-band">
                        <center>
                        <h2>Add New Band</h2>
                        <form id="addBandForm" method="post" action="AdminSection.php" onsubmit="return validateBand()">
                            <input type="text" id="bandName" name="bandName" placeholder="Band Name">
                            <button type="submit">Add Band</button>
                        </form>
                        </center>
                    </div>
                </div>
                <div id="venues" class="layout">
                    <div class="current-bands">
                        <center>
                        <h2>Current Venues</h2>
                        </center>
                        <ul>
                            <?php
								$result = $db->query("SELECT * FROM venue ORDER BY venue_id");

								if ($result && $result->rowCount() > 0) {
									foreach ($result as $row) {
										echo '<li><div class="label">' . $row['venue_name'] . '</div>';
										echo '<div class="actions">';
										echo '<button>Edit</button>';
										echo '<button>Delete</button>';
										echo '</div></li>';
									}
								} else {
									echo '<li><div class="label">No venues available</div></li>';
								}
							?>
                        </ul>
                    </div>
                    <div class="add-new-band">
                        <center>
                        <h2>Add New Venue</h2>
                        <form id="addVenueForm" method="post" action="AdminSection.php" onsubmit="return validateVenue()">
                            <input type="text" id="venueName" name="venueName" placeholder="Venue Name">
                            <button type="submit">Add Venue</button>
                        </form>
                        </center>
                    </div>
                </div>
                <div id="concert" class="layout">
                    <div class="current-bands">
                        <center>
                        <h2>Current Concerts</h2>
                        </center>
                        <ul>
                            <?php
								$result = $db->query("
									SELECT c.concert_id, c.band_id, b.band_name, c.venue_id, v.venue_name, c.concert_date
									FROM concert c
									JOIN band b ON c.band_id = b.band_id
									JOIN venue v ON c.venue_id = v.venue_id
									ORDER BY c.concert_id
								");

								if ($result && $result->rowCount() > 0) {
									foreach ($result as $row) {
										echo '<li><div class="label"><center>' . $row['band_name'] . '</center>';
										echo '<center>' . $row['venue_name'] . '</center>';
										echo '<center>' . $row['concert_date'] . '</center></div>';
										echo '<div class="actions">';
										echo '<button>Delete</button>';
										echo '</div></li>';
									}
								} else {
									echo '<li><div class="label">No venues available</div></li>';
								}
							?>
                        </ul>
                    </div>
				
				
				
                    <center><h2>Add New Concert</h2>
                    <div class="add-new-band">
                        <form id="addConcertForm" method="post" action="AdminSection.php" onsubmit="return validateConcert()">
							<center>
							<select name="bandSelect" required>
								<option value=""selected disabled>Select a Band</option>
								 <?php
									$result = $db->query("Select * FROM band ORDER BY band_id");
									if ($result && $result->rowCount() > 0) 
									{
										foreach ($result as $row)
										{
											echo '<option value="'.$row['band_id'].'">',$row['band_name'].'</option>';
										}
									}
								?>
							</select>
							<select name="venueSelect" required>
								<option value=""selected disabled>Select a Venue</option>
								<?php
									$result = $db->query("Select * FROM venue ORDER BY venue_id");
									if ($result && $result->rowCount() > 0) 
									{
										foreach ($result as $row)
										{
											echo '<option value="'.$row['venue_id'].'">',$row['venue_name'].'</option>';
										}
									}
								?>
								</center>
							</select>
                            <input type="date" id="concert_date" name="concert_date" required>
							</center>
                            <button type="submit">Add Concert</button>
                        </form>
                    </div>
					</center>
                </div>
            </section>
        </div>
    </div>
    <script >
		// Function to change the layout based on the selected radio button
		function changeLayout(layoutType) {
			// Hide all layouts
			var layouts = document.querySelectorAll('.layout');
			layouts.forEach(function(layout) {
				layout.classList.remove('active');
			});

			// Show the selected layout
			var selectedLayout = document.getElementById(layoutType);
			selectedLayout.classList.add('active');
		}

		function validateBand() {
			var doc = document.forms["addBandForm"];

			var bandName = doc.bandName.value;
			if (bandName.length < 1) {
				alert("Please add a Band Name.");
				return false;
			}

			return true;
		}
		function validateVenue() {
			var doc = document.forms["addVenueForm"];

			var venueName = doc.venueName.value;
			if (!venueName) {
				alert("Please add a Venue Name.");
				return false;
			}

			return true;
		}
		function validateConcert() {
			var doc = document.forms["addConcertForm"];

			var bandName = doc.bandSelect.value;
			if (!bandName) {
				alert("Please add a Band.");
				return false;
			}
			
			var venueName = doc.venueSelect.value;
			if (!venueName) {
				alert("Please add a Venue.");
				return false;
			}


			return true;
		}
	</script>
</body>
</html>
