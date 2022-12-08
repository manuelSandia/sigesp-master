<?php
 ////////////////////////////////////////////////////////////////////////////////////////////////////////
 //       Class : class_sigesp_sps_integracion                                                     //    
 // Description : Esta clase tiene todos los metodos necesario para el manejo de la rutina integradora //
 //               de las nominas y aportes															   //
 ////////////////////////////////////////////////////////////////////////////////////////////////////////
class class_sigesp_sps_integracion
{
	//Instancia de la clase funciones.
    var $is_msg_error;
	var $dts_nomina;
	var $dts_banco;
	var $dts_nomina_aporte;
	var $obj="";
	var $io_sql;
	var $io_siginc;
	var $io_conect;
	var $io_function;	
    var $io_sigesp_int;
	var $io_sigesp_int_spg;
	var $io_sigesp_int_scg;	
	var $io_fecha;
	var $io_msg;
	var $is_codemp="";
	var $is_procede="";
	var $is_mensaje_spi="";	
	var $is_mensaje_spg="";	
	var $is_comprobante;
	var $idt_fecha;
    var	$is_tiponomina;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function class_sigesp_sps_integracion()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: class_sigesp_sno_integracion
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 25/10/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/class_sql.php");  
		require_once("../shared/class_folder/class_datastore.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/class_sigesp_int.php");
		require_once("../shared/class_folder/class_sigesp_int_int.php");
		require_once("../shared/class_folder/class_sigesp_int_spg.php");
		require_once("../shared/class_folder/class_sigesp_int_scg.php");
		require_once("../shared/class_folder/class_sigesp_int_spi.php");
		require_once("../shared/class_folder/class_fecha.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("class_funciones_mis.php");
	    $this->io_fun_mis=new class_funciones_mis();
	    $this->io_fecha=new class_fecha();
        $this->io_sigesp_int=new class_sigesp_int_int();
		$this->io_function=new class_funciones() ;
		$this->io_siginc=new sigesp_include();
		$this->io_connect=$this->io_siginc->uf_conectar();
		$this->io_sql=new class_sql($this->io_connect);		
		$this->is_codemp=$_SESSION["la_empresa"]["codemp"];		
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];		
		$this->dts_prestaciones=new class_datastore();
		$this->io_msg=new class_mensajes();		
		$this->io_sigesp_int_spg=new class_sigesp_int_spg();
		$this->io_sigesp_int_scg=new class_sigesp_int_scg();		
		$this->io_seguridad=new sigesp_c_seguridad();		
		$this->as_procede="";
		$this->as_comprobante="";
		$this->ad_fecha="";
		$this->as_codban="";
		$this->as_ctaban="";
	}// end function class_sigesp_sno_integracion
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destroy_objects()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destroy_objects
		//		   Access: public 
		//	  Description: Destructor de los objectos de la Clase
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 25/10/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if( is_object($this->io_fecha) ) { unset($this->io_fecha);  }
		if( is_object($this->io_sigesp_int) ) { unset($this->io_sigesp_int);  }
		if( is_object($this->io_function) ) { unset($this->io_function);  }
		if( is_object($this->io_siginc) ) { unset($this->io_siginc);  }
		if( is_object($this->io_connect) ) { unset($this->io_connect);  }
		if( is_object($this->io_sql) ) { unset($this->io_sql);  }	   
		if( is_object($this->obj) ) { unset($this->obj);  }	   
		if( is_object($this->dts_prestaciones) ) { unset($this->dts_prestaciones);  }	   	   
		if( is_object($this->dts_banco) ) { unset($this->dts_banco);  }	   	   
		if( is_object($this->dts_prestaciones_aporte) ) { unset($this->dts_prestaciones_aporte);  }	   
		if( is_object($this->io_msg) ) { unset($this->io_msg);  }	   
		if( is_object($this->io_sigesp_int_spg) ) { unset($this->io_sigesp_int_spg);  }	   
		if( is_object($this->io_sigesp_int_scg) ) { unset($this->io_sigesp_int_scg);  }	   
		if( is_object($this->io_seguridad) ) { unset($this->io_seguridad);  }	   
	}// end function uf_destroy_objects
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_config($as_sistema, $as_seccion, $as_variable, $as_valor, $as_tipo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_config
		//		   Access: public
		//	    Arguments: as_sistema  // Sistema al que pertenece la variable
		//				   as_seccion  // Sección a la que pertenece la variable
		//				   as_variable  // Variable nombre de la variable a buscar
		//				   as_valor  // valor por defecto que debe tener la variable
		//				   as_tipo  // tipo de la variable
		//	      Returns: $ls_resultado variable buscado
		//	  Description: Función que obtiene una variable de la tabla config
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_valor="";
		$ls_sql="SELECT value ".
				"  FROM sigesp_config ".
				" WHERE codemp='".$this->is_codemp."' ".
				"   AND codsis='".$as_sistema."' ".
				"   AND seccion='".$as_seccion."' ".
				"   AND entry='".$as_variable."' ";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->SNO MÉTODO->uf_select_config ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			$li_i=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_valor=$row["value"];
				$li_i=$li_i+1;
			}
			if($li_i==0)
			{
				$lb_valido=$this->uf_insert_config($as_sistema, $as_seccion, $as_variable, $as_valor, $as_tipo);
				if ($lb_valido)
				{
					$ls_valor=$this->uf_select_config($as_sistema, $as_seccion, $as_variable, $as_valor, $as_tipo);
				}
			}
			$this->io_sql->free_result($rs_data);		
		}
		return rtrim($ls_valor);
	}// end function uf_select_config
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_config($as_sistema, $as_seccion, $as_variable, $as_valor, $as_tipo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_config
		//		   Access: public
		//	    Arguments: as_sistema  // Sistema al que pertenece la variable
		//				   as_seccion  // Sección a la que pertenece la variable
		//				   as_variable  // Variable nombre de la variable a buscar
		//				   as_valor  // valor por defecto que debe tener la variable
		//				   as_tipo  // tipo de la variable
		//	      Returns: $lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que inserta la variable de configuración
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();		
		$ls_sql="DELETE ".
				"  FROM sigesp_config ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codsis='".$as_sistema."' ".
				"   AND seccion='".$as_seccion."' ".
				"   AND entry='".$as_variable."' ";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_msg->message("CLASE->SNO MÉTODO->uf_insert_config ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message)); 
			//$this->io_mensajes->message("CLASE->SNO MÉTODO->uf_insert_config ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			switch ($as_tipo)
			{
				case "C"://Caracter
					$valor = $as_valor;
					break;

				case "D"://Double
					$as_valor=str_replace(".","",$as_valor);
					$as_valor=str_replace(",",".",$as_valor);
					$valor = $as_valor;
					break;

				case "B"://Boolean
					$valor = $as_valor;
					break;

				case "I"://Integer
					$valor = intval($as_valor);
					break;
			}
			$ls_sql="INSERT INTO sigesp_config(codemp, codsis, seccion, entry, value, type)VALUES ".
					"('".$this->ls_codemp."','".$as_sistema."','".$as_seccion."','".$as_variable."','".$valor."','".$as_tipo."')";
					
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_msg->message("CLASE->SNO MÉTODO->uf_insert_config ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message)); 
				//$this->io_mensajes->message("CLASE->SNO MÉTODO->uf_insert_config ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
			else
			{
				$this->io_sql->commit();
			}
		}
		return $lb_valido;
	}// end function uf_insert_config	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_contabilizacion_prestaciones($as_comprobante,$as_tipdoc,$adt_fecha,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_contabilizacion_prestaciones
		//		   Access: public (sigesp_mis_p_contabiliza_sno.php)
		//	    Arguments: as_comprobante  // Código de Comprobante
		//				   adt_fecha  // Fecha de contabilización
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Funcion que procesa la contabilización dado un comprobante
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 25/10/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
	    if($this->uf_obtener_data_comprobante($as_comprobante))
		{
			$lb_valido = $this->uf_procesar_recepcion_documento($as_comprobante,$as_tipdoc,$adt_fecha,$aa_seguridad);
		}
        return $lb_valido;		
    } // end function uf_procesar_contabilizacion_nomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_data_comprobante($as_comprobante)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_data_comprobante
		//		   Access: private
		//	    Arguments: as_comprobante  // Código de Comprobante
		//	      Returns: lb_existe True si existe el comprobante
		//	  Description: Este metodo que obtiene la información agrupada en un registro con la información 
		//                  de cabecera de proveedor,beneficiario tipo destino y descripcion 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 25/10/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_existe = false;		
		$ls_sql="SELECT DISTINCT sps_dt_spg.codcom, sps_dt_spg.codnom, sps_dt_spg.descripcion, sps_dt_spg.ced_bene as codper, rpc_beneficiario.ced_bene, codtipdoc,  ".
				"		(SELECT monto ".
				"		   FROM sps_dt_scg ".
				" 	      INNER JOIN (rpc_beneficiario ".
				"               INNER JOIN sno_personal ".
				"   	           ON sno_personal.codemp = rpc_beneficiario.codemp ".
				"                 AND sno_personal.cedper = rpc_beneficiario.ced_bene) ".
				"			 ON sps_dt_scg.codemp='".$this->is_codemp."' ".
				"	        AND sps_dt_scg.codcom='".$as_comprobante."' ".
				"	        AND sps_dt_scg.debhab='H' ".
				"			AND sps_dt_scg.codemp = rpc_beneficiario.codemp ".
				"			AND sps_dt_scg.ced_bene = sno_personal.codper ".
				"			AND sps_dt_scg.sc_cuenta = rpc_beneficiario.sc_cuenta) as monto ".
				"  FROM sps_dt_spg  ".
				" INNER JOIN (rpc_beneficiario ".
				"   		  INNER JOIN sno_personal ".
				"                ON sno_personal.codemp = rpc_beneficiario.codemp ".
				"               AND sno_personal.cedper = rpc_beneficiario.ced_bene) ".
				"	 ON sps_dt_spg.codemp='".$this->is_codemp."' ".
				"	AND sps_dt_spg.codcom='".$as_comprobante."' ".
				"	AND sps_dt_spg.codemp = rpc_beneficiario.codemp ".
				"	AND sps_dt_spg.ced_bene = sno_personal.codper ".
				" ORDER BY codcom ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
            $this->io_msg->message("CLASE->Integración SNO MÉTODO->uf_obtener_data_comprobante ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{                 
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;			
                $this->dts_prestaciones->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	} // end function uf_obtener_data_comprobante
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estatus_liquidacion($as_ced_bene,$as_nomina,$as_comprobante,$ai_estatus,$as_estliq,$as_codtipdoc,$ad_fechaconta,$ad_fechaanula)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_estatus_liquidacion
		//		   Access: private
		//	    Arguments: as_periodo  // Período de la Nómina
		//				   as_nomina  // Código de la Nómina
		//				   as_comprobante  // Código del comprobante 
		//				   as_tipo  // Tipo si es de nómina ó de aportes
		//				   ai_estatus  // estatus si es 0 ó 1
		//	      Returns: lb_valido True si se actualizó correctamente
		//	  Description: Método que actualiza el estatus de la nomina en contabilizad o no 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 25/10/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;	
		$ls_sql="UPDATE sps_liquidacion ".
				"   SET estliq='".$as_estliq."'".
				" WHERE codemp='".$this->is_codemp."' ".
				"   AND codnom='".$as_nomina."' ".
				"   AND codper like '%".$as_ced_bene."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->Integración SPS MÉTODO->uf_update_estatus_liquidacion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		if($lb_valido)
		{
			$ls_sql="UPDATE sps_dt_scg ".
					"   SET estatus = ".$ai_estatus.", ".
					"   	codtipdoc = '".$as_codtipdoc."', ".
					"   	fechaconta = '".$ad_fechaconta."', ".
					"   	fechaanula = '".$ad_fechaanula."'".
					" WHERE codemp='".$this->is_codemp."' ".
					"   AND codnom='".$as_nomina."' ".
					"   AND codcom='".$as_comprobante."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
	           	$this->io_msg->message("CLASE->Integración SPS MÉTODO->uf_update_estatus_liquidacion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
				$lb_valido=false;
			}
		}
		if ($lb_valido)
		{
			$ls_sql="UPDATE sps_dt_spg ".
					"   SET estatus = ".$ai_estatus.", ".
					"   	codtipdoc = '".$as_codtipdoc."', ".
					"   	fechaconta = '".$ad_fechaconta."', ".
					"   	fechaanula = '".$ad_fechaanula."'".
					" WHERE codemp='".$this->is_codemp."' ".
					"   AND codnom='".$as_nomina."' ".
					"   AND codcom='".$as_comprobante."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->Integración SPS MÉTODO->uf_update_estatus_liquidacion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
				$lb_valido=false;
			}
		}
		return $lb_valido;
	}// end function uf_update_estatus_liquidacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_recepcion_documento($as_comprobante,$as_tipdoc,$adt_fecha,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_recepcion_documento_tipo_nomina
		//		   Access: private
		//	    Arguments: as_comprobante  // Código de Comprobante
		//				   adt_fecha  // Fecha de contabilización
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se genero la recepción de documento correctamente
		//	  Description: Método que registra la contabilizacion solo de nomina en la recepción documento
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 25/10/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = true;
		$ls_cod_pro = '----------';	
		$ls_ced_bene = $this->dts_prestaciones->getValue("ced_bene",1);	
		$ls_codper = $this->dts_prestaciones->getValue("codper",1);	
		$ls_nomina = $this->dts_prestaciones->getValue("codnom",1);	
        $ls_tipo_destino = 'B';			
		$ls_codtipdoc = $as_tipdoc;  
		$ls_descripcion = $this->dts_prestaciones->getValue("descripcion",1);  
		$adt_fecha = $this->io_function->uf_convertirdatetobd($adt_fecha);
		$ldec_monto=$this->dts_prestaciones->getValue("monto",1);  
		if($ldec_monto<=0)
		{
			$this->io_msg->message("Favor Verifique los datos del beneficiario.");
			$lb_valido=false;
		}		
		// inicia transacción SQL
		if($lb_valido)
		{	
			$this->io_sigesp_int->uf_int_init_transaction_begin(); 
			// Insertamos la Cabecera
			$ls_sql="INSERT INTO cxp_rd (codemp,numrecdoc,codtipdoc,ced_bene,cod_pro,dencondoc,fecemidoc, fecregdoc, fecvendoc,".
					"montotdoc, mondeddoc,moncardoc,tipproben,numref,estprodoc,procede,estlibcom,estaprord,fecaprord,usuaprord,".
					"estimpmun,codcla) VALUES ('".$this->is_codemp."','".$as_comprobante."','".$ls_codtipdoc."','".$ls_ced_bene."',".
					"'".$ls_cod_pro."','".$ls_descripcion."','".$adt_fecha."','".$adt_fecha."','".$adt_fecha."',".$ldec_monto.
					",0,0,'".$ls_tipo_destino."','".$as_comprobante."','R','SNOCNO',0,0,'1900-01-01','',0,'--')";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{  
				$this->io_msg->message("CLASE->Integración SPS MÉTODO->uf_procesar_recepcion_documento ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
			}
		}
		// Insertar los detalles 
		if($lb_valido)
		{	// Insertar los detalles Presupuestarios
	        $lb_valido=$this->uf_insert_recepcion_documento_gasto($as_comprobante,$ls_codtipdoc,$ls_ced_bene,$ls_cod_pro);
		}
		if($lb_valido)
		{	// Insertar los detalles Contables
			$lb_valido=$this->uf_insert_recepcion_documento_contable($as_comprobante,$ls_codtipdoc,$ls_ced_bene,$ls_cod_pro);
		}
	    if($lb_valido)
		{	// Actualizar el estatus en la liquidacion
			$lb_valido=$this->uf_update_estatus_liquidacion($ls_codper,$ls_nomina,$as_comprobante,1,'P',$ls_codtipdoc,$adt_fecha,'1900-01-01');
		}		
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Generó la Recepción de Documento de Prestaciones Sociales <b>".$as_comprobante."</b>, Beneficiario <b>".$ls_ced_bene."</b> ";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);		
		return $lb_valido;
	}  // end function uf_procesar_recepcion_documento_tipo_nomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_recepcion_documento_gasto($as_comprobante,$as_codtipdoc,$as_ced_bene,$as_cod_pro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_recepcion_documento_gasto
		//		   Access: private
		//	    Arguments: as_comprobante  // Código de Comprobante
		//				   as_codtipdoc  // Tipo de Documento
		//				   as_ced_bene  // Cédula del Beneficiario
		//				   as_cod_pro  // Código del Proveedor
		//				   as_codcomapo  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se inserto los detalles presupuestario en la recepción de documento correctamente
		//	  Description: Método que inserta los movimientos de gasto en la tabla de detalle de gasto de la recepcion de documento
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 25/10/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;		
		$ls_procede="SNOCNO";
		$ls_sql="SELECT codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, spg_cuenta, estcla, monto ".
				"  FROM sps_dt_spg ".
				" WHERE codemp='".$this->is_codemp."' ".
				"	AND codcom='".$as_comprobante."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   
           	$this->io_msg->message("CLASE->Integración SNO MÉTODO->uf_insert_recepcion_documento_gasto ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{           
			while((!$rs_data->EOF) and ($lb_valido))
			{
				$ls_estcla=$rs_data->fields["estcla"];
				$la_estructura[0]=$rs_data->fields["codestpro1"];
				$la_estructura[1]=$rs_data->fields["codestpro2"];
				$la_estructura[2]=$rs_data->fields["codestpro3"];		
				$la_estructura[3]=$rs_data->fields["codestpro4"];
				$la_estructura[4]=$rs_data->fields["codestpro5"];
				$la_estructura[5]=$ls_estcla;
				$ls_codestpro=$la_estructura[0].$la_estructura[1].$la_estructura[2].$la_estructura[3].$la_estructura[4];
				$ls_spg_cuenta=$rs_data->fields["spg_cuenta"];
				$ldec_monto=$rs_data->fields["monto"];				
				$ls_documento=$as_comprobante;								 
				$ls_documento=$this->io_sigesp_int->uf_fill_comprobante(trim($ls_documento));
				$ls_status="";
				$ls_denominacion="";
				$ls_sc_cuenta="";
				if(!$this->io_sigesp_int_spg->uf_spg_select_cuenta($this->is_codemp,$la_estructura,$ls_spg_cuenta,&$ls_status,&$ls_denominacion,&$ls_sc_cuenta))
				{
					$this->io_msg->message("La Cuenta Presupuestaria ".$ls_estructura."::".$ls_spg_cuenta." no existe en el plan de cuenta.");			
					$lb_valido=false;
				}
				if($lb_valido)
				{
					$ls_sql="INSERT INTO cxp_rd_spg (codemp,numrecdoc,codtipdoc,ced_bene,cod_pro,procede_doc,numdoccom,codestpro,".
							"spg_cuenta,monto,estcla)  VALUES ('".$this->is_codemp."','".$as_comprobante."','".$as_codtipdoc."',".
							"'".$as_ced_bene."','".$as_cod_pro."','".$ls_procede."','".$ls_documento."','".$ls_codestpro."',".
							"'".$ls_spg_cuenta."',".$ldec_monto.",'".$ls_estcla."')";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$this->io_msg->message("CLASE->Integración SPS MÉTODO->uf_insert_recepcion_documento_gasto ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
						$lb_valido=false;
						break;
					}
				}
				$rs_data->MoveNext();
			} // end while
		}
		$this->io_sql->free_result($rs_data);	 
		return $lb_valido;
    } // end function uf_insert_recepcion_documento_gasto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_recepcion_documento_contable($as_comprobante,$as_codtipdoc,$as_ced_bene,$as_cod_pro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_recepcion_documento_contable
		//		   Access: private
		//	    Arguments: as_comprobante  // Código de Comprobante
		//				   as_codtipdoc  // Tipo de Documento
		//				   as_ced_bene  // Cédula del Beneficiario
		//				   as_cod_pro  // Código del Proveedor
		//				   as_codcomapo  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se inserto los detalles contables en la recepción de documento correctamente
		//	  Description: Método que inserta los movimientos contables en la tabla de detalle de contable de la recepcion de documento
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 25/10/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;		
		$ls_procede="SNOCNO";
		$ls_sql="SELECT sc_cuenta, debhab, monto".
				"  FROM sps_dt_scg ".
				" WHERE codemp='".$this->is_codemp."' ".
				"	AND codcom='".$as_comprobante."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   
           	$this->io_msg->message("CLASE->Integración SNO MÉTODO->uf_insert_recepcion_documento_contable ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{           
			while((!$rs_data->EOF) and ($lb_valido))
			{
				$ls_scg_cuenta = $rs_data->fields["sc_cuenta"];
				$ldec_monto = $rs_data->fields["monto"];				
				$ls_debhab = $rs_data->fields["debhab"];				
				$ls_documento = $as_comprobante;							 
				$ls_documento = $this->io_sigesp_int->uf_fill_comprobante(trim($ls_documento));
				$ls_status="";
				$ls_denominacion="";
				if(!$this->io_sigesp_int_scg->uf_scg_select_cuenta($this->is_codemp,$ls_scg_cuenta,$ls_status,$ls_denominacion))
				{
					$this->io_msg->message("La cuenta contable ".trim($ls_sc_cuenta)." no exite en el plan de cuenta.");			
				}
				if($lb_valido)
				{
					$ls_sql="INSERT INTO cxp_rd_scg (codemp,numrecdoc,codtipdoc,ced_bene,cod_pro,procede_doc,numdoccom,debhab,".
							"sc_cuenta,monto) VALUES ('".$this->is_codemp."','".$as_comprobante."','".$as_codtipdoc."','".$as_ced_bene."',".
							"'".$as_cod_pro."','".$ls_procede."','".$ls_documento."','".$ls_debhab."','".$ls_scg_cuenta."',".$ldec_monto.")";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$this->io_msg->message("CLASE->Integración SPS MÉTODO->uf_insert_recepcion_documento_contable ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
						$lb_valido=false;
						break;
					}
				}
				$rs_data->MoveNext();
			} // end while
		}
		$this->io_sql->free_result($rs_data);	 
		return $lb_valido;
    } // end function uf_insert_recepcion_documento_contable
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_reversar_contabilizacion_prestaciones($as_comprobante,$ad_fechaconta,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reversar_contabilizacion_prestaciones
		//		   Access: public (sigesp_mis_p_reverso_sno.php)
		//	    Arguments: as_comprobante  // Código de Comprobante
		//				   ad_fechaconta  // Fecha en que fue contabilizado el Documento
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el reverso correctamente
		//	  Description: Método que reversa la contabilizacion de la nomina
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 25/10/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
	    if($this->uf_obtener_data_comprobante($as_comprobante))
		{
			$lb_valido = $this->uf_reversar_recepcion_documento($as_comprobante,$ad_fechaconta,$aa_seguridad);
		}		
        return $lb_valido;		
    } // end function uf_reversar_contabilizacion_prestaciones
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_reversar_recepcion_documento($as_comprobante,$ad_fechaconta,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reversar_recepcion_documento
		//		   Access: private
		//	    Arguments: as_comprobante  // Código de Comprobante
		//				   ad_fechaconta  // Fecha en que fue contabilizado el Documento
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el reverso correctamente
		//	  Description: Este metodo elimina la recepción de documento de una nómina
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 25/10/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;
		$ls_estprodoc="";
		$ls_cod_pro = '----------';	
		$ls_codper = $this->dts_prestaciones->getValue("codper",1);	
		$ls_nomina = $this->dts_prestaciones->getValue("codnom",1);	
		$ls_ced_bene = $this->dts_prestaciones->getValue("ced_bene",1);	
        $ls_tipo_destino = 'B';			
		$ls_codtipdoc = $this->dts_prestaciones->getValue("codtipdoc",1);	
        $this->io_sigesp_int->uf_int_init_transaction_begin();
		// Eliminamos los Detalles Contables
		$lb_existe=$this->uf_validar_recepcion_documento($as_comprobante,$ls_ced_bene,$ls_codtipdoc,&$ls_estprodoc);
		if($lb_existe)
		{
			if($ls_estprodoc!="R")
			{
				$this->io_msg->message("La Recepcion de Documentos ".$as_comprobante." debe estar en estatus de Registro");
				return false;
			}
		}
		$ls_sql="DELETE ".
				"  FROM cxp_rd_scg ".
				" WHERE codemp='".$this->is_codemp."' ".
				"   AND numrecdoc='".$as_comprobante."' ".
				"   AND codtipdoc='".$ls_codtipdoc."' ".
				"   AND cod_pro='".$ls_cod_pro."' ".
				"   AND ced_bene='".$ls_ced_bene."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->Integración SNO MÉTODO->uf_reversar_recepcion_documento ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		if ($lb_valido)
		{
			// Eliminamos los Detalles Presupuestarios
			$ls_sql="DELETE ".
					"  FROM cxp_rd_spg ".
					" WHERE codemp='".$this->is_codemp."' ".
					"   AND numrecdoc='".$as_comprobante."' ".
					"   AND codtipdoc='".$ls_codtipdoc."' ".
					"   AND cod_pro='".$ls_cod_pro."' ".
					"   AND ced_bene='".$ls_ced_bene."'";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->Integración SNO MÉTODO->uf_reversar_recepcion_documento ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
				$lb_valido=false;
			}
			if ($lb_valido)
			{
				// Eliminamos los Históricos de La Recepción de Documento
				$ls_sql="DELETE ".
						"  FROM cxp_historico_rd ".
						" WHERE codemp='".$this->is_codemp."' ".
						"   AND numrecdoc='".$as_comprobante."' ".
						"   AND codtipdoc='".$ls_codtipdoc."' ".
						"   AND cod_pro='".$ls_cod_pro."' ".
						"   AND ced_bene='".$ls_ced_bene."'";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$this->io_msg->message("CLASE->Integración SNO MÉTODO->uf_reversar_recepcion_documento ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
					$lb_valido=false;
				}
			}
			if ($lb_valido)
			{
				// Eliminamos La Recepción de Documento
				$ls_sql="DELETE ".
						"  FROM cxp_rd ".
						" WHERE codemp='".$this->is_codemp."' ".
						"   AND numrecdoc='".$as_comprobante."' ".
						"   AND codtipdoc='".$ls_codtipdoc."' ".
						"   AND cod_pro='".$ls_cod_pro."' ".
						"   AND ced_bene='".$ls_ced_bene."'";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$this->io_msg->message("CLASE->Integración SNO MÉTODO->uf_reversar_recepcion_documento ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
					$lb_valido=false;
				}
			}
		}
	    if($lb_valido) 
		{	// Actualizamos el estatus de la Nómina 
		   $lb_valido=$this->uf_update_estatus_liquidacion($ls_codper,$ls_nomina,$as_comprobante,0,'A',$ls_codtipdoc,'1900-01-01','1900-01-01');
	    } 
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reverso la Recepción de Documento de Prestaciones Sociales <b>".$as_comprobante."</b>, Beneficiario <b>".$ls_ced_bene."</b> ";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		return $lb_valido;
	}  // end function uf_reversar_recepcion_documento
	//-----------------------------------------------------------------------------------------------------------------------------------


	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_validar_recepcion_documento($as_numrecdoc,$as_cedbene,$as_codtipdoc,&$as_estprodoc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_recepcion_documento
		//		   Access: public
		//	    Arguments: as_codper  // Codigo de Personal
		//	      Returns: $ls_resultado variable buscado
		//	  Description: Función que obtiene la cedula del beneficiario de nomina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/05/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		$ls_codpro= "----------";	
		$as_estprodoc="";
		$ls_sql="SELECT numrecdoc,estprodoc ".
				"  FROM cxp_rd ".
				" WHERE codemp='".$this->is_codemp."' ".
				"   AND numrecdoc='".$as_numrecdoc."' ".
				"   AND cod_pro='".$ls_codpro."'".
				"   AND ced_bene='".$as_cedbene."'".
				"   AND codtipdoc='".$as_codtipdoc."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Integración SNO MÉTODO->uf_validar_recepcion_documento ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;
				$as_estprodoc=$row["estprodoc"];
			}
			$this->io_sql->free_result($rs_data);		
		}
		return $lb_existe;
	}// end function uf_validar_recepcion_documento
	//-----------------------------------------------------------------------------------------------------------------------------------	

}
?>