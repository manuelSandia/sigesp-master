<?php 
session_start(); 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
	    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	    <title id="page-title">SIGESP - Cerrar Tramite</title> 
    	<link href="../../base/css/general.css" rel="stylesheet" type="text/css">
    	<link href="../../base/css/sigesp_css_cabecera.css" rel="stylesheet" type="text/css">
		<link href="../../base/css/sigesp_css_menu.css" rel="stylesheet" type="text/css">
		<link href="../../base/css/sigesp_css_formulario.css" rel="stylesheet" type="text/css">
		<link href="../../base/librerias/js/ext/resources/css/ext-all.css" rel="stylesheet" type="text/css" >
    	<script language="javascript">
			var sistema='SST';
			var vista='sigesp_vis_sst_gestionartramite.php';
			var tbnuevo = false;
			var tbactualizar = false;
			var tbadministrativo = false;
			<?php
				require_once ('../../base/librerias/php/general/sigesp_lib_funciones.php');
		    	obtenerEmpresaSession();           
			?>
		</script>
    	<script type="text/javascript" src="../../base/librerias/js/ext/adapter/ext/ext-base.js"></script>
		<script type="text/javascript" src="../../base/librerias/js/ext/ext-all.js"></script>
		<script type="text/javascript" src="../../base/librerias/js/ext/build/locale/ext-lang-es.js"></script>
		<script type="text/javascript" src="../../base/librerias/js/general/funciones.js"></script>
		<script type="text/javascript" src="../../base/librerias/js/general/json2.js"></script>
		<script type="text/javascript" src="../../base/librerias/js/general/barra_herramientas.js"></script>
		<script type="text/javascript" src="js/sigesp_vis_sst_menuprincipal.js"></script>
		<script type="text/javascript" src="../sss/js/sigesp_vis_sss_sesion.js"></script>
		<script type="text/javascript" src="../../base/librerias/js/componentes/sigesp_com_campotexcatalogo.js"></script>
		<script type="text/javascript" src="js/sigesp_vis_sst_cerrartramite.js"></script>
		<script type="text/javascript" src="catalogo/sigesp_vis_sst_catalogo_documento.js"></script>
	</head>
	<body>
		<script type="text/javascript" src="../../base/librerias/js/general/sigesp_vis_encabezado.js"></script>
		<table width="925" height="33" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno" id="norte">
			<tr> 
				<div id="menu_principal"></div>
				<div id="barra_herramientas"></div>
				<div id="formulario_cerrartramite"></div>
			</tr>
 		</table>
	</body> 
</html>