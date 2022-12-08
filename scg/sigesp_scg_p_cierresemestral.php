<?php
    session_start();
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
	$ls_logusr=$_SESSION["la_logusr"];
	
	if($_SESSION["la_empresa"]["estciesem"]==0){
		print "<script language=JavaScript>";
		print "alert('Debe configurar en empresa para poder realizar el cierre semestral');";
		print "location.href='sigespwindow_blank.php';";
		print "</script>";
	}
	
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();
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
		$ls_permisos=$io_seguridad->uf_sss_select_permisos($_SESSION["la_empresa"]["codemp"],$ls_logusr,"SCG","sigesp_scg_p_cierresemestral.php");
	}
	
	
	$la_seguridad["empresa"]  = $_SESSION["la_empresa"]["codemp"];
	$la_seguridad["logusr"]   = $ls_logusr;
	$la_seguridad["sistema"]  = "SCG";
	$la_seguridad["ventanas"] = "sigesp_scg_p_cierresemestral.php";
	
	
	
	
	  
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
</script>
<title >Cierre Semestral contable</title>
<meta http-equiv="imagetoolbar" content="no"> 
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #EFEBEF;
}

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
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funciones.js"></script>
<link href="css/rpc.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
</head>
<body>

<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="10" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="10" bgcolor="#E7E7E7" class="cd-menu">
	<table width="778" border="0" align="center" cellpadding="0" cellspacing="0">
			
                  <td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Contabilidad Patrimonial </td>
			        <td width="349" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequeñas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  	  <tr>
	  	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	    <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </table></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="10" class="toolbar"></td>
  </tr>
  <tr>
    <td width="25" height="20" class="toolbar"><div align="center"><a href="javascript:ue_procesar();"></a><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif"  title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"></a><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="22" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"></a><a href="javascript: ue_ayuda();"></a><a href="javascript: ue_cerrar();"></a><a href="javascript: ue_ayuda();"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_ayuda();"></a></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>

<p>
<?php
    if(array_key_exists("operacion",$_POST)){
    	require_once ("../shared/class_folder/class_mensajes.php");
    	require_once ("sigesp_scg_c_cierresemestral.php");
    	$io_cierre = new sigesp_scg_c_cierresemestral();
    	$io_msg    = new class_mensajes();
    	
    	switch ($_POST["operacion"]) {
    		case "CIERRE":
    			if($_SESSION["la_empresa"]["ciesem1"]==0){
    				$lb_cierre1 = $io_cierre->uf_update_estatus_comprobante(1,0,$la_seguridad);
    				if($lb_cierre1){
    					$_SESSION["la_empresa"]["ciesem1"]=1;
    					$_SESSION["la_empresa"]["m01"]=1;
    					$_SESSION["la_empresa"]["m02"]=1;
    					$_SESSION["la_empresa"]["m03"]=1;
    					$_SESSION["la_empresa"]["m04"]=1;
    					$_SESSION["la_empresa"]["m05"]=1;
    					$_SESSION["la_empresa"]["m06"]=1;
    					$io_msg->message("El cierre semestral se ejecuto satisfactoriamente");
    				}
    				else{
    					$io_msg->message("Error al ejecutar el cierre semestral");
    				}
    			}
    			else{
    				$lb_cierre2 = $io_cierre->uf_update_estatus_comprobante(2,0,$la_seguridad);
    				if($lb_cierre2){
    					$_SESSION["la_empresa"]["ciesem2"]=1;
    					$_SESSION["la_empresa"]["m07"]=1;
    					$_SESSION["la_empresa"]["m08"]=1;
    					$_SESSION["la_empresa"]["m09"]=1;
    					$_SESSION["la_empresa"]["m10"]=1;
    					$_SESSION["la_empresa"]["m11"]=1;
    					$_SESSION["la_empresa"]["m12"]=1;
    					$io_msg->message("El cierre semestral se ejecuto satisfactoriamente");
    				}
    				else{
    					$io_msg->message("Error al ejecutar el cierre semestral");
    				}
    			}
    			break;
    		
    		case "REVERSO1":
    			$lb_reverso1 = $io_cierre->uf_update_estatus_comprobante(1,1,$la_seguridad);
    			if($lb_reverso1){
    				$_SESSION["la_empresa"]["ciesem1"] = 0;
    				$_SESSION["la_empresa"]["m01"]=0;
    				$_SESSION["la_empresa"]["m02"]=0;
    				$_SESSION["la_empresa"]["m03"]=0;
    				$_SESSION["la_empresa"]["m04"]=0;
    				$_SESSION["la_empresa"]["m05"]=0;
    				$_SESSION["la_empresa"]["m06"]=0;
    				$io_msg->message("El reverso del cierre semestral se ejecuto satisfactoriamente");
    			}
    			else{
    				$io_msg->message("Error al ejecutar el reverso del cierre semestral");
    			}
    			break;
    		
    		case "REVERSO2":
    			$lb_reverso2 = $io_cierre->uf_update_estatus_comprobante(2,1,$la_seguridad);
    			if($lb_reverso2){
    				$_SESSION["la_empresa"]["ciesem2"] = 0;
    				$_SESSION["la_empresa"]["m07"]=0;
    				$_SESSION["la_empresa"]["m08"]=0;
    				$_SESSION["la_empresa"]["m09"]=0;
    				$_SESSION["la_empresa"]["m10"]=0;
    				$_SESSION["la_empresa"]["m11"]=0;
    				$_SESSION["la_empresa"]["m12"]=0;
    				$io_msg->message("El reverso del cierre semestral se ejecuto satisfactoriamente");
    			}
    			else{
    				$io_msg->message("Error al ejecutar el reverso del cierre semestral");
    			}
    			break;
    	}
    }
?>
</p>
<form name="form1" method="post" action="">
<?php
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if (($ls_permisos)||($ls_logusr=="PSEGIS")){
		print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	}
	else{
		print("<script language=JavaScript>");
		print(" location.href='sigespwindow_blank.php'");
		print("</script>");
	}
	//////////////////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////
?>		  
<table width="200" border="0" align="center">
    <tr>
      <td><div align="center">
        <table width="570" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
         <td height="22" colspan="2" class="titulo-celdanew">CIERRE SEMESTRAL CONTABLE </td>
      </tr>                 
          
                 
          <tr>
            <td colspan="2" ><p>
                <input name="dbdestino" type="hidden" id="dbdestino" value="<?php print $ls_dbdestino;?>">
                <input name="operacion" type="hidden" id="operacion" value="<?php print ($ls_operacion); ?>">
                <input name="conexion" type="hidden" id="conexion" value="<?php print ($li_conexion); ?>">
              </p>              <div align="right"></div></td>
            </tr>
          
		 <tr>
            <td height="50" align="center">
			<?php 
				$ls_etiqueta = "";
			    if ($_SESSION["la_empresa"]["ciesem1"] == 0){
			    	$ls_etiqueta = " CIERRE DE PRIMER SEMESTRE ";
				}
				else {
					$ls_etiqueta = " CIERRE DE SEGUNDO SEMESTRE ";
				} 
				
				if($_SESSION["la_empresa"]["ciesem2"] != 1){
			?>
              <input name="btcerrar" type="button" class="boton" id="btcerrar" value= "<? print trim($ls_etiqueta); ?>" height="120" onClick="javascript:ue_procesar()">
             <?php 
				}
             ?> 
            </td>
            <td height="50" align="center">
           	<?php 
				if ($_SESSION["la_empresa"]["ciesem1"] == 1 && $_SESSION["la_empresa"]["ciesem2"] == 0){
			?>
				<input name="btreversar" type="button" class="boton" id="btreversar" value= "REVERSAR CIERRE PRIMER TRIMESTRE" height="120" onClick="javascript:ue_reversar(1)">
			<?php
			    }
			    else if($_SESSION["la_empresa"]["ciesem1"] == 1 && $_SESSION["la_empresa"]["ciesem2"] == 1){ 
			?> 
				<input name="btreversar" type="button" class="boton" id="btreversar" value= "REVERSAR CIERRE SEGUNDO TRIMESTRE" height="120" onClick="javascript:ue_reversar(2)">
			<?php
			    } 
			?>
            </td>
           </tr>
	  
		 <tr>
          <td colspan="2">
		  </td>		  
         </tr>
        </table>
      </div></td>
    </tr>
  </table>
</form>      
</body>
<script language="javascript">
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
function ue_procesar(){
	f=document.form1;
	/*li_ejecutar=f.ejecutar.value;
	li_cierre=f.hidciepre.value;
	if (li_ejecutar==1){
	  

   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}*/
	f.operacion.value = "CIERRE";
    f.action="sigesp_scg_p_cierresemestral.php";
    f.submit();
}

function ue_reversar(li_semestre){
	f=document.form1;
	if(li_semestre==1){
		f.operacion.value = "REVERSO1";
	}
	else{
		f.operacion.value = "REVERSO2";
	}	
	

    f.action="sigesp_scg_p_cierresemestral.php";
    f.submit();
}

function ue_cerrar()
{
	location.href='sigespwindow_blank.php' 
}

</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js" ></script>
</html>