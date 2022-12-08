<?php
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";		
		print "</script>";		
	}

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // T�tulo del Reporte
		//    Description: funci�n que guarda la seguridad de quien gener� el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 06/07/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$lb_valido=true;
		$ls_descripcion="Gener� el Reporte ".$as_titulo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_constanciatrabajo.php",$ls_descripcion);
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_leer_archivo($as_archivo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_leer_archivo
		//		   Access: private 
		//	    Arguments: as_archivo //  ruta donde se encuentra el archivo
		//    Description: funci�n que lee un archivo de texto y lo mete en una cadena
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 08/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_texto = file($as_archivo);
		$li_tamano = sizeof($ls_texto);
		$ls_textocompleto="";
		for($li_i=0;$li_i<$li_tamano;$li_i++)
		{
			$ls_textocompleto=$ls_textocompleto.$ls_texto[$li_i];
		}
		return $ls_textocompleto;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_snorh_class_report.php");
	$io_report=new sigesp_snorh_class_report();
	include("../../shared/class_folder/class_numero_a_letra.php");
	$io_numero_letra= new class_numero_a_letra();
	//imprime numero con los valore por defecto
	//cambia a minusculas
	$io_numero_letra->setMayusculas(1);
	//cambia a femenino
	$io_numero_letra->setGenero(1);
	//cambia moneda
	$io_numero_letra->setMoneda("Bolivares");
	//cambia prefijo
	$io_numero_letra->setPrefijo("");
	//cambia sufijo
	$io_numero_letra->setSufijo("");
	//imprime numero con los cambios
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	require_once("../../shared/class_folder/class_fecha.php");
	$io_fecha=new class_fecha();
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
	$ls_codcont=$io_fun_nomina->uf_obtenervalor_get("codcont","");
	$ls_codnom=$io_fun_nomina->uf_obtenervalor_get("codnom","");
	$li_rac=$io_fun_nomina->uf_obtenervalor_get("rac","");
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_fecha=$io_fun_nomina->uf_obtenervalor_get("fecha","");
	$ls_tiporeporte=$io_fun_nomina->uf_obtenervalor_get("tiporeporte",0);
	$ls_mesactual=$io_fun_nomina->uf_obtenervalor_get("mesactual","");
	$ls_anocurnom=$io_fun_nomina->uf_obtenervalor_get("anocurnom","");
	
	$ls_parametros=$io_fun_nomina->uf_obtenervalor_get("parametro","");
	$ls_anticipo=$io_fun_nomina->uf_obtenervalor_get("anticipo","");
	$arr_codper=split("-",$ls_parametros); 
	$li_totcodper=count($arr_codper);

	$li_mesanterior=(intval($ls_mesactual)-1);
	if($li_mesanterior==0)
	{
		$li_mesanterior=12;
		$ls_anocurnom=(intval($ls_anocurnom)-1);
	}
	$ls_mesanterior=str_pad($li_mesanterior,2,"0",0);
	global $ls_tiporeporte;
	
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad("CONSTANCIA En Word"); // Seguridad de Reporte
	if($lb_valido)
	{
		if ($li_totcodper==1)
		{
			$lb_valido=$io_report->uf_constanciatrabajo_constancia($ls_codcont,$ls_codnom,$ls_codperdes,$ls_codperhas); // Obtenemos el detalle del reporte
			if ($lb_valido)
			{
				$li_totrow=$io_report->rs_data->RecordCount(); 
			}
		}
		else
		{
			$lb_valido=$io_report->uf_constanciatrabajo_constancia_lote($ls_codcont,$ls_codnom,$ls_codperdes,$ls_codperhas,$arr_codper,$li_totcodper);
			if ($lb_valido)
			{
				$li_totrow=$io_report->rs_data->RecordCount(); 
			}
		}
	}
	if($lb_valido==false) // Existe alg�n error � no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		if ($li_totcodper==1)
		{
			while ((!$io_report->rs_data->EOF)&&($lb_valido))
			{
				$ls_ente=$_SESSION["la_empresa"]["nombre"];
				$ld_fecha=date("d/m/Y");
				$ls_dia_act=substr($ld_fecha,0,2);
				$ls_mes_act=$io_fecha->uf_load_nombre_mes(substr($ld_fecha,3,2));
				$ls_ano_act=substr($ld_fecha,6,4);			
				$ls_original=$io_report->rs_data->fields["arcrtfcont"];			
				$lb_valido=$io_report->uf_constanciatrabajo_personal($ls_codnom,$li_rac,$ls_codperdes,$ls_codperhas); // Obtenemos el detalle del reporte
				if($lb_valido)
				{
					$li_totrow_det=$io_report->rs_detalle->RecordCount();
					$ls_archivo="../documentos/original/".$ls_original;
					$ls_copia=substr($ls_original,0,strrpos($ls_original,"."));
					$ls_salida="../documentos/copia/".$ls_copia."-".$_SESSION["la_logusr"].".rtf";
					$ls_contenido="";
					$ls_contenido=uf_leer_archivo($ls_archivo);
					$la_matriz=explode("sectd",$ls_contenido);
					$ls_cabecera=$la_matriz[0]."sectd";
					$li_inicio=strlen($ls_cabecera);
					$li_final=strrpos($ls_contenido,"}");
					$li_longitud=$li_final-$li_inicio;
					$ls_nuevocuerpo=substr($ls_contenido,$li_inicio,$li_longitud);
					$ls_punt=fopen($ls_salida,"w");
					fputs($ls_punt,$ls_cabecera);
					$li_s=0;
					while ((!$io_report->rs_detalle->EOF)&&($lb_valido))
					{
						$ls_codper=$io_report->rs_detalle->fields["codper"];
						$ls_cedper=$io_report->rs_detalle->fields["cedper"];
						$ls_apeper=$io_report->rs_detalle->fields["apeper"];		
						$ls_nomper=$io_report->rs_detalle->fields["nomper"];		
						$ls_descar=$io_report->rs_detalle->fields["descar"];
						$ls_denominacioncargo=$io_report->rs_detalle->fields["descasicar"];			
						$ld_fecingper=$io_report->rs_detalle->fields["fecingper"];
						$ls_mes=$io_fecha->uf_load_nombre_mes(substr($ld_fecingper,5,2));
						$ls_fechaingreso="el ".substr($ld_fecingper,8,2)." de ".$ls_mes." de ".substr($ld_fecingper,0,4);
						$ld_fecegrper=$io_report->rs_detalle->fields["fecegrper"];
						$ls_mes=$io_fecha->uf_load_nombre_mes(substr($ld_fecegrper,5,2));
						$ls_fechaegreso="el ".substr($ld_fecegrper,8,2)." de ".$ls_mes." de ".substr($ld_fecegrper,0,4);
						$ls_dirper=$io_report->rs_detalle->fields["dirper"];		
						$ld_fecnacper=$io_funciones->uf_convertirfecmostrar($io_report->rs_detalle->fields["fecnacper"]);		
						$ls_edocivper=$io_report->rs_detalle->fields["edocivper"];	
						switch($ls_edocivper)
						{
							case "S": // Soltero
								$ls_edocivper="Soltero";
								break;
							case "C": // Casado
								$ls_edocivper="Casado";
								break;
							case "D": // Divociado
								$ls_edocivper="Divociado";
								break;
							case "V": // Viudo
								$ls_edocivper="Viudo";
								break;
						}
						$ls_nacper=$io_report->rs_detalle->fields["nacper"];
						switch($ls_nacper)
						{
							case "V": // Venezolano
								$ls_nacper="Venezolano";
								break;
							case "E": // Extranjero
								$ls_nacper="Extranjero";
								break;
						}
						$ls_tipnom=$io_report->rs_detalle->fields["tipnom"];	
						switch($ls_tipnom)
						{
							case "1": // Empleado Fijo
								$ls_tipnom="Empleado Fijo";
								break;
							case "2": // Empleado Contratado
								$ls_tipnom="Empleado Contratado";
								break;
							case "3": // Obrero Fijo
								$ls_tipnom="Obrero Fijo";
								break;
							case "4": // Obrero Contratado
								$ls_tipnom="Obrero Contratado";
								break;
							case "5": // Docente Fijo
								$ls_tipnom="Docente Fijo";
								break;
							case "6": // Docente Contratado
								$ls_tipnom="Docente Contratado";
								break;
							case "7": // Jubilado
								$ls_tipnom="Jubilado";
								break;
							case "8": // Comision de Servicios
								$ls_tipnom="Comision de Servicios";
								break;
							case "9": // Libre Nombramiento
								$ls_tipnom="Libre Nombramiento";
								break;
							case "10": // Militar
								$ls_tipnom="Militar";
								break;
							case "11": // Honorarios Profesionales
								$ls_tipnom="Honorarios Profesionales";
								break;
							case "12": // Pensionado
								$ls_tipnom="Pensionado";
								break;
							case "13": // Suplente
								$ls_tipnom="Suplente";
								break;
							case "14": // Contratado
								$ls_tipnom="Contratado";
								break;
							case "15": // Incapacitados
								$ls_tipnom="Incapacitados";
								break;
						}
						if($ls_tiporeporte==1)
						{
							$ls_prefijo="Bs.F.";
						}
						else
						{
							$ls_prefijo="Bs.";
						}
						$ls_sueldo_sin_fomato=$io_report->rs_detalle->fields["sueper"];
						$ls_telhabper=$io_report->rs_detalle->fields["telhabper"];	
						$ls_telmovper=$io_report->rs_detalle->fields["telmovper"];	
						$ls_desuniadm=$io_report->rs_detalle->fields["desuniadm"];	
						$li_horper=$io_fun_nomina->uf_formatonumerico($io_report->rs_detalle->fields["horper"]);	
						$li_sueper=$io_fun_nomina->uf_formatonumerico($io_report->rs_detalle->fields["sueper"]);		
						$io_numero_letra->setNumero($io_report->rs_detalle->fields["sueper"]);
						$ls_sueper=$io_numero_letra->letra();
						$ls_sueper=$ls_sueper." (".$ls_prefijo." ".$li_sueper.")";
						$li_sueintper=$io_fun_nomina->uf_formatonumerico($io_report->rs_detalle->fields["sueintper"]);	
						$io_numero_letra->setNumero($io_report->rs_detalle->fields["sueintper"]);
						$ls_sueintper=$io_numero_letra->letra();
						$ls_sueintper=$ls_sueintper." (".$ls_prefijo." ".$li_sueintper.")";
						$li_sueproper=$io_fun_nomina->uf_formatonumerico($io_report->rs_detalle->fields["sueproper"]);	
						$io_numero_letra->setNumero($io_report->rs_detalle->fields["sueproper"]);
						$ls_sueproper=$io_numero_letra->letra();
						$ls_sueproper=$ls_sueproper." (".$ls_prefijo." ".$li_sueproper.")";
						$ls_desded=$io_report->rs_detalle->fields["desded"];	
						$ls_destipper=$io_report->rs_detalle->fields["destipper"];	
						$ls_fecjub=$io_report->rs_detalle->fields["fecjubper"];
						$ls_mes2=$io_fecha->uf_load_nombre_mes(substr($ls_fecjub,5,2));
						$ls_fecjub="el ".substr($ls_fecjub,8,2)." de ".$ls_mes2." de ".substr($ls_fecjub,0,4);	
						$li_sueintper_mensual=0;
						$li_sueproper_mensual=0;
						$li_salnorpermensual=0;
						$lb_valido=$io_report->uf_constanciatrabajo_integralpromedio_mensual($ls_codnom,$ls_codper,$ls_mesanterior,$ls_anocurnom,$li_sueintper_mensual,
																							 $li_sueproper_mensual,$li_salnorpermensual); // Obtenemos el detalle del reporte
						$li_sueintper_mensual_sinformato=$li_sueintper_mensual;
						$io_numero_letra->setNumero($li_sueintper_mensual);
						$ls_sueintper_mensual=$io_numero_letra->letra();
						$li_sueintper_mensual=$io_fun_nomina->uf_formatonumerico($li_sueintper_mensual);
						$ls_sueintper_mensual=$ls_sueintper_mensual." (".$ls_prefijo." ".$li_sueintper_mensual.")";
						$io_numero_letra->setNumero($li_sueproper_mensual);
						$ls_sueproper_mensual=$io_numero_letra->letra();
						$li_sueproper_mensual=$io_fun_nomina->uf_formatonumerico($li_sueproper_mensual);
						$ls_sueproper_mensual=$ls_sueproper_mensual." (".$ls_prefijo." ".$li_sueproper_mensual.")";
						$li_salnorper=$io_fun_nomina->uf_formatonumerico($io_report->rs_detalle->fields["salnorper"]);	
						$io_numero_letra->setNumero($io_report->rs_detalle->fields["salnorper"]);
						$ls_salnorper=$io_numero_letra->letra();
						$ls_salnorper=$ls_salnorper." (".$ls_prefijo." ".$li_salnorper.")";
						$ls_gerencia=$io_report->rs_detalle->fields["denger"];
						$io_numero_letra->setNumero($li_salnorpermensual);
						$ls_salnorpermensual=$io_numero_letra->letra();
						$li_salnorpermensual=$io_fun_nomina->uf_formatonumerico($li_salnorpermensual);
						$ls_salnorpermensual=$ls_salnorpermensual." (".$ls_prefijo." ".$li_salnorpermensual.")";	
						$ls_cuerpo=$ls_nuevocuerpo;
						if($ls_anticipo=="0")
						{
							$ls_cuerpo=str_replace("\$ls_ente",$ls_ente,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_dia",$ls_dia_act,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_mes",$ls_mes_act,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_ano",$ls_ano_act,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_nombres",$ls_nomper,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_apellidos",$ls_apeper,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_cedula",$ls_cedper,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_cargo",$ls_descar,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$li_sueldo",$ls_sueper,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ld_fecha_ingreso",$ls_fechaingreso,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ld_fecha_egreso",$ls_fechaegreso,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_direccion",$ls_dirper,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ld_fecha_nacimiento",$ld_fecnacper,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_edo_civil",$ls_edocivper,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_nacionalidad",$ls_nacper,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_telefono_hab",$ls_telhabper,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_telefono_mov",$ls_telmovper,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_unidad_administrativa",$ls_desuniadm,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$li_horas_lab",$li_horper,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$li_inte_sueldo",$ls_sueintper,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$li_salario_normal",$ls_salnorper,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$li_prom_sueldo",$ls_sueproper,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_dedicacion",$ls_desded,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_tipo_personal",$ls_destipper,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_tipo_nomina",$ls_tipnom,$ls_cuerpo);		
							$ls_cuerpo=str_replace("\$li_mensual_inte_sueldo",$ls_sueintper_mensual,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$li_mensual_prom_sueldo",$ls_sueproper_mensual,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_fecjub",$ls_fecjub,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_gerencia",$ls_gerencia,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$li_numerosueldo",$ls_sueldo_sin_fomato,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$li_num_sue_integral",$li_sueintper_mensual_sinformato,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$li_normalmensual",$ls_salnorpermensual,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_denomin_cargo",$ls_denominacioncargo,$ls_cuerpo);
							
							$li_long=strlen($ls_cuerpo);
							$a=0;
							$ls_pal_bus=array();
							$ls_pal_bus= explode("];",$ls_cuerpo);
							$ls_cont=count($ls_pal_bus)-1;
							for($li_x=1;$li_x<=$ls_cont;$li_x++)
							{
								//Creando variable que encierra la formula
								$li_posicion1=strpos($ls_cuerpo,"FORMULA[",$a);
								$li_posicion2=strpos($ls_cuerpo,"];",$a);
								$li_posicion2=$li_posicion2-$li_posicion1;
								$ls_var_formula=substr($ls_cuerpo,$li_posicion1,$li_posicion2+2);
								//Creando variable que encierra la formula
								
								$li_pos_for=strpos($ls_cuerpo,"FORMULA[",$a);
								$li_pos_for=$li_pos_for+8;
								$li_pos_forhas=strpos($ls_cuerpo,"];",$a);
								$li_pos_forhas=$li_pos_forhas-$li_pos_for;
								$ls_formula_agre=substr($ls_cuerpo,$li_pos_for,$li_pos_forhas);
								$ls_formula_agre=str_replace(",",".",$ls_formula_agre);
								$lb_correcto=$io_evaluate->uf_evaluar_nomina($ls_formula_agre,&$li_resultado);
								$li_resul_numero=$io_fun_nomina->uf_formatonumerico($li_resultado);
								$io_numero_letra->setNumero($li_resultado);
								$ls_resultado=$io_numero_letra->letra();
								$ls_resultado=$ls_resultado." (".$ls_prefijo." ".$li_resul_numero.")";
								$ls_cuerpo=str_replace("$ls_var_formula",$ls_resultado,$ls_cuerpo);
								$a++;
							}
							fputs($ls_punt,$ls_cuerpo);
							$li_s++;
						}
						else
						{
							$lb_valido=$io_report->uf_constanciatrabajo_anticipos($ls_codper,$rs_data); // Obtenemos el detalle del reporte
							if($lb_valido)
							{
								while(!$rs_data->EOF)
								{
									$ls_codant=$rs_data->fields["codant"];
									$ls_estant=$rs_data->fields["estant"];
									switch($ls_estant)
									{
										case "R": // Registro
											$ls_estant="Registrado";
											break;
										case "A": // Aprobada
											$ls_estant="Aprobado";
											break;
										case "C": // Contabilizada
											$ls_estant="Contabilizado";
											break;
										case "X": // Anulada
											$ls_estant="Anulado";
											break;
									}
									$ld_fecant=$io_funciones->uf_convertirfecmostrar($rs_data->fields["fecant"]);
									$li_monpreant=$rs_data->fields["monpreant"];
									$li_monintant=$rs_data->fields["monintant"];
									$li_monantant=$rs_data->fields["monantant"];
									$li_monantint=$rs_data->fields["monantint"];
									$li_monant=$rs_data->fields["monant"];
									$li_monint=$rs_data->fields["monint"];
									$ls_motant=$rs_data->fields["motant"];
									$ls_obsant=$rs_data->fields["obsant"];
									
									$io_numero_letra->setNumero($li_monpreant);
									$ls_monpreant=$io_numero_letra->letra();
									$li_monpreant=$io_fun_nomina->uf_formatonumerico($li_monpreant);
									$ls_monpreant=$ls_monpreant." (".$ls_prefijo." ".$li_monpreant.")";
									$io_numero_letra->setNumero($li_monintant);
									$ls_monintant=$io_numero_letra->letra();
									$li_monintant=$io_fun_nomina->uf_formatonumerico($li_monintant);
									$ls_monintant=$ls_monintant." (".$ls_prefijo." ".$li_monintant.")";
									$io_numero_letra->setNumero($li_monantant);
									$ls_monantant=$io_numero_letra->letra();
									$li_monantant=$io_fun_nomina->uf_formatonumerico($li_monantant);
									$ls_monantant=$ls_monantant." (".$ls_prefijo." ".$li_monantant.")";
									$io_numero_letra->setNumero($li_monantint);
									$ls_monantint=$io_numero_letra->letra();
									$li_monantint=$io_fun_nomina->uf_formatonumerico($li_monantint);
									$ls_monantint=$ls_monantint." (".$ls_prefijo." ".$li_monantint.")";
									$io_numero_letra->setNumero($li_monant);
									$ls_monant=$io_numero_letra->letra();
									$li_monant=$io_fun_nomina->uf_formatonumerico($li_monant);
									$ls_monant=$ls_monant." (".$ls_prefijo." ".$li_monant.")";
									$io_numero_letra->setNumero($li_monint);
									$ls_monint=$io_numero_letra->letra();
									$li_monint=$io_fun_nomina->uf_formatonumerico($li_monint);
									$ls_monint=$ls_monint." (".$ls_prefijo." ".$li_monint.")";

									$ls_anticipo=$ls_cuerpo;
									$ls_anticipo=str_replace("\$ls_ente",$ls_ente,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_dia",$ls_dia_act,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_mes",$ls_mes_act,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_ano",$ls_ano_act,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_nombres",$ls_nomper,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_apellidos",$ls_apeper,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_cedula",$ls_cedper,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_cargo",$ls_descar,$ls_anticipo);
									$ls_anticipo=str_replace("\$li_sueldo",$ls_sueper,$ls_anticipo);
									$ls_anticipo=str_replace("\$ld_fecha_ingreso",$ls_fechaingreso,$ls_anticipo);
									$ls_anticipo=str_replace("\$ld_fecha_egreso",$ls_fechaegreso,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_direccion",$ls_dirper,$ls_anticipo);
									$ls_anticipo=str_replace("\$ld_fecha_nacimiento",$ld_fecnacper,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_edo_civil",$ls_edocivper,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_nacionalidad",$ls_nacper,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_telefono_hab",$ls_telhabper,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_telefono_mov",$ls_telmovper,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_unidad_administrativa",$ls_desuniadm,$ls_anticipo);
									$ls_anticipo=str_replace("\$li_horas_lab",$li_horper,$ls_anticipo);
									$ls_anticipo=str_replace("\$li_inte_sueldo",$ls_sueintper,$ls_anticipo);
									$ls_anticipo=str_replace("\$li_salario_normal",$ls_salnorper,$ls_anticipo);
									$ls_anticipo=str_replace("\$li_prom_sueldo",$ls_sueproper,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_dedicacion",$ls_desded,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_tipo_personal",$ls_destipper,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_tipo_nomina",$ls_tipnom,$ls_anticipo);		
									$ls_anticipo=str_replace("\$li_mensual_inte_sueldo",$ls_sueintper_mensual,$ls_anticipo);
									$ls_anticipo=str_replace("\$li_mensual_prom_sueldo",$ls_sueproper_mensual,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_fecjub",$ls_fecjub,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_gerencia",$ls_gerencia,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_codant",$ls_codant,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_estant",$ls_estant,$ls_anticipo);
									$ls_anticipo=str_replace("\$ld_fecant",$ld_fecant,$ls_anticipo);
									$ls_anticipo=str_replace("\$li_monpreant",$ls_monpreant,$ls_anticipo);
									$ls_anticipo=str_replace("\$li_monintant",$ls_monintant,$ls_anticipo);
									$ls_anticipo=str_replace("\$li_monantant",$ls_monantant,$ls_anticipo);
									$ls_anticipo=str_replace("\$li_monantint",$ls_monantint,$ls_anticipo);
									$ls_anticipo=str_replace("\$li_monant",$ls_monant,$ls_anticipo);
									$ls_anticipo=str_replace("\$li_monint",$ls_monint,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_motant",$ls_motant,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_obsant",$ls_obsant,$ls_anticipo);
									$ls_anticipo=str_replace("\$li_numerosueldo",$ls_sueldo_sin_fomato,$ls_anticipo);
									$ls_anticipo=str_replace("\$li_num_sue_integral",$li_sueintper_mensual_sinformato,$ls_anticipo);
									$ls_anticipo=str_replace("\$li_normalmensual",$ls_salnorpermensual,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_denomin_cargo",$ls_denominacioncargo,$ls_anticipo);
									
									$li_long=strlen($ls_anticipo);
									$a=0;
									$ls_pal_bus=array();
									$ls_pal_bus= explode("];",$ls_anticipo);
									$ls_cont=count($ls_pal_bus)-1;
									for($li_x=1;$li_x<=$ls_cont;$li_x++)
									{
										//Creando variable que encierra la formula
										$li_posicion1=strpos($ls_anticipo,"FORMULA[",$a);
										$li_posicion2=strpos($ls_anticipo,"];",$a);
										$li_posicion2=$li_posicion2-$li_posicion1;
										$ls_var_formula=substr($ls_anticipo,$li_posicion1,$li_posicion2+2);
										//Creando variable que encierra la formula
										
										$li_pos_for=strpos($ls_anticipo,"FORMULA[",$a);
										$li_pos_for=$li_pos_for+8;
										$li_pos_forhas=strpos($ls_anticipo,"];",$a);
										$li_pos_forhas=$li_pos_forhas-$li_pos_for;
										$ls_formula_agre=substr($ls_anticipo,$li_pos_for,$li_pos_forhas);
										$ls_formula_agre=str_replace(",",".",$ls_formula_agre);
										$lb_correcto=$io_evaluate->uf_evaluar_nomina($ls_formula_agre,&$li_resultado);
										$li_resul_numero=$io_fun_nomina->uf_formatonumerico($li_resultado);
										$io_numero_letra->setNumero($li_resultado);
										$ls_resultado=$io_numero_letra->letra();
										$ls_resultado=$ls_resultado." (".$ls_prefijo." ".$li_resul_numero.")";
										$ls_anticipo=str_replace("$ls_var_formula",$ls_resultado,$ls_anticipo);
										$a++;
									}
									fputs($ls_punt,$ls_anticipo);
									$rs_data->MoveNext();
									if(!$rs_data->EOF)
									{
										$ls_salto="\par \page \par";
										fputs($ls_punt,$ls_salto);
									}
								}
							}
						}
						if(($li_s<$li_totrow_det)&&(($ls_anticipo=="0")))
						{
							$ls_salto="\par \page \par";
							fputs($ls_punt,$ls_salto);
						}
						$io_report->rs_detalle->MoveNext();
					}
					$io_report->rs_data->MoveNext();
					fputs($ls_punt,"}");
					fclose($ls_punt);
					@chmod($ls_salida,0755);
					if($lb_valido) // Si no ocurrio ning�n error
					{
						header ("Content-Disposition: attachment; filename=".$ls_copia."-".$_SESSION["la_logusr"].".rtf\n\n");
						header ("Content-Type: application/octet-stream");
						header ("Content-Length: ".filesize($ls_salida));
						readfile($ls_salida);
					}
					else  // Si hubo alg�n error
					{
						print("<script language=JavaScript>");
						print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
						print(" close();");
						print("</script>");		
					}
				}
			}
		}
		else
		{ 
			while ((!$io_report->rs_data->EOF)&&($lb_valido))
			{ 
				$ls_ente=$_SESSION["la_empresa"]["nombre"];
				$ld_fecha=date("d/m/Y");
				$ls_dia_act=substr($ld_fecha,0,2);
				$ls_mes_act=$io_fecha->uf_load_nombre_mes(substr($ld_fecha,3,2));
				$ls_ano_act=substr($ld_fecha,6,4);			
				$ls_original=$io_report->rs_data->fields["arcrtfcont"];			
				$lb_valido=$io_report->uf_constanciatrabajo_personal_lote($ls_codnom,$li_rac,$arr_codper,$li_totcodper); // Obtenemos el detalle del reporte			
				if($lb_valido)
				{
					$li_totrow_det=$io_report->rs_detalle->RecordCount();
					$ls_archivo="../documentos/original/".$ls_original;
					$ls_copia=substr($ls_original,0,strrpos($ls_original,"."));
					$ls_salida="../documentos/copia/".$ls_copia."-".$_SESSION["la_logusr"].".rtf";
					$ls_contenido="";
					$ls_contenido=uf_leer_archivo($ls_archivo);
					$la_matriz=explode("sectd",$ls_contenido);
					$ls_cabecera=$la_matriz[0]."sectd";
					$li_inicio=strlen($ls_cabecera);
					$li_final=strrpos($ls_contenido,"}");
					$li_longitud=$li_final-$li_inicio;
					$ls_nuevocuerpo=substr($ls_contenido,$li_inicio,$li_longitud);
					$ls_punt=fopen($ls_salida,"w");
					fputs($ls_punt,$ls_cabecera);			
					$li_s=0;				
					while ((!$io_report->rs_detalle->EOF)&&($lb_valido))
					{
						$ls_codper=$io_report->rs_detalle->fields["codper"];
						$ls_cedper=$io_report->rs_detalle->fields["cedper"];
						$ls_apeper=$io_report->rs_detalle->fields["apeper"];		
						$ls_nomper=$io_report->rs_detalle->fields["nomper"];		
						$ls_descar=$io_report->rs_detalle->fields["descar"];
						$ls_denominacioncargo=$io_report->rs_detalle->fields["descasicar"];			
						$ld_fecingper=$io_report->rs_detalle->fields["fecingper"];
						$ls_mes=$io_fecha->uf_load_nombre_mes(substr($ld_fecingper,5,2));
						$ls_fechaingreso="el ".substr($ld_fecingper,8,2)." de ".$ls_mes." de ".substr($ld_fecingper,0,4);
						$ld_fecegrper=$io_report->rs_detalle->fields["fecegrper"];
						$ls_mes=$io_fecha->uf_load_nombre_mes(substr($ld_fecegrper,5,2));
						$ls_fechaegreso="el ".substr($ld_fecegrper,8,2)." de ".$ls_mes." de ".substr($ld_fecegrper,0,4);
						$ls_dirper=$io_report->rs_detalle->fields["dirper"];		
						$ld_fecnacper=$io_funciones->uf_convertirfecmostrar($io_report->rs_detalle->fields["fecnacper"]);		
						$ls_edocivper=$io_report->rs_detalle->fields["edocivper"];	
						switch($ls_edocivper)
						{
							case "S": // Soltero
								$ls_edocivper="Soltero";
								break;
							case "C": // Casado
								$ls_edocivper="Casado";
								break;
							case "D": // Divociado
								$ls_edocivper="Divociado";
								break;
							case "V": // Viudo
								$ls_edocivper="Viudo";
								break;
						}
						$ls_nacper=$io_report->rs_detalle->fields["nacper"];
						switch($ls_nacper)
						{
							case "V": // Venezolano
								$ls_nacper="Venezolano";
								break;
							case "E": // Extranjero
								$ls_nacper="Extranjero";
								break;
						}
						$ls_tipnom=$io_report->rs_detalle->fields["tipnom"];	
						switch($ls_tipnom)
						{
							case "1": // Empleado Fijo
								$ls_tipnom="Empleado Fijo";
								break;
							case "2": // Empleado Contratado
								$ls_tipnom="Empleado Contratado";
								break;
							case "3": // Obrero Fijo
								$ls_tipnom="Obrero Fijo";
								break;
							case "4": // Obrero Contratado
								$ls_tipnom="Obrero Contratado";
								break;
							case "5": // Docente Fijo
								$ls_tipnom="Docente Fijo";
								break;
							case "6": // Docente Contratado
								$ls_tipnom="Docente Contratado";
								break;
							case "7": // Jubilado
								$ls_tipnom="Jubilado";
								break;
							case "8": // Comision de Servicios
								$ls_tipnom="Comision de Servicios";
								break;
							case "9": // Libre Nombramiento
								$ls_tipnom="Libre Nombramiento";
								break;
						}
						if($ls_tiporeporte==1)
						{
							$ls_prefijo="Bs.F.";
						}
						else
						{
							$ls_prefijo="Bs.";
						}
						$ls_sueldo_sin_fomato=$io_report->rs_detalle->fields["sueper"];
						$ls_telhabper=$io_report->rs_detalle->fields["telhabper"];	
						$ls_telmovper=$io_report->rs_detalle->fields["telmovper"];	
						$ls_desuniadm=$io_report->rs_detalle->fields["desuniadm"];	
						$li_horper=$io_fun_nomina->uf_formatonumerico($io_report->rs_detalle->fields["horper"]);	
						$li_sueper=$io_fun_nomina->uf_formatonumerico($io_report->rs_detalle->fields["sueper"]);		
						$io_numero_letra->setNumero($io_report->rs_detalle->fields["sueper"]);
						$ls_sueper=$io_numero_letra->letra();
						$ls_sueper=$ls_sueper." (".$ls_prefijo." ".$li_sueper.")";
						$li_sueintper=$io_fun_nomina->uf_formatonumerico($io_report->rs_detalle->fields["sueintper"]);	
						$io_numero_letra->setNumero($io_report->rs_detalle->fields["sueintper"]);
						$ls_sueintper=$io_numero_letra->letra();
						$ls_sueintper=$ls_sueintper." (".$ls_prefijo." ".$li_sueintper.")";
						$li_sueproper=$io_fun_nomina->uf_formatonumerico($io_report->rs_detalle->fields["sueproper"]);	
						$io_numero_letra->setNumero($io_report->rs_detalle->fields["sueproper"]);
						$ls_sueproper=$io_numero_letra->letra();
						$ls_sueproper=$ls_sueproper." (".$ls_prefijo." ".$li_sueproper.")";
						$ls_desded=$io_report->rs_detalle->fields["desded"];	
						$ls_destipper=$io_report->rs_detalle->fields["destipper"];
						$ls_fecjub=$io_report->rs_detalle->fields["fecjubper"];
						$ls_mes2=$io_fecha->uf_load_nombre_mes(substr($ls_fecjub,5,2));
						$ls_fecjub="el ".substr($ls_fecjub,8,2)." de ".$ls_mes2." de ".substr($ls_fecjub,0,4);	
						$ls_gerencia=$io_report->rs_detalle->fields["denger"];
						$li_sueintper_mensual=0;
						$li_sueproper_mensual=0;
						$li_salnorpermensual=0;
						$lb_valido=$io_report->uf_constanciatrabajo_integralpromedio_mensual($ls_codnom,$ls_codper,$ls_mesanterior,$ls_anocurnom,$li_sueintper_mensual,
																							 $li_sueproper_mensual,$li_salnorpermensual); // Obtenemos el detalle del reporte
						$li_sueintper_mensual_sinformato=$li_sueintper_mensual;
						$io_numero_letra->setNumero($li_sueintper_mensual);
						$ls_sueintper_mensual=$io_numero_letra->letra();
						$li_sueintper_mensual=$io_fun_nomina->uf_formatonumerico($li_sueintper_mensual);
						$ls_sueintper_mensual=$ls_sueintper_mensual." (".$ls_prefijo." ".$li_sueintper_mensual.")";
						$io_numero_letra->setNumero($li_sueproper_mensual);
						$ls_sueproper_mensual=$io_numero_letra->letra();
						$li_sueproper_mensual=$io_fun_nomina->uf_formatonumerico($li_sueproper_mensual);
						$ls_sueproper_mensual=$ls_sueproper_mensual." (".$ls_prefijo." ".$li_sueproper_mensual.")";						
						$li_salnorper=$io_fun_nomina->uf_formatonumerico($io_report->rs_detalle->fields["salnorper"]);	
						$io_numero_letra->setNumero($io_report->rs_detalle->fields["salnorper"]);
						$ls_salnorper=$io_numero_letra->letra();
						$ls_salnorper=$ls_salnorper." (".$ls_prefijo." ".$li_salnorper.")";
						$io_numero_letra->setNumero($li_salnorpermensual);
						$ls_salnorpermensual=$io_numero_letra->letra();
						$li_salnorpermensual=$io_fun_nomina->uf_formatonumerico($li_salnorpermensual);
						$ls_salnorpermensual=$ls_salnorpermensual." (".$ls_prefijo." ".$li_salnorpermensual.")";	
						$ls_cuerpo=$ls_nuevocuerpo;
						if($ls_anticipo=="0")
						{
							$ls_cuerpo=str_replace("\$ls_ente",$ls_ente,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_dia",$ls_dia_act,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_mes",$ls_mes_act,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_ano",$ls_ano_act,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_nombres",$ls_nomper,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_apellidos",$ls_apeper,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_cedula",$ls_cedper,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_cargo",$ls_descar,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$li_sueldo",$ls_sueper,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ld_fecha_ingreso",$ls_fechaingreso,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ld_fecha_egreso",$ls_fechaegreso,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_direccion",$ls_dirper,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ld_fecha_nacimiento",$ld_fecnacper,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_edo_civil",$ls_edocivper,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_nacionalidad",$ls_nacper,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_telefono_hab",$ls_telhabper,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_telefono_mov",$ls_telmovper,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_unidad_administrativa",$ls_desuniadm,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$li_horas_lab",$li_horper,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$li_inte_sueldo",$ls_sueintper,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$li_prom_sueldo",$ls_sueproper,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_dedicacion",$ls_desded,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_tipo_personal",$ls_destipper,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_tipo_nomina",$ls_tipnom,$ls_cuerpo);		
							$ls_cuerpo=str_replace("\$li_mensual_inte_sueldo",$ls_sueintper_mensual,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$li_mensual_prom_sueldo",$ls_sueproper_mensual,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_fecjub",$ls_fecjub,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$li_salario_normal",$ls_salnorper,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_gerencia",$ls_gerencia,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$li_numerosueldo",$ls_sueldo_sin_fomato,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$li_num_sue_integral",$li_sueintper_mensual_sinformato,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$li_normalmensual",$ls_salnorpermensual,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_denomin_cargo",$ls_denominacioncargo,$ls_cuerpo);
							
							$li_long=strlen($ls_cuerpo);
							$a=0;
							$ls_pal_bus=array();
							$ls_pal_bus= explode("];",$ls_cuerpo);
							$ls_cont=count($ls_pal_bus)-1;
							for($li_x=1;$li_x<=$ls_cont;$li_x++)
							{
								//Creando variable que encierra la formula
								$li_posicion1=strpos($ls_cuerpo,"FORMULA[",$a);
								$li_posicion2=strpos($ls_cuerpo,"];",$a);
								$li_posicion2=$li_posicion2-$li_posicion1;
								$ls_var_formula=substr($ls_cuerpo,$li_posicion1,$li_posicion2+2);
								//Creando variable que encierra la formula
								
								$li_pos_for=strpos($ls_cuerpo,"FORMULA[",$a);
								$li_pos_for=$li_pos_for+8;
								$li_pos_forhas=strpos($ls_cuerpo,"];",$a);
								$li_pos_forhas=$li_pos_forhas-$li_pos_for;
								$ls_formula_agre=substr($ls_cuerpo,$li_pos_for,$li_pos_forhas);
								$ls_formula_agre=str_replace(",",".",$ls_formula_agre);
								$lb_correcto=$io_evaluate->uf_evaluar_nomina($ls_formula_agre,&$li_resultado);
								$li_resul_numero=$io_fun_nomina->uf_formatonumerico($li_resultado);
								$io_numero_letra->setNumero($li_resultado);
								$ls_resultado=$io_numero_letra->letra();
								$ls_resultado=$ls_resultado." (".$ls_prefijo." ".$li_resul_numero.")";
								$ls_cuerpo=str_replace("$ls_var_formula",$ls_resultado,$ls_cuerpo);
								$a++;
							}
							fputs($ls_punt,$ls_cuerpo);
							$li_s++;
						}
						else
						{
							$lb_valido=$io_report->uf_constanciatrabajo_anticipos($ls_codper,$rs_data); // Obtenemos el detalle del reporte
							if($lb_valido)
							{
								while(!$rs_data->EOF)
								{
									$ls_codant=$rs_data->fields["codant"];
									$ls_estant=$rs_data->fields["estant"];
									switch($ls_estant)
									{
										case "R": // Registro
											$ls_estant="Registrado";
											break;
										case "A": // Aprobada
											$ls_estant="Aprobado";
											break;
										case "C": // Contabilizada
											$ls_estant="Contabilizado";
											break;
										case "X": // Anulada
											$ls_estant="Anulado";
											break;
									}
									$ld_fecant=$io_funciones->uf_convertirfecmostrar($rs_data->fields["fecant"]);
									$li_monpreant=$rs_data->fields["monpreant"];
									$li_monintant=$rs_data->fields["monintant"];
									$li_monantant=$rs_data->fields["monantant"];
									$li_monantint=$rs_data->fields["monantint"];
									$li_monant=$rs_data->fields["monant"];
									$li_monint=$rs_data->fields["monint"];
									$ls_motant=$rs_data->fields["motant"];
									$ls_obsant=$rs_data->fields["obsant"];
									
									$io_numero_letra->setNumero($li_monpreant);
									$ls_monpreant=$io_numero_letra->letra();
									$li_monpreant=$io_fun_nomina->uf_formatonumerico($li_monpreant);
									$ls_monpreant=$ls_monpreant." (".$ls_prefijo." ".$li_monpreant.")";
									$io_numero_letra->setNumero($li_monintant);
									$ls_monintant=$io_numero_letra->letra();
									$li_monintant=$io_fun_nomina->uf_formatonumerico($li_monintant);
									$ls_monintant=$ls_monintant." (".$ls_prefijo." ".$li_monintant.")";
									$io_numero_letra->setNumero($li_monantant);
									$ls_monantant=$io_numero_letra->letra();
									$li_monantant=$io_fun_nomina->uf_formatonumerico($li_monantant);
									$ls_monantant=$ls_monantant." (".$ls_prefijo." ".$li_monantant.")";
									$io_numero_letra->setNumero($li_monantint);
									$ls_monantint=$io_numero_letra->letra();
									$li_monantint=$io_fun_nomina->uf_formatonumerico($li_monantint);
									$ls_monantint=$ls_monantint." (".$ls_prefijo." ".$li_monantint.")";
									$io_numero_letra->setNumero($li_monant);
									$ls_monant=$io_numero_letra->letra();
									$li_monant=$io_fun_nomina->uf_formatonumerico($li_monant);
									$ls_monant=$ls_monant." (".$ls_prefijo." ".$li_monant.")";
									$io_numero_letra->setNumero($li_monint);
									$ls_monint=$io_numero_letra->letra();
									$li_monint=$io_fun_nomina->uf_formatonumerico($li_monint);
									$ls_monint=$ls_monint." (".$ls_prefijo." ".$li_monint.")";

									$ls_anticipo=$ls_cuerpo;
									$ls_anticipo=str_replace("\$ls_ente",$ls_ente,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_dia",$ls_dia_act,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_mes",$ls_mes_act,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_ano",$ls_ano_act,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_nombres",$ls_nomper,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_apellidos",$ls_apeper,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_cedula",$ls_cedper,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_cargo",$ls_descar,$ls_anticipo);
									$ls_anticipo=str_replace("\$li_sueldo",$ls_sueper,$ls_anticipo);
									$ls_anticipo=str_replace("\$ld_fecha_ingreso",$ls_fechaingreso,$ls_anticipo);
									$ls_anticipo=str_replace("\$ld_fecha_egreso",$ls_fechaegreso,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_direccion",$ls_dirper,$ls_anticipo);
									$ls_anticipo=str_replace("\$ld_fecha_nacimiento",$ld_fecnacper,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_edo_civil",$ls_edocivper,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_nacionalidad",$ls_nacper,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_telefono_hab",$ls_telhabper,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_telefono_mov",$ls_telmovper,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_unidad_administrativa",$ls_desuniadm,$ls_anticipo);
									$ls_anticipo=str_replace("\$li_horas_lab",$li_horper,$ls_anticipo);
									$ls_anticipo=str_replace("\$li_inte_sueldo",$ls_sueintper,$ls_anticipo);
									$ls_anticipo=str_replace("\$li_salario_normal",$ls_salnorper,$ls_anticipo);
									$ls_anticipo=str_replace("\$li_prom_sueldo",$ls_sueproper,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_dedicacion",$ls_desded,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_tipo_personal",$ls_destipper,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_tipo_nomina",$ls_tipnom,$ls_anticipo);		
									$ls_anticipo=str_replace("\$li_mensual_inte_sueldo",$ls_sueintper_mensual,$ls_anticipo);
									$ls_anticipo=str_replace("\$li_mensual_prom_sueldo",$ls_sueproper_mensual,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_fecjub",$ls_fecjub,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_gerencia",$ls_gerencia,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_codant",$ls_codant,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_estant",$ls_estant,$ls_anticipo);
									$ls_anticipo=str_replace("\$ld_fecant",$ld_fecant,$ls_anticipo);
									$ls_anticipo=str_replace("\$li_monpreant",$ls_monpreant,$ls_anticipo);
									$ls_anticipo=str_replace("\$li_monintant",$ls_monintant,$ls_anticipo);
									$ls_anticipo=str_replace("\$li_monantant",$ls_monantant,$ls_anticipo);
									$ls_anticipo=str_replace("\$li_monantint",$ls_monantint,$ls_anticipo);
									$ls_anticipo=str_replace("\$li_monant",$ls_monant,$ls_anticipo);
									$ls_anticipo=str_replace("\$li_monint",$ls_monint,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_motant",$ls_motant,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_obsant",$ls_obsant,$ls_anticipo);
									$ls_anticipo=str_replace("\$li_numerosueldo",$ls_sueldo_sin_fomato,$ls_anticipo);
									$ls_anticipo=str_replace("\$li_num_sue_integral",$li_sueintper_mensual_sinformato,$ls_anticipo);
									$ls_anticipo=str_replace("\$li_normalmensual",$ls_salnorpermensual,$ls_anticipo);
									$ls_anticipo=str_replace("\$ls_denomin_cargo",$ls_denominacioncargo,$ls_anticipo);
									
									$li_long=strlen($ls_anticipo);
									$a=0;
									$ls_pal_bus=array();
									$ls_pal_bus= explode("];",$ls_anticipo);
									$ls_cont=count($ls_pal_bus)-1;
									for($li_x=1;$li_x<=$ls_cont;$li_x++)
									{
										//Creando variable que encierra la formula
										$li_posicion1=strpos($ls_anticipo,"FORMULA[",$a);
										$li_posicion2=strpos($ls_anticipo,"];",$a);
										$li_posicion2=$li_posicion2-$li_posicion1;
										$ls_var_formula=substr($ls_anticipo,$li_posicion1,$li_posicion2+2);
										//Creando variable que encierra la formula
										
										$li_pos_for=strpos($ls_anticipo,"FORMULA[",$a);
										$li_pos_for=$li_pos_for+8;
										$li_pos_forhas=strpos($ls_anticipo,"];",$a);
										$li_pos_forhas=$li_pos_forhas-$li_pos_for;
										$ls_formula_agre=substr($ls_anticipo,$li_pos_for,$li_pos_forhas);
										$ls_formula_agre=str_replace(",",".",$ls_formula_agre);
										$lb_correcto=$io_evaluate->uf_evaluar_nomina($ls_formula_agre,&$li_resultado);
										$li_resul_numero=$io_fun_nomina->uf_formatonumerico($li_resultado);
										$io_numero_letra->setNumero($li_resultado);
										$ls_resultado=$io_numero_letra->letra();
										$ls_resultado=$ls_resultado." (".$ls_prefijo." ".$li_resul_numero.")";
										$ls_anticipo=str_replace("$ls_var_formula",$ls_resultado,$ls_anticipo);
										$a++;
									}
									fputs($ls_punt,$ls_anticipo);
									$rs_data->MoveNext();
									if(!$rs_data->EOF)
									{
										$ls_salto="\par \page \par";
										fputs($ls_punt,$ls_salto);
									}
								}
							}
						}
						if(($li_s<$li_totrow_det)&&($ls_anticipo=="0"))
						{
							$ls_salto="\par \page \par";
							fputs($ls_punt,$ls_salto);
						}
						$io_report->rs_detalle->MoveNext();
					}
					$io_report->rs_data->MoveNext();
					fputs($ls_punt,"}");
					fclose($ls_punt);
					@chmod($ls_salida,0755);
				}
			}
			if($lb_valido) // Si no ocurrio ning�n error
			{
				header ("Content-Disposition: attachment; filename=".$ls_copia."-".$_SESSION["la_logusr"].".rtf\n\n");
				header ("Content-Type: application/octet-stream");
				header ("Content-Length: ".filesize($ls_salida));
				readfile($ls_salida);
			}
			else  // Si hubo alg�n error
			{
				print("<script language=JavaScript>");
				print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
				print(" close();");
				print("</script>");		
			}
		}
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 