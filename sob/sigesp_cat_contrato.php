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
	$ls_campo="c.codcon";
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
<title>Cat&aacute;logo de Contratos</title>
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
require_once ("class_folder/sigesp_sob_c_funciones_sob.php");
$io_funobr= new sigesp_sob_c_funciones_sob(); 
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
	$ls_opener=$_POST["hidopener"]; 
	$ls_operacion=$_POST["operacion"];
	$ls_codobr="%".$_POST["txtcodobr"]."%";	
	$ls_desobr="%".$_POST["txtdesobr"]."%";	
	$ls_codcon="%".$_POST["txtcodcon"]."%";
	$ls_codasi="%".$_POST["txtcodasi"]."%";
	$ld_feccrecon=$io_funcion->uf_convertirdatetobd($_POST["txtfeccrecon"]);
	$ld_fecinicon=$io_funcion->uf_convertirdatetobd($_POST["txtfecinicon"]);
	//$ls_estado2=$_POST["hidestado2"];
	$ls_codigoobr=$_POST["txtcodobr"];	
	$ls_descripcionobr=$_POST["txtdesobr"];	
	if($ls_opener=="")
		$ls_estadocon=$_POST["cmbestado"];	
	else	
		$ls_estadocon="";
	$ls_estcon="";
	if(array_key_exists("cmbestado",$_POST))
	{
		if (trim($_POST["cmbestado"])<>'')
		{
			$ls_estcon="AND c.estcon =".$_POST["cmbestado"]."";
		}
	}	
	$ls_codigocon=$_POST["txtcodcon"];
	$ls_codigoasi=$_POST["txtcodasi"];
	$ld_fechacrecon=$_POST["txtfeccrecon"];
	$ld_fechainicon=$_POST["txtfecinicon"];
}
else
{
	$ls_operacion="";
	if(array_key_exists("opener",$_GET))
		$ls_opener=$_GET["opener"];
	else
		$ls_opener="";
	$ls_codigoobr="";
	$ls_descripcionobr="";
	$ls_estadocon="";
	$ls_codigocon="";
	$ls_codigoasi="";
	$ld_fechacrecon="";
	$ld_fechainicon="";
}
?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  	 <table width="800" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="800" colspan="2" class="titulo-celda">Cat&aacute;logo de Contratos</td>
    	</tr>
	 </table>
	 <br>
	 <table width="800" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td height="26"><div align="right"></div></td>
        <td><div align="right">C&oacute;digo del Contrato</div></td>
        <td><input name="txtcodcon" type="text" id="txtcodcon" value="<? print $ls_codigocon;?>" size="6" maxlength="6" ></td>
        <td><div align="right"></div></td>
        <td><div align="right">Descripci&oacute;n de la Obra</div></td>
        <td><input name="txtdesobr" type="text" id="txtdesobr" value="<? print $ls_descripcionobr;?>" size="30" maxlength="254"></td>
      </tr>
      <tr>
        <td height="27"><div align="right"></div></td>
        <td height="27"><div align="right">C&oacute;digo de la Asignaci&oacute;n</div></td>
        <td><input name="txtcodasi" type="text" id="txtcodasi" value="<? print $ls_codigoasi;?>" size="6" maxlength="6"></td>
        <td><div align="right"></div></td>
        <td><div align="right">Fecha de Creaci&oacute;n del Contrato</div></td>
        <td><input name="txtfeccrecon" type="text"  id="txtfeccrecon" onKeyPress="return validaCajas(this,'n',event)" value="<? print $ld_fechacrecon;?>" size="11" maxlength="10" datepicker="true"></td>
      </tr>
      <tr>
        <td height="29">&nbsp;</td>
        <td height="29"><div align="right">C&oacute;digo de la Obra </div></td>
        <td><input name="txtcodobr" type="text" id="txtcodobr" value="<? print $ls_codigoobr;?>" size="6" maxlength="6"></td>
        <td>&nbsp;</td>
        <td><div align="right">Fecha de Inicio del Contrato</div></td>
        <td><input name="txtfecinicon" type="text" id="txtinicon" onKeyPress="return validaCajas(this,'n',event)" value="<? print $ld_fechainicon;?>" size="11" maxlength="10" datepicker="true"></td>
      </tr>
      <tr>
        <td width="60" height="29"><div align="right"></div></td>
        <td width="118" height="29"><div align="right">Estado del Contrato</div></td>
        <td width="128"><div align="left">
            <?Php
			if ($ls_opener!="")
			{
			?> 
				<select name="cmbestado" id="cmbestado"  disabled="disabled">
           			<option value="" >Seleccione...</option>
					<option value="5">Contabilizado</option>
					
		  </select>
			<?Php
			}
			else
			{
			?>
				<select name="cmbestado" id="cmbestado">
           			<option value="" >Seleccione...</option>
            		<option value="1">Emitido</option>
					<option value="5">Contabilizado</option>
					<option value="10">Iniciado</option>
					<option value="6">Modificado</option>
				</select>
			<?Php
			}
			if ($ls_estadocon!="")
			{
				print "<script>";
					print "document.form1.cmbestado.value='$ls_estadocon';";
				print "</script>";
			}
			?>
          
        </div></td>
        <td width="3"><div align="right"></div></td>
        <td width="161">&nbsp;</td>
        <td width="174">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2"></td>
        <td colspan="4"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<input type="hidden" name="hidopener" id="hidestado"value="<? print $ls_opener?>">
<?

if($ls_operacion=="BUSCAR")
{
	if ($ls_opener!="")
	{
		if($ls_opener=="anticipo" || $ls_opener=="variacion")
		{
			$ls_sql="SELECT c.codcon, c.monto, c.placon,c.placonuni, c.mulcon, c.fecfincon, c.tiemulcon, c.mulreuni,c.lapgarcon, c.lapgaruni,  c.codtco, a.cod_pro, a.cod_pro_ins,
					c.monmaxcon, c.pormaxcon,  c.obscon,  c.porejefiscon, t.nomtco, c.porejefincon, c.monejefincon,c.codasi,o.codobr,o.desobr,c.feccon,c.fecinicon,c.estcon,c.precon,c.estspgscg,c.estapr,
					(SELECT MAX(codant) FROM sob_anticipo WHERE c.codemp=sob_anticipo.codemp AND c.codcon=sob_anticipo.codcon GROUP BY sob_anticipo.codemp,sob_anticipo.codcon) AS existeant
					FROM sob_contrato c,sob_asignacion a,sob_obra o, sob_tipocontrato t
					WHERE c.codemp='".$ls_codemp."' AND a.codemp='".$ls_codemp."' AND o.codemp='".$ls_codemp."' AND c.codasi=a.codasi AND a.codobr=o.codobr AND c.codtco=t.codtco
					AND o.codobr like '".$ls_codobr."'	AND  o.desobr like '".$ls_desobr."' AND (c.estcon = 1 OR c.estcon = 6 OR c.estcon = 7 OR c.estcon = 9 OR c.estcon = 10 OR c.estcon = 11) AND c.estspgscg=1 AND c.codcon like '".$ls_codcon."'
					AND a.codasi like '".$ls_codasi."' AND c.estcon<>3";
		}
		elseif($ls_opener=="valuacion")
		{
			$ls_sql="SELECT c.codcon, c.monto, c.placon,c.placonuni, c.mulcon, c.fecfincon, c.tiemulcon, c.mulreuni,c.lapgarcon, c.lapgaruni,  c.codtco, a.cod_pro, a.cod_pro_ins,
					c.monmaxcon, c.pormaxcon,  c.obscon,  c.porejefiscon, t.nomtco, c.porejefincon, c.monejefincon,c.codasi,o.codobr,o.desobr,c.feccon,c.fecinicon,c.estcon,c.precon,c.estspgscg,c.estapr,'' AS existeant
					FROM sob_contrato c,sob_asignacion a,sob_obra o, sob_tipocontrato t
					WHERE c.codemp='".$ls_codemp."' AND a.codemp='".$ls_codemp."' AND o.codemp='".$ls_codemp."' AND c.codasi=a.codasi AND a.codobr=o.codobr AND c.codtco=t.codtco
					AND o.codobr like '".$ls_codobr."'	AND  o.desobr like '".$ls_desobr."' AND (c.estcon=6 OR c.estcon=9 OR c.estcon=10) AND c.estspgscg=1  AND c.codcon like '".$ls_codcon."'
					AND a.codasi like '".$ls_codasi."' AND c.estcon<>3";
		}		
	}
	else
	{
		$ls_sql="SELECT c.codcon, c.monto, c.placon,c.placonuni, c.mulcon, c.fecfincon, c.tiemulcon, c.mulreuni,c.lapgarcon, c.lapgaruni,  c.codtco, a.cod_pro, a.cod_pro_ins,
				c.monmaxcon, c.pormaxcon,  c.obscon,  c.porejefiscon, t.nomtco, c.porejefincon, c.monejefincon,c.codasi,o.codobr,o.desobr,c.feccon,c.fecinicon,c.estcon,c.precon,c.estspgscg,c.estapr,'' AS existeant
				FROM sob_contrato c,sob_asignacion a,sob_obra o, sob_tipocontrato t
				WHERE c.codemp='".$ls_codemp."' AND a.codemp='".$ls_codemp."' AND o.codemp='".$ls_codemp."' ".$ls_estcon." AND c.codasi=a.codasi AND a.codobr=o.codobr AND c.codtco=t.codtco
				AND o.codobr like '".$ls_codobr."'	AND  o.desobr like '".$ls_desobr."'  AND c.codcon like '".$ls_codcon."'
				AND a.codasi like '".$ls_codasi."' AND c.estcon<>3";
		
		
		/*$ls_sql="SELECT c.codcon, c.monto, c.placon,c.placonuni, c.mulcon, c.fecfincon, c.tiemulcon, c.mulreuni,c.lapgarcon, c.lapgaruni,  c.codtco, a.cod_pro, a.cod_pro_ins,
				c.monmaxcon, c.pormaxcon,  c.obscon,  c.porejefiscon, t.nomtco, c.porejefincon, c.monejefincon,c.codasi,o.codobr,o.desobr,c.feccon,c.fecinicon,c.estcon,c.precon
				FROM sob_contrato c,sob_asignacion a,sob_obra o, sob_tipocontrato t
				WHERE c.codemp='".$ls_codemp."' AND a.codemp='".$ls_codemp."' AND o.codemp='".$ls_codemp."' AND c.codasi=a.codasi AND a.codobr=o.codobr AND c.codtco=t.codtco
				AND o.codobr like '".$ls_codobr."'	AND  o.desobr like '".$ls_desobr."' AND (c.estcon like '".$ls_estcon."' OR c.estcon=$ls_estado2) AND c.codcon like '".$ls_codcon."'
				AND a.codasi like '".$ls_codasi."' AND c.estcon<>3";	*/
	}		
	if ($ld_feccrecon!="")
	{
		$ls_sql=$ls_sql." AND c.feccon='".$ld_feccrecon."'";
	}
	
	if ($ld_fecinicon)
	{
		$ls_sql=$ls_sql." AND c.fecinicon='".$ld_fecinicon."'";
	}			
	
	$ls_sql=$ls_sql." ORDER BY $ls_campo $ls_orden";
	$rs_data=$io_sql->select($ls_sql);
	if($rs_data===false)
	{
		$is_msg_error="Error en select catalogo contrato".$io_funcion->uf_convertirmsg($io_sql->message);
		print $is_msg_error;
	}
	else
	{
		print "<table width=800 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td><a href=javascript:ue_ordenar('c.codcon','BUSCAR');><font color=#FFFFFF>Cod. Cont.</font></a></td>";
		print "<td><a href=javascript:ue_ordenar('c.codasi','BUSCAR');><font color=#FFFFFF>Cod. Asig</font></a></td>";
		print "<td><a href=javascript:ue_ordenar('o.codobr','BUSCAR');><font color=#FFFFFF>Cod. Obra</font></a></td>";
		print "<td><a href=javascript:ue_ordenar('o.desobr','BUSCAR');><font color=#FFFFFF>Descripci?n Obra</font></a></td>";
		print "<td><a href=javascript:ue_ordenar('c.feccon','BUSCAR');><font color=#FFFFFF>Fecha Creaci?n Cont.</font></a></td>";
		print "<td><a href=javascript:ue_ordenar('c.fecinicon','BUSCAR');><font color=#FFFFFF>Fecha Inicio Cont.</font></a></td>";
		print "<td><a href=javascript:ue_ordenar('c.estcon','BUSCAR');><font color=#FFFFFF>Estado</font></a></td>";
		print "<td><font color=#FFFFFF>Contabilizado</font></td>";

		print "</tr>";
		print "<br>";
		while(!$rs_data->EOF)
		{
			$lb_valido=true;
			$ls_codigo=$rs_data->fields["codcon"];
			$ls_desobr=$rs_data->fields["desobr"];
			$ls_estado=$io_funobr->uf_convertir_numeroestado($rs_data->fields["estcon"]);
			$ls_codest=$rs_data->fields["estcon"];
			$ls_codobr=$rs_data->fields["codobr"];
			$ld_monto=$rs_data->fields["monto"];
			$ls_placon=$rs_data->fields["placon"];				
			$ls_placonuni=$rs_data->fields["placonuni"];				
			$ls_mulcon=$rs_data->fields["mulcon"];					
			$ls_tiemulcon=$rs_data->fields["tiemulcon"];
			$ls_mulreuni=$rs_data->fields["mulreuni"];
			$ls_lapgarcon=$rs_data->fields["lapgarcon"];
			$ls_lapgaruni=$rs_data->fields["lapgaruni"];
			$ls_codtco=$rs_data->fields["codtco"];	
			$ls_monmaxcon=$rs_data->fields["monmaxcon"];			
			$ls_pormaxcon=$rs_data->fields["pormaxcon"];
			$ls_obscon=$rs_data->fields["obscon"];
			$ls_porejefiscon=$rs_data->fields["porejefiscon"];
			$ls_porejefincon=$rs_data->fields["porejefincon"];
			$ls_monejefincon=$rs_data->fields["monejefincon"];
			$ls_codasi=$rs_data->fields["codasi"];
			$ls_nomtco=	$rs_data->fields["nomtco"];
			$ls_codobr=	$rs_data->fields["codobr"];
			$ls_codpro=$rs_data->fields["cod_pro"];
			$ls_estspgscg=$rs_data->fields["estspgscg"];
			$ls_codproins=$rs_data->fields["cod_pro_ins"];
			$ls_estapr=$rs_data->fields["estapr"];
			$ls_feccrecon=$io_funcion->uf_convertirfecmostrar($rs_data->fields["feccon"]);
			$ls_fecinicon=$io_funcion->uf_convertirfecmostrar($rs_data->fields["fecinicon"]);
			$ls_fecfincon=$io_funcion->uf_convertirfecmostrar($rs_data->fields["fecfincon"]);			
			$ls_precon=$rs_data->fields["precon"];		
			$ls_existeant=$rs_data->fields["existeant"];
			if(($ls_opener=="anticipo")&&($ls_existeant!=""))
			{
				$lb_valido=false;
			}
			if($lb_valido)
			{
				print "<tr class=celdas-blancas align=center>";				
				print "<td ><a href=\"javascript: aceptar('$ls_codigo','$ls_desobr','$ls_estado','$ls_codest','$ld_monto','$ls_placon',
				'$ls_placonuni','$ls_mulcon','$ls_tiemulcon','$ls_mulreuni','$ls_lapgarcon','$ls_lapgaruni','$ls_codtco','$ls_monmaxcon','$ls_pormaxcon',
				'$ls_obscon','$ls_porejefiscon','$ls_porejefincon','$ls_monejefincon','$ls_codasi','$ls_feccrecon','$ls_fecinicon','$ls_nomtco','$ls_codobr',
				'$ls_codpro','$ls_codproins','$ls_fecfincon','$ls_precon','$ls_estapr');\">".$ls_precon.$ls_codigo."</a></td>";
				print "<td>".$ls_codasi."</td>";
				print "<td>".$ls_codobr."</td>";
				print "<td align=left>".$ls_desobr."</td>";
				print "<td>".$ls_feccrecon."</td>";
				print "<td>".$ls_fecinicon."</td>";			
				print "<td>".$ls_estado."</td>";		
				if($ls_estspgscg==0)
					print "<td></td>";
				else			
					print "<td><img src=../shared/imagebank/aprobado.gif width=15 height=15 border=0></td>";	
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
function aceptar(ls_codigo,ls_desobr,ls_estado,ls_codest,ld_monto,ls_placon,ls_placonuni,ls_mulcon,ls_tiemulcon,ls_mulreuni,ls_lapgarcon,ls_lapgaruni,
				ls_codtco,ls_monmaxcon,ls_pormaxcon,ls_obscon,ls_porejefiscon,ls_porejefincon,ls_monejefincon,ls_codasi,ls_feccrecon,
				ls_fecinicon,ls_nomtco,ls_codobr,ls_codpro,ls_codproins,ls_fecfincon,ls_precon,ls_estapr)
  {
    opener.ue_cargarcontrato(ls_codigo,ls_desobr,ls_estado,ls_codest,ld_monto,ls_placon,ls_placonuni,ls_mulcon,ls_tiemulcon,ls_mulreuni,ls_lapgarcon,ls_lapgaruni,
						ls_codtco,ls_monmaxcon,ls_pormaxcon,ls_obscon,ls_porejefiscon,ls_porejefincon,ls_monejefincon,ls_codasi,ls_feccrecon,
						ls_fecinicon,ls_nomtco,ls_codobr,ls_codpro,ls_codproins,ls_fecfincon,ls_precon,ls_estapr);
	close();
  }
  
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_contrato.php";
  f.submit();
  }
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
</html>