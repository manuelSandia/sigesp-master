<?Php
	session_start();
	if (!array_key_exists("la_logusr",$_SESSION))
	   {
		 print "<script language=JavaScript>";
		 print "location.href='../sigesp_conexion.php';";
		 print "</script>";		
	   }
	function uf_upload($as_nomfot,$as_tipfot,$as_tamfot,$as_nomtemfot,$io_msg)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_upload
		//		   Access: public (sigesp_snorh_d_personal)
		//	    Arguments: as_nomfot  // Nombre Foto
		//				   as_tipfot  // Tipo Foto
		//				   as_tamfot  // Tamaño Foto
		//				   as_nomtemfot  // Nombre Temporal
		//	      Returns: Retorna un booleano
		//	  Description: Funcion que sube una foto al servidor
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$nombre_carpeta = "fotosobras"; 
		if(!is_dir($nombre_carpeta))
		{ 
			@mkdir($nombre_carpeta, 0777); 
		}
		if ($as_nomfot!="")
		{
			if($as_tamfot < 100000)
			{
				if (!(strpos($as_tipfot, "gif") || strpos($as_tipfot, "jpeg") || strpos($as_tipfot, "png")))
				{ 
					$lb_valido=false;
					$as_nomfot="";
					$io_msg->message("El archivo de la foto no es válido.");
				}
				else
				{ 
					if (!((move_uploaded_file($as_nomtemfot, $nombre_carpeta."/".$as_nomfot))))
					{

						$lb_valido=false;
						$as_nomfot="";
						$io_msg->message("Se produjo un error al grabar la foto"); 
					}
				}
			}
			else
			{
				$io_msg->message("Seleccione un archivo menor a 100 Kb.");
			}
		}
		return $lb_valido;	
    }
  //----------------------------------------------------------------------------------------------------------------------------------

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Registro de Fotos </title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<style type="text/css">
<!--
body {
	margin-top: 40px;
}
-->
</style>

</head>
<?
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_datastore.php");
require_once ("class_folder/sigesp_sob_c_funciones_sob.php");
require_once("../shared/class_folder/class_funciones_db.php");
$io_funsob= new sigesp_sob_c_funciones_sob(); 
$io_datastore=new class_datastore();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_msg=new class_mensajes();
$io_sql=new class_sql($io_connect);
$io_fundb = new class_funciones_db($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();
$la_empresa=$_SESSION["la_empresa"];
$ls_codemp=$la_empresa["codemp"];
require_once("../shared/class_folder/sigesp_c_generar_consecutivo.php");
$io_keygen= new sigesp_c_generar_consecutivo();

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_campoclave=$_SESSION["campoclave"];
	$ls_opener=$_POST["hidopener"];
	if($ls_operacion=="ue_aceptar")
	{
		$ls_nomfot=$HTTP_POST_FILES['txtfile']['name']; 
		$ls_ext=$HTTP_POST_FILES['txtfile']['type']; 
		$li_tamano=$HTTP_POST_FILES['txtfile']['size']; 
		$ls_nomtemfot=$HTTP_POST_FILES['txtfile']['tmp_name'];
		$ls_descripcion=$_POST["txtdesfot"];
		$ls_nombre=$_POST["txtnomfot"];
		$ls_codfot= $io_keygen->uf_generar_numero_nuevo("SOB","sob_foto","codfot","SOBFOT",10,"","","");
		$ls_nomfot=$ls_codfot.substr($ls_nomfot,strrpos($ls_nomfot,"."));
		$lb_valido=uf_upload($ls_nomfot,$ls_ext,$li_tamano,$ls_nomtemfot,$io_msg);
		if($lb_valido)
		{
			if($ls_opener=="obra")
			{
				$ls_sql="INSERT INTO sob_foto (codfot,codobr,codemp,nomfot,tipfot,desfot,tamfot,foto)".
						" VALUES ('$ls_codfot','$ls_campoclave','$ls_codemp','$ls_nombre','$ls_ext','$ls_descripcion',".
						"         '$li_tamano','$ls_nomfot')";
			}	
			else
			{
				$ls_contrato=$_SESSION["contrato"];
				$ls_sql="INSERT INTO sob_foto (codfot,codval,codcon,codemp,nomfot,tipfot,desfot,tamfot,foto)".
						" VALUES ('$ls_codfot','$ls_campoclave','$ls_contrato','$ls_codemp','$ls_nombre','$ls_ext',".
						"         '$ls_descripcion','$li_tamano','$ls_nomfot')";			
					
			}
			$io_sql->begin_transaction();	
			$li_row=$io_sql->execute($ls_sql);
			if($li_row===false)
			{			
				print "Error al insertar Foto".$io_funcion->uf_convertirmsg($io_sql->message);
				$io_sql->rollback();	
			}
			else
			{
				if($li_row>0)
				{			
					$io_sql->commit();
					$io_msg->message('La foto fue incluida!!!');
						
				}
				else
				{			
					$io_sql->rollback();
				}
			 }	 
		}
	}	
}
else
{
	$ls_opener=$_GET["opener"];
}
?>

<body>
<form name="form1" method="post" action="" enctype="multipart/form-data">
  <table width="356" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td height="22" colspan="4" class="titulo-celdanew">Registro de Fotos </td>
    </tr>
    <tr>
      <td height="30"><div align="right">Nombre</div></td>
      <td height="30" colspan="3"><label>
        <input name="txtnomfot" type="text" id="txtnomfot" size="40" maxlength="26">
      </label></td>
    </tr>
    <tr>
      <td height="30"><div align="right">Descripci&oacute;n</div></td>
      <td height="30" colspan="3"><label>
        <textarea name="txtdesfot" cols="37" rows="2" wrap="virtual" id="txtdesfot"></textarea>
      </label></td>
    </tr>
    <tr>
      <td height="30"><div align="right">Archivo</div></td>
      <td height="30" colspan="3"><input type="file" size="25" name="txtfile" id="txtfile" ></td>
    </tr>
    <tr>
      <td colspan="4">* Solo se permiten archivos .gif, .jpg, .png</td>
    </tr>
    <tr>
      <td width="107"><div align="right"></div></td>
      <td width="152">&nbsp;</td>
      <td width="39"><a href="javascript:uf_aceptar();"><img src="../shared/imagebank/tools20/aprobado.gif" alt="Aceptar" width="20" height="20" border="0" ></a></td>
      <td width="56"><a href="javascript:uf_cancelar();"><img src="../shared/imagebank/tools20/eliminar.gif" border="0" alt="Cancelar" width="20" height="20" ></a></td>
    </tr>
  </table>
  <input type="hidden" name="operacion" id="operacion">
  <input type="hidden" name="hidfile" id="hidfile">
  <input type="hidden" name="hidopener" id="hidopener" value="<?Php print $ls_opener?>">
</form>
<p>&nbsp;</p>
</body>
<script language="javascript">
function uf_aceptar()
{
	f=document.form1;
	if (f.txtfile.value!="")
	{
		f.hidfile.value=f.txtfile.value;
		f.operacion.value="ue_aceptar";
		f.submit();	
	}	
	else
	{
		alert("Seleccione un Archivo!!!");
		
	}

}

function uf_cancelar()
{
	close();
}

</script>
</html>
