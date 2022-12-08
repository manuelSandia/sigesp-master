<?php

class sigesp_srh_c_evaluacion_contratado_obrero
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function sigesp_srh_c_evaluacion_contratado_obrero($path)
	{   
		require_once($path."shared/class_folder/class_sql.php");
		require_once($path."shared/class_folder/class_datastore.php");
		require_once($path."shared/class_folder/class_mensajes.php");
		require_once($path."shared/class_folder/sigesp_include.php");
		require_once($path."shared/class_folder/sigesp_c_seguridad.php");
		require_once($path."shared/class_folder/class_funciones.php");
		$this->io_msg=new class_mensajes();
		$this->io_funcion = new class_funciones();
		$this->la_empresa=$_SESSION["la_empresa"];
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad();
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	//-----------------------------------------------------------------------------------------------------------------------------------	
	function getCodPersonal($as_codper, $as_feceval,&$ao_datos="")       
	{
		$lb_existe=false;
		$as_codper=trim($as_codper);
		$as_feceval=$this->io_funcion->uf_convertirdatetobd($as_feceval);
		$ls_sql = "SELECT feceval ".
				  "  FROM srh_evaluacion_contratado_obrero ".
		          " WHERE codemp='". $this->ls_codemp."'".
		          "   AND codper = '$as_codper' ".
				  "   AND feceval = '$as_feceval'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->evaluacion_contratado  M�TODO->getCodPersonal ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;
				$ls_feceval=$this->io_funcion->uf_convertirfecmostrar($row['feceval']);
			}
			
			$this->io_sql->free_result($rs_data);
		}
		return array($lb_existe,$ls_feceval);
	}	// end function getCodPersonal
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_srh_consultar_items (&$ai_totrows,&$ao_object)
	{
		$lb_valido=true;
		$ls_sql="SELECT codasp, denasp ".
				"  FROM srh_tipo_aspecto ".
				" WHERE codemp = '$this->ls_codemp' ".
				" ORDER BY codasp";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->evaluacion_contratado_obrero M�TODO->uf_srh_consultar_items( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{ 
			$ai_totrows=0;
			while(!$rs_data->EOF) 
			{   			
				$ai_totrows++;
				$ls_codasp=$rs_data->fields["codasp"];
				$ls_denasp=htmlentities  ($rs_data->fields["denasp"]);
									
				$ao_object[$ai_totrows][1]="<input name=txtcodasp".$ai_totrows." type=text id=txtcodasp".$ai_totrows." class=sin-borde size=15 value='".$ls_codasp."' readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtdenasp".$ai_totrows." type=text id=txtdenasp".$ai_totrows." class=sin-borde size=70 value='".$ls_denasp."' readonly>";
				$ao_object[$ai_totrows][3]="<select name=cmbpuntaje".$ai_totrows." id=cmbpuntaje".$ai_totrows.">".
										   "   <option value='0' selected>Deficiente</option>".
										   "   <option value='1'>Bajo</option>".
										   "   <option value='2'>Promedio</option>".
										   "   <option value='3'>Optimo</option>".
										   "   <option value='4'>Excelente</option>".
										   "   <option value='5'>No Observo</option>".
										   "</select>";
				$rs_data->MoveNext();
			}
		}
		return $lb_valido;
	}		
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_srh_guardarevaluacion($ao_evaluacion,$as_operacion="insertar", $aa_seguridad)
	{ 
		$as_codper=$ao_evaluacion->codper;
		$this->io_sql->begin_transaction();
		$ao_evaluacion->feceval=$this->io_funcion->uf_convertirdatetobd($ao_evaluacion->feceval);
		$lb_valido=true;
		if ($as_operacion == "modificar")
		{
			$ls_sql = "UPDATE srh_evaluacion_contratado_obrero ".
					  "   SET carpos = '$ao_evaluacion->carpos' ,  ".
					  "       obseval = '$ao_evaluacion->obseval' ,  ".
			          "       receval = $ao_evaluacion->receval ".
			          " WHERE codper= '$ao_evaluacion->codper' ".
					  "   AND feceval = '$ao_evaluacion->feceval' ".
					  "   AND codemp='".$this->ls_codemp."'" ;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modific� la evaluaci�n contratado obrero del personal".$as_codper;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		else
		{
			$ls_sql = "INSERT INTO srh_evaluacion_contratado_obrero (codemp,codper,feceval,carpos,obseval,receval) ".	  
			          "VALUES ('".$this->ls_codemp."','$ao_evaluacion->codper','$ao_evaluacion->feceval',".
					  "'$ao_evaluacion->carpos' ,'$ao_evaluacion->obseval' , $ao_evaluacion->receval)";
			
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insert� la evaluaci�n contratado obrero del del personal ".$as_codper;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////				
		}
		$lb_guardo = $this->io_sql->execute($ls_sql);
		if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->evaluacion_contratado M�TODO->uf_srh_guardarevaluacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido = $this->guardarDetalles_Evaluacion($ao_evaluacion, $aa_seguridad);
		}
		//Guardamos los items de la Evaluaci�n Psicol�gica
		if ($lb_valido)
		{
			$this->io_sql->commit();
		}
		else
		{
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function guardarDetalles_Evaluacion ($ao_evaluacion, $aa_seguridad)
	{
		//Borramos los registros anteriores 
		$lb_guardo=$this->uf_srh_eliminar_dt_evaluacion($ao_evaluacion->codper, $ao_evaluacion->feceval , $aa_seguridad);
		//Ahora guardamos
		$li_eval = 0;
		while (($li_eval < count($ao_evaluacion->evaluacion)) &&($lb_guardo))
		{
			$lb_guardo = $this->uf_srh_guardar_dt_evaluacion($ao_evaluacion->evaluacion[$li_eval], $aa_seguridad);
			$li_eval++;
		}
		return $lb_guardo;    
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_srh_eliminar_dt_evaluacion($as_codper,$as_feceval, $aa_seguridad)
	{
		$ls_sql = "DELETE ".
				  "  FROM srh_dt_evaluacion_contratado_obrero ".
				  " WHERE codper='$as_codper' ".
				  "   AND feceval ='$as_feceval'   ".
				  "   AND codemp='".$this->ls_codemp."'";
		$lb_borro=$this->io_sql->execute($ls_sql);
		if($lb_borro===false)
		{
			$this->io_msg->message("CLASE->evaluacion_contratado M�TODO->uf_srh_eliminar_dt_evaluacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion ="Elimin� el detalle de evaluaci�n contratado obrero ".$as_codper;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
									$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
									$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////			
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	
  
	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_srh_guardar_dt_evaluacion($ao_evaluacion, $aa_seguridad)
	{ 
		$ao_evaluacion->feceval=$this->io_funcion->uf_convertirdatetobd($ao_evaluacion->feceval);
		$ls_sql = "INSERT INTO srh_dt_evaluacion_contratado_obrero (codemp,feceval,codper,codasp,puntaje) ".	  
		          " VALUES ('".$this->ls_codemp."','$ao_evaluacion->feceval','$ao_evaluacion->codper','$ao_evaluacion->codasp',$ao_evaluacion->puntaje)";
		$lb_guardo = $this->io_sql->execute($ls_sql);
		if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->evaluacion_contratado M�TODO->uf_srh_guardar_dt_evaluacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insert� el detalle de evaluaci�n Contratado Obrero ".$ao_evaluacion->codper;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_srh_buscar_evaluacion($as_codper,$as_fecha1,$as_fecha2)
	{
		$as_fecha1=$this->io_funcion->uf_convertirdatetobd($as_fecha1);
		$as_fecha2=$this->io_funcion->uf_convertirdatetobd($as_fecha2);
		$ls_codperdestino="txtcodper";
		$ls_nomperdestino="txtnomper";
		$ls_fechadestino="txtfecha";
		$ls_carposdestino="txtcarpos";
		$ls_obsevaldestino="txtobseval";
		$ls_recevaldestino="cmbreceval";
		$lb_valido=true;
		$ls_sql="SELECT srh_evaluacion_contratado_obrero.codper,srh_evaluacion_contratado_obrero.feceval,".
				"		srh_evaluacion_contratado_obrero.carpos,srh_evaluacion_contratado_obrero.obseval,srh_evaluacion_contratado_obrero.receval,".
				"		srh_solicitud_empleo.nomsol, srh_solicitud_empleo.apesol  ".
				"  FROM srh_evaluacion_contratado_obrero ".
				"  LEFT JOIN srh_solicitud_empleo ".
				"    ON srh_solicitud_empleo.codemp = srh_evaluacion_contratado_obrero.codemp ".			 
				"   AND srh_solicitud_empleo.cedsol = srh_evaluacion_contratado_obrero.codper ".			 
				" WHERE srh_evaluacion_contratado_obrero.codemp = '".$this->ls_codemp."' ".					 			
				"   AND srh_evaluacion_contratado_obrero.codper like '".$as_codper."' ".					 			
				"   AND srh_evaluacion_contratado_obrero.feceval between  '".$as_fecha1."' AND '".$as_fecha2."'".
				" ORDER BY srh_evaluacion_contratado_obrero.codper";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->evaluacion_contratado M�TODO->uf_srh_buscar_evaluacion( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
			$dom = new DOMDocument('1.0', 'iso-8859-1');
			$team = $dom->createElement('rows');
			$dom->appendChild($team);			
			while(!$rs_data->EOF) 
			{
				$ls_codper=$rs_data->fields["codper"];
				$ls_fecha=$this->io_funcion->uf_formatovalidofecha($rs_data->fields["feceval"]);
				$ls_fecha=$this->io_funcion->uf_convertirfecmostrar($ls_fecha);
				$ls_nomper = trim (htmlentities   ($rs_data->fields["nomsol"]))." ".trim (htmlentities ($rs_data->fields["apesol"]));
				$ls_carpos=($rs_data->fields["carpos"]);
				$ls_obseval= trim (htmlentities  ($rs_data->fields["obseval"]));	
				$li_receval=trim ($rs_data->fields["receval"]);	
				$row_ = $team->appendChild($dom->createElement('row'));
				$row_->setAttribute("id",$ls_codcon);
				$cell = $row_->appendChild($dom->createElement('cell'));   
				$cell->appendChild($dom->createTextNode($ls_fecha." ^javascript:aceptar(\"$ls_codper\", \"$ls_codperdestino\", \"$ls_fecha\", \"$ls_fechadestino\", \"$ls_nomper\", \"$ls_nomperdestino\",".
				" \"$ls_carpos\", \"$ls_carposdestino\",\"$ls_obseval\", \"$ls_obsevaldestino\", \"$li_receval\",\"$ls_recevaldestino\" );^_self"));
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_nomper));												
				$row_->appendChild($cell);								
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_carpos));												
				$row_->appendChild($cell);
				$rs_data->MoveNext();
			}
			return $dom->saveXML();
		}
	} // end function uf_srh_buscar_evaluacion
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_srh_load_dt_evaluacion($as_codper,$as_feceval,&$ai_totrows,&$ao_object)
	{
		$lb_valido=true;
		$as_feceval=$this->io_funcion->uf_convertirdatetobd($as_feceval);
		$ls_sql="SELECT srh_tipo_aspecto.codasp, srh_tipo_aspecto.denasp, srh_dt_evaluacion_contratado_obrero.puntaje ".
				"  FROM srh_dt_evaluacion_contratado_obrero ".
				" INNER JOIN srh_tipo_aspecto ".
				"    ON srh_dt_evaluacion_contratado_obrero.codemp = srh_tipo_aspecto.codemp".
				"   AND srh_dt_evaluacion_contratado_obrero.codasp = srh_tipo_aspecto.codasp".
				" WHERE srh_dt_evaluacion_contratado_obrero.codemp='".$this->ls_codemp."'".
				"   AND srh_dt_evaluacion_contratado_obrero.codper = '".$as_codper."' ".
				"   AND srh_dt_evaluacion_contratado_obrero.feceval = '".$as_feceval."' ".
				" ORDER BY srh_tipo_aspecto.codasp ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->evaluacion_contratado M�TODO->uf_srh_load_dt_evaluacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			$la_puntaje[0]='';
			$la_puntaje[1]='';
			$la_puntaje[2]='';
			$la_puntaje[3]='';
			$la_puntaje[4]='';
			$la_puntaje[5]='';
			while(!$rs_data->EOF)
			{
				$ai_totrows++;
				$ls_codasp=$rs_data->fields["codasp"];
				$ls_denasp=htmlentities  ($rs_data->fields["denasp"]);
				$li_puntaje=$rs_data->fields["puntaje"];
				$la_puntaje[0]='';
				$la_puntaje[1]='';
				$la_puntaje[2]='';
				$la_puntaje[3]='';
				$la_puntaje[4]='';
				$la_puntaje[5]='';
				$la_puntaje[$li_puntaje]='selected';
				$ao_object[$ai_totrows][1]="<input name=txtcodasp".$ai_totrows." type=text id=txtcodasp".$ai_totrows." class=sin-borde size=15 value='".$ls_codasp."' readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtdenasp".$ai_totrows." type=text id=txtdenasp".$ai_totrows." class=sin-borde size=70 value='".$ls_denasp."' readonly>";
				$ao_object[$ai_totrows][3]="<select name=cmbpuntaje".$ai_totrows." id=cmbpuntaje".$ai_totrows.">".
										   "   <option value='0' ".$la_puntaje[0].">Deficiente</option>".
										   "   <option value='1' ".$la_puntaje[1].">Bajo</option>".
										   "   <option value='2' ".$la_puntaje[2].">Promedio</option>".
										   "   <option value='3' ".$la_puntaje[3].">Optimo</option>".
										   "   <option value='4' ".$la_puntaje[4].">Excelente</option>".
										   "   <option value='5' ".$la_puntaje[5].">No Observo</option>".
										   "</select>";
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_srh_load_dt_evaluacion
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_srh_eliminarevaluacion($as_codper, $as_feceval, $aa_seguridad)
	{
		$this->io_sql->begin_transaction();	
		$as_feceval=$this->io_funcion->uf_convertirdatetobd($as_feceval);
		$lb_valido=$this->uf_srh_eliminar_dt_evaluacion($as_codper, $as_feceval, $aa_seguridad);
		if ($lb_valido)
		{
			$ls_sql = "DELETE ".
					  "  FROM srh_evaluacion_contratado_obrero ".
					  " WHERE codper = '$as_codper' ".
					  "   AND feceval = '$as_feceval'  ".
					  "   AND codemp='".$this->ls_codemp."'";
			$lb_borro=$this->io_sql->execute($ls_sql);
			if($lb_borro===false)
			{
				$this->io_msg->message("CLASE->evaluacion_contratado M�TODO->uf_srh_eliminarevaluacion_contratado ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Elimin� la evaluaci�n Psicol�gica del personal ".$as_codper;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
									$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
									$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			}		
			
		}
		if ($lb_valido)
		{
			$this->io_sql->commit();
		}
		else
		{
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	
}// end
?>
