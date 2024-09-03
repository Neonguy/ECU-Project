<?php
	require 'db_connect.php';
  
	if (!isset($_SESSION['real_name'])) {
		header("Location: PublicSection.php");
	}
	
	// Handle booking
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['make_booking_id'])) 
	{
		$concertId = intval($_POST['make_booking_id']);
		
		try 
		{
			$existingBookings = $db->prepare("SELECT COUNT(*) as booked_concerts
                                  FROM booking 
                                  WHERE mobile_number = ?");
			$existingBookings->execute([$_SESSION['mobile']]);
			// Fetch the count of booked concerts
			$bookingsCount = $existingBookings->fetchColumn();
							
			if ($bookingsCount < $maxBookings) {
				
				// No dependencies, proceed with deletion
				$stmt = $db->prepare("INSERT INTO booking (mobile_number, concert_id) VALUES (:mobile_number, :concert_id)");
				$stmt->bindParam(':mobile_number', $_SESSION['mobile']);
				$stmt->bindParam(':concert_id', $concertId);
				$stmt->execute();
				
				if ($stmt->rowCount() > 0) {
					header('Location: AttendeeSection.php?book=success');
				} 
				else 
				{
					header('Location: AttendeeSection.php?book=failed');
				}
			}
			else
			{
				header('Location: AttendeeSection.php?book=overbooked');
			}
		}
		catch (PDOException $e) 
		{
			$confirmationMessage = "Error: " . $e->getMessage();
		}
	}
	
	// Handle deletion
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_booking_id'])) 
	{
		$bookingID = intval($_POST['cancel_booking_id']);

		try 
		{
			// No dependencies, proceed with deletion
			$stmt = $db->prepare("DELETE FROM booking WHERE booking_id = ?");
			$stmt->execute([$bookingID]);

			if ($stmt->rowCount() > 0) 
			{
				header('Location: AttendeeSection.php?delete=success');
			} 
			else 
			{
				header('Location: AttendeeSection.php?delete=failed');
			}
		}
		catch (PDOException $e) 
		{
			$confirmationMessage = "Error: " . $e->getMessage();
		}
	}
	
?>