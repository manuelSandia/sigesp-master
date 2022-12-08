<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.formulario.submit();";
		print "</script>";		
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Registro de Detalle de Ingreso</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_cxp.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<body >
<form name="formulario" method="post" action="">
<input name="campoorden" type="hidden" id="campoorden" value="numsol">
<input name="orden" type="hidden" id="orden" value="ASC">
<table width="780" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td><div align="right"><a href="javascript: ue_catalogocuentasingreso();"><img src="../shared/imagebank/tools20/nuevo.gif" width="20" height="20" class="sin-borde">Agregar Cuenta</a> <a href="javascript: uf_procesar();"><img src="../shared/imagebank/tools20/aprobado.gif" width="20" height="20" class="sin-borde">Procesar Operaci&oacute;n</a> </div></td>
  </tr>
  <tr>
    <td><div id="resultados" align="center"></div></td>
  </tr>
</table>
	
	</p>
    
    <p><br>    
  </p>
</form>  
</body>
<script language="JavaScript">
function uf_procesar(ls_codemp,ls_numrecdoc,ls_codtipdoc,ls_dentipdoc,ls_tipproben,ls_codpro,ls_cedbene)
{
	f=opener.document.formulario;
	li_total=ue_calcular_total_fila_local("txtcodestpro");
	li_opener=f.numrowsprenota.value;
	ls_campos="";
	li_selected=0;
	ls_ctaprov=f.txtcuentaprov.value;
	ls_denctascg=f.txtdenctascg.value;
	ls_numrecdoc=f.txtnumrecdoc.value;
	ls_codproben   =f.txtcodproben.value;
	//estcargo   =f.txtestcargo.value;
	if(f.tipproben[0].checked)
	{
	    ls_tipproben='P';
	}
	else
	{
		ls_tipproben='B';
	}	
	ls_codtipdoc=f.txttipdoc.value;	
	if(f.tiponota[0].checked)
	{
	    ls_tiponota='NC';
	}
	else
	{
		ls_tiponota='ND';
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//Obtengo los registros ya existentes en el grid de detalle presupuestario
	li_registros=0;
	for (li=1;li<=li_opener;li++)
	    {
		  ls_cuentaspg    = eval("f.txtcuentaspgncnd"+li+".value");
		  ls_codestpro    = eval("f.txtcodestproncnd"+li+".value");
		  ls_cuentascg    = eval("f.txtscgcuentadt"+li+".value");
		  ls_dencuentascg = eval("f.txtdenscgcuentadt"+li+".value");
		  ls_dencuentaspg = eval("f.txtdencuentancnd"+li+".value");
		  ls_estcargo	  = eval("f.txtestcargo"+li+".value");
		  ldec_monto	  = eval("f.txtmontoncnd"+li+".value");
		  ls_estcla       = eval("f.txtestclancnd"+li+".value");
		  if (ls_cuentaspg!="" && ls_codestpro!="" && ldec_monto!="0,00" && ls_estcargo!='C')
		     {
			   li_registros++;					
			   ls_campos=ls_campos+"&txtcuentaspgncnd"+li_registros+"="+ls_cuentaspg+
			                       "&txtcodestpro"+li_registros+"="+ls_codestpro+
								   "&txtestclancnd"+li_registros+"="+ls_estcla+
								   "&txtscgcuenta"+li_registros+"="+ls_cuentascg+
						           "&txtdenscgcuentadt"+li_registros+"="+ls_dencuentascg+
								   "&txtdencuenta"+li_registros+"="+ls_dencuentaspg+
								   "&txtestcargo"+li_registros+"="+ls_estcargo+
								   "&txtmonto"+li_registros+"="+ldec_monto;		
		     }
	    }
	//////////////////////////////////////////////////////////////////////////
	//Obtengo los valores seleccionados del catalogo para enviarlo por POST //
	//////////////////////////////////////////////////////////////////////////
	for (j=1;j<=li_total;j++)
	    {
		   lb_existe     = false;
		   cuenta        = eval("document.formulario.txtspicuenta"+j+".value");
		   codestpro     = eval("document.formulario.txtcodestpro"+j+".value");
		   codpro        = eval("document.formulario.txtcodpro"+j+".value");
		   estcla        = eval("document.formulario.txtestcla"+j+".value");
		   dencuenta     = eval("document.formulario.txtdencuenta"+j+".value");
		   monto         = eval("document.formulario.txtmonto"+j+".value");
		   scg_cuenta    = eval("document.formulario.txtscgcuenta"+j+".value");
		   den_scgcuenta = eval("document.formulario.txtdenscgcuenta"+j+".value");
		   for (k=1;k<=li_opener;k++)
			   {
				 ls_cuenta_op    = eval("f.txtcuentaspgncnd"+k+".value");
				 ls_codestpro_op = eval("f.txtcodestproncnd"+k+".value");
				 if (ls_cuenta_op==cuenta && ls_codestpro_op==codestpro)
					{
					  lb_existe=true;
					  break;					
					}
			   }
		   if (lb_existe==false)
			  {
				ldec_montoaux      = parseFloat(uf_convertir_monto(monto));
				ldec_monto         = eval("document.formulario.txtmonto"+j+".value");
				ldec_montooriginal = parseFloat(eval("document.formulario.txtmontooriginal"+j+".value"));
				ldec_monto         = parseFloat(uf_convertir_monto(ldec_monto));
				 if (ldec_montoaux>0)
					{
					  li_registros++;	
					  li_selected=li_registros;
					  ls_campos=ls_campos+"&txtcuentaspgncnd"+li_selected+"="+cuenta+"&txtcodestpro"+li_selected+"="+codestpro+"&txtestclancnd"+li_selected+"="+estcla+"&txtcodpro"+li_selected+"="+codpro+
								"&txtdencuenta"+li_selected+"="+dencuenta+"&txtmonto"+li_selected+"="+monto+"&txtscgcuenta"+li_selected+"="+scg_cuenta+"&txtdenscgcuentadt"+li_selected+"="+den_scgcuenta;	//+"&txtestcargo"+li_selected+"="+estcargo				   
					}						   
				 else
					{
					  alert("El monto del detalle para la cuenta "+cuenta+" asociada a la estructura "+codestpro+", debe ser mayor a cero(0,00)");
					}
			  }
		   else
			  {
				alert("La cuenta "+cuenta+" asociada a la estructura "+codestpro+" ya existe en el detalle de la nota");
			  }		   
	    }
	ls_campos=ls_campos+"&txtctaprov="+ls_ctaprov+"&denctascg="+ls_denctascg+"&tiponota="+ls_tiponota+"&selected="+li_selected+"&tipproben="+ls_tipproben+"&codproben="+ls_codproben+"&numrecdoc="+ls_numrecdoc+"&codtipdoc="+ls_codtipdoc;	
	if (ls_campos!="")
	   {
	     // Cargamos las variables para pasarlas al AJAX
		 divgrid = opener.document.getElementById('detallesnota');
		 // Instancia del Objeto AJAX
		 ajax=objetoAjax();
		 // Pagina donde est�n los m�todos para buscar y pintar los resultados
		 ajax.open("POST","class_folder/sigesp_cxp_c_ncnd_ajax.php",true);
		 ajax.onreadystatechange=function(){
		 if (ajax.readyState==1)
			{
			  divgrid.innerHTML = "<img src='../shared/imagebank/cargando.gif' width='32' height='32'>";//<-- aqui iria la precarga en AJAX 
			}
		 else
			{
			  if (ajax.readyState==4)
				 {
				   if (ajax.status==200)
					  {//mostramos los datos dentro del contenedor
					    divgrid.innerHTML = ajax.responseText;
					  }
				   else
					  {
					    if (ajax.status==404)
						   {
							 divgrid.innerHTML = "La p�gina no existe";
						   }
						else
						   {//mostramos el posible error     
							 divgrid.innerHTML = "Error:".ajax.status;
						   }
					  }
				 }
			}
		 }	
		 ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		 // Enviar todos los campos a la pagina para que haga el procesamiento
		 ajax.send("funcion=AGREGARDTNOTAPRE"+ls_campos);
	   }
}

function ue_search()
{
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	ls_numord="<?php print $_GET["numord"]?>";
	ls_tipo="<?php print $_GET["tipproben"]?>";
	ls_codproben="<?php print $_GET["codproben"]?>";
	ls_numrecdoc="<?php print $_GET["numrecdoc"]?>";
	ls_numncnd="<?php print $_GET["numncnd"]?>";
	ls_codtipdoc="<?php print $_GET["codtipdoc"]?>";
	ls_tiponota="<?php print $_GET["tiponota"]?>";
	estcargo="<?php print $_GET["cargo"]?>";
	//ls_nomproben="";
	orden=f.orden.value;
	campoorden=f.campoorden.value;
	// Div donde se van a cargar los resultados
	divgrid = document.getElementById('resultados');
	// Instancia del Objeto AJAX
	ajax=objetoAjax();
	// Pagina donde est�n los m�todos para buscar y pintar los resultados
	ajax.open("POST","class_folder/sigesp_cxp_c_catalogo_ajax.php",true);
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1)
		{
			divgrid.innerHTML = "<img src='../shared/imagebank/cargando.gif' width='32' height='32'>";//<-- aqui iria la precarga en AJAX 
		}
		else
		{
			if(ajax.readyState==4)
			{
				if(ajax.status==200)
				{//mostramos los datos dentro del contenedor
					divgrid.innerHTML = ajax.responseText;
				}
				else
				{
					if(ajax.status==404)
					{
						divgrid.innerHTML = "La p�gina no existe";
					}
					else
					{//mostramos el posible error     
						divgrid.innerHTML = "Error:".ajax.status;
					}
				}
			}
		}
	}	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	// Enviar todos los campos a la pagina para que haga el procesamiento
	ajax.send("catalogo=DTINGRESO&numord="+ls_numord+"&tipproben="+ls_tipo+"&codproben="+ls_codproben+"&numrecdoc="+ls_numrecdoc+"&codtipdoc="+ls_codtipdoc+"&tiponota="+ls_tiponota+"&estcargo="+estcargo+
			  "&orden="+orden+"&campoorden="+campoorden);
}

/*function ue_chequearfilas(a)
{
	li_total=document.formulario.selected.value;
	if(eval("document.formulario.chk"+a+".checked"))
	{
		li_selected=parseInt(li_total)+1;
	}
	else
	{
		li_selected=parseInt(li_total)-1;
	}
	document.formulario.selected.value=li_selected;
	
}*/
function uf_valida_monto(li)
{
	f=document.formulario;
	if(opener.document.formulario.tiponota[0].checked)
	{
	    ls_tiponota='NC';
	}
	else
	{
		ls_tiponota='ND';
	}
	ldec_monto=eval("f.txtmonto"+li+".value");
	ldec_montooriginal=parseFloat(eval("f.txtmontooriginal"+li+".value"));
	ldec_monto=parseFloat(uf_convertir_monto(ldec_monto));
/*	if(ldec_monto>ldec_montooriginal && ls_tiponota=='NC')
	{
		alert("El monto a registrar no puede ser mayor al registrado en la Recepci&oacute;n de Documento");
		eval("f.txtmonto"+li+".value='"+uf_convertir(eval("f.txtmontooriginal"+li+".value"))+"'")
	}
*/	
}
function uf_format(obj)
{
	ldec_monto=uf_convertir(obj.value);
	obj.value=ldec_monto;
}

function ue_close()
{
	close();
}

function ue_catalogocuentasingreso()
{
	f=document.formulario;
	totalrows=f.totalrows.value;
	numrecdoc=f.numrecdoc.value;
	codtipdoc=f.codtipdoc.value;
	codproben=f.codproben.value;
	tipproben=f.tipproben.value;
	window.open("sigesp_cxp_cat_cuentasspi.php?totalrows="+totalrows+"&numrecdoc="+numrecdoc+"&codtipdoc="+codtipdoc+"&codproben="+codproben+"&tipproben="+tipproben,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=650,height=400,left=50,top=50,location=no,resizable=yes");
	
}

ue_search();
</script>
</html>