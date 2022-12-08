<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title> Planificaci&oacute;n Estrategica- Organos
Ejecutores</title>


  <script type="text/javascript" language="JavaScript" src="../../librerias/js/general/funciones.js"></script>
  <script type="text/javascript" src="../../librerias/js/ext/adapter/ext/ext-base.js"></script>
  <script type="text/javascript" src="../../librerias/js/ext/ext-all.js"></script><script type="text/javascript" src="../../librerias/js/menu/sigesp_mcd_vis_menu.js"></script>
  <script type="text/javascript" src="../../librerias/js/general/json2.js"></script>
  <script type="text/javascript" src="../js/sigesp_sfp_problemas.js"></script>
  <script type="text/javascript" src="../js/tabs-example.js"></script>
  <link href="../../otros/css/tablas.css" rel="stylesheet" type="text/css">

  <link href="../../otros/css/ventanas.css" rel="stylesheet" type="text/css">

  <link href="../../otros/css/cabecera.css" rel="stylesheet" type="text/css">

  <link href="../../otros/css/general.css" rel="stylesheet" type="text/css">

  <link rel="stylesheet" type="text/css" href="../../librerias/js/ext/resources/css/ext-all.css">

  <link rel="stylesheet" type="text/css" href="../../otros/css/ExtStart.css">

</head>


<body>

<table class="contorno" align="center" border="0" cellpadding="0" cellspacing="0" width="762">

  <tbody>

    <tr>

      <td colspan="11" class="cd-logo" height="30" width="780"><img src="../../../shared/imagebank/header.jpg" height="40" width="778"></td>

    </tr>

    <tr>

      <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu" id='toolbar'></td>

    </tr>

    <tr>

      <td colspan="11" class="toolbar" bgcolor="#e7e7e7" height="13">&nbsp;</td>

    </tr>

    <tr>

      <td class="Botonera"> <img src="../../../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" id="BtnNuevo" border="0" height="20" width="20">
      <img src="../../../shared/imagebank/tools20/grabar.gif" alt="Grabar" id="BtnGrabar" border="0" height="20" width="20"> <img src="../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" id="BtnCat" border="0" height="20" width="20"><img src="../../../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" id="BtnElim" border="0" height="20" width="20"> <img src="../../../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" id="BtnImp" border="0" height="20" width="20"> <img src="../../../shared/imagebank/tools20/salir.gif" alt="Salir" id="BtnSalir" border="0" height="20" width="20">
      <img src="../../../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" id="BtnAyu" height="20" width="20"></td>

    </tr>

  </tbody>
</table>

<form name="form1" method="post" action="" id="form1">
  <table align="center" border="0" cellpadding="0" cellspacing="0" width="588">

    <tbody>

      <tr>
        <td>&nbsp;</td>
      </tr>

      <tr>
        <td>
        <table class="formato-blanco" align="center" border="0" cellpadding="0" cellspacing="0" width="566">

          <tbody>

            <tr>
              <td colspan="2" class="titulo-ventana">Definici&oacute;n de Problemas </td>
            </tr>

            <tr class="formato-blanco">

              <td height="19" width="111">&nbsp;</td>

              <td height="22" width="408"><input name="txtempresa" id="txtempresa" value="" type="hidden"> <input name="txtnombrevie" id="txtnombrevie2" type="hidden"></td>

            </tr>
            <tr class="formato-blanco">

              <td height="29">
              <div align="right">C&oacute;digo</div>
              </td>

              <td height="22"><input name="txtcod" id="codprob" value="" size="15" maxlength="15" style="text-align: center;" title="C&oacute;digo" readonly="readonly" type="text"> <input name="codemp" id="codemp" value="0001" type="hidden"> <input name="actualizar" id="actualizar" type="hidden">
              </td>

            </tr>

            <tr class="formato-blanco">
              <td height="28">
              <div align="right">Denominaci&oacute;n</div>
              </td>
              <td height="22"><input name="txtden" id="denominacion" value="" size="50" maxlength="100" title="Denominaci&oacute;n" type="text">
			  </td>
            </tr>
            <tr class="formato-blanco">
              <td height="28">
              <div align="right">Descripci&oacute;n</div>
              </td>
              <td height="22"><textarea cols="80" rows="3" name="descripcion" id="descripcion"></textarea>

			  </td>
            </tr>			
          </tbody>
        </table>
        </td>

      </tr>

      <tr>

        <td>&nbsp;</td>
		<table>
		<tbody>
		<tr>
				<!-- container for the existing markup tabs -->
				<div id="tabs1" align="center" >
					<div id="caracteristicas" class="x-hide-display">
						<textarea cols="100" rows="10" name="caracteristicas" id="caracteristicas"></textarea>
					</div>
					<div id="causas" class="x-hide-display">
						<textarea cols="100" rows="10" name="causas" id="causas"></textarea>
					</div>	
					<div id="efectos" class="x-hide-display">
						<textarea cols="100" rows="10" name="efectos" id="efectos"></textarea>
					</div>		
						
				</div>
		</tr>
		</tbody>
		</table>
      </tr>

    </tbody>
  </table>
  <input name="operacion" id="operacion" type="hidden">
  
</form>
</body>
</html>