<?php 
	require_once("parametri.php");
  	require_once("stdmodric.php");  	
  	
  	$db=connetti(DB);
?>

<?php
	echo "<table>";
	echo "<tr>";
	echo "<td class='titolo_griglia' style='width:90%;text-align:center'>Articolo</td>";
	echo "<td class='titolo_griglia' style='width:10%;text-align:center'>Elimina</td>";	
	echo "</tr>";
  	 
	
  	$sql  = " select * from rrricstd_de a";
  	$sql .= " join gecatal b on (b.codente=a.codente and b.codarticolo=a.codarticolo)"; 
  	$sql .= " where a.codente=".CODENTE." and b.areamagaz='".$_GET['tipo']."' and a.prog_te=".$_GET['codice'];  	
  	$sql .= " order by b.descr1";
  	
  	$qry= query($db, $sql);
  	$cont=0;
  	while ($row = ibase_fetch_assoc($qry))
  	{
  		$cont++;
  		
  		$tabella= 'RRRICSTD_DE';
  		$elencocampichiave= 'CODENTE;PROG_TE;PROG_DE';
  		$valorichiave= CODENTE.';'.$row['PROG_TE'].';'.$row['PROG_DE'];
  		$nomeriga= 'rigadet';
  		
  		echo "<TR id='".$nomeriga.$cont."' onMouseOver=\"this.bgColor='gold';\" onMouseOut=\"this.bgColor='';\">";

		CreaCella($row['DESCR1']);
		CreaCella("<a href= javascript:eliminarecord('".$tabella."','".$elencocampichiave."','".$valorichiave."','".$nomeriga.$cont."');><img src='img/elimina.gif' hspace='0' border='0' title='Elimina registrazione corrente'/></a>","cella_griglia_centrata");		
		echo "</tr>";
  	}	

  	echo "</table>";
?>