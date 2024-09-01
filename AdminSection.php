
<?php
	require 'db_connect.php';
		
	if (!isset($_SESSION['username'])) {
		header("Location: admin_login.php");
	}
	
	$confirmationMessage = '';
	
	if (isset($_GET['addConcert'])) {
		if ($_GET['addConcert'] == 'success') {
			$confirmationMessage = 'Add Concert Successful.';
		} elseif ($_GET['addConcert'] == 'error') {
			$confirmationMessage = 'Add Concert Failed.';
		} elseif ($_GET['addConcert'] == 'error') {
			$confirmationMessage = 'Add Concert had data missing.';
		}
	}
	if (isset($_GET['addVenue'])) {
		if ($_GET['addVenue'] == 'success') {
			$confirmationMessage = 'Add Venue Successful.';
		} elseif ($_GET['addVenue'] == 'error') {
			$confirmationMessage = 'Add Venue Failed.';
		} elseif ($_GET['addVenue'] == 'error') {
			$confirmationMessage = 'Add Venue had data missing.';
		}
	}
	if (isset($_GET['addBand'])) {
		if ($_GET['addBand'] == 'success') {
			$confirmationMessage = 'Add Band Successful.';
		} elseif ($_GET['addBand'] == 'error') {
			$confirmationMessage = 'Add Band Failed.';
		} elseif ($_GET['addBand'] == 'error') {
			$confirmationMessage = 'Add Band had data missing.';
		}
	}
	
	// Variable to track which band/venue is being edited
	$editBandId = null; 
	$editvenueId = null;
	$editconcertId = null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="author" content="Sebbs" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events Listing</title>
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
		button[name=delete] {
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
                    <h2>Admin Menu</h2>
					<hr>
                    <ul>
                        <li>Manage Bands <input type="radio" name="menu" value="bands" onclick="changeLayout('bands')"></li>
                        <li>Manage Venues <input type="radio" name="menu" value="venues" onclick="changeLayout('venues')"></li>
                        <li>Manage Concerts <input type="radio" name="menu" value="concert" onclick="changeLayout('concert')"></li>
                        <li><a href="logout.php">Log Out</a></li>
                    </ul>
                </center>
            </section>
            <section class="edit-area">
                <div id="bands" class="layout">
                    <div class="add-new-band">
                        <center>
                        <h2>Add New Band</h2>
					<hr>
                        <form id="addBandForm" method="post" action="ProcessAdmin.php" onsubmit="return validateBand()">
                            <input type="text" id="bandName" name="bandName" placeholder="Band Name">
                            <button type="submit">Add Band</button>
                        </form>
                        </center>
                    </div>
                    <div class="current-bands">
                        <center>
                        <h2>Current Bands</h2>
                        </center>
                        <ul>
							 <?php

								// Handle deletion
								if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_band_id'])) 
								{
									$bandId = intval($_POST['delete_band_id']);

									try 
									{
										// Check if there are related rows in the `concert` table
										$stmt = $db->prepare("SELECT COUNT(*) FROM concert WHERE band_id = ?");
										$stmt->execute([$bandId]);
										$count = $stmt->fetchColumn();

										if ($count > 0) 
										{
											$confirmationMessage = "Cannot delete the band. There are concerts that depend on this band.";
										} 
										else 
										{
											// No dependencies, proceed with deletion
											$stmt = $db->prepare("DELETE FROM band WHERE band_id = ?");
											$stmt->execute([$bandId]);

											if ($stmt->rowCount() > 0) 
											{
												$confirmationMessage = "Band deleted successfully.";
											} 
											else 
											{
												$confirmationMessage = "Failed to delete the Band.";
											}
										}
									} 
									catch (PDOException $e) 
									{
										$confirmationMessage = "Error: " . $e->getMessage();
									}
								}
																
								// Handle band name update
								if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_band_id'])) 
								{
									$bandId = intval($_POST['update_band_id']);
									$newBandName = trim($_POST['new_band_name']);

									if (!empty($newBandName)) 
									{
										try 
										{
											$stmt = $db->prepare("UPDATE band SET band_name = ? WHERE band_id = ?");
											$stmt->execute([$newBandName, $bandId]);

											if ($stmt->rowCount() > 0) 
											{
												$confirmationMessage = "Band name updated successfully.";
											} 
											else 
											{
												$confirmationMessage = "Failed to update the band name.";
											}			
										} 
										catch (PDOException $e) 
										{
											$confirmationMessage = "Error: " . $e->getMessage();
										}
									} 
									else 
									{
										$confirmationMessage = "Band name cannot be empty.";
									}
								}

								// Handle edit button click (to track which band is being edited)
								if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_band_id'])) 
								{
									$editBandId = intval($_POST['edit_band_id']);
								}
								
								// Fetch and display bands
								$result = $db->query("SELECT * FROM band ORDER BY band_id");

								if ($result && $result->rowCount() > 0) 
								{
									echo '<ul>';
									foreach ($result as $row) 
									{
										echo '<li>';
										
										if ($editBandId === intval($row['band_id'])) 
										{
											echo '<div style="margin-top: 10px;">';
											echo '<form method="POST">';
											echo '<input type="hidden" name="update_band_id" value="' . $row['band_id'] . '">';
											echo '<input type="text" name="new_band_name" placeholder="New Band Name" value="' . htmlspecialchars($row['band_name']) . '">';
											echo '</div>';
											echo '<div class="actions">';
											echo '<button name="update">Save</button>';
											echo '</form>';
											echo '</div>';
										}
										else
										{
											echo '<div class="label">' . htmlspecialchars($row['band_name']) . '</div>';
										}
										
										if ($editBandId === intval($row['band_id'])) 
										{
										}
										else
										{
											echo '<div class="actions">';
											
											// Edit button
											echo '<form method="POST" style="display:inline;">';
											echo '<input type="hidden" name="edit_band_id" value="' . $row['band_id'] . '">';
											echo '<button name="edit">Edit</button>';
											echo '</form>';

											// Delete button
											echo '<form method="POST" style="display:inline; margin-left: 10px;">';
											echo '<input type="hidden" name="delete_band_id" value="' . $row['band_id'] . '">';
											echo '<button name="delete" onclick="return confirm(\'Are you sure you want to delete this thread?\')">Delete</button>';
											echo '</form>';
											
											echo '</div>';
										}
										echo '</li>';
									}
									echo '</ul>';
								} 
								else 
								{
									echo '<li><div class="label">No bands available</div></li>';
								}
							?>
                        </ul>
                    </div>
                </div>
                <div id="venues" class="layout">
                    <div class="add-new-band">
                        <center>
                        <h2>Add New Venue</h2>
					<hr>
                        <form id="addVenueForm" method="post" action="ProcessAdmin.php" onsubmit="return validateVenue()">
                            <input type="text" id="venueName" name="venueName" placeholder="Venue Name">
                            <button type="submit">Add Venue</button>
                        </form>
                        </center>
                    </div>
                    <div class="current-bands">
                        <center>
                        <h2>Current Venues</h2>
                        </center>
                        <ul>
							 <?php

								// Handle deletion
								if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_venue_id'])) 
								{
									$venueId = intval($_POST['delete_venue_id']);

									try 
									{
										// Check if there are related rows in the `concert` table
										$stmt = $db->prepare("SELECT COUNT(*) FROM concert WHERE venue_id = ?");
										$stmt->execute([$venueId]);
										$count = $stmt->fetchColumn();

										if ($count > 0) 
										{
											$confirmationMessage = "Cannot delete the venue. There are concerts that depend on this venue.";
										} 
										else 
										{
											// No dependencies, proceed with deletion
											$stmt = $db->prepare("DELETE FROM venue WHERE venue_id = ?");
											$stmt->execute([$venueId]);

											if ($stmt->rowCount() > 0) 
											{
												$confirmationMessage = "Venue deleted successfully.";
											} 
											else 
											{
												$confirmationMessage = "Failed to delete the Venue.";
											}
										}
									} 
									catch (PDOException $e) 
									{
										$confirmationMessage = "Error: " . $e->getMessage();
									}
								}
																
								// Handle venue name update
								if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_venue_id'])) 
								{
									$venueId = intval($_POST['update_venue_id']);
									$newvenueName = trim($_POST['new_venue_name']);

									if (!empty($newvenueName)) 
									{
										try 
										{
											$stmt = $db->prepare("UPDATE venue SET venue_name = ? WHERE venue_id = ?");
											$stmt->execute([$newvenueName, $venueId]);

											if ($stmt->rowCount() > 0) 
											{
												$confirmationMessage = "Venue name updated successfully.";
											} 
											else 
											{
												$confirmationMessage = "Failed to update the Venue name.";
											}			
										} 
										catch (PDOException $e) 
										{
											$confirmationMessage = "Error: " . $e->getMessage();
										}
									} 
									else 
									{
										$confirmationMessage = "Venue name cannot be empty.";
									}
								}

								// Handle edit button click (to track which venue is being edited)
								if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_venue_id'])) 
								{
									$editvenueId = intval($_POST['edit_venue_id']);
								}
								
								// Fetch and display venues
								$result = $db->query("SELECT * FROM venue ORDER BY venue_id");

								if ($result && $result->rowCount() > 0) 
								{
									echo '<ul>';
									foreach ($result as $row) 
									{
										echo '<li>';
										
										if ($editvenueId === intval($row['venue_id'])) 
										{
											echo '<div style="margin-top: 10px;">';
											echo '<form method="POST">';
											echo '<input type="hidden" name="update_venue_id" value="' . $row['venue_id'] . '">';
											echo '<input type="text" name="new_venue_name" placeholder="New venue Name" value="' . htmlspecialchars($row['venue_name']) . '">';
											echo '</div>';
											echo '<div class="actions">';
											echo '<button name="update">Save</button>';
											echo '</form>';
											echo '</div>';
										}
										else
										{
											echo '<div class="label">' . htmlspecialchars($row['venue_name']) . '</div>';
										}
										
										if ($editvenueId === intval($row['venue_id'])) 
										{
										}
										else
										{
											echo '<div class="actions">';
											
											// Edit button
											echo '<form method="POST" style="display:inline;">';
											echo '<input type="hidden" name="edit_venue_id" value="' . $row['venue_id'] . '">';
											echo '<button name="edit">Edit</button>';
											echo '</form>';

											// Delete button
											echo '<form method="POST" style="display:inline; margin-left: 10px;">';
											echo '<input type="hidden" name="delete_venue_id" value="' . $row['venue_id'] . '">';
											echo '<button name="delete" onclick="return confirm(\'Are you sure you want to delete this thread?\')">Delete</button>';
											echo '</form>';
											
											echo '</div>';
										}
										echo '</li>';
									}
									echo '</ul>';
								} 
								else 
								{
									echo '<li><div class="label">No venues available</div></li>';
								}
							?>
                        </ul>
                    </div>
                </div>
				
                <div id="concert" class="layout">
					<center>
					<h2>
					<?php 
						// Handle edit button click (to track which band is being edited)
						if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_concert_id'])) 
						{
							$editconcertId = intval($_POST['edit_concert_id']);
						}
						if ($editconcertId != null) 
						{
							echo '<h1>Edit Concert ' . $editconcertId . '</h1>';
									echo '<hr>';
						}
						else
						{
							echo '<h1>Add New Concert</h1>';
									echo '<hr>';
						}
					?>
					</h2>
                    <div class="add-new-band">
                        <form id="addConcertForm" method="post" action="ProcessAdmin.php" onsubmit="return validateConcert()">
							<select name="bandSelect" required>
								<option value="" selected disabled>Select a Band</option>
								 <?php
									$result = $db->query("Select * FROM band ORDER BY band_id");
									if ($result && $result->rowCount() > 0) 
									{
										// Prepare the SQL statement
										$stmt = $db->prepare("SELECT * FROM concert WHERE concert_id = ?");
										$stmt->execute([$editconcertId]);
										$concertData = $stmt->fetch();
										
										foreach ($result as $row)
										{
											$selected = ($row['band_id'] == $concertData['band_id']) ? 'selected' : '';
											echo '<option value="'.$row['band_id'].'" '.$selected.'>',$row['band_name'].'</option>';
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
											$selected = ($row['venue_id'] == $concertData['venue_id']) ? 'selected' : '';
											echo '<option value="'.$row['venue_id'].'" '.$selected.'>',$row['venue_name'].'</option>';
										}
									}
								?>
							</select>
							
							<?php 
								if ($editconcertId == null) 
								{
									echo '<input type="datetime-local" id="concert_date" name="concert_date" required></br>';
									echo '<input type="checkbox" id="adult" name="adult"> 18+ Only</br>';
									echo '<button type="submit">Add Concert</button>';
								}
								else
								{
									echo '<input type="datetime-local" id="concert_date" name="concert_date" value="' . date('Y-m-d\TH:i', strtotime($concertData['concert_date'])) . '" required></br>';
									echo '<input type="hidden" name="concert_id" value="' . $editconcertId . '">';
									echo '<input type="checkbox" id="adult" name="adult" value="N" ' . ($concertData['adult'] == 'Y' ? 'checked' : '') . '> 18+ Only<br>';
									echo '<button type="submit">Save Concert</button>';
								}
							?>
                        </form>
                    </div>
					</center>
					
                    <div class="current-bands">
                        <center>
                        <h2>
						<?php 
							if ($editconcertId == null) 
							{
								echo 'Current Concerts';
							}
							else
							{
							}
						
						?>
						</h2>
                        </center>
                        <ul>
                            <?php
								// Handle deletion
								if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_concert_id'])) 
								{
									$concertId = intval($_POST['delete_concert_id']);

									try 
									{
										// Delete the concert record from the database
										$stmt = $db->prepare("DELETE FROM concert WHERE concert_id = ?");
										$stmt->execute([$concertId]);

										if ($stmt->rowCount() > 0) 
										{
											$confirmationMessage = "Concert deleted successfully.";
										} 
										else 
										{
											$confirmationMessage = "Failed to delete the Concert.";
										}
									} 
									catch (PDOException $e) 
									{
										$confirmationMessage = "Error: " . $e->getMessage();
									}
								}

								if ($editconcertId == null) 
								{
									// Fetch and display concert details
									$result = $db->query("
										SELECT c.concert_id, c.band_id, b.band_name, c.venue_id, v.venue_name, c.concert_date, c.adult
										FROM concert c
										JOIN band b ON c.band_id = b.band_id
										JOIN venue v ON c.venue_id = v.venue_id
										ORDER BY c.concert_id
									");
									
									if ($result && $result->rowCount() > 0) 
									{
										
										foreach ($result as $row) {  
								
											displayConcert($row);
											displayConcertButtons($row);
											
											echo '</li>';
										}
										echo '</ul>';
									} 
									else 
									{
										echo '<li><div class="label">No concerts available</div></li>';
									}
								}
							?>
                        </ul>
                    </div>
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
    <script >
	
		
        function changeLayout(layoutType) 
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
            localStorage.setItem('selectedMenu', layoutType);
        }

        document.addEventListener('DOMContentLoaded', function() 
		{
            // Retrieve the selected layout from localStorage
            var selectedMenu = localStorage.getItem('selectedMenu');
            if (selectedMenu) 
			{
                // Set the corresponding radio button as checked
                var radio = document.querySelector('input[name="menu"][value="' + selectedMenu + '"]');
                if (radio) 
				{
                    radio.checked = true;
                }

                // Display the selected layout
                changeLayout(selectedMenu);
            } 
			else 
			{
                // If no value is stored, default to showing the first layout (optional)
                changeLayout('bands'); // Set default if no previous selection exists
            }
        });
		
		function validateBand() 
		{
			var doc = document.forms["addBandForm"];

			var bandName = doc.bandName.value;
			if (bandName.length < 1) 
			{
				alert("Please add a Band Name.");
				return false;
			}

			return true;
		}
		function validateVenue() 
		{
			var doc = document.forms["addVenueForm"];

			var venueName = doc.venueName.value;
			if (!venueName) 
			{
				alert("Please add a Venue Name.");
				return false;
			}

			return true;
		}
		function validateConcert() 
		{
			var doc = document.forms["addConcertForm"];

			var bandName = doc.bandSelect.value;
			if (!bandName) 
			{
				alert("Please add a Band.");
				return false;
			}
			
			var venueName = doc.venueSelect.value;
			if (!venueName) 
			{
				alert("Please add a Venue.");
				return false;
			}
			
			var concert_date = new Date(doc.concert_date.value);
			var current_date = new Date();

			// Check if the concert date is in the past or today
			if (concert_date <= current_date) {
				alert("Please select a future date for the concert.");
				return false;
			}
			
			return true;
		}
	</script>
</body>
</html>
