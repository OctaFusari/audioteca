<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!--Bootstrap-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css">
<title>
	MUSICA LOGIN
</title>
</head>

	<body>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" integrity="sha256-qM7QTJSlvtPSxVRjVWNM2OfTAz/3k5ovHOKmKXuYMO4=" crossorigin="anonymous"></script>
		<?php

		include 'db.php';


		login();

		?>
		<div class="login">
			<h1>LOGIN TO MUSICA</h1>
			<form action = "" method = "post">
				<div class="campi">
					<div class="campo">
						<span>NOME</span>
						<input type = "text" name = "usern">
					</div>
					<div class="campo">
						<span>PASSWORD</span>
						<input type = "password" name = "pass">
					</div>
				</div>
				<input class="submit" type = "submit" value="SUBMIT">
			</form>
		</div>
		

	</body>

</html>