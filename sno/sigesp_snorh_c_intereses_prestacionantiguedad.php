<?php
class sigesp_snorh_c_intereses_prestacionantiguedad
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_intereses_prestacionantiguedad()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_intereses_prestacionantiguedad
		//		   Access: public (sigesp_snorh_d_sueldominimo)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 19/10/2010 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad=new sigesp_c_seguridad();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_snorh_c_intereses_prestacionantiguedad
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_d_intereses_prestacionantiguedad)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 19/10/2010 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
		unset($this->io_personal);
        unset($this->ls_codemp);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_intereses($as_mesint,$as_anoint)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_intereses
		//		   Access: private
 		//	    Arguments: as_mesint  // c�digo del mes
 		//	    		   as_anoint  // c�digo del anio
		//	      Returns: lb_existe True si existe � False si no existe
		//	  Description: Funcion que verifica si el interes est� registrado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 19/10/2010 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT mesint ".
			    "  FROM sno_fideiintereses ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND mesint=".$as_mesint." ".
				"   AND anoint='".$as_anoint."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Intereses Prestacion M�TODO->uf_select_intereses ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if($rs_data->EOF)
			{
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	}// end function uf_select_intereses
	//-----------------------------------------------------------------------------------------------------------------------------------
   
	//-----------------------------------------------------------------------------------------------------------------------------------		
	function uf_insert_intereses($as_mesint,$as_anoint,$as_nrogacint,$ad_fecviggacint,$ai_montasint,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_intereses
		//		   Access: private
 		//	    Arguments: as_mesint  // Mes del interes Prestaci�n
 		//	    		   as_anoint  // A�o del Intereses Prestacion
 		//	    		   as_nrogacint  // N�mero de Gaceta 
 		//	    		   ad_fecviggacint  // Fecha de la gaceta
 		//	    		   ai_montasint  //  Monto de tasa
 		//	    		   aa_seguridad  // arreglo de seguridad
		//	      Returns: lb_existe True si existe � False si no existe
		//	  Description: Funcion que inserta en la tabla sno_fideiintereses
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 19/10/2010 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_fideiintereses(codemp, mesint, anoint, nrogacint, fecviggacint, montasint)". 
			    "     VALUES ('".$this->ls_codemp."',".$as_mesint.",".$as_anoint.",'".$as_nrogacint."','".$ad_fecviggacint."',".
				"			  ".$ai_montasint.")";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Intereses Prestacion M�TODO->uf_insert_intereses ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="INSERT";
			$ls_descripcion ="Insert� el Interes de Prestaci�n Antiguedad ".$as_mesint." - ".$as_anoint;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Intereses Prestacion Antiguedad fue Registrado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Intereses Prestacion M�TODO->uf_insert_intereses ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_intereses
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------		
	function uf_update_intereses($as_mesint,$as_anoint,$as_nrogacint,$ad_fecviggacint,$ai_montasint,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_intereses
		//		   Access: private
 		//	    Arguments: as_mesint  // Mes del interes Prestaci�n
 		//	    		   as_anoint  // A�o del Intereses Prestacion
 		//	    		   as_nrogacint  // N�mero de Gaceta 
 		//	    		   ad_fecviggacint  // Fecha de la gaceta
 		//	    		   ai_montasint  //  Monto de tasa
 		//	    		   aa_seguridad  // arreglo de seguridad
		//	      Returns: lb_existe True si existe � False si no existe
		//	  Description: Funcion que inserta en la tabla sno_fideiintereses
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 19/10/2010 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		$ls_sql="UPDATE sno_fideiintereses ".
			    "   SET nrogacint = '".$as_nrogacint."',".
				"       fecviggacint = '".$ad_fecviggacint."', ".
				"       montasint = ".$ai_montasint." ".
				" WHERE codemp = '".$this->ls_codemp."' ".
				"   AND mesint = ".$as_mesint." ".
				"   AND anoint = '".$as_anoint."' ";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Intereses Prestacion M�TODO->uf_update_intereses ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualiz� el Interes de Prestaci�n Antiguedad ".$as_mesint." - ".$as_anoint;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Intereses Prestacion Antigedad fue Actualizado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Intereses Prestacion M�TODO->uf_update_intereses ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_intereses
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_mesint,$ai_anoint,$as_nrogacint,$ad_fecviggacint,$ai_montasint,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_snorh_d_intereses_prestacionantiguedad)
 		//	    Arguments: as_mesint  // Mes del interes Prestaci�n
 		//	    		   as_anoint  // A�o del Intereses Prestacion
 		//	    		   as_nrogacint  // N�mero de Gaceta 
 		//	    		   ad_fecviggacint  // Fecha de la gaceta
 		//	    		   ai_montasint  //  Monto de tasa
 		//	    		   aa_seguridad  // arreglo de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar � False si hubo error en el guardar
		//	  Description: Funcion que guarda en la tabla sno_fideiintereses
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 19/10/2010 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		$ai_montasint=str_replace(".","",$ai_montasint);
		$ai_montasint=str_replace(",",".",$ai_montasint);	
		$ad_fecviggacint=$this->io_funciones->uf_convertirdatetobd($ad_fecviggacint);			
		switch ($as_existe)
		{
			case "FALSE":
				if($this->uf_select_intereses($as_mesint,$ai_anoint)===false)
				{
					$lb_valido=$this->uf_insert_intereses($as_mesint,$ai_anoint,$as_nrogacint,$ad_fecviggacint,$ai_montasint,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El Intereses Prestacion Antiguedad ya existe, no la puede incluir.");
				}
				break;

			case "TRUE":
				if(($this->uf_select_intereses($as_mesint,$ai_anoint)))
				{
					if ($this->uf_integridad($as_mesint,$ai_anoint)===false)
					{
						$lb_valido=$this->uf_update_intereses($as_mesint,$ai_anoint,$as_nrogacint,$ad_fecviggacint,$ai_montasint,$aa_seguridad);
					}
					else
					{
						$this->io_mensajes->message("No se puede cambiar el Interes. Ya se realizaron calculos para este mes y a�o.");
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("El Intereses Prestacion Antiguedad no existe, no la puede actualizar.");
				}
				break;
		}
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_integridad($as_mesint,$as_anoint)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_integridad
		//		   Access: private
		//	    Arguments: as_mesint  //  Mes
		//				   as_anoint  // A�o
		//	      Returns: lb_existe True si existe � False si no existe
		//	  Description: Funcion que valida que el interes no haya sido calculado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 21/10/2010 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 		$lb_existe=true;
       	$ls_sql="SELECT codper ".
				"  FROM sno_fideiperiodointereses ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND mescurper=".$as_mesint." ".
				"   AND anocurper='".$as_anoint."'";
       	$rs_data=$this->io_sql->select($ls_sql);
       	if ($rs_data===false)
       	{
			$this->io_mensajes->message("CLASE->Intereses Prestacion M�TODO->uf_integridad ERROR->".$this->io_funciones->uf_convertirmsg($this->SQL->message)); 
       	}
       	else
       	{
			if($rs_data->EOF)
			{
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_data);	
       	}
		return $lb_existe ;    
	}// end function uf_integridad
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete($as_mesint,$as_anoint,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete
		//		   Access: public (sigesp_snorh_d_intereses_prestacionantiguedad)
 		//	    Arguments: as_mesint  // Mes del interes Prestaci�n
 		//	    		   as_anoint  // A�o del Intereses Prestacion
 		//	    		   aa_seguridad  // arreglo de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar � False si hubo error en el guardar
		//	  Description: Funcion que elimina en la tabla sno_fideiintereses
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 19/10/2010 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        if ($this->uf_integridad($as_mesint,$as_anoint)===false)
		{
			$ls_sql="DELETE ".
					"  FROM sno_fideiintereses ".
					" WHERE codemp = '".$this->ls_codemp."' ".
					"   AND mesint = ".$as_mesint." ".
					"   AND anoint = '".$as_anoint."' ";
			$this->io_sql->begin_transaction();
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Intereses Prestacion M�TODO->uf_delete ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Elimin� el Interes de Prestaci�n Antiguedad ".$as_mesint." - ".$as_anoint;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				if($lb_valido)
				{	
					$this->io_mensajes->message("El Intereses Prestacion Antiguedad fue Eliminado.");
					$this->io_sql->commit();
				}
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Intereses Prestacion M�TODO->uf_delete ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					$this->io_sql->rollback();
				}
			}
		}
		else
		{
			$this->io_mensajes->message("No se puede eliminar el Interes. Ya se realizaron calculos para este mes y a�o.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_delete
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>