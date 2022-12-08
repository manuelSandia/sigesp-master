<?php
class sigesp_soc_c_anulacion_solicitud_cotizacion
{
  function sigesp_soc_c_anulacion_solicitud_cotizacion($as_path)
  {
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: sigesp_soc_c_anulacion_registro_cotizacion
	//		   Access: public 
	//	  Description: Constructor de la Clase
	//	   Creado Por: Ing. N�stor Falc�n.
	// Fecha Creaci�n: 09/06/2007 								Fecha �ltima Modificaci�n : 03/06/2007 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        require_once($as_path."shared/class_folder/sigesp_include.php");
		require_once($as_path."shared/class_folder/class_sql.php");
		require_once($as_path."shared/class_folder/class_funciones.php");
		require_once($as_path."shared/class_folder/sigesp_c_seguridad.php");
		require_once($as_path."shared/class_folder/class_mensajes.php");
		$io_include			= new sigesp_include();
		$io_conexion		= $io_include->uf_conectar();
		$this->io_sql       = new class_sql($io_conexion);	
		$this->io_mensajes  = new class_mensajes();		
		$this->io_funciones = new class_funciones();	
		$this->io_seguridad = new sigesp_c_seguridad();
		$this->ls_codemp    = $_SESSION["la_empresa"]["codemp"];
  }

function uf_load_solicitud_cotizacion($as_numcot,$ad_fecdes,$ad_fechas)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_load_solicitud_cotizacion
//         Access: public
//      Argumento: 
//   $as_numordcom //N�mero de la Orden de Compra (Bien o Servicio.)
//      $ad_fecdes //Fecha desde el cual buscaremos las Ordenes de Compra.
//      $ad_fechas //Fecha hasta el cual buscaremos las Ordenes de Compra.
//      $as_codpro //C�digo del Proveedor asociado a la Orden de Compra.
//	      Returns: Retorna un resulset
//    Description: Funcion que carga la Ordenes de Compra dispuestas para el proceso de Anulaci�n. 
//	   Creado Por: Ing. N�stor Falc�n.
// Fecha Creaci�n: 06/03/2007							Fecha �ltima Modificaci�n : 09/06/2007
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 
  $ls_straux = "";
  if (!empty($ad_fecdes) && !empty($ad_fechas))
     {
	   $ld_fecdes = $this->io_funciones->uf_convertirdatetobd($ad_fecdes);
       $ld_fechas = $this->io_funciones->uf_convertirdatetobd($ad_fechas);
	   $ls_straux = " AND soc_cotizacion.feccot BETWEEN '".$ld_fecdes."' AND '".$ld_fechas."'"; 
	 }
  $ls_sql = "SELECT soc_sol_cotizacion.numsolcot,soc_sol_cotizacion.fecsol,soc_sol_cotizacion.obssol,soc_sol_cotizacion.tipsolcot,soc_cotizacion.montotcot  ".			
		    "  FROM soc_cotizacion , soc_sol_cotizacion".
			" WHERE soc_sol_cotizacion.codemp='".$this->ls_codemp."'".
			"   AND soc_sol_cotizacion.numsolcot like '%".$as_numcot."%'".
			"   AND soc_cotizacion.estcot= '2'".
			"   AND soc_sol_cotizacion.estcot= '0'".
			"   AND soc_cotizacion.codemp=soc_sol_cotizacion.codemp".
			"   AND soc_cotizacion.numsolcot=soc_sol_cotizacion.numsolcot".
			" GROUP BY soc_sol_cotizacion.numsolcot,soc_sol_cotizacion.fecsol,soc_sol_cotizacion.obssol,soc_sol_cotizacion.tipsolcot,soc_cotizacion.montotcot ".
			" ORDER BY soc_sol_cotizacion.numsolcot";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
		$lb_valido=false;
		$this->io_mensajes	->message("CLASE->sigesp_soc_c_anulacion_orden_compra.M�TODO->uf_load_solicitud_cotizacion.ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
	}
	return $rs_data;
} // end  function uf_load_ordenes_compra

function uf_update_estatus_solicitud_cotizacion($ai_totrows,$aa_seguridad)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_update_estatus_solicitud_cotizacion
//         Access: public
//      Argumento: $ai_totrows = Total de filas dispuestas para su anulaci�n.
//                 $aa_seguridad = Arreglo cargado con la informacion de la pantalla, usuario, entre otros.
//     $as_totrows //Total de Ordenes de Compra.
//	      Returns: Retorna un resulset
//    Description: Funcion que carga la Ordenes de Compra dispuestas para el proceso de Anulaci�n. 
//	   Creado Por: Ing. N�stor Falc�n.
// Fecha Creaci�n: 06/03/2007							Fecha �ltima Modificaci�n : 09/06/2007
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_valido = true;
  if ($ai_totrows>0)
     {
	   $this->io_sql->begin_transaction();
	   for ($i=1;$i<=$ai_totrows;$i++)
	       {
			 if (array_key_exists("chk".$i,$_POST))
			    {
			      $ls_numsolcot = $_POST["txtnumord".$i];
			      $ls_tipsol = $_POST["txttipordcom".$i];
				  if($ls_tipsol=="Bienes")
				  {
				  	$ls_tipsol="B";
				  }
				  else
				  {
				  	$ls_tipsol="S";
				  }
			      $ls_sql="UPDATE soc_sol_cotizacion".
			              "   SET estcot='A'".
			              " WHERE codemp='".$this->ls_codemp."'".
						  "   AND numsolcot='".$ls_numsolcot."'";
				  $rs_data = $this->io_sql->execute($ls_sql);
				  if ($rs_data===false)
					 {
					   $lb_valido = false;
					   $this->io_mensajes->message("CLASE->uf_update_estatus_analisis_cotizacion.php->M�TODO->uf_update_estatus_solicitud_cotizacion.ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					   break;
					 }
				  else
					 {
						/////////////////////////////////         SEGURIDAD               /////////////////////////////////////////		
						$ls_descripcion ="Anul� el Analisis de Cotizacion Nro. $ls_numanacot asociada a la empresa ".$this->ls_codemp;
						$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],"UPDATE",$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               //////////////////////////////////////////				   
					   
					   $lb_valido = $this->uf_liberar_sep($ls_numsolcot,$ls_tipsol,$aa_seguridad);
					 }
				}
		   }
	    if ($lb_valido)
	       {
		     $this->io_sql->commit();
			 $this->io_mensajes->message("Operaci�n realizada con �xito !!!");
		     $this->io_sql->close();

		   } 
	    else
		   {
		     $this->io_sql->rollback();
			 $this->io_mensajes->message("Error  en Operaci�n !!!");
		     $this->io_sql->close();
		   }
	 }
  return $lb_valido;
}

function uf_liberar_sep($as_numsolcot,$as_tipsol,$aa_seguridad)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_liberar_sep
	//         Access: public
	//      Argumento: 
	//   $as_numordcom //N�mero de la Orden de Compra (Bien o Servicio.)
	//      $as_codpro //C�digo del Proveedor asociado a la Orden de Compra.
	//   $as_tipordcom //Tipo de Orden de Compra (Bien o Servicio).
	//	      Returns: Retorna un resulset
	//    Description: Funcion que carga la Ordenes de Compra dispuestas para el proceso de Anulaci�n. 
	//	   Creado Por: Ing. N�stor Falc�n.
	// Fecha Creaci�n: 06/06/2007							Fecha �ltima Modificaci�n : 09/06/2007
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido= true;
	$ls_sql= "SELECT numsol ".
			 "  FROM soc_solcotsep ".
			 " WHERE codemp='".$this->ls_codemp."'".
			 "   AND numsolcot='".$as_numsolcot."'";
	$rs_datos = $this->io_sql->select($ls_sql);
	if ($rs_datos===false)
	{
		$lb_valido = false;
		$this->io_mensajes->message("CLASE->sigesp_soc_c_anulacion_orden_compra.php->M�TODO->uf_liberar_solicitud_cotizacion.ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		echo $this->io_sql->message;
	}		    
	else
	{
		while ($row=$this->io_sql->fetch_row($rs_datos))
		{
			$ls_numsol = $row["numsol"];
			$lb_valido = $this->uf_update_estatus_solicitud($ls_numsol,$as_tipsol,$as_numsolcot,$aa_seguridad);
		}
	}
  return $lb_valido;
}

function uf_update_estatus_solicitud($ls_numsol,$as_tipsol,$as_numsolcot,$aa_seguridad)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_update_estatus_solicitud
	//         Access: public
	//      Argumento: 
	//   $as_numordcom //N�mero de la Orden de Compra (Bien o Servicio.)
	//      $as_codpro //C�digo del Proveedor asociado a la Orden de Compra.
	//   $as_tipordcom //Tipo de Orden de Compra (Bien o Servicio).
	//	      Returns: Retorna un resulset
	//    Description: Funcion que carga la Ordenes de Compra dispuestas para el proceso de Anulaci�n. 
	//	   Creado Por: Ing. N�stor Falc�n.
	// Fecha Creaci�n: 06/06/2007							Fecha �ltima Modificaci�n : 09/06/2007
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido= true;
	if ($as_tipsol=='B')
	{
		$ls_tabla = "sep_dt_articulos";
		$ls_campo = "codart";
	}
	elseif($as_tipsol=='S')
	{
		$ls_tabla  = "sep_dt_servicio";
		$ls_campo  = "codser";
	}
	$ls_sql= "SELECT $ls_campo ".
			 "  FROM $ls_tabla ".
			 " WHERE codemp='".$this->ls_codemp."'".
			 "   AND numsol='".$ls_numsol."'";
	$rs_datos = $this->io_sql->select($ls_sql);
	if ($rs_datos===false)
	{
		$lb_valido = false;
		$this->io_mensajes->message("CLASE->sigesp_soc_c_anulacion_orden_compra.php->M�TODO->uf_update_estatus_solicitud.ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		echo $this->io_sql->message;
	}		    
	else
	{
		while ($row=$this->io_sql->fetch_row($rs_datos))
		{
			$ls_codigo = $row["$ls_campo"];
			$lb_valido = $this->uf_update_estatus_incorporacion($as_tipsol,$ls_numsol,$ls_codigo);
		}
	}
	if($lb_valido)
	{
		$lb_valido=$this->uf_update_estatus_sep($ls_numsol,$ls_tabla);
	}
	if($lb_valido)
	{
		/////////////////////////////////         SEGURIDAD               /////////////////////////////
		$ls_evento="UPDATE";
		$ls_descripcion ="Actualizo el estatus de la SEP ".$ls_numsol." retornandola a Contabilizada. Asociado a la empresa ".$this->ls_codemp;
		$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
				$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////
	}
  return $lb_valido;
}
    function uf_update_estatus_incorporacion($as_tipsolcot,$as_numsep,$as_codigo)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_update_estatus_incorporacion
	//		   Access: public
	//		 Argument: $as_numsolcot       //Numero de la Solicitud de Cotizaci�n.
	//                 $as_numsep          //N�mero de la Solicituds de Ejecuci�n Presupuestaria. 
	//                 $as_tipsolcot       //Tipo de Solicitud de Cotizaci�n B= Bienes, S= Servicios.  
	//                 $as_codigo          //
	//                 $as_desope	       //Si la operacion a realizar es un Insert o un Delete.
	//	  Description: Funci�n actualiza el estatus de incorporacion del item en las Tabla de Detalles de la SEP.
	//	   Creado Por: Ing. Nestor Falcon.
	// Fecha Creaci�n: 20/05/2007								Fecha �ltima Modificaci�n : 20/05/2007
	//////////////////////////////////////////////////////////////////////////////
	  
		$lb_valido = true;
		if ($as_tipsolcot=='B')
		   {
			 $ls_tabla = "sep_dt_articulos";
			 $ls_campo = "codart";
		   }
		elseif($as_tipsolcot=='S')
		   {
			 $ls_tabla  = "sep_dt_servicio";
			 $ls_campo  = "codser";
		   }
			$ls_sql = "UPDATE $ls_tabla SET estincite='NI', ".
			          "       numdocdes='' ".
					  " WHERE codemp='".$this->ls_codemp."' ".
					  "   AND numsol='".$as_numsep."'       ".
					  "   AND $ls_campo='".$as_codigo."'    ";

		$rs_recordset = $this->io_sql->execute($ls_sql);//print $ls_sql.'<br>';
		if ($rs_recordset===false)
		   {
			 $lb_valido = false;
			 $this->io_mensajes->message("CLASE->sigesp_soc_c_solicitud_cotizacion.php->M�TODO->uf_update_estatus_incorporacion->ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			 $this->io_sql->rollback();
		   }
		return $lb_valido;
	}// end function uf_update_estatus_incorporacion 


    function uf_update_estatus_sep($as_numsep,$as_tabla)
    {
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_update_estatus_sep
	//		   Access: public
	//		 Argument: 
	//      $as_desope //Descripcion de la Operacion (INSERT,UPDATE,DELETE). 
	//      $as_numsep //N�mero de la Solicitud de Ejecuci�n Presupuestaria.
	//       $as_tabla //Tabla donde verificaremos el estatus de los items incluidos en una Solicitud de Cotizacion,
	//                   si es de Tipo Bienes Tabla=sep_dt_articulos, Tipo Servicios=sep_d_servicios.  
	//	  Description: Funci�n actualiza el estatus de la SEP a procesada en caso de que ningun Item se encuentre como 
	//                 NI = NO INCORPORADO.
	//	   Creado Por: Ing. Nestor Falcon.
	// Fecha Creaci�n: 26/07/2007								Fecha �ltima Modificaci�n : 20/07/2007
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = true;
		$ls_sql = "SELECT sep_solicitud.numsol 
					FROM sep_solicitud, $as_tabla 
					WHERE sep_solicitud.codemp='".$this->ls_codemp."' 
					AND sep_solicitud.numsol='".$as_numsep."'
					AND $as_tabla.estincite<>'NI'
					AND sep_solicitud.codemp=$as_tabla.codemp
					AND sep_solicitud.numsol=$as_tabla.numsol";
		$rs_data = $this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido = false;
			$this->io_mensajes->message("CLASE->sigesp_soc_c_solicitud_cotizacion.php->M�TODO->uf_update_estatus_sep->ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			$li_numrows = $this->io_sql->num_rows($rs_data);
			if ($li_numrows<=0)
			{
				$ls_sql = "UPDATE sep_solicitud 
							SET estsol='C'
							WHERE codemp='".$this->ls_codemp."'
							AND numsol='".$as_numsep."'";
				$rs_data = $this->io_sql->execute($ls_sql);
				if ($rs_data===false)
				{
					$lb_valido = false;
					$this->io_mensajes->message("CLASE->sigesp_soc_c_solicitud_cotizacion.php->M�TODO->uf_update_estatus_sep->ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}  
			}
		}
		return $lb_valido;
    }//Fin de la Funcion uf_update_estatus_sep.

//-----------------------------------------------------------------------------------------------------------------------------------	
function uf_nivel_aprobacion_usu($as_codusu,$as_codtipniv)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_estatus_solicitud
		//		   Access: private
		//	    Arguments: as_numsol  //  N�mero de Solicitud
		//				   as_estsol  //  Estatus de la Solicitud
		// 	      Returns: lb_existe True si existe � False si no existe
		//	  Description: Funcion que valida el estatus de aprobacion de la solicitud 
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creaci�n: 26/02/2007 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$as_codniv="";
		$ls_sql="SELECT codasiniv ".
				"  FROM sss_niv_usuarios ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codusu='".$as_codusu."' ".
				"   AND codtipniv='".$as_codtipniv."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->sigesp_soc_c_aprobacion_analisis_cotizacion.php->uf_nivel_aprobacion_usu ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_codniv=$row["codasiniv"];
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $as_codniv;
	}// end function uf_validar_estatus_solicitud
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_nivel_aprobacion_montohasta($as_codniv)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_estatus_solicitud
		//		   Access: private
		//	    Arguments: as_numsol  //  N�mero de Solicitud
		//				   as_estsol  //  Estatus de la Solicitud
		// 	      Returns: lb_existe True si existe � False si no existe
		//	  Description: Funcion que valida el estatus de aprobacion de la solicitud 
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creaci�n: 26/02/2007 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ai_monhas=0;
		$ls_sql="SELECT monnivhas ".
				"  FROM sigesp_nivel ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codniv='".$as_codniv."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->sigesp_soc_c_aprobacion_analisis_cotizacion.php-> ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_monhas=$row["monnivhas"];
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $ai_monhas;
	}// end function uf_validar_estatus_solicitud
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_nivel($as_codniv)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_estatus_solicitud
		//		   Access: private
		//	    Arguments: as_numsol  //  N�mero de Solicitud
		//				   as_estsol  //  Estatus de la Solicitud
		// 	      Returns: lb_existe True si existe � False si no existe
		//	  Description: Funcion que valida el estatus de aprobacion de la solicitud 
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creaci�n: 26/02/2007 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$as_nivel="";
		$ls_sql="SELECT codniv ".
				"  FROM sigesp_asig_nivel ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codasiniv='".$as_codniv."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->sigesp_soc_c_aprobacion_analisis_cotizacion.php-> ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_nivel=$row["codniv"];
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $as_nivel;
	}// end function uf_validar_estatus_solicitud
//-----------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_load_monto_cotizacion_nivel($as_numanacot,$as_tipanacot)
{
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_analisis_cotizacion
		//		   Access: public
		//		 Argument: 
		//   $as_numanacot //N�mero del An�lisis de Cotizacion
		//      $ad_fecdes //Fecha a partir del cual comenzar� la b�squeda de los An�lisis de Cotizacion
		//      $ad_fechas //Fecha hasta el cual comenzar� la b�squeda de los An�lisis de Cotizacion
		//   $as_tipanacot//Tipo del Analisis de Cotizacion B=Bienes , S=Servicios.
		//      $as_tipope //Tipo de la Operaci�n a ejecutar A=Aprobacion, R=Reverso de la Aprobaci�n.
		//	  Description: Funci�n que busca los Analisis de Cotizacion que esten dispuestas para Aprobacion/Reverso.
		//	   Creado Por: Ing. Laura Cabre
		// Fecha Creaci�n: 05/08/2007								Fecha �ltima Modificaci�n : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = true;
        $ai_montocot = 0;
		
		if ($as_tipanacot=='Bienes')//Aprobacion
		   {  
				 $ls_sql ="SELECT SUM(montotart) as monto
							FROM soc_dtcot_bienes, soc_dtac_bienes
							WHERE soc_dtcot_bienes.codemp=soc_dtac_bienes.codemp
							AND soc_dtac_bienes.numanacot='".$as_numanacot."'
							AND soc_dtcot_bienes.codart=soc_dtac_bienes.codart
							AND soc_dtcot_bienes.numcot=soc_dtac_bienes.numcot
							AND soc_dtcot_bienes.cod_pro=soc_dtac_bienes.cod_pro";
		   }
		elseif($as_tipanacot=='Servicios')//Reverso.
		   {
				$ls_sql ="SELECT SUM(montotser) as monto
							FROM soc_dtcot_servicio, soc_dtac_servicios
							WHERE soc_dtcot_servicio.codemp=soc_dtac_servicios.codemp
							AND soc_dtac_servicios.numanacot='".$as_numanacot."'
							AND soc_dtcot_servicio.codser=soc_dtac_servicios.codser
							AND soc_dtcot_servicio.numcot=soc_dtac_servicios.numcot
							AND soc_dtcot_servicio.cod_pro=soc_dtac_servicios.cod_pro";		  
		   }
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->sigesp_soc_c_aprobacion_analisis_cotizacion.php->M�TODO->uf_load_monto_cotizacion_nivel.ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_montocot=$row["monto"];
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $ai_montocot;

	}// end function uf_load_ordenes_compra
//-----------------------------------------------------------------------------------------------------------------------------------	



}
?>
