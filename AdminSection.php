
<?php
	require 'db_connect.php';
	
	$validLogin = false;
	// Prepare the SQL statement
	$stmt = $db->prepare("SELECT * FROM admin WHERE username = ?");

	// Execute the statement with the provided username
	$stmt->execute([$username]);

	// Fetch the result
	$result = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($result) {
		$validLogin = true;
	}
	
	if (!$validLogin) {
        echo '<div class="content">';
        echo '<center>';
		echo '<h1>No valid Admin logged in.</h1>';
		echo '<br/>';
		echo '<h2>What are you doing here?</h2>';
        echo '</center>';
        echo '</div>';
		
		exit;
	}
										
	$confirmationMessage = '';
	// Variable to track which band/venue is being edited
	$editBandId = null; 
	$editvenueId = null;
	$editconcertId = null;

	// Check if the form has been submitted
	if ($_POST && isset($_POST['bandName']))
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
	if ($_POST && isset($_POST['venueName'])) 
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
	if ($_POST && isset($_POST['bandSelect']) && isset($_POST['venueSelect']) && isset($_POST['concert_date'])) 
	{
		  
		// Get the venue name from the form
		$band_id = trim($_POST['bandSelect']);
		$venue_id = trim($_POST['venueSelect']);
		$concert_date = trim($_POST['concert_date']);
		$concert_id = trim($_POST['concert_id']);

		
		// Check if required values are not empty
		if (!empty($band_id) && !empty($venue_id) && !empty($concert_date)) {
			try {
				if (!empty($concert_id)) {
					// If concert_id exists, update the existing concert details
					$stmt = $db->prepare("UPDATE concert SET band_id = :band_id, venue_id = :venue_id, concert_date = :concert_date WHERE concert_id = :concert_id");
					$stmt->bindParam(':concert_id', $concert_id);
				} else {
					// If concert_id does not exist, insert a new concert
					$stmt = $db->prepare("INSERT INTO concert (band_id, venue_id, concert_date) VALUES (:band_id, :venue_id, :concert_date)");
				}

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
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to Events Listing, the Free Event Website!</h1>
        <div class="content">
            <section class="admin-area">
                <center>
                    <h2>Admin Menu</h2>
                    <ul>
                        <li>Manage Bands <input type="radio" name="menu" value="bands" onclick="changeLayout('bands')"></li>
                        <li>Manage Venues <input type="radio" name="menu" value="venues" onclick="changeLayout('venues')"></li>
                        <li>Manage Concerts <input type="radio" name="menu" value="concert" onclick="changeLayout('concert')"></li>
                        <li><a href="#">Log Out</a></li>
                    </ul>
                </center>
            </section>
            <section class="edit-area">
                <div id="bands" class="layout">
                    <div class="add-new-band">
                        <center>
                        <h2>Add New Band</h2>
                        <form id="addBandForm" method="post" action="AdminSection.php" onsubmit="return validateBand()">
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
												$confirmationMessage = "Failed to delete the band.";
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
                        <form id="addVenueForm" method="post" action="AdminSection.php" onsubmit="return validateVenue()">
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
												$confirmationMessage = "venue deleted successfully.";
											} 
											else 
											{
												$confirmationMessage = "Failed to delete the venue.";
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
												$confirmationMessage = "venue name updated successfully.";
											} 
											else 
											{
												$confirmationMessage = "Failed to update the venue name.";
											}			
										} 
										catch (PDOException $e) 
										{
											$confirmationMessage = "Error: " . $e->getMessage();
										}
									} 
									else 
									{
										$confirmationMessage = "venue name cannot be empty.";
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
							echo 'Edit Concert ' . $editconcertId;
						}
						else
						{
							echo 'Add New Concert';
						}
					?>
					</h2>
                    <div class="add-new-band">
                        <form id="addConcertForm" method="post" action="AdminSection.php" onsubmit="return validateConcert()">
							<select name="bandSelect" required>
								<option value="" disabled>Select a Band</option>
								 <?php
									$result = $db->query("Select * FROM band ORDER BY band_id");
									if ($result && $result->rowCount() > 0) 
									{
										// Prepare the SQL statement
										$concert = $db->prepare("SELECT * FROM concert WHERE concert_id = ?");
										$concert->execute([$editconcertId]);
										$concertData = $concert->fetch(PDO::FETCH_ASSOC);
										
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
									echo '<button type="submit">Add Concert</button>';
								}
								else
								{
									echo '<input type="datetime-local" id="concert_date" name="concert_date" value="' . date('Y-m-d\TH:i', strtotime($concertData['concert_date'])) . '" required></br>';
									echo '<input type="hidden" name="concert_id" value="' . $editconcertId . '">';
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
											$confirmationMessage = "Failed to delete the concert.";
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
										SELECT c.concert_id, c.band_id, b.band_name, c.venue_id, v.venue_name, c.concert_date
										FROM concert c
										JOIN band b ON c.band_id = b.band_id
										JOIN venue v ON c.venue_id = v.venue_id
										ORDER BY c.concert_id
									");
									
									if ($result && $result->rowCount() > 0) 
									{
										
										echo '<ul>';
										foreach ($result as $row) 
										{   
											// Convert the date to Australian format (DD/MM/YYYY)
											$concertDate = new DateTime($row['concert_date']);
											$formattedDate = $concertDate->format('d/m/Y');
											$formattedTime = $concertDate->format('H:i');
											
											echo '<li>';
											echo '<div class="label"><center>' . htmlspecialchars($row['band_name']) . '</center>';
											echo '<center>' . htmlspecialchars($row['venue_name']) . '</center>';
											echo '<center>' . htmlspecialchars($formattedTime) . " ". htmlspecialchars($formattedDate) . '</center></div>';
											
											echo '<div class="actions">';
											
												// Edit button
												echo '<form method="POST" style="display:inline;">';
												echo '<input type="hidden" name="edit_concert_id" value="' . $row['concert_id'] . '">';
												echo '<button name="edit">Edit</button>';
												echo '</form>';
												
												// Delete button
												echo '<form method="POST" style="display:inline;">';
												echo '<input type="hidden" name="delete_concert_id" value="' . $row['concert_id'] . '">';
												echo '<button name="delete" onclick="return confirm(\'Are you sure you want to delete this thread?\')">Delete</button>';
												echo '</form>';
											echo '</div>';
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
