<?php
class sigesp_scg_c_interfase_bancaria
{
	var $io_sql;
	var $io_msg;
	var $io_fun;
	
	function sigesp_scg_c_interfase_bancaria()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/sigesp_include.php");
		$io_siginc=new sigesp_include();
		$io_con=$io_siginc->uf_conectar();
		$this->io_sql=new class_sql($io_con);
		$this->io_mensajes=new class_mensajes();
		$this->io_funciones=new class_funciones();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad=new sigesp_c_seguridad();
	}
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_generarxml($ad_fecregdes,$ad_fecreghas,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_declaracionxml
		//         Access: public
		//	    Arguments: ad_fecregdes // Quincena del cual se van a generar los txt
		//	    		   ad_fecreghas      // Mes del cual se van a generar los txt
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que genera los txt de la declaración informativa
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 15/07/2007									Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_ruta="xml/exportar";
		@mkdir($ls_ruta,0755);
		$ls_archivo="xml/exportar/Comprobantes_contables-".date("Y_m_d_H_i").".xml";
		$lo_archivo=fopen("$ls_archivo","a+");
		$rs_datac=$this->uf_detalle_xml($ad_fecregdes,$ad_fecreghas);
		$ls_contenido='<?xml version="1.0" encoding="utf-8"?>';
		$ls_contenido.='<comprobantes>';
		$ls_cadena="";
		while(!$rs_datac->EOF)
		{
			$ls_procede=trim($rs_datac->fields["procede"]);
			$ls_comprobante=trim($rs_datac->fields["comprobante"]);
			$ls_codban=trim($rs_datac->fields["codban"]);
			$ls_ctaban=trim($rs_datac->fields["ctaban"]);
			$ls_sccuenta=trim($rs_datac->fields["sc_cuenta"]);
			$ls_descripcion=rtrim($rs_datac->fields["descripcion"]);
			$ls_codestpro1=$rs_datac->fields["codestpro1"];
			$ls_codestpro2=$rs_datac->fields["codestpro2"];
			$ls_codestpro3=$rs_datac->fields["codestpro3"];
			$ls_codestpro4=$rs_datac->fields["codestpro4"];
			$ls_codestpro5=$rs_datac->fields["codestpro5"];
			$ls_estcla=$rs_datac->fields["estcla"];
			$ls_fecha=trim($rs_datac->fields["fecha"]);
			$ls_procede=trim($rs_datac->fields["procede"]);
			$li_monto=number_format($rs_datac->fields["monto"],2,'.','');
			$ls_year=substr($ls_fecha,0,4);
			$ls_mes=substr($ls_fecha,5,2);
			$ls_dia=substr($ls_fecha,8,2);
			$ls_codsuc=$this->uf_select_sucursal($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla);
			switch($ls_procede)
			{
				case"SCBBCH":
					$ls_origen="4";
				break;
				case"SCBBND":
					$ls_origen="1";
				break;
				case"SCBBNC":
					$ls_origen="1";
				break;
				case"CXPSOP":
					$ls_origen="5";
				break;
					$ls_origen="9";
				default:
			}
			$ls_contenido.='<comprobante>';
			$ls_contenido.='<impobr>'.$ls_codsuc.'</impobr>';
			$ls_contenido.='<impbrn></impbrn>';
			$ls_contenido.='<impccy>001</impccy>';
			$ls_contenido.='<impgln>'.$ls_sccuenta.'</impgln>';
			$ls_contenido.='<impacc>0</impacc>';
			$ls_contenido.='<impref>'.$ls_comprobante.'</impref>';
			$ls_contenido.='<impvdm>'.$ls_mes.'</impvdm>';
			$ls_contenido.='<impvdd>'.$ls_dia.'</impvdd>';
			$ls_contenido.='<impvdy>'.$ls_year.'</impvdy>';
			$ls_contenido.='<imptds>'.$ls_descripcion.'</imptds>';
			$ls_contenido.='<impdra>'.$li_monto.'</impdra>';
			$ls_contenido.='<impcr1>'.$li_monto.'</impcr1>';
			$ls_contenido.='<impori>'.$ls_origen.'</impori>';
			$ls_contenido.='</comprobante>';
			$lb_valido=$this->uf_update_estatus_comprobante($ls_procede,$ls_comprobante,$ls_fecha,$ls_codban,$ls_ctaban,$aa_seguridad);
			if(!$lb_valido)
			{
				$lb_valido=false;
				break;
			}
			$rs_datac->MoveNext();
		}
		$ls_contenido.='</comprobantes>';
		@fwrite($lo_archivo,$ls_contenido);
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="PROCESS";
			$ls_descripcion ="Genero el xml de los comprobantes contables periodo ".
							 $ad_fecregdes." al ".$ad_fecreghas." en el Archivo ".$ls_archivo.
							 " Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		return $lb_valido;
	}// end function uf_declaracioninformativa
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_detalle_xml($as_fecemidocdes,$as_fecemidochas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_declaracion_xml_cabecera
		//         Access: public
		//      Argumento: as_fecemidocdes // Parametro de busqueda Fecha Desde
		//				   as_fecemidochas // Parametro de busqueda Fecha Hasta
		//	      Returns: Retorna un Datastored
		//    Description: Funcion que obtiene los datos para la declaracion de salarios y otras remuneraciones
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 05/06/2009									Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_criterio="";
		$rs_data="";
		$as_fecemidocdes=$this->io_funciones->uf_convertirdatetobd($as_fecemidocdes);
		$as_fecemidochas=$this->io_funciones->uf_convertirdatetobd($as_fecemidochas);
		$ls_sql="SELECT MAX(scg_dt_cmp.codemp) AS codemp,MAX(scg_dt_cmp.comprobante) AS comprobante,MAX(scg_dt_cmp.fecha) AS fecha,MAX(scg_dt_cmp.procede) AS procede,MAX(scg_dt_cmp.sc_cuenta) AS sc_cuenta,".
				"       MAX(scg_dt_cmp.descripcion) AS descripcion, MAX(scg_dt_cmp.monto) AS monto,MAX(scg_dt_cmp.codban) AS codban, MAX(scg_dt_cmp.ctaban)AS ctaban,".
				"      (SELECT MAX(codestpro1) FROM spg_dt_cmp".
				"        WHERE MAX(scg_dt_cmp.codemp)=sigesp_cmp.codemp".
				"          AND MAX(scg_dt_cmp.procede)=sigesp_cmp.procede".
				"          AND MAX(scg_dt_cmp.comprobante)=sigesp_cmp.comprobante".
				"          AND MAX(scg_dt_cmp.fecha)=sigesp_cmp.fecha".
				"          AND MAX(scg_dt_cmp.codban)=sigesp_cmp.codban".
				"          AND MAX(scg_dt_cmp.ctaban)=sigesp_cmp.ctaban) AS codestpro1,".
				"      (SELECT MAX(codestpro2) FROM spg_dt_cmp".
				"        WHERE MAX(scg_dt_cmp.codemp)=sigesp_cmp.codemp".
				"          AND MAX(scg_dt_cmp.procede)=sigesp_cmp.procede".
				"          AND MAX(scg_dt_cmp.comprobante)=sigesp_cmp.comprobante".
				"          AND MAX(scg_dt_cmp.fecha)=sigesp_cmp.fecha".
				"          AND MAX(scg_dt_cmp.codban)=sigesp_cmp.codban".
				"          AND MAX(scg_dt_cmp.ctaban)=sigesp_cmp.ctaban) AS codestpro2,".
				"      (SELECT MAX(codestpro3) FROM spg_dt_cmp".
				"        WHERE MAX(scg_dt_cmp.codemp)=sigesp_cmp.codemp".
				"          AND MAX(scg_dt_cmp.procede)=sigesp_cmp.procede".
				"          AND MAX(scg_dt_cmp.comprobante)=sigesp_cmp.comprobante".
				"          AND MAX(scg_dt_cmp.fecha)=sigesp_cmp.fecha".
				"          AND MAX(scg_dt_cmp.codban)=sigesp_cmp.codban".
				"          AND MAX(scg_dt_cmp.ctaban)=sigesp_cmp.ctaban) AS codestpro3,".
				"      (SELECT MAX(codestpro4) FROM spg_dt_cmp".
				"        WHERE MAX(scg_dt_cmp.codemp)=sigesp_cmp.codemp".
				"          AND MAX(scg_dt_cmp.procede)=sigesp_cmp.procede".
				"          AND MAX(scg_dt_cmp.comprobante)=sigesp_cmp.comprobante".
				"          AND MAX(scg_dt_cmp.fecha)=sigesp_cmp.fecha".
				"          AND MAX(scg_dt_cmp.codban)=sigesp_cmp.codban".
				"          AND MAX(scg_dt_cmp.ctaban)=sigesp_cmp.ctaban) AS codestpro4,".
				"      (SELECT MAX(codestpro5) FROM spg_dt_cmp".
				"        WHERE MAX(scg_dt_cmp.codemp)=sigesp_cmp.codemp".
				"          AND MAX(scg_dt_cmp.procede)=sigesp_cmp.procede".
				"          AND MAX(scg_dt_cmp.comprobante)=sigesp_cmp.comprobante".
				"          AND MAX(scg_dt_cmp.fecha)=sigesp_cmp.fecha".
				"          AND MAX(scg_dt_cmp.codban)=sigesp_cmp.codban".
				"          AND MAX(scg_dt_cmp.ctaban)=sigesp_cmp.ctaban) AS codestpro5,".
				"      (SELECT MAX(estcla) FROM spg_dt_cmp".
				"        WHERE MAX(scg_dt_cmp.codemp)=sigesp_cmp.codemp".
				"          AND MAX(scg_dt_cmp.procede)=sigesp_cmp.procede".
				"          AND MAX(scg_dt_cmp.comprobante)=sigesp_cmp.comprobante".
				"          AND MAX(scg_dt_cmp.fecha)=sigesp_cmp.fecha".
				"          AND MAX(scg_dt_cmp.codban)=sigesp_cmp.codban".
				"          AND MAX(scg_dt_cmp.ctaban)=sigesp_cmp.ctaban) AS estcla".
				"  FROM scg_dt_cmp,sigesp_cmp".
				" WHERE scg_dt_cmp.codemp='".$this->ls_codemp."'".
				"   AND scg_dt_cmp.fecha<='".$as_fecemidochas."'".
				"   AND scg_dt_cmp.fecha>='".$as_fecemidocdes."'".
				"   AND scg_dt_cmp.debhab='H'".
				"   AND sigesp_cmp.estgenxml='0'".
				"   AND sigesp_cmp.procede<>'SPGINT'".
				"   AND sigesp_cmp.procede<>'SPIINT'".
				"   AND scg_dt_cmp.codemp=sigesp_cmp.codemp".
				"   AND scg_dt_cmp.procede=sigesp_cmp.procede".
				"   AND scg_dt_cmp.comprobante=sigesp_cmp.comprobante".
				"   AND scg_dt_cmp.fecha=sigesp_cmp.fecha".
				"   AND scg_dt_cmp.codban=sigesp_cmp.codban".
				"   AND scg_dt_cmp.ctaban=sigesp_cmp.ctaban".
				" GROUP BY sigesp_cmp.codemp,sigesp_cmp.comprobante,sigesp_cmp.fecha,".
				"          sigesp_cmp.procede, sigesp_cmp.codban, sigesp_cmp.ctaban";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Interfase MÉTODO->uf_detalle_xml ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			return false;
		}
		return $rs_data;
	}// end function uf_arc_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_sucursal($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_estcla)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_declaracion_xml_cabecera
		//         Access: public
		//      Argumento: as_fecemidocdes // Parametro de busqueda Fecha Desde
		//				   as_fecemidochas // Parametro de busqueda Fecha Hasta
		//	      Returns: Retorna un Datastored
		//    Description: Funcion que obtiene los datos para la declaracion de salarios y otras remuneraciones
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 05/06/2009									Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$rs_data="";
		$as_codsuc="---";
		$ls_sql="SELECT codsuc ".
				"  FROM sigesp_sucursales".
				" WHERE sigesp_sucursales.codemp='".$this->ls_codemp."'".
				"   AND sigesp_sucursales.codestpro1='".$as_codestpro1."'".
				"   AND sigesp_sucursales.codestpro2='".$as_codestpro2."'".
				"   AND sigesp_sucursales.codestpro3='".$as_codestpro3."'".
				"   AND sigesp_sucursales.codestpro4='".$as_codestpro4."'".
				"   AND sigesp_sucursales.codestpro5='".$as_codestpro5."'".
				"   AND sigesp_sucursales.estcla='".$as_estcla."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Interfase MÉTODO->uf_select_sucursal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			return false;
		}
		else
		{
			while(!$rs_data->EOF)
			{
				$as_codsuc=trim($rs_datac->fields["codsuc"]);
			}

		}
		return $as_codsuc;
	}// end function uf_arc_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estatus_comprobante($as_procede,$as_comprobante,$as_fecha,$as_codban,$as_ctaban,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_estatus_comprobante
		//		   Access: private
		//	    Arguments: as_numrecdoc // Número de Recepcion de Documentos
		//                 as_codtipdoc // Codigo de Tipo de Documento
		//				   as_cedbene   // Cedula de Beneficiario
		//				   as_codpro    // Código Proveedor
		//				   ls_estatus   // Estatus en que se desea colocar la R.D.
		//                 aa_seguridad // Arreglo que contiene informacion de seguridad
		// 	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que actualiza el estatus de la Recepcion de Documentos
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 25/04/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sigesp_cmp ".
				"   SET estgenxml = '1' ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"	AND procede = '".$as_procede."' ".
				"	AND comprobante = '".$as_comprobante."' ".
				"	AND fecha = '".$as_fecha."' ".
				"	AND codban = '".trim($as_codban)."' ".
				"	AND ctaban = '".$as_ctaban."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Interfase MÉTODO->uf_update_estatus_comprobante ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó en estatus del comprobante <b>".$as_comprobante." - ".$as_procede.
							 "</b> Asociado a la Empresa <b>".$this->ls_codemp."<b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_update_estatus_procedencia
	//-----------------------------------------------------------------------------------------------------------------------------------

	function uf_aprobaciondebcre($as_path,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_declaracionxml
		//         Access: public  
		//	    Arguments: as_quincena // Quincena del cual se van a generar los txt
		//	    		   as_mes      // Mes del cual se van a generar los txt
		//	    		   as_anio     // Año del cual se van a generar los txt
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que genera los txt de la declaración informativa
		//	   Creado Por: Ing. Carlos Zambrano
		// Fecha Creación: 15/07/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        require_once("../shared/class_folder/class_funciones_xml.php");
		$this->io_xml=new class_funciones_xml();
		$la_archivos=$this->io_xml->uf_load_archivos($as_path);
		$li_totalarchivos=count($la_archivos["filnam"]);
		if($la_archivos=="")
		{
			$li_totalarchivos=0;
		}
		if ($li_totalarchivos==0)
		{
			$this->io_mensajes->message("No hay XML a leer o estan vacios.");
			$lb_valido=false;
		}
		else
		{
			for($li_i=1;$li_i<=$li_totalarchivos;$li_i++)
			{
				$ls_archivo=$la_archivos["filnam"][$li_i];
				$la_data=$this->io_xml->uf_cargar_comprobantes($as_path.$ls_archivo);
				$li_total=count($la_data);
				for($i=1;$i<=$li_total;$i++)
				{
					$ls_codban=rtrim($la_data[$i]["codban"]);
					$ls_ctaban=rtrim($la_data[$i]["ctaban"]);
					$ls_numdoc=rtrim($la_data[$i]["numdoc"]);
					$ls_codope=rtrim($la_data[$i]["codope"]);
					$lb_valido=$this->uf_select_movbco($ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope);
					if($lb_valido)
					{
						$lb_valido=$this->uf_cambio_estatus_estapribs($ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope);
					}
					
				}
		    }
		}
		return $lb_valido;
	}// end function uf_declaracioninformativa
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_cargar_ncred_ndeb_aprobados($as_filnam)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_cargar_desembolso
		//		   Access: private
		//	    Arguments: $as_filnam = Archivo xml leido.
		//	      Returns: $lr_datos  = Arreglo cargado con la información leida desde el archivo xml.
		//	  Description: Devuelve mediante un arreglo el desembolso generado por la cobranza 
		//	   Creado Por: Ing. Yesenia Moreno de Lang
		//   Fecha Creación: 14/07/2008 							Fecha Última Modificación : 12/12/2009
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_i = 1;
		$lr_datos="";
		$io_docxml = new DOMDocument();
		$io_docxml->load($as_filnam);
		$registros = $io_docxml->getElementsByTagName('COMPROBANTES');
		if($registros)
		{ 
			foreach ($registros as $registro)
			{
				$comprobante = $registro->getElementsByTagName('comprobante');
				foreach ($comprobante as $comprobante)
				{
					$cabecera = $comprobante->getElementsByTagName('cabecera');
					foreach ($cabecera as $cabecera)
					{
						$io_campo = $cabecera->getElementsByTagName("procede");
						$ls_procede= $io_campo->item(0)->nodeValue;

						$io_campo = $cabecera->getElementsByTagName("numerocomprobante");
						$ls_numerocomprobante= $io_campo->item(0)->nodeValue;

						$io_campo = $cabecera->getElementsByTagName("fecha");
						$ls_fecha= $io_campo->item(0)->nodeValue;

						$io_campo = $cabecera->getElementsByTagName("codban");
						$ls_codban= $io_campo->item(0)->nodeValue;

						$io_campo = $cabecera->getElementsByTagName("ctaban");
						$ls_ctaban= $io_campo->item(0)->nodeValue;

						$io_campo = $cabecera->getElementsByTagName("descripcion");
						$ls_descripcion= $io_campo->item(0)->nodeValue;

						$io_campo = $cabecera->getElementsByTagName("tipo_comp");
						$ls_tipo_comp= $io_campo->item(0)->nodeValue;

						$io_campo = $cabecera->getElementsByTagName("tipo_destino");
						$ls_tipo_destino= $io_campo->item(0)->nodeValue;

						$io_campo = $cabecera->getElementsByTagName("cod_pro");
						$ls_cod_pro= $io_campo->item(0)->nodeValue;

						$io_campo = $cabecera->getElementsByTagName("ced_bene");
						$ls_ced_bene= $io_campo->item(0)->nodeValue;

						$io_campo = $cabecera->getElementsByTagName("total");
						$ls_total= $io_campo->item(0)->nodeValue;
					}

					$detalle = $comprobante->getElementsByTagName('detalle');
					foreach ($detalle as $detalle)
					{
						$io_campo = $cabecera->getElementsByTagName("procede");
						$ls_procede= $io_campo->item(0)->nodeValue;

						$io_campo = $cabecera->getElementsByTagName("numerocomprobante");
						$ls_numerocomprobante= $io_campo->item(0)->nodeValue;

						$io_campo = $cabecera->getElementsByTagName("fecha");
						$ls_fecha= $io_campo->item(0)->nodeValue;

						$io_campo = $cabecera->getElementsByTagName("codban");
						$ls_codban= $io_campo->item(0)->nodeValue;

						$io_campo = $cabecera->getElementsByTagName("ctaban");
						$ls_ctaban= $io_campo->item(0)->nodeValue;

						$io_campo = $cabecera->getElementsByTagName("descripcion");
						$ls_descripcion= $io_campo->item(0)->nodeValue;

						$io_campo = $cabecera->getElementsByTagName("tipo_comp");
						$ls_tipo_comp= $io_campo->item(0)->nodeValue;

						$io_campo = $cabecera->getElementsByTagName("tipo_destino");
						$ls_tipo_destino= $io_campo->item(0)->nodeValue;

						$io_campo = $cabecera->getElementsByTagName("cod_pro");
						$ls_cod_pro= $io_campo->item(0)->nodeValue;

						$io_campo = $cabecera->getElementsByTagName("ced_bene");
						$ls_ced_bene= $io_campo->item(0)->nodeValue;

						$io_campo = $cabecera->getElementsByTagName("total");
						$ls_total= $io_campo->item(0)->nodeValue;
					}
					$li_i++;
				}
			} 
		}
		return $lr_datos;
	}	
	
}
?>