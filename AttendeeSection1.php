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
					
                    <div class="current-bands">
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
									displayBookingButton($row);
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
					
                    <div class="current-bands">
					<?php            
						// get bookings by mobile number
						try {
							$stmt = $db->prepare("SELECT a.booking_id, a.mobile_number, a.concert_id, c.band_id, b.band_name, c.venue_id, v.venue_name, c.concert_date, c.adult
								FROM booking a
								JOIN concert c ON a.concert_id = c.concert_id
								JOIN band b ON c.band_id = b.band_id
								JOIN venue v ON c.venue_id = v.venue_id
								WHERE a.mobile_number = ?");
							$stmt->execute([$_SESSION['mobile']]);

							if ($stmt->rowCount() > 0) {
								
								echo '<ul>';
								foreach ($stmt as $row) {
									displayConcert($row);
									displayCancelButtons($row);
								}
								echo '</ul>';
								
							} else {
								echo 'no results found';
							}
						} catch (PDOException $e) {
							echo 'Error: ' . $e->getMessage();
						}
					?>
          <?php
								// Handle deletion
								if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_booking'])) 
								{
									$concertId = intval($_POST['cancel_booking']);

									try 
									{
										// Check if there are related rows in the `concert` table
										$stmt = $db->prepare("SELECT COUNT(*) FROM concert WHERE concert_id = ?");
										$stmt->execute([$concertId]);
										$count = $stmt->fetchColumn();

										if ($count > 0) 
										{
											$confirmationMessage = "Cannot delete the concert. There are dependencies on this concert.";
										} 
										else 
										{
											// No dependencies, proceed with deletion
											$stmt = $db->prepare("DELETE FROM concert WHERE concert_id = ?");
											$stmt->execute([$concertId]);

											if ($stmt->rowCount() > 0) 
											{
												$confirmationMessage = "Booking deleted successfully.";
											} 
											else 
											{
												$confirmationMessage = "Failed to delete the booking.";
											}
										}
									} 
									catch (PDOException $e) 
									{
										$confirmationMessage = "Error: " . $e->getMessage();
									}
								}
            ?>																
          <?php
								// Fetch and display bands
								$result = $db->query("SELECT * FROM concert ORDER BY concert_id");

								if ($result && $result->rowCount() > 0) 
								{
									echo '<ul>';
									foreach ($result as $row) 
									{
//										echo '<li>';
										
											// Delete button
//											echo '<form method="POST" style="display:inline; margin-left: 10px;">';
//											echo '<input type="hidden" name="delete_concert_id" value="' . $row['concert_id'] . '">';
//											echo '<button name="delete" onclick="return confirm(\'Are you sure you want to delete this booking?\')">Delete</button>';
											echo '</form>';
											
											echo '</div>';
										
//										echo '</li>';
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

                    </div>
					</center>
				</div>
            </section>
        </div>
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
