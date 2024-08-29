<?php
	require 'db_connect.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Home Page</title>
    <link rel="stylesheet" href="styles.css">
    <style>
		input[type=text], input[type=mobile], input[type=password] {
			width: 100%; 
			margin: 3px; 
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
                <p><i>You cannot book tickets</i></p>
                <p><i>unless you are logged in</i></p>
                
<form id="login" method="post" action="user_login.php">
<input type="text" name="mobile_number" required placeholder="Mobile Number">
<input type="password" name="password" placeholder="Password">
<br/>
<button name = "login" type = "submit">Log in</button>
</form>
<p>Click <a href=>here</a> to register.</p>
<a href="admin_login.php">Admin Login</a>
                </center>  
            </section>
            <section class="edit-area">
                <center>                
                <h2>Upcoming Concerts:</h2>
                </center>
<?php            
            									// Fetch and display concert details
									$result = $db->query
                  ("
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
											// Convert the date to Australian format (DD/MM/YYYY Hours:Minutes:Seconds)
											$concertDate = new DateTime($row['concert_date']);
											$formattedDate = $concertDate->format('d/m/Y H:i:s');
											
                      echo '<li>';

											echo '<div class="label"><center>' . htmlspecialchars($row['band_name']) . '</center>';
											echo '<center>' . htmlspecialchars($row['venue_name']) . '</center>';
											echo '<center>' . htmlspecialchars($formattedDate) . '</center></div>';								
										}
                    echo '</ul>';
                    }
                    else 
							  	{
                     echo '<li><div class="label">No concerts available</div></li>';
									}    
?>
		           <!-- retrieve upcoming concerts from database -->
                </center>
            </section>   
        </div>        
    </div>       
    <script>
                /*-- validation alerts --> */
	</script>
</body>
</html>
