<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}
	$ls_codper=$_GET["codper"];
	$ls_anocurper=$_GET["anocurper"];
	$ls_mescurper=$_GET["mescurpe"];
	$ls_sueint=$_GET["sueint"];
	$ls_sueint=strtoupper($ls_sueint);
	$ls_nomina=$_GET["nomina"];

   //--------------------------------------------------------------
   function uf_print($ls_codper, $ls_anocurper, $ls_mescurper,$ls_sueint,$ls_nomina)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	  Description: Funci�n que obtiene e imprime los resultados de la busqueda
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];
        $ls_codperi2=str_pad($ls_mescurper,2,"0",0)+2;
        
        if($ls_codperi2<10){
        	$ls_codperi2="0".$ls_codperi2;
        }
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=60>Codigo Nomina</td>";
		print "<td width=60>Codigo Concepto</td>";
		print "<td width=300>Descripcion</td>";
		print "<td width=80>Monto</td>";
		print "</tr>";
		$ls_sql=" SELECT  ".
" sno_hsalida.codnom,   ".
" sno_hsalida.codconc,  ".
" (select distinct nomcon from sno_concepto where sno_concepto.codnom=sno_hsalida.codnom  ". 
" and sno_concepto.codconc=sno_hsalida.codconc) AS nomcon,  ".
"     ".
" sum(case sno_hsalida.codconc when '0000000001' then (select SUEPER/2 from sno_personalnomina ,  ". 
" sno_nomina where sno_personalnomina.codnom=sno_nomina.codnom and  ".
" sno_personalnomina.codper=sno_hsalida.codper   ".
 " and staper='1'  ".
  " and sno_nomina.espnom='0' limit 1)  ". 
" else valsal end)  ".
 " as valsal  ,(select sueper from sno_personalnomina ,  ". 
"    sno_nomina where sno_personalnomina.codnom=sno_nomina.codnom and ". 
"    sno_personalnomina.codper=sno_hsalida.codper  ". 
 "    and staper='1' ". 
 "     and sno_nomina.espnom='0' limit 1) as sueldo ".
"     FROM sno_hsalida ,sno_concepto  ".
    
  "        WHERE   ".
    "      sno_concepto.codemp=sno_hsalida.codemp and  ". 
         " sno_concepto.codnom=sno_hsalida.codnom and  ".
         " sno_concepto.codconc=sno_hsalida.codconc and  ".
         " sno_concepto.sueintcon=1 and  ".
        " sno_hsalida.codemp='".$ls_codemp."'  AND   ".
         " sno_hsalida.codper= '".$ls_codper."'   ".
         " AND sno_hsalida.anocur ='".$ls_anocurper."'    ".
         " AND sno_hsalida.tipsal='A'  ".
         " and codperi in (select codperi from sno_periodo where sno_periodo.codemp='".$ls_codemp."' ". 
  				"	". 
  				"	 AND sno_periodo.codnom = sno_hsalida.codnom  ". 
		        "    ".
  				"	 AND substr(cast(sno_periodo.fecdesper as char(10)),6,2) >= '".str_pad($ls_mescurper,2,"0",0)."' ". 
  				"	 AND substr(cast(sno_periodo.fecdesper as char(10)),1,4) = '".$ls_anocurper."' ". 
			"	 AND substr(cast(sno_periodo.fecdesper as char(10)),6,2) <= '".$ls_codperi2."' )  ". 
         " and valsal <> 0  ".
         
" group by 1,2,sno_hsalida.codper  order by 1,2";
		
		

		
		//echo "<br>".$ls_sql;
		$rs_data=$io_sql->select($ls_sql);
		
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			$li_sueldo=0;
			$valor=0;
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codnom=$row["codnom"];
				$ls_codconc=$row["codconc"];
				$ls_nomcon= strtoupper($row["nomcon"]);
				$li_monto = number_format ($row["valsal"],2,",",".");
				if($ls_codconc=='0000000001' && $valor==0){
					$li_sueldo=$li_sueldo + $row["valsal"];	
					print "<tr class=celdas-blancas>";
					print "<td align='center'></td>";
					print "<td></td>";
					print "<td> Sueldo mensual</td>";
					print "<td align='right'>".number_format($row["sueldo"],2,",",".")."</td>";
					print "</tr>";	
					$valor=1;	
				}else{
					$li_sueldo=$li_sueldo + $row["valsal"];	
				}
						
				print "<tr class=celdas-blancas>";
				print "<td align='center'>".$ls_codnom."</td>";
				print "<td>".$ls_codconc."</td>";
				print "<td>".$ls_nomcon."</td>";
				print "<td align='right'>".$li_monto."</td>";
				print "</tr>";			
			}
			print "<tr class=celdas-blancas>";
			print "<td></td>";
			print "<td></td>";
			print "<td align='right'>".$ls_sueint."</td>";
			print "<td align='right'>".number_format($li_sueldo,2,",",".")."</td>";
			print "</tr>";	
			$io_sql->free_result($rs_data);
		}
		print "</table>";
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php print $ls_sueint." POR PERSONAL";?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style>
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" height="20" colspan="2" class="titulo-ventana"><?php print $ls_sueint?></td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="139" height="22"><div align="right">C&oacute;digo Personal</div></td>
        <td width="355"><div align="left">
          <input name="txtcodper" type="text" id="txtcodper" size="16" style="text-align:center" value="<?php print ($ls_codper);?>" readonly>        
        </div></td>
      </tr>      
  </table>
  <?php
  	 uf_print($ls_codper, $ls_anocurper, $ls_mescurper,$ls_sueint,$ls_nomina);   
  ?>
  <br>

</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>

</html>
