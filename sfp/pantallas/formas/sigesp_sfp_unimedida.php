<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
  "http://www.w3.org/TR/html4/loose.dtd">
<head>
	 <title id="page-title">SIGESP - Definici&oacute;n de Unidad de Medida</title> 

<script type="text/javascript" language="JavaScript" src="../../librerias/js/general/funciones.js"></script>
<script type="text/javascript" src="../../librerias/js/ext/adapter/ext/ext-base.js"></script>
<script type="text/javascript" src="../../librerias/js/ext/ext-all.js"></script>
<script type="text/javascript" src="../../librerias/js/ext/build/locale/ext-lang-es.js"></script>
<script type="text/javascript" src="../../librerias/js/menu/sigesp_mcd_vis_menu.js"></script>
<script type="text/javascript" src="../../librerias/js/general/json2.js"></script>
<script type="text/javascript" src="../js/sigesp_sfp_unimedida.js"></script>
<link href="../../otros/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../otros/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../../otros/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../otros/css/general.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../../librerias/js/ext/resources/css/ext-all.css">
<link rel="stylesheet" type="text/css" href="../../otros/css/ExtStart.css">
</head>
<html>
<body>



<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno" id="norte">
  <tr>
    <!-- <td width="780" height="30" colspan="11" class="cd-logo"><img src="../../../shared/imagebank/header.jpg" width="778" height="40"></td>-->
	<td align='center'><img src='../../../shared/imagebank/header.jpg' width='990px' height='40px'></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu" id='toolbar'></td>
  </tr>
  <tr>
    <td height="13" colspan="11" bgcolor="#E7E7E7" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
	  <td class="Botonera">
		    <img src="../../../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0" id="BtnNuevo" >
		    <img src="../../../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0" id="BtnGrabar">
		    <img src="../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0" id="BtnCat">
		    <img src="../../../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0" id="BtnElim">
		     <img src="../../../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0" id="BtnImp">
		    <img src="../../../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0" id="BtnSalir">
		    <img src="../../../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" id="BtnAyu">
	  </td>
  </tr>
</table>
<div id="centro">
<div class="titulo-pantalla">Definici&oacute;n de Unidad de Medida</div>
<form name="form1" method="post" action="" id="form1">

<table width="588" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><table width="566" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                
                  <tr class="formato-blanco">
                    <td width="111" height="19">&nbsp;</td>
                    <td width="408" height="22"><input name="txtempresa" type="hidden" id="txtempresa" value="">
                      <input name="txtnombrevie" type="hidden" id="txtnombrevie2"></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="29"><div align="right">C&oacute;digo</div></td>
                    <td height="22"><input name="txtcod" type="text" id="txtcod" value="" size="8" maxlength="4" style="text-align:center " title="C�digo" readonly>
                    <input name="codemp" type="hidden" id="codemp" value="0001">
		      		<input name="actualizar" type="hidden" id="actualizar">
		      
		      </td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="28"><div align="right">Denominaci&oacute;n</div></td>
                    <td height="22"><input type="text" id="txtden"   size="50" maxlength="100" title="Denominaci�n"></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="28"><div align="right">Sensible a G�nero</div></td>
                    <td height="22"><input name="chkgen" type="checkbox" id="chkgen"  value="" size="50" maxlength="100" title="G�nero"></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
            </table>
              <input name="operacion" type="hidden" id="operacion">
          </form>
</div>
</body>

</html>