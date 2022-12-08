<?
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "opener.location.href='../sigesp_conexion.php';";
	print "close();";
	print "</script>";		
} 
$la_datemp=$_SESSION["la_empresa"];
if(!array_key_exists("campo",$_POST))
{
	$ls_campo="sob_obra.codobr";
	$ls_orden="ASC";
}
else
{
	$ls_campo=$_POST["campo"];
	$ls_orden=$_POST["orden"];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Obras</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/number_format.js"></script>
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699#006699;
}
-->
</style></head>

<body>
<form name="form1" method="post" action="">
<input type="hidden" name="campo" id="campo" value="<? print $ls_campo;?>" >
<input type="hidden" name="orden" id="orden" value="<? print $ls_orden;?>">
<?

require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("class_folder/sigesp_sob_class_obra.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("class_folder/sigesp_sob_c_funciones_sob.php");
$io_funsob=new sigesp_sob_c_funciones_sob();
$io_datastore=new class_datastore();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_msg=new class_mensajes();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();
$io_obra=new sigesp_sob_class_obra();
$ls_codemp=$la_datemp["codemp"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codobr="%".$_POST["txtcodobr"]."%";	
	$ls_desobr="%".$_POST["txtdesobr"]."%";	
	$ls_codest="%".$_POST["cmbestado"]."%";
	$ls_nompro="%".$_POST["txtnompro"]."%";
	$ls_estobr=$_POST["hidestado"];//Estado viene vacio si no es necesario filtrar por estado, vienecon alguna cadena si es necesario filtrar por algun estado(status)
	
	$ld_fechainicio=$io_funcion->uf_convertirdatetobd($_POST["txtfeciniobr"]);
	$ld_fechafin=$io_funcion->uf_convertirdatetobd($_POST["txtfecfinobr"]);	
	$ls_codigoobra=$_POST["txtcodobr"];	
	$ls_descripcionobra=$_POST["txtdesobr"];	
	$ls_codigoestado=$_POST["cmbestado"];
	$ls_codpai=$_POST["cmbpais"];
	$ls_nombreproveedor=$_POST["txtnompro"];
	$ls_fechaini=$_POST["txtfeciniobr"];
	$ls_fechafin=$_POST["txtfecfinobr"];
	$ls_origen=$_POST["origen"];

}
else
{
	$ls_operacion="";
	$ls_estobr=$_GET["estado"];	
	$ld_fechainicio="";
	$ld_fechafin="";
	$ls_codigoobra="";
	$ls_descripcionobra="";
	$ls_codigoestado="";
	$ls_nombreproveedor="";
	$ls_fechaini="";
	$ls_fechafin="";
	$ls_codpai="";
	$ls_origen="";
}
if(array_key_exists("origen",$_GET))
{
	$ls_origen=$_GET["origen"];	
}
?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
<input name="origen" type="hidden" id="origen" value="<?php print $ls_origen; ?>">
</p>
  	 <table width="800" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="800" colspan="2" class="titulo-celda">Cat&aacute;logo de Obras </td>
    	</tr>
	 </table>
	 <br>
	 <table width="800" border="0" cellpadding="0" cellspacing="3" class="formato-blanco" align="center">
      <tr>
        <td height="18"><div align="right"></div></td>
        <td><div align="right">C&oacute;digo</div></td>
        <td width="295"><input name="txtcodobr" type="text" id="txtcodobr" value="<? print $ls_codigoobra;?>" size="6" maxlength="6" ></td>
        <td><div align="right"></div></td>
        <td width="133"><div align="right">Organismo Ejecutor</div></td>
        <td><input name="txtnompro" type="text" id="txtnompro" value="<? print $ls_nombreproveedor;?>" size="30"></td>
      </tr>
      <tr>
        <td height="18"><div align="right"></div></td>
        <td height="18"><div align="right">Descripci&oacute;n</div></td>
        <td><input name="txtdesobr" type="text" id="txtdesobr" value="<? print $ls_descripcionobra;?>" size="30"></td>
        <td><div align="right"></div></td>
        <td><div align="right">Fecha de Inicio</div></td>
        <td><input name="txtfeciniobr" type="text"  id="txtfeciniobr" value="<? print $ls_fechaini;?>" size="11" maxlength="10" datepicker="true" onKeyPress="return validaCajas(this,'n',event)"></td>
      </tr>
      <tr>
        <td height="21">&nbsp;</td>
        <td height="21"><div align="right">Pa&iacute;s</div></td>
        <td><div align="left">
          <?Php
           $lb_valido=$io_obra->uf_llenarcombo_pais($la_paises);
		   
		   if($lb_valido)
		   {
		    $io_data->data=$la_paises;
		    $li_totalfilas=$io_data->getRowCount("codpai");
		   }
		   ?>
          <select name="cmbpais" id="cmbpais" onChange="javascript:document.form1.submit();">
            <option value="" >Seleccione...</option>
            <?Php
			for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
			 {
			  $ls_codigo=$io_data->getValue("codpai",$li_i);
		      $ls_desest=$io_data->getValue("despai",$li_i);
		      if ($ls_codigo==$ls_codpai)
			   {
				print "<option value='$ls_codigo' selected>$ls_desest</option>";
			   }
		       else
			   {
				print "<option value='$ls_codigo'>$ls_desest</option>";
			   }
		      }      
	        ?>
          </select>
          <input name="hidpais" type="hidden" id="hidpais"  value="<? print $ls_codpai?>">
        </div>
          <div align="right"></div>
        </td>
        <td></td>
        <td  ><div align="right">Fecha de Fin</div></td>
        <td><input name="txtfecfinobr" type="text" id="txtfecfinobr2" value="<? print $ls_fechafin;?>" size="11" maxlength="10" datepicker="true" onKeyPress="return validaCajas(this,'n',event)"></td>
      </tr>
      <tr>
        <td width="13" height="21"><div align="right"></div></td>
        <td width="62" height="21"><div align="right">Estado</div></td>
        <td><div align="left">
          <?Php
           $lb_valido=$io_obra->uf_llenarcombo_estado($ls_codpai,$la_estados);
		   
		   if($lb_valido)
		   {
		    $io_data->data=$la_estados;
		    $li_totalfilas=$io_data->getRowCount("codest");
		   }
		   else
		    $li_totalfilas=0;
		   ?>
          <select name="cmbestado" id="cmbestado">
            <option value="" >Seleccione...</option>
            <?Php
			for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
			 {
			  $ls_codigo=$io_data->getValue("codest",$li_i);
		      $ls_desest=$io_data->getValue("desest",$li_i);
		      if ($ls_codigo==$ls_codigoestado)
			   {
				print "<option value='$ls_codigo' selected>$ls_desest</option>";
			   }
		       else
			   {
				print "<option value='$ls_codigo'>$ls_desest</option>";
			   }
		      }      
	        ?>
          </select>
          <input name="hidestado2" type="hidden" id="hidestado2"  value="<? print $ls_codigoestado ?>">
        </div>          </td>
        <td width="3"></td>
        <td  ><div align="right"></div>          <div align="right"></div></td>
        <td width="292"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<br>

	<input name="hidestado" id="hidestado" type="hidden" value="<? print $ls_estobr;?>">
<?

if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT sob_obra.codemp,sob_obra.codobr,sob_obra.codten,sob_obra.codtipest,sob_obra.codpai,sob_obra.codest,".
			"       sob_obra.codmun,sob_obra.codpar,sob_obra.codcom,sob_obra.codsiscon,sob_obra.codpro,sob_obra.codtob,".
			"       sob_obra.desobr,sob_obra.dirobr,sob_obra.obsobr,sob_obra.resobr,sob_obra.feciniobr,sob_obra.fecfinobr,".
			"       sob_obra.cantobr,sob_obra.monto,sob_obra.feccreobr,sob_tenencia.nomten,sob_tipoestructura.nomtipest,".
			"       sob_sistemaconstructivo.nomsiscon,sob_propietario.nompro,sob_tipoobra.nomtob,sigesp_estados.desest as desest,".
			"       sob_obra.staobr,sob_obra.codpai,sob_obra.monimp,sob_obra.basimp,".
			"      (SELECT SUM(montotasi) FROM sob_asignacion".
			"        WHERE sob_obra.codemp=sob_asignacion.codemp".
			"          AND sob_obra.codobr=sob_asignacion.codobr".
			"        GROUP BY sob_asignacion.codemp,sob_asignacion.codobr) AS montoasignado".
			"  FROM sob_obra,sob_tenencia,sob_tipoestructura,sob_sistemaconstructivo,sob_propietario,sob_tipoobra,sigesp_estados".
			" WHERE sob_obra.codemp='".$ls_codemp."' ".
			"   AND sob_obra.codobr like '".$ls_codobr."'". 
			"   AND sob_obra.desobr like '".$ls_desobr."'". 
			"   AND sob_obra.codest like '".$ls_codest."'". 
			"   AND sob_propietario.nompro like '".$ls_nompro."'". 
			"   AND sob_obra.codpro=sob_propietario.codpro ".
			"   AND sob_obra.codten=sob_tenencia.codten ".
			"   AND sigesp_estados.codpai=sob_obra.codpai ".
			"   AND sob_obra.codtipest=sob_tipoestructura.codtipest ".
			"   AND sob_obra.codsiscon=sob_sistemaconstructivo.codsiscon ".
			"   AND sob_obra.codpro=sob_propietario.codpro ".
			"   AND sob_obra.codtob=sob_tipoobra.codtob ".
			"   AND sob_obra.codest=sigesp_estados.codest ".
			"   AND sob_obra.staobr<>3 ";				
			//print $ls_sql;
	if($ls_estobr!="")
	{
		$ls_sql=$ls_sql." AND (sob_obra.staobr=1 OR sob_obra.staobr=2)";
	}
	if($ld_fechainicio!="" && $ld_fechafin=="")
	{
		$ls_sql=$ls_sql." AND feciniobr>='".$ld_fechainicio."'";			
	}
	if($ld_fechainicio=="" && $ld_fechafin!="")
	{
		$ls_sql=$ls_sql." AND fecfinobr<='".$ld_fechafin."'";			
	}
	$ls_sql=$ls_sql." ORDER BY $ls_campo $ls_orden";
	$rs_data=$io_sql->select($ls_sql);
	if($rs_data===false)
	{
		$is_msg_error="Error en select".$io_funcion->uf_convertirmsg($io_sql->message);
		print $is_msg_error;
	}
	else
	{
		print "<table width=800 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td><a href=javascript:ue_ordenar('sob_obra.codobr','BUSCAR');><font color=#FFFFFF>Código</font></a></td>";
		print "<td><a href=javascript:ue_ordenar('sob_obra.desobr','BUSCAR');><font color=#FFFFFF>Descripción</font></a></td>";
		print "<td><a href=javascript:ue_ordenar('sob_propietario.nompro','BUSCAR');><font color=#FFFFFF>Organismo Ejecutor</font></a></td>";
		print "<td><a href=javascript:ue_ordenar('sigesp_estados.desest','BUSCAR');><font color=#FFFFFF>Edo.</font></a></td>";
		print "<td><a href=javascript:ue_ordenar('sob_obra.feciniobr','BUSCAR');><font color=#FFFFFF>Fecha Inicio</font></a></td>";
		print "<td><a href=javascript:ue_ordenar('sob_obra.fecfinobr','BUSCAR');><font color=#FFFFFF>Fecha Fin</font></a></td>";
		print "<td><a href=javascript:ue_ordenar('sob_obra.staobr','BUSCAR');><font color=#FFFFFF>Estado</font></a></td>";
		print "</tr>";
		while(!$rs_data->EOF)
		{
			$lb_valido=true;
			$ls_codigo=$rs_data->fields["codobr"];
			$ls_descripcion=$rs_data->fields["desobr"];
			$ls_estado=$rs_data->fields["desest"];
			$ls_codest=$rs_data->fields["codest"];
			$ls_codten=$rs_data->fields["codten"];
			$ls_codtipest=$rs_data->fields["codtipest"];
			$ls_codpai=$rs_data->fields["codpai"];	
			$ls_codmun=$rs_data->fields["codmun"];
			$ls_codpar=$rs_data->fields["codpar"];
			$ls_codcom=$rs_data->fields["codcom"];
			$ls_codsiscon=$rs_data->fields["codsiscon"];
			$ls_codpro=$rs_data->fields["codpro"];	
			$ls_codtob=$rs_data->fields["codtob"];			
			$ls_dirobr=$rs_data->fields["dirobr"];
			$ls_obsobr=$rs_data->fields["obsobr"];
			$ls_resobr=$rs_data->fields["resobr"];
			$ld_monto=$rs_data->fields["monto"];
			$ld_monimp=$rs_data->fields["monimp"];
			$ld_basimp=$rs_data->fields["basimp"];
			$ls_feccreobr=$io_funcion->uf_convertirfecmostrar($rs_data->fields["feccreobr"]);
			$ls_nompro=$rs_data->fields["nompro"];				 
			$ls_fechainicio=$io_funcion->uf_convertirfecmostrar($rs_data->fields["feciniobr"]);
			$ls_fechafin=$io_funcion->uf_convertirfecmostrar($rs_data->fields["fecfinobr"]);
			$ls_nomten=$rs_data->fields["nomten"];
			$ls_nomtipest=$rs_data->fields["nomtipest"];
			$ls_nomsiscon=$rs_data->fields["nomsiscon"];
			$ls_nomtob=$rs_data->fields["nomtob"];
			$ls_codigopais=$rs_data->fields["codpai"];
			$li_montoasignado=$rs_data->fields["montoasignado"];
			$ls_status=$io_funsob->uf_convertir_numeroestado($rs_data->fields["staobr"]);
			if($ls_origen=="ASIGNACION")
			{
				if($ld_monto<=$li_montoasignado)
				{
					$lb_valido=false;
				}
			}
			if($lb_valido)
			{
				print "<tr class=celdas-blancas align=center>";
				print "<td align=center><a href=\"javascript: aceptar('$ls_codigo','$ls_descripcion','$ls_estado','$ls_codest','$ls_codten',
															'$ls_codtipest','$ls_codpai','$ls_codmun','$ls_codpar','$ls_codcom','$ls_codsiscon','$ls_codpro','$ls_codtob',
															'$ls_dirobr','$ls_obsobr','$ls_resobr','$ld_monto','$ls_feccreobr','$ls_nompro','$ls_fechainicio',
															'$ls_fechafin','$ls_nomten','$ls_nomtipest','$ls_nomsiscon','$ls_nomtob','$ls_status','$ls_codigopais','$ld_monimp','$ld_basimp');\">".$ls_codigo."</a></td>";
				print "<td align=left>".$ls_descripcion."</td>";
				print "<td align=center>".$ls_nompro."</td>";
				print "<td align=center>".$ls_estado."</td>";
				print "<td align=center>".$ls_fechainicio."</td>";
				print "<td align=center>".$ls_fechafin."</td>";			
				print "<td align=center>".$ls_status."</td>";	
				print "</tr>";
			}
			$rs_data->MoveNext();
		}
		print "</table>";

		$io_sql->free_result($rs_data);
		$io_sql->close();
	}
}
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  
  function aceptar(ls_codigo,ls_descripcion,ls_estado,ls_codest,ls_codten,ls_codtipest,ls_codpai,ls_codmun,ls_codpar,ls_codcom,
				  ls_codsiscon,ls_codpro,ls_codtob,ls_dirobr,ls_obsobr,ls_resobr,ld_monto,ls_feccreobr,ls_nompro,
				  ls_fechainicio,ls_fechafin,ls_nomten,ls_nomtipest,ls_nomsiscon,ls_nomtob,ls_estado,ls_codpais,monimp,basimp)
  {
   if(document.form1.origen.value=="REPDES")
   {
   		opener.document.formulario.txtcoddes.value=ls_codigo;
   }
   else 
   {
   		if(document.form1.origen.value=="REPHAS")
		{
   			opener.document.formulario.txtcodhas.value=ls_codigo;
		}
		else
		{
			opener.ue_cargarobra(ls_codigo,ls_descripcion,ls_estado,ls_codest,ls_codten,ls_codtipest,ls_codpai,ls_codmun,ls_codpar,ls_codcom,
								 ls_codsiscon,ls_codpro,ls_codtob,ls_dirobr,ls_obsobr,ls_resobr,ld_monto,ls_feccreobr,ls_nompro,
								 ls_fechainicio,ls_fechafin,ls_nomten,ls_nomtipest,ls_nomsiscon,ls_nomtob,ls_estado,ls_codpais,monimp,basimp);
		}
   }
   
	close();
  }
  
  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cat_obra.php";
	  f.submit();
  }
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
</html>
