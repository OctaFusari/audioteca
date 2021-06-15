<?php	
if (session_id() == '') session_start();

require_once('parametri.php');  
date_default_timezone_set("Europe/Paris");

function connetti($databasename)
{	
  if (strpos($databasename,'CBAECONIB') !== false) 
	{
      $path_db= $_SESSION['DB_CBAECON'];
	}
  elseif (strpos($databasename,'CBADATIIB') !== false) 
    {	
	  $path_db= $_SESSION['DB_CBADATI'];		
	}
	else
	  {
		$path_db= $databasename;
	   }
	
	return ibase_connect($path_db, USERNAME, PWD);
	//return ibase_connect($databasename, USERNAME, PWD);
}

function descrizioneCDA ($db, $cdcliv1, $cdcliv2, $cdcliv3, $cdcliv4, $areamagaz, $vedi = true)
{
  $vett_CDA = array ('CDCLIV1' => $cdcliv1, 'CDCLIV2' => $cdcliv2, 'CDCLIV3' => $cdcliv3, 'CDCLIV4' => $cdcliv4);
    
  if ($cdcliv1 != 0) $livello = 1;
  if ($cdcliv2 != 0) $livello = 2;
  if ($cdcliv3 != 0) $livello = 3;
  if ($cdcliv4 != 0) $livello = 4;    

  $cmd = "select Descrizione from " . $areamagaz ."aCdcLi" . $livello . " where codente = " . CODENTE . " And Anno = " . $_SESSION['ANNO'];
  for ($i=1;$i<=$livello - 1;$i++)
    {
	  $cmd = $cmd . " And CodiceLivello" . $i . " = " . $vett_CDA['CDCLIV' . $i];
	}
  $cmd = $cmd . " And Codice = " . $vett_CDA['CDCLIV' . $livello];	
  $row = ibase_fetch_assoc(ibase_query($db,$cmd));
  
  if ($vedi == true)
    if ($areamagaz == "M")
	  return SostituisciAccentate($row['DESCRIZIONE']) . ' (Magazzino)';
	  else return SostituisciAccentate($row['DESCRIZIONE']) . ' (Farmacia)';  
	else return SostituisciAccentate($row['DESCRIZIONE']);

}

function descrizioneCDAPadre ($db, $cdcliv1, $cdcliv2, $cdcliv3, $cdcliv4, $areamagaz, $vedi = true)
{ 
  $vett_CDA = array ('CDCLIV1' => $cdcliv1, 'CDCLIV2' => $cdcliv2, 'CDCLIV3' => $cdcliv3, 'CDCLIV4' => $cdcliv4);
  
  if (($cdcliv1!=0)&&($cdcliv2==0)&&($cdcliv3==0)&&($cdcliv4==0))
	return "";
	
  if ($cdcliv2 != 0) $livello = 1;
  if ($cdcliv3 != 0) $livello = 2;
  if ($cdcliv4 != 0) $livello = 3;
  
  $cmd = "select Descrizione from " . $areamagaz ."aCdcLi" . $livello . " where codente = " . CODENTE . " And Anno = " . $_SESSION['ANNO'];
  for ($i=1;$i<=$livello - 1;$i++)
    {
	  $cmd = $cmd . " And CodiceLivello" . $i . " = " . $vett_CDA['CDCLIV' . $i];
	}
  $cmd = $cmd . " And Codice = " . $vett_CDA['CDCLIV' . $livello];	
  $row = ibase_fetch_assoc(ibase_query($db,$cmd));
  
  if (!is_null($row['DESCRIZIONE']))
  {
  	if ($vedi == true)
	    if ($areamagaz == "M")
		  return " [".SostituisciAccentate($row['DESCRIZIONE'])."]";
	  	else return " [".SostituisciAccentate($row['DESCRIZIONE'])."]";  
	else return " [".SostituisciAccentate($row['DESCRIZIONE'])."]";
  }
  else return "";	
}

function grigliarichiesta($db, $prog_te, $tipoop, $nuovoinser=false)
{
	
  if ($tipoop !== 'link')
  {
  if ($tipoop == 'next')
    {
	 $sql = "Select MIN(Prog_Te) as Prog_Te From RRRichieste_Te Where CodEnte =? And CodUtente =? And Prog_Te >?";
    }
    else if ($tipoop == 'prior')
      {
  	   $sql = "Select MAX(Prog_Te)  as Prog_Te From RRRichieste_Te Where CodEnte =? And CodUtente =? And Prog_Te <?";
      }
      else if ($tipoop == 'first')
        {
  	     $sql = "Select MIN(Prog_Te) as Prog_Te From RRRichieste_Te Where CodEnte =? And CodUtente =?";
        }
        else if ($tipoop == 'last')
          {
  	       $sql = "Select MAX(Prog_Te) as Prog_Te From RRRichieste_Te Where CodEnte =? And CodUtente =?";
          }

  if (($tipoop == 'last') || ($tipoop == 'first'))
    {
    $dati = ibase_query($db, $sql, CODENTE, $_SESSION['USERID']);
    }
    else $dati = ibase_query($db, $sql, CODENTE, $_SESSION['USERID'], $prog_te);
  $row = ibase_fetch_assoc($dati);
    
  if (!isset($row['PROG_TE']))
    {
      if ($tipoop == 'next')
      {
	   $sql = "Select MAX(Prog_Te) as Prog_Te From RRRichieste_Te Where CodEnte =? And CodUtente =?";	
	  }
      if ($tipoop == 'prior')
      {
	   $sql = "Select MIN(Prog_Te) as Prog_Te From RRRichieste_Te Where CodEnte =? And CodUtente =?";
	  }
     $dati = ibase_query($db, $sql, CODENTE, $_SESSION['USERID']);
     $row = ibase_fetch_assoc($dati);	  
	}
  $prog_te = $row['PROG_TE'];
  }  
  
  $sth = ibase_query($db, "Select EXTRACT(YEAR FROM Data_Creazione) as Anno, EXTRACT(MONTH FROM Data_Creazione) as Mese, EXTRACT(DAY FROM Data_Creazione) as Giorno From RRRichieste_Te Where CodEnte =? And Prog_Te =?", CODENTE, $prog_te);
  $row = ibase_fetch_assoc($sth); 
  $dataric = date ("d/m/Y", mktime(0,0,0,$row['MESE'], $row['GIORNO'], $row['ANNO']));
  /*date("m/d/Y", $row['DATA_CREAZIONE']);*/

  $datitmp = ibase_fetch_assoc(ibase_query($db, "Select NumRif From RRRichieste_Te Where Prog_Te =?", $prog_te));
  
  $sql = "
Select T.NumRif as NUMRIF, D.Prog_De, D.CodArticolo, D.QtaOrdine, D.QtaEvasa, G.*

From RRRichieste_Te T
  left Join RRRichieste_De D
  ON (T.CodEnte = D.CodEnte And
      T.Prog_Te = D.Prog_Te)
    Left Join GeCatal G ON (G.CodEnte = D.CodEnte And G.CodArticolo = D.CodArticolo)

Where T.Prog_Te = " . $prog_te . " Order By G.Descr1";

  $sth = ibase_query($db, $sql);
    
  $campi = array ('DESCR1', 'QTAORDINE', 'QTAEVASA');
  $titoli = array ('Articolo', 'Quantità', 'Q.tà Evasa');
  $chiavi = array ('prog_de');

  echo "<input type='button' value='Visualizza tutte le Richieste' onclick='javascript:AjaxApriPagina(\"visuric.php\", \"content\")'>";
  echo "<br><br>";

  echo "<TABLE border='0px'>";
  echo "<TR>";
  echo "<TD>";
  echo "<h2>Richiesta numero " . $datitmp['NUMRIF'] . " del " . $dataric . "      </h2>";
  echo "</TD>";
  echo "<TD>";
  if ($nuovoinser == false)
    {
  echo "<input type='button' value='<<' onclick='javascript:AjaxApriPagina(\"visuric.php?prog_te=" . $prog_te . "&op=first\", \"content\")'>";
  echo "<input type='button' value='<' onclick='javascript:AjaxApriPagina(\"visuric.php?prog_te=" . $prog_te . "&op=prior\", \"content\")'>";
  echo "<input type='button' value='>' onclick='javascript:AjaxApriPagina(\"visuric.php?prog_te=" . $prog_te . "&op=next\", \"content\")'>";
  echo "<input type='button' value='>>' onclick='javascript:AjaxApriPagina(\"visuric.php?prog_te=" . $prog_te . "&op=last\", \"content\")'>";  
    }
  echo "</TD>";
  echo "</TR>";
  echo "</TABLE>"; 
  echo "<br>";
	
  CreaGriglia($sth, $campi, $titoli,"RRRichieste_De",$chiavi, false, false, "", "record", "L");
}

/*da usare all'interno di tag html*/
function SostituisciAccentate($str)
{
/*$str = str_replace("'","\'",$str);*/
$str = str_replace('à','&agrave',$str);
$str = str_replace('è','&egrave',$str);
$str = str_replace('é','&eacute',$str);
$str = str_replace('ì','&igrave',$str);
$str = str_replace('ò','&ograve',$str);
$str = str_replace('ù','&ugrave',$str);
$str = str_replace('´','\'',$str);
$str = str_replace('’','\'',$str);
$str = str_replace('°','&deg',$str);
$str = str_replace('<','&lt',$str);
$str = str_replace('>','&gt',$str);

/*$stringa = <<<EOD
$str
EOD;

$str = $stringa;*/
return $str;	
}

function ProgTab($databasefilename, $tabella, $campo)
	{
		$db = connetti($databasefilename);
		$command = 'select max('.$campo.') MASSIMO from '.$tabella.' where CodEnte = 101';
		$sth = ibase_query($db, $command);
		$row = ibase_fetch_assoc($sth);
		$prog = $row['MASSIMO']+1;
		//SalvaMSG($command.' '.$prog);		
		return $prog;					
	}

function ConvertiDataInMMGGAAAA($data_da_convertire)
	{
	 
        for ($i=0; (($data_da_convertire[$i] != '/') && ($i<=strlen($data_da_convertire))); $i++) {};
        $gg = substr($data_da_convertire, 0,$i);
     
	    for ($j=$i+1; (($data_da_convertire[$j] != '/') && ($j<=strlen($data_da_convertire))); $j++) {};
        $mm = substr($data_da_convertire, $i+1,$j-$i-1);

        for ($z=$j+1; $z<=strlen($data_da_convertire); $z++) {};
        $aa = substr($data_da_convertire, $j+1,$z-$i-1);

/*		$gg = substr($data_da_convertire,0,2);
		$mm = substr($data_da_convertire,3,2);
		$aa = substr($data_da_convertire,6,4); */
        if(strlen($mm)==1) $mm="0".$mm;
        if(strlen($gg)==1) $gg="0".$gg;

        $dt= $mm."/".$gg."/".$aa;
		if (strlen($dt)==2) $dt ='';
		return $dt;
	}


function ConvertiDataInGGMMAAAA($data_da_convertire)
	{
        for ($i=0; (($data_da_convertire[$i] != '/') && ($i<=strlen($data_da_convertire))); $i++) {};
        $mm = substr($data_da_convertire, 0,$i);
     
	    for ($j=$i+1; (($data_da_convertire[$j] != '/') && ($j<=strlen($data_da_convertire))); $j++) {};
        $gg = substr($data_da_convertire, $i+1,$j-$i-1);

        for ($z=$j+1; $z<=strlen($data_da_convertire); $z++) {};
        $aa = substr($data_da_convertire, $j+1,$z-$i-1);

        if(strlen($mm)==1) $mm="0".$mm;
        if(strlen($gg)==1) $gg="0".$gg;

        $dt= $gg."/".$mm."/".$aa;
		if (strlen($dt)==2) $dt ='';
		return $dt;
	}
	
function ConvertiDataInMMGGAAAAORAMIN($data_da_convertire)
	{
	 
        for ($i=0; (($data_da_convertire[$i] != '/') && ($i<=strlen($data_da_convertire))); $i++) {};
        $gg = substr($data_da_convertire, 0,$i);
     
	    for ($j=$i+1; (($data_da_convertire[$j] != '/') && ($j<=strlen($data_da_convertire))); $j++) {};
        $mm = substr($data_da_convertire, $i+1,$j-$i-1);

        for ($z=$j+1; $z<=strlen($data_da_convertire); $z++) {};
        $aa = substr($data_da_convertire, $j+1,$z-$i-1);

        if(strlen($mm)==1) $mm="0".$mm;
        if(strlen($gg)==1) $gg="0".$gg;

        $dt= $mm."/".$gg."/".$aa;
		if (strlen($dt)==2) $dt ='';
		
		$ora= date('H.i');
		return $dt." ".$ora;
	}	
	
/*function ConvertiMinutiInHHMM($tempo)
{
$minuti = $tempo % 60;
$ore = ($tempo-($minuti)) / 60; 
if (strlen($minuti) == 1)
  return $ore.':0'.$minuti;
	else return $ore.':'.$minuti;
}	*/
	
function CreaGriglia($dati, $campidavisualizzare, $titolicampi, $tabella, $campichiave, $modifica, $elimina, $crealink, $editpage, $nomeriga="record", $permesso_schermata ="S", $stile_tabella="",$destinazione="content")
{

  echo "<table " .$stile_tabella.">";
?> 
  <tr>
  <!-- creo i titoli della griglia -->
  <?php
  foreach($titolicampi as $titolocampo)
    {
	echo "<TD class = \"titolo_griglia\"><strong>".SostituisciAccentate($titolocampo)."</strong></TD>";
	}
	if ($modifica == true)
	  echo "<TD style=\"width:50px\" class = \"titolo_griglia\"><strong>Modifica</strong></TD>";

	if (($elimina == true) && ($permesso_schermata == 'S'))
	  echo "<TD style=\"width:50px\" class = \"titolo_griglia\"><strong>Elimina</strong></TD>";
	if ($crealink == true)  
	  echo "<TD style=\"width:50px\" class = \"titolo_griglia\"><strong>Visualizza</strong></TD>";
  ?>	 
  </tr>	
  <?php     
  /* creo i record dei dati veri e propri */
	  $cont =0;
		while ($row = ibase_fetch_assoc($dati))
		{
			$cont++;
			$campochiave = '';			
			$valorichiave = '';
			$elencocampichiave = '';
			$parametrichiamata = "";
			foreach($campichiave as $campochiave)
			{
				$parametrichiamata = $parametrichiamata."&".$campochiave."=".$row[strtoupper($campochiave)];
/*  		  if ($permesso_schermata == 'L')	
	  		  $parametrichiamata .= '&readonly=true';*/
				
				$valorichiave .= $row[strtoupper($campochiave)].';';
				$elencocampichiave .= $campochiave.';';
			}	
					 
		 $link = "<a href= javascript:AjaxApriPagina('".$editpage.".php?".$parametrichiamata."','".$destinazione."')>";
		 $valorichiave = substr($valorichiave,0,strlen($valorichiave)-1);
		 $elencocampichiave = substr($elencocampichiave,0,strlen($elencocampichiave)-1); //tolgo l'ultimo ";"

			echo "<tr id=".$nomeriga.$cont." onMouseOver=\"this.bgColor='gold';\" onMouseOut=\"this.bgColor='';\">";
			/* scrivo il record con il campo da visualizzare */
			foreach($campidavisualizzare as $campo)
				CreaCella(SostituisciAccentate($row[strtoupper($campo)]) . "</a>");

			/* se c'è da da inserire metto la cella con la modifica */
		if ($modifica == true)
		  CreaCella($link . "<img src='img/modifica.gif' hspace='0' border='0' title='Modifica record corrente'/></a>","cella_griglia_centrata");
			/* se c'è da da inserire metto la cella x l'eliminazione */
			
			if (($elimina == true) && ($permesso_schermata == 'S'))
				CreaCella("<a href= javascript:eliminarecord('".$tabella."','".$elencocampichiave."','".$valorichiave."','".$nomeriga.$cont."');><img src='img/elimina.gif' hspace='0' border='0' title='Elimina registrazione corrente'/></a>","cella_griglia_centrata");
			
		if ($crealink == true)
		  CreaCella($link . "<img src='img/link.gif' hspace='0' border='0' title='Posizionati sul record corrente'/></a>","cella_griglia_centrata");
			/* se c'è da da inserire metto la cella x il link */
			
			echo '</tr>';
			}	
  echo ("</table>"); 
} 	

function CreaCella($testo,$classe="",$edit=false)
	{
		if (!$edit)
			{
			if ($classe=='')
				{
				  echo "<td>".$testo."</td>";
				}
				else echo "<td class='".$classe."'>".$testo."</td>";
			}
			else {
				if ($classe=='')
					echo '<td><input type="text" value='.$testo.'></td>';
					else echo '<td class="'.$classe.'"><input type="text" value='.$testo.'></td>';
				}
	}

function getValoreComune($databasefilename, $codistat, $campo)
	{
		$db = connetti($databasefilename);
		$command = "select ".$campo." AS VALORE from CITTA C JOIN REGIONE_PROVINCIE P ON (P.CODPROVINCIA=C.CODPROVINCIA) where c.codistat='".$codistat."'";
		$sth = ibase_query($db, $command);
		$row = ibase_fetch_assoc($sth);
		if ($row['VALORE']!='')
			return SostituisciAccentate($row['VALORE']);
			else return '';
	}


function getNomeGiorno($numero)
	{
		if ($numero==1) return 'Luned&igrave';
  		else if ($numero==2) return 'Marted&igrave';
		  else if ($numero==3) return 'Mercoled&igrave';
      else if ($numero==4) return 'Gioved&igrave';
      else if ($numero==5) return 'Venerd&igrave';
      else if ($numero==6) return 'Sabato';
      else if ($numerov=7) return 'Domenica';
	}

function getOraFormattata($ore,$minuti)
	{
		$o = $ore;
		$m = $minuti;
		if (strlen($o)<2) $o = '0'.$o;
		if (strlen($m)<2) $m = '0'.$m;

		if (strlen($o)<2) $o = '0'.$o;
		if (strlen($m)<2) $m = '0'.$m;
	return $o.':'.$m;	
	}

function Cognome_Nome_Utente($Id)
{
  $db = connetti(DB);
  $command = "select Cognome, Nome from PERSONALE where codpersonale = " . $Id;
  $sth = ibase_query($db, $command);
  $row = ibase_fetch_assoc($sth);
  return $row['NOME'] . " " . $row['COGNOME'];
  ibase_close($db);
}

function DesPrestazione($codprestazione)
{
  $dbl=connetti(DB);
  $sqlprest = 'select descrizione from prestazioni where codprestazione = '.$codprestazione;
  $datiprest = ibase_query($dbl,$sqlprest);
  $rigaprest = ibase_fetch_assoc($datiprest);
  return $rigaprest['DESCRIZIONE'];
	ibase_close($dbl);
}

/*funzioni x la gestione delle password */
function IsDefaultPassword($pwdInput)
{
return  ($pwdInput == 'consultorio');
}

function criptadecripta ($stringa_in)
{
  
  $risultato = "";
  for ($i=0; $i<=strlen($stringa_in) - 1; $i++) 
    {	
	  if ($i & 1)
	    {
		 $appo = 3;		  
		}
		else
		{
		 $appo = 4;
		}
      $risultato = $risultato . chr(255
                                    - 
	            					ord($stringa_in[$i]) - 
			    				    bcdiv(($i + 1), $appo, 0)
				    			    );
		
    }
    
  /*echo $risultato;*/
  return $risultato;    
  
}

function PasswordScaduta($datascadenza)
/*nb : il parametro deve già essere passato decriptato*/
{
$giorno=substr($datascadenza,0,2);
$mese=substr($datascadenza,3,2);
$anno=substr($datascadenza,6,4);

$str = $giorno . '/' . $mese .'/' .$anno;

echo $str;

if (is_numeric($anno))
  {
	  $scadenza = mktime(0,0,0,$mese,$giorno,$anno);
		return ($scadenza <= time());
  }		
	else return true;

}	

function NumeroCaso($db, $anno)
{
  if (session_id() == '') session_start();

  $dbh = connetti($db);
  $command = " select max(NUMEROCASO) MASSIMO from CASI where (DATAINIZIO "
			." between '01/01/".$anno."' and '12/31/".$anno."') and CODCONSULTORIO=".$_SESSION['CODCONS'];
						 
  echo "<br>".$command."<br>" ;						 
						 
	$sth = ibase_query($dbh, $command);
	$row = ibase_fetch_assoc($sth);
	$prog = $row['MASSIMO']+1;	
	return $prog;
}
/******************************************************/

function Codice_Azienda($Id)
{
  $db = connetti(DB);
  $command = "select CodAzienda from PERSONALE where codpersonale = " . $Id;
  $sth = ibase_query($db, $command);
  $row = ibase_fetch_assoc($sth);
  return $row['CODAZIENDA'];
  ibase_close($db);
}

function EsportaPDF($orientamento="landscape")
{
  $_POST = $_GET;
  
  require_once("Pdf/dompdf_config.inc.php");
  
  ob_start();
  
  include($_GET['sorg'] . '.php');
  
  $testo = ob_get_contents();
  //SalvaMSG($testo);

  ob_end_clean();

  if ( get_magic_quotes_gpc() )
    $testo = stripslashes($testo);    
  
  $old_limit = ini_set("memory_limit", "64M");  //BAN (prima era 16) poi 32
  
  $dompdf = new DOMPDF();
  $dompdf->load_html($testo);
  $dompdf->set_paper("a4", $orientamento);
  $dompdf->render();

  $dompdf->stream($_GET['dest'] . '.pdf');  
}

function Gestore_Indietro()
{
if (session_id() == '') session_start();

if (!isset($_GET['dacache']))
	{
	if (!strpos($_SERVER["REQUEST_URI"],'action=update'))
		array_push($_SESSION['STORICO'],$_SERVER["REQUEST_URI"]);
	}	
	else array_pop($_SESSION['STORICO']);
if (count($_SESSION['STORICO'])>1)
	{
	$url=$_SESSION['STORICO'][count($_SESSION['STORICO'])-2];
	if (!strpos($url,"?"))
		$chr_parametro='?';
		else $chr_parametro='&';


	echo "<input type=\"button\" value=\"<< indietro\" onclick=\"javascript:AjaxApriPagina('".$url.$chr_parametro."dacache=1','content')\"\>";
//	print_r($_SESSION['STORICO']);
	}
}

function LeggiPersonReparto ($db)
{
  if ($_SESSION['ADMINISTRATOR'] == 'T')
    $tipocampo = 'AM';
	else $tipocampo = 'UT';

  //Caso particolare che qualcuno abbia più amministratori, che gestiscono i loro flag diversi. P.S: sul programma per gli utenti fargli impostare gli stessi privilegi
  if ($_SESSION['ADMINISTRATOR'] == 'T')
    {
      return ibase_fetch_assoc(ibase_query($db, 
      "
      Select " . $tipocampo . "_PREZZI as PREZZI, " . $tipocampo . "_TOTALI as TOTALI, " . $tipocampo . "_RIEP as RIEP, " . $tipocampo . "_GIACENZA as GIACENZA, " . $tipocampo . "_DATA as DATA
      From EcPassword_Person Pers
      Join EcPassword Pwd
      On (Pwd.Administrator = 'T' And
      Pwd.Richieste_Reparto = 'T' And
      Pwd.CodUtente = Pers.CodUtente)
      Join EcPassword_Reparti Rep
      On (Rep.CodUtente = Pwd.CodUtente And
        Rep.AreaMagaz =? And
        Rep.Anno =? And
        Rep.CdcLiv1 =? And
        Rep.CdcLiv2 =? And
        Rep.CdcLiv3 =? And
        Rep.CdcLiv4 =?) where Pers.CodUtente =?"
      , $_SESSION['AREAMAGAZ'], $_SESSION['ANNO'], $_SESSION['DEST_CDCLIV1'], $_SESSION['DEST_CDCLIV2'], $_SESSION['DEST_CDCLIV3'], $_SESSION['DEST_CDCLIV4'], $_SESSION['USERID']));
    }
    else 
    {
      return ibase_fetch_assoc(ibase_query($db, 
      "
      Select " . $tipocampo . "_PREZZI as PREZZI, " . $tipocampo . "_TOTALI as TOTALI, " . $tipocampo . "_RIEP as RIEP, " . $tipocampo . "_GIACENZA as GIACENZA, " . $tipocampo . "_DATA as DATA
      From EcPassword_Person Pers
      Join EcPassword Pwd
      On (Pwd.Administrator = 'T' And
      Pwd.Richieste_Reparto = 'T' And
      Pwd.CodUtente = Pers.CodUtente)
      Join EcPassword_Reparti Rep
      On (Rep.CodUtente = Pwd.CodUtente And
        Rep.AreaMagaz =? And
        Rep.Anno =? And
        Rep.CdcLiv1 =? And
        Rep.CdcLiv2 =? And
        Rep.CdcLiv3 =? And
        Rep.CdcLiv4 =?)"
      , $_SESSION['AREAMAGAZ'], $_SESSION['ANNO'], $_SESSION['DEST_CDCLIV1'], $_SESSION['DEST_CDCLIV2'], $_SESSION['DEST_CDCLIV3'], $_SESSION['DEST_CDCLIV4']));
    }

}

function FormattaNumero ($numero, $decimali)
{

  if ($numero == '')
    $numero = '0';
  
  $numero = str_replace('.', ',', $numero);
  
  $esistevirgola = strpos($numero, ',');
  if ($esistevirgola == false)
    {
	 if ($decimali > 0)
	   {
    	 $numero = $numero . ",";
    	 for ($i=1;$i<=$decimali;$i++)
		   $numero = $numero . "0";  
		 return $numero; 
	   } 
	}
	else {
	  for ($i=1;$i<=$decimali;$i++)
	  {
	    if (!isset($numero[$i]) + $esistevirgola)
		  $numero = $numero . '0';		  
	  }
	  return substr($numero, 0, $esistevirgola+1+$decimali);
	
	}
	
}

function FormattaNumeroBis ($numero, $decimali)
{

  if ($numero == '')
    $numero = '0';

  if ($numero == '0')
    return '';

  $numero = str_replace('.', ',', $numero);

  $esistevirgola = strpos($numero, ',');
  if ($esistevirgola == false)
    {
	 if ($decimali > 0)
	   {
    	 $numero = $numero . ",";
    	 for ($i=1;$i<=$decimali;$i++)
		   $numero = $numero . "0";
		 return $numero;
	   }
	}
	else {
	  for ($i=1;$i<=$decimali;$i++)
	  {
	    if (!isset($numero[$i]) + $esistevirgola)
		  $numero = $numero . '0';
	  }
	  return substr($numero, 0, $esistevirgola+1+$decimali);

	}

}

function DaVirgolaAPunto ( $strnumero )
{

  $strnumero = str_replace(',', '.', $strnumero);
  return $strnumero;
  
}

function MettiZeri($valore)
{
  $appo = $valore;
		
  if (strlen($appo) == 1) $appo = '0' . $appo;
  if (strlen($appo) == 1) $appo = '0' . $appo;
  return $appo;	
  
}

function SalvaMSG($msg)
{
	$var=fopen("errore.txt","a+");
	fwrite($var,$msg.chr(13).chr(10));
	fclose($var);
}

function query($db,$cmd) {
	return ibase_query($db,$cmd);
}

function leggi_riga($qry) {
	return ibase_fetch_assoc($qry);
}

function Inserisci_record_in_tabella($db,$tabella,$campi,$valori,$tipi) {
	// Procedura standard che mi genera la query per il salvataggio
	$app = 'INSERT INTO '.$tabella.' (';
	foreach($campi as $campo)
	$app .= $campo.',';
	$sql = substr($app,0,strlen($app)-1).') VALUES (';
	
	$app='';
	for($i=0;$i<count($valori);$i++)
	{
		if (trim($valori[$i])=='') $app .= 'null,';
		else $app .= FormattaPerSalvataggio($tipi[$i],$valori[$i],$tabella,$campi[$i]);
	}
	
	$sql = $sql.substr($app,0,strlen($app)-1).')';
	//SalvaMSG($sql);

	$sth = query($db, $sql);	
}

function FormattaPerSalvataggio($tipo,$valore,$tabella,$campo,$separatore=",") {
	// formatta un valore in base al tipo del campo
	if ($tipo=='D')	return "'".ConvertiDataInMMGGAAAA($valore)."'$separatore";
	if ($tipo=='I') return $valore.$separatore;
	if ($tipo=='F') {
		if ((($tabella=='PAVOCVAR')&&($campo=='IMPORTO_BASE')) || (($tabella=='PAVOCMAN')&&($campo=='IMPBASE')))
		$decimali=5;
		else $decimali=2;
		return number_format(AdattaImporto($valore), $decimali, '.','').$separatore;
	}
	if ($tipo=='S') return "'".AdattaStringaPerSalvataggio($valore)."'$separatore";

}

function AdattaStringaPerSalvataggio($stringa) {
	$str = $stringa;
	$str = str_replace("'","`",$str);
	$str = str_replace('"','`',$str);

	$str = str_replace('&','',$str);
	$str = str_replace('?','',$str);
	$str = str_replace('$','',$str);
	//$str = str_replace('@','',$str); BAN

	$str = str_replace('à','a',$str);
	$str = str_replace('è','e',$str);
	$str = str_replace('é','e',$str);
	$str = str_replace('ì','i',$str);
	$str = str_replace('ò','o',$str);
	$str = str_replace('ù','u',$str);

	$str = str_replace('°','',$str);
	$str = str_replace('^','',$str);


	return strtoupper($str);
}

function CreaTabelle($db) {
	$sql= "select * from RRRICSTD_DE";
	$qry = @query($db,$sql);
	
	if ($qry==false)
	{
		$sql  = "CREATE TABLE RRRICSTD_DE (";
		$sql .= "CODENTE      INTEGER,";
		$sql .= "PROG_TE      INTEGER,";
    	$sql .= "PROG_DE      INTEGER,";
    	$sql .= "CODARTICOLO  INTEGER)";
		$qry = query($db,$sql);
		
		$sql  = "CREATE INDEX RRRICSTD_DE_IDXPROG_DE ON RRRICSTD_DE (CODENTE, PROG_TE, PROG_DE)";
		$qry = query($db,$sql);		
				
		$sql  = "CREATE TABLE RRRICSTD_TE (";
		$sql .= "CODENTE      	   INTEGER,";
		$sql .= "PROG_TE      	   INTEGER,";
    	$sql .= "DEST_CDCLIV1      INTEGER,";
    	$sql .= "DEST_CDCLIV2      INTEGER,";
    	$sql .= "DEST_CDCLIV3      INTEGER,";
    	$sql .= "DEST_CDCLIV4      INTEGER,";
    	$sql .= "DEST_AREAMAGAZ    VARCHAR(1),";    	    	    	    	
    	$sql .= "DEST_ANNO    	   INTEGER,";    	
    	$sql .= "DESCRIZIONE  	   VARCHAR(100) CHARACTER SET NONE)";
		$qry = query($db,$sql);
		
		$sql  = "CREATE INDEX RRRICSTD_TE_IDXPROG_TE ON RRRICSTD_TE (CODENTE, PROG_TE);";
		$qry = query($db,$sql);		
	}
	
}

function ApriFileSalvato($nomefile,$ext,$salva=false,$nomefile_salva='') {
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // questo per evitare che mi venga cacheato la pagina...

	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);

	if ($salva)
	header("Content-Disposition: attachment; filename=\"".$nomefile_salva."\";" );

	header("Content-Transfer-Encoding: binary");
	header("Content-type: application/".$ext);

	$var=fopen($nomefile,"r");
	$file= fread($var,filesize($nomefile));
	fclose($var);

	readfile($nomefile);
}

//Funzione che mi restituisce la data + l' ora
function formattaDataOra($data) {
	//la data non deve essere convertita con il cast
	$date = new DateTime($data);
	return $date->format('d/m/Y H:i');
}

//Funzione che mi restituisce la data
function formattaData($data) {
	//la data non deve essere convertita con il cast
	$date = new DateTime($data);
	return $date->format('d/m/Y');
}
?>