<?php 
    
    function aggiungiAlbum(){
    
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $genNomeA = mysqli_real_escape_string(connetti(),$_POST['genNome']);
            $genStelleA = mysqli_real_escape_string(connetti(),$_POST['genStelle']);
            $genAutoreA = mysqli_real_escape_string(connetti(),$_POST['genAutore']);
            $genDatauscitaA = mysqli_real_escape_string(connetti(),$_POST['genDatauscita']); 
            $query = "INSERT INTO ALBUM (NOME, ID_STELLE, ID_AUTORE, DATAUSCITA) VALUES('$genNomeA', '$genStelleA', '$genAutoreA', '$genDatauscitaA')";
            $result = mysqli_query(connetti(),$query);
        }
        connetti()->close();
    }

    function aggiungiArtista(){
    
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $genNomeArtista = mysqli_real_escape_string(connetti(),$_POST['genNomeArtista']);
            $genCognomeTArtista = mysqli_real_escape_string(connetti(),$_POST['genCognomeTArtista']);
            $genNAArtista = mysqli_real_escape_string(connetti(),$_POST['genNAArtista']);
            $genGenereArtista = mysqli_real_escape_string(connetti(),$_POST['genGenereArtista']);
            $genStelleArtista = mysqli_real_escape_string(connetti(),$_POST['genStelleArtista']); 
            $query = "INSERT INTO AUTORI (NOMEA, COGNOME, NOMEARTE, ID_GENERE, STELLE) VALUES('$genNomeArtista', '$gengenCognomeTArtistaStelle', '$genNAArtista', '$genGenereArtista','$genStelleArtista')";
            $result = mysqli_query(connetti(),$query);
        }
        connetti()->close();
    }
    /*function aggiungiMusica(){
    
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $genNome = mysqli_real_escape_string(connetti(),$_POST['genNome']);
            $genStelle = mysqli_real_escape_string(connetti(),$_POST['genStelle']);
            $genAutore = mysqli_real_escape_string(connetti(),$_POST['genAutore']);
            $genDatauscita = mysqli_real_escape_string(connetti(),$_POST['genDatauscita']); 
            $query = "INSERT INTO ALBUM (NOME, ID_STELLE, ID_AUTORE, DATAUSCITA) VALUES('$genNome', '$genStelle', '$genAutore', '$genDatauscita')";
            $result = mysqli_query(connetti(),$query);
        }
        connetti()->close();
    }*/

    aggiungiAlbum();

    aggiungiArtista();
?>