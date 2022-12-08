<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}
   //----------------------------------------------------------------------------------------------------------------------------
   function uf_imprimirresultados($as_codval,$as_codcom,$as_disponibilidad)
   {
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_imprimirresultados
		//		   Access: private
		//	    Arguments: as_codcom  // N�mero de Comprobante
		//	  Description: Funci�n que Imprime los detalles del comprobante
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 31/10/2006 								Fecha �ltima Modificaci�n : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $in_class_mis;
		$arrdetalles = array();
		$arrdetscg   = array();
		$arrdetscg1  = array();
		$arrdetscg2  = array();
		$arrdetscg3	 = array();	
		
		require_once("../shared/class_folder/sigesp_include.php");
		$in=new sigesp_include();
		$con=$in->uf_conectar();
		require_once("../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();
		require_once("../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($con);
		require_once("../shared/class_folder/class_sql.php");
		$io_sql2=new class_sql($con);
		require_once("../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];
		require_once("../shared/class_folder/class_sigesp_int.php");
		require_once("../shared/class_folder/class_sigesp_int_int.php");
		require_once("../shared/class_folder/class_sigesp_int_scg.php");
		require_once("../shared/class_folder/class_sigesp_int_spg.php");
		require_once("class_folder/class_sigesp_sob_integracion.php");  	
		$in_class = new class_sigesp_sob_integracion();
		$io_int_spg=new class_sigesp_int_spg();
		
		

		$ls_sql="SELECT sob_valuacion.*,sob_asignacion.cod_pro,".
				"		(SELECT nompro FROM rpc_proveedor ".
				"		  WHERE rpc_proveedor.codemp = sob_asignacion.codemp ".
				"           AND rpc_proveedor.cod_pro = sob_asignacion.cod_pro ) as nompro ".
				"  FROM sob_valuacion, sob_contrato, sob_asignacion ".
				" WHERE sob_valuacion.codemp='".$ls_codemp."'".
				"   AND sob_valuacion.codval='".$as_codval."' ".
				"   AND sob_valuacion.codcon='".$as_codcom."' ".
				"	AND sob_valuacion.codemp = sob_contrato.codemp ".
				"	AND sob_valuacion.codcon = sob_contrato.codcon ".
				"	AND sob_contrato.codemp = sob_asignacion.codemp ".
				"	AND sob_contrato.codasi = sob_asignacion.codasi ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			$arrdetalles = $in_class->uf_mostrar_cargos($as_codval, $as_codcom);
						
			if(!$rs_data->EOF){
				$ls_codpro = $rs_data->fields["cod_pro"];
				$ls_descripcion=$rs_data->fields["obsval"];
				$ls_descontrato=$as_codcom." - ".$rs_data->fields["obscon"];
				$ls_nombre_destino=$rs_data->fields["cod_pro"]." - ".$rs_data->fields["nompro"];
				$ld_fecinival=$io_funciones->uf_convertirfecmostrar($rs_data->fields["fecinival"]);
				$_SESSION["fechacomprobante"]=$ld_fecinival;
				$arrdetscg3 = $in_class->uf_mostrar_deducciones($rs_data->fields["cod_pro"], $as_codval, $as_codcom, $rs_data->fields["amoval"]);

				print "<table width='450' height='20' border='0' align='center' cellpadding='0' cellspacing='0'>";
				print "	<tr>";
				print "		<td width='450' class='titulo-ventana'>Informaci�n del Comprobante</td>";
				print " </tr>";
				print "</table>";
				print "<table width='450' border=0 cellpadding=1 cellspacing=1 align='center' class='formato-blanco'>";
				print "  <tr>";
				print "		<td width='100'><div align='right' class='texto-azul'>Contrato Asociado</div></td>";
				print "		<td width='350'><div align='left'>".$ls_descontrato."</div></td>";
				print "  </tr>";
				print "  <tr>";
				print "		<td width='100'><div align='right' class='texto-azul'>Valuaci�n</div></td>";
				print "		<td width='350'><div align='left'>".$as_codval."</div></td>";
				print "  </tr>";
				print "  <tr>";
				print "		<td width='100'><div align='right' class='texto-azul'>Fecha</div></td>";
				print "		<td width='350'><div align='left'>".$ld_fecinival."</div></td>";
				print "  </tr>";
				print "  <tr>";
				print "		<td><div align='right' class='texto-azul'>Descripci&oacute;n </div></td>";
				print "		<td><div align='justify'>".$ls_descripcion."</div></td>";
				print "  </tr>";
				print "  <tr>";
				print "		<td><div align='right' class='texto-azul'>Proveedor</div></td>";
				print "		<td><div align='left'>".$ls_nombre_destino."</div></td>";
				print "  </tr>";
				print "  <tr>";
				print "		<td><div align='right' class='texto-azul'>Contabilizaci&oacute;n </div></td>";
				print "		<td><div align='left'>COMPROMISO</div></td>";
				print "  </tr>";
				print "  <tr>";
				print "		<td><div align='right' class='texto-azul'></div></td>";
				print "		<td><div align='left'></div></td>";
				print "  </tr>";
				print "</table>";
				$ls_sql="SELECT codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, estcla, spg_cuenta, monto ".
						"  FROM sob_cuentavaluacion ".
						" WHERE codemp='".$ls_codemp."'".
						"   AND codval='".$as_codval."' ".
						"   AND codcon='".$as_codcom."' ";
				$rs_data2=$io_sql2->select($ls_sql);
				if($rs_data2===false)
				{
					$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql2->message)); 
				}
				else
				{
					$ls_titulo="";
					$li_len1=0;
					$li_len2=0;
					$li_len3=0;
					$li_len4=0;
					$li_len5=0;
					$in_class_mis->uf_loadmodalidad(&$li_len1,&$li_len2,&$li_len3,&$li_len4,&$li_len5,&$ls_titulo);
					print "<table width='450' height='20' border='0' align='center' cellpadding='0' cellspacing='0' class='formato-blanco'>";
					print "	<tr>";
					print "		<td colspan='5' class='titulo-celdanew'>Detalle Presupuestario</td>";
					print " </tr>";
					print " <tr class=titulo-celdanew>";
					print "		<td width='150'>".$ls_titulo."</td>";
					print "		<td width='50'>Estatus</td>";
					print "		<td width='80'>Cuenta</td>";
					print "		<td width='80'>Monto</td>";
					print "		<td width='90'>Disponibilidad</td>";
					print "	</tr>";
					$li_total=0;
					$li_scg = 0;
					while(!$rs_data2->EOF)
					{
						$ls_cuenta=$rs_data2->fields["spg_cuenta"];
						$li_total=$li_total+$rs_data2->fields["monto"];
						$li_monto=$rs_data2->fields["monto"];
						$ls_codestpro=$rs_data2->fields["codestpro1"].$rs_data2->fields["codestpro2"].$rs_data2->fields["codestpro3"].$rs_data2->fields["codestpro4"].$rs_data2->fields["codestpro5"];
						$ls_estcla=$rs_data2->fields["estcla"];
						$ls_programatica="";
						$ls_estatus="";
						$ls_imagen='blank.gif';
						if($as_disponibilidad==1)
						{
							$ls_imagen='ok.png';
							$li_disponibilidad=0;
							uf_disponibilidad($ls_codemp,$rs_data2,$io_int_spg,&$ls_imagen,&$li_disponibilidad);
							$li_disponibilidad=$in_class_mis->uf_formatonumerico($li_disponibilidad);
						}
						$in_class_mis->uf_formatoprogramatica($ls_codestpro,&$ls_programatica);
						switch($ls_estcla)
						{
							case "A":
								$ls_estatus="Acci�n";
								break;
							case "P":
								$ls_estatus="Proyecto";
								break;
						}
						print "<tr class=celdas-blancas>";
						print "<td align=center width='150'>".$ls_programatica."</td>";
						print "<td align=center width='50'>".$ls_estatus."</td>";
						print "<td align=center width='80'>".$ls_cuenta."</td>";
						print "<td align=right width='80'>".$in_class_mis->uf_formatonumerico($li_monto)."  </td>";
						print "<td align=center width='90'><img src='../shared/imagebank/".$ls_imagen."'></td>";
						print "</tr>";	

						//ARMANDO DENTALLE CONTABLE....
						$ls_cuentascg = $in_class->uf_select_cuentacontable($ls_cuenta, $ls_codestpro, $ls_estcla);
						$arrdetscg1 [$li_scg]['cuentascg'] = $ls_cuentascg;
						$arrdetscg1 [$li_scg]['operacionscg'] = 'D';
						$arrdetscg1 [$li_scg]['monto'] = $li_monto;
											
						
						$rs_data2->MoveNext();	
					}
					
					if(!empty($arrdetalles[0])){
						foreach ($arrdetalles[0] as $detallespg) {
							$ls_cuenta    = $detallespg["cuentaspg"];
							$li_total     = $li_total + $detallespg["monto"];
							$li_monto     = $detallespg["monto"];
							$ls_codestpro = $detallespg["codestpro"];
							$ls_estcla    = $detallespg["estcla"];
							$ls_programatica="";
							$ls_estatus="";
							$ls_imagen='blank.gif';
							if($as_disponibilidad==1)
							{
								$ls_imagen='ok.png';
								$li_disponibilidad=0;
								uf_disponibilidad($ls_codemp,'',$io_int_spg,&$ls_imagen,&$li_disponibilidad,$ls_cuenta,$ls_codestpro,$ls_estcla);
								$li_disponibilidad=$in_class_mis->uf_formatonumerico($li_disponibilidad);
							}
							$in_class_mis->uf_formatoprogramatica($ls_codestpro,&$ls_programatica);
							switch($ls_estcla)
							{
								case "A":
									$ls_estatus="Acci�n";
									break;
								case "P":
									$ls_estatus="Proyecto";
									break;
							}
							print "<tr class=celdas-blancas>";
							print "<td align=center width='150'>".$ls_programatica."</td>";
							print "<td align=center width='50'>".$ls_estatus."</td>";
							print "<td align=center width='80'>".$ls_cuenta."</td>";
							print "<td align=right width='80'>".$in_class_mis->uf_formatonumerico($li_monto)."  </td>";
							print "<td align=center width='90'><img src='../shared/imagebank/".$ls_imagen."'></td>";
							print "</tr>";
						}
					}
					
					$li_total=$in_class_mis->uf_formatonumerico($li_total);
					print "	<tr class=celdas-blancas>";
					print "		<td colspan='3' align='right' class='texto-azul'>Total</td>";
					print "		<td width='100' align='right' class='texto-azul'>".$li_total."</td>";
					print "		<td align=right width='90'></td>";
					print " </tr>";
					print "</table>";
					$io_sql2->free_result($rs_data2);	
				}
			}
			$io_sql->free_result($rs_data);
			
			
			//PINTANDO DETALLE CONTABLE
			$arrdetscg = array_merge_recursive($arrdetscg1,$arrdetalles[1]);
			$arrdetscg = array_merge_recursive($arrdetscg,$arrdetscg3);
			$li_total_deb = 0;
			$li_total_hab = 0;
			print "<table width='450' height='20' border='0' align='center' cellpadding='0' cellspacing='0' class='formato-blanco'>";
			print "	<tr>";
			print "		<td colspan='3' class='titulo-celdanew'>Detalle Contable</td>";
			print " </tr>";
			print " <tr class=titulo-celdanew>";
			print "		<td width='100'>Cuenta</td>";
			print "		<td width='100'>Debe</td>";
			print "		<td width='100'>Haber</td>";
			print "	</tr>";
			foreach ($arrdetscg as $detscg) 
			{
				$ls_scgcuenta = $detscg['cuentascg'];
				$ls_debhab = $detscg['operacionscg'];
				$li_monto  = $detscg['monto'];
				
				switch($ls_debhab)
				{
					case "D":
						$li_debe=$in_class_mis->uf_formatonumerico($li_monto);
						$li_haber="0,00";
						$li_total_deb=$li_total_deb+$li_monto;
						break;
					case "H":
						$li_debe="0,00";
						$li_haber=$in_class_mis->uf_formatonumerico($li_monto);
						$li_total_hab=$li_total_hab+$li_monto;
						break;
				}
				print "<tr class=celdas-blancas>";
				print "<td align=center width='100'>".$ls_scgcuenta."</td>";
				print "<td align=right width='100'>".$li_debe."</td>";
				print "<td align=right width='100'>".$li_haber."</td>";
				print "</tr>";			
			}
			//OJO LA DIFERENCIA DEBE HABER CARGARSELA A LA CUENTA DEL PROVEEDOR
			$li_totdifpro = $li_total_deb - $li_total_hab;
			$li_debe  = "0,00";
			$li_haber = $in_class_mis->uf_formatonumerico($li_totdifpro);
			$li_total_hab = $li_total_hab+$li_totdifpro; 
			$in_class->uf_select_cuenta_proveedor($ls_codpro, $scgcuenta);
			print "<tr class=celdas-blancas>";
			print "<td align=center width='100'>".$scgcuenta."</td>";
			print "<td align=right width='100'>".$li_debe."</td>";
			print "<td align=right width='100'>".$li_haber."</td>";
			print "</tr>";
			
			$li_total_deb =$in_class_mis->uf_formatonumerico($li_total_deb);
			$li_total_hab =$in_class_mis->uf_formatonumerico($li_total_hab);
			
			
			
			print "	<tr>";
			print "		<td align=right class='texto-azul'>Total</td>";
			print "		<td align=right class='texto-azul'>".$li_total_deb."</td>";
			print "		<td align=right class='texto-azul'>".$li_total_hab."</td>";
			print " </tr>";
			print "</table>";
			print "<br><br>";
		}
   }
   //----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_disponibilidad($ls_codemp,$rs_data2,$io_int_spg,&$ls_imagen,&$li_disponibilidad,$spg_cuenta='',$as_codestpro='',$estcla='')
    { 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_disponibilidad
		//		   Access: public 
		//       Argument: uf_disponibilidad //  data
		//	  Description: busca la disponibilidad presupuiestaria 
		//	      Returns: mensaje
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: Ing. Yesenia Moreno								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if ($spg_cuenta=='' && $codestpro=='') {
			$ls_cuenta=$rs_data2->fields["spg_cuenta"];
			$ls_estprog[0]=$rs_data2->fields["codestpro1"];
			$ls_estprog[1]=$rs_data2->fields["codestpro2"];
			$ls_estprog[2]=$rs_data2->fields["codestpro3"];
			$ls_estprog[3]=$rs_data2->fields["codestpro4"];
			$ls_estprog[4]=$rs_data2->fields["codestpro5"];
			$ls_estprog[5]=$rs_data2->fields["estcla"];
		}
		else{
	    	$ls_cuenta=$spg_cuenta;
			$ls_estprog[0]=substr($as_codestpro,0,25);
			$ls_estprog[1]=substr($as_codestpro,25,25);
			$ls_estprog[2]=substr($as_codestpro,50,25);
			$ls_estprog[3]=substr($as_codestpro,75,25);
			$ls_estprog[4]=substr($as_codestpro,100,25);
			$ls_estprog[5]=$estcla;
		}
		
		$la_empresa=$_SESSION["la_empresa"];
		$ls_vali_nivel=$la_empresa["vali_nivel"];
		
		if($ls_vali_nivel==5)
		{
			$ls_formpre=str_replace("-","",$la_empresa["formpre"]);
			$ls_vali_nivel=$io_int_spg->uf_spg_obtener_nivel($ls_formpre);
		}
		if($_SESSION["la_empresa"]["estvaldis"]==0)
		{
			$ls_vali_nivel=0;
		}
		$lb_valido=true;
		$li_nivel=$io_int_spg->uf_spg_obtener_nivel($ls_cuenta);
		if ($li_nivel <= $ls_vali_nivel)
		{
			$ls_status="";
			$li_asignado=0;
			$li_aumento=0;
			$li_disminucion=0;
			$li_precomprometido=0;
			$li_comprometido=0;
			$li_causado=0;
			$li_pagado=0;
			$io_int_spg->uf_spg_saldo_select($ls_codemp,$ls_estprog,$ls_cuenta,$ls_status,$li_asignado,$li_aumento,$li_disminucion,
											 $li_precomprometido,$li_comprometido,$li_causado,$li_pagado,'ACTUAL');
			$li_disponibilidad=(($li_asignado + $li_aumento) - ( $li_disminucion + $li_comprometido + $li_precomprometido));
			if(round($rs_data2->fields["monto"],2) >= round($li_disponibilidad,2))
			{
				$ls_imagen='failed.png';
				$lb_valido=false;
			}
			if($lb_valido)
			{
				$ls_status="";
				$li_asignado=0;
				$li_aumento=0;
				$li_disminucion=0;
				$li_precomprometido=0;
				$li_comprometido=0;
				$li_causado=0;
				$li_pagado=0;
				$io_int_spg->uf_spg_saldo_select($ls_codemp,$ls_estprog,$ls_cuenta,$ls_status,$li_asignado,$li_aumento,$li_disminucion,
												 $li_precomprometido,$li_comprometido,$li_causado,$li_pagado,'COMPROBANTE');
				$li_disponibilidad=(($li_asignado + $li_aumento) - ( $li_disminucion + $li_comprometido + $li_precomprometido));
				if(round($rs_data2->fields["monto"],2) >= round($li_disponibilidad,2))
				{
					$ls_imagen='failed.png';
					$lb_valido=false;
				}
			}				
		} 	
		return true;
	} // end function uf_show_error_disponible
	//-----------------------------------------------------------------------------------------------------------------------------------
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
<title>Detalle Comprobante</title>
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
<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
<?php
	require_once("class_folder/class_funciones_mis.php");
	$in_class_mis=new class_funciones_mis();
	$ls_codval=$in_class_mis->uf_obtenervalor_get("codval","");
	$ls_codcon=$in_class_mis->uf_obtenervalor_get("codcon","");
	$ls_disponibilidad=$in_class_mis->uf_obtenervalor_get("disponibilidad","0");
	uf_imprimirresultados($ls_codval,$ls_codcon,$ls_disponibilidad);
?>
</div>
</form>
</body>
</html>