<?php
/*********************************************************************************************************************************
          Compa�ia : SOFTBRI C.A.
    Nombre Archivo : sps_pro_liquidacion_dao.php
   Tipo de Archivo : Archivo tipo DAO ( DATA ACCESS OBJECT )
             Autor : Ing. Maria Alejandra Roa
  	   Descripci�n : Esta clase maneja el acceso de dato de la tabla liquidacion del sistema de presatciones sociales
*********************************************************************************************************************************/
require_once("../../class_folder/utilidades/class_dao.php");
require_once("../../../shared/class_folder/sigesp_c_seguridad.php");

class sps_pro_liquidacion_dao extends class_dao
{
   public function sps_pro_liquidacion_dao()
   {
     $this->class_dao("sps_liquidacion");  //constructor de la clase
	 $this->io_seguridad= new sigesp_c_seguridad();
     if(array_key_exists("la_empresa",$_SESSION))
	 {
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$this->ls_logusr=$_SESSION["la_logusr"];
	 }
	 $this->debe=0;
	 $this->haber=0;
   }

  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getRelationLiq
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripci�n : Funci�n que retorna un booleano para indicar si existe relacion con la tabla liquidacion
  //    Arguments : $as_codigo -> Parametro que indica el codigo que se buscar� en la tabla para chequear la integridad relacional
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function getRelationLiq($ps_codigo)
  {
    $lb_valido = false;
 	$ls_sql    = "SELECT * FROM ".$this->as_tabla." WHERE codcauret= ".$ps_codigo;	
    $rs_data= $this->io_sql->select($ls_sql);
	
	if($rs_data==false)
	{
		$this->io_msg->message("Error en getRelationLiq de la tabla ".$this->as_tabla );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
	}
	else 
	{ 
		$lb_valido=false;
	}
	
	return $lb_valido;
  }
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getProximoCodigo
  //      Alcance : Publico
  //         Tipo : String 
  //  Descripci�n : Funci�n que devuelve el proximo codigo generado que representa el id del nuevo registro.
  //    Arguments :
  //      Retorna : Codigo generado en string 
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function getProximoCodigo()
  {
	return $this->io_function_db->uf_generar_codigo(false,"",$this->as_tabla,"numliq");
  }	

  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getCausaRetiro
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripci�n : Funci�n que retorna un arreglo de datos de causas de retiro registradas en la tabla sps_causaretiro
  //    Arguments : $ps_orden -> orden de la sentencia 
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
   public function getCausaRetiro($ps_orden="", &$pa_datos="")
   {
   		$lb_valido = false;
		$ls_sql    = "SELECT codcauret,dencauret FROM sps_causaretiro ".$ps_orden;			  
		$rs_data= $this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			$this->io_function_sb->message("Error en getCausaRetiro. " );
		}
		elseif($row=$this->io_sql->fetch_row($rs_data))
		{
			 $lb_valido=true;
			 $pa_data=$this->io_sql->obtener_datos($rs_data); 
		 	 $pa_datos=$this->io_function_sb->uf_sort_array($pa_data); 
		}
		else { $this->io_function_sb->message("Registro no encontrado en la tabla "); }	
		return $lb_valido;
   }
   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getArticulos
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripci�n : Funci�n que retorna un arreglo de registros del DAO
  //    Arguments : $ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function getArticulos( $ps_orden="", &$pa_datos="" )  
  {
  	$lb_valido = false;
 	$ls_sql    = "SELECT DISTINCT id_art,conart FROM sps_articulos ".$ps_orden;			  

    $rs_data= $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_function_sb->message("Error en getArticulos de la tabla ".$this->as_tabla );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
		 $pa_data =$this->io_sql->obtener_datos($rs_data);
		 $pa_datos=$this->io_function_sb->uf_sort_array($pa_data); 
	}
	else { $this->io_function_sb->message("Registro no encontrado en la tabla ".$this->as_tabla); }	
	return $lb_valido;
  }
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getDeudaAnterior
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripci�n : Funci�n que retorna un arreglo de datos del monto adeudado anterior por concepto de antiguedad 
  //    Arguments : $ps_codper -> codigo de personal
  //                $ps_codnom -> codigo de nomina
  //                $pd_fecdes -> fecha desde
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
  public function getDeudaAnterior( $ps_codper,$ps_codnom,$pd_fecdes,&$pa_datos )
  { 
     $lb_valido=false;
	 $ld_fecdes  = $this->io_function->uf_convertirdatetobd($pd_fecdes);
  	 $ls_sql = "SELECT  feccordeuant,deuantant,deuantint,antpag FROM sps_deuda_anterior WHERE codemp='".$this->ls_codemp."' and codper='".$ps_codper."' and codnom='".$ps_codnom."' and feccordeuant > '".$ld_fecdes."' and estdeuant='E'";	 
	 $rs_data   = $this->io_sql->select($ls_sql);			
	 if($rs_data==false)
	 {
		$this->io_function_sb->message("Error en getDeudaAnterior en ".$this->as_tabla );
	 }
	 elseif($row=$this->io_sql->fetch_row($rs_data))	
	 {
		$lb_valido=true;
	    $pa_data =$this->io_sql->obtener_datos($rs_data); 
		$pa_datos=$this->io_function_sb->uf_sort_array($pa_data); 
	 }
	 else { $this->io_function_sb->message("Registro no encontrado en la tabla "); }
		 
	 return $lb_valido;
  } // end function getDeudaAnterior
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getAntiguedad
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripci�n : Funci�n que retorna un arreglo de datos del monto adeudado por concepto de antiguedad 
  //    Arguments : $ps_codper -> codigo de personal
  //                $ps_codnom -> codigo de nomina
  //                $pd_fecdes -> fecha desde
  //                $pd_fechas -> fecha hasta
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
  public function getAntiguedad( $ps_codper,$ps_codnom,$pd_fecdes,$pd_fechas,&$pa_datos )
  { 
     $lb_valido=false;
	 $ld_fecdes  = $this->io_function->uf_convertirdatetobd($pd_fecdes);
	 $ld_fechas  = $this->io_function->uf_convertirdatetobd($pd_fechas);
  	 $ls_sql = "SELECT sum(diabas) as diasal,sum(monant) as monant,sum(monantant)as monantant,sum(monint) as monint FROM sps_antiguedad WHERE codemp='".$this->ls_codemp."' and codper='".$ps_codper."' and codnom='".$ps_codnom."' and fecant between '".$ld_fecdes."' and '".$ld_fechas."' and estant<>'P'"	;	 
	 $rs_data   = $this->io_sql->select($ls_sql);			
	 if($rs_data==false)
	 {
		$this->io_function_sb->message("Error en getAntiguedad en ".$this->as_tabla );
	 }
	 elseif($row=$this->io_sql->fetch_row($rs_data))	
	 {
		$lb_valido=true;
	    $pa_data =$this->io_sql->obtener_datos($rs_data); 
		$pa_datos=$this->io_function_sb->uf_sort_array($pa_data); 
	 }
	 else { $this->io_function_sb->message("Registro no encontrado en la tabla "); }
		 
	 return $lb_valido;
  } // end function getExtraerAntiguedad
  
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getIncidencias
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripci�n : Funci�n que retorna los dias de incidencias 
  //    Arguments : $pi_ano -> 
  //                $ps_dedicacion -> dedicacion
  //                $ps_tipopersonal -> tipo de personal
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  function getIncidencias($pi_ano,$ps_tipoper,$ps_dedicacion,&$pa_datos ) 
  {       
   	$lb_valido = false;
 	$ls_sql    = "SELECT f.anocurfid, f.diabonvacfid, f.diabonfinfid FROM sno_fideiconfigurable f WHERE f.anocurfid='".$pi_ano."' AND codtipper IN (
                  SELECT codtipper FROM sno_tipopersonal WHERE destipper='".$ps_tipoper."' AND codded IN ( 
				  SELECT codded FROM sno_dedicacion WHERE desded='".$ps_dedicacion."'))";		  			  
	$rs_data   = $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_function_sb->message("Error en getIncidencias " );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
		 $pa_datos =$this->io_sql->obtener_datos($rs_data);
	}
	else { $this->is_msg_error = "No existen registro de incidencia para el a�o ".$pi_ano ; }	
	return $lb_valido;
  }
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getVacaciones
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripci�n : Funci�n que retorna un arreglo de datos del monto adeudado por concepto de vacaciones 
  //    Arguments : $ps_codper -> codigo de personal
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
  public function getVacaciones( $ps_codper,$ps_codnom,&$pa_datos )
  { 
     $lb_valido=false;
  	 $ls_sql   = "SELECT fecvenvac,diavac,sueintvac,diaadivac FROM sno_vacacpersonal WHERE codemp='".$this->ls_codemp."' and codper='".$ps_codper."' and stavac='1' and pagcan='0' "	;	 
	 $rs_data  = $this->io_sql->select($ls_sql);			
	 if($rs_data==false)
	 {
		$this->io_function_sb->message("Error en getVacaciones en ".$this->as_tabla );
	 }
	 elseif($row=$this->io_sql->fetch_row($rs_data))	
	 {
		$lb_valido=true;
	    $pa_data =$this->io_sql->obtener_datos($rs_data); 
		$pa_datos=$this->io_function_sb->uf_sort_array($pa_data); 
	 }
	 else { $this->io_function_sb->message("Registro no encontrado en la tabla "); }
		 
	 return $lb_valido;
  } // end function geVacaciones
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getBonoVacacional
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripci�n : Funci�n que retorna un arreglo de datos del monto adeudado por concepto de bono vacacional 
  //    Arguments : $ps_codper -> codigo de personal
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
  public function getBonoVacacional( $ps_codper,$ps_codnom,&$pa_datos )
  { 
     $lb_valido=false;
  	 $ls_sql   = "SELECT fecvenvac,sueintbonvac,diabonvac FROM sno_vacacpersonal WHERE codemp='".$this->ls_codemp."' and codper='".$ps_codper."' and pagcan='0' ";	 
	 $rs_data  = $this->io_sql->select($ls_sql);			
	 if($rs_data==false)
	 {
		$this->io_function_sb->message("Error en getBonoVacacional en ".$this->as_tabla );
	 }
	 elseif($row=$this->io_sql->fetch_row($rs_data))	
	 {
		$lb_valido=true;
	    $pa_data =$this->io_sql->obtener_datos($rs_data); 
		$pa_datos=$this->io_function_sb->uf_sort_array($pa_data); 
	 }
	 else { $this->io_function_sb->message("Registro no encontrado en la tabla "); }
		 
	 return $lb_valido;
  } // end function getBonoVacacional
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getDatosPersonal
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripci�n : Funci�n que retorna la fecha de Ingrso del empleado
  //    Arguments : $ps_codper -> codigo de personal
  //                $ps_codnom -> codigo de nomina
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function getDatosPersonal( $ps_codper, $ps_codnom, &$pa_datos )  
  {       
  	$lb_valido = false;
	$ls_sql    = "SELECT p.fecingper,p.sueintper, p.sueintper/30 as sueintdia, c.denasicar FROM sno_personalnomina p, sno_asignacioncargo c WHERE  p.codemp='".$this->ls_codemp."' and codper='".$ps_codper."' and codnom='".$ps_codnom."' and p.codemp=c.codemp and p.codnom=c.codnom and p.codasicar=c.codasicar  ";
	$rs_data   = $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_function_sb->message("Error en getDatosPersonal ".$this->as_tabla );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
		 $pa_data =$this->io_sql->obtener_datos($rs_data); 
		 $pa_datos=$this->io_function_sb->uf_sort_array($pa_data);
	}
	else { $this->io_function_sb->message("Registro no encontrado en la tabla "); }	
	return $lb_valido;
  } 
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getBonoNavidad
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripci�n : Funci�n que retorna un arreglo de datos del monto adeudado por concepto de bono de fin de a�o 
  //    Arguments : $ps_codper -> codigo de personal
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
  public function getBonoNavidad( $ps_codper,$ps_codnom,&$pa_datos )
  { 
     $lb_valido=false;
  	 $ls_sql   = "SELECT fecvenvac,sueintbonvac,diabonvac FROM sno_vacacpersonal WHERE codemp='".$this->ls_codemp."' and codper='".$ps_codper."' and pagcan='0' ";	 
	 $rs_data  = $this->io_sql->select($ls_sql);			
	 if($rs_data==false)
	 {
		$this->io_function_sb->message("Error en getBonoNavidad en ".$this->as_tabla );
	 }
	 elseif($row=$this->io_sql->fetch_row($rs_data))	
	 {
		$lb_valido=true;
	    $pa_data =$this->io_sql->obtener_datos($rs_data); 
		$pa_datos=$this->io_function_sb->uf_sort_array($pa_data); 
	 }
	 else { $this->io_function_sb->message("Registro no encontrado en la tabla "); }
		 
	 return $lb_valido;
  } // end function getBonoNavidad
  
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getDetalleArticulo
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripci�n : Funci�n que retorna un arreglo de registros del DAO de detalles de articulos
  //    Arguments : $ps_id_art -> Parametro que indica el numero identificador del articulo
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  function getDetalleArticulo( $ps_id_art="", &$pa_datos="" )  
  {
  	$lb_valido = false;
 	$ls_sql    = "SELECT id_art,numart,conart,numlitart,operador,canmes,tiempo,diasal,condicion,estacu,diaacu,numcon,estpro,valmaxpro 
	              FROM sps_articulos  WHERE id_art='".$ps_id_art."' ORDER BY numlitart,numcon DESC" ;			  

    $rs_data= $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_function_sb->message("Error en DetalleArticulo en Liquidacion_dao " );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
		 $pa_datos =$this->io_sql->obtener_datos($rs_data);
		 $pa_datos =$this->io_function_sb->uf_sort_array($pa_datos); 
	}
	else { $this->io_function_sb->message("Registro no encontrado "); }	
	return $lb_valido;
  }
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getDetalleLiquidacion
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripci�n : Funci�n que retorna un arreglo de registros del DAO de los detalles de la Liquidacion
  //    Arguments : 
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  function getDetalleLiquidacion($ps_codper,$ps_codnom,$ps_numliq, &$pa_datos="" )  
  {   
  	$lb_valido = false;
	$ls_sql    = "SELECT desespliq,diapag,subtotal FROM sps_dt_liquidacion WHERE codemp='".$this->ls_codemp."' AND codper='".$ps_codper."' AND codnom='".$ps_codnom."' AND numliq='".$ps_numliq."' ORDER BY numespliq ASC" ;			  
	
    $rs_data= $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_function_sb->message("Error en DetalleLiquidacion " );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
		 $pa_data  =$this->io_sql->obtener_datos($rs_data);
		 $pa_datos =$this->io_function_sb->uf_sort_array($pa_data); 
	}
	else { $this->io_function_sb->message("Registro no encontrado "); }	
	return $lb_valido;
  }
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : updateLiquidacion
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripci�n : Funci�n que actualiza la informaci�n sobre la aprob/rechazo del anticipo 
  //    Arguments : $ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
  public function updateLiquidacion( $po_object , $ps_operacion="modificar" )
  {	
        $lb_actilizo= false;
		$lb_valido =true;  
		$ls_fecliq  = $this->io_function->uf_convertirdatetobd($po_object->fecliq );
		if ($ps_operacion=="modificar")
		{
			$this->io_sql->begin_transaction();
			$ls_sql = "UPDATE ".$this->as_tabla." ".
					  "   SET estliq='".$po_object->estliq."', ".
					  "		  obsliq='".$po_object->obsliq."' ". 
			          " WHERE codemp='".$this->ls_codemp."' ".
					  "   AND codper='".$po_object->codper."' ".
					  "   AND codnom='".$po_object->codnom."' ".
					  "   AND numliq='".$po_object->numliq."'";
			$li_guardo = $this->io_sql->execute( $ls_sql );
			if ($li_guardo > 0)
			{
			   if (($po_object->estliq=='A')||($po_object->estliq=='R'))
			   {
					$ls_dia = substr($po_object->fecliq,0,2);
					$ls_mes = substr($po_object->fecliq,3,2);
					$ls_anoantper = substr($po_object->fecliq, 8, 2);                              //10/04/2008=>100408
					$ls_comprobante=$ls_dia.$ls_mes.$ls_anoantper.substr($po_object->codper, 2, 8); // Comprobante
					$ls_sql = "DELETE FROM sps_dt_spg  ".
							  " WHERE codemp='".$this->ls_codemp."' ".
							  "   AND codnom='".$po_object->codnom."' ".
							  "   AND codcom='".$ls_comprobante."'".
							  "   AND tipo ='L'";
					$li_guardo = $this->io_sql->execute( $ls_sql );
					if ($li_guardo===false)
					{
						$lb_valido =false;
					}
					$ls_sql = "DELETE FROM sps_dt_scg  ".
							  " WHERE codemp='".$this->ls_codemp."' ".
							  "   AND codnom='".$po_object->codnom."' ".
							  "   AND codcom='".$ls_comprobante."'".
							  "   AND tipo ='L'";
					$li_guardo = $this->io_sql->execute( $ls_sql );
					if ($li_guardo===false)
					{
						$lb_valido =false;
					}
			   }			  
			   //genero los datos para el asiento de contabilizacion si la liquidacion es aprobada
			   if (($po_object->estliq=='A')&&($lb_valido))
			   {                                                 
					$lb_valido = $this->uf_contabilizar_liquidacion_spg($po_object->codnom,$po_object->codper,$po_object->numliq,$po_object->fecliq);                                                                                
					if ($lb_valido) 
					{
						$lb_valido = $this->uf_contabilizar_liquidacion_scg($po_object->codnom,$po_object->codper,$po_object->numliq,$po_object->fecliq);
					}
			   }	
			   $lb_guardo=$lb_valido;
			}
			else
			{
			  
			   $this->io_function_sb->message("No pudo actualizar los Datos.");
			   $lb_guardo=false;
			}	
			if($lb_valido)
			{
				 $this->io_sql->commit();
			   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
			    $ls_evento="UPDATE";
			    $ls_descripcion =" Actualiz� en la tabla ".$this->as_tabla." codper=".$po_object->codper." codnom=".$po_object->codnom." numliq=".$po_object->numliq;
			    $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_pro_aprobacionliquidacion.html.php",$ls_descripcion);
			   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
			   $this->io_function_sb->message("Los Datos fueron actualizados.");
			}
			else
			{
				 $this->io_sql->rollback();
			}
					
		}	
	    return $lb_guardo;
  }  //function updateLiquidacion
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : updateAntiguedad
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripci�n : Funci�n que actualiza la informaci�n sobre la aprob/rechazo del anticipo 
  //    Arguments : $pd_fecdes -> Parametro que indica la fecha desde de Antiguedad
  //                $pd_fechas -> Parametro que indica la fecha hasta de Antiguedad    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
  public function updateAntiguedad( $po_liq )
  {	
  
 	    $lb_actilizo= false;  
		$ld_fecdes  = $this->io_function->uf_convertirdatetobd($po_liq->fecdes);
		$ld_fechas  = $this->io_function->uf_convertirdatetobd($po_liq->fechas);
		
		$ls_sql = " UPDATE sps_antiguedad SET liquidacion='".$po_liq->numliq."', estant='L' WHERE codemp='".$this->ls_codemp."' AND codper='".$po_liq->codper."' AND codnom='".$po_liq->codnom."' and fecant between '".$ld_fecdes."' and '".$ld_fechas."' ";
		$li_guardo = $this->io_sql->execute( $ls_sql );
		if ($li_guardo > 0)
		{
		   $this->io_sql->commit();
		    /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		    $ls_evento="UPDATE";
		    $ls_descripcion =" Actualiz� en la tabla sps_antiguedad, codper=".$po_liq->codper." codnom=".$po_liq->codnom." ";
		    $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_pro_aprobacionliquidacion.html.php",$ls_descripcion);
		   /////////////////////////////////         SEGURIDAD               /////////////////////////////
		   $lb_actilizo=true;
		}
		else
		{
		   $this->io_sql->rollback();
		   $lb_actilizo=false;
		}				
		return $lb_actilizo;
  }  //function updateAntiguedad
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : updateData
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripci�n : Funci�n que actualiza la informaci�n sobre el detalle de la liquidacion
  //    Arguments : $ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
  public function updateData( $po_object ) 
  {	
        $lb_actilizo= false;  
		$ld_salpro = $this->uf_convertir_decimal_bd( $po_object->salpro ); 
		$ld_subtotal = $this->uf_convertir_decimal_bd( $po_object->subtotal ); 

		$ls_sql = "DELETE FROM sps_dt_liquidacion WHERE codemp='".$this->ls_codemp."' and codper='".$po_object->codper."' and codnom='".$po_object->codnom."' and numliq='".$po_object->numliq."' ";
	    $li_elimino=$this->io_sql->execute( $ls_sql );
	    if ($li_elimino > 0)
		{   
			$lb_valido = $this->restaurarAntiguedad( $po_object->codper,$po_object->codnom,$po_object->numliq );
			if ($lb_valido)
			{
				$this->io_sql->commit();
				$lb_actilizo = true;
			}
		}
		else    
		{
			$this->io_sql->rollback();
			$this->io_function_sb->message("Los datos no pueden ser modificados/actualizados.");
		}
	    return $lb_actilizo;
  }  //function updateData
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : guardarLiquidacion
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripci�n : Funci�n que guarda la informaci�n 
  //    Arguments : $ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
  public function guardarLiquidacion($po_object, $ps_operacion="insertar" )
  {	
  	$lb_guardo   = true;
	$lb_valido   = false;
	$li_registro = 0;
	if ($ps_operacion=="modificar")  
	{
		$this->io_function_sb->message("No pudo modificar los datos, para ello elimine la Liquidaci�n.");
	}
	else
	{
		$lb_guardo = $this->insertCabecera($po_object->dt_liquidacion[$li_registro]);
		if ($lb_guardo)
		{  
			while (($li_registro<count($po_object->dt_liquidacion))&&($lb_guardo))
			{  
				$lb_guardo = $this->insertDetalle($po_object->dt_liquidacion[$li_registro]);
				$li_registro++;
			} //end del while
		}	
	} //end del else
	if ($lb_guardo)
	{
	   $this->io_sql->commit();
	    /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	    $ls_evento="UPDATE";
	    $ls_descripcion =" Actualiz� en la tabla sps_antiguedad, codper=".$po_liq->codper." codnom=".$po_liq->codnom." ";
	    $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_pro_aprobacionliquidacion.html.php",$ls_descripcion);
		   /////////////////////////////////         SEGURIDAD               /////////////////////////////
	   $this->io_function_sb->message("Los datos fueron actualizados.");
	   $lb_valido = $this->updateAntiguedad($po_object->dt_liquidacion[0]);  
	}
	else
	{
	   $this->io_sql->rollback();
	   $this->io_function_sb->message("No pudo actualizar los datos.");
	}	 
  } //end function guardarLiquidacion
  function uf_convertir_decimal_bd( $pd_decimal)
  {
  		$ld_decaux = str_replace(".", "", $pd_decimal );	
		$ld_dec    = str_replace(",", ".", $ld_decaux );	
  		return $ld_dec;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
  //     Function : insertCabecera
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripci�n : Funci�n que guarda la informaci�n 
  //    Arguments : $ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
  public function insertCabecera( $po_liq ) 
  {	 
  	$lb_inserto = false;
	$ls_obsliq  = "";
	$this->io_sql->begin_transaction();

		$ls_fecliq    = $this->io_function->uf_convertirdatetobd($po_liq->fecliq );
		$ls_fecing    = $this->io_function->uf_convertirdatetobd($po_liq->fecing );
		$ls_fecegr    = $this->io_function->uf_convertirdatetobd($po_liq->fecegr ); 
		$ld_salint    = $this->uf_convertir_decimal_bd( $po_liq->salint ); 
		$ld_totasiliq = $this->uf_convertir_decimal_bd( $po_liq->totasiliq );	
		$ld_totdedliq = $this->uf_convertir_decimal_bd( $po_liq->totdedliq );
		$ld_totpagliq = $this->uf_convertir_decimal_bd( $po_liq->totpagliq );
		$ld_diaabofid = $this->uf_convertir_decimal_bd( $po_liq->diaabofid );
					
		$ls_sql = " INSERT INTO ".$this->as_tabla." (codemp,codper,codnom,numliq,codcauret,fecliq,fecing,fecegr,salint,descargo,anoser,messer,diaser,totasiliq,totdedliq,totpagliq,estliq,obsliq,dedicacion,tipopersonal,diaabofid)
	                VALUES ('".$this->ls_codemp."','".$po_liq->codper."','".$po_liq->codnom."','".$po_liq->numliq."','".$po_liq->codcauret."','".$ls_fecliq."','".$ls_fecing."','".$ls_fecegr."','".$ld_salint."','".$po_liq->descargo."','".$po_liq->anoser."','".$po_liq->messer."','".$po_liq->diaser."','".$ld_totasiliq."','".$ld_totdedliq."','".$ld_totpagliq."','".$po_liq->estliq."','".$ls_obsliq."','".$po_liq->dedicacion."','".$po_liq->tipopersonal."','".$ld_diaabofid."' ) ";						
	
	    $li_inserto = $this->io_sql->execute( $ls_sql );
	
	if ($li_inserto>0 )
	{ 
	 	$lb_inserto=true;
	}
	else { $lb_inserto=false; }
    return $lb_inserto;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
  //     Function : insertDetalle
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripci�n : Funci�n que guarda la informaci�n de detalles de la liquidacion
  //    Arguments : $ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
  public function insertDetalle( $po_liq ) 
  {	 
  	$lb_inserto_dt = false;
	$this->io_sql->begin_transaction();
	$li_pos=strpos($po_liq->diapag,"-");
	if($li_pos===0)
	{ 
		$ld_diapag = "0.00";
	}
	else
	{   
		$ld_diapag = $this->uf_convertir_decimal_bd( $po_liq->diapag ); 
	}

	$ld_salpro   = $this->uf_convertir_decimal_bd( $po_liq->salpro );
	$ld_subtotal = $this->uf_convertir_decimal_bd( $po_liq->subtotal );
			
	$ls_sql = " INSERT INTO sps_dt_liquidacion (codemp, codper, codnom, numliq, numespliq, desespliq, salpro, diapag, subtotal, sc_cuenta_ded,id_art)
				VALUES ('".$this->ls_codemp."','".$po_liq->codper."','".$po_liq->codnom."','".$po_liq->numliq."','".$po_liq->numespliq."','".$po_liq->desespliq."','".$ld_salpro."','".$ld_diapag."','".$ld_subtotal."','".$po_liq->sc_cuenta_ded."','".$po_liq->id_art."' ) ";					
	$li_inserto_dt = $this->io_sql->execute( $ls_sql );


	if ($li_inserto_dt>0 )
	{ 
	 	$lb_inserto_dt=true;
	}
	else { $lb_inserto_dt=false; }
    return $lb_inserto_dt;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
  //     Function : get_liquidacion
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripci�n : Funci�n que obtiene la informaci�n de la liquidacion
  //    Arguments : $ps_numliq:numero de liquidacion,$ps_nomper:nombre personal,$ps_apeper:apellido personal
  //				$ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
   public function get_liquidacion($ps_numliq,$ps_nomper,$ps_apeper,$ps_order,&$pa_datos="")
   {
  		 $lb_valido = false;
		 $ls_numliq = $ps_numliq."%";
		 $ls_nomper = $ps_nomper."%"; 
		 $ls_apeper = $ps_apeper."%";
		 $ls_sql    = "SELECT l.numliq, p.nomper, p.apeper, l.codper,l.codnom,n.desnom,l.codcauret,l.fecliq,l.fecing,l.fecegr,l.salint,l.descargo,l.anoser,l.messer,l.diaser,l.totasiliq,l.totdedliq,l.totpagliq,l.estliq,l.dedicacion,l.tipopersonal,l.diaabofid
		               FROM sps_liquidacion l, sno_personal p, sno_nomina n 
					   WHERE l.codemp=p.codemp and l.codper=p.codper and l.codemp='".$this->ls_codemp."' and l.numliq like '".$ls_numliq."' and p.nomper like '".$ls_nomper."' and p.apeper like '".$ls_apeper."' and l.codemp=n.codemp and l.codnom=n.codnom ";	 //and estliq='R' 
		 $rs_data   = $this->io_sql->select($ls_sql);			
		 if($rs_data==false)
		 {
			$this->io_function_sb->message("Error en get_liquidacion en ".$this->as_tabla );
		 }
		 elseif($row=$this->io_sql->fetch_row($rs_data))	
		 {
			$lb_valido=true;
			$pa_data =$this->io_sql->obtener_datos($rs_data); 
			$pa_datos=$this->io_function_sb->uf_sort_array($pa_data); 
		 }
		 else { $this->io_function_sb->message("Registro no encontrado en la tabla "); }
			 
		 return $lb_valido; 		
   }

  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
  //     Function : get_aprob_liquidacion
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripci�n : Funci�n que obtiene la informaci�n de la liquidacion
  //    Arguments : $ps_numliq:numero de liquidacion,$ps_nomper:nombre personal,$ps_apeper:apellido personal
  //				$ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
   public function get_aprob_liquidacion($ps_numliq,$ps_nomper,$ps_apeper,$ps_order,&$pa_datos="")
   {
  		 $lb_valido = false;
		 $ls_numliq = strtoupper($ps_numliq)."%";
		 $ls_nomper = strtoupper($ps_nomper)."%"; 
		 $ls_apeper = strtoupper($ps_apeper)."%";
		 $ps_order  = "ORDER BY l.numliq, p.nomper, p.apeper, l.codper,l.codnom,n.desnom,l.fecliq,l.fecing,l.fecegr,l.totpagliq,l.estliq,l.obsliq ASC";
		 $ls_sql    = "SELECT l.numliq, p.nomper, p.apeper, l.codper,l.codnom,n.desnom,l.fecliq,l.fecing,l.fecegr,l.totpagliq,l.estliq,l.obsliq 
		               FROM sps_liquidacion l, sno_personal p, sno_nomina n 
					   WHERE l.codemp=p.codemp and l.codper=p.codper and l.codemp='".$this->ls_codemp."' and l.numliq like '".$ls_numliq."' and p.nomper like '".$ls_nomper."' and p.apeper like '".$ls_apeper."' and l.codemp=n.codemp and l.codnom=n.codnom  ".$ps_order;	 
		 $rs_data   = $this->io_sql->select($ls_sql);			
		 if($rs_data==false)
		 {
			$this->io_function_sb->message("Error en get_aprob_liquidacion en ".$this->as_tabla );
		 }
		 elseif($row=$this->io_sql->fetch_row($rs_data))	
		 {
			$lb_valido=true;
			$pa_data =$this->io_sql->obtener_datos($rs_data); 
			$pa_datos=$this->io_function_sb->uf_sort_array($pa_data); 
		 }
		 else { $this->io_function_sb->message("Registro no encontrado en la tabla "); }
			 
		 return $lb_valido; 		
   }
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
  //     Function : get_personal_Liq
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripci�n : Funci�n que obtiene la informaci�n del personal que posee anticipos en el modulo de sps
  //    Arguments : $ps_codper -> codigo de personal,$ps_nomper:nombre personal,$ps_apeper:apellido personal
  //				$ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
   public function get_personal_Liq($ps_codper,$ps_nomper,$ps_apeper,$ps_order,&$pa_datos="")
   {
  		 $lb_valido = false;
		 $ls_codper = strtoupper($ps_codper)."%";
		 $ls_nomper = strtoupper($ps_nomper)."%"; 
		 $ls_apeper = strtoupper($ps_apeper)."%";
		 $ps_order  = "ORDER BY a.codper, p.nomper, p.apeper,a.codnom,n.desnom,a.numliq ASC";
		 $ls_sql    = "SELECT DISTINCT a.codper, p.nomper, p.apeper,a.codnom,n.desnom,a.numliq
                       FROM sps_liquidacion a, sno_personal p, sno_nomina n
                       WHERE a.codemp=p.codemp and a.codper=p.codper and a.codemp=n.codemp and a.codnom=n.codnom and a.codemp='".$this->ls_codemp."' and p.codper like '".$ls_codper."' and p.nomper like '".$ls_nomper."' and p.apeper like '".$ls_apeper."' and a.estliq='A' ".$ps_order;
		 $rs_data   = $this->io_sql->select($ls_sql);			
		 if($rs_data==false)
		 {
			$this->io_function_sb->message("Error en get_personal_Liq ".$this->as_tabla );
		 }
		 elseif($row=$this->io_sql->fetch_row($rs_data))	
		 {
			$lb_valido=true;
			$pa_data =$this->io_sql->obtener_datos($rs_data); 
			$pa_datos=$this->io_function_sb->uf_sort_array($pa_data); 
		 }
		 else { $this->io_function_sb->message("Registro no encontrado en la tabla "); }
			 
		 return $lb_valido; 		
   }

  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
  //     Function : getCabeceraLiquidacion
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripci�n : Funci�n que obtiene la informaci�n de los datos de cabecera de la liquidaci�n
  //    Arguments : $ps_codper -> codigo de personal
  //				$ps_codnom -> codigo del a nomina
  //				$ps_numliq -> numero de liquidacion
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
   public function getCabeceraLiquidacion($ps_codper,$ps_codnom,$ps_numliq,&$pa_datos="")
   {
  		 $lb_valido = false;
		 $ls_sql    = "SELECT p.cedper,l.fecliq,l.fecing,l.fecegr,l.descargo,l.anoser,l.messer,l.diaser,c.dencauret,l.salint,pn.sueproper,u.desuniadm
					   FROM   sps_liquidacion l, sps_causaretiro c, sno_personalnomina pn, sno_unidadadmin u,sno_personal p
                       WHERE  l.codemp=p.codemp and l.codper=p.codper and l.codcauret=c.codcauret and l.codemp=pn.codemp and l.codnom=pn.codnom and l.codper=pn.codper and pn.codemp=u.codemp and pn.minorguniadm=u.minorguniadm and pn.ofiuniadm=u.ofiuniadm and pn.uniuniadm=u.uniuniadm and pn.depuniadm=u.depuniadm and pn.prouniadm=u.prouniadm and l.codemp='".$this->ls_codemp."' and l.codper='".$ps_codper."' and l.codnom='".$ps_codnom."' and l.numliq='".$ps_numliq."' ";

		 $rs_data   = $this->io_sql->select($ls_sql);			
		 if($rs_data==false)
		 {
			$this->io_function_sb->message("Error en getCabeceraLiquidacion ".$this->as_tabla );
		 }
		 elseif($row=$this->io_sql->fetch_row($rs_data))	
		 {
			$lb_valido=true;
			$pa_data =$this->io_sql->obtener_datos($rs_data); 
			$pa_datos=$this->io_function_sb->uf_sort_array($pa_data); 
		 }
		 else { $this->io_function_sb->message("Registro no encontrado en la tabla "); }
			 
		 return $lb_valido; 		
   }
   
   //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
  //     Function : getDetalleLiquidacionReporte
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripci�n : Funci�n que obtiene la informaci�n de los datos del detalle de la liquidaci�n
  //    Arguments : $ps_codper -> codigo de personal
  //				$ps_codnom -> codigo del a nomina
  //				$ps_numliq -> numero de liquidacion
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
   public function getDetalleLiquidacionReporte($ps_codper,$ps_codnom,$ps_numliq,&$pa_datos="")
   {
  		 $lb_valido = false;
		 $ls_sql    = "SELECT numespliq,desespliq,diapag,subtotal
					   FROM   sps_dt_liquidacion 
                       WHERE  codemp='".$this->ls_codemp."' and codper='".$ps_codper."' and codnom='".$ps_codnom."' and numliq='".$ps_numliq."' ORDER BY numespliq ASC ";

		 $rs_data   = $this->io_sql->select($ls_sql);			
		 if($rs_data==false)
		 {
			$this->io_function_sb->message("Error en getCabeceraLiquidacion ".$this->as_tabla );
		 }
		 elseif($row=$this->io_sql->fetch_row($rs_data))	
		 {
			$lb_valido=true;
			$pa_data =$this->io_sql->obtener_datos($rs_data); 
			$pa_datos=$this->io_function_sb->uf_sort_array($pa_data); 
		 }
		 else { $this->io_function_sb->message("Registro no encontrado en la tabla "); }
			 
		 return $lb_valido; 		
   }// end function getDetalleLiquidacionReporte
   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : restaurarAntiguedad
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripci�n : Funci�n que actualiza la informaci�n cuando una liquidacion es eliminada (solo cuando el esttus esta en R)
  //    Arguments : $ps_numliq -> Numero de liquidacion
  //                $ps_codper -> Codigo Personal
  //                $ps_codnom -> Codigo Nomina 
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
  public function restaurarAntiguedad( $ps_codper, $ps_codnom, $ps_numliq )
  {	
  		$lb_actilizo= false;
		$lb_valido=false;
		$ls_sql = "SELECT estant FROM sps_antiguedad WHERE codemp='".$this->ls_codemp."' AND codper='".$ps_codper."' AND codnom='".$ps_codnom."' and liquidacion='".$ps_numliq."' ";

		$rs_data   = $this->io_sql->select($ls_sql);
		if($row=$this->io_sql->fetch_row($rs_data))	
		{  
			$lb_valido=true;
		}
		else { $lb_valido=false; }		
        if ($lb_valido)  
		{
			$ls_sql = " UPDATE sps_antiguedad SET liquidacion='0', estant='R' WHERE codemp='".$this->ls_codemp."' AND codper='".$ps_codper."' AND codnom='".$ps_codnom."' and liquidacion='".$ps_numliq."' ";
		
			$li_guardo = $this->io_sql->execute( $ls_sql );
			if ($li_guardo > 0)
			{
			   $this->io_sql->commit();
			     /////////////////////////////////         SEGURIDAD               /////////////////////////////		
			  	$ls_evento="UPDATE";
				$ls_descripcion ="Restaurar datos en sps_antiguedad por eliminar Liquidacion, codper=".$ps_codper." codnom=".$ps_codnom;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_pro_liquidaciones.html.php",$ls_descripcion);
			   /////////////////////////////////         SEGURIDAD               /////////////////////////////	
			   $lb_actilizo=true;
			}
			else
			{
			   $this->io_sql->rollback();
			   $this->io_function_sb->message("No pudo actualizar los datos en la tabla Antiguedad.");
			   $lb_actilizo=false;
			}				
		}	
		return $lb_actilizo;
  }  //function restaurarAntiguedad
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : eliminarData
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripci�n : Funci�n que elimina un registro de datos
  //    Arguments : $pa_codigo -> representa el codigo clave primario de la tabla
  //      Retorna : Registro eliminado
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////    
  public function eliminarData( $ps_codper, $ps_codnom, $ps_numliq )
  {
     $lb_borro = false;
	 $lb_valido= false;
     $ls_sql   = "SELECT estliq FROM ".$this->as_tabla." WHERE codemp='".$this->ls_codemp."' and codper='".$ps_codper."' and codnom='".$ps_codnom."' and numliq='".$ps_numliq."' ";	
     $rs_data= $this->io_sql->select($ls_sql);
	 if($row=$this->io_sql->fetch_row($rs_data))
	 {
		 $pa_data=$this->io_sql->obtener_datos($rs_data); 
		 $ls_estliq=$pa_data["estliq"][1];
		 if ($ls_estliq=='R') $lb_valido=true;
	 }
	 else 
	 { 
		$lb_valido=false;
	 }
     if (!$lb_valido)
	 {
	   $this->io_function_sb->message("No se puede modificar/actualizar este registro.");
	 }
	 else
	 {
	    $ls_sql = "DELETE FROM sps_dt_liquidacion WHERE codemp='".$this->ls_codemp."' and codper='".$ps_codper."' and codnom='".$ps_codnom."' and numliq='".$ps_numliq."' ";
	    $li_elimino=$this->io_sql->execute( $ls_sql );
	    if ($li_elimino > 0)
		{
			$ls_sql = "DELETE FROM ".$this->as_tabla." WHERE codemp='".$this->ls_codemp."' and codper='".$ps_codper."' and codnom='".$ps_codnom."' and numliq='".$ps_numliq."' ";
			$li_elimino=$this->io_sql->execute( $ls_sql );
			if ($li_elimino > 0)
			{
				$lb_valido = $this->restaurarAntiguedad( $ps_codper, $ps_codnom, $ps_numliq );
				if ($lb_valido)
				{
					$this->io_sql->commit();
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="DELETE";
				$ls_descripcion =" Elimin� en la tabla ".$this->as_tabla." codper=".$ps_codper." codnom=".$ps_codnom." numliq=".$ps_numliq;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_pro_liquidaciones.html.php",$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$this->io_function_sb->message("Los datos fueron eliminados.");
					$lb_borro = true;
				}	
			}
			else
			{
				$this->io_sql->rollback();
				$this->io_function_sb->message("Los datos no pueden ser eliminados.");
			}	
		}
		else
		{
			$this->io_sql->rollback();
			$this->io_function_sb->message("Los datos no pueden ser eliminados.");
		}
	 }
	 return $lb_borro;
  } 

 //-----------------------------------------Registros Contables y Presupuestarios----------------------------------//
      /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //     Function : get_sc_cuenta
	  //      Alcance : Publico
	  //         Tipo : Object Data Record
	  //  Descripci�n : Funci�n que obtiene el valor de la cuenta contable de configuracion
	  //    Arguments : 
	  //      Retorna : Obtener los registros.
	  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  public function uf_get_sc_cuenta()
	  {
	    $ls_sql    = "SELECT sc_cuenta_ps FROM sps_configuracion WHERE id=1";	
	    $rs_data   = $this->io_sql->select($ls_sql);
		$ls_sc_cuenta = ""; 
		if($rs_data==false)
		{
			$this->io_msg->message("Error en get_sc_cuenta - Liquidacion." );
		}
		elseif($row=$this->io_sql->fetch_row($rs_data))
		{
			 $pa_data=$this->io_sql->obtener_datos($rs_data); 
			 $ls_sc_cuenta=$pa_data["sc_cuenta_ps"][1];
		}
		else { $this->io_function_sb->message("Registro no encontrado en la tabla "); }
		return $ls_sc_cuenta;
	  }  
      /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //     Function : uf_get_sc_cuenta_beneficiario
	  //      Alcance : Publico
	  //         Tipo : Object Data Record
	  //  Descripci�n : Funci�n que obtiene el valor de la cuenta contable de beneficiario
	  //    Arguments : 
	  //      Retorna : Obtener los registros.
	  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  public function uf_get_sc_cuenta_beneficiario($ps_codper)
	  {
	    $ls_sql    = "select sc_cuenta from rpc_beneficiario where codemp='".$this->ls_codemp."' AND ced_bene IN
                      (select cedper from sno_personal where codemp='".$this->ls_codemp."' AND codper='".$ps_codper."')";	
	    $rs_data   = $this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			$this->io_msg->message("Error en get_sc_cuenta_beneficiario - Liquidacion." );
		}
		elseif($row=$this->io_sql->fetch_row($rs_data))
		{
			 $pa_data=$this->io_sql->obtener_datos($rs_data); 
			 $ls_sc_cuenta=$pa_data["sc_cuenta"][1];
		}
		else { $this->io_function_sb->message("Registro no encontrado en la tabla "); }
		return $ls_sc_cuenta;
	  }  
	  
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //     Function : uf_sc_cuenta_deduccion
	  //      Alcance : Publico
	  //         Tipo : Object Data Record
	  //  Descripci�n : Funci�n que obtiene el valor de la cuenta contable de deducciones
	  //    Arguments : 
	  //      Retorna : Obtener los registros.
	  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  public function uf_sc_cuenta_deduccion($ps_codper,$ps_codnom,$ps_numliq,&$pa_datos)
	  {
		$ls_valido=true;
	    $ls_sql    = "select sc_cuenta_ded from sps_dt_liquidacion where codemp='".$this->ls_codemp."' AND codper='".$ps_codper."' AND codnom='".$ps_codnom."' AND numliq='".$ps_numliq."' AND subtotal<0 ";	
	   
	    $rs_data   = $this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			 $this->io_msg->message("Error en uf_sc_cuenta_deduccion - Liquidacion." );
			$ls_valido=false;
		}
		elseif($row=$this->io_sql->fetch_row($rs_data))
		{
			 $pa_data=$this->io_sql->obtener_datos($rs_data);
			 $pa_datos=$this->io_function_sb->uf_sort_array($pa_data); 
		}
		else { $this->io_function_sb->message("Registro no encontrado en la tabla "); }
		return $ls_valido;
	  }  
   	//-----------------------------------------------------------------------------------------------------------------------------------
   	/////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_contabilizar_anticipos_spg 
	//	    Arguments: as_codnom      //  C�digo de N�mina
	//	    		   as_codper       //  codigo del personal
	//	    		   $adt_fecantper  //  Fecha de Anticipo Personal
	//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
	//	  Description: Funci�n que se encarga de procesar la data para la contabilizaci�n del Anticipo
    //     Creado por: Ing. Maria A Roa
	//////////////////////////////////////////////////////////////////////////////////////////////
	function uf_contabilizar_liquidacion_spg($as_codnom,$as_codper,$as_numliq,$adt_fecliq)
	{
		$lb_valido=true;
		$ls_dia = substr($adt_fecliq,0,2);
		$ls_mes = substr($adt_fecliq,3,2);
		$ls_anoantper = substr($adt_fecliq, 8, 2);                              //10/04/2008=>100408
		$ls_ano   = substr($adt_fecliq, 6, 4);
		$ls_comprobante=$ls_dia.$ls_mes.$ls_anoantper.substr($as_codper, 2, 8); // Comprobante
		$li_tipo="L"; 
		$ls_operacion="OC";
		$li_genrecdoc="1";
		$li_estatus = 0;   //No Contabilizado 
		$ls_tipdoc  = "";
		$ls_descripcion=" Liquidacion de Prestaciones Sociales Nro ".$as_numliq.", para la persona ".$as_codper;      // Descripci�n
		$ls_fecliq = $this->io_function->uf_convertirdatetobd($adt_fecliq);
		$lb_valido=$this->getEstructuraPresupuestaria($as_codper,$as_codnom,$as_numliq,$adt_fecliq,$la_ctaspg);
		if ($lb_valido)
		{
 			$li_totrow_det=count($la_ctaspg["codestpro1"]);
 			for($li_d=0;($li_d<$li_totrow_det)&&($lb_valido);$li_d++)
			{
				$ls_programatica = $la_ctaspg["codestpro1"][$li_d].$la_ctaspg["codestpro2"][$li_d].$la_ctaspg["codestpro3"][$li_d].$la_ctaspg["codestpro4"][$li_d].$la_ctaspg["codestpro5"][$li_d];
				$ls_estcla = trim($la_ctaspg["estcla"][$li_d]);
				$ls_cueprefid = trim($la_ctaspg["spg_cuenta"][$li_d]);
				$ls_scg_cuenta = trim($la_ctaspg["sc_cuenta"][$li_d]);
				$ld_totpagliq = $la_ctaspg["monto"][$li_d];
				$this->debe=$this->debe+$ld_totpagliq;
				$lb_valido = $this->uf_insert_contabilizacion_spg($as_codnom,$ls_comprobante,$li_tipo,$ls_programatica,$ls_estcla,
																  $ls_cueprefid,$ls_operacion,$as_codper,$ls_descripcion,$ld_totpagliq,
																  $li_estatus,$li_genrecdoc,$ls_tipdoc);
				if($lb_valido)
				{
					$lb_valido = $this->uf_insert_contabilizacion_scg($as_codnom,$ls_comprobante,$li_tipo,$ls_scg_cuenta,'D',$as_codper,
																	  $ls_descripcion,$ld_totpagliq,$li_estatus,$li_genrecdoc,$ls_tipdoc);
				}
			}
		}		
		 return $lb_valido; 		
		    
	}// end function uf_contabilizar_anyicipos_spg
	//-----------------------------------------------------------------------------------------------------------------------------------
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : uf_insert_contabilizacion_spg
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripci�n : Funci�n que guarda la informaci�n para la contabilizacion 
  //    Arguments : $as_codnom: codigo de nomina
  //                $ls_comprobante: codigo de comprobante
  //				$li_tipo: tipo de transaccion (A:anticipo, L:liquidacion)
  //				$ls_programatica: N� de programatica
  //				$ls_cueprefid: cuenta presupuestaria
  //				$ls_operacion: tipo de operacion (compromete y causa)
  //  				$as_codper: codigo de personal
  //                $ls_descripcion: 
  //				$ld_monant: monto del anticipo
  //				$li_estatus: estatus de contabilizacion (0:no; 1:si) 
  //				$li_genrecdoc: generar rec de documento
  //				$ls_tipdoc:  tipo de documento     
  //      Retorna : 
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
  public function uf_insert_contabilizacion_spg( $ps_codnom,$ps_comprobante,$pi_tipo,$ps_programatica,$ps_estcla,$ps_cueprefid,$ps_operacion,
			                                     $ps_codper,$ps_descripcion,$pd_monliq,$pi_estatus,$pi_genrecdoc,$ps_tipdoc )
  {	
   
    	$lb_guardo = false;  
		$ldt_fecha = "1900-01-01"; //fecha por defecto para fecha de contabilizacion y anulacion
		$ls_codestpro1=substr($ps_programatica,0,25);
		$ls_codestpro2=substr($ps_programatica,25,25);
		$ls_codestpro3=substr($ps_programatica,50,25);
		$ls_codestpro4=substr($ps_programatica,75,25);
		$ls_codestpro5=substr($ps_programatica,100,25);

		$ls_sql    = "INSERT INTO sps_dt_spg(codemp, codnom, codcom, tipo, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, estcla, spg_cuenta, operacion, ced_bene, descripcion, monto, estatus, estrd, codtipdoc, fechaconta, fechaanula) VALUES 
		              ( '".$this->ls_codemp."','" .$ps_codnom."','".$ps_comprobante."','".$pi_tipo."','".$ls_codestpro1."','".$ls_codestpro2."','".$ls_codestpro3."','".$ls_codestpro4."','".$ls_codestpro5."',
					    '".$ps_estcla."','".$ps_cueprefid."','".$ps_operacion."','".$ps_codper."','".$ps_descripcion."','".$pd_monliq."','".$pi_estatus."','".$pi_genrecdoc."','".$ps_tipdoc."','".$ldt_fecha."','".$ldt_fecha."' ) ";
		$li_guardo = $this->io_sql->execute( $ls_sql );
		if ($li_guardo > 0)
		{
			$lb_guardo=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion =" Insert� en la tabla sps_dt_spg codper=".$ps_codper." codnom=".$ps_codnom." comprobante=".$ps_comprobante;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_pro_aprobacionliquidacion.html.php",$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		else
		{
			$this->io_function_sb->message("Los datos no puden ser registrados.");
			$lb_guardo=false;
		}
		 
		return $lb_guardo;
	
	}  //function uf_insert_contabilizacion_spg
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contabilizar_liquidacion_scg($as_codnom,$as_codper,$as_numliq,$adt_fecliq)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_contabilizar_liquidacion_scg 
	//	    Arguments: as_codnom       //  C�digo de N�mina
	//	    		   as_codper       //  codigo del personal
	//	    		   $adt_fecantper  //  Fecha de Anticipo Personal
	//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
	//	  Description: Funci�n que se encarga de procesar la data para la contabilizaci�n del Anticipo
        //     Creado por: Ing. Maria A Roa
	//////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$ls_dia = substr($adt_fecliq,0,2);
		$ls_mes = substr($adt_fecliq,3,2);
		$ls_anoliq = substr($adt_fecliq, 8, 2);                           //10/04/2008=>100408
		$ls_comprobante=$ls_dia.$ls_mes.$ls_anoliq.substr($as_codper, 2, 8); // Comprobante
		$ls_fecliq = $this->io_function->uf_convertirdatetobd($adt_fecliq);
		$ls_cedper  = 
		$li_tipo="L"; 
		$li_genrecdoc="1";
		$li_estatus = 0;   //No Contabilizado 
		$ls_tipdoc  = "";
		$ls_descripcion=" Liquidacion de Prestaciones Sociales Nro ".$as_numliq;                 // Descripci�n
		$lb_valido=$this->getDetalleContableLiq($as_codper,$as_codnom,$as_numliq,$adt_fecliq,$la_ctasc);
		if ($lb_valido)
		{
			$li_totrow_det=count($la_ctasc["sc_cuenta"]);
			for($li_j=0;($li_j<$li_totrow_det)&&($lb_valido);$li_j++)
			{
				$ls_scg_beneficiario = trim($la_ctasc["cuentabene"][$li_j]);
				$ls_scg_cuenta = trim($la_ctasc["sc_cuenta"][$li_j]);
				if ($ls_scg_cuenta!="")
				{
					$ld_totpagliq = abs($la_ctasc["monto"][$li_j]);
					$this->haber=$this->haber+$ld_totpagliq;
					$lb_valido = $this->uf_insert_contabilizacion_scg($as_codnom,$ls_comprobante,$li_tipo,$ls_scg_cuenta,'H',$as_codper,
																	  $ls_descripcion,$ld_totpagliq,$li_estatus,$li_genrecdoc,$ls_tipdoc);	
				}
			}
			$ai_saldo=$this->debe - $this->haber;	
			$lb_valido = $this->uf_insert_contabilizacion_scg($as_codnom,$ls_comprobante,$li_tipo,$ls_scg_beneficiario,'H',$as_codper,
															  $ls_descripcion,$ai_saldo,$li_estatus,$li_genrecdoc,$ls_tipdoc);	
		}		
		 return $lb_valido;	  
	}// end function uf_contabilizar_conceptos_scg
 //-----------------------------------------------------------------------------------------------------------------------------------
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : uf_insert_contabilizacion_scg
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripci�n : Funci�n que guarda la informaci�n para la contabilizacion 
  //    Arguments : $as_codnom: codigo de nomina
  //                $ls_comprobante: codigo de comprobante
  //				$li_tipo: tipo de transaccion (A:anticipo, L:liquidacion)
  //                $ls_sc_cuenta: cuenta contable de prestaciones sociales
  //				$ls_programatica: N� de programatica
  //  				$as_codper: codigo de personal
  //                $ls_descripcion: 
  //				$ld_monant: monto del anticipo
  //				$li_estatus: estatus de contabilizacion (0:no; 1:si) 
  //				$li_genrecdoc: generar rec de documento
  //				$ls_tipdoc:  tipo de documento     
  //      Retorna : 
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
  public function uf_insert_contabilizacion_scg( $ps_codnom,$ps_comprobante,$pi_tipo,$ps_sc_cuenta,$ps_debhab,$ps_codper,
                                                 $ps_descripcion,$pd_monliq,$pi_estatus,$pi_genrecdoc,$ps_tipdoc )
     {	
       	$lb_guardo = false;  
		$ldt_fecha = "1900-01-01";          //fecha por defecto para fecha de contabilizacion y anulacion
		$ls_sql    = "INSERT INTO sps_dt_scg(codemp, codnom, codcom, tipo, sc_cuenta, debhab, ced_bene, descripcion, monto, estatus, estrd, codtipdoc, fechaconta, fechaanula) VALUES 
		              ( '".$this->ls_codemp."','" .$ps_codnom."','".$ps_comprobante."','".$pi_tipo."','".$ps_sc_cuenta."','".$ps_debhab."',
					    '".$ps_codper."','".$ps_descripcion."','".$pd_monliq."','".$pi_estatus."','".$pi_genrecdoc."','".$ps_tipdoc."','".$ldt_fecha."','".$ldt_fecha."' ) ";	
		
		$li_guardo = $this->io_sql->execute( $ls_sql );
		if ($li_guardo > 0)
		{
			$lb_guardo=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion =" Insert� en la tabla sps_dt_scg codper=".$ps_codper." codnom=".$ps_codnom." comprobante=".$ps_comprobante;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_pro_aprobacionliquidacion.html.php",$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		else
		{
			$this->io_function_sb->message("Los datos no puden ser registrados.");
			$lb_guardo=false;
		}
		 
		return $lb_guardo;
	
     }//function uf_insert_contabilizacion_scg
  
      /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //     Function : getEstructuraPresupuestaria
	  //      Alcance : Publico
	  //         Tipo : Object Data Record
	  //  Descripci�n : Funci�n que extrae la informaci�n de la extructura presupuestaria de la Liquidaci�n
	  //    Arguments : $as_codnom: codigo de nomina
	  //  				$as_codper: codigo de personal
	  //                $ls_desuniadm: unidad administrativa
	  //      Retorna : estructura presupuestaria 
	  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	  public function getEstructuraPresupuestaria($as_codper,$as_codnom,$as_numliq,$adt_fecliq,&$pa_datos="")
	  {
		$ls_valido=true;
	    $ls_sql = "SELECT sno_unidadadmin.codestpro1, sno_unidadadmin.codestpro2, sno_unidadadmin.codestpro3, ".
				  "       sno_unidadadmin.codestpro4, sno_unidadadmin.codestpro5, sno_unidadadmin.estcla, ".
				  "       sps_articuloscuentas.spg_cuenta, sum(sps_dt_liquidacion.subtotal) as monto, MAX(spg_cuentas.sc_cuenta) AS sc_cuenta   ".
				  "  FROM sps_articuloscuentas ".
				  " INNER JOIN sps_dt_liquidacion ".
				  "    ON sps_dt_liquidacion.numliq='".$as_numliq."' ".
				  "   AND sps_dt_liquidacion.codemp = sps_articuloscuentas.codemp".
				  "   AND sps_dt_liquidacion.id_art = sps_articuloscuentas.id_art ".
				  " INNER JOIN (sno_personalnomina ".
				  "       INNER JOIN sno_unidadadmin ".
				  "          ON sno_personalnomina.codper = '".$as_codper."' ".
				  "         AND sno_personalnomina.codnom = '".$as_codnom."' ".
				  "   	    AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				  "         AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				  "   	    AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				  "         AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				  "    	    AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				  "         AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm) ".
				  "    ON sno_personalnomina.codper = '".$as_codper."' ".
				  "   AND sno_personalnomina.codnom = '".$as_codnom."' ".
				  "   AND sno_personalnomina.codemp = sps_articuloscuentas.codemp ".
				  "   AND sno_personalnomina.codded = sps_articuloscuentas.codded ".
				  "   AND sno_personalnomina.codtipper = sps_articuloscuentas.codtipper ".
				  " INNER JOIN spg_cuentas ".
				  "    ON (sps_articuloscuentas.codemp = spg_cuentas.codemp".
				  "   AND sps_articuloscuentas.spg_cuenta = spg_cuentas.spg_cuenta) ".
				  "   	    AND sno_unidadadmin.codemp = spg_cuentas.codemp ".
				  "         AND sno_unidadadmin.codestpro1 = spg_cuentas.codestpro1 ".
				  "   	    AND sno_unidadadmin.codestpro2 = spg_cuentas.codestpro2 ".
				  "         AND sno_unidadadmin.codestpro3 = spg_cuentas.codestpro3 ".
				  "    	    AND sno_unidadadmin.codestpro4 = spg_cuentas.codestpro4 ".
				  "    	    AND sno_unidadadmin.codestpro5 = spg_cuentas.codestpro5".
				  "         AND sno_unidadadmin.estcla = spg_cuentas.estcla ".
				  " GROUP BY sno_unidadadmin.codestpro1, sno_unidadadmin.codestpro2, sno_unidadadmin.codestpro3, ".
				  "		     sno_unidadadmin.codestpro4, sno_unidadadmin.codestpro5, sno_unidadadmin.estcla, sps_articuloscuentas.spg_cuenta   ".	
				  " ORDER BY sno_unidadadmin.codestpro1, sno_unidadadmin.codestpro2, sno_unidadadmin.codestpro3, ".
				  "		     sno_unidadadmin.codestpro4, sno_unidadadmin.codestpro5, sno_unidadadmin.estcla, sps_articuloscuentas.spg_cuenta   ";
	    $rs_data   = $this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			 $this->io_msg->message("Error en getEstructuraPresupuestaria - Liquidacion." );
			$ls_valido=false;
		}
		elseif($row=$this->io_sql->fetch_row($rs_data))
		{    
			 $pa_data=$this->io_sql->obtener_datos($rs_data);
			 $pa_datos=$this->io_function_sb->uf_sort_array($pa_data); 
		}
		else 
		{
			$this->io_function_sb->message("Registro no encontrado en la tabla "); 
			$ls_valido=false;
		}
		return $ls_valido;	
	  
	  }
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //     Function : getDetalleContableLiq
	  //      Alcance : Publico
	  //         Tipo : Object Data Record
	  //  Descripci�n : Funci�n que extrae la informaci�n de la extructura presupuestaria de la Liquidaci�n
	  //    Arguments : $as_codnom: codigo de nomina
	  //  				$as_codper: codigo de personal
	  //                $ls_desuniadm: unidad administrativa
	  //      Retorna : 
	  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	  public function getDetalleContableLiq($as_codper,$as_codnom,$as_numliq,$adt_fecliq,&$pa_datos="")
	  {               
		$ls_valido=true;
	    $ls_sql = "SELECT scg_cuentas.sc_cuenta, sum(sps_dt_liquidacion.subtotal) as monto, '' AS cuentabene ".
				  "  FROM sps_dt_liquidacion ".
				  " INNER JOIN  scg_cuentas ".
				  "    ON sps_dt_liquidacion.numliq='".$as_numliq."' ".
				  "   AND sps_dt_liquidacion.codemp = scg_cuentas.codemp".
				  "   AND sps_dt_liquidacion.sc_cuenta_ded = scg_cuentas.sc_cuenta  ".
				  " GROUP BY scg_cuentas.sc_cuenta   ".	
				  " UNION ".
	  			  "SELECT '' AS sc_cuenta, sum(sps_dt_liquidacion.subtotal) as monto, (rpc_beneficiario.sc_cuenta) AS cuentabene ".
				  "  FROM sps_dt_liquidacion ".
				  " INNER JOIN (sno_personal ".
				  "       INNER JOIN rpc_beneficiario ".
				  "          ON sno_personal.codper = '".$as_codper."' ".
				  "   	    AND sno_personal.codemp = rpc_beneficiario.codemp ".
				  "         AND sno_personal.cedper = rpc_beneficiario.ced_bene) ".
				  "    ON sno_personal.codper = '".$as_codper."' ".
				  "   AND sno_personal.codemp = sps_dt_liquidacion.codemp ".
				  "   AND sno_personal.codper = sps_dt_liquidacion.codper ".
				  " GROUP BY rpc_beneficiario.sc_cuenta   ";
	    $rs_data   = $this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			 $this->io_msg->message("Error en getEstructuraPresupuestaria - Liquidacion." );
			$ls_valido=false;
		}
		elseif($row=$this->io_sql->fetch_row($rs_data))
		{    
			 $pa_data=$this->io_sql->obtener_datos($rs_data);
			 $pa_datos=$this->io_function_sb->uf_sort_array($pa_data); 
		}
		else
		{
			$this->io_function_sb->message("Registro no encontrado en la tabla ");
			$ls_valido=false;
		}
		return $ls_valido;	
	  }
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //     Function : getTipoNomina
	  //      Alcance : Publico
	  //         Tipo : Object Data Record
	  //  Descripci�n : Funci�n que extrae la informaci�n del tipo de nomina
	  //    Arguments : 
	  //      Retorna : 
	  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  public function getTipoNomina($as_codnom)
	  {
		$ls_sql   = "SELECT tipnom FROM sno_nomina WHERE codemp='".$this->ls_codemp."' AND codnom='".$as_codnom."' AND espnom='0' ";
		$rs_data  = $this->io_sql->select($ls_sql);
		if ($rs_data===false)
       	{
        	$this->io_mensajes->message("CLASE->Liquidacion M�TODO->getDetalleContableLiq ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
       	}
       	else
       	{
			if($row=$this->io_sql->fetch_row($rs_data))
         	{
            	$li_tipnom=trim($row["tipnom"]);
         	}
			$this->io_sql->free_result($rs_data);	
       	}
		return $li_tipnom;	  	
	  }
      /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //     Function : getConfiguracion
	  //      Alcance : Publico
	  //         Tipo : Object Data Record
	  //  Descripci�n : Funci�n que extrae la informaci�n de la configuracion del modulo
	  //    Arguments : 
	  //      Retorna : 
	  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      public function getConfiguracion(&$pa_array)
	  {
		$lb_valido=false;
		$ls_sql   = "SELECT * FROM sps_configuracion WHERE id='1' ";
		$rs_data  = $this->io_sql->select($ls_sql);
		if ($rs_data===false)
       	{
        	$this->io_mensajes->message("CLASE->Liquidacion M�TODO->getConfiguracion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
       	}
       	else
       	{
			if($row=$this->io_sql->fetch_row($rs_data))
         	{
            	$pa_data=$this->io_sql->obtener_datos($rs_data);
			 	$pa_array=$this->io_function_sb->uf_sort_array($pa_data);
				$lb_valido=true;  
         	}
			$this->io_sql->free_result($rs_data);	
       	}
		return $lb_valido;	  	
	  }
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //     Function : getDetalleLiquidacion
	  //      Alcance : Publico
	  //         Tipo : Object Data Record
	  //  Descripci�n : Funci�n que retorna un arreglo de registros del DAO de los detalles de la Liquidacion
	  //    Arguments : 
	  //                $pa_datos -> Arreglo de datos    
	  //      Retorna : Obtener los registros.
	  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  function getDetalleLiquidacionRep($ps_codper,$ps_codnom,$ps_numliq, &$pa_detalle="" )  
	  {   
	  	$lb_valido = false;
		$ls_sql    = "SELECT desespliq,diapag,subtotal,sc_cuenta_ded FROM sps_dt_liquidacion WHERE codemp='".$this->ls_codemp."' AND codper='".$ps_codper."' AND codnom='".$ps_codnom."' AND numliq='".$ps_numliq."' ORDER BY numespliq ASC" ;
	    $rs_data= $this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			$this->io_function_sb->message("Error en DetalleLiquidacion " );
		}
		elseif($row=$this->io_sql->fetch_row($rs_data))
		{
			 $pa_data  =$this->io_sql->obtener_datos($rs_data);
			 $pa_detalle =$this->io_function_sb->uf_sort_array($pa_data);
			 $lb_valido=true;
		}
		else { $this->io_function_sb->message("Registro no encontrado "); }	
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	  }
} // end class
?>
