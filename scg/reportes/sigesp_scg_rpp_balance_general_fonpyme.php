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
function uf_insert_seguridad($as_titulo){
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
	$lb_valido=$io_fun_scg->uf_load_seguridad_reporte("SCG","sigesp_scg_r_balance_general.php",$ls_descripcion);
	return $lb_valido;
}
//-----------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_encabezado_pagina($as_titulo,$as_titulo1,$as_titulo2,$as_titulo3,&$io_pdf){
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
	$tm=306-($li_tm/2);
	$io_pdf->addText($tm,740,11,$as_titulo); // Agregar el título

	$li_tm=$io_pdf->getTextWidth(11,$as_titulo1);
	$tm=306-($li_tm/2);
	$io_pdf->addText($tm,730,11,$as_titulo1); // Agregar el título

	$li_tm=$io_pdf->getTextWidth(11,$as_titulo2);
	$tm=306-($li_tm/2);
	$io_pdf->addText($tm,720,11,$as_titulo2); // Agregar el título

	$li_tm=$io_pdf->getTextWidth(11,$as_titulo3);
	$tm=306-($li_tm/2);
	$io_pdf->addText($tm,710,11,$as_titulo3); // Agregar el título

	$io_pdf->addText(510,740,7,$_SESSION["ls_database"]); // Agregar la Base de datos
	$io_pdf->addText(510,730,8,date("d/m/Y")); // Agregar la Fecha
	$io_pdf->addText(510,720,8,date("h:i a")); // Agregar la hora
	
	
	
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
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>520, // Ancho de la tabla
						 'maxWidth'=>520, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>520))); // Justificación y ancho de la columna
	$la_columnas = array('titulo'=>'');
	$la_data[0]   = array('titulo'=>"");
	$la_data[1]   = array('titulo'=>$as_titulo);
	$la_data[2]   = array('titulo'=>"");
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

function uf_print_firmas(&$io_pdf) {
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
}



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
require_once("sigesp_scg_class_bal_general.php");

$io_report  = new sigesp_scg_class_bal_general();
$io_funciones=new class_funciones();
$io_fecha=new class_fecha();
$io_fun_scg=new class_funciones_scg();
//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
$ls_tituloreporte=$_GET["tituloreporte"];
$ls_cmbmes=$_GET["cmbmes"];
$ls_cmbagno=$_GET["cmbagno"];
$ls_last_day=$io_fecha->uf_last_day($ls_cmbmes,$ls_cmbagno);
$fechas=$ls_last_day;
$ldt_fechas=$io_funciones->uf_convertirdatetobd($ls_last_day)." 00:00:00";
//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
$ls_nombre=$_SESSION["la_empresa"]["nombre"];
$li_ano=substr($ldt_periodo,0,4);


$ld_fechas=$io_funciones->uf_convertirfecmostrar($fechas);
switch ($ls_tituloreporte) {
	case "N":
		$ls_titulo="<b>BALANCE GENERAL</b>";
		break;
	case "M":
		$ls_titulo="<b>BALANCE GENERAL MENSUAL</b>";
		break;
	case "T":
		$ls_titulo="<b>BALANCE GENERAL TRIMESTRAL</b>";
		break;
	case "A":
		$ls_titulo="<b>BALANCE GENERAL ANAUAL</b>";
		break;
	
}
$ls_titulo1="<b> ".$ls_nombre." </b>";
$ls_titulo2="<b> al ".$ld_fechas."</b>";
$ls_titulo3="<b>(Expresado en Bs.)</b>";
//--------------------------------------------------------------------------------------------------------------------------------
// Cargar datastore con los datos del reporte
$lb_valido=uf_insert_seguridad("<b>Balance General en PDF</b>"); // Seguridad de Reporte
if($lb_valido){
	$rs_data=$io_report->uf_balance_general_fonpyme($ldt_fechas);
}

if($rs_data->EOF) // no hay registros
{
	print("<script language=JavaScript>");
	print(" alert('No hay nada que Reportar');");
	//print(" close();");
	print("</script>");
}
else// Imprimimos el reporte
{
	error_reporting(E_ALL);
	$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
	$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
	$io_pdf->ezSetCmMargins(3,4.3,3,3); // Configuración de los margenes en centímetros
	uf_print_encabezado_pagina($ls_titulo,$ls_titulo1,$ls_titulo2,$ls_titulo3,$io_pdf); // Imprimimos el encabezado de la página
	
	
	//totales
	$ld_total_pasivopatrimonio=0;
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
	$la_data_diponibilidades   = array();
	$la_data_diponibilidades[] = array('cuenta'=>"",'denominacion'=>"",'saldo'=>"");
	$la_data_inversiones       = array();
	$la_data_inversiones[]     = array('cuenta'=>"",'denominacion'=>"",'saldo'=>"");
	$la_data_deudores	       = array();
	$la_data_deudores[]        = array('cuenta'=>"",'denominacion'=>"",'saldo'=>"");
	$la_data_intereses         = array();
	$la_data_intereses[]       = array('cuenta'=>"",'denominacion'=>"",'saldo'=>"");
	$la_data_otrasinversion    = array();
	$la_data_otrasinversion[]  = array('cuenta'=>"",'denominacion'=>"",'saldo'=>"");
	$la_data_pasivopatri       = array();
	$la_data_pasivopatri[]     = array('cuenta'=>"",'denominacion'=>"",'saldo'=>"");
	$la_data_interesesxpagar   = array();
	$la_data_interesesxpagar[] = array('cuenta'=>"",'denominacion'=>"",'saldo'=>"");
	
	//arreglos de data cuentas nivel 2
	$la_data_diponibilidades2   = array();
	$la_data_inversiones2       = array();
	$la_data_deudores2          = array();
	$la_data_intereses2         = array();
	$la_data_otrasinversion2    = array();
	$la_data_pasivopatri2       = array();
	$la_data_otraobligacion     = array();
	$la_data_interesesxpagar2   = array();
	$la_data_interesesxpagar2[] = array('cuenta'=>"",'denominacion'=>"",'saldo'=>"");
	$la_data_acumulaciones      = array();
	$la_data_310                = array();
	$la_data_311                = array();
	$la_data_311[] 				= array('cuenta'=>"",'denominacion'=>"",'saldo'=>"");
	$la_data_330                = array();
	$la_data_340                = array();
	$la_data_340[] 				= array('cuenta'=>"",'denominacion'=>"",'saldo'=>"");
	$la_data_350                = array();
	$la_data_350[] 				= array('cuenta'=>"",'denominacion'=>"",'saldo'=>"");
	$la_data_360                = array();
	$la_data_360[] 				= array('cuenta'=>"",'denominacion'=>"",'saldo'=>"");
	$la_data_370                = array();
	$la_data_370[] 				= array('cuenta'=>"",'denominacion'=>"",'saldo'=>"");
	$la_data_300                = array();
	$la_data_300[] 				= array('cuenta'=>"",'denominacion'=>"",'saldo'=>"");
	$la_data_610                = array();
	$la_data_610[] 				= array('cuenta'=>"",'denominacion'=>"",'saldo'=>"");
	$la_data_620                = array();
	$la_data_620[] 				= array('cuenta'=>"",'denominacion'=>"",'saldo'=>"");
	$la_data_810                = array();
	$la_data_810[] 				= array('cuenta'=>"",'denominacion'=>"",'saldo'=>"");
	$la_data_820                = array();
	$la_data_820[] 				= array('cuenta'=>"",'denominacion'=>"",'saldo'=>"");
	$la_data_160                = array();
	$la_data_160[]              = array('cuenta'=>"",'denominacion'=>"",'saldo'=>"");
	$la_data_170                = array();
	$la_data_170[]              = array('cuenta'=>"",'denominacion'=>"",'saldo'=>"");
	$la_data_180                = array();
	$la_data_180[]              = array('cuenta'=>"",'denominacion'=>"",'saldo'=>"");
	
	
	//arreglos de data cuentas nivel 1
	$la_data_totalpasivos       = array();
	$la_data_totalpasivos[]     = array('cuenta'=>"",'denominacion'=>"",'saldo'=>"");
	$la_data_300                = array();
	$la_data_300[]     = array('cuenta'=>"",'denominacion'=>"",'saldo'=>"");
	$la_data_100                = array();
	$la_data_100[]              = array('cuenta'=>"",'denominacion'=>"",'saldo'=>"");
	
	//informacion adicional
	$ld_cap_suscrito=$io_report->uf_obtener_capital($ldt_fechas,"001");
	$ld_cap_nopagado=$io_report->uf_obtener_capital($ldt_fechas,"002");
	$la_data_311[] = array('cuenta'=>"",'denominacion'=>"CAPITAL SUSCRITO",'saldo'=>uf_is_negative($ld_cap_suscrito));
	$la_data_311[] = array('cuenta'=>"",'denominacion'=>"CAPITAL NO PAGADO",'saldo'=>uf_is_negative($ld_cap_nopagado));
	
	//digito tipo de cuenta
	$ls_activo       = $_SESSION["la_empresa"]["activo"];
	$ls_pasivo       = $_SESSION["la_empresa"]["pasivo"];
	$ls_patrimonio   = $_SESSION["la_empresa"]["capital"];
	$ls_ingreso      = $_SESSION["la_empresa"]["ingreso"];
	$ls_gasto        = $_SESSION["la_empresa"]["gasto"];
	while (!$rs_data->EOF) {
		$digtipcuenta = substr($rs_data->fields["sc_cuenta"],0,1);
		$codcuenta = substr($rs_data->fields["sc_cuenta"], 0, 3);
		$dencuenta = $rs_data->fields["denominacion"];
		$debe         = $rs_data->fields["debe"];
		$haber        = $rs_data->fields["haber"];
		$salcuenta = $rs_data->fields["saldo"];
		$nivcuenta = $rs_data->fields["nivel"];
		
		if(($codcuenta!="119")&&($codcuenta!="129")&&($codcuenta!="139")&&($codcuenta!="149")&&($codcuenta!="159")){
			switch ($digtipcuenta) {
				case $ls_activo:
					$salcuenta = abs($salcuenta);
					break;
			
				case $ls_pasivo:
					$salcuenta = abs($salcuenta);
					break;
			
				case $ls_patrimonio:
					$salcuenta = abs($salcuenta);
					break;
			
				case $ls_ingreso:
					if($debe<$haber){
						$salcuenta = abs($salcuenta);
					}
					break;
				
				case $ls_gasto:
					if($debe>$haber){
						$salcuenta = abs($salcuenta);
					}
					break;
			}
		}
		
		
		$arr_diponibilidades = array("111","113","114","116","119");
		$arr_inversiones     = array("121","122","123","124","125","129");
		$arr_deudores        = array("131","132","133","134","139");
		$arr_intereses       = array("141","142","143","144","145","149");
		$arr_otrasinversion  = array("151","159");
		$arr_pasivopatri     = array("240","241","242","243","244","245","246");
		$arr_interesesxpagar = array("264","265");
		$arr_inggas          = array("421","422","423");
		if ($nivcuenta==1) {
			if ($codcuenta == '100'){
				$la_data_100[] = array('cuenta'=>"<b>".$codcuenta.".00</b>",'denominacion'=>"<b>TOTAL ".$dencuenta."</b>",'saldo'=>"<b>".uf_is_negative($salcuenta)."</b>");
			}
			elseif ($codcuenta == '200'){
				$la_data_totalpasivos[] = array('cuenta'=>"<b>".$codcuenta.".00</b>",'denominacion'=>"<b>TOTAL DEL ".$dencuenta."</b>",'saldo'=>"<b>".uf_is_negative($salcuenta)."</b>");
				$ld_total_pasivopatrimonio = $ld_total_pasivopatrimonio + $salcuenta; 
			}
			elseif ($codcuenta == '300'){
				$la_data_300[] = array('cuenta'=>"<b>".$codcuenta.".00</b>",'denominacion'=>"<b>TOTAL ".$dencuenta."</b>",'saldo'=>"<b>".uf_is_negative($salcuenta)."</b>");
				$ld_total_pasivopatrimonio = $ld_total_pasivopatrimonio + $salcuenta;
			}
		}
		elseif ($nivcuenta==2){
			if($codcuenta == '110'){
				$la_data_diponibilidades2[] = array('cuenta'=>"<b>".$codcuenta.".00</b>",'denominacion'=>"<b>".$dencuenta."</b>",'saldo'=>"<b>".uf_is_negative($salcuenta)."</b>");
			}
			elseif ($codcuenta == '120'){
				$la_data_inversiones2[] = array('cuenta'=>"<b>".$codcuenta.".00</b>",'denominacion'=>"<b>".$dencuenta."</b>",'saldo'=>"<b>".uf_is_negative($salcuenta)."</b>");
			}
			elseif ($codcuenta == '130'){
				$la_data_deudores2[] = array('cuenta'=>"<b>".$codcuenta.".00</b>",'denominacion'=>"<b>".$dencuenta."</b>",'saldo'=>"<b>".uf_is_negative($salcuenta)."</b>");
			}
			elseif ($codcuenta == '140'){
				$la_data_intereses2[] = array('cuenta'=>"<b>".$codcuenta.".00</b>",'denominacion'=>"<b>".$dencuenta."</b>",'saldo'=>"<b>".uf_is_negative($salcuenta)."</b>");
			}
			elseif ($codcuenta == '150'){
				$la_data_otrasinversion2[] = array('cuenta'=>"<b>".$codcuenta.".00</b>",'denominacion'=>"<b>".$dencuenta."</b>",'saldo'=>"<b>".uf_is_negative($salcuenta)."</b>");
			}
			elseif ($codcuenta == '160'){
				$la_data_160[] = array('cuenta'=>"<b>".$codcuenta.".00</b>",'denominacion'=>"<b>".$dencuenta."</b>",'saldo'=>"<b>".uf_is_negative($salcuenta)."</b>");
			}
			elseif ($codcuenta == '170'){
				$la_data_170[] = array('cuenta'=>"<b>".$codcuenta.".00</b>",'denominacion'=>"<b>".$dencuenta."</b>",'saldo'=>"<b>".uf_is_negative($salcuenta)."</b>");
			}
			elseif ($codcuenta == '180'){
				$la_data_180[] = array('cuenta'=>"<b>".$codcuenta.".00</b>",'denominacion'=>"<b>".$dencuenta."</b>",'saldo'=>"<b>".uf_is_negative($salcuenta)."</b>");
			}
			elseif ($codcuenta == '240'){
				$la_data_pasivopatri2[] = array('cuenta'=>"<b>".$codcuenta.".00</b>",'denominacion'=>"<b>".$dencuenta."</b>",'saldo'=>"<b>".uf_is_negative($salcuenta)."</b>");
			}
			elseif ($codcuenta == '250'){
				$la_data_otraobligacion[] = array('cuenta'=>"<b>".$codcuenta.".00</b>",'denominacion'=>"<b>".$dencuenta."</b>",'saldo'=>"<b>".uf_is_negative($salcuenta)."</b>");
			}
			elseif ($codcuenta == '260'){
				$la_data_interesesxpagar2[] = array('cuenta'=>"<b>".$codcuenta.".00</b>",'denominacion'=>"<b>".$dencuenta."</b>",'saldo'=>"<b>".uf_is_negative($salcuenta)."</b>");
			}
			elseif ($codcuenta == '270'){
				$la_data_acumulaciones[] = array('cuenta'=>"<b>".$codcuenta.".00</b>",'denominacion'=>"<b>".$dencuenta."</b>",'saldo'=>"<b>".uf_is_negative($salcuenta)."</b>");
			}
			elseif ($codcuenta == '310'){
				$la_data_310[] = array('cuenta'=>"<b>".$codcuenta.".00</b>",'denominacion'=>"<b>".$dencuenta."</b>",'saldo'=>"<b>".uf_is_negative($salcuenta)."</b>");
			}
			elseif ($codcuenta == '330'){
				$la_data_330[] = array('cuenta'=>"<b>".$codcuenta.".00</b>",'denominacion'=>"<b>".$dencuenta."</b>",'saldo'=>"<b>".uf_is_negative($salcuenta)."</b>");
			}
			elseif ($codcuenta == '340'){
				$la_data_340[] = array('cuenta'=>"<b>".$codcuenta.".00</b>",'denominacion'=>"<b>".$dencuenta."</b>",'saldo'=>"<b>".uf_is_negative($salcuenta)."</b>");
			}
			elseif ($codcuenta == '350'){
				$la_data_350[] = array('cuenta'=>"<b>".$codcuenta.".00</b>",'denominacion'=>"<b>".$dencuenta."</b>",'saldo'=>"<b>".uf_is_negative($salcuenta)."</b>");
			}
			elseif ($codcuenta == '360'){
				$la_data_360[] = array('cuenta'=>"<b>".$codcuenta.".00</b>",'denominacion'=>"<b>".$dencuenta."</b>",'saldo'=>"<b>".uf_is_negative($salcuenta)."</b>");
			}
			elseif ($codcuenta == '370'){
				$la_data_370[] = array('cuenta'=>"<b>".$codcuenta.".00</b>",'denominacion'=>"<b>".$dencuenta."</b>",'saldo'=>"<b>".uf_is_negative($salcuenta)."</b>");
			}
			elseif ($codcuenta == '610'){
				$la_data_610[] = array('cuenta'=>"<b>".$codcuenta.".00</b>",'denominacion'=>"<b>".$dencuenta."</b>",'saldo'=>"<b>".uf_is_negative($salcuenta)."</b>");
			}
			elseif ($codcuenta == '620'){
				$la_data_620[] = array('cuenta'=>"<b>".$codcuenta.".00</b>",'denominacion'=>"<b>".$dencuenta."</b>",'saldo'=>"<b>".uf_is_negative($salcuenta)."</b>");
			}
			elseif ($codcuenta == '810'){
				$la_data_810[] = array('cuenta'=>"<b>".$codcuenta.".00</b>",'denominacion'=>"<b>".$dencuenta."</b>",'saldo'=>"<b>".uf_is_negative($salcuenta)."</b>");
			}
			elseif ($codcuenta == '820'){
				$la_data_820[] = array('cuenta'=>"<b>".$codcuenta.".00</b>",'denominacion'=>"<b>".$dencuenta."</b>",'saldo'=>"<b>".uf_is_negative($salcuenta)."</b>");
			}
			if ($codcuenta=="510") {
				$ld_total_510 = $salcuenta;
			}
			elseif ($codcuenta=="410"){
				$ld_total_410 = $salcuenta;
			}
			elseif ($codcuenta=="520"){
				$ld_total_520 = $salcuenta;
			}
			elseif ($codcuenta=="440"){
				$ld_total_440=$salcuenta;
			}
			elseif ($codcuenta=="530"){
				$ld_total_530  = $salcuenta;
			}
			elseif ($codcuenta=="430"){
				$ld_total_430  = $salcuenta;
			}
			elseif ($codcuenta=="540"){
				$ld_total_540  = $salcuenta;
			}
			elseif ($codcuenta=="450"){
				$ld_total_450  = $salcuenta;
			}
			elseif ($codcuenta=="470"){
				$ld_total_470  = $salcuenta;
			}
		}
		elseif ($nivcuenta==3){
			if (in_array($codcuenta, $arr_diponibilidades)) {
				$la_data_diponibilidades[] = array('cuenta'=>$codcuenta.".00",'denominacion'=>$dencuenta,'saldo'=>uf_is_negative($salcuenta));
			}
			elseif (in_array($codcuenta, $arr_inversiones)) {
				$la_data_inversiones[] = array('cuenta'=>$codcuenta.".00",'denominacion'=>$dencuenta,'saldo'=>uf_is_negative($salcuenta));
			}
			elseif (in_array($codcuenta, $arr_deudores)){
				$la_data_deudores[] = array('cuenta'=>$codcuenta.".00",'denominacion'=>$dencuenta,'saldo'=>uf_is_negative($salcuenta));
			}
			elseif (in_array($codcuenta, $arr_intereses)){
				$la_data_intereses[] = array('cuenta'=>$codcuenta.".00",'denominacion'=>$dencuenta,'saldo'=>uf_is_negative($salcuenta));
			}
			elseif (in_array($codcuenta, $arr_otrasinversion)){
				$la_data_otrasinversion[] = array('cuenta'=>$codcuenta.".00",'denominacion'=>$dencuenta,'saldo'=>uf_is_negative($salcuenta));
			}
			elseif (in_array($codcuenta, $arr_pasivopatri)){
				$la_data_pasivopatri[] = array('cuenta'=>$codcuenta.".00",'denominacion'=>$dencuenta,'saldo'=>uf_is_negative($salcuenta));
			}
			elseif (in_array($codcuenta, $arr_interesesxpagar)){
				$la_data_interesesxpagar[] = array('cuenta'=>$codcuenta.".00",'denominacion'=>$dencuenta,'saldo'=>uf_is_negative($salcuenta));
			}
			elseif ($codcuenta == '311'){
				$la_data_311[] = array('cuenta'=>$codcuenta.".00",'denominacion'=>$dencuenta,'saldo'=>uf_is_negative($salcuenta));
			}
			elseif (in_array($codcuenta, $arr_inggas)){
				$ld_total_420     = $ld_total_420 + $salcuenta;
			}
		}
		
		$rs_data->MoveNext();
	}

	//CALCULANDO GASTO OPERATIVO
	$ld_total_margenfinancierobruto = $ld_total_510-$ld_total_410;
	$ld_total_margenfinancieroneto  = ($ld_total_margenfinancierobruto + $ld_total_520) - $ld_total_420;
	$ld_total_marinter = $ld_total_margenfinancieroneto - $ld_total_440;
	$ld_total_marnegocio = ($ld_total_marinter + $ld_total_530) - $ld_total_430;
	$ld_total_brutoantimp = ($ld_total_marnegocio+ $ld_total_540) - $ld_total_450;
	$ld_total_neto = $ld_total_brutoantimp - $ld_total_470;
	$la_data_gesoperativa   = array();
	$la_data_gesoperativa[] = array('cuenta'=>"      .00",'denominacion'=>"GESTION OPERATIVA",'saldo'=>uf_is_negative($ld_total_neto));
	//CALCULANDO GASTO OPERATIVO
	$ld_total_pasivopatrimonio = $ld_total_pasivopatrimonio+$ld_total_neto;
	$la_data_totalpasivopatrimonio[] = array('cuenta'=>'','denominacion'=>'','saldo'=>'');
	$la_data_totalpasivopatrimonio[] = array('cuenta'=>'','denominacion'=>'TOTAL DE PASIVO Y PATRIMONIO','saldo'=>uf_is_negative($ld_total_pasivopatrimonio));
	
	
	
	//IMPRIMIENDO ACTIVO
	$ls_subtitulo='<b>ACTIVO</b>';
	uf_print_subtitulo($ls_subtitulo, $io_pdf);
	
	//IMPRIMIENDO DISPONIBILIDADES (110)
	uf_print_detalle($la_data_diponibilidades2,2,7, $io_pdf);
	$la_data_diponibilidades[] = array('cuenta'=>"",'denominacion'=>"",'saldo'=>"");
	uf_print_detalle($la_data_diponibilidades,0,6, $io_pdf);
	
	//IMPRIMIENDO INVERSIONES (120)
	uf_print_detalle($la_data_inversiones2,2,7, $io_pdf);
	$la_data_inversiones[] = array('cuenta'=>"",'denominacion'=>"",'saldo'=>"");
	uf_print_detalle($la_data_inversiones,0,6, $io_pdf);
	
	//IMPRIMIENDO DEUDORES (130)
	uf_print_detalle($la_data_deudores2,2,7, $io_pdf);
	$la_data_deudores[] = array('cuenta'=>"",'denominacion'=>"",'saldo'=>"");
	uf_print_detalle($la_data_deudores,0,6, $io_pdf);
	
	//IMPRIMIENDO INTERESES X COBRAR (140)
	uf_print_detalle($la_data_intereses2,2,7, $io_pdf);
	$la_data_intereses[] = array('cuenta'=>"",'denominacion'=>"",'saldo'=>"");
	uf_print_detalle($la_data_intereses,0,6, $io_pdf);
	
	//IMPRIMIENDO INVERSIONES OTROS FONDOS (150)
	uf_print_detalle($la_data_otrasinversion2,2,7, $io_pdf);
	uf_print_detalle($la_data_otrasinversion,0,6, $io_pdf);
	
	//IMPRIMIENDO INVERSIONES OTROS FONDOS (160)
	uf_print_detalle($la_data_160,1,7, $io_pdf);
	
	//IMPRIMIENDO INVERSIONES OTROS FONDOS (170)
	uf_print_detalle($la_data_170,1,7, $io_pdf);
	
	//IMPRIMIENDO INVERSIONES OTROS FONDOS (180)
	uf_print_detalle($la_data_180,1,7, $io_pdf);
	
	//IMPRIMIENDO INVERSIONES OTROS FONDOS (100)
	uf_print_detalle($la_data_100,1,7, $io_pdf);
	
	//IMPRIMIENDO PASIVO
	$ls_subtitulo='<b>PASIVO</b>';
	uf_print_subtitulo($ls_subtitulo, $io_pdf);
	
	//IMPRIMIENDO FINANCIAMIENTOS OBTENIDOS (240)
	uf_print_detalle($la_data_pasivopatri2,2,6, $io_pdf);
	$la_data_pasivopatri[] = array('cuenta'=>"",'denominacion'=>"",'saldo'=>"");
	uf_print_detalle($la_data_pasivopatri,0,6, $io_pdf);
	
	//IMPRIMIENDO OTRAS OBLIGACIONES (250)
	uf_print_detalle($la_data_otraobligacion,2,6, $io_pdf);
	
	//IMPRIMIENDO INTERESES POR PAGAR (260)
	uf_print_detalle($la_data_interesesxpagar2,1,6, $io_pdf);
	$la_data_interesesxpagar[] = array('cuenta'=>"",'denominacion'=>"",'saldo'=>"");
	uf_print_detalle($la_data_interesesxpagar,0,6, $io_pdf);
	
	//IMPRIMIENDO ACUMULACIONES (270)
	uf_print_detalle($la_data_acumulaciones,2,6, $io_pdf);
	
	//IMPRIMIENDO TOTAL PASIVO (200)
	uf_print_detalle($la_data_totalpasivos,1,6, $io_pdf);
	
	uf_print_detalle($la_data_gesoperativa,0,6, $io_pdf);
	
	//IMPRIMIENDO PATRIMONIO
	$ls_subtitulo='<b>PATRIMONIO</b>';
	uf_print_subtitulo($ls_subtitulo, $io_pdf);
	
	//IMPRIMIENDO CAPITAL (310)
	uf_print_detalle($la_data_310,2,6, $io_pdf);
	$la_data_311[] = array('cuenta'=>"",'denominacion'=>"",'saldo'=>"");
	uf_print_detalle($la_data_311,0,6, $io_pdf);
	
	uf_print_detalle($la_data_330,2,6, $io_pdf);
	
	
	uf_print_detalle($la_data_340,1,6, $io_pdf);
	
	uf_print_detalle($la_data_350,1,6, $io_pdf);
	
	uf_print_detalle($la_data_360,1,6, $io_pdf);
	
	uf_print_detalle($la_data_370,1,6, $io_pdf);
	
	uf_print_detalle($la_data_300,1,6, $io_pdf);
	
	uf_print_detalle($la_data_totalpasivopatrimonio,0,6, $io_pdf);
	
	uf_print_detalle($la_data_610,1,6, $io_pdf);
	uf_print_detalle($la_data_620,1,6, $io_pdf);
	uf_print_detalle($la_data_810,1,6, $io_pdf);
	uf_print_detalle($la_data_820,1,6, $io_pdf);
	uf_print_firmas($io_pdf);

	
	
	if (isset($d) && $d)
	{
		$ls_pdfcode = $io_pdf->ezOutput(1);
		$ls_pdfcode = str_replace("\n","\n<br>",htmlspecialchars($ls_pdfcode));
		echo '<html><body>';
		echo trim($ls_pdfcode);
		echo '</body></html>';
	}
	else
	{
		$io_pdf->ezStream();
	}
	unset($io_pdf);
}//else
unset($io_report);
unset($io_funciones);
?>