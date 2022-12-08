<?php
require_once("../shared/class_folder/class_mensajes.php");

class sigesp_scg_procesos
{
	var $rs_oaf="";
	var $is_msg_error;
    var $lb_valido=false;
    var $msg;
	var $io_funciones;
 function sigesp_scg_procesos()
 {
	$in=new sigesp_include();
	$this->con=$in->uf_conectar();
    $this->SQL=new class_sql($this->con);
	$this->msg=new class_mensajes();
	$this->io_funciones =  new class_funciones();
	$this->io_int_scg =  new class_sigesp_int_scg();
 }
   
 function uf_select_scg_plantillacuentareporte($as_codemp,$as_cod_report)	
 { 
   //////////////////////////////////////////////////////////////////////  
   // Function:uf_select_scg_plantillacuentareporte
   // Argumentos: string $as_codemp,
   //             string $as_cod_report, 
   //             
   // Return      $li_rtn: integer
   // Descripcion: selecciona las plantillas de las cuenta reporte 
   //////////////////////////////////////////////////////////////////////  
   $li_rtn=0; $li_count=0; $li_row=0;
   $ls_sql="";
   
   $ls_sql =   " SELECT cod_report, sc_cuenta,".   
	           " denominacion, ".  
	           " status, ".
	           " asignado,".  
	           " distribuir,".   
	           " enero,".
	           " febrero,".  
	           " marzo,". 
	           " abril,". 
	           " mayo,". 
	           " junio,". 
	           " julio,". 
	           " agosto,". 
	           " septiembre,".   
	           " octubre,".
	           " noviembre,". 
	           " diciembre,". 
	           " nivel,". 
	           " referencia,".
	           " no_fila,".
	           " tipo,".
	           " cta_res ".
	           " FROM scg_pc_reporte".
		       " WHERE codemp = '".$as_codemp."'AND trim(cod_report) = '".$as_cod_report."'" ;
	
	   	$rs_oaf=$this->SQL->select($ls_sql);
		$li_row=$this->SQL->num_rows($rs_oaf);
	return	$li_row;
 }	
 
  function uf_select_scg_datastore($as_codemp, $as_cod_report )	
 { // Function:uf_select_scg_plantillacuentareporte
   // Argumentos: string $as_codemp,
   //             string $as_cod_report, 
   // Return      $li_rtn: integer
   // Descripcion: selecciona las plantillas de las cuenta reporte para llenar la tabla 
   ///////////////////////////////////////////////////////////////////////////////////// 
   
   $ls_sql =   " SELECT cod_report, sc_cuenta,".   
	           " denominacion, ".  
	           " status, ".
	           " asignado,".  
	           " distribuir,".   
	           " enero,".
	           " febrero,".  
	           " marzo,". 
	           " abril,". 
	           " mayo,". 
	           " junio,". 
	           " julio,". 
	           " agosto,". 
	           " septiembre,".   
	           " octubre,".
	           " noviembre,". 
	           " diciembre,". 
	           " nivel,". 
	           " referencia,".
	           " no_fila,".
	           " tipo,".
	           " cta_res,".
			   " modrep, ".
			   " saldo_real_ant, ".
			   " saldo_apro, ".
			   " saldo_mod ".
	           " FROM scg_pc_reporte".
		       " WHERE codemp = '".$as_codemp."'AND trim(cod_report) = '".$as_cod_report."'
			     ORDER BY no_fila" ; ////print $ls_sql;
	   	$rs_oaf=$this->SQL->select($ls_sql);
	return	$rs_oaf;
 }

  function uf_select_datastore_trim($as_codemp, $as_cod_report)	
 { // Function:uf_select_datastore_trim
   // Argumentos: string $as_codemp,
   //             string $as_cod_report, 
   // Return      $li_rtn: integer
   // Descripcion: selecciona las plantillas de las cuenta reporte para llenar la tabla 
   ///////////////////////////////////////////////////////////////////////////////////// 
   
   $ls_sql =   " SELECT cod_report, sc_cuenta,denominacion,status,asignado,distribuir,enero,febrero,
	           			marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,
	           			nivel,referencia,no_fila,tipo,cta_res,modrep,saldo_real_ant,saldo_apro,saldo_mod
	               FROM scg_pc_reporte
		          WHERE codemp ='".$as_codemp."' 
				    AND trim(cod_report) ='".$as_cod_report."' 
					AND (modrep='3' OR  modrep='0') 
				 ORDER BY no_fila ";
	$rs_oaf=$this->SQL->select($ls_sql);
	return	$rs_oaf;
 }
	
  function uf_select_datastore_mensual($as_codemp, $as_cod_report)	
 { // Function:uf_select_datastore_mensual
   // Argumentos: string $as_codemp,
   //             string $as_cod_report, 
   // Return      $li_rtn: integer
   // Descripcion: selecciona las plantillas de las cuenta reporte para llenar la tabla 
   ///////////////////////////////////////////////////////////////////////////////////// 
   
   $ls_sql =   " SELECT cod_report, sc_cuenta,denominacion,status,asignado,distribuir,enero,febrero,marzo,abril,mayo,junio,julio,
                        agosto,septiembre,octubre,noviembre,diciembre,nivel,referencia,no_fila,tipo,cta_res,modrep,saldo_real_ant,saldo_apro,saldo_mod
	               FROM scg_pc_reporte
		          WHERE codemp ='".$as_codemp."' 
				    AND trim(cod_report) ='".$as_cod_report."'
					 ORDER BY no_fila "; 
				
   $rs_data = $this->SQL->select($ls_sql);
   if ($rs_data==false)
       {
	     return false;
	   }					
   return $rs_data;
 }
 
  function uf_cargar_txt_inversiones_0714($as_codemp)	
 {  ////////////////////////////////////////////////////////////////////////////////////
 	//	Function:  uf_cargar_txt_inversiones_0714
	//	Access:  public
	//	Description:  Este m�todo accesa la informaci�n de las cuentas de inversion
	//                y procede a insertarla en la tabla SCG_PC_Reporte
	////////////////////////////////////////////////////////////////////////////////////
	
	$ls_linea=""; $ls_cadena_linea=""; $ls_cuenta=""; $ls_denominacion=""; $ls_denominacion_plan=""; $li_no_fila="";
	$ls_codreport=""; $ls_status=""; $ls_ref=""; $ls_tipo=""; $ls_cta_res=""; $ls_sql="";
	$ldc_asignado=0; $ldc_ene=0; $ldc_feb=0; $ldc_mar=0; $ldc_abr=0; $ldc_may=0; $ldc_jun=0;
	$ldc_jul=0; $ldc_ago=0; $ldc_sep=0; $ldc_oct=0; $ldc_nov=0; $ldc_dic=0;
	$li_NumFile=0; $li_Read_Result=0; $li_valid=0; $li_distribuir=0; $li_nivel=0; $li_no_fila=0; $li_exec=0; $li_rtn=0 ;
	$lb_valido=true;
	       
		    $ls_archivo = file("inversiones_0714.txt");
            $ls_linea = count($ls_archivo);
		    
			for ($i=0; $i < $ls_linea; $i++)
			{
	           // Reemplazar por el procesamiento
	           $ls_cadena_linea = $ls_archivo[$i];
	           $ls_codreport    = substr($ls_cadena_linea,0,5);	    //5
			   $ls_cuenta       = substr($ls_cadena_linea,5,25);    //25
			   $ls_denominacion = substr($ls_cadena_linea,30,100);  //100
			   $ls_status       = substr($ls_cadena_linea,130,1); //1
			   $ldc_asignado    = substr($ls_cadena_linea,131,1); //1  
			   $li_distribuir   = substr($ls_cadena_linea,132,1); //1 
			   $ldc_ene         = substr($ls_cadena_linea,133,1); //1  
			   $ldc_feb         = substr($ls_cadena_linea,134,1); //1  
			   $ldc_mar         = substr($ls_cadena_linea,135,1); //1  
			   $ldc_abr         = substr($ls_cadena_linea,136,1); //1  
			   $ldc_may         = substr($ls_cadena_linea,137,1); //1  
			   $ldc_jun         = substr($ls_cadena_linea,138,1); //1  
			   $ldc_jul         = substr($ls_cadena_linea,139,1); //1  
			   $ldc_ago         = substr($ls_cadena_linea,140,1); //1  
			   $ldc_sep         = substr($ls_cadena_linea,141,1); //1  
			   $ldc_oct         = substr($ls_cadena_linea,142,1); //1  
			   $ldc_nov         = substr($ls_cadena_linea,143,1); //1  
			   $ldc_dic         = substr($ls_cadena_linea,144,1); //1  
			   $li_nivel        = substr($ls_cadena_linea,145,1); //1  
			   $ls_ref          = substr($ls_cadena_linea,146,25); //25
			   $li_no_fila++;  //3	
			   $ls_tipo         = ""; //1
			   $ls_cta_res      = ""; 	
			   $ls_codreport    = str_replace("-", " ", $ls_codreport);
			   $ls_cuenta       = str_replace("-", " ", $ls_cuenta);
			   $ls_denominacion = str_replace("-", " ", $ls_denominacion);
	           $ls_ref          = str_replace("-", " ", $ls_ref);
	           $ls_modrep		= "0";  //modalidad mensual 
			   
			     ///-------------------------------------------------------------------------------------------------------------------
//		 		  $ldc_eneaux         = substr($ls_cadena_linea,133,1); //1  
//		  		  $ldc_febaux         = substr($ls_cadena_linea,134,1); //1  
//		   		  $ldc_maraux         = substr($ls_cadena_linea,135,1); //1  
//		  		  $ldc_abraux         = substr($ls_cadena_linea,136,1); //1  
//		          $ldc_mayaux         = substr($ls_cadena_linea,137,1); //1  
//		          $ldc_junaux         = substr($ls_cadena_linea,138,1); //1  
//		          $ldc_julaux         = substr($ls_cadena_linea,139,1); //1  
//		          $ldc_agoaux         = substr($ls_cadena_linea,140,1); //1  
//		          $ldc_sepaux         = substr($ls_cadena_linea,141,1); //1  
//		          $ldc_octaux         = substr($ls_cadena_linea,142,1); //1  
//		          $ldc_novaux         = substr($ls_cadena_linea,143,1); //1  
//		          $ldc_dicaux         = substr($ls_cadena_linea,144,1); //1 
		   //----------------------------------------------------------------------------------------------------------------------    	              			              			
				  //INSERT
				   
				   $ls_sql= " INSERT INTO scg_pc_reporte (codemp,cod_report,sc_cuenta,denominacion,status,asignado,distribuir,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,nivel,referencia,no_fila,tipo,cta_res,modrep)".
				   " VALUES('".trim($as_codemp)."','".trim($ls_codreport)."','".trim($ls_cuenta)."','".trim($ls_denominacion)."','".$ls_status."',".$ldc_asignado.",".$li_distribuir.",".$ldc_ene.",".$ldc_feb.",".$ldc_mar.",".$ldc_abr.",".$ldc_may.",".$ldc_jun.",".$ldc_jul.",".$ldc_ago.",".$ldc_sep.",".$ldc_oct.",".$ldc_nov.",".$ldc_dic.",".$li_nivel.",'".trim($ls_ref)."',"
				               .$li_no_fila.",'".$ls_tipo."','".$ls_cta_res."','".$ls_modrep."');" ;	              			
				 				    
					$rs_data = $this->SQL->execute($ls_sql);						
					if ($rs_data===false)
					   {
						 $is_msg_error = "Error en m�todo uf_cargar_txt_inversiones ";
						 $lb_valido = false;
						 $li_rtn=0;
					   }
					else
					   {
						 $li_rtn=1;
					   }			
	}
		
	if ($li_rtn==1)
	{
	    $this->SQL->commit();
		$is_msg_error = "Cuentas de Inversi�n cargadas..";
		$lb_valido = true;
	}
	else
	{
	    $this->SQL->rollback();
		$is_msg_error = " Cuentas de Inversi�n no cargadas.." ;
		$lb_valido = false;
	}	
	return $lb_valido;	
 } // fin de uf_cargar_txt_inversiones_0714
	
 function uf_cargar_txt_inversiones($as_codemp)	
 {  ////////////////////////////////////////////////////////////////////////////////////
 	//	Function:  uf_cargar_txt_inversiones
	//	Access:  public
	//	Description:  Este m�todo accesa la informaci�n de las cuentas de inversion
	//                y procede a insertarla en la tabla SCG_PC_Reporte
	////////////////////////////////////////////////////////////////////////////////////
	
	$ls_linea=""; $ls_cadena_linea=""; $ls_cuenta=""; $ls_denominacion=""; $ls_denominacion_plan=""; $li_no_fila="";
	$ls_codreport=""; $ls_status=""; $ls_ref=""; $ls_tipo=""; $ls_cta_res=""; $ls_sql="";
	$ldc_asignado=0; $ldc_ene=0; $ldc_feb=0; $ldc_mar=0; $ldc_abr=0; $ldc_may=0; $ldc_jun=0;
	$ldc_jul=0; $ldc_ago=0; $ldc_sep=0; $ldc_oct=0; $ldc_nov=0; $ldc_dic=0;
	$li_NumFile=0; $li_Read_Result=0; $li_valid=0; $li_distribuir=0; $li_nivel=0; $li_no_fila=0; $li_exec=0; $li_rtn=0 ;
	$lb_valido=true;
	       
		    $ls_archivo = file("inversiones.txt");
            $ls_linea = count($ls_archivo);
		    
			for ($i=0; $i < $ls_linea; $i++)
			{
	           // Reemplazar por el procesamiento
	           $ls_cadena_linea = $ls_archivo[$i];
	           $ls_codreport    = substr($ls_cadena_linea,0,5);	    //5
			   $ls_cuenta       = substr($ls_cadena_linea,5,25);    //25
			   $ls_denominacion = substr($ls_cadena_linea,30,100);  //100
			   $ls_status       = substr($ls_cadena_linea,130,1); //1
			   $ldc_asignado    = substr($ls_cadena_linea,131,1); //1  
			   $li_distribuir   = substr($ls_cadena_linea,132,1); //1 
			   $ldc_ene         = substr($ls_cadena_linea,133,1); //1  
			   $ldc_feb         = substr($ls_cadena_linea,134,1); //1  
			   $ldc_mar         = substr($ls_cadena_linea,135,1); //1  
			   $ldc_abr         = substr($ls_cadena_linea,136,1); //1  
			   $ldc_may         = substr($ls_cadena_linea,137,1); //1  
			   $ldc_jun         = substr($ls_cadena_linea,138,1); //1  
			   $ldc_jul         = substr($ls_cadena_linea,139,1); //1  
			   $ldc_ago         = substr($ls_cadena_linea,140,1); //1  
			   $ldc_sep         = substr($ls_cadena_linea,141,1); //1  
			   $ldc_oct         = substr($ls_cadena_linea,142,1); //1  
			   $ldc_nov         = substr($ls_cadena_linea,143,1); //1  
			   $ldc_dic         = substr($ls_cadena_linea,144,1); //1  
			   $li_nivel        = substr($ls_cadena_linea,145,1); //1  
			   $ls_ref          = substr($ls_cadena_linea,146,25); //25
			   $li_no_fila++;  //3	
			   $ls_tipo         = ""; //1
			   $ls_cta_res      = ""; 	
			   $ls_codreport    = str_replace("-", " ", $ls_codreport);
			   $ls_cuenta       = str_replace("-", " ", $ls_cuenta);
			   $ls_denominacion = str_replace("-", " ", $ls_denominacion);
	           $ls_ref          = str_replace("-", " ", $ls_ref);
	           $ls_modrep		= "0";  //modalidad mensual 	
			   
			     ///-------------------------------------------------------------------------------------------------------------------
//		 		  $ldc_eneaux         = substr($ls_cadena_linea,133,1); //1  
//		  		  $ldc_febaux         = substr($ls_cadena_linea,134,1); //1  
//		   		  $ldc_maraux         = substr($ls_cadena_linea,135,1); //1  
//		  		  $ldc_abraux         = substr($ls_cadena_linea,136,1); //1  
//		          $ldc_mayaux         = substr($ls_cadena_linea,137,1); //1  
//		          $ldc_junaux         = substr($ls_cadena_linea,138,1); //1  
//		          $ldc_julaux         = substr($ls_cadena_linea,139,1); //1  
//		          $ldc_agoaux         = substr($ls_cadena_linea,140,1); //1  
//		          $ldc_sepaux         = substr($ls_cadena_linea,141,1); //1  
//		          $ldc_octaux         = substr($ls_cadena_linea,142,1); //1  
//		          $ldc_novaux         = substr($ls_cadena_linea,143,1); //1  
//		          $ldc_dicaux         = substr($ls_cadena_linea,144,1); //1 
		   //----------------------------------------------------------------------------------------------------------------------    	              			              			
				  //INSERT
				   
				   $ls_sql= " INSERT INTO scg_pc_reporte (codemp,cod_report,sc_cuenta,denominacion,status,asignado,distribuir,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,nivel,referencia,no_fila,tipo,cta_res,modrep)".
				   " VALUES('".trim($as_codemp)."','".trim($ls_codreport)."','".trim($ls_cuenta)."','".trim($ls_denominacion)."','".$ls_status."',".$ldc_asignado.",".$li_distribuir.",".$ldc_ene.",".$ldc_feb.",".$ldc_mar.",".$ldc_abr.",".$ldc_may.",".$ldc_jun.",".$ldc_jul.",".$ldc_ago.",".$ldc_sep.",".$ldc_oct.",".$ldc_nov.",".$ldc_dic.",".$li_nivel.",'".trim($ls_ref)."',"
				               .$li_no_fila.",'".$ls_tipo."','".$ls_cta_res."','".$ls_modrep."');" ;
				    
				 $rs_data = $this->SQL->execute($ls_sql);						
				 if ($rs_data===false)
				    {
						$is_msg_error = "Error en m�todo uf_cargar_txt_inversiones ";
						$lb_valido = false;
						$li_rtn=0;
					}
					else
					{
						$li_rtn=1;
					}
			
	}
		
	if ($li_rtn==1)
	{
	    $this->SQL->commit();
		$is_msg_error = "Cuentas de Inversi�n cargadas..";
		$lb_valido = true;
	}
	else
	{
	    $this->SQL->rollback();
		$is_msg_error = " Cuentas de Inversi�n no cargadas.." ;
		$lb_valido = false;
	}	
	return $lb_valido;	
 } // fin de uf_cargar_txt_inversiones	
	
	
 
 
 
 function uf_cargar_txt_balancegeneral($as_codemp)    
 {  ////////////////////////////////////////////////////////////////////////////////////////////////
     //    Function:  uf_cargar_txt_balancegeneral
    //    Access:  public
    //    Description:  Este m�todo accesa la informaci�n de las cuentas de balance general
    //                y procede a insertarla en la tabla SCG_PC_Reporte
    //               
    /////////////////////////////////////////////////////////////////////////////////////////////////
    $ls_linea=0; $ls_cadena_linea=""; $ls_cuenta=""; $ls_denominacion=""; $ls_denominacion_plan=""; $ls_no_fila="";
    $ls_codreport=""; $ls_status=""; $ls_ref=""; $ls_tipo=""; $ls_cta_res=""; $ls_sql="";
    $ldc_asignado=0; $ldc_ene=0; $ldc_feb=0; $ldc_mar=0; $ldc_abr=0; $ldc_may=0; $ldc_jun=0;
    $ldc_jul=0; $ldc_ago=0; $ldc_sep=0; $ldc_oct=0; $ldc_nov=0; $ldc_dic=0;
    $li_NumFile=0; $li_Read_Result=0; $li_valid=0; $li_distribuir=0; $li_nivel=0; $li_no_fila=0; $li_exec=0; //$li_rtn=0;
    $ldc_saldo_real_ant=0; $ldc_saldo_aprobado=0; $ldc_saldo_modificado=0;
    $lb_valido=true;
    
       
        $ls_archivo = file("balance_general.txt");
        $ls_linea = count($ls_archivo);
        for ($i=0; $i < $ls_linea; $i++)   
        {
           // Reemplazar por el procesamiento
           $ls_cadena_linea = $ls_archivo[$i];
           $ls_codreport    = substr($ls_cadena_linea,0,5);        //5
           $ls_cuenta       = substr($ls_cadena_linea,5,25);    //25
           $ls_denominacion = substr($ls_cadena_linea,30,100);  //100
           $ls_status       = substr($ls_cadena_linea,130,1); //1
           $ldc_asignado    = substr($ls_cadena_linea,131,1); //1  
           $li_distribuir   = substr($ls_cadena_linea,132,1); //1 
           $ldc_ene         = substr($ls_cadena_linea,133,1); //1  
           $ldc_feb         = substr($ls_cadena_linea,134,1); //1  
           $ldc_mar         = substr($ls_cadena_linea,135,1); //1  
           $ldc_abr         = substr($ls_cadena_linea,136,1); //1  
           $ldc_may         = substr($ls_cadena_linea,137,1); //1  
           $ldc_jun         = substr($ls_cadena_linea,138,1); //1  
           $ldc_jul         = substr($ls_cadena_linea,139,1); //1  
           $ldc_ago         = substr($ls_cadena_linea,140,1); //1  
           $ldc_sep         = substr($ls_cadena_linea,141,1); //1  
           $ldc_oct         = substr($ls_cadena_linea,142,1); //1  
           $ldc_nov         = substr($ls_cadena_linea,143,1); //1  
           $ldc_dic         = substr($ls_cadena_linea,144,1); //1  
           $li_nivel        = substr($ls_cadena_linea,145,1); //1  
           $ls_ref          = substr($ls_cadena_linea,146,25); //25
           $li_no_fila++;  //3    
           $ls_tipo         = ""; //1
           $ls_cta_res      = "";     
           $ls_codreport    = str_replace("-", " ", $ls_codreport);
           $ls_cuenta       = str_replace("-", " ", $ls_cuenta);
           $ls_denominacion = str_replace("-", " ", $ls_denominacion);
           $ls_ref          = str_replace("-", " ", $ls_ref);
           $ls_modrep        = "0"; 
           ///-------------------------------------------------------------------------------------------------------------------
//           $ldc_eneaux         = substr($ls_cadena_linea,133,1); //1  
//           $ldc_febaux         = substr($ls_cadena_linea,134,1); //1  
//           $ldc_maraux         = substr($ls_cadena_linea,135,1); //1  
//           $ldc_abraux         = substr($ls_cadena_linea,136,1); //1  
//           $ldc_mayaux         = substr($ls_cadena_linea,137,1); //1  
//           $ldc_junaux         = substr($ls_cadena_linea,138,1); //1  
//           $ldc_julaux         = substr($ls_cadena_linea,139,1); //1  
//           $ldc_agoaux         = substr($ls_cadena_linea,140,1); //1  
//           $ldc_sepaux         = substr($ls_cadena_linea,141,1); //1  
//           $ldc_octaux         = substr($ls_cadena_linea,142,1); //1  
//           $ldc_novaux         = substr($ls_cadena_linea,143,1); //1  
//           $ldc_dicaux         = substr($ls_cadena_linea,144,1); //1 
           //----------------------------------------------------------------------------------------------------------------------                                  
          //INSERT   $ldc_saldo_real_ant=0; $ldc_saldo_aprobado=0; $ldc_saldo_modificado=0;
          
           
            
          $ls_sql= " INSERT INTO scg_pc_reporte (codemp,cod_report,sc_cuenta,denominacion,status,asignado,distribuir,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,nivel,referencia,no_fila,tipo,cta_res,modrep,saldo_real_ant,saldo_apro,saldo_mod)".
                   " VALUES('".trim($as_codemp)."','".trim($ls_codreport)."','".trim($ls_cuenta)."','".trim($ls_denominacion)."','".$ls_status."',".$ldc_asignado.",".$li_distribuir.",".$ldc_ene.",".$ldc_feb.",".$ldc_mar.",".$ldc_abr.",".$ldc_may.",".$ldc_jun.",".$ldc_jul.",".$ldc_ago.",".$ldc_sep.",".$ldc_oct.",".$ldc_nov.",".$ldc_dic.",".$li_nivel.",'".trim($ls_ref)."',"
                               .$li_no_fila.",'".$ls_tipo."','".$ls_cta_res."','".$ls_modrep."'".",'".$ldc_saldo_real_ant."','".$ldc_saldo_aprobado."','".$ldc_saldo_modificado."');" ;
             $rs_data = $this->SQL->execute($ls_sql);                        
            if ($rs_data===false)
               {
                 $is_msg_error = "Error en m�todo uf_cargar_txt_balancegeneral ";
                 $lb_valido = false;
                 $li_rtn=0;
               }
            else
               {
                 $li_rtn=1;
                }            
        }
           if ($li_rtn==1)
        {
          $this->SQL->commit();
          $lb_valido = true;
          $is_msg_error = "Cuentas de Balance General cargadas.."    ;
        }
    
        else
       {
          $this->SQL->rollback();
          $lb_valido = false;
          $is_msg_error = " Cuentas de Balance General no cargadas.." ;
       }
    return $lb_valido;    
 } // fin de uf_cargar_txt_balancegeneral
 
 
 function uf_cargar_txt_ctaahorroinversion($as_codemp)	
 {  ////////////////////////////////////////////////////////////////////////////////////////////
 	//	Function:  uf_cargar_txt_ctaahorroinversion
	//	Access:  public
	//	Description:  Este m�todo accesa la informaci�n de las cuentas de ahorro inversion
	//                y procede a insertarla en la tabla SCG_PC_Reporte
	//               
	////////////////////////////////////////////////////////////////////////////////////////////
	$ls_linea=""; $ls_cadena_linea=""; $ls_cuenta=""; $ls_denominacion=""; $ls_denominacion_plan=""; $ls_no_fila="";
	$ls_codreport=""; $ls_status=""; $ls_ref=""; $ls_tipo=""; $ls_cta_res=""; $ls_sql="";
	$ldc_asignado=0; $ldc_ene=0; $ldc_feb=0; $ldc_mar=0; $ldc_abr=0; $ldc_may=0; $ldc_jun=0;
	$ldc_jul=0; $ldc_ago=0; $ldc_sep=0; $ldc_oct=0; $ldc_nov=0; $ldc_dic=0;
	$li_NumFile=0; $li_Read_Result=0; $li_valid=0; $li_distribuir=0; $li_nivel=0; $li_no_fila=0; $li_exec=0; $li_rtn=0 ;
	$lb_valido=true;
	   
            $ls_archivo = file("cuenta_ahorro_inversion.txt");
			$ls_linea =  count($ls_archivo);	       	       
	        for ($i=0; $i< $ls_linea; $i++)
	        {
	              // Reemplazar por el procesamiento
	              $ls_cadena_linea = $ls_archivo[$i];
	              $ls_codreport    = substr($ls_cadena_linea,0,5);	    //5
				  $ls_cuenta       = substr($ls_cadena_linea,5,25);    //25
				  $ls_denominacion = substr($ls_cadena_linea,30,100);  //100
				  $ls_status       = substr($ls_cadena_linea,130,1); //1
				  $ldc_asignado    = substr($ls_cadena_linea,131,1); //1  
				  $li_distribuir   = substr($ls_cadena_linea,132,1);   //1 
				  $ldc_ene         = substr($ls_cadena_linea,133,1); //1  
				  $ldc_feb         = substr($ls_cadena_linea,134,1); //1  
				  $ldc_mar         = substr($ls_cadena_linea,135,1); //1  
				  $ldc_abr         = substr($ls_cadena_linea,136,1); //1  
				  $ldc_may         = substr($ls_cadena_linea,137,1); //1  
				  $ldc_jun         = substr($ls_cadena_linea,138,1); //1  
				  $ldc_jul         = substr($ls_cadena_linea,139,1); //1  
				  $ldc_ago         = substr($ls_cadena_linea,140,1); //1  
				  $ldc_sep         = substr($ls_cadena_linea,141,1); //1  
				  $ldc_oct         = substr($ls_cadena_linea,142,1); //1  
				  $ldc_nov         = substr($ls_cadena_linea,143,1); //1  
				  $ldc_dic         = substr($ls_cadena_linea,144,1); //1  
				  $li_nivel        = substr($ls_cadena_linea,145,1); //1  
				  $ls_ref          = substr($ls_cadena_linea,146,25); //25
				  $li_no_fila++;  //3	
				  $ls_tipo         = ""; //1
				  $ls_cta_res      = ""; 	
				  $ls_codreport    = str_replace("-", " ", $ls_codreport);
				  $ls_cuenta       = str_replace("-", " ", $ls_cuenta);
				  $ls_denominacion = str_replace("-", " ", $ls_denominacion);
	              $ls_ref          = str_replace("-", " ", $ls_ref);
	              $ls_modrep	   = "0";	
		    ///-------------------------------------------------------------------------------------------------------------------
//		 		  $ldc_eneaux         = substr($ls_cadena_linea,133,1); //1  
//		  		  $ldc_febaux         = substr($ls_cadena_linea,134,1); //1  
//		   		  $ldc_maraux         = substr($ls_cadena_linea,135,1); //1  
//		  		  $ldc_abraux         = substr($ls_cadena_linea,136,1); //1  
//		          $ldc_mayaux         = substr($ls_cadena_linea,137,1); //1  
//		          $ldc_junaux         = substr($ls_cadena_linea,138,1); //1  
//		          $ldc_julaux         = substr($ls_cadena_linea,139,1); //1  
//		          $ldc_agoaux         = substr($ls_cadena_linea,140,1); //1  
//		          $ldc_sepaux         = substr($ls_cadena_linea,141,1); //1  
//		          $ldc_octaux         = substr($ls_cadena_linea,142,1); //1  
//		          $ldc_novaux         = substr($ls_cadena_linea,143,1); //1  
//		          $ldc_dicaux         = substr($ls_cadena_linea,144,1); //1 
		   //----------------------------------------------------------------------------------------------------------------------    	              			              			
				  //INSERT
				   
				   $ls_sql= " INSERT INTO scg_pc_reporte (codemp,cod_report,sc_cuenta,denominacion,status,asignado,distribuir,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,nivel,referencia,no_fila,tipo,cta_res,modrep)".
				   " VALUES('".trim($as_codemp)."','".trim($ls_codreport)."','".trim($ls_cuenta)."','".trim($ls_denominacion)."','".$ls_status."',".$ldc_asignado.",".$li_distribuir.",".$ldc_ene.",".$ldc_feb.",".$ldc_mar.",".$ldc_abr.",".$ldc_may.",".$ldc_jun.",".$ldc_jul.",".$ldc_ago.",".$ldc_sep.",".$ldc_oct.",".$ldc_nov.",".$ldc_dic.",".$li_nivel.",'".trim($ls_ref)."',"
				               .$li_no_fila.",'".$ls_tipo."','".$ls_cta_res."','".$ls_modrep."');" ;
				                                                                                                                                                                                              
				 $rs_data = $this->SQL->execute($ls_sql);						
				 if ($rs_data===false)
				    {
					  $is_msg_error = "Error en m�todo uf_cargar_txt_ctaahorroinversion ";
					  $lb_valido = false;
					  $li_rtn=0;
					}
					else
					{
						$li_rtn=1;
					}
					
	}
	if ($li_rtn==1)
	{
	    $this->SQL->commit();
		$lb_valido = true;
		$is_msg_error = "Cuentas de Ahorro Inversion cargadas.."	;
	}
	
	else
	{
	   $this->SQL->rollback();
	   $lb_valido = false;
	   $is_msg_error = " Cuentas de Ahorro Inversion no cargadas.." ;
	}
	return $lb_valido;	
 } // fin de uf_cargar_txt_catahorroinversion
 	
///-------------------------------------------------------------------------------------------------------------------------
 
 function uf_cargar_origen_y_aplic_fondos_txt($as_codemp)	
 {  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 	//	Function:  uf_cargar_origen_y_aplic_fondos_txt
	//	Access:  public
	//	Description:  Este m�todo accesa la informaci�n del c�digo y denominaci�n de las 
	//      cuentas de origen y aplic de fondos y procede a insertarla en la tabla SCG_PlantillaCuentaReporte
	//               
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$ls_linea=""; $ls_cadena_linea=""; $ls_cuenta=""; $ls_denominacion=""; $ls_denominacion_plan=""; $ls_no_fila="";
	$ls_codreport=""; $ls_status=""; $ls_ref=""; $ls_tipo=""; $ls_cta_res=""; $ls_sql="";
	$ldc_asignado=0; $ldc_ene=0; $ldc_feb=0; $ldc_mar=0; $ldc_abr=0; $ldc_may=0; $ldc_jun=0;
	$ldc_jul=0; $ldc_ago=0; $ldc_sep=0; $ldc_oct=0; $ldc_nov=0; $ldc_dic=0;
	$li_NumFile=0; $li_Read_Result=0; $li_valid=0; $li_distribuir=0; $li_nivel=0; $li_no_fila=0; $li_exec=0; $li_rtn=0 ;
	$lb_valido=true;
	
	        $ls_archivo = file("origen_y_aplic_fondos.txt");
			$ls_linea = count($ls_archivo);
	        for ($i=0; $i < $ls_linea; $i++)
	        {
	              // Reemplazar por el procesamiento
	              $ls_cadena_linea = $ls_archivo[$i];
	              $ls_codreport    = substr($ls_cadena_linea,0,5);	    //5
				  $ls_cuenta       = substr($ls_cadena_linea,5,25);    //25
				  $ls_denominacion = substr($ls_cadena_linea,30,100);  //100
				  $ls_status       = substr($ls_cadena_linea,130,1); //1
				  $ldc_asignado    = substr($ls_cadena_linea,131,1); //1  
				  $li_distribuir   = substr($ls_cadena_linea,132,1); //1 
				  $ldc_ene         = substr($ls_cadena_linea,133,1); //1  
				  $ldc_feb         = substr($ls_cadena_linea,134,1); //1  
				  $ldc_mar         = substr($ls_cadena_linea,135,1); //1  
				  $ldc_abr         = substr($ls_cadena_linea,136,1); //1  
				  $ldc_may         = substr($ls_cadena_linea,137,1); //1  
				  $ldc_jun         = substr($ls_cadena_linea,138,1); //1  
				  $ldc_jul         = substr($ls_cadena_linea,139,1); //1  
				  $ldc_ago         = substr($ls_cadena_linea,140,1); //1  
				  $ldc_sep         = substr($ls_cadena_linea,141,1); //1  
				  $ldc_oct         = substr($ls_cadena_linea,142,1); //1  
				  $ldc_nov         = substr($ls_cadena_linea,143,1); //1  
				  $ldc_dic         = substr($ls_cadena_linea,144,1); //1  
				  $li_nivel        = substr($ls_cadena_linea,145,1); //1  
				  $ls_ref          = substr($ls_cadena_linea,146,1); //25
				  $ls_no_fila      = substr($ls_cadena_linea,171,1); //3	
				  $ls_tipo         = substr($ls_cadena_linea,174,1); //1
				  $ls_cta_res      = substr($ls_cadena_linea,175,strlen($ls_cadena_linea)); 	
				  $ls_codreport    = str_replace("-", " ", $ls_codreport);
				  $ls_cuenta       = str_replace("-", " ", $ls_cuenta);
				  $ls_denominacion = str_replace("-", " ", $ls_denominacion);
	              $ls_no_fila      = str_replace("-", " ", $ls_no_fila);
	              $li_no_fila      = trim($ls_no_fila); 
	              $ls_ref          = str_replace("-", " ",$ls_ref); 
	              $ls_modrep	   = "0";					  
		  ///-------------------------------------------------------------------------------------------------------------------
//		 		  $ldc_eneaux         = substr($ls_cadena_linea,133,1); //1  
//		  		  $ldc_febaux         = substr($ls_cadena_linea,134,1); //1  
//		   		  $ldc_maraux         = substr($ls_cadena_linea,135,1); //1  
//		  		  $ldc_abraux         = substr($ls_cadena_linea,136,1); //1  
//		          $ldc_mayaux         = substr($ls_cadena_linea,137,1); //1  
//		          $ldc_junaux         = substr($ls_cadena_linea,138,1); //1  
//		          $ldc_julaux         = substr($ls_cadena_linea,139,1); //1  
//		          $ldc_agoaux         = substr($ls_cadena_linea,140,1); //1  
//		          $ldc_sepaux         = substr($ls_cadena_linea,141,1); //1  
//		          $ldc_octaux         = substr($ls_cadena_linea,142,1); //1  
//		          $ldc_novaux         = substr($ls_cadena_linea,143,1); //1  
//		          $ldc_dicaux         = substr($ls_cadena_linea,144,1); //1 
		   //----------------------------------------------------------------------------------------------------------------------    	              			              				
						
			//INSERT
		    $ls_sql= "INSERT INTO scg_pc_reporte (codemp,cod_report,sc_cuenta,denominacion,status,asignado,distribuir,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,nivel,referencia,no_fila,tipo,cta_res,modrep)".
				           " VALUES('".trim($as_codemp)."','".trim($ls_codreport)."','".trim($ls_cuenta)."','".trim($ls_denominacion)."','".$ls_status."',".$ldc_asignado.",".$li_distribuir.",".$ldc_ene.",".$ldc_feb.",".$ldc_mar.",".$ldc_abr.",".$ldc_may.",".$ldc_jun.",".$ldc_jul.",".$ldc_ago.",".$ldc_sep.",".$ldc_oct.",".$ldc_nov.",".$ldc_dic.",".$li_nivel.",'".trim($ls_ref)."',".$li_no_fila.",'".$ls_tipo."','".$ls_cta_res."','".$ls_modrep."')" ;	   
			        
			$rs_data = $this->SQL->execute($ls_sql);
			if ($rs_data===false)
			   {
			     $is_msg_error = "Error en m�todo uf_cargar_origen_y_aplic_fondos_txt ";
				 $lb_valido = false;			   
			   }		
			else
			   {
			     $li_rtn=1;
			   }
		}
				
	   $this->SQL->begin_transaction();	
	   if ($li_rtn==1)
	   {
		    $this->SQL->commit();
		    $lb_valido = true;
		    $is_msg_error = "Origen y Aplicaci�n de Fondos cargado.."	;
		}
		else
		{
			$this->SQL->rollback();
			$lb_valido = false;
			$is_msg_error = "Origen y Aplicaci�n de Fondos no fue cargado..";
		} 
    return $lb_valido;
	
 } // fin de uf_cargar_origen_y_aplic_fondos_txt
 ///--------------------------------------------------------------------------------------------------------------------------------
 
 function uf_cargar_txt_edoresultado($as_codemp)    
 {  ////////////////////////////////////////////////////////////////////////////////////////////////
     //    Function:  uf_cargar_txt_balancegeneral
    //    Access:  public
    //    Description:  Este m�todo accesa la informaci�n de las cuentas de balance general
    //                y procede a insertarla en la tabla SCG_PC_Reporte
    //               
    /////////////////////////////////////////////////////////////////////////////////////////////////
    $ls_linea=0; $ls_cadena_linea=""; $ls_cuenta=""; $ls_denominacion=""; $ls_denominacion_plan=""; $ls_no_fila="";
    $ls_codreport=""; $ls_status=""; $ls_ref=""; $ls_tipo=""; $ls_cta_res=""; $ls_sql="";
    $ldc_asignado=0; $ldc_ene=0; $ldc_feb=0; $ldc_mar=0; $ldc_abr=0; $ldc_may=0; $ldc_jun=0;
    $ldc_jul=0; $ldc_ago=0; $ldc_sep=0; $ldc_oct=0; $ldc_nov=0; $ldc_dic=0;
    $li_NumFile=0; $li_Read_Result=0; $li_valid=0; $li_distribuir=0; $li_nivel=0; $li_no_fila=0; $li_exec=0; //$li_rtn=0;
    $ldc_saldo_real_ant=0; $ldc_saldo_aprobado=0; $ldc_saldo_modificado=0;
    $lb_valido=true;
    
       
        $ls_archivo = file("estado_de_resultado.txt");
        $ls_linea = count($ls_archivo);
        for ($i=0; $i < $ls_linea; $i++)   
        {
           // Reemplazar por el procesamiento
           $ls_cadena_linea = $ls_archivo[$i];
           $ls_codreport    = substr($ls_cadena_linea,0,5);        //5
           $ls_cuenta       = substr($ls_cadena_linea,5,25);    //25
           $ls_denominacion = substr($ls_cadena_linea,30,100);  //100
           $ls_status       = substr($ls_cadena_linea,130,1); //1
           $ldc_asignado    = substr($ls_cadena_linea,131,1); //1  
           $li_distribuir   = substr($ls_cadena_linea,132,1); //1 
           $ldc_ene         = substr($ls_cadena_linea,133,1); //1  
           $ldc_feb         = substr($ls_cadena_linea,134,1); //1  
           $ldc_mar         = substr($ls_cadena_linea,135,1); //1  
           $ldc_abr         = substr($ls_cadena_linea,136,1); //1  
           $ldc_may         = substr($ls_cadena_linea,137,1); //1  
           $ldc_jun         = substr($ls_cadena_linea,138,1); //1  
           $ldc_jul         = substr($ls_cadena_linea,139,1); //1  
           $ldc_ago         = substr($ls_cadena_linea,140,1); //1  
           $ldc_sep         = substr($ls_cadena_linea,141,1); //1  
           $ldc_oct         = substr($ls_cadena_linea,142,1); //1  
           $ldc_nov         = substr($ls_cadena_linea,143,1); //1  
           $ldc_dic         = substr($ls_cadena_linea,144,1); //1  
           $li_nivel        = substr($ls_cadena_linea,145,1); //1  
           $ls_ref          = substr($ls_cadena_linea,146,25); //25
           $li_no_fila++;  //3    
           $ls_tipo         = ""; //1
           $ls_cta_res      = "";     
           $ls_codreport    = str_replace("-", " ", $ls_codreport);
           $ls_cuenta       = str_replace("-", " ", $ls_cuenta);
           $ls_denominacion = str_replace("-", " ", $ls_denominacion);
           $ls_ref          = str_replace("-", " ", $ls_ref);
           $ls_modrep        = "0"; 
          
           
            
          $ls_sql= " INSERT INTO scg_pc_reporte (codemp,cod_report,sc_cuenta,denominacion,status,asignado,distribuir,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,nivel,referencia,no_fila,tipo,cta_res,modrep,saldo_real_ant,saldo_apro,saldo_mod)".
                   " VALUES('".trim($as_codemp)."','".trim($ls_codreport)."','".trim($ls_cuenta)."','".trim($ls_denominacion)."','".$ls_status."',".$ldc_asignado.",".$li_distribuir.",".$ldc_ene.",".$ldc_feb.",".$ldc_mar.",".$ldc_abr.",".$ldc_may.",".$ldc_jun.",".$ldc_jul.",".$ldc_ago.",".$ldc_sep.",".$ldc_oct.",".$ldc_nov.",".$ldc_dic.",".$li_nivel.",'".trim($ls_ref)."',"
                               .$li_no_fila.",'".$ls_tipo."','".$ls_cta_res."','".$ls_modrep."'".",'".$ldc_saldo_real_ant."','".$ldc_saldo_aprobado."','".$ldc_saldo_modificado."');" ;
             $rs_data = $this->SQL->execute($ls_sql);                        
            if ($rs_data===false)
               {
                 $is_msg_error = "Error en m�todo uf_cargar_txt_balancegeneral ";
                 $lb_valido = false;
                 $li_rtn=0;
               }
            else
               {
                 $li_rtn=1;
                }            
        }
           if ($li_rtn==1)
        {
          $this->SQL->commit();
          $lb_valido = true;
          $is_msg_error = "Cuentas de Balance General cargadas.."    ;
        }
    
        else
       {
          $this->SQL->rollback();
          $lb_valido = false;
          $is_msg_error = " Cuentas de Balance General no cargadas.." ;
       }
    return $lb_valido;    
 } // fin de uf_cargar_txt_balancegeneral
	
  function  uf_count_scg_plantillacuentareporte( $as_codemp, $as_cod_report, $as_sc_cuenta )	
  {	////////////////////////////////////////////////////////////////////////////
    //Function:	uf_count_scg_plantillacuentareporte
    // Argumentos:  string as_codemp,
    //              string as_cod_report,
    //              string as_sc_cuenta
	// Descripci�n:	Devuelve si encontro scg_plantillacuentareporte
	//////////////////////////////////////////////////////////////////////////////
	$li_rtn=0; $li_count=0;
	$ls_sql="";
	
	$ls_sql = " SELECT COUNT(sc_cuenta) as total " .
	         "  FROM scg_pc_reporte " .
	         "  WHERE codemp	= '".trim($as_codemp)."' AND " .
	         "        cod_report = '".trim($as_cod_report)."' AND " .
	         "        sc_cuenta  = '".trim($as_sc_cuenta)."'" ;
	    //print $ls_sql;
	    $rs_oaf=$this->SQL->select($ls_sql);
		if ($row=$this->SQL->fetch_row($rs_oaf))
		{
			$li_rtn = $row["total"] ;  // N� de registros
		}
		else
		{
			$li_rtn = 0; //no existen registros
			$is_msg_error= " Error en la funci�n uf_count_scg_plantillacuentareporte. ";
		}
	
	return	$li_rtn;
		
  }	//fin de uf_count_scg_plantillacuentareporte
  
  function uf_insert_scg_plantillacuentareporte($la_datos)  ///ojo con esto como haer con los objeto
  { /////////////////////////////////////////////////////////////////
    //Funtion: uf_insert_scg_plantillacuentareporte
    //Argumentos: ao_datos, arreglo de objects
	//Descripci�n:	Inserta datos en scg_plantillacuentareporte
	///////////////////////////////////////////////////////////////////
	$li_rtn=0; $li_distribuir=0; $li_nivel=0; $li_no_fila=0; $li_exec=-2;
	$ls_codemp=""; $ls_cod_report=""; $ls_sc_cuenta=""; $ls_denominacion=""; $ls_status="";
	$ldc_enero=0; $ldc_febrero=0; $ldc_marzo=0; $ldc_abril=0; $ldc_mayo=0; $ldc_junio=0; $ldc_asignado=0;
	$ldc_julio=0; $ldc_agosto=0; $ldc_septiembre=0; $ldc_octubre=0; $ldc_noviembre=0; $ldc_diciembre=0;
	$ls_tipo=""; $ls_cta_res=""; $ls_referencia=""; $ls_sql="";
	$ld_saldo_real_ant = 0; $ld_saldo_aprobado = 0;$ld_saldo_mod = 0;
	
	$la_empresa      	=  $_SESSION["la_empresa"];
	$ls_codemp       	=  $la_empresa["codemp"];
	$ls_cod_report   	=  $la_datos[0];
	$ls_sc_cuenta    	=  $la_datos[1];
	$ls_denominacion 	=  $la_datos[2];
	$ls_status       	=  $la_datos[3]; 
	$ldc_asignado    	=  $la_datos[4];
	$li_distribuir   	=  $la_datos[5];
		
	$ldc_enero       	=  $la_datos[6];
	$ldc_febrero     	=  $la_datos[7];
	$ldc_marzo       	=  $la_datos[8];
	$ldc_abril       	=  $la_datos[9];
	$ldc_mayo        	=  $la_datos[10];
	$ldc_junio       	=  $la_datos[11];
	$ldc_julio       	=  $la_datos[12];
	$ldc_agosto      	=  $la_datos[13];
	$ldc_septiembre  	=  $la_datos[14];
	$ldc_octubre     	=  $la_datos[15];
	$ldc_noviembre   	=  $la_datos[16];
	$ldc_diciembre   	=  $la_datos[17];
	
	$li_nivel        	=  $la_datos[18];
	$ls_referencia   	=  $la_datos[19];
	$li_no_fila      	=  $la_datos[20];    
	$ls_tipo         	=  $la_datos[21]; 
	$ls_cta_res      	=  $la_datos[22];
	$ls_modrep		 	=  $la_datos[23];
	$ld_saldo_real_ant	=  $la_datos[24];
	$ld_saldo_aprobado	=  $la_datos[25];
	$ld_saldo_mod		=  $la_datos[26];
	
	$ld_asignado=str_replace('.','',$ldc_asignado);
	$ld_asignado=str_replace(',','.',$ldc_asignado);		
	$ld_enero=str_replace('.','',$ldc_enero);
	$ld_enero=str_replace(',','.',$ld_enero);
	$ld_febrero=str_replace('.','',$ldc_febrero);
	$ld_febrero=str_replace(',','.',$ld_febrero);
	$ld_marzo=str_replace('.','',$ldc_marzo);
	$ld_marzo=str_replace(',','.',$ld_marzo);
	$ld_abril=str_replace('.','',$ldc_abril);
	$ld_abril=str_replace(',','.',$ld_abril);
	$ld_mayo=str_replace('.','',$ldc_mayo);
	$ld_mayo=str_replace(',','.',$ld_mayo);
	$ld_junio=str_replace('.','',$ldc_junio);
	$ld_junio=str_replace(',','.',$ld_junio);
	$ld_julio=str_replace('.','',$ldc_julio);
	$ld_julio=str_replace(',','.',$ld_julio);
	$ld_agosto=str_replace('.','',$ldc_agosto);
	$ld_agosto=str_replace(',','.',$ld_agosto);
	$ld_septiembre=str_replace('.','',$ldc_septiembre);
	$ld_septiembre=str_replace(',','.',$ld_septiembre);
	$ld_octubre=str_replace('.','',$ldc_octubre);
	$ld_octubre=str_replace(',','.',$ld_octubre);
	$ld_noviembre=str_replace('.','',$ldc_noviembre);
	$ld_noviembre=str_replace(',','.',$ld_noviembre);
	$ld_diciembre=str_replace('.','',$ldc_diciembre);
	$ld_diciembre=str_replace(',','.',$ld_diciembre);

	$ld_saldo_real_ant=str_replace('.','',$ld_saldo_real_ant);
	$ld_saldo_real_ant=str_replace(',','.',$ld_saldo_real_ant);
	
	$ld_saldo_aprobado=str_replace('.','',$ld_saldo_aprobado);
	$ld_saldo_aprobado=str_replace(',','.',$ld_saldo_aprobado);
	
	$ld_saldo_mod=str_replace('.','',$ld_saldo_mod);
	$ld_saldo_mod=str_replace(',','.',$ld_saldo_mod);	
 	
	$ls_sql = " INSERT INTO scg_pc_reporte ".  
	         " (codemp, " . 
	         "  cod_report,".    
	         "  sc_cuenta,".   
	         "  denominacion,".   
	         "  status,".   
	         "  asignado,".   
	         "  distribuir,".   
	         "  enero,".   
	         "  febrero,".   
	         "  marzo,".   
	         "  abril,".   
	         "  mayo,".   
	         "  junio,".   
	         "  julio,".   
	         "  agosto,".   
	         "  septiembre,".   
	         "  octubre,".   
	         "  noviembre,".   
	         "  diciembre,".   
	         "  nivel,".   
	         "  referencia,".   
	         "  no_fila,".   
	         "  tipo,".   
	         "  cta_res,".
			 "  modrep,saldo_real_ant,saldo_apro,saldo_mod)".  
	 " VALUES ('".$ls_codemp."',". 
	         " '".$ls_cod_report."',". 
	         " '".$ls_sc_cuenta."',". 
	         " '".$ls_denominacion."',". 
	         " '".$ls_status."',". 
	         " '".$ldc_asignado."',". 
	         " '".$li_distribuir."',".
	         " '".$ldc_enero."',". 
	         " '".$ldc_febrero."',". 
	         " '".$ldc_marzo."',".  
	         " '".$ldc_abril."',". 
	         " '".$ldc_mayo."',". 
	         " '".$ldc_junio."',". 
	         " '".$ldc_julio."',". 
	         " '".$ldc_agosto."',". 
	         " '".$ldc_septiembre."',". 
	         " '".$ldc_octubre."',". 
	         " '".$ldc_noviembre."',". 
	         " '".$ldc_diciembre."',". 
	         "  ".$li_nivel.",". 
	         " '".$ls_referencia."',". 
	         " ".$li_no_fila.",". 
	         " '".$ls_tipo."',". 
	         " '".$ls_cta_res."',".
			 " '".$ls_modrep."',".
			 " '".$ld_saldo_real_ant."',".
			 " '".$ld_saldo_apro."',".		
			 " '".$ld_saldo_mod."' )";
		    
	 $rs_data = $this->SQL->execute($ls_sql);						
	 if ($rs_data===false)
	    {
			$is_msg_error = "Error en m�todo uf_insert_scg_plantillacuentareporte ";
			$li_rtn=0;
		}
		else
		{
			$li_rtn=1;
		}
		
	return	$li_rtn;
  }		  

  function uf_update_scg_plantillacuentareporte($la_datos)		
  { ///////////////////////////////////////////////////////////////////
    //Funtion: uf_update_scg_plantillacuentareporte
    //Argumentos: ao_datos, arreglo de objects
	//Descripci�n:	Actuliza datos en scg_pc_reporte
	///////////////////////////////////////////////////////////////////
  
	$li_rtn=0; $li_distribuir=0; $li_nivel=0; $li_no_fila=0; 
	$ls_codemp=""; $ls_cod_report=""; $ls_sc_cuenta=""; $ls_denominacion=""; $ls_status="";
	$ldc_enero=0; $ldc_febrero=0; $ldc_marzo=0; $ldc_abril=0; $ldc_mayo=0; $ldc_junio=0; $ldc_asignado=0;
	$ldc_julio=0; $ldc_agosto=0; $ldc_septiembre=0; $ldc_octubre=0; $ldc_noviembre=0; $ldc_diciembre=0;
	$ls_tipo=""; $ls_cta_res=""; $ls_referencia=""; $ls_sql="";
	$ld_saldo_real_ant = 0; $ld_saldo_aprobado = 0;$ld_saldo_mod = 0;
	
    $la_empresa      	=  $_SESSION["la_empresa"];
    $ls_codemp       	=  $la_empresa["codemp"];
	$ls_cod_report   	=  $la_datos[0];
	$ls_sc_cuenta    	=  $la_datos[1];
	$ls_denominacion 	=  $la_datos[2];
	$ls_status       	=  $la_datos[3]; 
	$ldc_asignado    	=  $la_datos[4];
	$li_distribuir   	=  $la_datos[5];
		
	$ldc_enero       	=  $la_datos[6];
	$ldc_febrero     	=  $la_datos[7];
	$ldc_marzo       	=  $la_datos[8];
	$ldc_abril       	=  $la_datos[9];
	$ldc_mayo        	=  $la_datos[10];
	$ldc_junio       	=  $la_datos[11];
	$ldc_julio       	=  $la_datos[12];
	$ldc_agosto      	=  $la_datos[13];
	$ldc_septiembre  	=  $la_datos[14];
	$ldc_octubre     	=  $la_datos[15];
	$ldc_noviembre   	=  $la_datos[16];
	$ldc_diciembre   	=  $la_datos[17];
	
	$li_nivel        	=  $la_datos[18];
	$ls_referencia   	=  $la_datos[19];
	$li_no_fila      	=  $la_datos[20];    
	$ls_tipo         	=  $la_datos[21]; 
	$ls_cta_res      	=  $la_datos[22]; 
	$ls_modrep		 	=  $la_datos[23];
	$ld_saldo_real_ant	=  $la_datos[24];
	$ld_saldo_aprobado	=  $la_datos[25];
	$ld_saldo_mod		=  $la_datos[26];
	
	if (trim($ld_saldo_real_ant)=='')
	{
		$ld_saldo_real_ant	= 0.00;
	}
  	if (trim($ld_saldo_aprobado)=='')
	{
		$ld_saldo_aprobado	= 0.00;
	}
  	if (trim($ld_saldo_mod)=='')
	{
		$ld_saldo_mod	= 0.00;
	}		

	
	$ls_sql = " UPDATE scg_pc_reporte SET status  		= '".$ls_status."',".   
							         " asignado	  		= ".$ldc_asignado.",".   
							         " distribuir 		= ".$li_distribuir.",".   
							         " enero	  		= ".$ldc_enero.",".
							         " febrero	  		= ".$ldc_febrero.",".   
							         " marzo	  		= ".$ldc_marzo.",".   
							         " abril	  		= ".$ldc_abril.",".   
							         " mayo		  		= ".$ldc_mayo.",".   
							         " junio	  		= ".$ldc_junio.",".   
							         " julio	  		= ".$ldc_julio.",".   
							         " agosto	  		= ".$ldc_agosto.",".   
							         " septiembre 		= ".$ldc_septiembre.",".   
							         " octubre 	  		= ".$ldc_octubre.",".   
							         " noviembre  		= ".$ldc_noviembre.",".   
							         " diciembre  		= ".$ldc_diciembre.",".   
							         " nivel	  		= ".$li_nivel.",".   
							         " referencia 		= '".$ls_referencia."',".   
							         " no_fila	  		=  ".$li_no_fila.",".   
							         " tipo		  		= '".$ls_tipo."',".   
							         " cta_res	  		= '".$ls_cta_res."',". 
									 " modrep     		= '".$ls_modrep."', ".
									 " saldo_real_ant   = ".$ld_saldo_real_ant.", ".
									 " saldo_apro   	= ".$ld_saldo_aprobado.", ".
									 " saldo_mod     	= ".$ld_saldo_mod." ".			
						             " WHERE codemp = '".$ls_codemp."' AND ".		
							         "       cod_report = '".$ls_cod_report."' AND ".
							         "       sc_cuenta = '".$ls_sc_cuenta."' ";
		//echo $ls_sql."<br>";
		$li_numrows=$this->SQL->execute($ls_sql);	
	    if($li_numrows===false)
	    {
			$lb_valido=false;
			$this->is_msg_error="Error en metodo update".$this->SQL->message;
	    }
	    else
	    {
			$lb_valido=true;
	    }
	return	$lb_valido;		
  }	//fin de uf_update_scg_plantillacuentareporte
  
	
  function uf_sql_transaction($ab_valido) 
  {
  	 $lb_valido=false;
  	 
	$this->SQL->begin_transaction(); 
  	if ($ab_valido)
  	{
		$this->SQL->commit();
		$lb_valido=true;
	}
	else
	{
		$this->SQL->rollback();
		$lb_valido=false;
	}
  	
  	return $lb_valido;
  }

	function uf_eliminar_cmp( $as_codemp, $as_comprobante, $as_procede, $as_fecha)
	{
		$ls_sql="";
		$li_exec=0;
		$lb_valido=false;
		
		$ls_sql="DELETE FROM sigesp_cmp WHERE codemp='".$as_codemp."' AND comprobante='".$as_comprobante."' AND procede='".$as_procede."' AND fecha=".$as_fecha." AND procede ='SCGCMP' " ;
		$rs_data = $this->SQL->execute($ls_sql);
		if ($rs_data===false)
		   {
		     $this->SQL->rollback();
			 $lb_valido=false;
			 $is_msg_error="Error en eliminar comprobante";
		   }
		else
		   {
			 $this->SQL->commit();
			 $lb_valido=true;		   
		   }	
        return $lb_valido;
	}


    function uf_consultar_asig_previa( $as_sc_cuenta )
    {
    	$ls_sql="";
    	$ldc_asig_previo ;
    	
    	$ls_sql = " SELECT asignado FROM scg_pc_reporte WHERE sc_cuenta = '".$as_sc_cuenta."'";
    	$rs_oaf=$this->SQL->select($ls_sql);
		if ($row=$this->SQL->fecth_row($rs_oaf))
		{
			$ldc_asig_previo = $row["asignado"] ;  
		}
		else
		{
			$ldc_asig_previo = 0; //no existen registros
			$is_msg_error= " Error en la funci�n uf_consultar_asig_previa. ";
		}
		
      return $ldc_asig_previo;	
    	
    } //fin de uf_consultar_asig_previa
	
    function uf_select_cta_referencia($as_codemp, $as_sc_cuenta )	
	{   // Function:uf_select_cta_refencia
	   // Argumentos: string $as_codemp,
	   //             string $as_sc_cuenta, 
	   //             
	   // Return      $ls_cta_ref: string
	   // Descripcion: selecciona la cuenta de referecia de sc_cuenta
	   //////////////////////////////////////////////////////////////////////  
	   $ls_sql=""; $ls_cta_ref="";
	   
	   $ls_sql = " SELECT  referencia,".
		         " FROM    scg_pc_reporte".
			     " WHERE   codemp = '".$as_codemp."' AND TRIM(sc_cuenta) = '".$as_sc_cuenta."'" ;
		
	  $rs_oaf=$this->SQL->select($ls_sql);
	  if ($row=$this->SQL->fecth_row($rs_oaf))
	  {
		$ls_cta_ref = $row["referencia"] ;
	  }
	  else
	  {
		$ls_cta_ref = ""; //no existen registros
		$is_msg_error= " Error en la funci�n uf_select_scg_plantillacuentareporte. ";
	  }
		
	 return	$ls_cta_ref;
		
	 } // fin de uf_select_cta_referencia
    
    
     function uf_buscar_cuenta($as_codemp, $as_sc_cuenta )	
	 { // Function:uf_buscar_cuenta
	   // Argumentos: string as_codemp,
	   //             string as_sc_cuenta, 
	   //             
	   // Return      li_count
	   // Descripcion: 
	   //////////////////////////////////////////////////////////////////////  
	   $ls_sql="";
	   $li_rtn=0;
	      
	   $ls_sql = " SELECT COUNT(sc_cuenta) as total,".
		         " FROM scg_pc_reporte".
			     " WHERE codemp = '".$as_codemp."' AND TRIM(sc_cuenta) = '".$as_sc_cuenta."'" ;
	   $rs_oaf=$this->SQL->execute($ls_sql);
	   if ($row=$this->SQL->fecth_row($rs_oaf))
	   {
		   $li_rtn = $row["total"] ;  // N� de registros
	   }
	   else
	   {
		  $li_rtn = 0; //no existen registros
		  $is_msg_error= " Error en la funci�n uf_buscar_cuenta." ;
	   }
	return	$li_rtn;
		
    } // fin de uf_buscar_cuenta
   
   function uf_obt_nivel_cta( $as_cuenta )
  	 {  ////////////////////////////////////////////////////////////////////////////////////////////
  	 	// Function:    uf_obt_nivel_cta
  	    // Acesso:      Public
  	    // Argumentos:  as_sc_cuenta: String
  	    // Descripci�n  Busca en la tabla scg_pc_report el nivel de la cuenta que pasa por parametro 		  	  
  	 	/////////////////////////////////////////////////////////////////////////////////////////////
  	 	
  	 	$ls_sql="";
  	 	$ls_sql = "SELECT nivel FROM scg_pc_reporte WHERE sc_cuenta = '".$as_cuenta."'";	
  	 	$rs_oaf = $this->SQL->select($ls_sql);
		if ($row=$this->SQL->fetch_row($rs_oaf))
		{
			$li_nivel = $row["nivel"];
		}
		else
		{
			$li_nivel = 0; //no existen registros
			$is_msg_error= " Error en la funci�n uf_select_scg_plantillacuentareporte. ";
		}
	return $li_nivel;
  	 	
   }
/***************************************************************************************************************************************/	 
   function uf_cuenta_sin_ceros( $as_cuenta )
   {    //////////////////////////////////////////////////////////////////////// 
        // Function:   uf_cuenta_sin_ceros
	    // Acceso:     public
	    // Argumentos: as_cuenta
	    // Descripci�n: Elimina los ceros a la derecha de la cuenta contable   
	 	////////////////////////////////////////////////////////////////////////
	 	$li_lenCta=0; $li_cero=1;
	 	$ls_cta_ceros=""; $ls_cad="";
	 	$lb_encontrado=true;
	 	global $msg;
	 	$li_lenCta = strlen(trim($as_cuenta));
		
		$ls_cad = substr(trim($as_cuenta), strlen(trim($as_cuenta))-1, 1 );
		$li_cero = $ls_cad;
	 	
	 	if ($li_cero == 0)
	 	{
			$ls_cta_ceros = substr(trim($as_cuenta), 0 , 11);
	  	}
	 	
	 	do  
		{
			$ls_cad = substr(trim($ls_cta_ceros), strlen($ls_cta_ceros)-1, 1);
	 		$li_cero = intval($ls_cad);
			$li_cant=strlen($ls_cta_ceros)-1;
	 		if ($li_cero == 0 )
	 		{
				$ls_cta_ceros = substr(trim($ls_cta_ceros), 0 , $li_cant);
	 			$lb_encontrado=true;
	 	 	}
	 	 	else
	 	 	{
	 	 		$lb_encontrado = false;
	 	 	}
	 		
	 	}while ( $lb_encontrado == true ); 
	 	
	  	return $ls_cta_ceros;
	 }
/***************************************************************************************************************************************/	 
	 function uf_disable_cta_inferior( $as_cta_ceros, $as_sc_cuenta,$as_cod_report )
	 {
	 	$lb_valido=true;
	 	$li_row = 0; $li_contador=0; $li_distribuir=0; $li_nivel=0; $li_no_fila=0; $li_exec=0; $li_rtn=0;
	  	$ls_codemp=""; $ls_cod_report=""; $ls_sc_cuenta=""; $ls_denominacion=""; $ls_status="";
		$ldc_enero=0; $ldc_febrero=0; $ldc_marzo=0; $ldc_abril=0; $ldc_mayo=0; $ldc_junio=0; $ldc_asignado=0;
		$ldc_julio=0; $ldc_agosto=0; $ldc_septiembre=0; $ldc_octubre=0; $ldc_noviembre=0; $ldc_diciembre=0;
		$ls_tipo=""; $ls_cta_res=""; $ls_referencia=""; $ls_sql="";
		global $msg;
		$data=array();
	    $la_empresa = $_SESSION["la_empresa"];	
		$ls_codemp = $la_empresa["codemp"]; 
	 		
	 	$ls_sql = " SELECT * FROM scg_pc_reporte WHERE sc_cuenta like '".$as_cta_ceros."%' and sc_cuenta <> '".$as_sc_cuenta."' and cod_report='".$as_cod_report."' order by sc_cuenta " ;
		$rs_oaf=$this->SQL->select($ls_sql);
		$li_row=$this->SQL->num_rows($rs_oaf);
		if ($row=$this->SQL->fetch_row($rs_oaf))
		{
			while ($row=$this->SQL->fetch_row($rs_oaf))
			{	
				$ldc_asignado = $row["asignado"];
				$ls_sc_cuenta = $row["sc_cuenta"];
										
				if (!($ldc_asignado == 0))
				{
					$li_rtn = 1 ;
					$this->msg->message("La cuenta ".$ls_sc_cuenta." tiene asignaci�n. ");
					break;
				}
				else
				{
					$li_contador = $li_contador + 1;
				} 	
			} //cierre del while
			
			if ($li_contador + 1 == $li_row )
			{   
				$ls_sql = " SELECT * FROM scg_pc_reporte WHERE sc_cuenta like '".$as_cta_ceros."%' and sc_cuenta <> '".$as_sc_cuenta."' and cod_report='".$as_cod_report."' order by sc_cuenta " ;
                $rs_oaf=$this->SQL->select($ls_sql);
				if($rs_oaf===false)
				{
				  $data=0;
				  $this->is_msg_error="Error en metodo uf_disable_cta_inferior".$this->SQL->message;
				  print $this->is_msg_error;
				}
				else
				{
					$i=1;
					$data=array();
					while($row=$this->SQL->fetch_row($rs_oaf))
					{
						$ls_sc_cuenta  =  $row["sc_cuenta"];
						$data[$i]=$ls_sc_cuenta;
						$i=$i+1;
					}// cierre del while rs_oaf.next (update)
				}
			}// cierre del if (li_contador == li_row)
  }//cierre del if
return $data;
} // fin de uf_disable_cta_inferior

	

function uf_select_Cuentas($as_codemp)
{
	$ls_sql="";
	$rs_data=null;
	 	
	$ls_sql="SELECT sc_cuenta, denominacion, status, asignado, distribuir,".
				   " enero, febrero, marzo, abril, mayo, junio, julio, agosto, septiembre, octubre, noviembre, diciembre,". 
				   " nivel, referencia ".
			       " FROM scg_cuentas " . 
				   " WHERE codemp = '".$as_codemp."' ". 
				   " ORDER BY sc_cuenta";
		
    $rs_data = $this->SQL->select($ls_sql);	
	if ($rs_data=false)
	   {
	     $is_msg_error = "Error en el SELECT de la funci�n uf_select_Cuentas";
	     $rs_data = false;
	   }
    return $rs_CatCta;
}//uf_select_Cuentas
	
function delete_scg_pc_reporte($as_cod_report)
{      
	   
		$ls_sql="";
		$lb_valido=true;
		
		$ls_sql = "DELETE FROM scg_pc_reporte WHERE cod_report='".$as_cod_report."' ";
		$rs_data = $this->SQL->execute($ls_sql);
		if ($rs_data===false)
		   {
		     $this->is_msg_error="Error al eliminar";
		     $lb_valido = false;
		     $this->SQL->rollback();
		     $this->ib_db_error = true ;
		   }
		else
		   {
		     $lb_valido=true;
			 $this->SQL->commit();	   
		   }
 return $lb_valido;
}

function  uf_select_reporte($as_codemp,&$ai_cuantos,$as_cod_report)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	Function:     uf_select_reporte 
	//	Arguments:    $as_codemp // codigo de la empresa
	//                $ai_cuantos  // cuantos 
	//                $as_cod_report  // codigo del reporte             
	//	Returns:	  $lb_valido true si es correcto los delete o false en caso contrario
	//	Description:  Funci�n que elimina el periodos de todas las tablas historicas para proceder al cierre del mismo
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $ls_sql=" SELECT count(cod_report) as cuantos ".
            " FROM   scg_pc_reporte ".
            " WHERE  codemp='".$as_codemp."' AND cod_report='".$as_cod_report."' ";
   	$rs_cod=$this->SQL->select($ls_sql);
	if($rs_cod===false)
	{
		  $lb_valido=false;
		  $this->is_msg_error="Error en metodo uf_disable_cta_inferior".$this->SQL->message;
		  print $this->is_msg_error;
	}
	else
	{
		  $lb_valido=true;
		  if($row=$this->SQL->fetch_row($rs_cod))
		  {
		    $ai_cuantos=$row["cuantos"];
		  }
	}
 return $lb_valido;
}

function uf_consultar_status_cuenta( $as_sc_cuenta )
{
	$ls_sql="";
	$ls_status="" ;
	
	$ls_sql = " SELECT status FROM scg_pc_reporte WHERE sc_cuenta = '".$as_sc_cuenta."'";
	$rs_oaf=$this->SQL->select($ls_sql);
	if ($row=$this->SQL->fetch_row($rs_oaf))
	{
		$ls_status = $row["status"] ;  
	}
  return $ls_status;	
}	 

//-------------------------------------------------------------------------------------------------------------------------
//------------------------------------- INSTRUCTIVO   8  ------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------

function uf_cargar_cuentas_inversiones_ins08($as_codemp)    
 {  ////////////////////////////////////////////////////////////////////////////////////
     //    Function:  uf_cargar_cuentas_inversiones_ins08
    //    Access:  public
    //    Description:  Este m�todo accesa la informaci�n de las cuentas de inversion
    //                y procede a insertarla en la tabla SCG_PC_Reporte
    ////////////////////////////////////////////////////////////////////////////////////
    
    $ls_linea=""; $ls_cadena_linea=""; $ls_cuenta=""; $ls_denominacion=""; $ls_denominacion_plan=""; $li_no_fila="";
    $ls_codreport=""; $ls_status="S"; $ls_ref=""; $ls_tipo=""; $ls_cta_res=""; $ls_sql="";
    $ldc_asignado=0; $ldc_ene=0; $ldc_feb=0; $ldc_mar=0; $ldc_abr=0; $ldc_may=0; $ldc_jun=0;
    $ldc_jul=0; $ldc_ago=0; $ldc_sep=0; $ldc_oct=0; $ldc_nov=0; $ldc_dic=0;
    $li_NumFile=0; $li_Read_Result=0; $li_valid=0; $li_distribuir=0; $li_nivel=0; $li_no_fila=0; $li_exec=0; $li_rtn=0 ;
    $lb_valido=true;
           
           
            $la_cuentas = array();
            $la_dencuentas = array();
			$la_status = array();
			
            $ls_formcont=$_SESSION["la_empresa"]["formcont"];
			$ls_formcont=str_replace('-','',$ls_formcont);
			$li_len=strlen($ls_formcont);
			$li_len=$li_len-9;
			$ls_ceros=$this->io_funciones->uf_cerosderecha("",$li_len);          
            $la_cuentas[1]  = '121000000'.$ls_ceros;
            $la_cuentas[2]  = '121010000'.$ls_ceros;
            $la_cuentas[3]  = '121010100'.$ls_ceros;
            $la_cuentas[4]  = '121010200'.$ls_ceros;
            $la_cuentas[5]  = '121020000'.$ls_ceros;
            $la_cuentas[6]  = '121020100'.$ls_ceros;
            $la_cuentas[7]  = '121020200'.$ls_ceros;
            $la_cuentas[8]  = '121030000'.$ls_ceros;
            $la_cuentas[9]  = '121030100'.$ls_ceros;
            $la_cuentas[10] = '121030200'.$ls_ceros;
            $la_cuentas[11] = '123000000'.$ls_ceros;
            $la_cuentas[12] = '123010000'.$ls_ceros;
            $la_cuentas[13] = '123010100'.$ls_ceros;
            $la_cuentas[14] = '123010200'.$ls_ceros;
            $la_cuentas[15] = '123010300'.$ls_ceros;
            $la_cuentas[16] = '123010400'.$ls_ceros;
            $la_cuentas[17] = '123010500'.$ls_ceros;
            $la_cuentas[18] = '123010600'.$ls_ceros;
            $la_cuentas[19] = '123010700'.$ls_ceros;
            $la_cuentas[20] = '123010800'.$ls_ceros;
            $la_cuentas[21] = '123011900'.$ls_ceros;
            $la_cuentas[22] = '123020000'.$ls_ceros;
            $la_cuentas[23] = '123030000'.$ls_ceros;
            $la_cuentas[24] = '123040000'.$ls_ceros;
            $la_cuentas[25] = '123050000'.$ls_ceros;
            $la_cuentas[26] = '123050100'.$ls_ceros;
            $la_cuentas[27] = '123050200'.$ls_ceros;
            $la_cuentas[28] = '124000000'.$ls_ceros;
            $la_cuentas[29] = '124010000'.$ls_ceros;
            $la_cuentas[30] = '124020000'.$ls_ceros;
            $la_cuentas[31] = '124030000'.$ls_ceros;
            $la_cuentas[32] = '124040000'.$ls_ceros;
            $la_cuentas[33] = '124050000'.$ls_ceros;
            $la_cuentas[34] = '124190000'.$ls_ceros;

            $la_dencuentas[1]  = 'Inversi�n Financiera a Largo Plazo';
            $la_dencuentas[2]  = 'Inversiones Financieras en Acciones y Participaciones de Capital a Largo Plazo';
            $la_dencuentas[3]  = 'Inversiones en acciones y participaciones de capital a largo plazo al sector privado';
            $la_dencuentas[4]  = 'Inversiones en acciones y participaciones de capital a largo plazo al sector p�blico';
            $la_dencuentas[5]  = 'Inversiones Financieras en T�tulos y Valores a Largo Plazo';
            $la_dencuentas[6]  = 'Inversiones en t�tulos y valores privados a largo plazo';
            $la_dencuentas[7]  = 'Inversiones en t�tulos y valores p�blicos a largo plazo';
            $la_dencuentas[8]  = 'Pr�stamos por Cobrar a Largo Plazo';
            $la_dencuentas[9]  = 'Pr�stamos por cobrar a largo plazo al sector privado';
            $la_dencuentas[10] = 'Pr�stamos por cobrar a largo plazo al sector p�blico';
            $la_dencuentas[11] = 'Propiedad, Planta y Equipo';
            $la_dencuentas[12] = 'Bienes de Uso';
            $la_dencuentas[13] = 'Edificios e Instalaciones';
            $la_dencuentas[14] = 'Maquinarias y dem�s Equipos de Construcci�n, Campo, Industria y Taller';
            $la_dencuentas[15] = 'Equipos de Transporte, Tracci�n y Elevaci�n';
            $la_dencuentas[16] = 'Equipos de Comunicaci�n y Se�alamiento';
            $la_dencuentas[17] = 'Equipos M�dicos Quir�rgicos, Dentales y Veterinarios';
            $la_dencuentas[18] = 'Equipos Cient�ficos, Religiosos de Ense�anza y Recreaci�n';
            $la_dencuentas[19] = 'Equipos para la Seguridad P�blica';
            $la_dencuentas[20] = 'Maquinaria Muebles y Equipos de Oficina y de Alojamiento';
            $la_dencuentas[21] = 'Otras Bienes de Uso';
            $la_dencuentas[22] = 'Tierras y Terrenos';
            $la_dencuentas[23] = 'Tierras y Terrenos Expropiados';
            $la_dencuentas[24] = 'Edificios e Instalaciones Expropiados';
            $la_dencuentas[25] = 'Construcciones en Proceso (S�lo lo correspondiente a Estudios y Proyectos)';
            $la_dencuentas[26] = 'Construcciones en Proceso de Bienes del Dominio Privado';
            $la_dencuentas[27] = 'Construcciones en Proceso de Bienes del Dominio P�blico';
            $la_dencuentas[28] = 'Activo Intangible';
            $la_dencuentas[29] = 'Marcas de Fabricas y Patentes de Inversi�n';
            $la_dencuentas[30] = 'Derechos de Autor';
            $la_dencuentas[31] = 'Gastos de Organizaci�n';
            $la_dencuentas[32] = 'Paquetes y Programas de Computaci�n';
            $la_dencuentas[33] = 'Estudios y Proyectos';
            $la_dencuentas[34] = 'Otros Activos Intangibles';
			
			$la_status[1]  = 'S';
            $la_status[2]  = 'S';
            $la_status[3]  = 'C';
            $la_status[4]  = 'C';
            $la_status[5]  = 'S';
            $la_status[6]  = 'C';
            $la_status[7]  = 'C';
            $la_status[8]  = 'S';
            $la_status[9]  = 'C';
            $la_status[10] = 'C';
            $la_status[11] = 'S';
            $la_status[12] = 'S';
            $la_status[13] = 'C';
            $la_status[14] = 'C';
            $la_status[15] = 'C';
            $la_status[16] = 'C';
            $la_status[17] = 'C';
            $la_status[18] = 'C';
            $la_status[19] = 'C';
            $la_status[20] = 'C';
            $la_status[21] = 'C';
            $la_status[22] = 'S';
            $la_status[23] = 'S';
            $la_status[24] = 'S';
            $la_status[25] = 'S';
            $la_status[26] = 'C';
            $la_status[27] = 'C';
            $la_status[28] = 'S';
            $la_status[29] = 'C';
            $la_status[30] = 'C';
            $la_status[31] = 'C';
            $la_status[32] = 'C';
            $la_status[33] = 'C';
            $la_status[34] = 'C';

            $li_no_fila=0;
            for ($i=1; $i <= 34; $i++)
            {
               // Reemplazar por el procesamiento
               $ls_cadena_linea = $ls_archivo[$i];
               $ls_codreport    = '0801';        
               $ls_cuenta       = $la_cuentas[$i];    
               $ls_denominacion = $la_dencuentas[$i];
			   $ls_status = $la_status[$i];  
               $li_no_fila++;  
               $ls_tipo         = ""; //1
               $ls_cta_res      = "";     
               $ls_modrep        = "0";  //modalidad mensual
			   $li_nivel = $this->io_int_scg->uf_scg_obtener_nivel($ls_cuenta);                 

              //INSERT
               
               $ls_sql= " INSERT INTO scg_pc_reporte (codemp,cod_report,sc_cuenta,denominacion,status,asignado,distribuir,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,nivel,referencia,no_fila,tipo,cta_res,modrep)".
               " VALUES('".trim($as_codemp)."','".trim($ls_codreport)."','".trim($ls_cuenta)."','".trim($ls_denominacion)."','".$ls_status."',".$ldc_asignado.",".$li_distribuir.",".$ldc_ene.",".$ldc_feb.",".$ldc_mar.",".$ldc_abr.",".$ldc_may.",".$ldc_jun.",".$ldc_jul.",".$ldc_ago.",".$ldc_sep.",".$ldc_oct.",".$ldc_nov.",".$ldc_dic.",".$li_nivel.",'".trim($ls_ref)."',"
                           .$li_no_fila.",'".$ls_tipo."','".$ls_cta_res."','".$ls_modrep."');" ;
                
                $rs_data = $this->SQL->execute($ls_sql);                        
                if ($rs_data===false)
                {
                    $is_msg_error = "Error en m�todo uf_cargar_cuentas_inversiones_ins08 ";
                    $lb_valido = false;
                    $li_rtn=0;
                }
                else
                {
                    $li_rtn=1;
                }
    }
        
    if ($li_rtn==1)
    {
        $this->SQL->commit();
        $is_msg_error = "Cuentas de Inversi�n Instructivo 8 cargadas..";
        $lb_valido = true;
    }
    else
    {
        $this->SQL->rollback();
        $is_msg_error = " Cuentas de Inversi�n Instructivo 8 no cargadas.." ;
        $lb_valido = false;
    }    
    return $lb_valido;    
 } // fin de uf_cargar_cuentas_inversiones_ins08    

//-----------------------------------------------------------------------------------------------------------------------

 
//----------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------
 function uf_cargar_cuentas_balancegeneral_ins08($as_codemp)    
 {  ////////////////////////////////////////////////////////////////////////////////////////////////
     //    Function:  uf_cargar_cuentas_balancegeneral_ins08
    //    Access:  public
    //    Description:  Este m�todo accesa la informaci�n de las cuentas de balance general
    //                y procede a insertarla en la tabla SCG_PC_Reporte
    //               
    /////////////////////////////////////////////////////////////////////////////////////////////////
    $ls_linea=0; $ls_cadena_linea=""; $ls_cuenta=""; $ls_denominacion=""; $ls_denominacion_plan=""; $ls_no_fila="";
    $ls_codreport=""; $ls_status=""; $ls_ref=""; $ls_tipo=""; $ls_cta_res=""; $ls_sql="";
    $ldc_asignado=0; $ldc_ene=0; $ldc_feb=0; $ldc_mar=0; $ldc_abr=0; $ldc_may=0; $ldc_jun=0;
    $ldc_jul=0; $ldc_ago=0; $ldc_sep=0; $ldc_oct=0; $ldc_nov=0; $ldc_dic=0;
    $li_NumFile=0; $li_Read_Result=0; $li_valid=0; $li_distribuir=0; $li_nivel=0; $li_no_fila=0; $li_exec=0; //$li_rtn=0;
    $ldc_saldo_real_ant=0; $ldc_saldo_aprobado=0; $ldc_saldo_modificado=0;
    $lb_valido=true;
    
        $la_cuentas = array();
        $la_dencuentas = array();	
		$ls_formcont=$_SESSION["la_empresa"]["formcont"];
		$ls_formcont=str_replace('-','',$ls_formcont);
		$li_len=strlen($ls_formcont);
		$li_len=$li_len-9;
		$ls_ceros=$this->io_funciones->uf_cerosderecha("",$li_len);
        $la_cuentas[1]  = '100000000'.$ls_ceros;
        $la_cuentas[2]  = '110000000'.$ls_ceros;
        $la_cuentas[3]  = '111000000'.$ls_ceros;
        $la_cuentas[4]  = '111010100'.$ls_ceros;
        $la_cuentas[5]  = '111010200'.$ls_ceros;
        $la_cuentas[6]  = '111010201'.$ls_ceros;
        $la_cuentas[7]  = '111010202'.$ls_ceros;
        $la_cuentas[8]  = '111010203'.$ls_ceros;
        $la_cuentas[9]  = '111020000'.$ls_ceros;
        $la_cuentas[10] = '112000000'.$ls_ceros;
        $la_cuentas[11] = '112010000'.$ls_ceros;
        $la_cuentas[12] = '112020000'.$ls_ceros;
        $la_cuentas[13] = '112030000'.$ls_ceros;
        $la_cuentas[14] = '224010100'.$ls_ceros;
        $la_cuentas[15] = '112040000'.$ls_ceros;
        $la_cuentas[16] = '112050000'.$ls_ceros;
        $la_cuentas[17] = '112100000'.$ls_ceros;
        $la_cuentas[18] = '112110000'.$ls_ceros;
        $la_cuentas[19] = '112190000'.$ls_ceros;
        $la_cuentas[20] = '113000000'.$ls_ceros;
        $la_cuentas[21] = '119000000'.$ls_ceros;
        $la_cuentas[22] = '120000000'.$ls_ceros;
        $la_cuentas[23] = '121000000'.$ls_ceros;
        $la_cuentas[24] = '121010000'.$ls_ceros;
        $la_cuentas[25] = '121020000'.$ls_ceros;
        $la_cuentas[26] = '121030000'.$ls_ceros;
        $la_cuentas[27] = '122000000'.$ls_ceros;
        $la_cuentas[28] = '133000000'.$ls_ceros;
        $la_cuentas[29] = '225010000'.$ls_ceros;
        $la_cuentas[30] = '123050100'.$ls_ceros;
        $la_cuentas[31] = '123050200'.$ls_ceros;
        $la_cuentas[32] = '123011900'.$ls_ceros;
        $la_cuentas[33] = '124000000'.$ls_ceros;
        $la_cuentas[34] = '225020000'.$ls_ceros;
        $la_cuentas[35] = '129000000'.$ls_ceros;
        $la_cuentas[36] = '200000000'.$ls_ceros;
        $la_cuentas[37] = '210000000'.$ls_ceros;
        $la_cuentas[38] = '211000000'.$ls_ceros;
        $la_cuentas[39] = '211040000'.$ls_ceros;
        $la_cuentas[40] = '212000000'.$ls_ceros;
        $la_cuentas[41] = '224010000'.$ls_ceros;
        $la_cuentas[42] = '224010200'.$ls_ceros;
        $la_cuentas[43] = '224010300'.$ls_ceros;
        $la_cuentas[44] = '224010900'.$ls_ceros;
        $la_cuentas[45] = '224020000'.$ls_ceros;
        $la_cuentas[46] = '219090000'.$ls_ceros;
        $la_cuentas[47] = '220000000'.$ls_ceros;
        $la_cuentas[48] = '221020000'.$ls_ceros;
        $la_cuentas[49] = '222000000'.$ls_ceros;
        $la_cuentas[50] = '222020000'.$ls_ceros;
        $la_cuentas[51] = '222040000'.$ls_ceros;
        $la_cuentas[52] = '224000000'.$ls_ceros;
        $la_cuentas[53] = '224010400'.$ls_ceros;
        $la_cuentas[54] = '229090000'.$ls_ceros;
        $la_cuentas[55] = '300000000'.$ls_ceros;
        $la_cuentas[56] = '320000000'.$ls_ceros;
        $la_cuentas[57] = '321010000'.$ls_ceros;
        $la_cuentas[58] = '322010100'.$ls_ceros;
        $la_cuentas[59] = '323010000'.$ls_ceros;
        $la_cuentas[60] = '325010000'.$ls_ceros;
        $la_cuentas[61] = '325020000'.$ls_ceros;
        $la_cuentas[62] = '322020100'.$ls_ceros;
        $la_cuentas[63] = '322020200'.$ls_ceros;

        $la_dencuentas[1]  = 'ACTIVO';
        $la_dencuentas[2]  = 'Activo Circulante';
        $la_dencuentas[3]  = 'Activo Disponible';
        $la_dencuentas[4]  = 'Caja';
        $la_dencuentas[5]  = 'Bancos';
        $la_dencuentas[6]  = 'Bancos P�blicos';
        $la_dencuentas[7]  = 'Bancos Privados';
        $la_dencuentas[8]  = 'Bancos del Exterior';
        $la_dencuentas[9]  = 'Inversiones Temporales';
        $la_dencuentas[10] = 'Activo Exigibles';
        $la_dencuentas[11] = 'Inversiones Financieras en T�tulos y Valores a';
        $la_dencuentas[12] = 'Pr�stamos por Cobrar a Corto Plazo';
        $la_dencuentas[13] = 'Cuentas Comerciales por Cobrar a Corto Plazo';
        $la_dencuentas[14] = 'Provisi�n para cuentas incobrables';
        $la_dencuentas[15] = 'Otras Cuentas por Cobrar a Corto Plazo';
        $la_dencuentas[16] = 'Efectos Comerciales por Cobrar a Corto Plazo';
        $la_dencuentas[17] = 'Anticipos a Proveedores a Corto Plazo';
        $la_dencuentas[18] = 'Anticipos a Contratistas por Contratos a Corto Plazo';
        $la_dencuentas[19] = 'Rentas por Rrcaudar a Corto Plazo';
        $la_dencuentas[20] = 'Activo Realizable';
        $la_dencuentas[21] = 'Otros Activos Circulantes';
        $la_dencuentas[22] = 'Activo no Circulante';
        $la_dencuentas[23] = 'Inversi�nes Financieras a Largo Plazo';
        $la_dencuentas[24] = 'Acciones y Participaciones de Capital';
        $la_dencuentas[25] = 'T�tulos y Valores a Largo Plazo';
        $la_dencuentas[26] = 'Pr�stamos a Cobrar a Largo Plazo';
        $la_dencuentas[27] = 'Cuentas y Efectos por Cobrar a Mediano y Largo Plazo';
        $la_dencuentas[28] = 'Activo Fijo Bruto (Propiedad, Planta y Equipo)';
        $la_dencuentas[29] = 'Depreciaci�n Acumulada de Bienes de Uso';
        $la_dencuentas[30] = 'Construcciones en Proceso de bienes de Dominio Privado';
        $la_dencuentas[31] = 'Construcciones en Proceso de bienes de Dominio P�blico';
        $la_dencuentas[32] = 'Otros Bienes de Uso';
        $la_dencuentas[33] = 'Activo Intangible';
        $la_dencuentas[34] = 'Amortizaci�n Acumulada de Activos Intangibles';
        $la_dencuentas[35] = 'Otros Activos no Circulantes';
        $la_dencuentas[36] = 'PASIVO';
        $la_dencuentas[37] = 'Pasivo Circulante';
        $la_dencuentas[38] = 'Cuentas y Efectos por Pagar a';
        $la_dencuentas[39] = 'Efectos por Pagar a Corto Plazo';
        $la_dencuentas[40] = 'Deuda Publica a Corto Plazo';
        $la_dencuentas[41] = 'Provisiones';
        $la_dencuentas[42] = 'Provisi�n para Despidos';
        $la_dencuentas[43] = 'Provisi�n para P�rdida en el';
        $la_dencuentas[44] = 'Otras Provisiones';
        $la_dencuentas[45] = 'Reservas T�cnicas';
        $la_dencuentas[46] = 'Otros Pasivos Circulantes';
        $la_dencuentas[47] = 'Pasivo no Circulante';
        $la_dencuentas[48] = 'Efectos por Pagar a Mediano y Largo Plazo';
        $la_dencuentas[49] = 'Deuda P�blica a Largo Plazo';
        $la_dencuentas[50] = 'Deuda P�blica Interna por Prestamos por Pagar a Largo Plazo';
        $la_dencuentas[51] = 'Deuda P�blica Externa por Prestamos por Pagar a Largo Plazo';
        $la_dencuentas[52] = 'Provisiones Acumuladas y Reservas T�cnicas';
        $la_dencuentas[53] = 'Provision para Beneficios Sociales';
        $la_dencuentas[54] = 'Otros Pasivos no Circulantes';
        $la_dencuentas[55] = 'PATRIMONIO';
        $la_dencuentas[56] = 'Patrimonio Institucional';
        $la_dencuentas[57] = 'Capital Institucional';
        $la_dencuentas[58] = 'Transferencias de capital internas recibidas del sector privado';
        $la_dencuentas[59] = 'Reservas Legales y Estatutarias';
        $la_dencuentas[60] = 'Resultados Acumulados';
        $la_dencuentas[61] = 'Resultados del Ejercicio';
        $la_dencuentas[62] = 'Donaci�nes de Capital Internas';
        $la_dencuentas[63] = 'Donaci�nes de Capital Externas';
		
		$la_status[1]  = 'S';
        $la_status[2]  = 'S';
        $la_status[3]  = 'S';
        $la_status[4]  = 'C';
        $la_status[5]  = 'S';
        $la_status[6]  = 'C';
        $la_status[7]  = 'C';
        $la_status[8]  = 'C';
        $la_status[9]  = 'S';
        $la_status[10] = 'S';
        $la_status[11] = 'C';
        $la_status[12] = 'C';
        $la_status[13] = 'C';
        $la_status[14] = 'S';
        $la_status[15] = 'C';
        $la_status[16] = 'C';
        $la_status[17] = 'C';
        $la_status[18] = 'C';
        $la_status[19] = 'C';
        $la_status[20] = 'S';
        $la_status[21] = 'S';
        $la_status[22] = 'S';
        $la_status[23] = 'S';
        $la_status[24] = 'C';
        $la_status[25] = 'C';
        $la_status[26] = 'C';
        $la_status[27] = 'S';
        $la_status[28] = 'S';
        $la_status[29] = 'S';
        $la_status[30] = 'S';
        $la_status[31] = 'S';
        $la_status[32] = 'S';
        $la_status[33] = 'S';
        $la_status[34] = 'S';
        $la_status[35] = 'S';
        $la_status[36] = 'S';
        $la_status[37] = 'S';
        $la_status[38] = 'S';
        $la_status[39] = 'C';
        $la_status[40] = 'S';
        $la_status[41] = 'S';
        $la_status[42] = 'C';
        $la_status[43] = 'C';
        $la_status[44] = 'C';
        $la_status[45] = 'S';
        $la_status[46] = 'S';
        $la_status[47] = 'S';
        $la_status[48] = 'C';
        $la_status[49] = 'S';
        $la_status[50] = 'C';
        $la_status[51] = 'C';
        $la_status[52] = 'S';
        $la_status[53] = 'C';
        $la_status[54] = 'S';
        $la_status[55] = 'S';
        $la_status[56] = 'S';
        $la_status[57] = 'S';
        $la_status[58] = 'C';
        $la_status[59] = 'S';
        $la_status[60] = 'S';
        $la_status[61] = 'S';
        $la_status[62] = 'S';
        $la_status[63] = 'S';
              
        $li_rtn=1;
		$this->SQL->begin_transaction();	
        for ($i=1; (($i <= 63)&&($li_rtn==1)); $i++)   
        {                    
           $ls_codreport    = '0803';        
           $ls_cuenta       = $la_cuentas[$i];
		   $ls_denominacion = "";
           $ls_status       = "";
		   $ls_sql_det = "";
		   $rs_data_det = NULL;
		   $li_no_fila++;  
           $ls_tipo         = ""; 
           $ls_cta_res      = "";     
           $ls_modrep       = "0"; 
		   
		   $this->uf_obtener_datos_cuenta($as_codemp,$ls_cuenta,$ls_denominacion,$ls_status);
		   if(empty($ls_denominacion))
		   {
		    $ls_denominacion = $la_dencuentas[$i];
		   }
		   $ls_status       =  $la_status[$i];  
		   
           $ls_sql= " INSERT INTO scg_pc_reporte (codemp,cod_report,sc_cuenta,denominacion,status,asignado,distribuir,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,nivel,referencia,no_fila,tipo,cta_res,modrep,saldo_real_ant,saldo_apro,saldo_mod)".
                    " VALUES('".trim($as_codemp)."','".trim($ls_codreport)."','".trim($ls_cuenta)."','".trim($ls_denominacion)."','".$ls_status."',".$ldc_asignado.",".$li_distribuir.",".$ldc_ene.",".$ldc_feb.",".$ldc_mar.",".$ldc_abr.",".$ldc_may.",".$ldc_jun.",".$ldc_jul.",".$ldc_ago.",".$ldc_sep.",".$ldc_oct.",".$ldc_nov.",".$ldc_dic.",".$li_nivel.",'".trim($ls_ref)."',"
                               .$li_no_fila.",'".$ls_tipo."','".$ls_cta_res."','".$ls_modrep."'".",'".$ldc_saldo_real_ant."','".$ldc_saldo_aprobado."','".$ldc_saldo_modificado."');" ;
           $rs_data = $this->SQL->execute($ls_sql);                        
            if ($rs_data===false)
            {
                $this->msg->message("Error en m�todo uf_cargar_cuentas_balancegeneral_ins08 ".$this->io_funciones->uf_convertirmsg($this->SQL->message));
                $lb_valido = false;
                $li_rtn=0;
            }
            else
            {
                $li_rtn=1;
            }
			
			switch(trim($ls_cuenta))
		    {
			 case '113000000'.$ls_ceros :
										 $ls_sql_det = "SELECT sc_cuenta, denominacion, status FROM scg_cuentas WHERE codemp = '".$as_codemp."' AND sc_cuenta LIKE '113%' AND sc_cuenta <> '".'113000000'.$ls_ceros."' ORDER BY sc_cuenta";
										 $rs_data_det=$this->SQL->select($ls_sql_det); 
										 if($rs_data_det === false)
										 {
										  $this->io_msg->message("Error al seleccionar detalles de la cuenta ".trim($ls_cuenta)." ".$this->io_fun->uf_convertirmsg($this->io_sql->message));
										  return false;
										 }
										 else
										 {
										  while(!$rs_data_det->EOF)
										  {
										   $ls_cuenta = $rs_data_det->fields["sc_cuenta"];
										   $ls_status = $rs_data_det->fields["status"];
										   $ls_denominacion = $rs_data_det->fields["denominacion"];
										   $li_no_fila++;
										   $ls_sql= " INSERT INTO scg_pc_reporte (codemp,cod_report,sc_cuenta,denominacion,status,asignado,distribuir,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,nivel,referencia,no_fila,tipo,cta_res,modrep,saldo_real_ant,saldo_apro,saldo_mod)".
												" VALUES('".trim($as_codemp)."','".trim($ls_codreport)."','".trim($ls_cuenta)."','".trim($ls_denominacion)."','".$ls_status."',".$ldc_asignado.",".$li_distribuir.",".$ldc_ene.",".$ldc_feb.",".$ldc_mar.",".$ldc_abr.",".$ldc_may.",".$ldc_jun.",".$ldc_jul.",".$ldc_ago.",".$ldc_sep.",".$ldc_oct.",".$ldc_nov.",".$ldc_dic.",".$li_nivel.",'".trim($ls_ref)."',"
														   .$li_no_fila.",'".$ls_tipo."','".$ls_cta_res."','".$ls_modrep."'".",'".$ldc_saldo_real_ant."','".$ldc_saldo_aprobado."','".$ldc_saldo_modificado."');" ;
										   $rs_data = $this->SQL->execute($ls_sql);                        
											if ($rs_data===false)
											{
												$this->msg->message("Error en m�todo uf_cargar_cuentas_balancegeneral_ins08 ".$this->io_funciones->uf_convertirmsg($this->SQL->message));
												$lb_valido = false;
												$li_rtn=0;
											}
											else
											{
												$li_rtn=1;
											}
										  
										   $rs_data_det->MoveNext();
										  }
										 }
			 break;
			 
			 case '129000000'.$ls_ceros :
										 $ls_sql_det = "SELECT sc_cuenta, denominacion, status FROM scg_cuentas WHERE codemp = '".$as_codemp."' AND sc_cuenta LIKE '129%' AND sc_cuenta <> '".'129000000'.$ls_ceros."' ORDER BY sc_cuenta";
										 $rs_data_det=$this->SQL->select($ls_sql_det); 
										 if($rs_data_det === false)
										 {
										  $this->io_msg->message("Error al seleccionar detalles de la cuenta ".trim($ls_cuenta)." ".$this->io_fun->uf_convertirmsg($this->io_sql->message));
										  return false;
										 }
										 else
										 {
										  while(!$rs_data_det->EOF)
										  {
										   $ls_cuenta = $rs_data_det->fields["sc_cuenta"];
										   $ls_status = $rs_data_det->fields["status"];
										   $ls_denominacion = $rs_data_det->fields["denominacion"];
										   $li_no_fila++;
										   $ls_sql= " INSERT INTO scg_pc_reporte (codemp,cod_report,sc_cuenta,denominacion,status,asignado,distribuir,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,nivel,referencia,no_fila,tipo,cta_res,modrep,saldo_real_ant,saldo_apro,saldo_mod)".
												" VALUES('".trim($as_codemp)."','".trim($ls_codreport)."','".trim($ls_cuenta)."','".trim($ls_denominacion)."','".$ls_status."',".$ldc_asignado.",".$li_distribuir.",".$ldc_ene.",".$ldc_feb.",".$ldc_mar.",".$ldc_abr.",".$ldc_may.",".$ldc_jun.",".$ldc_jul.",".$ldc_ago.",".$ldc_sep.",".$ldc_oct.",".$ldc_nov.",".$ldc_dic.",".$li_nivel.",'".trim($ls_ref)."',"
														   .$li_no_fila.",'".$ls_tipo."','".$ls_cta_res."','".$ls_modrep."'".",'".$ldc_saldo_real_ant."','".$ldc_saldo_aprobado."','".$ldc_saldo_modificado."');" ;
										   $rs_data = $this->SQL->execute($ls_sql);                        
											if ($rs_data===false)
											{
												$this->msg->message("Error en m�todo uf_cargar_cuentas_balancegeneral_ins08 ".$this->io_funciones->uf_convertirmsg($this->SQL->message));
												$lb_valido = false;
												$li_rtn=0;
											}
											else
											{
												$li_rtn=1;
											}
										  
										   $rs_data_det->MoveNext();
										  }
										}
			 break;
			}            
        }
        if ($li_rtn==1)
        {
            $this->SQL->commit();
            $lb_valido = true;
            $this->msg->message($is_msg_error = "Cuentas de Balance General Instructivo 8 cargadas..".$this->io_funciones->uf_convertirmsg($this->SQL->message));    ;
        }
        else
        {
            $this->SQL->rollback();
            $lb_valido = false;
            $this->msg->message(" Cuentas de Balance General Instructivo 8 no cargadas..".$this->io_funciones->uf_convertirmsg($this->SQL->message));
        }
    return $lb_valido;    
 } // fin de uf_cargar_cuentas_balancegeneral_ins08
 
 function uf_obtener_datos_cuenta($as_codemp,$as_cuenta,&$as_denominacion,&$as_status = "S")
 {
  	////////////////////////////////////////////////////////////////////////////////////////////////
    //    Function:  uf_obtener_datos_cuenta
    //    Access:  public
    //    Description:  Este m�todo retorna la denominacion y status de la cuenta, si esta se encuentra
    //                  definida en el Plan de Cuentas
    //               
    /////////////////////////////////////////////////////////////////////////////////////////////////
	
	$ls_sql= "SELECT denominacion, status FROM scg_cuentas ".
	         " WHERE codemp = '".$as_codemp."' AND sc_cuenta = '".trim($as_cuenta)."'";
    $rs_data = $this->SQL->execute($ls_sql);
	if($rs_data === false)
	{
	 $this->msg->message("Error en m�todo uf_obtener_datos_cuenta ".$this->io_funciones->uf_convertirmsg($this->SQL->message));
	}
	else
	{
	 if(!$rs_data->EOF)
	 {
	  $as_denominacion = $rs_data->fields["denominacion"];
	  $as_status = $rs_data->fields["status"];
	 }
	}
 }



} //fin de la class SCG_procesos
?>