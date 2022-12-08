/***********************************************************************************
* @Javascript para el manejo de la Session
* @fecha de creaci�n: 15/07/2009
* @autor: Ing. Arnaldo Su�rez
* **************************
* @fecha modificacion  
* @autor  
* @descripcion  
***********************************************************************************/

function mostrarInformacionUsuario()
{
	var rutaarchivo ='../../controlador/sss/sigesp_ctr_sss_sesion.php';
   	Ext.QuickTips.init();
   	var myJSONObject ={
		"operacion": "informacionusuario",
		"codsis": sistema
	};
	var objUsuario=Ext.util.JSON.encode(myJSONObject);
	var parametros = 'ObjSon='+objUsuario;
	Ext.Ajax.request({
	url : rutaarchivo,
	params : parametros,
	method: 'POST',
	success: function (resultado, request)
	{ 
		obj   = eval('('+resultado.responseText+')');
		if(obj.raiz[0].valido==true)
		{
			divsistema = document.getElementById('sistema');
			divusuario = document.getElementById('usuario');
			divhora = document.getElementById('hora');
			divinactivo = document.getElementById('inactivo');
			divsistema.innerHTML = obj.raiz[0].nomsis;
			divusuario.innerHTML = obj.raiz[0].apeusu+', '+obj.raiz[0].nomusu;
			divhora.innerHTML = obj.raiz[0].fecha;
			//divinactivo.innerHTML = 'Tiempo de inactividad '+obj.raiz[0].inactivo+' min';
			//divinactivo.innerHTML = 'Empresa: '+obj.raiz[0].empresa;
		}
		else
		{
			Ext.MessageBox.alert('Error', obj.raiz[0].mensaje);
			setTimeout('volverEscritorio()',5000);
		}
	},
	failure: function (result,request) 
	{ 
		Ext.MessageBox.alert('Error', 'No se pudo Cargar el sistema. Favor Contacte al administrador del Sistema.');
		setTimeout('volverEscritorio()',5000);
	}
	});
}

/***********************************************************************************
* @Javascript para el manejo de la Session
* @fecha de creaci�n: 15/07/2009
* @autor: Ing. Arnaldo Su�rez
* **************************
* @fecha modificacion  
* @autor  
* @descripcion  
***********************************************************************************/

function informacionUsuarioInicio(titulo)
{
	Ext.QuickTips.init();
   	var myJSONObject ={
		"operacion": "informacionusuarioinicio"
	};
	var objUsuario=Ext.util.JSON.encode(myJSONObject);
	var parametros = 'ObjSon='+objUsuario;
	Ext.Ajax.request({
	url : 'controlador/sss/sigesp_ctr_sss_sesion.php',
	params : parametros,
	method: 'POST',
	success: function (resultado, request)
	{ 
		obj   = eval('('+resultado.responseText+')');
		if(obj.raiz[0].valido==true)
		{
			divsistema = document.getElementById('sistema');
			divusuario = document.getElementById('usuario');
			divhora = document.getElementById('hora');
			divinactivo = document.getElementById('inactivo');
			divsistema.innerHTML = titulo;
			divusuario.innerHTML = obj.raiz[0].apeusu+', '+obj.raiz[0].nomusu;
			divhora.innerHTML = obj.raiz[0].fecha;
			//divinactivo.innerHTML = 'Tiempo de inactividad '+obj.raiz[0].inactivo+' min';
			//divinactivo.innerHTML = 'Empresa: '+obj.raiz[0].empresa;
		}
		else
		{
			Ext.MessageBox.alert('Error', obj.raiz[0].mensaje);
			setTimeout('volverEscritorio()',5000);
		}
	},
	failure: function (result,request) 
	{ 
		Ext.MessageBox.alert('Error', 'No se pudo Cargar el sistema. Favor Contacte al administrador del Sistema.');
		setTimeout('volverEscritorio()',5000);
	}
	});
}

/*********************************************************************
* @Funci�n que valida que haya una sesion abierta
* @Par�metros: controlador // Ruta del Controlador que verifica el inicio de Sesion
*              pantalla    // Nombre f�sico de la pantalla que se desea validar
* @Valor de Retorno: 
* @Fecha de Creaci�n: 13/07/2009
**********************************************************************/

function validarSesion(controlador,pantalla){
	var myJSONObject ={
		"operacion":"ObtenerSesion" ,
		"pantalla":pantalla,
		"codsis":sistema
	};

	var ObjSon=Ext.util.JSON.encode(myJSONObject);
	var parametros ='ObjSon='+ObjSon; 
    Ext.Ajax.request({
	url : controlador,
	params : parametros,
	method: 'POST',
	success: function ( result, request ) { 
            respuesta = result.responseText;
		    arregloDatos = respuesta.split("|");
		    switch (arregloDatos[1]){
		    	case 'nosesion':
		    		Ext.Msg.show({
     		   			title:'Error',
     		   			msg: 'No ha iniciado sesion',
     		   			buttons: Ext.Msg.OK,
     		   			fn: irInicioSesion,
     		   			animEl: 'elId',
     		   			icon: Ext.MessageBox.ERROR
     				});
					return false;
		    		break;
		    		
		    	case '1':
		    		return true;
		    		break;
		    		
		    	case '0':
		    		Ext.Msg.show({
		   				title:'Mensaje',
		   				msg: 'No tiene permiso para acceder a la pantalla',
			   			buttons: Ext.Msg.OK,
			   			fn: volverIndexModulo,
			   			animEl: 'elId',
			   			icon: Ext.MessageBox.INFO
					});
			   		return false;
		    		break;
		    }
	},
	failure: function ( result, request){ 
				Ext.MessageBox.alert('Error', 'Problema de comunicacion con el servidor contacte al administrador');
				return false;
		}
	});
}

/*********************************************************************
* @Funci�n que valida que haya una sesion abierta
* @Valor de Retorno: 
* @Fecha de Creaci�n: 13/07/2009
**********************************************************************/
function validarInicioSesion(){
	var myJSONObject ={
		"operacion":"ObtenerInicioSesion"
	};

	var ObjSon=Ext.util.JSON.encode(myJSONObject);
	var parametros ='ObjSon='+ObjSon; 
    Ext.Ajax.request({
	url : 'controlador/sss/sigesp_ctr_sss_sesion.php',
	params : parametros,
	method: 'POST',
	success: function ( result, request ) { 
            respuesta = result.responseText;
		    arregloDatos = respuesta.split("|");
		    if(arregloDatos[1]=="0"){
		    	Ext.Msg.show({
     		   		title:'Error',
     		   		msg: 'No ha iniciado sesion',
     		   		buttons: Ext.Msg.OK,
     		   		fn: irInicio,
     		   		animEl: 'elId',
     		   		icon: Ext.MessageBox.ERROR
     			});
     			return false;
		    }
		    else{
		    	return true;
		    }
	},
	failure: function ( result, request){ 
				Ext.MessageBox.alert('Error', 'Problema de comunicacion con el servidor contacte al administrador');
				return false;
		}
	});
}

/*********************************************************************
* @Funci�n que valida que haya una sesion abierta
* @Valor de Retorno: 
* @Fecha de Creaci�n: 13/07/2009
**********************************************************************/
function destruirSesion(){
	var myJSONObject ={
		"operacion":"DestruirSesion" 
	};

	var ObjSon=Ext.util.JSON.encode(myJSONObject);
	var parametros ='ObjSon='+ObjSon; 
    Ext.Ajax.request({
	url : 'controlador/sss/sigesp_ctr_sss_sesion.php',
	params : parametros,
	method: 'POST',
	success: function ( result, request ) { 
            respuesta = result.responseText;
	},
	failure: function ( result, request){ 
				Ext.MessageBox.alert('Error', 'Problema de comunicacion con el servidor contacte al administrador');
				return false;
		}
	});
}

/***********************************************************************************
* @Funci�n para que regrese a la pantalla de conexion
* @parametros: 
* @retorno: 
* @fecha de creaci�n: 17/07/2008
* @autor: Ing. Arnaldo Su�rez
************************************************************************************
* @fecha modificaci�n: 
* @descripci�n: 
* @autor: 
***********************************************************************************/		
function irInicioSesion(){
	location.href='../../sigesp_vis_index.php';
};


/***********************************************************************************
* @Funci�n para regresar al escritorio
* @parametros: 
* @retorno: 
* @fecha de creaci�n: 14/10/2008
* @autor: Ing. Yesenia Moreno.
************************************************************************************
* @fecha modificaci�n: 15/07/2009
* @descripci�n: Se ajust� para mostrarse con la version 1 de Sigesp Php
* @autor: Ing. Arnaldo Su�rez
***********************************************************************************/	
function volverEscritorio()
{
	parent.location.target='_parent';
	parent.location.href='../../index_modules.php';
	
}

function volverIndexModulo()
{
	parent.location.target='_parent';
	var modulo = sistema.toLowerCase();
	parent.location.href='sigesp_vis_'+modulo+'_index.php'
	
}

function irInicio(){
	location.href='sigesp_vis_index.php';
};

/*********************************************************************
* @Funci�n que valida acceso general a un sistema
* @Par�metros: controlador // Ruta del Controlador que verifica el inicio de Sesion
*              pantalla    // Nombre f�sico de la pantalla que se desea validar
* @Valor de Retorno: 
* @Fecha de Creaci�n: 13/07/2009
**********************************************************************/

function validarAccesoSistema()
{
	var myJSONObject ={
		"operacion":"accesosistema",
		"codsis":sistema
	};
	
	var ObjSon=Ext.util.JSON.encode(myJSONObject);
	var parametros ='ObjSon='+ObjSon; 
    Ext.Ajax.request({
	url :'../../controlador/sss/sigesp_ctr_sss_sesion.php',
	params : parametros,
	method: 'POST',
	success: function ( result, request ) { 
            respuesta = result.responseText;
		    arregloDatos = respuesta.split("|");
		    if(arregloDatos[0]=='0')
            {
            	Ext.Msg.show({
     		   	title:'Error',
     		   	msg: 'No tiene permiso para acceder al modulo',
     		   	buttons: Ext.Msg.OK,
     		   	fn: volverEscritorio,
     		   	animEl: 'elId',
     		   	icon: Ext.MessageBox.ERROR
     			})
				 return false;
            }
			else
			{
				//validarReleaseSistema();
				mostrarInformacionUsuario();
			}
	}
	});
}

/*********************************************************************
* @Funcion que obtiene un valor de la variable de session
* @Parametros: 
* @Fecha de Creacion: 20/08/2009
**********************************************************************/

function obtenerSesionEmpresa()
{
	var myJSONObject ={
		"operacion":"variablesesion"
	};
	
	
	var ObjSon=Ext.util.JSON.encode(myJSONObject);
	var parametros ='ObjSon='+ObjSon; 
    Ext.Ajax.request({
	url :'../../controlador/sss/sigesp_ctr_sss_sesion.php',
	params : parametros,
	method: 'POST',
	success: function ( result, request ) { 
    		//valor = eval('('+result.responseText+')');
    		//empresa = valor[0];
    	//empresa = eval('('+result.responseText+')');
	}
	});
    
}

function validarReleaseSistema()
{
	var cadenajson = {
			"operacion":"verificarrelease"
	};
	var rutarelease = "";
	switch(sistema)
	{
		case  'CFG' : rutarelease = "../../controlador/cfg/sigesp_ctr_cfg_release.php";
		break;
		
		case  'SEP' : rutarelease = "../../controlador/sep/sigesp_ctr_sep_release.php";
		break;
		
		case  'SOC' : rutarelease = "../../controlador/soc/sigesp_ctr_soc_release.php";
		break;
		
		case  'CXP' : rutarelease = "../../controlador/cxp/sigesp_ctr_cxp_release.php";
		break;
		
		case  'SCB' : rutarelease = "../../controlador/scb/sigesp_ctr_scb_release.php";
		break;
		
		case  'SPG' : rutarelease = "../../controlador/spg/sigesp_ctr_spg_release.php";
		break;
		
		case  'SCG' : rutarelease = "../../controlador/scg/sigesp_ctr_scg_release.php";
		break;
		
		case  'RPC' : rutarelease = "../../controlador/rpc/sigesp_ctr_rpc_release.php";
		break;
		
		case  'SST' : rutarelease = "../../controlador/sst/sigesp_ctr_sst_release.php";
		break;
	}
	
	ObjSon=Ext.util.JSON.encode(cadenajson);
	parametros ='ObjSon='+ObjSon; 
    Ext.Ajax.request({
	url :rutarelease,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ) { 
    	obj   = eval('('+resultado.responseText+')');
		if(obj.raiz[0].valido==false)
		{
			Ext.MessageBox.alert('Error', obj.raiz[0].mensaje,volverEscritorio);
			setTimeout('volverEscritorio()',5000);
		}
	}
	});
	
}

