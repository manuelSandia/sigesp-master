<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}

   //--------------------------------------------------------------
   function uf_print($as_tipo,$as_codnom,$as_codnomhas)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function : uf_print
		//		   Access : public 
		//	    Arguments : as_tipo  // Tipo de Llamada del catï¿½logo
		//	    			as_codnom  // Cï¿½digo de Nï¿½mina
		//	    			as_codnomhas  // Cï¿½digo de Nï¿½mina Hasta
		//	  Description : Funciï¿½n que obtiene e imprime los resultados de la busqueda
		//	   Creado Por : Ing. Yesenia Moreno
		// Fecha Creaciï¿½n : 10/04/2006 								Fecha ï¿½ltima Modificaciï¿½n : 
		//////////////////////////////////////////////////////////////////////////////
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
        $fff=$_SESSION["la_empresa"]["periodo"];
		$ango=substr($fff,0,4);
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=100>Año</td>";
		print "<td colspan=2>Mes</td>";
		print "</tr>";
		$ls_sql="SELECT DISTINCT sno_hperiodo.anocur, substr(cast(sno_hperiodo.fechasper as char(10)),6,2) as mes ".
				"  FROM sno_hperiodo, sno_hnomina, sno_periodo ".
				" WHERE  ".
				"    sno_periodo.cerper= 1 ".
				"	AND sno_hperiodo.codemp=sno_hnomina.codemp ".
				"   AND sno_hperiodo.codnom=sno_hnomina.codnom ".
				"   AND sno_hperiodo.codemp=sno_periodo.codemp ".
				"   AND sno_hperiodo.codnom=sno_periodo.codnom ".
				"   AND sno_hperiodo.codperi=sno_periodo.codperi   AND SNO_HPERIODO.ANOCUR='".$ango."' ";
		
		
		
			$ls_sql=$ls_sql."	AND (substr(cast(sno_hperiodo.fecdesper as char(10)),6,2) NOT IN (SELECT LPAD(cast(mescurper as char(2)),2,'0') ".
							"											     FROM sno_fideiperiodo ".
							"									 		    WHERE sno_hperiodo.codemp=sno_fideiperiodo.codemp ".
							"   								 		  	  AND sno_hperiodo.codnom=sno_fideiperiodo.codnom ".
							"   								 		   	  AND sno_hperiodo.anocur=sno_fideiperiodo.anocurper)) ".
							"   AND sno_hperiodo.codemp='".$ls_codemp."' ";
		
			$ls_sql=$ls_sql." GROUP BY sno_hperiodo.anocur, sno_hperiodo.fechasper ".
							" ORDER BY sno_hperiodo.anocur ";
		
	//	echo "<br>ddd".$ls_sql;
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_anocur=$row["anocur"];
				$ls_mes=str_pad($row["mes"],2,"0",0);
				
				switch($ls_mes)
				{
					case "01":
						$ls_mes="";
						$ls_mesh="";
						$ls_desmes="";
						break;
					case "02":
						$ls_mes="";
						$ls_mesh="";
						$ls_desmes="";
						break;
					case "04":
						$ls_mes="";
						$ls_mesh="";
						$ls_desmes="";
						break;
					case "05":
						$ls_mes="";
						$ls_mesh="";
						$ls_desmes="";
						break;
					case "07":
						$ls_mes="";
						$ls_mesh="";
						$ls_desmes="";
						break;
					case "08":
						$ls_mes="";
						$ls_mesh="";
						$ls_desmes="";
						break;
					case "10":
						$ls_mes="";
						$ls_mesh="";
						$ls_desmes="";
						break;
					case "11":
						$ls_mes="";
						$ls_mesh="";
						$ls_desmes="";
						break;							
						
					case "03":
						$ls_mes="01";
						$ls_mesh="03";
						$ls_desmes="ENERO";
						$ls_desmesH="MARZO";
						break;

					
					case "06":
						$ls_mes="04";
						$ls_mesh="06";						
						$ls_desmes="ABRIL";
						$ls_desmesH="JUNIO";
						break;

					case "09":
						$ls_mes="07";
						$ls_mesh="09";						
						$ls_desmes="JULIO";
						$ls_desmesH="SEPTIEMBRE";
						break;

					case "12":
						$ls_mes="10";
						$ls_mesh="12";						
						$ls_desmes="OCTUBRE";
						$ls_desmesH="DICIEMBRE";
						break;

				}
				switch ($as_tipo)
				{
					case "": // sigesp_snorh_p_fideicomiso
						
						if($ls_mes!=""){
						print "<tr class=celdas-blancas>";	
						print "<td><a href=\"javascript: aceptar('$ls_anocur','$ls_mes','$ls_mesh','$ls_desmes','$ls_desmesH');\">".$ls_anocur."</a></td>";
						print "<td>".$ls_desmes."-".$ls_desmesH."</td>";
						print "</tr>";				
						}
						
						break;					
					
				}
			}
			$io_sql->free_result($rs_data);
		}
		print "</table>";
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
		unset($ls_codnom);
		unset($ld_peractnom);
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Meses</title>
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Meses </td>
    </tr>
  </table>
<br>
    <br>
<?PHP
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$ls_tipo=$io_fun_nomina->uf_obtenertipo();
	$ls_codnom=$io_fun_nomina->uf_obtenervalor_get("codnom","");
	$ls_codnomhas=$io_fun_nomina->uf_obtenervalor_get("codnomhas","");
	uf_print($ls_tipo,$ls_codnom,$ls_codnomhas);
	unset($io_fun_nomina);
?>
</div>
</form>
</body>
<script language="JavaScript">
function aceptar(anocur,mescurper,mescurperH,desmes,desmesH)
{
	opener.document.form1.txtanocurper.value=anocur;
	opener.document.form1.txtanocurper.readOnly=true;
	opener.document.form1.txtmescurper.value=mescurper;
	opener.document.form1.txtmescurper.readOnly=true;
	opener.document.form1.txtdesmesper.value=desmes;
	opener.document.form1.txtdesmesper.readOnly=true;
	opener.document.form1.txtanocurperH.value=anocur;
	opener.document.form1.txtanocurperH.readOnly=true;
	opener.document.form1.txtmescurperH.value=mescurperH;
	opener.document.form1.txtmescurperH.readOnly=true;
	opener.document.form1.txtdesmesperH.value=desmesH;
	opener.document.form1.txtdesmesperH.readOnly=true;
	close();
}

function aceptargestdes(anocur,mescurper)
{
	opener.document.form1.txtanocurperdes.value=anocur;
	opener.document.form1.txtanocurperdes.readOnly=true;
	opener.document.form1.txtmescurperdes.value=mescurper;
	opener.document.form1.txtmescurperdes.readOnly=true;
	//opener.document.form1.txtanocurperhas.value=anocur;
	//opener.document.form1.txtanocurperhas.readOnly=true;
	//opener.document.form1.txtmescurperhas.value=mescurper;
	//opener.document.form1.txtmescurperhas.readOnly=true;
	close();
}

function aceptargesthas(anocur,mescurper)
{
	if(opener.document.form1.txtmescurperdes.value<=mescurper)
	{
		opener.document.form1.txtanocurperhas.value=anocur;
		opener.document.form1.txtanocurperhas.readOnly=true;
		opener.document.form1.txtmescurperhas.value=mescurper;
		opener.document.form1.txtmescurperhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango de meses Invï¿½lido.");
	}
}

function aceptarreplispreant(anocur,mescurper,desmes)
{
	opener.document.form1.txtanocurper.value=anocur;
	opener.document.form1.txtanocurper.readOnly=true;
	opener.document.form1.txtmescurper.value=mescurper;
	opener.document.form1.txtmescurper.readOnly=true;
	opener.document.form1.txtdesmesper.value=desmes;
	opener.document.form1.txtdesmesper.readOnly=true;
	close();
}

function aceptarreplispreantdesde(anocur,mescurper,desmes,codperi)
{
	opener.document.form1.txtanocurperdes.value=anocur;
	opener.document.form1.txtanocurperdes.readOnly=true;
	opener.document.form1.txtmescurperdes.value=mescurper;
	opener.document.form1.txtmescurperdes.readOnly=true;
	opener.document.form1.txtdesmesperdes.value=desmes;
	opener.document.form1.txtdesmesperdes.readOnly=true;
	opener.document.form1.txtcodperides.value=codperi;
	close();
}

function aceptarreplispreanthasta(anocur,mescurper,desmes,codperi)
{
	opener.document.form1.txtanocurperhas.value=anocur;
	opener.document.form1.txtanocurperhas.readOnly=true;
	opener.document.form1.txtmescurperhas.value=mescurper;
	opener.document.form1.txtmescurperhas.readOnly=true;
	opener.document.form1.txtdesmesperhas.value=desmes;
	opener.document.form1.txtdesmesperhas.readOnly=true;
	opener.document.form1.txtcodperihas.value=codperi;
	close();
}

function aceptarrepapopatdes(anocur,mescurper)
{
	opener.document.form1.txtanocurperdes.value=anocur;
	opener.document.form1.txtanocurperdes.readOnly=true;
	opener.document.form1.txtmescurperdes.value=mescurper;
	opener.document.form1.txtmescurperdes.readOnly=true;
	opener.document.form1.txtanocurperhas.value=anocur;
	opener.document.form1.txtanocurperhas.readOnly=true;
	opener.document.form1.txtmescurperhas.value=mescurper;
	opener.document.form1.txtmescurperhas.readOnly=true;
	close();
}

function aceptarrepapopathas(anocur,mescurper)
{
	if(opener.document.form1.txtmescurperdes.value<=mescurper)
	{
		opener.document.form1.txtanocurperhas.value=anocur;
		opener.document.form1.txtanocurperhas.readOnly=true;
		opener.document.form1.txtmescurperhas.value=mescurper;
		opener.document.form1.txtmescurperhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango de meses Invï¿½lido.");
	}
}

function aceptarreplisperracrecdes(anocur,mescurper)
{
	opener.document.form1.txtanocurperdes.value=anocur;
	opener.document.form1.txtanocurperdes.readOnly=true;
	opener.document.form1.txtmescurperdes.value=mescurper;
	opener.document.form1.txtmescurperdes.readOnly=true;
	//opener.document.form1.txtanocurperhas.value=anocur;
	//opener.document.form1.txtanocurperhas.readOnly=true;
	//opener.document.form1.txtmescurperhas.value=mescurper;
	//opener.document.form1.txtmescurperhas.readOnly=true;
	close();
}

function aceptarreplisperracrechas(anocur,mescurper)
{
	if(opener.document.form1.txtmescurperdes.value<=mescurper)
	{
		opener.document.form1.txtanocurperhas.value=anocur;
		opener.document.form1.txtanocurperhas.readOnly=true;
		opener.document.form1.txtmescurperhas.value=mescurper;
		opener.document.form1.txtmescurperhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango de meses Invï¿½lido.");
	}
}


function aceptarrepipaspat(anocur,mescurper,desmes)
{
	opener.document.form1.txtanocurper.value=anocur;
	opener.document.form1.txtanocurper.readOnly=true;
	opener.document.form1.txtmescurper.value=mescurper;
	opener.document.form1.txtmescurper.readOnly=true;
	opener.document.form1.txtdesmesper.value=desmes;
	opener.document.form1.txtdesmesper.readOnly=true;
	close();
}

function aceptarrepipascob(anocur,mescurper,desmes)
{
	opener.document.form1.txtanocurper.value=anocur;
	opener.document.form1.txtanocurper.readOnly=true;
	opener.document.form1.txtmescurper.value=mescurper;
	opener.document.form1.txtmescurper.readOnly=true;
	opener.document.form1.txtdesmesper.value=desmes;
	opener.document.form1.txtdesmesper.readOnly=true;
	close();
}

function aceptarrepretislr(anocur,mescurper,desmes)
{
	opener.document.form1.txtanocurper.value=anocur;
	opener.document.form1.txtanocurper.readOnly=true;
	opener.document.form1.txtmescurper.value=mescurper;
	opener.document.form1.txtmescurper.readOnly=true;
	opener.document.form1.txtdesmesper.value=desmes;
	opener.document.form1.txtdesmesper.readOnly=true;
	close();
}

function aceptarrepcestic(anocur,mescurper,desmes,codperi)
{
	opener.document.form1.txtanocurper.value=anocur;
	opener.document.form1.txtanocurper.readOnly=true;
	opener.document.form1.txtmescurper.value=mescurper;
	opener.document.form1.txtmescurper.readOnly=true;
	opener.document.form1.txtdesmesper.value=desmes;
	opener.document.form1.txtdesmesper.readOnly=true;
	opener.document.form1.txtcodperi.value=codperi;
	close();
}

function aceptarrepperips(anocur,mescurper,desmes,codperi)
{
	opener.document.form1.txtanocurper.value=anocur;
	opener.document.form1.txtanocurper.readOnly=true;
	opener.document.form1.txtmescurper.value=mescurper;
	opener.document.form1.txtmescurper.readOnly=true;
	opener.document.form1.txtdesmesper.value=desmes;
	opener.document.form1.txtdesmesper.readOnly=true;
	opener.document.form1.txtcodperi.value=codperi;
	close();
}

function aceptarrepcuadrect(anocur,mescurper,desmes,codperi)
{
	opener.document.form1.txtanocurper.value=anocur;
	opener.document.form1.txtanocurper.readOnly=true;
	opener.document.form1.txtmescurper.value=mescurper;
	opener.document.form1.txtmescurper.readOnly=true;
	opener.document.form1.txtdesmesper.value=desmes;
	opener.document.form1.txtdesmesper.readOnly=true;
	opener.document.form1.txtcodperi.value=codperi;
	close();
}

function aceptarreplispreantint(anocur,mescurper,desmes)
{
	opener.document.form1.txtanocurper.value=anocur;
	opener.document.form1.txtanocurper.readOnly=true;
	opener.document.form1.txtmescurper.value=mescurper;
	opener.document.form1.txtmescurper.readOnly=true;
	opener.document.form1.txtdesmesper.value=desmes;
	opener.document.form1.txtdesmesper.readOnly=true;
	close();
}

</script>
</html>
