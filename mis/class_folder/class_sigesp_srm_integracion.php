<?php
 ////////////////////////////////////////////////////////////////////////////////////////////////////////
 //       Class : class_sigesp_srm_integracion_php                                                     //    
 // Description : Esta clase tiene todos los metodos necesario para el manejo de la rutina integradora //
 //               del sistema de facturaciòn 														   //
 ////////////////////////////////////////////////////////////////////////////////////////////////////////
class class_sigesp_srm_integracion
{
	//Instancia de la clase funciones.
    var $is_msg_error;
	var $dts_empresa; 
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
	function class_sigesp_srm_integracion()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: class_sigesp_srm_integracion
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno de Lang
		// Modificado Por: 																Fecha Última Modificación : 
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
		$this->obj=new class_datastore();
		$this->dts_empresa=$_SESSION["la_empresa"];
		$this->is_codemp=$this->dts_empresa["codemp"];		
		$this->dts_banco=new class_datastore();
		$this->io_msg=new class_mensajes();		
		$this->io_sigesp_int_spg=new class_sigesp_int_spg();
		$this->io_sigesp_int_scg=new class_sigesp_int_scg();	
		$this->io_sigesp_spi=new class_sigesp_int_spi();			
		$this->io_seguridad=new sigesp_c_seguridad();		
		$this->as_procede="";
		$this->as_comprobante="";
		$this->ad_fecha="";
		$this->as_codban="";
		$this->as_ctaban="";
	}// end function class_sigesp_srm_integracion
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destroy_objects()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destroy_objects
		//		   Access: public 
		//	  Description: Destructor de los objectos de la Clase
		//	   Creado Por: Ing. Yesenia Moreno de Lang
		// Modificado Por: 																Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if( is_object($this->io_fecha) ) { unset($this->io_fecha);  }
		if( is_object($this->io_sigesp_int) ) { unset($this->io_sigesp_int);  }
		if( is_object($this->io_function) ) { unset($this->io_function);  }
		if( is_object($this->io_siginc) ) { unset($this->io_siginc);  }
		if( is_object($this->io_connect) ) { unset($this->io_connect);  }
		if( is_object($this->io_sql) ) { unset($this->io_sql);  }	   
		if( is_object($this->obj) ) { unset($this->obj);  }	   
		if( is_object($this->dts_empresa) ) { unset($this->dts_empresa);  }	   
		if( is_object($this->dts_banco) ) { unset($this->dts_banco);  }	   	   
		if( is_object($this->io_msg) ) { unset($this->io_msg);  }	   
		if( is_object($this->io_sigesp_int_spg) ) { unset($this->io_sigesp_int_spg);  }	   
		if( is_object($this->io_sigesp_int_scg) ) { unset($this->io_sigesp_int_scg);  }	   
		if( is_object($this->io_seguridad) ) { unset($this->io_seguridad);  }	   
	}// end function uf_destroy_objects
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_contabilizacion_cobranzas($as_comprobante,$adt_fecha,$as_procede,$as_codban,$as_ctaban,$as_descripcion,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_contabilizacion_cobranzas
		//		   Access: public (sigesp_mis_p_contabiliza_srm.php)
		//	    Arguments: as_comprobante  // Código de Comprobante
		//				   adt_fecha  // Fecha de contabilización
		//				   as_procede  // Procede
		//				   as_codban  // Còdigo de Banco
		//				   as_ctaban  // Cuenta Banco
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Funcion que procesa la contabilización dado un comprobante
		//	   Creado Por: Ing. Yesenia Moreno de Lang
		// Modificado Por: 																Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->io_sigesp_int->uf_int_init_transaction_begin();
		$ls_comprobante_sigesp = $this->io_sigesp_int->uf_fill_comprobante(trim($as_comprobante));
		$adt_fecha=$this->io_function->uf_convertirdatetobd($adt_fecha);
		$ls_codope='DP';
		$ls_estmov='N';
		$ls_codestpro='-------------------------';
		$ls_estcla='-';
		$ld_montototal=0;
		$ls_operacion='DC';
	    $lb_valido=true;
		$ls_sql="INSERT INTO scb_movbco(codemp,codban,ctaban,numdoc,codope,estmov,cod_pro,ced_bene,tipo_destino, codconmov,".
		        "                       fecmov, conmov, nomproben, monto, estbpd, estcon, estcobing, esttra, chevau, estimpche, ".
				"                       monobjret, monret, procede, comprobante, fecha, id_mco, emicheproc, emicheced, emichenom, ".
				"                       emichefec, estmovint, codusu, codopeidb, aliidb, feccon, estreglib, numcarord, numpolcon,".
				"                       coduniadmsig,codbansig,fecordpagsig,tipdocressig,  numdocressig,estmodordpag,codfuefin,".
				"                       forpagsig,medpagsig,codestprosig) ".
				" VALUES ('".$this->is_codemp."','".$as_codban."','".$as_ctaban."','".$ls_comprobante_sigesp."','".$ls_codope."','".$ls_estmov."','----------','----------',".
				"         '-','---','".$adt_fecha."','".$as_descripcion."','Ninguno',0,".
  				"         'M',0,1,0,' ',0,0,0,' ',' ','1900-01-01',' ',0,' ',' ','1900-01-01',0,'ninguno',".
 				"         ' ',0,'1900-01-01',0,' ',0,' ',' ','1900-01-01',' ',' ',0,' ',' ',' ',' ')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
           $this->io_msg->message("CLASE->Integración SRM 1 MÉTODO->uf_insert_movimiento_banco ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));		
		   $lb_valido=false;
		}
		if($lb_valido)
		{
				$ls_sql="SELECT sc_cuenta, monto, descripcion, numdoc ".
						"  FROM mis_sigesp_banco ".
						" WHERE comprobante='".$as_comprobante."' ".
						"   AND procede='".$as_procede."' ".
						"   AND fecdep='".$adt_fecha."' ".
						"   AND codban='".$as_codban."' ".
						"   AND ctaban='".$as_ctaban."' ".
						"   AND modulo='SPI' ".
						" ORDER BY sc_cuenta ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{   
				$this->io_msg->message("CLASE->Integración SRM 2 MÉTODO->uf_insert_movimiento_banco ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
				$lb_valido=false;
			}
			else
			{           
				while((!$rs_data->EOF) && ($lb_valido))
				{
					$ls_cuenta=$rs_data->fields["sc_cuenta"];
					$ldec_monto=number_format($rs_data->fields["monto"],2,".","");
					$ls_descripcion=$rs_data->fields["descripcion"];	
					$ls_numdoc=$rs_data->fields["numdoc"];	
					$as_status="";
					$as_denominacion="";
					$as_scgcuenta="";
					if($this->io_sigesp_spi->uf_spi_select_cuenta($this->is_codemp,$ls_cuenta,&$as_status,&$as_denominacion,&$as_scgcuenta))
					{
						$ls_sql="INSERT INTO scb_movbco_spi (codemp, codban, ctaban, numdoc, codope, estmov, spi_cuenta, documento, operacion, ".
								"                            desmov, procede_doc, monto, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, estcla) ".
								"VALUES ('".$this->is_codemp."','".$as_codban."',"."'".$as_ctaban."','".$ls_comprobante_sigesp."','".$ls_codope."','".$ls_estmov."', ".
								"        '".$ls_cuenta."','".$ls_numdoc."','".$ls_operacion."','".$ls_descripcion."','".$as_procede."',".$ldec_monto.",'".$ls_codestpro."', ".
								"        '".$ls_codestpro."','".$ls_codestpro."','".$ls_codestpro."','".$ls_codestpro."','".$ls_estcla."' )";
						$li_row=$this->io_sql->execute($ls_sql);
						if($li_row===false)
						{
							$this->io_msg->message("CLASE->Integración SRM 3 MÉTODO->uf_insert_movimiento_banco ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
							$lb_valido=false;
							break;
						}
					}
					else
					{
						$this->io_msg->message("La Cuenta de Ingreso ".$ls_cuenta." no existe.");			
						$lb_valido=false;
						break;
					}
					$rs_data->MoveNext();
				} // end while
			}
		}
		if($lb_valido)
		{
				$ls_sql="SELECT sc_cuenta, debhab, monto,descripcion,numdoc,documento ".
						"  FROM mis_sigesp_banco ".
						" WHERE comprobante='".$as_comprobante."' ".
						"   AND procede='".$as_procede."' ".
						"   AND fecdep='".$adt_fecha."' ".
						"   AND codban='".$as_codban."' ".
						"   AND ctaban='".$as_ctaban."' ".
						"   AND modulo='SCG' ".
						" ORDER BY debhab";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{   
				$this->io_msg->message("CLASE->Integración SRM 4 MÉTODO->uf_insert_movimiento_banco ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));	
				$lb_valido=false;
			}
			else
			{           
				while((!$rs_data->EOF) && ($lb_valido))
				{
					$ls_scg_cuenta = $rs_data->fields["sc_cuenta"];
					$ldec_monto = number_format($rs_data->fields["monto"],2,".","");				
					$ls_debhab = $rs_data->fields["debhab"];				
					$ls_descripcion=$rs_data->fields["descripcion"];				
					$ls_numdoc=$rs_data->fields["numdoc"];
					if($rs_data->fields["documento"]=='000000000000001')
					{	
						$ld_montototal=$ldec_monto;
					}
					$as_status="";
					$as_denominacion="";
					if($this->io_sigesp_int_scg->uf_scg_select_cuenta($this->is_codemp,$ls_scg_cuenta,&$as_status,&$as_denominacion))
					{
						$ls_sql="INSERT INTO scb_movbco_scg (codemp, codban, ctaban, numdoc, codope, estmov, scg_cuenta, debhab, codded, ".
								"documento, desmov, procede_doc, monto, monobjret) VALUES ('".$this->is_codemp."','".$as_codban."',".
								"'".$as_ctaban."','".$ls_comprobante_sigesp."','".$ls_codope."','".$ls_estmov."','".$ls_scg_cuenta."','".$ls_debhab."','00000',".
								"'".$ls_numdoc."','".$ls_descripcion."','".$as_procede."',".$ldec_monto.",0)";
						$li_row=$this->io_sql->execute($ls_sql);
						if($li_row===false)
						{
							$this->io_msg->message("CLASE->Integración SRM 5 MÉTODO->uf_insert_recepcion_documento_contable ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
							$lb_valido=false;
							break;
						}
					}
					else
					{
						$this->io_msg->message("La Cuenta de Contable ".$ls_scg_cuenta." no existe.");			
						$lb_valido=false;
						break;
					}
					$rs_data->MoveNext();
				} // end while
			}
		}
		if($lb_valido)
		{
			$ldec_monto = number_format($ld_montototal,2,".","");				
			$ls_sql="UPDATE scb_movbco ".
					"   SET monto = ".$ld_montototal." ".
					" WHERE codemp='".$this->is_codemp."' ".
					"   AND codban='".$as_codban."' ".
					"   AND ctaban='".$as_ctaban."' ".
					"   AND numdoc='".$as_comprobante."' ".
					"   AND codope='".$ls_codope."' ".
					"   AND estmov='".$ls_estmov."' ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{   
				$this->io_msg->message("CLASE->Integración SRM 6 MÉTODO->uf_insert_movimiento_banco ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
				$lb_valido=false;
			}
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_estatus($as_comprobante,$adt_fecha,$as_procede,$as_codban,$as_ctaban,1,$ls_comprobante_sigesp,$adt_fecha,'1900-01-01');
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Contabilizó la Cobranza de rentas municipales <b>".$as_comprobante."-".$as_codban."-".$as_ctaban."</b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);		
		return  $lb_valido;
	}  // end function uf_procesar_contabilizacion_cobranzas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_reversar_contabilizacion_cobranzas($as_comprobante,$adt_fecha,$as_procede,$as_codban,$as_ctaban,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reversar_contabilizacion_cobranzas
		//		   Access: public (sigesp_mis_p_contabiliza_srm.php)
		//	    Arguments: as_comprobante  // Código de Comprobante
		//				   adt_fecha  // Fecha de contabilización
		//				   as_procede  // Procede
		//				   as_codban  // Còdigo de Banco
		//				   as_ctaban  // Cuenta Banco
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se eliminó correctamente
		//	  Description: Este metodo elimina un registro de movimiento de banco
		//	   Creado Por: Ing. Yesenia Moreno de Lang
		// Modificado Por: 													Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;		
		$this->io_sigesp_int->uf_int_init_transaction_begin();
		$ls_comprobante_sigesp = $this->io_sigesp_int->uf_fill_comprobante(trim($as_comprobante));
		$adt_fecha=$this->io_function->uf_convertirdatetobd($adt_fecha);
		$ls_codope='DP';
		$ls_estmov='N';
		$ls_sql="SELECT * ".
				"  FROM scb_movbco ".
				" WHERE codemp='".$this->is_codemp."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numdoc='".$ls_comprobante_sigesp."' ".
				"   AND codope='".$ls_codope."' ".
				"   AND estmov='".$ls_estmov."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   
			$this->io_msg->message("CLASE->Integración SRM 4 MÉTODO->uf_insert_movimiento_banco ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));	
			$lb_valido=false;
		}
		else
		{           
			if($rs_data->EOF)
			{
			 	$lb_valido=false;
			   $this->io_msg->message("El Movimiento de Banco no existe ó está contabilizado.");			
			}
		}
		if($lb_valido)
		{
			$ls_sql="DELETE ".
					"  FROM scb_movbco_spi ".
					" WHERE codemp='".$this->is_codemp."' ".
					"   AND codban='".$as_codban."' ".
					"   AND ctaban='".$as_ctaban."' ".
					"   AND numdoc='".$ls_comprobante_sigesp."' ".
					"   AND codope='".$ls_codope."' ".
					"   AND estmov='".$ls_estmov."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{   
			   $this->io_msg->message("CLASE->Integración SRM MÉTODO->uf_delete_movimiento_banco ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			   $lb_valido=false;
			}
		}
		if($lb_valido)
		{
			$ls_sql="DELETE ".
					"  FROM scb_movbco_scg ".
					" WHERE codemp='".$this->is_codemp."' ".
					"   AND codban='".$as_codban."' ".
					"   AND ctaban='".$as_ctaban."' ".
					"   AND numdoc='".$ls_comprobante_sigesp."' ".
					"   AND codope='".$ls_codope."' ".
					"   AND estmov='".$ls_estmov."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{   
			   $this->io_msg->message("CLASE->Integración SCF MÉTODO->uf_delete_movimiento_banco ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			   $lb_valido=false;
			}
		}
		if($lb_valido)
		{
			$ls_sql="DELETE ".
					"  FROM scb_movbco ".
					" WHERE codemp='".$this->is_codemp."' ".
					"   AND codban='".$as_codban."' ".
					"   AND ctaban='".$as_ctaban."' ".
					"   AND numdoc='".$ls_comprobante_sigesp."' ".
					"   AND codope='".$ls_codope."' ".
					"   AND estmov='".$ls_estmov."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{   
			   $this->io_msg->message("CLASE->Integración SRM MÉTODO->uf_delete_movimiento_banco ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			   $lb_valido=false;
			}
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_estatus($as_comprobante,$adt_fecha,$as_procede,$as_codban,$as_ctaban,0,'','1900-01-01','1900-01-01');
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reverso la cobranza <b>".$as_comprobante."-".$as_codban."-".$as_ctaban."</b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);					$as_status="";

			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);		
		return $lb_valido;
    } // end uf_reversar_contabilizacion_cobranzas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estatus($as_comprobante,$adt_fecha,$as_procede,$as_codban,$as_ctaban,$ai_estatus,$as_comprobante_sigesp,$adt_fechaconta,$adt_fechaanula)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_estatus
		//		   Access: private
		//	    Arguments: as_comprobante  // Código de Comprobante
		//				   adt_fecha  // Fecha de contabilización
		//				   as_procede  // Procede
		//				   as_codban  // Còdigo de Banco
		//				   as_ctaban  // Cuenta Banco
		//				   ai_estatus  // estatus si es 0 ó 1
		//	      Returns: lb_valido True si se actualizó correctamente
		//	  Description: Método que actualiza el estatus del pago en contabilizad o no 
		//	   Creado Por: Ing. Yesenia Moreno de Lang
		// Modificado Por: 							Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;	
		$ls_sql="UPDATE mis_sigesp_banco ".
				"   SET estint=".$ai_estatus.", ".
				"       comprobante_sigesp='".$as_comprobante_sigesp."',".
				"       fechaconta='".$adt_fechaconta."', ".
				"       fechaanula='".$adt_fechaanula."' ".
				" WHERE comprobante='".$as_comprobante."' ".
				"   AND procede='".$as_procede."' ".
				"   AND fecdep='".$adt_fecha."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->Integración SRM MÉTODO->uf_update_estatus ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_update_estatus
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>