<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!--Bootstrap-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<title>
ARTISTI
</title>
</head>
	<body>
		<?php
		include 'db.php';
		include 'aggiungi.php';
		?>
		<div class="navbar">
			<nav class="navbar navbar-expand-lg ">
			    <a class="navbar-brand" href="#">ARTISTI</a>
			    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			    	<span class="navbar-toggler-icon"></span>
			    </button>
			 <div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav">
                <li class="nav-item col-sm active">
						<a class="opzioni" href="app.php">HOME <span class="sr-only">(current)</span></a>
					</li>
					<li class="nav-item col-sm">
						<a class="opzioni" href="musica.php">MUSICA</a>
					</li>
					<li class="nav-item col-sm">
						<a class="opzioni" href="album.php">ALBUM</a>
					</li>
					<li class="nav-item col-sm">
						<a class="opzioni" href="artisti.php">ARTISTI</a>
					</li>
					<li class="nav-item col-sm">
						<a class="opzioni" href="genere.php">GENERE</a>
                    </li>
                    <li class="nav-item col-sm">
						<a class="opzioni" href="index.php">LOGOUT</a>
					</li>
				</ul>
			</div>
			</nav>
		</div>
		<div class="visualizza">
            <h3 class="opzioni">I TUOI ARTISTI</h3>
			<script>

				$(document).ready(function(){
					$(".tabaggiungi").hide();
				
				});
				$(document).ready(function(){
					$(".show").click(function(){
						$(".tabaggiungi").show();
					});
				});
				function aggiungicazzo(){
						$(document).ready(function() {
						$("#formperphp").submit(function(e) {
							e.preventDefault();
							$.ajax( {
								url: "aggiungi.php",
								method: "post",
								data: $("form").serialize(),
								dataType: "text",
								success: function(strMessage) {
									$("#formperphp")[0].reset();
								}
							});
						});
				});
				}

			</script>

			<button class="show">AGGIUNGI</button>
			<form action="" id="formperphp" method="post">
				<table class="tabaggiungi">						
					<tr>
						<td>NOME <input type = "text" name = "genNomeArtista" class="inGen"></td> 
						<td>COGNOME <input type = "number" name = "genCognomeTArtista" class="inGen"></td> 
						<td>NOME D'ARTE<input type = "number" name = "genNAArtista" class="inGen"></td> 
					<tr>
					</tr>
						<td>GENERE<input type = "date" name = "genGenereArtista" class="inGen"></td> 
						<td>STELLE<input type = "date" name = "genStelleArtista" class="inGen"></td> 
						<td><input class="aggiungi" type = "submit" value="AGGIUNGI"></td>
					</tr>
				</table>
			</form>
			<div class="stampaa">
				<?php stampArtista(); ?>
			</div>
        </div>
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	</body>