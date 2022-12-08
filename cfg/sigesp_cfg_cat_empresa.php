<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de Empresas</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css"  rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
	color: #006699;
}
-->
</style></head>

<body>
<?php
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_sql.php");

$in           = new sigesp_include();
$conn         = $in->uf_conectar();
$io_dsempresa = new class_datastore();
$io_sql       = new class_sql($conn);
$io_sql2      = new class_sql($conn);
$io_funciones= new class_funciones();
$arr          = $_SESSION["la_empresa"];
$ls_sql       = " SELECT * FROM sigesp_empresa ";
$rs_empresa   = $io_sql->select($ls_sql);
$data         = $rs_empresa;
if ($row=$io_sql->fetch_row($rs_empresa))
   {
	 $data=$io_sql->obtener_datos($rs_empresa);
	 $io_sql->free_result($rs_empresa);
   }
$arrcols            = array_keys($data);
$totcol             = count($arrcols);
$io_dsempresa->data = $data;
$totrow             = $io_dsempresa->getRowCount("codemp");

////////////////////////////////////////////////////
$ls_sql2     = " SELECT COUNT(*) as total FROM sigesp_cmp ";
$rs_empresa2 = $io_sql2->select($ls_sql2);
$data2       = $rs_empresa2;
$totalfilas  = 0;
if ($rs_empresa2===false)
   {
	 print "Error en sigesp_cmp";
   }
else
   {
	 if ($row2=$io_sql2->fetch_row($rs_empresa2))
        {
	 	  $data2=$io_sql2->obtener_datos($rs_empresa2);
	      $totalfilas=$data2["total"][1];
        }
     $io_sql2->free_result($rs_empresa2);
   }

$ls_codemp=$_SESSION["la_empresa"]["codemp"];
$ls_periodo=$_SESSION["la_empresa"]["periodo"];

$ls_sql3= " SELECT  COUNT(*) as totalaper                                                                                     ".
		  "   FROM  sigesp_cmp                                                                                                ".
		  "  WHERE  codemp='".$ls_codemp."' AND procede='SPGAPR' AND comprobante='0000000APERTURA' AND fecha='".$ls_periodo."'";
$rs_empresa=$io_sql2->select($ls_sql3);
$total=0;
if ($rs_empresa===false)
   {
	 print "Error en sigesp_cmp";
   }
else
   {
	 if ($row=$io_sql2->fetch_row($rs_empresa))
	    {
		  $total=$row["totalaper"];
	    }
     $io_sql2->free_result($rs_empresa);
   }

$ls_sql4      = " SELECT COUNT(*) as totalctascg FROM scg_cuentas WHERE codemp='".$ls_codemp."'";
$rs_empresa   = $io_sql->select($ls_sql4);
$total_ctascg = 0;
if($rs_empresa===false)
{
	print "Error en sigesp_cmp";
}
else
{
	if ($row=$io_sql->fetch_row($rs_empresa))
	   {
		 $total_ctascg=$row["totalctascg"];
	   }
   	   $io_sql->free_result($rs_empresa);
}

$ls_sql5      = " SELECT COUNT(*) as totalctaspg FROM spg_cuentas WHERE codemp='".$ls_codemp."'";
$rs_empresa   = $io_sql->select($ls_sql5);
$total_ctaspg = 0;
if($rs_empresa===false)
{
	print "Error en sigesp_cmp";
}
else
{
	if ($row=$io_sql->fetch_row($rs_empresa))
	   {
		 $total_ctaspg=$row["totalctaspg"];
	   }
   	   $io_sql->free_result($rs_empresa);
}
////////////////////////////////////////////////////
$ls_ctarep=$_SESSION["la_empresa"]["repcajchi"];

$ls_sql6= " SELECT  denominacion ".
		  "   FROM  scg_cuentas ".
		  "  WHERE  codemp='".$ls_codemp."' AND sc_cuenta='".$ls_ctarep."'";
$rs_empresa=$io_sql2->select($ls_sql6);
if ($rs_empresa===false)
   {
	 print "Error en sigesp_cmp";
   }
else
   {
	 if ($row=$io_sql2->fetch_row($rs_empresa))
	    {
		  $denoscg=$row["denominacion"];
	    }
     $io_sql2->free_result($rs_empresa);
   }
////////////////////////////////////////////////////

?>
<br>
<table width="500" border="0" cellpadding="1"  cellspacing="1" class="fondo-tabla" align="center">
<tr class="titulo-celda">
  <td height="22" colspan="5">Cat&aacute;logo de Empresas</td>
  </tr>
<tr class="titulo-celdanew">
<td height="22">Código</td>
<td height="22">Nombre</td>
<td height="22">Siglas</td>
<td height="22">Dirección</td>
<td height="22">Teléfono</td>
</tr>
<?php
$li_len1=$_SESSION["la_empresa"]["loncodestpro1"];
$li_len2=$_SESSION["la_empresa"]["loncodestpro2"];
$li_len3=$_SESSION["la_empresa"]["loncodestpro3"];
$li_len4=$_SESSION["la_empresa"]["loncodestpro4"];
$li_len5=$_SESSION["la_empresa"]["loncodestpro5"];
$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
for($z=1;$z<=$totrow;$z++)
{
	print "<tr class=celdas-blancas>";
	$codigo             = $data["codemp"][$z];
	$nombre             = $data["nombre"][$z];
	$nomres				= $data["nomres"][$z];
	$titulo             = $data["titulo"][$z];
	$direccion          = $data["direccion"][$z];
	$ciuemp             = $data["ciuemp"][$z];
	$estemp             = $data["estemp"][$z];
	$zonpos             = $data["zonpos"][$z];
	$telefono           = $data["telemp"][$z];
	$fax                = $data["faxemp"][$z];
	$email              = $data["email"][$z];
	$website            = $data["website"][$z];
    $ls_nomorgads       = $data["nomorgads"][$z];
	$fecha              = $data["periodo"][$z];
	$periodo            = $io_funciones->uf_convertirfecmostrar($fecha);
	$enero              = $data["m01"][$z];
	$febrero            = $data["m02"][$z];
	$marzo              = $data["m03"][$z];
	$abril              = $data["m04"][$z];
	$mayo               = $data["m05"][$z];
	$junio              = $data["m06"][$z];
	$julio              = $data["m07"][$z];
	$agosto             = $data["m08"][$z];
	$septiembre         = $data["m09"][$z];
	$octubre            = $data["m10"][$z];
	$noviembre          = $data["m11"][$z];
	$diciembre          = $data["m12"][$z];
	$tipocontabilidad   = $data["esttipcont"][$z];
	$planunico          = $data["formplan"][$z];
	$contabilidad       = $data["formcont"][$z];
	$ls_formcont1        = substr($contabilidad,0,15);
	$ls_formcont2        = substr($contabilidad,15,30);
	$pgasto             = $data["formpre"][$z];
	$pingreso           = $data["formspi"][$z];
	$activo             = $data["activo"][$z];
	$pasivo             = $data["pasivo"][$z];
	$ingreso            = $data["ingreso"][$z];
	$gasto              = $data["gasto"][$z];
    $resultado          = $data["resultado"][$z];
	$capital            = $data["capital"][$z];
	$deudor             = $data["orden_h"][$z];
	$acreedor           = $data["orden_d"][$z];
	$presupuestogasto   = $data["gasto_p"][$z];
	$presupuestoingreso = $data["ingreso_p"][$z];
	$resultadoactual    = $data["c_resultad"][$z];
	$resultanterior     = $data["c_resultan"][$z];
	$desestpro1         = $data["nomestpro1"][$z];
	$desestpro2         = $data["nomestpro2"][$z];
	$desestpro3         = $data["nomestpro3"][$z];
	$desestpro4         = $data["nomestpro4"][$z];
	$desestpro5         = $data["nomestpro5"][$z];
	$haciendaactivo     = $data["activo_h"][$z];
   	$haciendapasivo     = $data["pasivo_h"][$z];
	$haciendaresul      = $data["resultado_h"][$z];
	$fiscalgasto        = $data["gasto_f"][$z];
	$ingresofiscal      = $data["ingreso_f"][$z];
	$li_traspasos       = $data["estvaltra"][$z];
	$li_valnivel        = $data["vali_nivel"][$z];
	$ls_cuentabienes    = $data["soc_gastos"][$z];
	$ls_cuentaservicios = $data["soc_servic"][$z];
	$ls_estmodape       = $data["estmodape"][$z];
	$li_estdesiva       = $data["estdesiva"][$z];
    $li_precomprometer  = $data["estprecom"][$z];	
	$ls_codorgsig       = $data["codorgsig"][$z];
	$ls_rifemp          = $data["rifemp"][$z];
	$ls_nitemp          = $data["nitemp"][$z];
	$ls_ivss          = $data["nroivss"][$z];
	$li_numnivest       = $data["numniv"][$z];
	$li_estmodest       = $data["estmodest"][$z];
	$ld_salinipro       = $data["salinipro"][$z];
	$ld_salinipro       = number_format($ld_salinipro,2,',','.');
	$ld_salinieje       = $data["salinieje"][$z];
	$ld_salinieje       = number_format($ld_salinieje,2,',','.');
	$ls_numordcom       = $data["numordcom"][$z];	
	$ls_numordser       = $data["numordser"][$z];
	$ls_numsolpag       = $data["numsolpag"][$z];
	$ls_numlicemp       = $data["numlicemp"][$z];
    $ls_modgenret       = $data["modageret"][$z];
    $ls_concomiva       = $data["concomiva"][$z];
    $ls_estmodiva       = $data["estmodiva"][$z];
    $ls_cedben          = $data["cedben"][$z];
    $ls_nomben          = $data["nomben"][$z];
    $ls_scctaben        = $data["scctaben"][$z];
    $ls_tesoroactivo    = $data["activo_t"][$z];
    $ls_tesoropasivo    = $data["pasivo_t"][$z];
    $ls_tesororesul     = $data["resultado_t"][$z];
    $ls_ctafinanciera   = $data["c_financiera"][$z];
    $ls_ctafiscal       = $data["c_fiscal"][$z];
	$ls_codasiona       = $data["codasiona"][$z];
	$ls_confch          = $data["confi_ch"][$z];
	$ls_lonestpro1      = $data["loncodestpro1"][$z];
	$ls_lonestpro2      = $data["loncodestpro2"][$z];
	$ls_lonestpro3      = $data["loncodestpro3"][$z];
	$ls_lonestpro4      = $data["loncodestpro4"][$z];
	$ls_lonestpro5      = $data["loncodestpro5"][$z];
	$ls_conrecdoc       = $data["conrecdoc"][$z];
	$li_diacadche       = $data["diacadche"][$z];
	$ls_nomrep          = $data["nomrep"][$z];
	$ls_cedrep          = $data["cedrep"][$z];
	$ls_telfrep         = $data["telfrep"][$z];
	$ls_cargo       = $data["cargorep"][$z];
	$ls_estretiva       = $data["estretiva"][$z];
	$ls_clactacont       = $data["clactacon"][$z];
	$ls_empcons       = $data["estempcon"][$z]; 
	$ls_bdconsolida       = $data["basdatcon"][$z]; 
	$ls_codaltempcon       = $data["codaltemp"][$z];
	$ls_estcamemp=$data["estcamemp"][$z];
	$ls_estparsindis=$data["estparsindis"][$z];
	$ls_bdconscomp=$data["basdatcmp"][$z];
	$ls_confinstr       = $data["confinstr"][$z];
	$ls_intecred       = $data["estintcred"][$z]; 
	$ls_estmanant      = $data["estmanant"][$z];
	$ls_estpresing   = $data["estpreing"][$z];
	$ls_concommun= $data["concommun"][$z];
	$ls_estmodpartsep= $data["estmodpartsep"][$z];
	$ls_estmodpartsoc= $data["estmodpartsoc"][$z];
	$ls_confiva         = $data["confiva"][$z];
	$ls_casconmov         = $data["casconmov"][$z];
	$ls_estmodprog         = $data["estmodprog"][$z];	
	$ls_ctaresact = $data["ctaresact"][$z];
	$ls_ctaresant = $data["ctaresant"][$z];
	$li_dedconproben = $data["dedconproben"][$z];
	$li_contintmovban=$data["contintmovban"][$z];
	$li_valinimovban=$data["valinimovban"][$z];
	$ls_estaprsep = $data["estaprsep"][$z];
	$ls_estaprsoc = $data["estaprsoc"][$z];
	$ls_estaprcxp = $data["estaprcxp"][$z];
	$ls_sujpasesp = $data["sujpasesp"][$z];
	$ls_bloanu = $data["bloanu"][$z];
	$ls_estretmil = $data["estretmil"][$z];
	$ls_concommil = $data["concommil"][$z];
	$ls_estintban = $data["estintban"][$z];
	$ls_cueproacu = $data["cueproacu"][$z];
	$ls_cuedepamo = $data["cuedepamo"][$z];
	$ls_valclacon = $data["valclacon"][$z];
	$ls_valcomrd = $data["valcomrd"][$z];
	$ls_ctaejeprecie = $data["ctaejeprecie"][$z];
	$ls_scfordenacreedora = $data["scforden_h"][$z];
	$ls_scfordendeudora = $data["scforden_d"][$z];
	$ls_ctaconrepcajchi	= $data["repcajchi"][$z];
	$ls_estafenc	= $data["estafenc"][$z];
	$ls_capiva	= $data["capiva"][$z];
	$ls_parcapiva	= $data["parcapiva"][$z];
	$ls_estciesem	= $data["estciesem"][$z];
	$ls_ciesem1	= $data["ciesem1"][$z];
	$ls_ciesem2	= $data["ciesem2"][$z];
	$ls_estceniva	= $data["estceniva"][$z];
	$ls_codestprocen1	= $data["codestprocen1"][$z];
	$ls_codestprocen2	= $data["codestprocen2"][$z];
	$ls_codestprocen3	= $data["codestprocen3"][$z];
	$ls_codestprocen4	= $data["codestprocen4"][$z];
	$ls_codestprocen5	= $data["codestprocen5"][$z];
	$ls_estclacen	= $data["estclacen"][$z];
	$ls_codestprocen1=substr($ls_codestprocen1,(25-$li_len1),$li_len1);
	$ls_codestprocen2=substr($ls_codestprocen2,(25-$li_len2),$li_len2);
	$ls_codestprocen3=substr($ls_codestprocen3,(25-$li_len3),$li_len3);
	$ls_codestprocen4=substr($ls_codestprocen4,(25-$li_len4),$li_len4);
	$ls_codestprocen5=substr($ls_codestprocen5,(25-$li_len5),$li_len5);
	$ls_estspgdecimal= $data["estspgdecimal"][$z];
	$ls_nivapro         = $data["nivapro"][$z];
	$ls_estenvcor       = $data["envcorsup"][$z];			
	$ls_estcomobr       = $data["estcomobr"][$z];
	$ls_numrefcarord    = $data["numrefcarord"][$z];			
	$ls_estretislr    = $data["estretislr"][$z];
	$ls_estcencos     = $data["estcencos"][$z];
	$ls_inicencos     = $data["inicencos"][$z];
	$ls_fincencos     = $data["fincencos"][$z];
	$ls_cencosact 	  = $data["cencosact"][$z];
	$ls_cencospas 	  = $data["cencospas"][$z];
	$ls_cencosing 	  = $data["cencosing"][$z];
	$ls_cencosgas 	  = $data["cencosgas"][$z];
	$ls_cencosres 	  = $data["cencosres"][$z];
	$ls_cencoscap 	  = $data["cencoscap"][$z];
	
	
	print "<td align=center><a href=\"javascript: aceptar_empresa('$codigo','$nombre','$nomres','$titulo','$direccion','$ciuemp',".
		  "'$estemp','$zonpos','$telefono','$fax','$email','$website','$periodo','$enero','$febrero','$marzo','$abril','$mayo',".
		  "'$junio','$julio','$agosto','$septiembre','$octubre','$noviembre','$diciembre','$tipocontabilidad','$planunico',".
		  "'$contabilidad','$pgasto','$pingreso','$activo','$pasivo','$ingreso','$gasto','$resultado','$capital','$deudor',".
		  "'$acreedor','$presupuestogasto','$presupuestoingreso','$desestpro1','$desestpro2','$desestpro3','$desestpro4',".
		  "'$desestpro5','$resultadoactual','$resultanterior','$haciendaactivo','$haciendapasivo','$haciendaresul','$fiscalgasto',".
		  "'$ingresofiscal','$li_traspasos','$li_valnivel','$ls_cuentabienes','$ls_cuentaservicios','$ls_estmodape','$li_estdesiva',".
		  "'$totalfilas','$total','$li_precomprometer','$total_ctascg','$total_ctaspg','$ls_codorgsig','$ls_rifemp','$ls_nitemp',".
		  "'$li_numnivest','$li_estmodest','$ld_salinipro','$ld_salinieje','$ls_numordcom','$ls_numordser','$ls_numsolpag',".
		  "'$ls_nomorgads','$ls_numlicemp','$ls_modgenret','$ls_concomiva','$ls_estmodiva','$ls_cedben','$ls_nomben','$ls_scctaben',".
		  "'$ls_tesoroactivo','$ls_tesoropasivo','$ls_tesororesul','$ls_ctafinanciera','$ls_ctafiscal','$ls_codasiona',".
		  "'$ls_lonestpro1','$ls_lonestpro2','$ls_lonestpro3','$ls_lonestpro4','$ls_lonestpro5','$ls_conrecdoc','$li_diacadche',".
		  "'$ls_ivss','$ls_nomrep','$ls_cedrep','$ls_telfrep','$ls_cargo','$ls_estretiva','$ls_clactacont','$ls_empcons',".
		  "'$ls_bdconsolida','$ls_codaltempcon','$ls_estcamemp','$ls_estparsindis','$ls_bdconscomp','$ls_confinstr','$ls_intecred',".
		  "'$ls_estmanant','$ls_estpresing','$ls_concommun','$ls_estmodpartsep','$ls_estmodpartsoc','$ls_confiva','$ls_casconmov',".
		  "'$ls_estmodprog','$ls_confch','$ls_ctaresact','$ls_ctaresant','$li_dedconproben','$ls_estaprsep','$ls_sujpasesp',".
		  "'$ls_bloanu','$ls_estretmil','$ls_concommil','$li_contintmovban',".
		  "'$li_valinimovban',$ls_estintban,'$ls_cueproacu','$ls_cuedepamo','$ls_valclacon','$ls_valcomrd','$ls_ctaejeprecie',".
		  "'$ls_estaprsoc','$ls_estaprcxp','$ls_scfordenacreedora','$ls_scfordendeudora','$ls_ctaconrepcajchi','$denoscg',".
		  "'$ls_estafenc','$ls_capiva','$ls_parcapiva','$ls_estciesem','$ls_ciesem1','$ls_ciesem2','$ls_estceniva','$ls_codestprocen1',".
		  "'$ls_codestprocen2','$ls_codestprocen3','$ls_codestprocen4','$ls_codestprocen5','$ls_estclacen','$ls_modalidad',".
		  "'$ls_estspgdecimal','$ls_nivapro','$ls_estenvcor','$ls_estcomobr','$ls_numrefcarord','$ls_estretislr','$ls_estcencos','$ls_inicencos','$ls_fincencos',".
		  "'$ls_cencosact','$ls_cencospas','$ls_cencosing','$ls_cencosgas','$ls_cencosres','$ls_cencoscap','$ls_formcont1','$ls_formcont2');\">".$codigo."</a></td>";


	print "<td align=center>".$nombre."</td>";
	print "<td align=center>".$titulo."</td>";
	print "<td align=left>".$direccion."</td>";
	print "<td align=right>".$telefono."</td>";
	print "</tr>";			
}

?>
</table>
</body>
<script language="JavaScript">
  function aceptar_empresa(codigo,nombre,nomres,titulo,direccion,ciuemp,estemp,zonpos,telefono,fax,email,website,periodo,enero,
  						   febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,tipocontabilidad,
						   planunico,contabilidad,pgasto,pingreso,activo,pasivo,ingreso,gasto,resultado,capital,acreedor,deudor,
						   presupuestogasto,presupuestoingreso,desestpro1,desestpro2,desestpro3,desestpro4,desestpro5,
						   resultadoactual,resultadoanterior,haciendaactivo,haciendapasivo,haciendaresul,fiscalgasto,ingresofiscal,
						   traspasos,valnivel,cuentabienes,cuentaservicios,estmodape,estdesiva,totalfilas,total,li_precomprometer,
						   total_ctascg,total_ctaspg,codorgsig,rifemp,nitemp,niveles,li_estmodest,salinipro,salinieje,numordcom,
						   numordser,numsolpag,ls_nomorgads,ls_numlicemp,ls_modgenret,concomiva,estmodiva,cedben,nomben,scctaben,
						   tesoroactivo,tesoropasivo,tesororesul,ctafinanciera,ctafiscal,ls_codasiona,ls_lonestpro1,ls_lonestpro2,
						   ls_lonestpro3,ls_lonestpro4,ls_lonestpro5,ls_conrecdoc,li_diacadche,li_ivss,ls_nomrep,ls_cedrep,
						   ls_telfrep,ls_cargo,ls_estretiva,ls_clactacont,ls_empcons,ls_bdconsolida,ls_codaltempcon,ls_estcamemp,
						   ls_estparsindis,ls_bdconscomp,ls_confinstr,ls_intecred,ls_estmanant,ls_estpresing,ls_concommun,
  						   ls_estmodpartsep,ls_estmodpartsoc,ls_confiva,ls_casconmov,ls_estmodprog,ls_confch,as_ctaresact,as_ctaresant,
  						   li_dedconproben,ls_estaprsep,ls_sujpasesp,ls_bloanu,ls_estretmil,ls_concommil,li_contintmovban,
						   li_valinimovban,ls_estintban,ls_cueproacu,ls_cuedepamo,ls_valclacon,ls_valcomrd,ls_ctaejeprecie,
						   ls_estaprsoc,ls_estaprcxp,ls_scfordenacreedora,ls_scfordendeudora,ls_ctaconrepcajchi,ls_denom,ls_estafenc,
						   ls_capiva,ls_parcapiva,ls_estciesem,ls_ciesem1,ls_ciesem2,ls_estceniva,ls_codestprocen1,ls_codestprocen2,
						   ls_codestprocen3,ls_codestprocen4,ls_codestprocen5,ls_estclacen,ls_modalidad,ls_estspgdecimal,ls_nivapro,
						   ls_estenvcor,ls_estcomobr,ls_numrefcarord,ls_estretislr,ls_estcencos,ls_inicencos,ls_fincencos,
						   ls_cencosact,ls_cencospas,ls_cencosing,ls_cencosgas,ls_cencosres,ls_cencoscap,ls_formcont1,ls_formcont2)
						  
  { 
  

    fop = opener.document.form1;	
    fop.txtcodigo.value    = codigo;
    fop.txtcodigo.readOnly = true;
	fop.txtnombre.value    = nombre;
	fop.txtnomres.value    = nomres;
	fop.txttitulo.value    = titulo;
	fop.txtrif.value       = rifemp;
	fop.txtnit.value       = nitemp;
	fop.txtivss.value       = li_ivss;
    fop.txtdireccion.value = direccion;
    fop.txtciuemp.value    = ciuemp;
    fop.txtestemp.value    = estemp;
    fop.txtzonpos.value    = zonpos;
	fop.txttelefono.value  = telefono;
    fop.txtfax.value       = fax;
	fop.txtemail.value     = email;
    fop.txtwebsite.value   = website;
	fop.txtnumlicemp.value = ls_numlicemp;
	fop.txtnomorgads.value = ls_nomorgads;
	fop.txtperiodo.value   = periodo;
	fop.cmbnumniv.value    = niveles;
	fop.hidnumniv.value    = niveles;
	fop.txtnomrep.value    =ls_nomrep;
	fop.txtcedrep.value    =ls_cedrep;
	fop.txttelfrep.value   =ls_telfrep;
	fop.txtcargo.value   =ls_cargo;
	fop.valinimovban.value  =li_valinimovban;
	fop.txtcodaltempcon.value=ls_codaltempcon;
	fop.txtinicencos.value=ls_inicencos;
	fop.txtfincencos.value=ls_fincencos;
	fop.hidcencosact.value=ls_cencosact;
	fop.hidcencospas.value=ls_cencospas;
	fop.hidcencosing.value=ls_cencosing;
	fop.hidcencosgas.value=ls_cencosgas;
	fop.hidcencosres.value=ls_cencosres;
	fop.hidcencoscap.value=ls_cencoscap;
	fop.chkdedconproben.checked=false;
	if(li_dedconproben=="1")
	{
		 fop.chkdedconproben.checked=true;
	}
	if (enero=="1")
	   {
	     fop.chkenero.checked=true;
	   }  
	if (febrero=="1")
	   {
	     fop.chkfebrero.checked=true;
	   }
	   if (marzo=="1")
	   {
	     fop.chkmarzo.checked=true;
	   }
	   if (abril=="1")
	   {
	     fop.chkabril.checked=true;
	   }
	   if (mayo=="1")
	   {
	     fop.chkmayo.checked=true;
	   }
	   if (junio=="1")
	   {
	     fop.chkjunio.checked=true;
	   }
	   if (julio=="1")
	   {
	     fop.chkjulio.checked=true;
	   }
	   if (agosto=="1")
	   {
	     fop.chkagosto.checked=true;
	   }
	   if (septiembre=="1")
	   {
	     fop.chkseptiembre.checked=true;
	   }
	   if (octubre=="1")
	   {
	     fop.chkoctubre.checked=true;
	   }
	   if (noviembre=="1")
	   {
	     fop.chknoviembre.checked=true;
	   }
	   if (diciembre=="1")
	   {
	     fop.chkdiciembre.checked=true;
	   }
	   
	   if(li_contintmovban=="1")
	   {
	   	 fop.contintmovban.checked=true
	   }
	   else
	   {
	   	 fop.contintmovban.checked=false
	   }
	  
	   
	   if (tipocontabilidad==1)
	      {
		    fop.hidtipcont.value=tipocontabilidad;
			fop.radiocontabilidad[0].checked=true;
		    fop.radiocontabilidad[0].disabled=true;
		    fop.radiocontabilidad[1].disabled=true;

		  }
	  else
	      {
		    fop.hidtipcont.value=tipocontabilidad;
			fop.radiocontabilidad[1].checked=true;
		    fop.radiocontabilidad[0].disabled=true;
		    fop.radiocontabilidad[1].disabled=true;
		  }	  
    if (ls_modgenret=='B')
       {
         fop.radiocmp[1].checked = true;
	   }	
    else
       {
         fop.radiocmp[0].checked = true;
       }
	 if (ls_estretiva=='C')
       {
         fop.radiocmpiva[0].checked = true;
       }	
    else
       {
         if (ls_estretiva=='B') 
		 { 
         	fop.radiocmpiva[1].checked = true;
         }
	   }
	 if (ls_estretmil=='C')
       {
         fop.radiocmpmil[0].checked = true;
       }	
    else
       {
         if (ls_estretmil=='B') 
		 { 
         	fop.radiocmpmil[1].checked = true;
         }
	   }
    if (ls_estcamemp=="1")
	   {
	     fop.chkcamemp.checked=true;
	   }
    fop.txtbdconso.value            = ls_bdconsolida;
    fop.txtbdconso.value            = ls_bdconsolida;
	fop.txtconcommil.value          = ls_concommil; 
	if (ls_empcons=="1")
	   { 
	     fop.chkempconsolidadora.checked=true;
	   }
    else
	  { 
	     fop.chkempconsolidadora.checked=false;
	  }
	 if (ls_clactacont=="1")
	   {
	     fop.chkclactacont.checked=true;
	   }
	 if (ls_estparsindis=="1")
	   {
		 fop.chkparnodis.checked=true;
	   } 
	 fop.txtbdintcmp.value=ls_bdconscomp;
	 if (ls_bdconscomp!="")
	   {
		 fop.chkintcompret.checked=true; 
	   }
	   else
	   { 
	      fop.txtbdintcmp.disabled="";
	      fop.txtbdintcmp.disabled=true;
	   }
	 if (ls_intecred=="1")
	   {
		 fop.chkintecred.checked=true;
	   }
	 else
	  {
		 fop.chkintecred.checked=false; 
	  }
	  if (ls_estcencos=="1")
	   {
		 fop.chkmancencoscon.checked=true;
	   }
	 else
	  {
		 fop.chkmancencoscon.checked=false; 
	  }    
	fop.txtplanunico.value          = planunico;
    //fop.txtcontabilidad.value       = contabilidad;
	fop.txtcontabilidad1.value       =ls_formcont1;
	fop.txtcontabilidad2.value       =ls_formcont2;
	fop.txtpgasto.value             = pgasto;
    fop.txtpingreso.value           = pingreso;
	fop.txtactivo.value             = activo;
	fop.txtpasivo.value             = pasivo;
	fop.txtingreso.value            = ingreso;
  	fop.txthaciendaactivo.value     = haciendaactivo;
   	fop.txthaciendapasivo.value     = haciendapasivo;
	fop.txthaciendaresul.value      = haciendaresul;
	fop.txtfiscalgasto.value        = fiscalgasto;
	fop.txtingresofiscal.value      = ingresofiscal;
	fop.txtgasto.value              = gasto;
    fop.txtresultado.value          = resultado;
	fop.txtcapital.value            = capital;
   	fop.txtordendeudor.value        = deudor;
	fop.txtordenacreedor.value      = acreedor;
	fop.txtscfordendeudora.value    = ls_scfordendeudora;
	fop.txtscfordenacreedora.value  = ls_scfordenacreedora;
	fop.txtpresupuestogasto.value   = presupuestogasto;
	fop.txtpresupuestoingreso.value = presupuestoingreso;
	fop.txtresultadoactual.value    = resultadoactual;
	fop.txtresultadoanterior.value  = resultadoanterior;
	fop.txtctaresact.value 			= as_ctaresact;
	fop.txtctaresant.value 			= as_ctaresant;
	fop.txtdesestpro1.value         = desestpro1;
	fop.txtdesestpro2.value         = desestpro2;
	fop.txtdesestpro3.value         = desestpro3;
	fop.txtdesestpro4.value         = desestpro4;
	fop.txtdesestpro5.value         = desestpro5;
	fop.cmbvalidacion.value         = valnivel;
	fop.txtcuentabienes.value       = cuentabienes;
	fop.txtcuentaservicios.value    = cuentaservicios;
	fop.txtnumordcom.value          = numordcom;
	fop.txtnumordser.value          = numordser;
	fop.txtnumsolpag.value          = numsolpag;
	fop.conrecdoc.value        = ls_conrecdoc;
	if (li_estmodest==1)
	   {
		fop.radioestructura[0].checked=true;
		fop.txtlonestpro1.value    = ls_lonestpro1;
		fop.txtlonestpro2.value    = ls_lonestpro2;
		fop.txtlonestpro3.value    = ls_lonestpro3;
	   }
    else
	   {
		fop.radioestructura[1].checked=true;
		fop.txtlonestpro1.value    = ls_lonestpro1;
		fop.txtlonestpro2.value    = ls_lonestpro2;
		fop.txtlonestpro3.value    = ls_lonestpro3;
		fop.txtlonestpro4.value    = ls_lonestpro4;
		fop.txtlonestpro5.value    = ls_lonestpro5;
	   }	
	fop.hidestmodest.value = li_estmodest;
	if (traspasos==1)
	   {
	     fop.chkvalidacion.checked=true;   
	   }
		fop.radioestmodape[0].checked=false;
		fop.radioestmodape[1].checked=false;
   if (estmodape==0)
	  {
		fop.radioestmodape[0].checked=true;
	  }
  else
	  {
		fop.radioestmodape[1].checked=true;
	  }	
	   fop.hidestmodape.value=estmodape;   
  if (estmodiva=="1")
	  {
	    fop.chkestmodiva.checked=true;
	  }
 else
	  {
	     fop.chkestmodiva.checked=false;
	  }
	 if (ls_confch=='0')
       {
         fop.radiocheqauto[0].checked = true;
       }	
    else
       {
         fop.radiocheqauto[1].checked = true;
       }  
	if (totalfilas>0)
	   {
		 
		 if(total_ctascg>0)
		 {
			fop.txtplanunico.readOnly=true;
		 	fop.txtcontabilidad1.readOnly=true;
			fop.hid_roscg.value="readonly";
		 }
		 else
		 {
			 fop.hid_roscg.value="";
		 }
		 if(total_ctaspg>0)	
		 {
		 	fop.txtpgasto.readOnly = true;
		    fop.hid_rospg.value    = "readonly";
	     }
		  else
		 {
			 fop.hid_rospg.value="";
		 }
		 fop.txtpingreso.readOnly=true;
       }
	if (totalfilas>0 || total_ctascg>0 || total_ctaspg>0 || total>0)
	   {
	     fop.hiddisabled.value = "1";
	   }
	if (total>0) 
	   {
	  	 fop.modaper.value=true;
	   }
	if (li_precomprometer==1)
	   {
	     fop.chkprecomprometer.checked=true;
	   }
	else
	   {
	     fop.chkprecomprometer.checked=false;
	   }
    if (estmodiva=="1")
	  {
	    fop.chkestmodiva.checked=true;
	  }
    else
	  {
	     fop.chkestmodiva.checked=false;
	  }
      
	fop.txtsalinipro.value     = salinipro;
	fop.txtsalinieje.value     = salinieje;
	fop.txtcodorg.value        = codorgsig;
	fop.txtconcomiva.value     = concomiva;
	fop.txtcedben.value        = cedben;
	fop.txtcedben.readOnly     = true;
	fop.txtnomben.value        = nomben;
	fop.txtnomben.readOnly     = true;
	fop.txtscctaben.value      = scctaben;
	fop.txtscctaben.readOnly   = true;
  	fop.txttesoroactivo.value  = tesoroactivo;
   	fop.txttesoropasivo.value  = tesoropasivo;
	fop.txttesororesul.value   = tesororesul;
   	fop.txtctafinanciera.value = ctafinanciera;
	fop.txtctafiscal.value     = ctafiscal;
	fop.txtcodasiona.value     = ls_codasiona;
	fop.txtconcommun.value     = ls_concommun;
	fop.hidestatus.value       = 'GRABADO';
	fop.txtcueproacu.value     = ls_cueproacu;
	fop.txtcuedepamo.value     = ls_cuedepamo;
	fop.txtctaconrepcajchi.value = ls_ctaconrepcajchi;
	fop.txtdencuentascg.value = ls_denom;
	if (li_diacadche!=0 && li_diacadche!='')
	   {
	     fop.txtdiacadche.value = li_diacadche;
	   }
	if (ls_confinstr=='V')
    {
         fop.radioinstr[0].checked = true;
    }	
    if (ls_confinstr=='N')
    {
         fop.radioinstr[1].checked = true;
    }  
	if (ls_confinstr=='A')
    {
         fop.radioinstr[2].checked = true;
    }
	if(ls_estmanant==1)
	{
		fop.chkestmanant.checked=true;
	}
	if (ls_estpresing=="1")
	   { 
		 fop.chkpresing.checked=true;
	   }
	 else
	  {
		 fop.chkpresing.checked=false; 
	  }
	if (ls_estmodpartsep=="1")
	{
		fop.chkestmodpartsep.checked=true;
	}
    else
	{ 
		fop.chkestmodpartsep.checked=false;
	}
	if (ls_estmodpartsoc=="1")
	{
		fop.chkestmodpartsoc.checked=true;
	}
    else
	{ 
		fop.chkestmodpartsoc.checked=false;
	}
	if (ls_confiva=='P')
    {
         fop.radioiva[0].checked = true;
    }	
    else if (ls_confiva=='C')
    {
         fop.radioiva[1].checked = true;
    }
    if (ls_numrefcarord=="1")
    {
	  fop.chknumrefcarord.checked=true;
    }
	if (ls_casconmov=="1")
    {
	  fop.chkcasconmov.checked=true;
    }
    if (ls_estmodprog=="1")
    {
	  fop.chkestmodprog.checked=true;
    }
	if(ls_estaprsep=="1")
	{
		 fop.chkestaprsep.checked=true;
	}
	else
	{
		 fop.chkestaprsep.checked=false;
	}
	if(ls_estaprsoc=="1")
	{
		 fop.chkestaprsoc.checked=true;
	}
	else
	{
		 fop.chkestaprsoc.checked=false;
	}
	if(ls_estaprcxp=="1")
	{
		 fop.chkestaprcxp.checked=true;
	}
	else
	{
		 fop.chkestaprcxp.checked=false;
	}
	if(ls_sujpasesp=="1")
	{
		 fop.chksujpasesp.checked=true;
	}
	else
	{
		 fop.chksujpasesp.checked=false;
	}
	if(ls_bloanu=="1")
	{
		 fop.chkbloanu.checked=true;
	}
	else
	{
		 fop.chkbloanu.checked=false;
	}
	if(ls_estintban=="1")
	{
		 fop.chkestintban.checked=true;
	}
	else
	{
		 fop.chkestintban.checked=false;
	}
	if(ls_valclacon=="1")
	{
		 fop.chkvalclacon.checked=true;
	}
	else
	{
		 fop.chkvalclacon.checked=false;
	}
	if(ls_valcomrd=="1")
	{
		 fop.chkvalcomrd.checked=true;
	}
	else
	{
		 fop.chkvalcomrd.checked=false;
	}
	if(ls_estretislr=="1")
	{
		 fop.chkestretislr.checked=true;
	}
	else
	{
		 fop.chkestretislr.checked=false;
	}
	if(fop.txtctaejeprecie != null)
	{
	 fop.txtctaejeprecie.value = ls_ctaejeprecie;
	}
	if(ls_capiva=="1")
	{
		 fop.chkcapiva.checked=true;
	}
	else
	{
		 fop.chkcapiva.checked=false;
	}
	if(ls_estciesem=="1")
	{
		 fop.chkestciesem.checked=true;
		 fop.chkestciesem.readonly=true;
	}
	else
	{
		 fop.chkestciesem.checked=false;
	}
	fop.hidciesem1.value=ls_ciesem1;
	fop.hidciesem2.value=ls_ciesem2;
	fop.cmbnc.value= ls_estafenc;
	fop.txtparcapiva.value= ls_parcapiva;
	fop.estcapiva.value= ls_estceniva;
	if(ls_estceniva=="1")
	{
		 fop.chkestcapiva.checked=true;
	}
	else
	{
		 fop.chkestcapiva.checked=false;
	}
	fop.txtcodestpro1.value= ls_codestprocen1;
	fop.txtcodestpro2.value= ls_codestprocen2;
	fop.txtcodestpro3.value= ls_codestprocen3;
	if(ls_modalidad==2)
	{
		fop.txtcodestpro4.value= ls_codestprocen4;
		fop.txtcodestpro5.value= ls_codestprocen5;
	}

	if(ls_estspgdecimal=="1"){
		fop.chkestspgdecimal.checked=true;
	}
	if(ls_nivapro=="1"){
		fop.chkestnivapro.checked=true;
	}
	if(ls_estenvcor=="1"){
		fop.chkestenvcor.checked=true;
	}
	if(ls_estcomobr=="1"){
		fop.chkestcomobr.checked=true;
	}
	close();
	fop.submit();
  }
</script>
</html>