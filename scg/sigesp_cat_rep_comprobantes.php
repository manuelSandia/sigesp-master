<?php
//session_id('8675309');
session_start();
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_sigesp_int.php");
require_once("../shared/class_folder/class_sigesp_int_scg.php");
$io_funciones=new class_funciones();
$in=new sigesp_include();
$con=$in->uf_conectar();
$int_scg=new class_sigesp_int_scg();
$msg=new class_mensajes();
$ds=new class_datastore();
$ds_procedencias=new class_datastore();
$SQL=new class_sql($con);
$SQL_cmp=new class_sql($con);
$arr=$_SESSION["la_empresa"];
$as_codemp=$arr["codemp"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Comprobantes</title>
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
  <table width="565" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Comprobantes Contables </td>
    </tr>
  </table>
  <br>
  <div align="center">
    <table width="565" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="94" align="right">&nbsp;</td>
        <td width="113">&nbsp;</td>
        <td width="176" colspan="3"><div align="left"></div></td>
      </tr>
      <tr>
        <td align="right">Comprobante</td>
        <td><div align="left">
          <input name="txtdocumento" type="text" id="txtdocumento" onBlur="javascript: rellenar_cad(document.form1.txtdocumento.value,15,'doc');" size="30">        
        </div></td>
			<?php
			if(array_key_exists("procede",$_POST))
			{
			$ls_procede_ant=$_POST["procede"];
			$sel_N="";
			}
			else
			{
			$ls_procede_ant="N";
			$sel_N="selected";
			}
			uf_cargar_procedencias($SQL);
			$li_rowcount=$ds_procedencias->getRowCount("procede");
			
			?>
			<td colspan="3" align="right">&nbsp;</td>
      </tr>
      <tr>
        <td><div align="right">Procedencia</div></td>
        <td colspan="4" align="left"><input name="procede" type="text" id="procede" size="30"></td>
      </tr>
      <tr>
        <td height="15"><div align="left"></div></td>
        <td colspan="4"><div align="left">
          <table width="72" border="0" align="right" cellpadding="0" cellspacing="0" class="letras-peque&ntilde;as">
            <tr>
              <td width="28"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" width="20" height="20" border="0"></a></td>
              <td width="44"><a href="javascript: ue_search();">Buscar</a></td>
              </tr>
          </table>
        </div></td>
      </tr>
    </table>
    <?php

function uf_cargar_procedencias($sql)
{
	global $ds_procedencias;
	$ls_sql="SELECT * FROM sigesp_procedencias";
	$data=$sql->select($ls_sql);
	if($row=$sql->fetch_row($data))
	{
		$data=$sql->obtener_datos($data);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds_procedencias->data=$data;
		
	}	
}

if(array_key_exists("operacion",$_POST))
{
	$ls_codemp=$as_codemp;
	$ls_operacion=$_POST["operacion"];
	$ls_comprobante="%".$_POST["txtdocumento"]."%";
	$ls_procedencia="%".$_POST["procede"]."%";
	if(array_key_exists("tipocat",$_GET))
	{
		$ls_tipocat=$_GET["tipocat"];
	}
	else
	{
		$ls_tipocat="";
	}
}
else
{
	$ls_operacion="";
	if(array_key_exists("tipocat",$_GET))
	{
		$ls_tipocat=$_GET["tipocat"];
	}
	else
	{
		$ls_tipocat="";
	}
	
}
if(($ls_tipocat=="")||($ls_tipocat=="repcompdes")||($ls_tipocat=="repcomphas"))
{
  print "<table width=565 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
  print "<tr class=titulo-celda>";
	print "<td>Comprobante</td>";
	print "<td>Descripcion Comprobante</td>";
	print "<td>Procede</td>";
	print "<td>Fecha</td>";
	print "<td>Proveedor</td>";
	print "<td>Beneficiario</td>";
	print "<td>Monto</td>";
print "</tr>";
}
elseif(($ls_tipocat=="rep_proc_des")||($ls_tipocat=="rep_proc_has"))
{
  print "<table width=565 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
  print "<tr class=titulo-celda>";
	print "<td>Procede</td>";
	print "<td>Comprobante</td>";
	print "<td>Descripcion Comprobante</td>";
	print "<td>Fecha</td>";
	print "<td>Proveedor</td>";
	print "<td>Beneficiario</td>";
	print "<td>Monto</td>";
print "</tr>";
}


if($ls_operacion=="BUSCAR"){
	if($ls_comprobante=="000000000000000"){
		$ls_comprobante="";
	}
	$ls_order=" ORDER BY cmp.comprobante,cmp.fecha,cmp.procede";
	switch($_SESSION["ls_gestor"]){
		case 'MYSQLT':
			$ls_sql="SELECT cmp.codemp,cmp.procede,
			                cmp.comprobante,cmp.descripcion,
							cmp.fecha,cmp.cod_pro,
							cmp.ced_bene,cmp.tipo_destino,
							SUM(dtcmp.monto) AS monto,
							(CASE   cmp.tipo_destino
								WHEN 'P'
                    				THEN
                        				(SELECT prov.nompro
                        				 FROM rpc_proveedor prov
                                         WHERE prov.codemp=cmp.codemp AND cmp.cod_pro=prov.cod_pro)
								WHEN 'B'
                    				THEN
                      					(SELECT CONCAT(RTRIM(xbf.apebene),',',xbf.nombene)
                      					 FROM rpc_beneficiario xbf
                      					 WHERE xbf.codemp=cmp.codemp AND cmp.cod_pro=xbf.ced_bene)
								ELSE 'Ninguno'
								END)  as  nombre
					 FROM sigesp_cmp cmp
					 INNER JOIN scg_dt_cmp dtcmp
					 ON  dtcmp.debhab = 'D' AND dtcmp.codemp=cmp.codemp
					 AND dtcmp.procede=cmp.procede AND dtcmp.comprobante=cmp.comprobante
					 AND dtcmp.fecha=cmp.fecha AND dtcmp.codban=cmp.codban
					 AND dtcmp.ctaban=cmp.ctaban
					 WHERE cmp.codemp='".$as_codemp."' AND  cmp.comprobante like '".$ls_comprobante."' 
					 AND cmp.procede like '".$ls_procedencia."'
					 GROUP BY cmp.codemp,cmp.procede,cmp.comprobante,cmp.descripcion,cmp.fecha,cmp.cod_pro, cmp.ced_bene,cmp.tipo_destino,cmp.codban,cmp.ctaban";
			break;
				
		case 'POSTGRES':
			$ls_sql="SELECT cmp.codemp,cmp.procede,
			                cmp.comprobante,cmp.descripcion,
							cmp.fecha,cmp.cod_pro,
							cmp.ced_bene,cmp.tipo_destino,
							SUM(dtcmp.monto) AS monto,
							(CASE   cmp.tipo_destino
								WHEN 'P'
                    				THEN
                        				(SELECT prov.nompro
                        				 FROM rpc_proveedor prov
                                         WHERE prov.codemp=cmp.codemp AND cmp.cod_pro=prov.cod_pro)
								WHEN 'B'
                    				THEN
                      					(SELECT TRIM(xbf.apebene)||','||xbf.nombene
                      					 FROM rpc_beneficiario xbf
                      					 WHERE xbf.codemp=cmp.codemp AND cmp.cod_pro=xbf.ced_bene)
								ELSE 'Ninguno'
								END)  as  nombre
					 FROM sigesp_cmp cmp
					 INNER JOIN scg_dt_cmp dtcmp
					 ON  dtcmp.debhab = 'D' AND dtcmp.codemp=cmp.codemp
					 AND dtcmp.procede=cmp.procede AND dtcmp.comprobante=cmp.comprobante
					 AND dtcmp.fecha=cmp.fecha AND dtcmp.codban=cmp.codban
					 AND dtcmp.ctaban=cmp.ctaban
					 WHERE cmp.codemp='".$as_codemp."' AND  cmp.comprobante like '".$ls_comprobante."' 
					 AND cmp.procede like '".$ls_procedencia."'
					 GROUP BY cmp.codemp,cmp.procede,cmp.comprobante,cmp.descripcion,cmp.fecha,cmp.cod_pro, cmp.ced_bene,cmp.tipo_destino,cmp.codban,cmp.ctaban ";
			break;
	}
	$ls_sql=$ls_sql.$ls_order;
	//print $ls_sql; 
	$data=$SQL_cmp->select($ls_sql);
	if(!$data->EOF){
		while(!$data->EOF){
			$ls_comprobante=$data->fields ["comprobante"];
			$ls_descripcion=$data->fields ["descripcion"];
			$ls_procedencia=$data->fields ["procede"];
			$ls_fecha=$io_funciones->uf_convertirfecmostrar($data->fields ["fecha"]);
			$ls_prov=$data->fields ["cod_pro"];
			$ls_bene=$data->fields ["ced_bene"];
			$ls_tip=$data->fields ["tipo_destino"];
			$ls_nomproben=$data->fields ["nombre"];
			$li_monto=number_format($data->fields ["monto"],2,",",".");			
			if($ls_tipocat==""){
					print "<tr class=celdas-blancas>";
					print "<td><a href=\"javascript: uf_aceptar('$ls_comprobante','$ls_descripcion','$ls_procedencia','$ls_fecha','$ls_tip','$ls_prov','$ls_bene','$ls_nomproben');\">".$ls_comprobante."</a></td>";
					print "<td>".$ls_descripcion."</td>";
					print "<td>".$ls_procedencia."</td>";				
					print "<td>".$ls_fecha."</td>";
					print "<td>".$ls_prov."</td>";
					print "<td>".$ls_bene."</td>";				
					print "<td>".$li_monto."</td>";				
				    print "</tr>";			
			}
			if($ls_tipocat=="repcompdes"){
					print "<tr class=celdas-blancas>";
					print "<td><a href=\"javascript: uf_aceptar_repcompdes('$ls_comprobante','$ls_descripcion','$ls_procedencia','$ls_fecha','$ls_tip','$ls_prov','$ls_bene');\">".$ls_comprobante."</a></td>";
					print "<td>".$ls_descripcion."</td>";
					print "<td>".$ls_procedencia."</td>";				
					print "<td>".$ls_fecha."</td>";
					print "<td>".$ls_prov."</td>";
					print "<td>".$ls_bene."</td>";				
					print "<td>".$li_monto."</td>";				
				    print "</tr>";		
			 }
			 if($ls_tipocat=="repcomphas")
			 {
					print "<tr class=celdas-blancas>";
					print "<td><a href=\"javascript: uf_aceptar_repcomphas('$ls_comprobante','$ls_descripcion','$ls_procedencia','$ls_fecha','$ls_tip','$ls_prov','$ls_bene');\">".$ls_comprobante."</a></td>";
					print "<td>".$ls_descripcion."</td>";
					print "<td>".$ls_procedencia."</td>";				
					print "<td>".$ls_fecha."</td>";
					print "<td>".$ls_prov."</td>";
					print "<td>".$ls_bene."</td>";				
					print "<td>".$li_monto."</td>";				
				    print "</tr>";		
			 }
			 if($ls_tipocat=="rep_proc_des")
			 {
					print "<tr class=celdas-blancas>";
					print "<td><a href=\"javascript: uf_aceptar_rep_proc_des('$ls_comprobante','$ls_descripcion','$ls_procedencia','$ls_fecha','$ls_tip','$ls_prov','$ls_bene');\">".$ls_procedencia."</a></td>";
					print "<td>".$ls_comprobante."</td>";				
					print "<td>".$ls_descripcion."</td>";
					print "<td>".$ls_fecha."</td>";
					print "<td>".$ls_prov."</td>";
					print "<td>".$ls_bene."</td>";				
					print "<td>".$li_monto."</td>";				
				    print "</tr>";		
			 }
			 if($ls_tipocat=="rep_proc_has")
			 {
					print "<tr class=celdas-blancas>";
					print "<td><a href=\"javascript: uf_aceptar_rep_proc_has('$ls_comprobante','$ls_descripcion','$ls_procedencia','$ls_fecha','$ls_tip','$ls_prov','$ls_bene');\">".$ls_procedencia."</a></td>";
					print "<td>".$ls_comprobante."</td>";				
					print "<td>".$ls_descripcion."</td>";
					print "<td>".$ls_fecha."</td>";
					print "<td>".$ls_prov."</td>";
					print "<td>".$ls_bene."</td>";				
					print "<td>".$li_monto."</td>";				
				    print "</tr>";		
			 }
			$data->MoveNext();
		}
	}
	else
	{
		?>
			<script language="JavaScript">
			alert("No se han creado Comprobantes Contables.....");
			close();
			</script>
		<?php
	}
}
print "</table>";
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

  function uf_aceptar(comprobante,descripcion,procede,fecha,tipo,prov,bene,ls_nomproben)
  {
    f=opener.document.form1;
	f.txtcomprobante.value=comprobante;
	f.txtdesccomp.value=descripcion;
	f.txtproccomp.value=procede;
	f.txtfecha.value=fecha;
	f.tipo.value=tipo;
	f.txtnomproben.value=ls_nomproben;
	if(tipo=="P")
	{
		f.txtprovbene.value=prov;
	}
	else if(tipo=="B")
	{
		f.txtprovbene.value=bene;
	}
	else
	{
		f.txtprovbene.value="";
	}
	f.operacion.value="CARGAR";
	f.action="sigespwindow_scg_comprobante.php";
	f.submit();
	close();
  }

  function uf_aceptar_repcompdes(comprobante,descripcion,procede,fecha,tipo,prov,bene,ls_provbene)
  {
		f=opener.document.form1;
		f.txtcompdes.value=comprobante;
		f.txtcompdes.readOnly=true;
		close();
  }
  
   function uf_aceptar_repcomphas(comprobante,descripcion,procede,fecha,tipo,prov,bene,ls_provbene)
   {
		f=opener.document.form1;
		f.txtcomphas.value=comprobante;
		f.txtcomphas.readOnly=true;
		close(); 
   }
   
   function uf_aceptar_rep_proc_des(comprobante,descripcion,procede,fecha,tipo,prov,bene,ls_provbene)
   {
		f=opener.document.form1;
		f.txtprocdes.value=procede;
		f.txtprocdes.readOnly=true;
		close(); 
   }
   
   function uf_aceptar_rep_proc_has(comprobante,descripcion,procede,fecha,tipo,prov,bene,ls_provbene)
   {
		f=opener.document.form1;
		f.txtprochas.value=procede;
		f.txtprochas.readOnly=true;
		close(); 
   }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cat_rep_comprobantes.php?tipocat=<? print $ls_tipocat; ?>";
	  f.submit();
  }
	function catprovbene(provbene)
	{
		f=document.form1;
		if(provbene=="P")
		{
			f.txtprovbene.disabled=false;	
			window.open("sigesp_catdinamic_prov.php","Catalogo","_blank");
		}
		else if(provbene=="B")
		{
			f.txtprovbene.disabled=false;	
			window.open("sigesp_catdinamic_bene.php","catalogo","_blank");
		}
	}
	//Funciones de validacion de fecha.
	function rellenar_cad(cadena,longitud,campo)
	{
		var mystring=new String(cadena);
		cadena_ceros="";
		lencad=mystring.length;
	
		total=longitud-lencad;
		for(i=1;i<=total;i++)
		{
			cadena_ceros=cadena_ceros+"0";
		}
		cadena=cadena_ceros+cadena;
		if(campo=="doc")
		{
			document.form1.txtdocumento.value=cadena;
		}
		else
		{
			document.form1.txtcomprobante.value=cadena;
		}
	
	}

	  function valSep(oTxt){ 
    var bOk = false; 
    var sep1 = oTxt.value.charAt(2); 
    var sep2 = oTxt.value.charAt(5); 
    bOk = bOk || ((sep1 == "-") && (sep2 == "-")); 
    bOk = bOk || ((sep1 == "/") && (sep2 == "/")); 
    return bOk; 
   } 

   function finMes(oTxt){ 
    var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
    var nAno = parseInt(oTxt.value.substr(6), 10); 
    var nRes = 0; 
    switch (nMes){ 
     case 1: nRes = 31; break; 
     case 2: nRes = 28; break; 
     case 3: nRes = 31; break; 
     case 4: nRes = 30; break; 
     case 5: nRes = 31; break; 
     case 6: nRes = 30; break; 
     case 7: nRes = 31; break; 
     case 8: nRes = 31; break; 
     case 9: nRes = 30; break; 
     case 10: nRes = 31; break; 
     case 11: nRes = 30; break; 
     case 12: nRes = 31; break; 
    } 
    return nRes + (((nMes == 2) && (nAno % 4) == 0)? 1: 0); 
   } 

   function valDia(oTxt){ 
    var bOk = false; 
    var nDia = parseInt(oTxt.value.substr(0, 2), 10); 
    bOk = bOk || ((nDia >= 1) && (nDia <= finMes(oTxt))); 
    return bOk; 
   } 

   function valMes(oTxt){ 
    var bOk = false; 
    var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
    bOk = bOk || ((nMes >= 1) && (nMes <= 12)); 
    return bOk; 
   } 

   function valAno(oTxt){ 
    var bOk = true; 
    var nAno = oTxt.value.substr(6); 
    bOk = bOk && ((nAno.length == 2) || (nAno.length == 4)); 
    if (bOk){ 
     for (var i = 0; i < nAno.length; i++){ 
      bOk = bOk && esDigito(nAno.charAt(i)); 
     } 
    } 
    return bOk; 
   } 

   function valFecha(oTxt){ 
    var bOk = true; 
	
		if (oTxt.value != ""){ 
		 bOk = bOk && (valAno(oTxt)); 
		 bOk = bOk && (valMes(oTxt)); 
		 bOk = bOk && (valDia(oTxt)); 
		 bOk = bOk && (valSep(oTxt)); 
		 if (!bOk){ 
		  alert("Fecha inv?lida ,verifique el formato(Ejemplo: 10/10/2005) \n o introduzca una fecha correcta."); 
		  oTxt.value = "01/01/1900"; 
		  oTxt.focus(); 
		 } 
		}
	 
   }

  function esDigito(sChr){ 
    var sCod = sChr.charCodeAt(0); 
    return ((sCod > 47) && (sCod < 58)); 
   }
	
</script>
</html>
