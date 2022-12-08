<?php 
class sigesp_sss_c_usuariosnivelapr
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_sss_c_usuariosnivelapr()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones.php");
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad;
		$this->io_msg=new class_mensajes();
		$this->io_funcion = new class_funciones();
	}

	function  uf_sss_load_usuarios($as_codemp,&$aa_usuarios)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_usuarios
		//         Access: public  
		//      Argumento: $as_codemp      //codigo de empresa
		//                 $aa_usuarios    //arreglo de usuarios
		//	      Returns: Retorna un Booleano
		//    Description: Función que carga los datos de los usuarios
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 30/10/2006								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT * FROM sss_usuarios".
				" WHERE  codemp ='".$as_codemp."' ".
				" ORDER BY nomusu";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariospresupuesto MÉTODO->uf_sss_load_usuarios ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_pos=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$aa_usuarios[$li_pos]["nomusu"]=$row["nomusu"];  
				$aa_usuarios[$li_pos]["apeusu"]=$row["apeusu"];  
				$aa_usuarios[$li_pos]["codusu"]=$row["codusu"];  
				$li_pos=$li_pos+1;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_sss_load_usuarios
    
	function  uf_sss_load_niveles(&$aa_nivel)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_sistemas
		//         Access: public  
		//      Argumento: $aa_sistemas    // arreglo de sistemas
		//	      Returns: Retorna un Booleano
		//    Description: Función que carga los datos de los sistemas
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 22/05/20008								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql=" SELECT codasiniv, despridoc FROM sigesp_asig_nivel ".
				" ORDER BY codasiniv";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosniveles MÉTODO->uf_sss_load_niveles ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_pos=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$aa_nivel[$li_pos]["codasiniv"]=trim($row["codasiniv"]);  
				$aa_nivel[$li_pos]["despridoc"]=trim($row["despridoc"]);  
				$li_pos=$li_pos+1;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_sss_load_niveles
	
	function  uf_sss_load_nivelesdisponibles($as_codemp,$as_codusu,$as_aperapr,&$aa_disponibles)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_unidadesdisponibles
		//         Access: public  
		//      Argumento: $as_codemp      //codigo de empresa
		//                 $as_codusu      //codigo de usuario
		//                 $aa_disponibles //arreglo de usuarios disponibles
		//	      Returns: Retorna un Booleano
		//    Description: Función que carga los datos de las Unidades Ejecutoras que se encuentran disponibles
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 21/05/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_logusr = $_SESSION["la_logusr"];
		$ls_gestor = $_SESSION["ls_gestor"];
		
			 $ls_sql= "SELECT codasiniv, codniv, despridoc ". 
			 		  "FROM sigesp_asig_nivel ".
					  "WHERE tipproc='".$as_aperapr."' ". 
					  "ORDER BY codasiniv"; //print $ls_sql."<br><br>";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosnivel MÉTODO->uf_sss_load_nivelesdisponibles ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_pos=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ls_codasinivel=$row["codasiniv"];
				$ls_codnivel=$row["codniv"];
				$ls_dennivel=$row["despridoc"];
				$lb_existe=$this->uf_sss_select_nivel_usuario($as_codemp,$ls_codasinivel,$ls_codnivel,$as_codusu,$as_aperapr);
				if(!$lb_existe)
				{
					$aa_disponibles["codasiniv"][$li_pos]=$ls_codasinivel;  
					$aa_disponibles["despridoc"][$li_pos]=$ls_dennivel;  
					$li_pos=$li_pos+1;
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_sss_load_unidadesdisponibles

	function  uf_sss_select_nivel_usuario($as_codemp,$as_codasinivel,$as_codnivel,$as_codusu,$as_aperapr)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_select_usuario_unidad
		//         Access: public  
		//      Argumento: $as_codemp   // codigo de empresa
		//                 $as_codest   // codigo de estructura programatica
		//                 $as_codusu   // codigo de usuario
		//                 $as_sistema  // codigo de sistema
		//	      Returns: Retorna un Booleano
		//    Description: Función que se encarga de verificar si una unidad ejecutora existe para un determinado usuario y sistema
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 21/05/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT codusu FROM sss_niv_usuarios".
				  " WHERE codemp = '".$as_codemp."'".
				  "   AND codasiniv = '".$as_codasinivel."'".
				  "   AND codniv = '".$as_codnivel."'".
				  "   AND codusu ='".$as_codusu."'".
				  "   AND codtipniv ='".$as_aperapr."'";	  
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosniveles MÉTODO->uf_sss_select_nivel_usuario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_sss_select_usuario_unidad

	function  uf_sss_select_derechos_usuarios($as_codemp,$as_codunidad,$as_codusu,$as_codsis)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_select_derechos_usuarios
		//         Access: public  
		//      Argumento: $as_codemp      // codigo de empresa
		//                 $as_codunidad   // codigo de la unidad ejecutora
		//                 $as_codusu      // codigo de usuario
		//                 $as_codsis      // codigo de sistema
		//	      Returns: Retorna un Booleano
		//    Description: Función que se encarga de verificar si una unidad ejecutora existe para un determinado usuario
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 21/05/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT codusu FROM sss_derechos_usuarios".
				  " WHERE codemp = '".$as_codemp."'".
				  "   AND codintper ='".$as_codunidad."'".
				  "   AND codusu ='".$as_codusu."'".
				  "   AND codsis ='".$as_codsis."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosunidad MÉTODO->uf_sss_select_derechos_usuarios ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_sss_select_derechos_usuarios
	
	function  uf_sss_load_nivelesasignados($as_codemp,$as_codusu,$as_aperapr,&$aa_asignados)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_unidadesasignadas
		//         Access: public  
		//      Argumento: $as_codemp     //codigo de empresa
		//                 $as_codusu     //codigo de usuario
		//                 $as_codsis     //codigo de sistema
		//                 $aa_asignados  //arreglo de usuarios asignados
		//	      Returns: Retorna un Booleano
		//    Description: Función que carga los datos de las unidades ejecutoras que estan asignados para un determinado usuario y sistema
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 21/05/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_logusr = $_SESSION["la_logusr"];
		$ls_gestor = $_SESSION["ls_gestor"];
		
			 $ls_sql= "SELECT codasiniv, codniv, despridoc ". 
			 		  "FROM sigesp_asig_nivel ".
					  "WHERE tipproc='".$as_aperapr."' ". 
					  "ORDER BY codasiniv"; //print $ls_sql."<br><br>";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosnivel MÉTODO->uf_sss_load_nivelesasignados ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_pos=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ls_codasinivel=$row["codasiniv"];
				$ls_codnivel=$row["codniv"];
				$ls_dennivel=$row["despridoc"];
				$lb_existe=$this->uf_sss_select_nivel_usuario($as_codemp,$ls_codasinivel,$ls_codnivel,$as_codusu,$as_aperapr);
				if($lb_existe)
				{
					$aa_asignados["codasiniv"][$li_pos]=$ls_codasinivel;  
					$aa_asignados["despridoc"][$li_pos]=$ls_dennivel;  
					$li_pos=$li_pos+1;
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_sss_load_unidadesasignadas
    
	function  uf_sss_nomunidad($as_codemp,$as_coduniadm)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_nomunidad
		//         Access: public  
		//      Argumento: $as_codemp     //codigo de empresa
		//                 $as_coduniadm    //codigo de usuario
		//	      Returns: Retorna un String
		//    Description: Función que busca el nombre asociado a una unidad ejecutora
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 22/05/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql= "SELECT denuniadm ".
				 " FROM spg_unidadadministrativa".
				 " WHERE codemp    =   '".$as_codemp."'".
				 "   AND coduniadm =   '".$as_coduniadm."'"; //print $ls_sql."<br>";
		$ls_denunidad = "";		 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosunidad MÉTODO->uf_sss_load_nomunidad ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
			    $ls_denunidad = $row["denuniadm"];  
			}
			$this->io_sql->free_result($rs_data);
		}
		return $ls_denunidad;
	}  // end  function  uf_sss_load_unidadesasignadas
	
	
	function  uf_sss_insert_nivel_usuario($as_codemp,$as_codasiniv,$as_codniv,$as_codusu,$as_aperapr,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_insert_usuario_unidad
		//         Access: public  
		//      Argumento: $as_codemp    // codigo de empresa
		//      		   $as_codest    // codigo de estructura presupuestaria (codigo interno de permisologia)
		//      		   $as_codusu    // codigo de usuario
		//      		   $as_codsis    // codigo de usuario
		//      		   $aa_seguridad // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: funcion que inserta un usuario en determinado unidad ejecutora en la tabla sss_permisos_internos
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 21/05/2008									Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "INSERT INTO sss_niv_usuarios (codemp, codasiniv, codniv, codusu, codtipniv) ".
				  "     VALUES('".$as_codemp."','".$as_codasiniv."','".$as_codniv."','".$as_codusu."','".$as_aperapr."')" ; 
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{print ($this->io_sql->message);
			$this->io_msg->message("CLASE->usuariosnivel MÉTODO->uf_sss_insert_nivel_usuario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el nivel de aprobación ".$as_codniv." al usuario ".$as_codusu." Asociado a la empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} // end  function  uf_sss_insert_usuario_unidad

	function uf_sss_delete_nivel_usuario($as_codemp,$as_codasiniv,$as_codniv,$as_codusu,$as_aperapr,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_delete_usuario_unidad
		//         Access: public  
		//      Argumento: $as_codemp     // codigo de empresa
		//      		   $as_codest     // codigo de estructura presupuestaria
		//      		   $as_codusu     // codigo de usuario
		//      		   $as_codsis     // codigo de sistema
		//      		   $aa_seguridad  // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: funcion que elimina un usuario en determinada unidad en la tabla sss_permisos_internos
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 21/05/2008									Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql= "DELETE FROM sss_niv_usuarios".
				 " WHERE codemp= '".$as_codemp. "'".
				 "   AND codasiniv= '".$as_codasiniv. "'".
				 "   AND codniv= '".$as_codniv. "'".
				 "   AND codusu= '".$as_codusu."'".
				 "   AND codtipniv='".$as_aperapr."'"; 
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{print ($this->io_sql->message);
			$this->io_msg->message("CLASE->usuariosnivel MÉTODO->uf_sss_delete_nivel_usuario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó el nivel de aprobación ".$as_codniv." al usuario ".$as_codusu." Asociado a la empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////			
			$lb_valido=true;
		}
		return $lb_valido;
	}  // end  function uf_sss_delete_usuario_unidad

	function  uf_sss_load_permisos($as_codemp,$as_codest,$as_codusu,$as_codsis,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_permisos
		//         Access: public  
		//      Argumento: $as_codemp    // codigo de empresa
		//                 $as_codest    // codigo de estructura presupuestaria
		//                 $as_codusu    // codigo de usuario
		//                 $as_codsis    // codigo de sistema
		//                 $aa_seguridad //arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Función que verifica si un usuario tiene definido algun perfil de seguridad en las unidades ejecutoras y sistema.
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 30/10/2006								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="  SELECT nomven,MAX(visible) AS visible,MAX(enabled) AS enabled,MAX(leer) AS leer,MAX(incluir) AS incluir,".
				"         MAX(cambiar)AS cambiar,MAX(eliminar) AS eliminar,MAX(imprimir) AS imprimir,MAX(administrativo) AS administrativo,".
				"         MAX(anular) AS anular,MAX(ejecutar) AS ejecutar".
				"    FROM sss_derechos_usuarios".
				"   WHERE codemp= '".$as_codemp."'".
				"     AND codusu= '".$as_codusu."'".
				"     AND codsis= '".$as_codsis."'".
				"   GROUP BY nomven";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosunidad MÉTODO->uf_sss_load_permisos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_pos=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ls_nomven=$row["nomven"];  
				$li_visible=$row["visible"];  
				$li_enabled=$row["enabled"];  
				$li_leer=$row["leer"];  
				$li_incluir=$row["incluir"];  
				$li_cambiar=$row["cambiar"];  
				$li_eliminar=$row["eliminar"];  
				$li_imprimir=$row["imprimir"];  
				$li_administrador=$row["administrativo"];  
				$li_anular=$row["anular"];  
				$li_ejecutar=$row["ejecutar"];  
				$lb_valido=$this->uf_sss_insert_derecho_usuario($as_codemp,$as_codusu,$as_codsis,$ls_nomven,$li_visible,$li_enabled,
									   					 		$li_leer,$li_incluir,$li_cambiar,$li_eliminar,$li_imprimir,
														 		$li_administrador,$li_anular,$li_ejecutar,$as_codest);
				if(!$lb_valido)
				{break;}
				$li_pos=$li_pos+1;
			}
			if(($li_pos>0)&&($lb_valido))
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion="Actualizó el perfil de seguridad en la Estructura ".$as_codest." al usuario ".$as_codusu.
								 " Asociado a la empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_sss_load_permisos

	function  uf_sss_insert_derecho_usuario($as_codemp,$as_codusu,$as_codsis,$as_nomven,$ai_visible,$ai_enabled,$ai_leer,
											$ai_incluir,$ai_cambiar,$ai_eliminar,$ai_imprimir,$ai_administrador,$ai_anular,
											$ai_ejecutar,$as_codintper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_insert_derecho_usuario
		//         Access: public  
		//      Argumento: $as_codemp        // codigo de empresa
		//      		   $as_codusu        // codigo de usuario
		//      		   $as_codsis        // codigo de sistema
		//      		   $as_nomven        // nombre de la ventana (fisico)
		//      		   $ai_visible       // indica si puede ver o no la pantalla
		//      		   $ai_enabled       // indica si tiene permiso o no a la pantalla
		//      		   $ai_leer          // indica si tiene permiso o no de lectura
		//      		   $ai_incluir       // indica si tiene permiso o no de incluir
		//      		   $ai_cambiar       // indica si tiene permiso o no demodificar
		//      		   $ai_habilitada    // indica si tiene permiso o no 
		//      		   $ai_imprimir      // indica si tiene permiso o no de imprimir
		//      		   $ai_administrador // indica si tiene permiso o no de administrador
		//      		   $ai_anular        // indica si tiene permiso o no de anular
		//      		   $ai_ejecutar      // indica si tiene permiso o no de ejecutar
		//      		   $as_codintper     // codigo interno de permisos
		//    Description: Función que se encarga de otorgar permisos a un usuario en determinada  pantalla
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 27/10/2006									Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "INSERT INTO sss_derechos_usuarios (codemp,codusu,codsis,nomven,visible,enabled,leer,incluir,cambiar,". 
				  "									  eliminar,imprimir,administrativo,anular,ejecutar,codintper) ".
				  "     VALUES('".$as_codemp."','".$as_codusu."','".$as_codsis."','".$as_nomven."',".$ai_visible.",".
				  " 	        ".$ai_enabled.",".$ai_leer.",".$ai_incluir.",".$ai_cambiar.",".$ai_eliminar.",".$ai_imprimir.",".
				  "             ".$ai_administrador.",".$ai_anular.",".$ai_ejecutar.",'".$as_codintper."')" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->usuariosunidad MÉTODO->uf_sss_insert_derecho_usuario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;
		}
		return $lb_valido;
	}  // end  function  uf_sss_insert_derecho_usuario

	function  uf_sss_delete_permisos($as_codemp,$as_codunidad,$as_codusu,$as_codsis,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_delete_permisos
		//         Access: public  
		//      Argumento: $as_codemp     // codigo de empresa
		//                 $as_codusu     // codigo de usuario
		//                 $as_codunidad  // codigo de unidad
		//                 $as_codsis     // codigo de sistema
		//                 $aa_seguridad  // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Función que elimina los permisos de un usuario a alguna nomina en especifico
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 22/05/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="DELETE FROM sss_derechos_usuarios".
			    " WHERE codemp='" .$as_codemp ."'".
			    "   AND codusu='" .$as_codusu ."'".
			    "   AND codsis='" .$as_codsis ."'".
			    "   AND codintper='" .$as_codunidad ."'";		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosunidad MÉTODO->uf_sss_delete_permisos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion="Eliminó el perfil de seguridad en la Unidad ".$as_codunidad." al usuario ".$as_codusu.
							 " Asociado a la empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$lb_valido=true;
		}
		return $lb_valido;
	}  // end  function  uf_sss_delete_permisos

	function uf_sss_select_nivel_asig($as_codemp,$as_codasiniv)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_select_nivel_asig
		//		   Access: private
		//	    Arguments: as_numsol  //  Número de Solicitud
		//				   as_estsol  //  Estatus de la Solicitud
		// 	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que valida el estatus de aprobacion de la solicitud 
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 26/02/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$as_codniv="";
		$ls_sql="SELECT codniv ".
				"  FROM sigesp_asig_nivel ".
				" WHERE codemp='".$as_codemp."' ".
				"   AND codasiniv='".$as_codasiniv."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosnivel MÉTODO->uf_sss_select_nivel_asig ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_existe=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_codniv=$row["codniv"];
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $as_codniv;
	}// end function uf_sss_select_nivel_asig
	//-----------------------------------------------------------------------------------------------------------------------------------	


	
}//  end  class sigesp_sss_c_usuariosnivelapr

?>
