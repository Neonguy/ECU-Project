<?php
  // This file will be included at the start of all other files in the site
  // It includes code to connect to the database server, but could be expanded
  // to include other things that are needed across multiple files in the site!
	$username = '';
	session_start();
  // Connect to database server
  try
  { 
    $db = new PDO('mysql:host=localhost;port=6033;dbname=csg2431: interactive web development', 'root', '');
  }
  catch (PDOException $e) 
  {
    echo 'Error connecting to database server:<br />';
    echo $e->getMessage();
    exit;
  } 
  
	function displayConcert($concert) {
		if ($concert) {
			
			// Convert the date to Australian format (DD/MM/YYYY Hours:Minutes:Seconds)
			$concertDate = new DateTime($concert['concert_date']);
			$current_date = new DateTime();

			// Check if the concert date is in the past or today
			if ($concertDate >= $current_date) {
				$formattedDate = $concertDate->format('d/m/Y H:i:s');
				
				echo '<li>';

				echo '<div class="label"><center>' . htmlspecialchars($concert['band_name']) . '</center>';
				echo '<center>' . htmlspecialchars($concert['venue_name']) . '</center>';
				echo '<center>' . htmlspecialchars($formattedDate) . '</center>';
				
				if ($concert['adult'] == 'Y') {
					echo '<center><span style="color: red;">Adults Only</span></center>';
				}
				echo '</div>';
			}
		} 
		else 
		{
			echo '<p>No concert details available.</p>';
		}
	}
	function displayConcertButtons($concert) {
		if ($concert) {
			
			// Convert the date to Australian format (DD/MM/YYYY Hours:Minutes:Seconds)
			$concertDate = new DateTime($concert['concert_date']);
			$current_date = new DateTime();

			// Check if the concert date is in the past or today
			if ($concertDate >= $current_date) {
				
				
				echo '<div class="actions">';
				
					// Edit button
					echo '<form method="POST" style="display:inline;">';
					echo '<input type="hidden" name="edit_concert_id" value="' . $concert['concert_id'] . '">';
					echo '<button name="edit">Edit</button>';
					echo '</form>';
					
					// Delete button
					echo '<form method="POST" style="display:inline;">';
					echo '<input type="hidden" name="delete_concert_id" value="' . $concert['concert_id'] . '">';
					echo '<button name="delete" onclick="return confirm(\'Are you sure you want to delete this thread?\')">Delete</button>';
					echo '</form>';
				echo '</div>';
				
			}
		} 
	}
?>