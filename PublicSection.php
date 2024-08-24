<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Home Page</title>
    <link rel="stylesheet" href="styles.css">
    <style>
		input[type=text], input[type=mobile] {
			width: 50%; 
			margin: 3px; 
			box-sizing: border-box;
			text-align: center;
		}
        input[type=text], input[type=password] {
			width: 50%; 
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
					<p><i>You cannot book tickets<i></p>
					<p><i>unless you are logged in<i></p>
					<input type="text" name="mobile_number" required placeholder="Mobile Number">
                    <input type="password" name="password" placeholder="Password">
					<p><a href="#">Log in</a></P>
                    <p>Click <a href=>here</a> to register.</p>
					<a href="#">Admin Login</a>
                </center>
            </section>
            <section class="edit-area">
                <!-- need to modify right hand box size in css -->  
                <center>
                    <h2>Upcoming Concerts:</h2>
		           <!-- retrieve upcoming concerts from database -->
                </center>
            </section>
    <script>
                /*-- validation alerts --> */
	</script>
</body>
</html>
