<?php 
session_start(); 
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";		
}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();
    
	$dat=$_SESSION["la_empresa"];
	$ls_empresa=$dat["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SPG";
	$ls_ventanas="sigesp_spg_p_progrep.php";

	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;
	
	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
		}
		else
		{
			$ls_permisos=$_POST["permisos"];
		}
	}
	else
	{
		$ls_permisos=$io_seguridad->uf_sss_select_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas);
	}
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey)){
		window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ 
		return false; 
		} 
		} 
	}
</script><title>Programacion de Reportes</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="styleshee t" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="javascript1.2" src="js/valida_tecla_grid.js"></script>

<link rel='stylesheet' type='text/css' href='../base/librerias/js/ext/resources/css/ext-all.css'/>
<script type='text/javascript' src='../base/librerias/js/ext/adapter/ext/ext-base.js'></script>
<script type='text/javascript' src='../base/librerias/js/ext/ext-all.js'></script>
<script type='text/javascript' src='../base/librerias/js/ext/adapter/locale/ext-lang-es.js'></script>
<script type='text/javascript' src='../base/librerias/js/general/sigesp_lib_funciones.js'></script>
<script type='text/javascript' src='../base/librerias/js/general/json2.js'></script>
<script type='text/javascript' src='../base/librerias/js/general/funciones.js'></script>
<script type="text/javascript" src="js/sigesp_programacionreporte.js"></script> 
<style type="text/css">
<!--
.Estilo2 {font-size: 15px}
.Estilo3 {font-size: 11px}
-->
</style>
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {font-weight: bold}
-->
</style>
</head>
<body>
  <table width="798" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
    <tr>
      <td width="1220" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="798" height="40"></td>
    </tr>
	<tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Contabilidad Presupuestaria de Gasto</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  	</tr>
    <tr>
      <td height="20" class="toolbar">&nbsp;</td>
    </tr>
    <tr>
      <td height="20" class="toolbar">
      <img src="../shared/imagebank/tools20/espacio.gif" width="4" height="20">
      <a href="javascript:ue_procesar();"><img src="../shared/imagebank/tools20/ejecutar.gif" title="Procesar" alt="Realizar programacion..."  width="20" height="20" border="0"> </a>
      <a href="javascript:ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a>
      <img src="../shared/imagebank/tools20/ayuda.gif"  title="Ayuda" alt="Ayuda" width="20" height="20"></td>
    </tr>
  </table>
  <p>
<?php
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_fecha.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_sigesp_int.php");
	require_once("../shared/class_folder/class_sigesp_int_scg.php");
	require_once("../shared/class_folder/class_sigesp_int_spg.php");
	require_once("../shared/class_folder/class_sigesp_int_spi.php");
	require_once("sigesp_spg_class_progrep.php");
	require_once("../shared/class_folder/grid_param.php");
	$io_include = new sigesp_include();
	$io_connect= $io_include->uf_conectar();
	$io_sql=new class_sql($io_connect);
	$io_msg=new class_mensajes();
	$io_function=new class_funciones();
    $io_fecha=new class_fecha();
	$io_class_progrep=new sigesp_spg_class_progrep();
	$sig_int=new class_sigesp_int();
	$int_spg=new class_sigesp_int_spg();
	$ds_progrep=new class_datastore();
	$io_class_grid=new grid_param();

	if(array_key_exists("operacion",$_POST))
	{
	  $ls_operacion=$_POST["operacion"];
	}
	else
	{
	  $ls_operacion="";
	}
	
	if	(array_key_exists("codestpro1",$_POST))
	{
	  $ls_codestpro1=$_POST["codestpro1"];
	}
	else
	{
	  $ls_codestpro1="";
	}
	
	if	(array_key_exists("denestpro1",$_POST))
	{
	  $ls_denestpro1=$_POST["denestpro1"];
	}
	else
	{
	  $ls_denestpro1="";
	}	
	
	if	(array_key_exists("codestpro2",$_POST))
	{
	  $ls_codestpro2=$_POST["codestpro2"];
	}
	else
	{
	  $ls_codestpro2="";
	}
		
	if	(array_key_exists("denestpro2",$_POST))
	{
	  $ls_denestpro2=$_POST["denestpro2"];
	}
	else
	{
	  $ls_denestpro2="";
	}		
	
	if	(array_key_exists("codestpro3",$_POST))
	{
	  $ls_codestpro3=$_POST["codestpro3"];
	}
	else
	{
	  $ls_codestpro3="";
	}
	
	if	(array_key_exists("denestpro3",$_POST))
	{
	  $ls_denestpro3=$_POST["denestpro3"];
	}
	else
	{
	  $ls_denestpro3="";
	}			
	if	(array_key_exists("codestpro4",$_POST))
	{
	  $ls_codestpro4=$_POST["codestpro4"];
	}
	else
	{
	  $ls_codestpro4="";
	}
	
	if	(array_key_exists("denestpro4",$_POST))
	{
	  $ls_denestpro4=$_POST["denestpro4"];
	}
	else
	{
	  $ls_denestpro4="";
	}
	if	(array_key_exists("codestpro5",$_POST))
	{
	  $ls_codestpro5=$_POST["codestpro5"];
	}
	else
	{
	  $ls_codestpro5="";
	}
	
	if	(array_key_exists("denestpro5",$_POST))
	{
	  $ls_denestpro5=$_POST["denestpro5"];
	}
	else
	{
	  $ls_denestpro5="";
	}
	if  (array_key_exists("estcla",$_POST))
	{
		$ls_estcla=$_POST["estcla"];
	}
	else
	{
		$ls_estcla="";
	}	
	
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if (($ls_permisos)||($ls_logusr=="PSEGIS"))
	{
		print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	}
	else
	{
		print("<script language=JavaScript>");
		print(" location.href='sigespwindow_blank.php'");
		print("</script>");
	}
	//////////////////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////
 ?>
  </p>
  <br>
  <br>
  	<form name="form1" method="post" action="">
    <table width="800"  border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
		<tr>
              <td height="20" colspan="3" class="titulo-ventana"><div align="center">Programaci&oacute;n de Reporte </div></td>
        </tr>
        <tr>
              <td height="18" colspan="3"><span class="Estilo2"></span></td>
        </tr>
        <tr>
  <?php
	  if ($ls_codrep=='0406') {
	   	$ej_f0704="selected";
		$ej_f0705="";
		$ej_f0707="";
	  }
	  elseif ($ls_codrep=='0704') {
	    $ej_f0704="selected";
		$ej_f0705="";
		$ej_f0707="";
	  }
	  elseif ($ls_codrep=='0705') {
	    $ej_f0704="selected";
		$ej_f0705="";
		$ej_f0707="";
	  }
	  
	        
	?>
              <td width="143" height="21"><div align="right">Reporte</div></td>
              <td colspan="2">
              	<select name="cmbrep" id="select">
                	<option value="0704" <?php  print $ej_f0704 ?>>ONAPRE - Instructivo 8 - Ejecucion Trimestral</option>
                	<option value="0705" <?php  print $ej_f0705 ?>>ONAPRE - Instructivo 8 - Consolidado Ejecucion Trimestral</option>
                	<option value="0707" <?php  print $ej_f0707 ?>>ONAPRE - Instructivo 8 - Estado de Resultado</option>
                </select>
      </tr>
            <tr>
      <?php
         $la_empresa    =  $_SESSION["la_empresa"];
		 $li_estmodest  = $la_empresa["estmodest"];
		 $ls_NomEstPro1 = $la_empresa["nomestpro1"];
		 $ls_NomEstPro2 = $la_empresa["nomestpro2"];
		 $ls_NomEstPro3 = $la_empresa["nomestpro3"];
		 $ls_NomEstPro4 = $la_empresa["nomestpro4"];
		 $ls_NomEstPro5 = $la_empresa["nomestpro5"];

         $ls_loncodestpro1 = $la_empresa["loncodestpro1"]+10;
		 $ls_loncodestpro2 = $la_empresa["loncodestpro2"]+10;
		 $ls_loncodestpro3 = $la_empresa["loncodestpro3"]+10;
		 $ls_loncodestpro4 = $la_empresa["loncodestpro4"]+10;
		 $ls_loncodestpro5 = $la_empresa["loncodestpro5"]+10;
	  ?>
            <td height="20"><div align="right"><span class="Estilo3">
                </span><?php print $ls_NomEstPro1;?></div>
                  <div align="left"></div></td>
              <td colspan="2">
                <input name="codestpro1" type="text" id="codestpro12" style="text-align:center" value="<?php print $ls_codestpro1 ?>" size="<?php print $ls_loncodestpro1; ?>" maxlength="<?php print $ls_loncodestpro1; ?>" readonly>
                <a href="javascript:catalogo_estpro1();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a>
                <input name="denestpro1" type="text" class="sin-borde" id="denestpro1" value="<?php print $ls_denestpro1 ?>" size="45">
                <div align="right"></div>
                <div align="center"> </div></td>
            </tr>
            <tr class="formato-blanco">
              <td height="20"><div align="right"><?php print $ls_NomEstPro2;?></div></td>
              <td colspan="2"><input name="codestpro2" type="text" id="codestpro2" style="text-align:center" value="<?php print $ls_codestpro2 ?>" size="<?php print $ls_loncodestpro2; ?>" maxlength="<?php print $ls_loncodestpro2; ?>" readonly>
                  <a href="javascript:catalogo_estpro2();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a>
                  <input name="denestpro2" type="text" class="sin-borde" id="denestpro2" value="<?php print $ls_denestpro2 ?>" size="45"></td>
            </tr>
            <tr class="formato-blanco">
              <td height="20"><div align="right"><?php print $ls_NomEstPro3;?></div></td>
              <td colspan="2"><input name="codestpro3" type="text" id="codestpro3" style="text-align:center"  value="<?php print $ls_codestpro3 ?>" size="<?php print $ls_loncodestpro3; ?>" maxlength="<?php print $ls_loncodestpro3; ?>" readonly>
                  <a href="javascript:catalogo_estpro3();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a>
                  <input name="denestpro3" type="text" class="sin-borde" id="denestpro3" value="<?php print $ls_denestpro3 ?>" size="45"></td>
            </tr>
			  <?php
				 if($li_estmodest==2)
				 {	
			  ?>
            <tr>
              <td height="20"><div align="right"><?php print $ls_NomEstPro4;?></div></td>
              <td colspan="2"><input name="codestpro4" type="text" id="codestpro4" style="text-align:center"  value="<?php print $ls_codestpro4 ?>" size="<?php print $ls_loncodestpro4; ?>" maxlength="<?php print $ls_loncodestpro4; ?>" readonly>
              <a href="javascript:catalogo_estpro4();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 4"></a><input name="denestpro4" type="text" class="sin-borde" id="denestpro4" value="<?php print $ls_denestpro4 ?>" size="45" readonly></td>
            </tr>
            <tr>
              <td height="20"><div align="right"><?php print $ls_NomEstPro5;?></div></td>
              <td colspan="2"><input name="codestpro5" type="text" id="codestpro5" style="text-align:center"  value="<?php print $ls_codestpro5 ?>" size="<?php print $ls_loncodestpro5; ?>" maxlength="<?php print $ls_loncodestpro5; ?>" readonly>
              <a href="javascript:catalogo_estpro5();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 5"></a><input name="denestpro5" type="text" class="sin-borde" id="denestpro5" value="<?php print $ls_denestpro5 ?>" size="45" readonly></td>
            </tr>
			  <?php  
			  }
			  ?>
			  
			<tr>
              <td></td>
              <td width="390"></td>
              <td width="257">
           	  <input name="botRecargar" type="button" class="boton" id="botRecargar" onClick="ue_recargar()" value="Cargar"></td>
              <td width="8"></td>
			<tr>
              <td height="18" colspan="3">
              	<span class="Estilo2">
              	<input name="estclades" type="hidden" id="estclades">
              	<input name="estmodest" type="hidden" id="estmodest" value="<?php print  $li_estmodest; ?>">
                <input name="estcla" type="hidden" id="estcla" value="<?php print $ls_estcla;?>">
              	</span>
              </td>
        	</tr>
        	<tr>
              <td height="200" colspan="3" ><span class="Estilo2"><div id="formulario"></div></span></td>
        	</tr>
	</table>
  </form>
   
</body>
<script language="javascript">
function uf_cargargrid()
{
	f=document.form1;
	f.operacion.value="CARGAR";
	f.action="sigesp_spg_p_progrep.php";
	f.submit();
	
}

function ue_recargar(){
	var f = document.form1;
	var codrep  = f.cmbrep.value;
	var estcla  = f.estcla.value;
	var codest1 = f.codestpro1.value;
    var codest2 = f.codestpro2.value;
    var codest3 = f.codestpro3.value;
    //var codest4 = ue_rellenarcampo(cod4, 25);
    var myJSONObject = {
        "operacion": 'cargarcuentas',
        "codrep": codrep,
        "codest1": codest1,
        "codest2": codest2,
        "codest3": codest3,
        "estcla": estcla
        
    };
    
    var ObjSon = Ext.util.JSON.encode(myJSONObject);
    var parametros = 'ObjSon=' + ObjSon;
    Ext.Ajax.request({
        url: '../controlador/spg/sigesp_ctr_spg_programacionreporte.php',
        params: parametros,
        method: 'POST',
        success: function(resultado, request){
            var respuesta = resultado.responseText;
            var datosCuentas = eval('(' + respuesta + ')');
            if (datosCuentas.raiz != '') {
            	gridProgramacion.store.loadData(datosCuentas);    
            }
            else{
            	Ext.Msg.alert('Advertencia', 'No hay cuentas registradas en la estructura indicada.');
            }    
        }
    });
}

function ue_procesar() {
	var primero = true
	var codrep  = f.cmbrep.value;
	var estcla  = f.estcla.value;
	var codest1 = f.codestpro1.value;
    var codest2 = f.codestpro2.value;
    var codest3 = f.codestpro3.value;
	var cadjson = "{'operacion':'procesar','codrep':'"+codrep+"','codest1':'"+codest1+"','codest2':'"+codest2+"',"+
				  "'codest3':'"+codest3+"','estcla':'"+estcla+"','cuenta':[";
	var dataCuenta = gridProgramacion.getStore().getModifiedRecords();
	for(var i=0;i<=dataCuenta.length-1;i++){
		var registro = dataCuenta[i];
		if(primero){
			cadjson = cadjson + "{'spg_cuenta':'"+registro.get('spg_cuenta')+"','denominacion':'"+registro.get('denominacion')+"',"+
			"'status':'"+registro.get('status')+"','sc_cuenta':'"+registro.get('sc_cuenta')+"','asignado':"+registro.get('asignado')+
			",'distribuir':"+registro.get('distribuir')+",'enero':"+registro.get('enero')+",'febrero':"+registro.get('febrero')+
			",'marzo':"+registro.get('marzo')+",'abril':"+registro.get('abril')+",'mayo':"+registro.get('mayo')+",'junio':"+registro.get('junio')+
			",'julio':"+registro.get('julio')+",'agosto':"+registro.get('agosto')+",'septiembre':"+registro.get('septiembre')+",'octubre':"+registro.get('octubre')+
			",'noviembre':"+registro.get('noviembre')+",'diciembre':"+registro.get('diciembre')+",'nivel':"+registro.get('nivel')+
			",'referencia':'"+registro.get('referencia')+"'}";
		}
		else{
			cadjson = cadjson + ",{'spg_cuenta':'"+registro.get('spg_cuenta')+"','denominacion':'"+registro.get('denominacion')+"',"+
			"'status':'"+registro.get('status')+"','sc_cuenta':'"+registro.get('sc_cuenta')+"','asignado':"+registro.get('asignado')+
			",'distribuir':"+registro.get('distribuir')+",'enero':"+registro.get('enero')+",'febrero':"+registro.get('febrero')+
			",'marzo':"+registro.get('marzo')+",'abril':"+registro.get('abril')+",'mayo':"+registro.get('mayo')+",'junio':"+registro.get('junio')+
			",'julio':"+registro.get('julio')+",'agosto':"+registro.get('agosto')+",'septiembre':"+registro.get('septiembre')+",'octubre':"+registro.get('octubre')+
			",'noviembre':"+registro.get('noviembre')+",'diciembre':"+registro.get('diciembre')+",'nivel':"+registro.get('nivel')+
			",'referencia':'"+registro.get('referencia')+"'}";
		}
		 primero =false;
	}

	cadjson = cadjson + "]}";

	var parametros = 'ObjSon=' + cadjson;
    Ext.Ajax.request({
        url: '../controlador/spg/sigesp_ctr_spg_programacionreporte.php',
        params: parametros,
        method: 'POST',
        success: function(resultado, request){
            var respuesta = resultado.responseText;
            if(respuesta=='1'){
            	Ext.Msg.alert('Mensaje', 'La programaci&oacuten fue procesada exitosamente');
            }
            else{
            	Ext.Msg.alert('Error', 'Ocurrio un error al procesar la programaci&oacuten');
            }        
        }
    });
	
	
}

function ue_distribuir(li)
{
    var i ;
    f=document.form1;
    cmbrep=f.cmbrep.value;
	cuenta="cuenta"+li;
	ls_cuenta_aux=eval("f."+cuenta+".value");
	if ((cmbrep!='00005') && (cmbrep!='0506')) 
	{	
	   lb_ok=true;
	}
	else
	{
	   lb_ok=false;
	}
	if( (f.codestpro1.value=="" || f.codestpro2.value=="" || f.codestpro3.value=="") && (lb_ok) ) 
    {
	  alert(" Por Favor Seleccione una Estructura Programatica....");
    }
    else
    {
		    document.opcion = "A"; 
		    f=document.form1;
		    ls_distribuir=1;
		    distribuir="distribuir"+li;
		    eval("f."+distribuir+".value='"+ls_distribuir+"'") ;
		    li_total=f.li_totnum.value;
		    opcion=document.opcion;
		    txtCuenta="txtCuenta"+li;
			ls_cuenta=eval("f."+txtCuenta+".value");
			txtDenominacion="txtDenominacion"+li;
			ls_denominacion=eval("f."+txtDenominacion+".value");
			txtAsignacion="txtAsignacion"+li;
			ld_asignado=eval("f."+txtAsignacion+".value");
			enero="enero"+li;
			ld_enero=eval("f."+enero+".value");
			febrero="febrero"+li;
			ld_febrero=eval("f."+febrero+".value");
			marzo="marzo"+li;
			ld_marzo=eval("f."+marzo+".value");
			abril="abril"+li;
			ld_abril=eval("f."+abril+".value");
			mayo="mayo"+li;
			ld_mayo=eval("f."+mayo+".value");
			junio="junio"+li;
			ld_junio=eval("f."+junio+".value");
			julio="julio"+li;
			ld_julio=eval("f."+julio+".value");
			agosto="agosto"+li;
			ld_agosto=eval("f."+agosto+".value");
			septiembre="septiembre"+li;
			ld_septiembre=eval("f."+septiembre+".value");
			octubre="octubre"+li;
			ld_octubre=eval("f."+octubre+".value");
			noviembre="noviembre"+li;
			ld_noviembre=eval("f."+noviembre+".value");
			diciembre="diciembre"+li;
			ld_diciembre=eval("f."+diciembre+".value");
			distribuir="distribuir"+li;
		    pagina="sigesp_spg_p_progrep_distribucion.php?fila="+li+"&txtAsignacion="+ld_asignado+"&enero="+ld_enero
					 +"&febrero="+ld_febrero+"&marzo="+ld_marzo+"&abril="+ld_abril+"&mayo="+ld_mayo+"&junio="+ld_junio+"&julio="+ld_julio
					 +"&agosto="+ld_agosto+"&septiembre="+ld_septiembre+"&octubre="+ld_octubre+"&noviembre="+ld_noviembre
					 +"&diciembre="+ld_diciembre+"&txtCuenta="+ls_cuenta+"&txtDenominacion="+ls_denominacion+"&tipo="+opcion;
		    window.open(pagina,"Asignación","menubar=no,toolbar=no,scrollbars=no,width=650,height=450,left=50,top=50,resizable=yes,location=no");
    }
}
function EvaluateText(cadena, obj)
{ 
	
    opc = false; 
	
    if (cadena == "%d")  
      if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32))  
      opc = true; 
    if (cadena == "%f")
	{ 
     if (event.keyCode > 47 && event.keyCode < 58) 
      opc = true; 
     if (obj.value.search("[.*]") == -1 && obj.value.length != 0) 
      if (event.keyCode == 46) 
       opc = true; 
    } 
	 if (cadena == "%s") // toma numero y letras
     if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32)||(event.keyCode > 47 && event.keyCode < 58)||(event.keyCode ==46)) 
      opc = true; 
	 if (cadena == "%c") // toma numero y punto
     if ((event.keyCode > 47 && event.keyCode < 58)|| (event.keyCode ==46))
      opc = true; 
    if(opc == false) 
     event.returnValue = false; 
   } 
function catalogo_estpro1()
{
	   pagina="sigesp_cat_public_estpro1.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}
function catalogo_estpro2()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;	
	estmodest=f.estmodest.value;
	estcla=f.estcla.value;
	if(estmodest==1)
	{
		if(codestpro1!="")
		{
			pagina="sigesp_cat_public_estpro2.php?codestpro1="+codestpro1+"&tipo=reporte"+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione nivel anterior");
		}
	}
	else
	{
		
		if(codestpro1=='**')
		{
			pagina="sigesp_cat_estpro2.php?tipo=reporte"+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			if(codestpro1!="")
			{
				pagina="sigesp_cat_public_estpro2.php?codestpro1="+codestpro1+"&tipo=reporte"+"&estcla="+estcla;
				window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
			}
			else
			{
				alert("Seleccione  nivel anterior");
			}
		}
	}	
}
function catalogo_estpro3()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	codestpro2=f.codestpro2.value;
	codestpro3=f.codestpro3.value;
	estmodest=f.estmodest.value;
	estcla=f.estcla.value;
	if(estmodest==1)
	{
		if((codestpro1!="")&&(codestpro2!="")&&(codestpro3==""))
		{
			pagina="sigesp_cat_public_estpro3.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&tipo=reporte"+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			pagina="sigesp_cat_public_estpro.php?tipo=reporte"+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
	}
	else
	{
		if((codestpro2=='**')||(codestpro1=='**'))
		{
			if((codestpro2!="")&&(codestpro1!=""))
			{
				pagina="sigesp_cat_estpro3.php?tipo=reporte&codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&estcla="+estcla;
				window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
			}
			else
			{
				alert("Seleccione niveles anteriores");
			}
		}
		else
		{
			if((codestpro2!="")&&(codestpro1!=""))
			{
				pagina="sigesp_cat_public_estpro3.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&tipo=reporte"+"&estcla="+estcla;
				window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");	
			}
			else
			{
				alert("Seleccione niveles anteriores");
			}
		}	
	}	
}
function catalogo_estpro4()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	codestpro2=f.codestpro2.value;
	codestpro3=f.codestpro3.value;
	estcla=f.estcla.value;
	if((codestpro2=='**')||(codestpro1=='**')||(codestpro3=='**'))
	{
		if((codestpro2!="")&&(codestpro1!="")&&(codestpro3!=""))
		{
			pagina="sigesp_cat_estpro4.php?tipo=reporte&codestpro1="+codestpro1+"&codestpro2="+codestpro2
			+"&codestpro3="+codestpro3+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione niveles anteriores");
		}
	}
	else
	{
		if((codestpro2!="")&&(codestpro1!="")&&(codestpro3!=""))
		{
			pagina="sigesp_cat_public_estpro4.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2
			+"&codestpro3="+codestpro3+"&tipo=reporte"+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");	
		}
		else
		{
			alert("Seleccione niveles anteriores");
		}
	}	
}
function catalogo_estpro5()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	codestpro2=f.codestpro2.value;
	codestpro3=f.codestpro3.value;
	codestpro4=f.codestpro4.value;
	codestpro5=f.codestpro5.value;
	estcla=f.estcla.value;
	if((codestpro2=='**')||(codestpro1=='**')||(codestpro3=='**')||(codestpro4=='**'))
	{
		if((codestpro2!="")&&(codestpro1!="")&&(codestpro3!="")&&(codestpro4!=""))
		{
			pagina="sigesp_cat_estpro5.php?tipo=reporte&codestpro1="+codestpro1+"&codestpro2="+codestpro2
			+"&codestpro3="+codestpro3+"&codestpro4="+codestpro4+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione niveles anteriores");
		}
	}
	else
	{
		if((codestpro2!="")&&(codestpro1!="")&&(codestpro3!="")&&(codestpro4!=""))
		{
			pagina="sigesp_cat_public_estpro5.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2
													 +"&codestpro3="+codestpro3+"&codestpro4="+codestpro4
													 +"&tipo=reporte"+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione niveles anteriores");
		}
	}
}

function ue_cerrar()
{
	f=document.form1;
	f.action="sigespwindow_blank.php";
	f.submit();
}
//--------------------------------------------------------
//	Función que formatea un número
//--------------------------------------------------------
function ue_formatonumero(fld, milSep, decSep, e)
{ 
	var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 

	if (whichCode == 13) return true; // Enter 
	if (whichCode == 8) return true; // Return
    key = String.fromCharCode(whichCode); // Get key value from key code 
    if (strCheck.indexOf(key) == -1) return false; // Not a valid key 
    len = fld.value.length; 
    for(i = 0; i < len; i++) 
    	if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break; 
    aux = ''; 
    for(; i < len; i++) 
    	if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i); 
    aux += key; 
    len = aux.length; 
    if (len == 0) fld.value = ''; 
    if (len == 1) fld.value = '0'+ decSep + '0' + aux; 
    if (len == 2) fld.value = '0'+ decSep + aux; 
    if (len > 2) { 
     aux2 = ''; 
     for (j = 0, i = len - 3; i >= 0; i--) { 
      if (j == 3) { 
       aux2 += milSep; 
       j = 0; 
      } 
      aux2 += aux.charAt(i); 
      j++; 
     } 
     fld.value = ''; 
     len2 = aux2.length; 
     for (i = len2 - 1; i >= 0; i--) 
     	fld.value += aux2.charAt(i); 
     fld.value += decSep + aux.substr(len - 2, len); 
    } 
    return false; 
}
</script>
</html>