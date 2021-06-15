
<?php
		
		/* parametri di connessione al database */
		
		$DB = 'localhost:C:\CBA\stage\db\musica.fdb';
		$nomeserver = 'AUDIOTECA';
		$host = 'localhost';	
        $database='audioteca';			
		$password = '';
		$user = 'root';
		
		/* funzione per connessione a db */
		function connetti(){
			
			$DB = 'localhost:C:\CBA\stage\db\musica.fdb';
			$nomeserver = 'AUDIOTECA';
			$host = 'localhost';	
			$database='audioteca';			
			$password = '';
			$user = 'root';
			$connessione = new mysqli($host, $user, $password,$database) or die("Conesiione fallita". $connessione -> error);
			return $connessione;
			
		}
		/* funzione per disconnesione a db */
		function chiudi(){

			connetti() -> close();

		}
			
		connetti();
		/* funzione per login al db */
		function login(){
			if($_SERVER["REQUEST_METHOD"] == "POST") {
				$password = mysqli_real_escape_string(connetti(),$_POST['usern']);
				$username = mysqli_real_escape_string(connetti(),$_POST['pass']); 
				$sql = "SELECT ID FROM LOGIN WHERE NOME = '$password' and PASSWORD = '$username'";
				$result = mysqli_query(connetti(),$sql);
				$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
				$active = $row['active'];
				$count = mysqli_num_rows($result);
				if($count == 1) {
					header("location: app.php");
				}
			}
		}

		/* funzione per stampare l'utente */
		function benUte(){
			$query = "SELECT NOME, COGNOME FROM LOGIN WHERE ID = 1";
			$result = mysqli_query(connetti(),$query);
			$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
			$count = mysqli_num_rows($result);
			$NOME = $row["NOME"];
			$COGNOME = $row["COGNOME"];
			echo '<a class="nomeutente">'.$NOME.' '.$COGNOME.'</a>';

		}
		

		/* funzione per visualizzare l'album */
		function stampAlbum(){

			$query = "SELECT A.ID, A.NOME, A.ID_STELLE, U.NOMEARTE, A.DATAUSCITA
			FROM ALBUM A
			JOIN AUTORI U ON(A.ID_AUTORE = U.ID)";
			if ($result = connetti()->query($query)) {
				echo '
				<table>						
					<tr>
						<td class="ind">NOME</td>
						<td class="ind">AUTORE</td> 
						<td class="ind">DATA USCITA </td> 
						<td class="ind">STELLE</td>
					</tr>';
				while ($row = $result->fetch_assoc()) {
					
					$ID = $row["ID"];
					$NOME = $row["NOME"];
					$ID_STELLE = $row["ID_STELLE"];
					$ID_AUTORE = $row["NOMEARTE"];
					$DATAUSCITA = $row["DATAUSCITA"]; 
			 
					echo 
					'<tr> 
					  	<td>'.$NOME.'</td>
					  	<td>'.$ID_AUTORE.'</td> 
						<td>'.$DATAUSCITA.'</td>
						<td>'.$ID_STELLE.'</td> 
					</tr>';
							  
				}
				
			}
			
		} 
		/* funzione per visualizzare la musica */
		function stampMusica(){

			$query = "SELECT B.NOME , A.NOMEARTE , B.STELLE, G.GENERENOME, B.DURATA, B.ANNO 
					  FROM BRANO B
					  JOIN AUTORI A ON(B.ID_AUTORE = A.ID)
					  JOIN GENERE G ON(B.ID_GENERE = G.ID)
					 ";
			if ($result = connetti()->query($query)) {
				echo '
				<table>						
					<tr >
						<td class="ind">NOME</td> 
						<td class="ind">ARTISTA</td> 
						<td class="ind">STELLE</td> 
						<td class="ind">GENERE</td> 
						<td class="ind">DURATA</td> 
					</tr>';
				while ($row = $result->fetch_assoc()) {
					$NOME = $row["NOME"];
					$NOMEA = $row["NOMEARTE"];
					$STELLE = $row["STELLE"];
					$GENERENOME = $row["GENERENOME"]; 
					$DURATA = $row["DURATA"];
					echo 
					'<tr> 
					  	<td>'.$NOME.'</td> 
					  	<td>'.$NOMEA.'</td> 
					  	<td>'.$STELLE.'</td> 
					  	<td>'.$GENERENOME.'</td> 
						<td>'.$DURATA.'</td>
					</tr>';
							  
				}
				
			}
			
		}
		/* funzione per visualizzare gli artisti */
		function stampArtista(){
			$CONT = 0;
			$query = "SELECT A.NOMEA, A.COGNOME, A.NOMEARTE, G.GENERENOME, A.STELLE 
					  FROM AUTORI A
					  JOIN GENERE G ON(A.ID_GENERE = G.ID)
					 ";
			if ($result = connetti()->query($query)) {
				echo '
				<table>						
					<tr >
						<td class="ind">NOME</td> 
						<td class="ind">COGNOME</td> 
						<td class="ind">NOMEA</td> 
						<td class="ind">GENERENOME</td> 
						<td class="ind">STELLE</td> 
					</tr>';
				while ($row = $result->fetch_assoc()) {
					$NOME = $row["NOMEA"];
					$COGNOME = $row["COGNOME"];
					$NOMEA = $row["NOMEA"];
					$GENERENOME = $row["GENERENOME"]; 
					$STELLE = $row["STELLE"];
					$CONT++;

					echo 
					'<tr> 
					  	<td>'.$NOME.'</td> 
					  	<td>'.$COGNOME.'</td> 
					  	<td>'.$NOMEA.'</td> 
					  	<td>'.$GENERENOME.'</td> 
						<td>'.$STELLE.'</td> 
						<td><input class="aggiungi" type="submit" id="button'.$CONT.'" name="button1" value="DELETE"></td>
					</tr>';
						  
				}
				
			}

		}

		/* funzione per visualizzare gli artisti */
		function stamGenere(){
			$query = "SELECT GENERENOME FROM GENERE";
			if ($result = connetti()->query($query)) {

				while ($row = $result->fetch_assoc()) {
					$GENERENOME = $row["GENERENOME"];
					echo 
					'
					<table class="tabellagen">
						<tr> 
							<td>'.$GENERENOME.'</td>
						</tr>';
								
				}
				
			}

		}
		


		function update(){
			

			
		}

		function elimina(){
			
			
			
		}

		function deleteArtisti(){
			if($_SERVER["REQUEST_METHOD"] == "POST") {
				$ALBUMDEL= mysqli_real_escape_string(connetti(),$_POST['button1']);
				$query = "DELETE FROM autori WHERE ID='$ALBUMDEL'";
				$result = mysqli_query(connetti(),$query);
			}

		}
		deleteArtisti();
		

?>
