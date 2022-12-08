<?php 
class sigesp_sss_c_usuariosalmacenes
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_sss_c_usuariosalmacenes()
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
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}

	function  uf_sss_select_usuario_almacen($as_codemp,$as_codalm,$as_codusu)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_select_usuario_almacen
		//         Access: public  
		//      Argumento: $as_codemp   //codigo de empresa
		//                 $as_codalm     //codigo de almacen
		//                 $as_codusu     //codigo de usuario
		//	      Returns: Retorna un Booleano
		//    Description: Función que se encarga de verificar si un usuario existe en determinado grupo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005								Fecha Última Modificación : 01/11/2005 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codintper ".
				"  FROM sss_permisos_internos".
				" WHERE codemp = '".$as_codemp."'".
				"   AND  codsis='SIV'".
				"   AND codusu ='".$as_codusu."'".
				"   AND codintper='".$as_codalm."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosalmacenes MÉTODO->uf_sss_select_usuario_almacen ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  // end  function  uf_sss_select_usuario_almacen
	
	function  uf_sss_load_usuarios(&$aa_usuarios)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_usuarios
		//         Access: public  
		//      Argumento: $aa_usuarios      //arreglo de usuarios
		//	      Returns: Retorna un Booleano
		//    Description: Función que carga los datos de los usuarios
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005								Fecha Última Modificación : 01/11/2005 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codusu, nomusu, apeusu".
				"  FROM sss_usuarios".
				" WHERE  codemp ='".$this->ls_codemp."'".
				" ORDER BY nomusu,apeusu,codusu";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosalmacenes MÉTODO->uf_sss_load_grupos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_pos=0;
			$lb_valido=true;
			while(!$rs_data->EOF)
			{
				$aa_usuarios[$li_pos]["codusu"]=$rs_data->fields["codusu"];
				$aa_usuarios[$li_pos]["nomusu"]=$rs_data->fields["nomusu"]." ".$rs_data->fields["apeusu"]." - ".$rs_data->fields["codusu"];
				$li_pos=$li_pos+1;
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_sss_load_grupos


	function  uf_sss_load_disponibles($as_codalm,&$aa_disponibles)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_disponibles
		//         Access: public  
		//      Argumento: as_codalm      // codigo de almacen
		//                 $aa_disponibles //arreglo de usuarios disponibles
		//	      Returns: Retorna un Booleano
		//    Description: Función que carga los datos de los usuarios que estan disponibles para un determinado almacen
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005								Fecha Última Modificación : 01/11/2005 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codalm, nomfisalm".
				"  FROM siv_almacen".
				" WHERE  codalm NOT IN".
				" (SELECT codintper FROM sss_permisos_internos".
				"   WHERE codemp ='".$this->ls_codemp."'".
				"     AND codsis='SIV'".
				" AND codusu ='".$as_codalm."') ".
				" ORDER BY nomfisalm ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosalmacenes MÉTODO->uf_sss_load_disponibles ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_pos=0;
			while(!$rs_data->EOF)
			{
				$aa_disponibles[$li_pos]["codalm"]=$rs_data->fields["codalm"];
				$aa_disponibles[$li_pos]["nomfisalm"]=$rs_data->fields["nomfisalm"];
				$li_pos=$li_pos+1;
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_sss_load_disponibles

	function  uf_sss_load_asignados($as_codalm,&$aa_asignados)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_asignados
		//         Access: public  
		//      Argumento: $as_codemp     //codigo de empresa
		//                 $as_nomgru     //nombre del grupo
		//                 $aa_asignados  //arreglo de usuarios asignados
		//	      Returns: Retorna un Booleano
		//    Description: Función que carga los datos de los usuarios que estan asignados para un determinado grupo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005								Fecha Última Modificación : 01/11/2005 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codalm, nomfisalm".
				"  FROM siv_almacen".
				" WHERE codalm IN".
				" (SELECT codintper FROM sss_permisos_internos".
				"   WHERE codemp ='".$this->ls_codemp."'".
				"     AND codsis='SIV'".
				" AND codusu ='".$as_codalm."') ".
				" ORDER BY nomfisalm ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosalmacenes MÉTODO->uf_sss_load_asignados ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_pos=0;
			while(!$rs_data->EOF)
			{
				$aa_asignados[$li_pos]["codalm"]=$rs_data->fields["codalm"];
				$aa_asignados[$li_pos]["nomfisalm"]=$rs_data->fields["nomfisalm"];
				$li_pos=$li_pos+1;
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_sss_load_asignados


	//---------------------------------------------------------------------------------------------------------------------------
	function  uf_sss_insert_usuario_almacen($as_codemp,$as_codalm,$as_codusu,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_insert_usuario_almacen
		//         Access: public  
		//      Argumento: $as_codemp    // codigo de empresa
		//      		   $as_codalm    // codigo de almacen
		//      		   $as_codusu    // codigo de usuario
		//      		   $aa_seguridad // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: funcion que inserta un usuario en determinado nomina en la tabla sss_permisos_internos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 26/10/2006									Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "INSERT INTO sss_permisos_internos (codemp, codsis, codusu, codintper) ".
				  "     VALUES('".$as_codemp."','SIV','".$as_codusu."','".$as_codalm."')" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->usuariosalmacenes MÉTODO->uf_sss_insert_usuario_almacen ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Relacionó el almacen ".$as_codalm." al usuario ".$as_codusu." Asociado a la empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} // end  function  uf_sss_insert_usuario_almacen
	//---------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------
	function uf_sss_delete_usuario_almacen($as_codemp,$as_codalm,$as_codusu,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_delete_usuario_almacen
		//         Access: public  
		//      Argumento: $as_codemp     // codigo de empresa
		//      		   $as_codcons     // codigo de constante por nomina
		//      		   $as_codusu     // codigo de usuario
		//      		   $aa_seguridad  // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: funcion que elimina un usuario en determinado nomina en la tabla sss_permisos_internos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 27/10/2006									Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql= " DELETE FROM sss_permisos_internos".
				 " WHERE codemp= '".$as_codemp. "'".
				 "   AND codintper= '".$as_codalm. "'".
				 "   AND codusu= '".$as_codusu."'".
				 "   AND codsis='SIV'"; 
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->usuariosalmacenes MÉTODO->uf_sss_delete_usuario_almacen ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó la relacion del almacen ".$as_codalm." al usuario ".$as_codusu." Asociado a la empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////			
			$lb_valido=true;
		}
		return $lb_valido;
	}  // end  function uf_sss_delete_usuario_almacen
	//---------------------------------------------------------------------------------------------------------------------------


}//  end  class sigesp_sss_c_usuarios_grupos

?>
