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
   function uf_imprimirresultados($as_codcom,$as_disponibilidad)
   {
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_imprimirresultados
		//		   Access: private
		//	    Arguments: as_codcom  // Número de Comprobante
		//	  Description: Función que Imprime los detalles del comprobante
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 31/10/2006 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $in_class_mis;
		
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
		$io_int_spg=new class_sigesp_int_spg();

		$ls_sql="SELECT DISTINCT sps_dt_spg.codcom, sps_dt_spg.codnom, sps_dt_spg.descripcion, sps_dt_spg.ced_bene as codper, ".
				"		rpc_beneficiario.ced_bene, rpc_beneficiario.apebene, rpc_beneficiario.nombene, codtipdoc,  ".
				"		(SELECT monto ".
				"		   FROM sps_dt_scg ".
				" 	      INNER JOIN (rpc_beneficiario ".
				"               INNER JOIN sno_personal ".
				"   	           ON sno_personal.codemp = rpc_beneficiario.codemp ".
				"                 AND sno_personal.cedper = rpc_beneficiario.ced_bene) ".
				"			 ON sps_dt_scg.codemp='".$ls_codemp."' ".
				"	        AND sps_dt_scg.codcom='".$as_codcom."' ".
				"	        AND sps_dt_scg.debhab='H' ".
				"			AND sps_dt_scg.codemp = rpc_beneficiario.codemp ".
				"			AND sps_dt_scg.ced_bene = sno_personal.codper ".
				"			AND sps_dt_scg.sc_cuenta = rpc_beneficiario.sc_cuenta) as monto ".
				"  FROM sps_dt_spg  ".
				" INNER JOIN (rpc_beneficiario ".
				"   		  INNER JOIN sno_personal ".
				"                ON sno_personal.codemp = rpc_beneficiario.codemp ".
				"               AND sno_personal.cedper = rpc_beneficiario.ced_bene) ".
				"	 ON sps_dt_spg.codemp='".$ls_codemp."' ".
				"	AND sps_dt_spg.codcom='".$as_codcom."' ".
				"	AND sps_dt_spg.codemp = rpc_beneficiario.codemp ".
				"	AND sps_dt_spg.ced_bene = sno_personal.codper ".
				" ORDER BY codcom ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while(!$rs_data->EOF)
			{
				$ls_descripcion=$rs_data->fields["descripcion"];
				$_SESSION["fechacomprobante"]=date("Y-m-d");
				$ls_destino="Beneficiario";
				$ls_nombre_destino=$rs_data->fields["ced_bene"]." - ".$rs_data->fields["apebene"].", ".$rs_data->fields["nombene"];
				print "<table width='450' height='20' border='0' align='center' cellpadding='0' cellspacing='0'>";
				print "	<tr>";
				print "		<td width='450' class='titulo-ventana'>Información del Comprobante</td>";
				print " </tr>";
				print "</table>";
				print "<table width='450' border=0 cellpadding=1 cellspacing=1 align='center' class='formato-blanco'>";
				print "  <tr>";
				print "		<td width='100'><div align='right' class='texto-azul'>Comprobante</div></td>";
				print "		<td width='350'><div align='left'>".$ls_comprobante."</div></td>";
				print "  </tr>";
				print "  <tr>";
				print "		<td><div align='right' class='texto-azul'>Descripci&oacute;n </div></td>";
				print "		<td><div align='justify'>".$ls_descripcion."</div></td>";
				print "  </tr>";
				print "  <tr>";
				print "		<td><div align='right' class='texto-azul'>".$ls_destino."</div></td>";
				print "		<td><div align='left'>".$ls_nombre_destino."</div></td>";
				print "  </tr>";
				print "  <tr>";
				print "		<td><div align='right' class='texto-azul'></div></td>";
				print "		<td><div align='left'></div></td>";
				print "  </tr>";
				print "</table>";
				$ls_sql="SELECT codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, estcla, spg_cuenta, monto ".
						"  FROM sps_dt_spg ".
						" WHERE codemp='".$ls_codemp."'".
						"   AND codcom='".$as_codcom."' ".
						$ls_criterio;							
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
					while(!$rs_data2->EOF)
					{
						$ls_cuenta=$rs_data2->fields["spg_cuenta"];
						$li_total=$li_total+$rs_data2->fields["monto"];
						$li_monto=number_format($rs_data2->fields["monto"],2,',','.');
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
								$ls_estatus="Acción";
								break;
							case "P":
								$ls_estatus="Proyecto";
								break;
						}
						print "<tr class=celdas-blancas>";
						print "<td align=center width='150'>".$ls_programatica."</td>";
						print "<td align=center width='50'>".$ls_estatus."</td>";
						print "<td align=center width='80'>".$ls_cuenta."</td>";
						print "<td align=right width='80'>".$li_monto."  </td>";
						print "<td align=center width='90'><img src='../shared/imagebank/".$ls_imagen."'></td>";
						print "</tr>";			
						$rs_data2->MoveNext();	
					}
					$li_total=number_format($li_total,2,',','.');
					print "	<tr class=celdas-blancas>";
					print "		<td colspan='3' align='right' class='texto-azul'>Total</td>";
					print "		<td width='100' align='right' class='texto-azul'>".$li_total."</td>";
					print "		<td align=right width='90'></td>";
					print " </tr>";
					print "</table>";
				}
				$io_sql2->free_result($rs_data2);	
				$ls_sql="SELECT sc_cuenta, debhab, monto, codnom ".
						"  FROM sps_dt_scg ".
						" WHERE codemp='".$ls_codemp."'".
						"   AND codcom='".$as_codcom."' ".
						" ORDER BY  debhab ";
				$rs_data2=$io_sql2->select($ls_sql);
				if($rs_data2===false)
				{
					$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql2->message)); 
				}
				else
				{
					$li_total_deb=0;
					$li_total_hab=0;
					print "<table width='450' height='20' border='0' align='center' cellpadding='0' cellspacing='0' class='formato-blanco'>";
					print "	<tr>";
					print "		<td colspan='3' class='titulo-celdanew'>Detalle Contable</td>";
					print " </tr>";
					print " <tr class=titulo-celdanew>";
					print "		<td width='100'>Cuenta</td>";
					print "		<td width='100'>Debe</td>";
					print "		<td width='100'>Haber</td>";
					print "	</tr>";
					while(!$rs_data2->EOF)
					{
						$ls_cuenta=$rs_data2->fields["sc_cuenta"];
						$li_monto=$rs_data2->fields["monto"];
						$ls_debhab=$rs_data2->fields["debhab"];
						switch($ls_debhab)
						{
							case "D":
								$li_debe=$li_monto;
								$li_debe=$in_class_mis->uf_formatonumerico($li_debe);
								$li_haber="0,00";
								$li_total_deb=$li_total_deb+$li_monto;
								break;
							case "H":
								$li_debe="0,00";
								$li_haber=$li_monto;
								$li_haber=$in_class_mis->uf_formatonumerico($li_haber);
								$li_total_hab=$li_total_hab+$li_monto;
								break;
						}
						print "<tr class=celdas-blancas>";
						print "<td align=center width='100'>".$ls_cuenta."</td>";
						print "<td align=right width='100'>".$li_debe."</td>";
						print "<td align=right width='100'>".$li_haber."</td>";
						print "</tr>";
						$rs_data2->MoveNext();	
					}
					$li_total_deb=$in_class_mis->uf_formatonumerico($li_total_deb);
					$li_total_hab=$in_class_mis->uf_formatonumerico($li_total_hab);
					print "	<tr>";
					print "		<td align=right class='texto-azul'>Total</td>";
					print "		<td align=right class='texto-azul'>".$li_total_deb."</td>";
					print "		<td align=right class='texto-azul'>".$li_total_hab."</td>";
					print " </tr>";
					print "</table>";
				}
				$io_sql2->free_result($rs_data2);
				print "<br><br>";	
				$rs_data->MoveNext();	
			}
			$io_sql->free_result($rs_data);	
		}
   }
   //----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_disponibilidad($ls_codemp,$rs_data2,$io_int_spg,&$ls_imagen,&$li_disponibilidad)
    { 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_disponibilidad
		//		   Access: public 
		//       Argument: uf_disponibilidad //  data
		//	  Description: busca la disponibilidad presupuiestaria 
		//	      Returns: mensaje
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_cuenta=$rs_data2->fields["spg_cuenta"];
		$ls_estprog[0]=$rs_data2->fields["codestpro1"];
		$ls_estprog[1]=$rs_data2->fields["codestpro2"];
		$ls_estprog[2]=$rs_data2->fields["codestpro3"];
		$ls_estprog[3]=$rs_data2->fields["codestpro4"];
		$ls_estprog[4]=$rs_data2->fields["codestpro5"];
		$ls_estprog[5]=$rs_data2->fields["estcla"];
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
<!--<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
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
--><title>Detalle Comprobante</title>
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
	$ls_codcom=$in_class_mis->uf_obtenervalor_get("codcom","");
	$ls_disponibilidad=$in_class_mis->uf_obtenervalor_get("disponibilidad","0");
	uf_imprimirresultados($ls_codcom,$ls_disponibilidad);
?>
</div>
</form>
</body>
</html>