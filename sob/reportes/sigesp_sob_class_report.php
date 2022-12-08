<?php
class sigesp_sob_class_report
{
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sob_class_report()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sob_class_report
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno /Ing. Luis Lang
		// Fecha Creación: 11/03/2007 								
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$this->io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($this->io_conexion);	
		$this->rs_data="";
		$this->rs_data_detalle="";
		require_once("../../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
        $this->ls_loncodestpro1=$_SESSION["la_empresa"]["loncodestpro1"];
		$this->ls_loncodestpro2=$_SESSION["la_empresa"]["loncodestpro2"];
		$this->ls_loncodestpro3=$_SESSION["la_empresa"]["loncodestpro3"];
		$this->ls_loncodestpro4=$_SESSION["la_empresa"]["loncodestpro4"];
		$this->ls_loncodestpro5=$_SESSION["la_empresa"]["loncodestpro5"];
		$this->DS=new class_datastore();
		$this->DS_detalle=new class_datastore();
		$this->ds_componente=new class_datastore();
	
	}// end function sigesp_sob_class_report
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_obra($as_codobr)
	{ 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_obra
		//         Access: public 
		//	    Arguments: as_codobr     // Còdigo de la Obra
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de la una obra
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 20/05/2009									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sob_obra.codobr, sob_obra.desobr,sob_obra.dirobr,sob_obra.obsobr,sob_obra.resobr,sob_obra.feciniobr, ".
				"       sob_obra.fecfinobr,sob_obra.monto,sob_obra.feccreobr,sob_tenencia.nomten,sob_tipoestructura.nomtipest, ".
				"       sob_sistemaconstructivo.nomsiscon,sob_propietario.nompro,sob_tipoobra.nomtob,sob_obra.staobr, ".
				"		sigesp_pais.despai, sigesp_estados.desest, sigesp_municipio.denmun, sigesp_parroquia.denpar, ".
				"		sigesp_comunidad.nomcom ".
				"  FROM sob_obra ".
				" INNER JOIN sob_tenencia  ".
				"    ON sob_obra.codemp='".$this->ls_codemp."'  ".
				"   AND sob_obra.codobr='".$as_codobr."'  ".
				"   AND sob_obra.codten=sob_tenencia.codten ".
				" INNER JOIN sob_tipoestructura ".
				"    ON sob_obra.codemp='".$this->ls_codemp."' ".
				"   AND sob_obra.codobr='".$as_codobr."' ".
				"   AND sob_obra.codemp=sob_tipoestructura.codemp ".
				"   AND sob_obra.codtipest=sob_tipoestructura.codtipest ".
				" INNER JOIN sob_sistemaconstructivo ".
				"    ON sob_obra.codemp='".$this->ls_codemp."' ".
				"   AND sob_obra.codobr='".$as_codobr."' ".
				"   AND sob_obra.codemp=sob_sistemaconstructivo.codemp ".
				"   AND sob_obra.codsiscon=sob_sistemaconstructivo.codsiscon ".
				" INNER JOIN sob_propietario ".
				"    ON sob_obra.codemp='".$this->ls_codemp."' ".
				"   AND sob_obra.codobr='".$as_codobr."' ".
				"   AND sob_obra.codemp=sob_propietario.codemp ".
				"   AND sob_obra.codpro=sob_propietario.codpro ".
				" INNER JOIN sob_tipoobra ".
				"    ON sob_obra.codemp='".$this->ls_codemp."' ".
				"   AND sob_obra.codobr='".$as_codobr."' ". 
				"   AND sob_obra.codemp=sob_tipoobra.codemp ".
				"   AND sob_obra.codtob=sob_tipoobra.codtob ".
				" INNER JOIN sigesp_pais ".
				"    ON sob_obra.codemp='".$this->ls_codemp."' ". 
				"   AND sob_obra.codobr='".$as_codobr."' ".
				"   AND sob_obra.codpai=sigesp_pais.codpai ".
				" INNER JOIN sigesp_estados ".
				"    ON sob_obra.codemp='".$this->ls_codemp."' ". 
				"   AND sob_obra.codobr='".$as_codobr."' ".
				"   AND sob_obra.codpai=sigesp_estados.codpai ".
				"   AND sob_obra.codest=sigesp_estados.codest ".
				" INNER JOIN sigesp_municipio ".
				"    ON sob_obra.codemp='".$this->ls_codemp."' ". 
				"   AND sob_obra.codobr='".$as_codobr."' ".
				"   AND sob_obra.codpai=sigesp_municipio.codpai ".
				"   AND sob_obra.codest=sigesp_municipio.codest ".
				"   AND sob_obra.codmun=sigesp_municipio.codmun ".
				" INNER JOIN sigesp_parroquia ".
				"    ON sob_obra.codemp='".$this->ls_codemp."' ". 
				"   AND sob_obra.codobr='".$as_codobr."' ".
				"   AND sob_obra.codpai=sigesp_parroquia.codpai ".
				"   AND sob_obra.codest=sigesp_parroquia.codest ".
				"   AND sob_obra.codmun=sigesp_parroquia.codmun ".
				"   AND sob_obra.codpar=sigesp_parroquia.codpar ".
				" INNER JOIN sigesp_comunidad ".
				"    ON sob_obra.codemp='".$this->ls_codemp."' ". 
				"   AND sob_obra.codobr='".$as_codobr."' ".
				"   AND sob_obra.codpai=sigesp_comunidad.codpai ".
				"   AND sob_obra.codest=sigesp_comunidad.codest ".
				"   AND sob_obra.codmun=sigesp_comunidad.codmun ".
				"   AND sob_obra.codpar=sigesp_comunidad.codpar ".
				"   AND sob_obra.codcom=sigesp_comunidad.codcom ".
				" WHERE sob_obra.codemp='".$this->ls_codemp."' ".
				"   AND sob_obra.codobr='".$as_codobr."' ";
		$this->rs_data=$this->io_sql->select($ls_sql);
		if($this->rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_obra ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($this->rs_data->RecordCount()==0)
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	}// end function uf_select_obra
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_partidas($as_codobr)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_partidas
		//         Access: public 
		//	    Arguments: as_codobr     // Còdigo de la Obra
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las partidas de una obra
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 20/05/2009									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sob_partidaobra.codpar,sob_partida.nompar,sob_unidad.nomuni,sob_partida.prepar,sob_partidaobra.canparobr ".
				"  FROM sob_partidaobra   ".
				" INNER JOIN (sob_partida ".
				" 				INNER JOIN sob_unidad ".
				"    			   ON sob_partida.codemp='".$this->ls_codemp."' ".
				"  				  AND sob_partida.codemp=sob_unidad.codemp ".
				"  				  AND sob_partida.coduni=sob_unidad.coduni) ".
				"    ON sob_partidaobra.codemp='".$this->ls_codemp."' ".
				"   AND sob_partidaobra.codobr='".$as_codobr."' ".
				"   AND sob_partidaobra.codemp=sob_partida.codemp ".
				"   AND sob_partidaobra.codpar=sob_partida.codpar ".
				" WHERE sob_partidaobra.codemp='".$this->ls_codemp."' ".
				"   AND sob_partidaobra.codobr='".$as_codobr."' ".
				" ORDER BY sob_partidaobra.codpar ASC";
		$this->rs_data_detalle=$this->io_sql->select($ls_sql);
		if($this->rs_data_detalle===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_partidas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_select_partidas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_fuentesfinancimiento($as_codobr)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_partidas
		//         Access: public 
		//	    Arguments: as_codobr     // Còdigo de la Obra
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las fuentes de financimiento de una obra
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 20/05/2009									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sob_fuentefinanciamientoobra.codfuefin, sigesp_fuentefinanciamiento.denfuefin,sob_fuentefinanciamientoobra.monto ".
				"  FROM sob_fuentefinanciamientoobra  ".
				" INNER JOIN sigesp_fuentefinanciamiento  ".
				"    ON sob_fuentefinanciamientoobra.codemp='".$this->ls_codemp."' ".
				"   AND sob_fuentefinanciamientoobra.codobr='".$as_codobr."'".
				"   AND sob_fuentefinanciamientoobra.codemp = sigesp_fuentefinanciamiento.codemp ".
				"   AND sob_fuentefinanciamientoobra.codfuefin = sigesp_fuentefinanciamiento.codfuefin ".
				" WHERE sob_fuentefinanciamientoobra.codemp='".$this->ls_codemp."'  ".
				"   AND sob_fuentefinanciamientoobra.codobr='".$as_codobr."'  ".
				" ORDER BY sob_fuentefinanciamientoobra.codfuefin ASC";
		$this->rs_data_detalle=$this->io_sql->select($ls_sql);
		if($this->rs_data_detalle===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_fuentesfinancimiento ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_select_dt_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_documento($as_coddoc,$as_codcondes,$as_codconhas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_documento
		//         Access: public 
		//	    Arguments: as_coddoc // Código del documento
		//                 as_codcondes //Codigo de Contratista desde
		//                 as_codcondes //Codigo de Contratista hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que busca la información del documento
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 13/05/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		if(!empty($as_codperdes))
		{
			$ls_criterio= $ls_criterio." AND rpc_proveedor.cod_pro>='".$as_codcondes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio." AND rpc_proveedor.cod_pro<='".$as_codconhas."'";
		}
		$ls_sql="SELECT coddoc, desdoc, condoc, tamletdoc, intlindoc, marinfdoc, marsupdoc, titdoc, piepagdoc, ".
				"		tamletpiedoc, arcrtfdoc,tipdoc ".
				"  FROM sob_documento ".
				" WHERE codemp = '".$this->ls_codemp."' ".
				"   AND coddoc = '".$as_coddoc."' ".
				"   AND codemp IN (SELECT codemp FROM rpc_proveedor ".
				"					WHERE codemp = '".$this->ls_codemp."'".
				"                    ".$ls_criterio.")";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_documento ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_documento
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_documento_contratista($as_codcondes,$as_codconhas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_documento_contratista
		//         Access: public   
		//	    Arguments: as_codcondes // Código Contratista donde inicia el filtro
		//	   			   as_codconhas // Código Contratista donde finaliza el filtro
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que busca la información del Contratista
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 15/05/2008 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_criterioperiodo="";
		if(!empty($as_codperdes))
		{
			$ls_criterio= " AND sob_asignacion.cod_pro>='".$as_codcondes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio." AND sob_asignacion.cod_pro<='".$as_codconhas."'";
		}

		$ls_sql="SELECT sob_obra.desobr,sob_obra.dirobr,sob_obra.resobr,sob_contrato.codcon,sob_contrato.monto,sob_contrato.monmaxcon,".
				"		sob_contrato.feccon,sob_contrato.obscon,sob_contrato.fecinicon,rpc_proveedor.nompro,rpc_proveedor.dirpro, ".
				"       rpc_proveedor.rifpro,rpc_proveedor.telpro,rpc_proveedor.capital".
				"  FROM sob_asignacion, sob_contrato, rpc_proveedor, sob_obra ".
				" WHERE sob_asignacion.codemp='".$this->ls_codemp."'".
				"   AND sob_asignacion.codemp=sob_contrato.codemp ".
				"   AND sob_asignacion.codasi=sob_contrato.codasi ".
				"   AND sob_asignacion.codemp=rpc_proveedor.codemp".
				"   AND sob_asignacion.cod_pro=rpc_proveedor.cod_pro".		
				"   AND sob_asignacion.codemp=sob_obra.codemp".
				"   AND sob_asignacion.codobr=sob_obra.codobr".		
				$ls_criterio; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_documento_contratista ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_documento_contratista
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_documento_actas($as_codcondes,$as_codconhas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_documento_actas
		//         Access: public   
		//	    Arguments: as_codcondes // Código Contratista donde inicia el filtro
		//	   			   as_codconhas // Código Contratista donde finaliza el filtro
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que busca la información del Contratista
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 15/05/2008 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_criterioperiodo="";
		if(!empty($as_codperdes))
		{
			$ls_criterio= " AND sob_asignacion.cod_pro>='".$as_codcondes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio." AND sob_asignacion.cod_pro<='".$as_codconhas."'";
		}

		$ls_sql="SELECT sob_obra.desobr,sob_obra.dirobr,sob_obra.resobr,sob_contrato.codcon,sob_contrato.monto,sob_contrato.monmaxcon,".
				"		sob_contrato.feccon,sob_contrato.obscon,sob_contrato.fecinicon,rpc_proveedor.nompro,rpc_proveedor.dirpro, ".
				"       rpc_proveedor.rifpro,rpc_proveedor.telpro,rpc_proveedor.capital,sob_acta.codact,sob_acta.fecact,sob_acta.feciniact".
				"       sob_acta.monact,sob_acta.obsact".
				"  FROM sob_asignacion, sob_contrato, rpc_proveedor, sob_obra,sob_acta ".
				" WHERE sob_asignacion.codemp='".$this->ls_codemp."'".
				"   AND sob_asignacion.codemp=sob_contrato.codemp ".
				"   AND sob_asignacion.codasi=sob_contrato.codasi ".
				"   AND sob_asignacion.codemp=rpc_proveedor.codemp".
				"   AND sob_asignacion.cod_pro=rpc_proveedor.cod_pro".		
				"   AND sob_asignacion.codemp=sob_obra.codemp".
				"   AND sob_asignacion.codobr=sob_obra.codobr".		
				"   AND sob_contrato.codemp=sob_acta.codemp".
				"   AND sob_contrato.codcon=sob_acta.codcon".		
				$ls_criterio; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_documento_actas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_documento_actas
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_documento_asignacion($as_codcondes,$as_codconhas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_documento_asignacion
		//         Access: public   
		//	    Arguments: as_codcondes // Código Contratista donde inicia el filtro
		//	   			   as_codconhas // Código Contratista donde finaliza el filtro
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que busca la información del Contratista
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 15/05/2008 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_criterioperiodo="";
		if(!empty($as_codperdes))
		{
			$ls_criterio= " AND sob_asignacion.cod_pro>='".$as_codcondes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio." AND sob_asignacion.cod_pro<='".$as_codconhas."'";
		}

		$ls_sql="SELECT sob_obra.desobr,sob_obra.dirobr,sob_obra.resobr,rpc_proveedor.nompro,rpc_proveedor.dirpro, ".
				"       rpc_proveedor.rifpro,rpc_proveedor.telpro,rpc_proveedor.capital, sob_asignacion.codasi, sob_asignacion.cod_pro_ins,".
				"       sob_asignacion.fecasi,sob_asignacion.montotasi,".
				"      (SELECT nompro FROM rpc_proveedor AS inspector".
				"        WHERE sob_asignacion.codemp=inspector.codemp".
				"          AND sob_asignacion.cod_pro_ins=inspector.cod_pro) AS nomproins,".
				"      (SELECT rifpro FROM rpc_proveedor AS inspector".
				"        WHERE sob_asignacion.codemp=inspector.codemp".
				"          AND sob_asignacion.cod_pro_ins=inspector.cod_pro) AS rifproins".
				"  FROM sob_asignacion, rpc_proveedor, sob_obra ".
				" WHERE sob_asignacion.codemp='".$this->ls_codemp."'".
				"   AND sob_asignacion.codemp=rpc_proveedor.codemp".
				"   AND sob_asignacion.cod_pro=rpc_proveedor.cod_pro".		
				"   AND sob_asignacion.codemp=sob_obra.codemp".
				"   AND sob_asignacion.codobr=sob_obra.codobr".		
				$ls_criterio;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_documento_asignacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_documento_contratista
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_contrato($as_codasi,$as_codcon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_contrato
		//         Access: public 
		//	    Arguments: as_codobr     // Còdigo de la Obra
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las fuentes de financimiento de una obra
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 20/05/2009									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sob_contrato.codcon, sob_contrato.codasi, sob_contrato.monto, sob_contrato.feccon, sob_contrato.fecinicon, sob_contrato.fecfincon,".
				"       sob_contrato.obscon, sob_asignacion.fecasi, sob_asignacion.montotasi,sob_asignacion.cod_pro,sob_asignacion.cod_pro_ins,".
				"       (SELECT montotant FROM sob_anticipo".
				"         WHERE codemp='".$this->ls_codemp."'".
				"           AND codcon='".$as_codcon."'".
				"           AND estspgscg='1') AS montotant,".
				"       (SELECT codant FROM sob_anticipo".
				"         WHERE codemp='".$this->ls_codemp."'".
				"           AND codcon='".$as_codcon."'".
				"           AND estspgscg='1') AS codant,".
				"       (SELECT fecant FROM sob_anticipo".
				"         WHERE codemp='".$this->ls_codemp."'".
				"           AND codcon='".$as_codcon."'".
				"           AND estspgscg='1') AS fecant,".
				"       (SELECT desobr FROM sob_obra".
				"         WHERE sob_asignacion.codemp='".$this->ls_codemp."'".
				"           AND sob_asignacion.codasi='".$as_codasi."'".
				"           AND sob_asignacion.codemp=sob_obra.codemp".
				"           AND sob_asignacion.codobr=sob_obra.codobr) AS desobr,".
				"       (SELECT nompro FROM rpc_proveedor".
				"         WHERE sob_asignacion.codemp='".$this->ls_codemp."'".
				"           AND sob_asignacion.codasi='".$as_codasi."'".
				"           AND sob_asignacion.codemp=rpc_proveedor.codemp".
				"           AND sob_asignacion.cod_pro=rpc_proveedor.cod_pro) AS empcon,".
				"       (SELECT nompro FROM rpc_proveedor".
				"         WHERE sob_asignacion.codemp='".$this->ls_codemp."'".
				"           AND sob_asignacion.codasi='".$as_codasi."'".
				"           AND sob_asignacion.codemp=rpc_proveedor.codemp".
				"           AND sob_asignacion.cod_pro_ins=rpc_proveedor.cod_pro) AS empins".
				"  FROM sob_contrato,sob_asignacion  ".
				" WHERE sob_contrato.codemp='".$this->ls_codemp."'  ".
				"   AND sob_contrato.codasi='".$as_codasi."'  ".
				"   AND sob_contrato.codcon='".$as_codcon."'  ".
				"   AND sob_contrato.codemp=sob_asignacion.codemp  ".
				"   AND sob_contrato.codasi=sob_asignacion.codasi   ".
				" ORDER BY sob_contrato.codcon ASC";
		$this->rs_data=$this->io_sql->select($ls_sql);
		if($this->rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_contrato ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_select_dt_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_cuentas_asignacion($as_codasi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_cuentas_asignacion
		//         Access: public 
		//	    Arguments: as_codobr     // Còdigo de la Obra
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las fuentes de financimiento de una obra
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 20/05/2009									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sob_cuentasasignacion.codestpro1, sob_cuentasasignacion.codestpro2, sob_cuentasasignacion.codestpro3,".
				"       sob_cuentasasignacion.codestpro4, sob_cuentasasignacion.codestpro5, sob_cuentasasignacion.spg_cuenta,".
				"       sob_cuentasasignacion.estcla,spg_cuentas.denominacion".
				"  FROM sob_cuentasasignacion,spg_cuentas  ".
				" WHERE sob_cuentasasignacion.codemp='".$this->ls_codemp."'  ".
				"   AND sob_cuentasasignacion.codasi='".$as_codasi."'  ".
				"   AND sob_cuentasasignacion.codestpro1=spg_cuentas.codestpro1  ".
				"   AND sob_cuentasasignacion.codestpro2=spg_cuentas.codestpro2  ".
				"   AND sob_cuentasasignacion.codestpro3=spg_cuentas.codestpro3  ".
				"   AND sob_cuentasasignacion.codestpro4=spg_cuentas.codestpro4  ".
				"   AND sob_cuentasasignacion.codestpro5=spg_cuentas.codestpro5  ".
				"   AND sob_cuentasasignacion.estcla=spg_cuentas.estcla  ".
				"   AND sob_cuentasasignacion.spg_cuenta=spg_cuentas.spg_cuenta  ".
				" ORDER BY sob_cuentasasignacion.spg_cuenta ASC";//print $ls_sql;
		$this->rs_datacuentas=$this->io_sql->select($ls_sql);
		if($this->rs_datacuentas===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_cuentas_asignacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_select_cuentas_asignacion
	//-----------------------------------------------------------------------------------------------------------------------------------


	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_valuaciones($as_codcon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_valuaciones
		//         Access: public 
		//	    Arguments: as_codobr     // Còdigo de la Obra
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las fuentes de financimiento de una obra
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 20/05/2009									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codcon,codval,fecinival,fecfinval,obsval,montotval".
				"  FROM sob_valuacion  ".
				" WHERE codemp='".$this->ls_codemp."'  ".
				"   AND codcon='".$as_codcon."'  ";//print $ls_sql;
		$this->rs_datavaluaciones=$this->io_sql->select($ls_sql);
		if($this->rs_datavaluaciones===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_valuaciones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_select_valuaciones
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_listadoobras($as_coddes,$as_codhas,$as_fecregdes,$as_fecreghas,$as_codorgeje)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_listadoobras
		//         Access: public 
		//	    Arguments: as_codobr     // Còdigo de la Obra
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las fuentes de financimiento de una obra
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 20/05/2009									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
	
		if($as_coddes!="")
		{
			$ls_criterio=$ls_criterio." AND codobr>='".$as_coddes."'";
		}
		if($as_codhas!="")
		{
			$ls_criterio=$ls_criterio." AND codobr<='".$as_codhas."'";
		}
		if($as_fecregdes!="")
		{
			$as_fecregdes=$this->io_funciones->uf_convertirdatetobd($as_fecregdes);
			$ls_criterio=$ls_criterio." AND feccreobr>='".$as_fecregdes."'";
		}
		if($as_fecreghas!="")
		{
			$as_fecreghas=$this->io_funciones->uf_convertirdatetobd($as_fecreghas);
			$ls_criterio=$ls_criterio." AND feccreobr<='".$as_fecreghas."'";
		}
		if($as_codorgeje!="")
		{
			$ls_criterio=$ls_criterio." AND codpro='".$as_codorgeje."'";
		}
		$ls_sql="SELECT codobr,desobr, monto, feciniobr, fecfinobr,".
				"       (SELECT despai FROM sigesp_pais WHERE sob_obra.codpai=sigesp_pais.codpai) AS despai,".
				"       (SELECT desest FROM sigesp_estados WHERE sob_obra.codpai=sigesp_estados.codpai AND sob_obra.codest=sigesp_estados.codest) AS desest,".
				"       (SELECT denmun FROM sigesp_municipio WHERE sob_obra.codpai=sigesp_municipio.codpai AND sob_obra.codest=sigesp_municipio.codest AND sob_obra.codmun=sigesp_municipio.codmun) AS desmun,".
				"       (SELECT denpar FROM sigesp_parroquia WHERE sob_obra.codpai=sigesp_parroquia.codpai AND sob_obra.codest=sigesp_parroquia.codest AND sob_obra.codmun=sigesp_parroquia.codmun AND sob_obra.codpar=sigesp_parroquia.codpar) AS despar,".
				"       (SELECT nomcom FROM sigesp_comunidad WHERE sob_obra.codpai=sigesp_comunidad.codpai AND sob_obra.codest=sigesp_comunidad.codest AND sob_obra.codmun=sigesp_comunidad.codmun AND sob_obra.codpar=sigesp_comunidad.codpar AND sob_obra.codcom=sigesp_comunidad.codcom) AS descom,".
				"       (SELECT SUM(sob_anticipo.monto) FROM sob_asignacion,sob_contrato,sob_anticipo".
				"         WHERE sob_obra.codemp=sob_asignacion.codemp".
				"           AND sob_obra.codobr=sob_asignacion.codobr".
				"           AND sob_asignacion.codemp=sob_contrato.codemp".
				"           AND sob_asignacion.codasi=sob_contrato.codasi".
				"           AND sob_contrato.codemp=sob_anticipo.codemp".
				"           AND sob_contrato.codcon=sob_anticipo.codcon".
				"           AND sob_anticipo.estspgscg='1') AS anticipo,".
				"       (SELECT SUM(sob_valuacion.subtot) FROM sob_asignacion,sob_contrato,sob_valuacion".
				"         WHERE sob_obra.codemp=sob_asignacion.codemp".
				"           AND sob_obra.codobr=sob_asignacion.codobr".
				"           AND sob_asignacion.codemp=sob_contrato.codemp".
				"           AND sob_asignacion.codasi=sob_contrato.codasi".
				"           AND sob_contrato.codemp=sob_valuacion.codemp".
				"           AND sob_contrato.codcon=sob_valuacion.codcon".
				"           AND sob_valuacion.estspgscg='1') AS valuacion".
				"  FROM sob_obra  ".
				" WHERE sob_obra.codemp='".$this->ls_codemp."'  ".
				$ls_criterio.
				" ORDER BY sob_obra.codobr ASC";
		$this->rs_data=$this->io_sql->select($ls_sql);
		if($this->rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_contrato ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_select_dt_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_contratosobras($as_codobr)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_contrato
		//         Access: public 
		//	    Arguments: as_codobr     // Còdigo de la Obra
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las fuentes de financimiento de una obra
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 20/05/2009									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_contratos="";
		$ls_sql="SELECT codcon,feccon".
				"  FROM sob_asignacion, sob_contrato  ".
				"  WHERE sob_asignacion.codemp='".$this->ls_codemp."'".
				"    AND sob_asignacion.codobr='".$as_codobr."'".
				"    AND sob_asignacion.codemp=sob_contrato.codemp".
				"    AND sob_asignacion.codasi=sob_contrato.codasi".
				" ORDER BY feccon ASC";
		$rs_datac=$this->io_sql->select($ls_sql);
		if($rs_datac===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_contratosobras ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_datac))
			{
 				if($ls_contratos=="")
				{
					$ls_contratos=$row["codcon"];
				}
				else
				{
					$ls_contratos=$ls_contratos." - ".$row["codcon"];
				}
			}
			$this->io_sql->free_result($rs_datac);
		}		
		return $ls_contratos;
	}// end function uf_select_dt_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_contratos_obras($as_codobr)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_contratos_obras
		//         Access: public 
		//	    Arguments: as_codobr     // Còdigo de la Obra
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las fuentes de financimiento de una obra
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 20/05/2009									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->rs_contratos="";
		$ls_sql="SELECT sob_contrato.codcon,sob_contrato.feccon,sob_contrato.monto".
				"  FROM sob_asignacion, sob_contrato  ".
				"  WHERE sob_asignacion.codemp='".$this->ls_codemp."'".
				"    AND sob_asignacion.codobr='".$as_codobr."'".
				"    AND sob_asignacion.codemp=sob_contrato.codemp".
				"    AND sob_asignacion.codasi=sob_contrato.codasi".
				" ORDER BY feccon ASC";
		$this->rs_contratos=$this->io_sql->select($ls_sql);
		if($this->rs_contratos===false)
		{print $this->io_sql->message;
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_contratosobras ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_select_dt_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_anticipos_obras($as_codobr)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_anticipos_obras
		//         Access: public 
		//	    Arguments: as_codobr     // Còdigo de la Obra
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las fuentes de financimiento de una obra
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 20/05/2009									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->rs_anticipos="";
		$ls_sql="SELECT sob_anticipo.codant,sob_anticipo.fecant,sob_anticipo.monto ".
				"  FROM sob_asignacion, sob_contrato, sob_anticipo  ".
				"  WHERE sob_asignacion.codemp='".$this->ls_codemp."'".
				"    AND sob_asignacion.codobr='".$as_codobr."'".
				"    AND sob_asignacion.codemp=sob_contrato.codemp".
				"    AND sob_asignacion.codasi=sob_contrato.codasi".
				"    AND sob_contrato.codemp=sob_anticipo.codemp".
				"    AND sob_contrato.codcon=sob_anticipo.codcon".
				"    AND sob_anticipo.estspgscg='1'".
				" ORDER BY feccon ASC";
		$this->rs_anticipos=$this->io_sql->select($ls_sql);
		if($this->rs_anticipos===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_anticipos_obras ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_select_dt_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_valuaciones_obras($as_codobr)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_valuaciones_obras
		//         Access: public 
		//	    Arguments: as_codobr     // Còdigo de la Obra
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las fuentes de financimiento de una obra
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 20/05/2009									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->rs_valuaciones="";
		$ls_sql="SELECT sob_valuacion.codval,sob_valuacion.subtot,sob_valuacion.fecha".
				"  FROM sob_asignacion, sob_contrato, sob_valuacion  ".
				"  WHERE sob_asignacion.codemp='".$this->ls_codemp."'".
				"    AND sob_asignacion.codobr='".$as_codobr."'".
				"    AND sob_asignacion.codemp=sob_contrato.codemp".
				"    AND sob_asignacion.codasi=sob_contrato.codasi".
				"    AND sob_contrato.codemp=sob_valuacion.codemp".
				"    AND sob_contrato.codcon=sob_valuacion.codcon".
				"    AND sob_valuacion.estspgscg='1'".
				" ORDER BY feccon ASC";
		$this->rs_valuaciones=$this->io_sql->select($ls_sql);
		if($this->rs_valuaciones===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_valuaciones_obras ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_select_dt_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_actacontrato($as_codact,$as_codcon,$as_tipact)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_actacontrato
		//         Access: public 
		//	    Arguments: as_codobr     // Còdigo de la Obra
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las fuentes de financimiento de una obra
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 20/05/2009									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena="CONCAT(sno_personal.nomper,' ',sno_personal.apeper)";
				break;
			case "POSTGRES":
				$ls_cadena="sno_personal.nomper||' '||sno_personal.apeper";
				break;
			case "INFORMIX":
				$ls_cadena="sno_personal.nomper||' '||sno_personal.apeper";
				break;
		}
		$lb_valido=true;
		$ls_sql="SELECT sob_obra.desobr,sob_contrato.codcon,sob_contrato.feccon,  sob_contrato.monto, sob_contrato.fecinicon, sob_contrato.fecfincon,".
				"       sob_acta.fecact, sob_acta.cedinsact,sob_acta.fecfinact,sob_acta.civinsact,sob_acta.nomresact,sob_acta.civresact,sob_acta.cedresact,	".
				"       (SELECT ".$ls_cadena." FROM sno_personal".
				"         WHERE sno_personal.codemp='".$this->ls_codemp."'".
				"           AND sno_personal.codemp=sob_acta.codemp".
				"           AND sno_personal.cedper=sob_acta.cedinsact) AS inspector,".
				"       (SELECT rpc_proveedor.nompro FROM rpc_proveedor".
				"         WHERE rpc_proveedor.codemp='".$this->ls_codemp."'".
				"           AND rpc_proveedor.codemp=sob_asignacion.codemp".
				"           AND rpc_proveedor.cod_pro=sob_asignacion.cod_pro) AS contratista,".
				"       (SELECT rpc_proveedor.nomreppro FROM rpc_proveedor".
				"         WHERE rpc_proveedor.codemp='".$this->ls_codemp."'".
				"           AND rpc_proveedor.codemp=sob_asignacion.codemp".
				"           AND rpc_proveedor.cod_pro=sob_asignacion.cod_pro) AS representante,".
				"       (SELECT rpc_proveedor.cedrep FROM rpc_proveedor".
				"         WHERE rpc_proveedor.codemp='".$this->ls_codemp."'".
				"           AND rpc_proveedor.codemp=sob_asignacion.codemp".
				"           AND rpc_proveedor.cod_pro=sob_asignacion.cod_pro) AS cedrepresentante".
				"  FROM sob_contrato,sob_acta,sob_asignacion,sob_obra  ".
				" WHERE sob_acta.codemp='".$this->ls_codemp."'  ".
				"   AND sob_acta.codact='".$as_codact."'  ".
				"   AND sob_acta.codcon='".$as_codcon."'  ".
				"   AND sob_acta.tipact='".$as_tipact."'".
				"   AND sob_contrato.codemp=sob_acta.codemp".
				"   AND sob_contrato.codcon=sob_acta.codcon".
				"   AND sob_contrato.codemp=sob_asignacion.codemp".
				"   AND sob_contrato.codasi=sob_asignacion.codasi".
				"   AND sob_asignacion.codemp=sob_obra.codemp".
				"   AND sob_asignacion.codobr=sob_obra.codobr";
		$this->rs_data=$this->io_sql->select($ls_sql);
		if($this->rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_actacontrato ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_select_actacontrato
	//-----------------------------------------------------------------------------------------------------------------------------------



}
?>