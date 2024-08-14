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
                            <li><div class="label">Big Beats</div><div class="actions"><button>Edit</button><button>Delete</button></div></li>
                            <li><div class="label">Kelly Roth</div><div class="actions"><button>Edit</button><button>Delete</button></div></li>
                            <li><div class="label">The Boggletops</div><div class="actions"><button>Edit</button><button>Delete</button></div></li>
                            <li><div class="label">The Ladder Coins</div><div class="actions"><button>Edit</button><button>Delete</button></div></li>
                        </ul>
                    </div>
                    <div class="add-new-band">
                        <center>
                        <h2>Add New Band</h2>
                        <form id="addBandForm" method="post" action="register.php" onsubmit="return validateBand()">
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
                            <li><div class="label">Madison Square Garden</div><div class="actions"><button>Edit</button><button>Delete</button></div></li>
                            <li><div class="label">Royal Albert Hall</div><div class="actions"><button>Edit</button><button>Delete</button></div></li>
                            <li><div class="label">Sydney Opera House</div><div class="actions"><button>Edit</button><button>Delete</button></div></li>
                        </ul>
                    </div>
                    <div class="add-new-band">
                        <center>
                        <h2>Add New Venue</h2>
                        <form id="addVenueForm" method="post" action="register.php" onsubmit="return validateVenue()">
                            <input type="text" id="venueName" name="venueName" placeholder="Venue Name">
                            <button type="submit">Add Venue</button>
                        </form>
                        </center>
                    </div>
                </div>
                <div id="concert" class="layout">
                    <center><h2>Add New Concert</h2>
                    <div class="add-new-band">
                        <form id="addConcertForm" method="post" action="register.php" onsubmit="return validateConcert()">
                            <input type="text" id="bandName" name="bandName" placeholder="Band Name">
                            <input type="text" id="venueName" name="venueName" placeholder="Venue Name">
                            <input type="date" id="concertDate" name="concertDate" required><br><br>
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

			var bandName = doc.bandName.value;
			if (!bandName) {
				alert("Please add a Band Name.");
				return false;
			}
			
			var venueName = doc.venueName.value;
			if (!venueName) {
				alert("Please add a Venue Name.");
				return false;
			}


			return true;
		}
	</script>
</body>
</html>
