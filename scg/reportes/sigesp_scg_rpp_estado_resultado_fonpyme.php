<?php
session_start();
header("Pragma: public");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
if(!array_key_exists("la_logusr",$_SESSION)){
	print "<script language=JavaScript>";
	print "close();";
	print "</script>";
}

//-----------------------------------------------------------------------------------------------------------------------------------
function uf_insert_seguridad($as_titulo)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_insert_seguridad
	//		   Access: private
	//	    Arguments: as_titulo // Título del Reporte
	//    Description: función que guarda la seguridad de quien generó el reporte
	//	   Creado Por: Ing. Yesenia Moreno
	// Fecha Creación: 22/09/2006
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	global $io_fun_scg;

	$ls_descripcion="Generó el Reporte ".$as_titulo;
	$lb_valido=$io_fun_scg->uf_load_seguridad_reporte("SCG","sigesp_scg_r_estado_resultado.php",$ls_descripcion);
	return $lb_valido;
}
//-----------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_encabezado_pagina($as_titulo,$as_titulo1,$as_titulo2,$as_titulo3,&$io_pdf)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_encabezadopagina
	//		    Acess: private
	//	    Arguments: as_titulo // Título del Reporte
	//	    		   io_pdf // Instancia de objeto pdf
	//    Description: función que imprime los encabezados por página
	//	   Creado Por: Ing. Yozelin Barragan
	// Fecha Creación: 28/04/2006
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$io_encabezado=$io_pdf->openObject();
	$io_pdf->saveState();
	
	$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,710,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo

	$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
	$tm=300-($li_tm/2);
	$io_pdf->addText($tm,740,11,$as_titulo); // Agregar el título

	$li_tm=$io_pdf->getTextWidth(11,$as_titulo1);
	$tm=290-($li_tm/2);
	$io_pdf->addText($tm,730,11,$as_titulo1); // Agregar el título

	$li_tm=$io_pdf->getTextWidth(11,$as_titulo2);
	$tm=290-($li_tm/2);
	$io_pdf->addText($tm,720,11,$as_titulo2); // Agregar el título

	$li_tm=$io_pdf->getTextWidth(11,$as_titulo3);
	$tm=280-($li_tm/2);
	$io_pdf->addText($tm,710,11,$as_titulo3); // Agregar el título

	$io_pdf->addText(510,725,7,$_SESSION["ls_database"]); // Agregar la Base de datos
	$io_pdf->addText(510,715,8,date("d/m/Y")); // Agregar la Fecha
	$io_pdf->addText(510,705,8,date("h:i a")); // Agregar la hora
	
	
	// cuadro inferior
    $io_pdf->setStrokeColor(0,0,0);
	$io_pdf->setLineStyle(1);
	$io_pdf->line(45,80,130,80);
	$io_pdf->line(210,80,350,80);
	$io_pdf->line(415,80,535,80);		
	$io_pdf->addText(55,70,7,"ALFREDO RIERA"); // Agregar el título
	$io_pdf->addText(60,60,7,"PRESIDENTE"); // Agregar el título
	$io_pdf->addText(250,70,7,"MARYORIS LURUA"); // Agregar el título
	$io_pdf->addText(215,60,7,"GERENTE DE ADMINISTRACION Y FINANZAS"); // Agregar el título
	$io_pdf->addText(450,70,7,"ELIEL RODRIGUEZ"); // Agregar el título
	$io_pdf->addText(425,60,7,"ESPECIALISTA EN CONTABILIDAD"); // Agregar el título
	
	$io_pdf->restoreState();
	$io_pdf->closeObject();
	$io_pdf->addObject($io_encabezado,'all');
}// end function uf_print_encabezadopagina
//--------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_subtitulo($as_titulo,&$io_pdf){
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_detalle
	//		    Acess: private
	//	    Arguments: la_data // arreglo de información
	//	   			   io_pdf // Objeto PDF
	//    Description: función que imprime el subtitulo
	//	   Creado Por: Ing. Gerardo Cordero
	// Fecha Creación: 28/01/2011
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>520, // Ancho de la tabla
						 'maxWidth'=>520, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>520))); // Justificación y ancho de la columna
	$la_columnas = array('titulo'=>'');
	$la_data[]   = array('titulo'=>$as_titulo);
	$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	unset($la_data);
}
//--------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_subtitulo_monto($as_titulo,$as_monto,&$io_pdf){
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_detalle
	//		    Acess: private
	//	    Arguments: la_data // arreglo de información
	//	   			   io_pdf // Objeto PDF
	//    Description: función que imprime el subtitulo
	//	   Creado Por: Ing. Gerardo Cordero
	// Fecha Creación: 28/01/2011
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>520, // Ancho de la tabla
						 'maxWidth'=>520, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>320),
									   'monto'=>array('justification'=>'right','width'=>200))); // Justificación y ancho de la columna
	$la_columnas = array('titulo'=>'','monto'=>'');
	$la_data[]   = array('titulo'=>"<b>".$as_titulo."</b>",'monto'=>"<b>".$as_monto."</b>");
	$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	unset($la_data);
}
//--------------------------------------------------------------------------------------------------------------------------------


//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_detalle($la_data,$li_sli,$li_letra,&$io_pdf){
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_detalle
	//		    Acess: private
	//	    Arguments: la_data // arreglo de información
	//	   			   io_pdf // Objeto PDF
	//    Description: función que imprime el detalle
	//	   Creado Por: Ing. Yozelin Barragan
	// Fecha Creación: 28/04/2006
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => $li_letra, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>$li_sli, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>520, // Ancho de la tabla
						 'maxWidth'=>520, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'left','width'=>70), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>320), // Justificación y ancho de la columna
									   'saldo'=>array('justification'=>'right','width'=>130))); // Justificación y ancho de la columna
	$la_columnas=array('cuenta'=>'<b>Cuenta</b>',
					   'denominacion'=>'<b>Denominación</b>',
					   'saldo'=>'<b>Saldo</b>');
	$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	unset($la_data);
}// end function uf_print_detalle
//--------------------------------------------------------------------------------------------------------------------------------

function uf_is_negative($ad_monto) {
	if ($ad_monto<0) {
		return "(".number_format(abs($ad_monto),0,",",".").")";
	}
	else{
		return number_format($ad_monto,0,",",".");
	}
}

require_once("../../shared/ezpdf/class.ezpdf.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/class_fecha.php");
require_once("../class_funciones_scg.php");
require_once("sigesp_scg_reporte.php");

$io_funciones = new class_funciones();
$io_fecha     = new class_fecha();
$io_fun_scg   = new class_funciones_scg();
$io_report    = new sigesp_scg_reporte();



//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
$ls_hidbot=$_GET["hidbot"];
if($ls_hidbot==1)
{
	$ls_titulo="<b> ESTADO DE RESULTADOS</b>";
	$ls_cmbmesdes=$_GET["cmbmesdes"];
	$ls_cmbagnodes=$_GET["cmbagnodes"];
	if($_SESSION["ls_gestor"]=='INFORMIX')
	{
		$fecdes=$ls_cmbagnodes."-".$ls_cmbmesdes."-01";
		$ldt_fecdes=$ls_cmbagnodes."-".$ls_cmbmesdes."-01";
	}
	else
	{
		$fecdes=$ls_cmbagnodes."-".$ls_cmbmesdes."-01"." 00:00:00";
		$ldt_fecdes=$ls_cmbagnodes."-".$ls_cmbmesdes."-01"." 00:00:00";
	}
	$ls_cmbmeshas=$_GET["cmbmeshas"];
	$ls_cmbagnohas=$_GET["cmbagnohas"];
	$ls_last_day=$io_fecha->uf_last_day($ls_cmbmeshas,$ls_cmbagnohas);
	$fechas=$ls_last_day;
	$ldt_fechas=$io_funciones->uf_convertirdatetobd($ls_last_day);
}
elseif($ls_hidbot==2)
{
	$ls_titulo="<b> ESTADO DE RESULTADOS</b>";
	$fecdes=$_GET["txtfecdes"];
	$ldt_fecdes=$io_funciones->uf_convertirdatetobd($fecdes);
	$fechas=$_GET["txtfechas"];
	$ldt_fechas=$io_funciones->uf_convertirdatetobd($fechas);
}
elseif ($ls_hidbot==3){
	$ls_cmbmesdes=$_GET["cmbmesdes"];
	$ls_cmbagnodes=$_GET["cmbagnodes"];
	 
	switch ($ls_cmbmesdes) {
		case '01':
			$ls_titulo="<b> ESTADO DE RESULTADOS TRIMESTRAL(ENERO-MARZO)</b>";;
			break;

		case '04':
			$ls_titulo="<b> ESTADO DE RESULTADOS TRIMESTRAL(ABRIL-JUNIO)</b>";;
			break;
				
		case '07':
			$ls_titulo="<b> ESTADO DE RESULTADOS TRIMESTRAL(JULIO-SEPTIEMBRE)</b>";;
			break;
				
		case '10':
			$ls_titulo="<b> ESTADO DE RESULTADOS TRIMESTRAL(OCTUBRE-DICIEMBRE)</b>";;
			break;
	}
	 
	if($_SESSION["ls_gestor"]=='INFORMIX')
	{
		$fecdes=$ls_cmbagnodes."-".$ls_cmbmesdes."-01"." 00:00:00";
		$ldt_fecdes=$ls_cmbagnodes."-".$ls_cmbmesdes."-01"." 00:00:00";
	}
	else
	{
		$fecdes=$ls_cmbagnodes."-".$ls_cmbmesdes."-01"." 00:00:00";
		$ldt_fecdes=$ls_cmbagnodes."-".$ls_cmbmesdes."-01"." 00:00:00";
	}
	$ls_cmbmeshas=$_GET["cmbmeshas"];
	$ls_cmbagnohas=$_GET["cmbagnohas"];
	$ls_last_day=$io_fecha->uf_last_day($ls_cmbmeshas,$ls_cmbagnohas);
	$fechas=$ls_last_day;
	$ldt_fechas=$io_funciones->uf_convertirdatetobd($ls_last_day);
}
$li_nivel=$_GET["cmbnivel"];
//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
$li_ano=substr($ldt_periodo,0,4);
$ls_nombre=$_SESSION["la_empresa"]["nombre"];
$ld_fecdes=$io_funciones->uf_convertirfecmostrar($fecdes);
$ld_fechas=$io_funciones->uf_convertirfecmostrar($fechas);
$ls_titulo1="<b> ".$ls_nombre." </b>";
$ls_titulo2="<b> al ".$ld_fechas."</b>";
$ls_titulo3="<b>(Expresado en Bs.)</b>";
//--------------------------------------------------------------------------------------------------------------------------------
error_reporting(E_ALL);
$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
$io_pdf->ezSetCmMargins(4,5,3,3); // Configuración de los margenes en centímetros
uf_print_encabezado_pagina($ls_titulo,$ls_titulo1,$ls_titulo2,$ls_titulo3,$io_pdf); // Imprimimos el encabezado de la página
$lb_valido=uf_insert_seguridad("<b>Estado de Resultado en PDF</b>"); // Seguridad de Reporte

if($lb_valido){
	$data=$io_report->uf_scg_reporte_estado_de_resultado_grupos($ldt_fecdes,$ldt_fechas);
}

if($data->EOF) // Existe algún error ó no hay registros
{
	print("<script language=JavaScript>");
	print(" alert('No hay nada que Reportar');");
	print(" close();");
	print("</script>");
}
else{
	$li_precierre=$_GET["precierre"];
	//totales
	$ld_total_margenfinancierobruto=0;
	$ld_total_margenfinancieroneto=0;
	$ld_total_510=0;
	$ld_total_520=0;
	$ld_total_410=0;
	$ld_total_420=0;
	$ld_total_440=0;
	$ld_total_441=0;
	$ld_total_gasoper=0;
	$ld_total_530=0;
	$ld_total_430=0;
	$ld_total_540=0;
	$ld_total_450=0;
	$ld_total_470=0;
	
	//arreglos de data cuentas nivel 3
	$la_data_ingfin   = array();
	$la_data_ingfin[] = array('cuenta'=>'','denominacion'=>'','saldo'=>'');
	$la_data_gasfin   = array();
	$la_data_gasfin[] = array('cuenta'=>'','denominacion'=>'','saldo'=>'');
	$la_data_inggas = array();
	$la_data_441    = array();
	
	//arreglos de data cuentas nivel 2
	$la_data_510   = array();
	$la_data_410   = array();
	$la_data_520   = array();
	$la_data_520[] = array('cuenta'=>'','denominacion'=>'','saldo'=>'');
	$la_data_440 = array();
	$la_data_530 = array();
	$la_data_530[] = array('cuenta'=>'','denominacion'=>'','saldo'=>'');
	$la_data_430 = array();
	$la_data_540 = array();
	$la_data_540[] = array('cuenta'=>'','denominacion'=>'','saldo'=>'');
	$la_data_450 = array();
	$la_data_470 = array();
	$la_data_470[] = array('cuenta'=>'','denominacion'=>'','saldo'=>'');
	
	//arreglos de data cuentas totales
	$la_data_totalmarfinbru = array();
	$la_data_totalmarfinnet = array();
	$la_data_menos          = array();
	$la_data_gastosoperati  = array();
	$la_data_margeninter    = array();

	//digito tipo de cuenta
	$ls_activo       = $_SESSION["la_empresa"]["activo"];
	$ls_pasivo       = $_SESSION["la_empresa"]["pasivo"];
	$ls_patrimonio   = $_SESSION["la_empresa"]["capital"];
	$ls_ingreso      = $_SESSION["la_empresa"]["ingreso"];
	$ls_gasto        = $_SESSION["la_empresa"]["gasto"];
	while(!$data->EOF){
		$digtipcuenta = substr($data->fields["sc_cuenta"],0,1);
		$codcuenta    = substr($data->fields["sc_cuenta"],0,3);
		$denominacion = $data->fields["denominacion"];
		$debe         = $data->fields["debe"];
		$haber        = $data->fields["haber"];
		$monto        = $data->fields["saldo"];
		$nivel        = $data->fields["nivel"];
		if ($li_precierre==1) {
			$monto=0;
		}
		
		switch ($digtipcuenta) {
			case $ls_activo:
				$monto = abs($monto);
				break;
			
			case $ls_pasivo:
				$monto = abs($monto);
				break;
			
			case $ls_patrimonio:
				$monto = abs($monto);
				break;
			
			case $ls_ingreso:
				if($debe<$haber){
					$monto = abs($monto);
				}
				break;
				
			case $ls_gasto:
				if($debe>$haber){
					$monto = abs($monto);
				}
				break;
		}
		
		$arr_ingfin   = array("511","512","513","514","519");
		$arr_gasfin   = array("414","415","419");
		$arr_inggas   = array("421","422","423");
		
		if ($nivel==2) {
			if ($codcuenta=="510") {
				$la_data_510[] = array('cuenta'=>"<b>".$codcuenta." . 00</b>",'denominacion'=>"<b>".$denominacion."</b>",'saldo'=>"<b>".uf_is_negative($monto)."</b>");
				$ld_total_510 = $monto;
			}
			elseif ($codcuenta=="410"){
				$la_data_410[] = array('cuenta'=>"<b>".$codcuenta." . 00</b>",'denominacion'=>"<b>".$denominacion."</b>",'saldo'=>"<b>".uf_is_negative($monto)."</b>");
				$ld_total_410=$monto;
			}
			elseif ($codcuenta=="520"){
				$la_data_520[] = array('cuenta'=>$codcuenta." . 00",'denominacion'=>$denominacion,'saldo'=>uf_is_negative($monto));
				$ld_total_520  = $ld_total_520 + $monto;
			}
			elseif ($codcuenta=="440"){
				$la_data_440[] = array('cuenta'=>'','denominacion'=>'GASTOS DE TRANSFORMACION','saldo'=>uf_is_negative($monto));
				$ld_total_440=$monto;
			}
			elseif ($codcuenta=="530"){
				$la_data_530[] = array('cuenta'=>$codcuenta." . 00",'denominacion'=>$denominacion,'saldo'=>uf_is_negative($monto));
				$ld_total_530  = $monto;
			}
			elseif ($codcuenta=="430"){
				$la_data_430[] = array('cuenta'=>$codcuenta." . 00",'denominacion'=>$denominacion,'saldo'=>uf_is_negative($monto));
				$ld_total_430  = $monto;
			}
			elseif ($codcuenta=="540"){
				$la_data_540[] = array('cuenta'=>$codcuenta." . 00",'denominacion'=>$denominacion,'saldo'=>uf_is_negative($monto));
				$ld_total_540  = $monto;
			}
			elseif ($codcuenta=="450"){
				$la_data_450[] = array('cuenta'=>$codcuenta." . 00",'denominacion'=>$denominacion,'saldo'=>uf_is_negative($monto));
				$ld_total_450  = $monto;
			}
			elseif ($codcuenta=="470"){
				$la_data_470[] = array('cuenta'=>$codcuenta." . 00",'denominacion'=>$denominacion,'saldo'=>uf_is_negative($monto));
				$ld_total_470  = $monto;
			}
		}
		elseif ($nivel==3){
			if (in_array($codcuenta, $arr_ingfin)) {
				$la_data_ingfin[] = array('cuenta'=>$codcuenta." . 00",'denominacion'=>$denominacion,'saldo'=>uf_is_negative($monto));
			}
			elseif (in_array($codcuenta, $arr_gasfin)){
				$la_data_gasfin[] = array('cuenta'=>$codcuenta." . 00",'denominacion'=>$denominacion,'saldo'=>uf_is_negative($monto));			
			}
			elseif (in_array($codcuenta, $arr_inggas)){
				$la_data_inggas[] = array('cuenta'=>$codcuenta." . 00",'denominacion'=>$denominacion,'saldo'=>uf_is_negative($monto));
				$ld_total_420     = $ld_total_420 + $monto;
			}
			elseif ($codcuenta=="441"){
				$la_data_441[] = array('cuenta'=>$codcuenta." . 00",'denominacion'=>$denominacion,'saldo'=>uf_is_negative($monto));
				$ld_total_441  = $monto;
			}
		}
		
		$data->MoveNext();
	}
	$ld_total_margenfinancierobruto = $ld_total_510-$ld_total_410;
	$ld_total_margenfinancieroneto  = ($ld_total_margenfinancierobruto + $ld_total_520) - $ld_total_420;
	$la_data_menos[] = array('cuenta'=>'','denominacion'=>'','saldo'=>'');
	$la_data_menos[] = array('cuenta'=>'','denominacion'=>'MENOS:','saldo'=>"");
	$ld_total_gasoper = $ld_total_440 - $ld_total_441;
	$la_data_gastosoperati[] = array('cuenta'=>'','denominacion'=>'GASTOS OPERATIVOS','saldo'=>uf_is_negative($ld_total_gasoper));
	$ld_total_marinter = $ld_total_margenfinancieroneto - $ld_total_440;
	$ld_total_marnegocio = ($ld_total_marinter + $ld_total_530) - $ld_total_430;
	$ld_total_brutoantimp = ($ld_total_marnegocio+ $ld_total_540) - $ld_total_450;
	$ld_total_neto = $ld_total_brutoantimp - $ld_total_470;
	
	
	//IMPRIMIENDO LAS CUENTAS 510
	uf_print_detalle($la_data_510,2,7,$io_pdf);
	$la_data_ingfin[] = array('cuenta'=>'','denominacion'=>'','saldo'=>'');
	uf_print_detalle($la_data_ingfin,0,6,$io_pdf);
	
	//IMPRIMIENDO LAS CUENTAS 410
	uf_print_detalle($la_data_410,2,7,$io_pdf);
	$la_data_gasfin[] = array('cuenta'=>'','denominacion'=>'','saldo'=>'');
	uf_print_detalle($la_data_gasfin,0,6,$io_pdf);
	
	//IMPRIMIENDO MARGEN FINANCIERO BRUTO
	uf_print_subtitulo_monto("MARGEN FINANCIERO BRUTO", uf_is_negative($ld_total_margenfinancierobruto), $io_pdf);
	uf_print_detalle($la_data_520,0,6,$io_pdf);
	$la_data_inggas[] = array('cuenta'=>'','denominacion'=>'','saldo'=>'');
	uf_print_detalle($la_data_inggas,0,6,$io_pdf);
	
	//IMPRIMIENDO MARGEN FINANCIERO NETO
	uf_print_subtitulo_monto("MARGEN FINANCIERO NETO", uf_is_negative($ld_total_margenfinancieroneto), $io_pdf);
	uf_print_detalle($la_data_menos,0,6,$io_pdf);
	$la_data_440[] = array('cuenta'=>'','denominacion'=>'','saldo'=>'');
	uf_print_detalle($la_data_440,0,6,$io_pdf);
	uf_print_detalle($la_data_441,0,6,$io_pdf);
	$la_data_gastosoperati[] = array('cuenta'=>'','denominacion'=>'','saldo'=>'');
	uf_print_detalle($la_data_gastosoperati,0,6,$io_pdf);
	
	//IMPRIMIENDO MARGEN DE INTERMEDIACION
	uf_print_subtitulo_monto("MARGEN DE INTERMEDIACION", uf_is_negative($ld_total_marinter), $io_pdf);
	uf_print_detalle($la_data_530,0,6,$io_pdf);
	$la_data_430[] = array('cuenta'=>'','denominacion'=>'','saldo'=>'');
	uf_print_detalle($la_data_430,0,6,$io_pdf);
	
	//IMPRIMIENDO MARGEN DEL NEGOCIO 
	uf_print_subtitulo_monto("MARGEN DEL NEGOCIO", uf_is_negative($ld_total_marnegocio), $io_pdf);
	uf_print_detalle($la_data_540,0,6,$io_pdf);
	$la_data_450[] = array('cuenta'=>'','denominacion'=>'','saldo'=>'');
	uf_print_detalle($la_data_450,0,6,$io_pdf);
	
	//
	uf_print_subtitulo_monto("RESULTADO BRUTO ANTES DE IMPUESTO", uf_is_negative($ld_total_brutoantimp), $io_pdf);
	$la_data_470[] = array('cuenta'=>'','denominacion'=>'','saldo'=>'');
	uf_print_detalle($la_data_470,0,6,$io_pdf);
	
	//
	uf_print_subtitulo_monto("RESULTADO NETO", uf_is_negative($ld_total_neto), $io_pdf);
}


if (isset($d) && $d){
	$ls_pdfcode = $io_pdf->ezOutput(1);
	$ls_pdfcode = str_replace("\n","\n<br>",htmlspecialchars($ls_pdfcode));
	echo '<html><body>';
	echo trim($ls_pdfcode);
	echo '</body></html>';
}
else{
	$io_pdf->ezStream();
}

unset($io_pdf);
unset($io_report);
unset($io_funciones);
?>