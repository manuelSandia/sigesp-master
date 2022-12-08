<?php
class sigesp_cfg_c_nivel_aprobacion
{
var $ls_sql;
var $arremp;
var $is_msg_error;

	
		function sigesp_cfg_c_nivel_aprobacion($conn)
		{
		  require_once("../shared/class_folder/sigesp_c_seguridad.php");
		  require_once("../shared/class_folder/class_mensajes.php");		
		  require_once("../shared/class_folder/class_funciones.php");
	      
		  $this->seguridad = new sigesp_c_seguridad();         
		  $this->io_funcion = new class_funciones();
		  $this->arremp=$_SESSION["la_empresa"];
		  $this->io_sql= new class_sql($conn);		
		  $this->io_msg = new class_mensajes();
		  $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		}
 

//-----------------------------------------------------------------------------------------------------------------------------------
function uf_guardar_niveles_aprobacion($as_codniv,$ai_monnivdes,$ai_monnivhas,$as_existe,$aa_seguridad)
{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar_niveles_aprobacion
		//		   Access: public (sigesp_nivel)
		//	    Arguments: as_codniv  // código de la tabla de vacacion
		//				   ai_monnivdes  // lapso
		//				   ai_monnivhas  // dias de disfrute
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que almacena el perído relacionado con la tabla de vacación
		//	   Creado Por: Ing. Carlos Zambrano
		// Fecha Creación: 01/04/2011 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if(($this->uf_select_nivel_aprobacion($as_codniv)===false)&&(!$as_existe))
		{
			$as_existe="FALSE";
			$lb_valido=$this->uf_insert_nivel_aprobacion($as_codniv,$ai_monnivdes,$ai_monnivhas,$as_existe,$aa_seguridad);
		}
		elseif($as_existe)
		{
			$as_existe="TRUE";
			$lb_valido=$this->uf_insert_nivel_aprobacion($as_codniv,$ai_monnivdes,$ai_monnivhas,$as_existe,$aa_seguridad);
		}
		elseif($this->uf_select_nivel_aprobacion($as_codniv)===true)
		{
			$this->io_msg->message("Ya existe un codigo de nivel, por favor cargue los existentes");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_guardar_niveles_aprobacion	
//-----------------------------------------------------------------------------------------------------------------------------------
	
//--------------------------------------------------------------
function uf_obtenervalor($as_valor, $as_valordefecto)
{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtenervalor
		//		   Access: public
		//	    Arguments: as_valor  // Variable que deseamos obtener
		//				   as_valordefecto  // Valor por defecto de la variable
		//	      Returns: valor contenido de la variable
		//	  Description: Función que obtiene el valor de una variable que viene de un submit
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		if(array_key_exists($as_valor,$_POST))
		{
			$valor=$_POST[$as_valor];
		}
		else
		{
			$valor=$as_valordefecto;
		}
   		return $valor; 
   }// end function uf_obtenervalor
//--------------------------------------------------------------

function uf_select_nivel_aprobacion($as_codniv)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_nivel_aprobacion
		//		   Access: private
		//	    Arguments: as_codniv  // código de nivel
		//				   
		//	      Returns: lb_valido True si existe ó False si no existe
		//	  Description: Funcion que verifica si el periodo de la tabla de vacacion está registrado
		//	   Creado Por: Ing. Carlos Zambrano
		// Fecha Creación: 01/04/2011 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codniv FROM sigesp_nivel ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codniv='".$as_codniv."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			 $this->io_mensajes->message("CLASE->Tabla Niveles MÉTODO->uf_select_nivel_aprobacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
			 $lb_valido=false;
		}
		else
		{
			if(!$row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_select_nivel_aprobacion
//-----------------------------------------------------------------------------------------------------------------------------------	

function uf_select_nivel_aprobacion_asignado($as_codniv)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_nivel_aprobacion
		//		   Access: private
		//	    Arguments: as_codniv  // código de nivel
		//				   
		//	      Returns: lb_valido True si existe ó False si no existe
		//	  Description: Funcion que verifica si el periodo de la tabla de vacacion está registrado
		//	   Creado Por: Ing. Carlos Zambrano
		// Fecha Creación: 01/04/2011 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codniv FROM sigesp_asig_nivel ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codniv='".$as_codniv."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			 $this->io_msg->message("CLASE->Tabla Niveles MÉTODO->uf_select_nivel_aprobacion_asignado ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
			 $lb_valido=false;
		}
		else
		{
			if(!$row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_select_nivel_aprobacion
//-----------------------------------------------------------------------------------------------------------------------------------	
		
function uf_insert_nivel_aprobacion($as_codniv,$ai_monnivdes,$ai_monnivhas,$as_existe,$aa_seguridad)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_nivel_aprobacion
		//		   Access: private
		//	    Arguments: as_codniv  // código de la tabla de vacacion
		//				   ai_monnivdes  // lapso
		//				   ai_monnivhas  // dias de disfrute
		//				   as_existe  // dias de bono
		//				   aa_seguridad  // días adicionales de disfrute
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla de vacación período
		//	   Creado Por: Ing. Carlos Zambrano
		// Fecha Creación: 01/04/2011 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_monnivdes=str_replace(".","",$ai_monnivdes);
		$ai_monnivdes=str_replace(",",".",$ai_monnivdes);
		$ai_monnivhas=str_replace(".","",$ai_monnivhas);
		$ai_monnivhas=str_replace(",",".",$ai_monnivhas);
		$ls_sql="INSERT INTO sigesp_nivel ".
				"(codemp,codniv,monnivdes,monnivhas)VALUES".
				"('".$this->ls_codemp."','".$as_codniv."',".$ai_monnivdes.",".$ai_monnivhas.")";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_msg->message("CLASE->Tabla Niveles MÉTODO->uf_insert_nivel_aprobacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////					
			$ls_evento="INSERT";
			$ls_descripcion="Insertó el nivel ".$as_codniv." asociado a la tabla de niveles ";
			$lb_valido= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		return $lb_valido;
	}// end function uf_insert_nivel_aprobacion	
//-----------------------------------------------------------------------------------------------------------------------------------

function uf_elimina_nivel_aprobacion($aa_seguridad)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_elimina_nivel_aprobacion
		//		   Access: private
		//	    Arguments: as_codniv  // código de la tabla de vacacion
		//				   ai_monnivdes  // lapso
		//				   ai_monnivhas  // dias de disfrute
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla de vacación período
		//	   Creado Por: Ing. Carlos Zambrano
		// Fecha Creación: 01/04/2011 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM sigesp_nivel ";

		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->Tabla Niveles MÉTODO->uf_elimina_nivel_aprobacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
		} 
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////					
			$ls_evento="UPDATE";
			$ls_descripcion="Eliminó los niveles ";
			$lb_valido= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}		
		return $lb_valido;
	}// end function uf_update_nivel_aprobacion	
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_cargar_niveles_aprobacion(&$ai_totrows,&$ao_object)
{		
		////////////////////////////////////////////////////////////////////////////////
		//	Function:	uf_cargar_niveles_aprobacion
		//  Access:		public
		//	Returns:	Boolean Retorna la data si proceso correctamente
		//	Description:	Funcion que se encarga de obtener los cheques emitidos por cancelaciones a 
		//					proveedores o beneficiarios que esten en status de no contabilizada y los retorna en un array de objects
		//					los cuales seran enviados a la clase grid_param para su muestra en pantalla. Esta es una versión del metodo
		//					anterior pero con algunos filtros de búsqueda.
		//	         Autor: Ing. Laura Cabré
		//           Fecha: 17 de Ocubre del 2006
		//  Modificado Por: Ing. Néstor Falcón.     Fecha Última Modificación: 19/07/2007.
		////////////////////////////////////////////////////////////////////////////////
	
	    $lb_valido = true;
		$ls_sql = "SELECT codemp,codniv,monnivdes,monnivhas
				     FROM sigesp_nivel 
				    WHERE codemp='".$this->ls_codemp."'";
					
		$rs_data = $this->io_sql->select($ls_sql);
		if ($rs_data===false)
		   {
			 $lb_valido=false;
			 $this->io_msg->message("CLASE->Tabla Niveles MÉTODO->uf_cargar_niveles_aprobacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
		   }
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_codniv    = $row["codniv"];
				$li_monnivdes = $row["monnivdes"];
				$li_monnivhas = $row["monnivhas"];

				$ao_object[$ai_totrows][1]="<input name=txtnivapro".$ai_totrows." type=text id=txtnivapro".$ai_totrows." class=sin-borde size=6 value='".$ls_codniv."' onKeyUp='javascript: ue_validarnumero(this);' onBlur='javascript: ue_rellenarcampo(this,4);' readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtmonnivdes".$ai_totrows." type=text id=txtmonnivdes".$ai_totrows." class=sin-borde size=12 value='".number_format($li_monnivdes,2,",",".")."' onKeyPress=return(ue_formatonumero(this,'.',',',event));>";
				$ao_object[$ai_totrows][3]="<input name=txtmonnivhas".$ai_totrows." type=text id=txtmonnivhas".$ai_totrows." class=sin-borde size=12 value='".number_format($li_monnivhas,2,",",".")."' onKeyPress=return(ue_formatonumero(this,'.',',',event));>";
				$ao_object[$ai_totrows][4]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";
				$ao_object[$ai_totrows][5]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/deshacer.gif alt=Aceptar width=15 height=15 border=0></a>";
			}
			$ai_totrows=$ai_totrows+1;
			$ao_object[$ai_totrows][1]="<input name=txtnivapro".$ai_totrows." type=text id=txtnivapro".$ai_totrows." class=sin-borde size=6 maxlength=4 onKeyUp='javascript: ue_validarnumero(this);' onBlur='javascript: ue_rellenarcampo(this,4);' readonly>";
			$ao_object[$ai_totrows][2]="<input name=txtmonnivdes".$ai_totrows." type=text id=txtmonnivdes".$ai_totrows." class=sin-borde size=12 maxlength=12 onKeyPress=return(ue_formatonumero(this,'.',',',event));>";
			$ao_object[$ai_totrows][3]="<input name=txtmonnivhas".$ai_totrows." type=text id=txtmonnivhas".$ai_totrows." class=sin-borde size=12 maxlength=12 onKeyPress=return(ue_formatonumero(this,'.',',',event));>";
			$ao_object[$ai_totrows][4]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";
			$ao_object[$ai_totrows][5]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/deshacer.gif alt=Aceptar width=15 height=15 border=0></a>";
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}//Fin uf_cargar_solicitudes
//-----------------------------------------------------------------------------------------------------------------------------------


function uf_check_relaciones($as_codemp,$as_codpai)
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_check_relaciones
//	          Access:  public
// 	        Arguments 
//        $as_codemp:  Código de la Empresa.  
//    $as_codtipoorg:  Código del Tipo Empresa.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de verificar si existen tablas relacionadas al Código del Tipo Empresa. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:22/03/2006.	 
//////////////////////////////////////////////////////////////////////////////

	$ls_sql="SELECT * FROM rpc_proveedor WHERE codemp='".$as_codemp."' AND codpai='".$as_codpai."'";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	  {
		$lb_valido=false;
	    $this->io_msg->message("CLASE->sigesp_cfg_c_nivel_aprobacion; METODO->uf_check_relaciones; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	  }
	else
	  {
		if ($row=$this->io_sql->fetch_row($rs_data))
		   {
		     $lb_valido=true;
		 	 $this->is_msg_error="El Pais no puede ser eliminado, posee registros asociados a otras tablas !!!";
		   }
		else
		   {
		     $lb_valido=false;
			 $this->is_msg_error="Registro no encontrado !!!";
	 	  }
	}
return $lb_valido;	
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function uf_guardar_asignaciones_nivel($ar_datos,$as_aperapr,$aa_seguridad)
{  	   
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Function:  uf_guardar_procedencia
//	Access:  public
//	Arguments: $ar_datos,$aa_seguridad
//   ar_datos=  Arreglo Cargado con la información proveniente de la Interfaz de Procedencias
//	Returns:	$lb_valido= Variable que devuelve true si la operación 
//                          fue exitosa de lo contrario devuelve false 
//	Description:Este método se encarga de realizar la inserción del registro si este existe con los 
//              datos,de lo contrario realiza una actualización con los datos cargados en el arreglo 
//              $ar_datos                  
/////////////////////////////////////////////////////////////////////////////////////////////////////////

  $ls_codasiniv       = $ar_datos["codasinivel"];
  $ls_codniv	  	  = $ar_datos["codnivel"];
  $ls_descripcion     = $ar_datos["descripcion"];
  if ($this->uf_select_asig_niveles($ls_codasiniv,$ls_codniv))
	 {
		$ls_sql=" UPDATE sigesp_asig_nivel ".
				" SET codniv='".$ls_codniv."',tipproc='".$as_aperapr."', ".
				" despridoc='".$ls_descripcion."' ". 
				" WHERE codemp='".$this->ls_codemp."'".
				" AND codasiniv='".$ls_codasiniv."'";
		$this->io_sql->begin_transaction();             
		$rs_data=$this->io_sql->execute($ls_sql);
		if ($rs_data==false)
		   {
			 $this->io_sql->rollback();
	         $this->io_msg->message("CLASE->SIGESP_CFG_C_ASIG_NIVEL; METODO->uf_guardar_asignaciones_nivel;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));   
		 	 $lb_valido=false;
		   }
		else
		   {   
  		     /////////////////////////////////         SEGURIDAD               /////////////////////////////		
			 $ls_evento="UPDATE";
			 $ls_descripcion ="Actualizó el nivel ".$ls_codasiniv;
			 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
			 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
			 $aa_seguridad["ventanas"],$ls_descripcion);
			 /////////////////////////////////         SEGURIDAD               ///////////////////////////
			 $this->io_sql->commit();
			 $lb_valido=true;
			 $this->io_msg->message("Registro Actualizado !!!");
		   }	  	
		return $lb_valido;
	 }
  else
	 {
	  $ls_sql=" INSERT INTO sigesp_asig_nivel".
			  " (codemp,codasiniv,codniv,tipproc,despridoc) ".
			  " VALUES "." 
			   ('".$this->ls_codemp."','".$ls_codasiniv."','".$ls_codniv."','".$as_aperapr."','".$ls_descripcion."' )";
		$this->io_sql->begin_transaction();
		$rs_data=$this->io_sql->execute($ls_sql);
		if ($rs_data==false)
		   {
			 $this->io_sql->rollback();
	         $this->io_msg->message("CLASE->SIGESP_CFG_C_ASIG_NIVEL; METODO->uf_guardar_asignaciones_nivel;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));   
		     print ($this->io_sql->message); 
			 $lb_valido=false;
		   }
		else
		   {   
			 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
			 $ls_evento="INSERT";
			 $ls_descripcion ="Insertó el nivel ".$ls_codasiniv;
			 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
			 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
			 $aa_seguridad["ventanas"],$ls_descripcion);
			 /////////////////////////////////         SEGURIDAD               ///////////////////////////
			 $this->io_sql->commit();
			 $lb_valido=true;
			 $this->io_msg->message("Registro Incluido !!!");
		   }	  	
		return $lb_valido;	
	  }
 $this->io_sql->close();
 }
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function uf_select_asig_niveles($as_codigo,$as_codniv)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//	   Function:  uf_select_procedencia
//	     Access:  public
//    Arguments:
//   $as_codigo=  Valor a buscar dentro de la tabla de procedencias.
//	    Returns:  $lb_valido= Variable que devuelve true si encontro el registro 
//                de lo contrario devuelve false. 
//	Description:  Este método que se ancarga de buscar el Código de Procedencia enviado por parametro.
/////////////////////////////////////////////////////////////////////////////////////////////////////////

	$ls_sql  = "SELECT * FROM sigesp_asig_nivel WHERE codemp='".$this->ls_codemp."' AND codasiniv='".$as_codigo."' AND codniv='".$as_codniv."'";
	$rs_data = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
	     $lb_valido=false;
	     $this->io_msg->message("CLASE->SIGESP_CFG_C_ASIG_NIVELES; METODO->uf_select_asig_niveles;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));   
	   }
	else
	   {
	     $li_numrows=$this->io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
	        {  
		      $lb_valido=true;
	          $this->io_sql->free_result($rs_data);
			}
	     else
	        {
	  	      $lb_valido=false;
	        }	 
      }
return $lb_valido;
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function uf_delete_asig_nivel($as_codigoasi,$as_codigonivel,$aa_seguridad)
{   
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Function:  uf_delete_procedencia
//	Access:  public
//	Arguments:
// $as_codigo=  Valor a buscar dentro de la tabla de procedencias.
//	  Returns:	$lb_valido= Variable que devuelve true si encontro el registro 
//                          de lo contrario devuelve false. 
//	Description: Este método que se ancarga de buscar el Código de Procedencia enviado por parametro.
/////////////////////////////////////////////////////////////////////////////////////////////////////////

	$lb_valido = false;
	$ls_sql    = " DELETE FROM sigesp_asig_nivel WHERE codasiniv='".$as_codigoasi."' AND codniv='".$as_codigonivel."'";
    $this->io_sql->begin_transaction();
	$rs_data=$this->io_sql->execute($ls_sql);
	if ($rs_data===false)
	   { 
	          $this->io_msg_error="CLASE->SIGESP_CFG_C_ASIG_NIVEL; METODO->uf_delete_asig_nivel;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);   
	   }
    else
	   {
	     /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	     $ls_evento="DELETE";
	     $ls_descripcion ="Eliminó la asignacion de nivel ".$as_codigoasi;
	     $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	     $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	     $aa_seguridad["ventanas"],$ls_descripcion);
	     /////////////////////////////////         SEGURIDAD               ///////////////////////////// 
		 $lb_valido = true;
	   }
	return $lb_valido;
}




}//Fin de la Clase...
?>