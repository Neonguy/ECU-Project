
<?php
	require 'db_connect.php';
		
	if (!isset($_SESSION['username'])) {
		header("Location: admin_login.php");
	}
	
	// Check if the form has been submitted
	if ($_POST && isset($_POST['bandName'])) {
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
					header("Location: AdminSection.php?addBand=success");
					exit();
				} 
				else 
				{
					// Redirect to AdminSection.php with an error message
					header("Location: AdminSection.php?addBand=error");
					exit();
				}
			} 
			catch (PDOException $e) 
			{
				// Redirect to AdminSection.php with an error message
				header("Location: AdminSection.php?addBand=error");
				exit();
			}
		} 
		else 
		{
			// Redirect to AdminSection.php with a validation message
			header("Location: AdminSection.php?addBand=empty");
			exit();
		}
	}
	// Check if the form has been submitted
	if ($_POST && isset($_POST['venueName'])) {
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
					header("Location: AdminSection.php?addVenue=success");
					exit();
				} 
				else 
				{
					// Redirect to AdminSection.php with an error message
					header("Location: AdminSection.php?addVenue=error");
					exit();
				}
			} 
			catch (PDOException $e) 
			{
				// Redirect to AdminSection.php with an error message
				header("Location: AdminSection.php?addVenue=error");
				exit();
			}
		} 
		else 
		{
			// Redirect to AdminSection.php with a validation message
			header("Location: AdminSection.php?addVenue=empty");
			exit();
		}
	}
	// Check if the form has been submitted
	if ($_POST && isset($_POST['bandSelect']) && isset($_POST['venueSelect']) && isset($_POST['concert_date'])) {
		  
		// Get the venue name from the form
		$band_id = trim($_POST['bandSelect']);
		$venue_id = trim($_POST['venueSelect']);
		$concert_date = trim($_POST['concert_date']);
		$concert_id = trim($_POST['concert_id']);
		$adult = isset($_POST['adult']) ? 'Y' : 'N';

		
		// Check if required values are not empty
		if (!empty($band_id) && !empty($venue_id) && !empty($concert_date)) {
			try {
				if (!empty($concert_id)) {
					// If concert_id exists, update the existing concert details
					$stmt = $db->prepare("UPDATE concert SET band_id = :band_id, venue_id = :venue_id, concert_date = :concert_date, adult = :adult WHERE concert_id = :concert_id");
					$stmt->bindParam(':concert_id', $concert_id);
				} else {
					// If concert_id does not exist, insert a new concert
					$stmt = $db->prepare("INSERT INTO concert (band_id, venue_id, concert_date, adult) VALUES (:band_id, :venue_id, :concert_date, :adult)");
				}

				// Bind the parameters to the SQL query
				$stmt->bindParam(':band_id', $band_id);
				$stmt->bindParam(':venue_id', $venue_id);
				$stmt->bindParam(':concert_date', $concert_date);
				$stmt->bindParam(':adult', $adult);

				// Execute the query
				if ($stmt->execute()) 
				{
					// Redirect to AdminSection.php with a success message
					header("Location: AdminSection.php?addConcert=success");
					exit();
				} 
				else 
				{
					// Redirect to AdminSection.php with an error message
					header("Location: AdminSection.php?addConcert=error");
					exit();
				}
			} 
			catch (PDOException $e) 
			{
				// Redirect to AdminSection.php with an error message
				header("Location: AdminSection.php?addConcert=error");
				exit();
			}
		} 
		else 
		{
			// Redirect to AdminSection.php with a validation message
			header("Location: AdminSection.php?addConcert=empty");
			exit();
		}
	}
?>