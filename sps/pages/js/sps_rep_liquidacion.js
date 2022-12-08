// JavaScript Document

var url = '../php/sps_rep_liquidacion.php';
var params = 'operacion';
var metodo = 'get';

Event.observe(window, 'load', ue_cancelar, false);

function ue_buscarpersonal()
{
	pagina="sps_cat_liquidacion.html.php";
    catalogo = popupWin(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=440,height=250,resizable=yes,location=no,top=0,left=0");
}
function ue_cargar_registro_liquidacion(arr_datos)
{    
	$('txtcodper').value = trim(arr_datos[3]);
	$('txtnomper').value = trim(arr_datos[1]+' '+arr_datos[2]);
	$('txtcodnom').value = trim(arr_datos[4]);
	$('txtdesnom').value = trim(arr_datos[5]);
	$('txtnumliq').value = trim(arr_datos[0]);
	$('txtestliq').value = trim(arr_datos[18]);
}
function ue_cancelar()
{
	  document.form1.reset();
	  deshabilitar("txtcodper,txtnomper,txtcodnom,txtdesnom,txtnumliq,txtestliq");
}

function ue_imprimir()
{
	var la_objetos=new Array("txtcodper");
	var la_mensajes=new Array("el Código del Personal");
	
	lb_valido = valida_datos_llenos(la_objetos, la_mensajes);
	if(lb_valido)
	{
	  var parametros =
	  {
		"operacion"  :"ue_imprimir",
		"codper"     :$F('txtcodper'),
		"nomper"     :$F('txtnomper'),
		"codnom"     :$F('txtcodnom'),
		"numliq"     :$F('txtnumliq')
	  };
	  params = $H(parametros).toQueryString();
	  if ($('txtestliq').value=="R")
	  {
		  var pagina = "../../reports/documents/sps_reporte_liquidacion.php?"+params;
	  }
	  else
	  {
		  var pagina = "../../reports/documents/sps_reporte_liquidacion_ctas.php?"+params;
	  }
	  ue_cancelar();
	  window.open(pagina,"reporte","menubar=no,toolbar=no,scrollbars=yes,width="+screen.width+",height="+(screen.height-60)+",resizable=yes,top=0,left=0");
	}
}