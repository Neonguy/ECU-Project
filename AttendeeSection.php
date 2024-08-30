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
					</center>
				</div>
					
                <div id="bookings" class="layout">
					<center>
					<h3>Your Bookings</h3>
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

							if ($stmt->rowCount() > 0) 
              {
                echo '<ul>';
								foreach ($stmt as $row) 
                {
                  displayConcert($row);
                }
                echo '</ul>';
              } 
              else 
              {
								echo 'no results found';
							}
						} catch (PDOException $e) {
							echo 'Error: ' . $e->getMessage();
						}
					?>
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
