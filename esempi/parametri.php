<?php
  define('IBASE_DATE','ibase.dateformat');
  define('IBASE_TIME','ibase.timeformat');
  define('IBASE_TIMESTAMP','ibase.timestampformat');

  define("DB",'walter-r:D:\DatiCase\EconomatoNew\CBAECONIB.FDB');
  define("DBDATI",'walter-r:D:\DatiCase\EconomatoNew\CBADATIIB.FDB');
        
  /* Password nuove!!! */
  //define("DBPWD",'walter-r:D:\DatiCase\EconomatoNew\CBAPWDIB.FDB');
  //define("CODENTEPWD", 1);
     
  define("USERNAME",'sysdba');
  define("PWD",'masterkey');
  define("SCRITTA_FOOTER","<br>Copyright by CBA Informatica srl (2008 - 2015) - Versione 1.4.0.0 <br><br>");
  define("HASHKEY","GECO2007");
  define("SECONDI_PER_GIORNO",86400);
  define("ERRORE_PRIVILEGI_NON_SUFFICIENTI","<div id = 'no_permission' style='position: absolute;"
	       . "width : 30%; height : 30%; top : 35%;left : 35%; text-align : center; '> "
         . "<span style = 'font-family : tahoma; font-size:18px; color : Navy;' > "
				 . " Attenzione : privilegi non sufficienti per accedere alla schermata ! </span> "
         . "</div> ");
  define("CODENTE", 101);       
	
	
if (!function_exists('ibase_timefmt')):
	function ibase_timefmt($format ,$columntype = IBASE_TIMESTAMP){
		switch ($columntype) {		
			case IBASE_TIMESTAMP:
				ini_set('ibase.dateformat' ,$format);
				break;
			case IBASE_DATE:
				ini_set('ibase.dateformat' ,$format);
				break;
			case IBASE_TIME:
				ini_set('ibase.timeformat' ,$format);
				break;
			default:
				return false;
		}
	
		return true;
	}
endif;	
	
	
ibase_timefmt("%d/%m/%Y", IBASE_DATE);	
ibase_timefmt("%H.%M", IBASE_TIME);	
?>
