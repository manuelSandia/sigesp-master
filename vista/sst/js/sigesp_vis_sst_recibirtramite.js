/***********************************************************************************
* @Archivo JavaScript que incluye tanto los componentes como los eventos asociados 
* a  
* @fecha de creacion: 00/00/2009
* @autor: Ing. Gerardo Cordero
************************************************************************************
* @fecha modificacion:
* @descripcion:
* @autor:
***********************************************************************************/
var formrecibirtramite = null;

//manejo de la session
var rutaSesion    = '../../controlador/sss/sigesp_ctr_sss_sesion.php';
validarSesion(rutaSesion,vista);
mostrarInformacionUsuario();
//fin manejo de la session


Ext.onReady(function(){
	Ext.BLANK_IMAGE_URL = '../../base/librerias/js/ext/resources/images/default/s.gif';
	
	//creando datastore y columnmodel para el catalogo de tramites
	var registro_asignacion = Ext.data.Record.create([
						{name: 'numtramite'},
						{name: 'numasi'},
						{name: 'coddocenv'},
						{name: 'tipdocenv'},
						{name: 'fecenv'}      
					]);
	
	var dsasignacion =  new Ext.data.Store({
			reader: new Ext.data.JsonReader({root: 'raiz',id: "id"},registro_asignacion)
		});
						
	var colmodelcatasignacion = new Ext.grid.ColumnModel([
          				{header: "Numero", width: 40, sortable: true,   dataIndex: 'numtramite'},
          				{header: "Documeto", width: 40, sortable: true, dataIndex: 'coddocenv'},
          				{header: "Tipo", width: 20, sortable: true, dataIndex: 'tipdocenv'},
          				{header: "Fecha", width: 35, sortable: true, dataIndex: 'fecenv',renderer:formatoFechaHoraGrid}
        		]);
	//fin creando datastore y columnmodel para el catalogo de tramites
	
    //creando arreglo de campos ocultos para setear con el catalogo
	var arrcamposocultos=['numasi'];
	//fin creando arreglo de campos ocultos para setear con el catalogo
		
	//componente campo catalogo para el tramite asignado
	comcampocatalogotramite = new com.sigesp.vista.comCampoCatalogo({
							titvencat: 'Catalogo de Tramites',
							anchoformbus: 450,
							altoformbus:130,
							anchogrid: 500,
							altogrid: 400,
							anchoven: 550,
							altoven: 500,
							datosgridcat: dsasignacion,
							colmodelocat: colmodelcatasignacion,
							rutacontrolador:'../../controlador/sst/sigesp_ctr_sst_catalogosst.php',
							parametros: "ObjSon={'operacion': 'catalogoasignarec'}",
							arrfiltro:[ {etiqueta:'Numero',id:'codigotra',valor:'numtramite'},
										{etiqueta:'Documento',id:'codigodoc',valor:'coddocenv'}],
							posicion:'position:absolute;left:5px;top:5px',
							idfieldset:'cattraasi',
							tittxt:'N&#250;mero Tramite',
							idtxt:'numtramite',
							campovalue:'numtramite',
							anchoetiquetatext:130,
							anchotext:130,
							anchocoltext:0.40,
							idlabel:'nomprop',
							labelvalue:'nomprop',
							anchocoletiqueta:0.53,
							anchoetiqueta:150,
							tipbus:'L',
							datosocultos:1,
							camposocultos:arrcamposocultos,
							binding:'C',
							hiddenvalue:'',
							defaultvalue:'',
							allowblank:false
							
						});
	//fin componente campo catalogo para el tramite asignado
	
	//funcion para la construcion del formulario de la solicitud
	function getFormRecibirTramite(){
		Ext.QuickTips.init();
		var Xpos = ((screen.width/2)-(800/2));
		formrecibirtramite = new Ext.FormPanel({
						width: 800,
						height: 300,
						applyTo: 'formulario_recibirtramite',
						title: 'Recepci&#243;n Tramite',
						frame:true,
						style:'position:absolute;margin-left:'+Xpos+'px;margin-top:70px;',
						items:[comcampocatalogotramite.fieldsetCatalogo,{
								layout : "column",
					        	defaults : {border : false},
								style:'position:absolute;left:15px;top:50px',
								items : [{
												layout : "form",
					        					border : false,
												labelWidth: 130,
					        					columnWidth : 1,
					        					items : [{
															xtype: 'textarea',
															fieldLabel: 'Observaci&#243;n',
															labelSeparator :'',
															id: 'obsrec',
															width: 450,
															binding:true,
												   			hiddenvalue:'',
												   			defaultvalue:'',
												   			allowBlank:false
														}]
										}]
								},{
									xtype: 'hidden',
									id: 'numasi',
									binding:true,
									defaultvalue:''
								}]
		});
	}
	//fin funcion para la construcion del formulario de la solicitud
	
	//llamado a funciones.........
	getFormRecibirTramite();
});

function grabarAsignacionTramite(){
	var cadjson = getItems(formrecibirtramite,"incluir","N",null,null);
	try {
		var objjson = Ext.util.JSON.decode(cadjson);
		if (typeof(objjson) == 'object') {
			var parametros = 'ObjSon='+cadjson;
			Ext.Ajax.request({
				url : '../../controlador/sst/sigesp_ctr_sst_recibirtramite.php',
				params : parametros,
				method: 'POST',
				success: function ( resultad, request ){ 
	        				datos = resultad.responseText;
	       					resultado = datos.split("|");
							if(resultado[1]=="1"){
								Ext.MessageBox.alert('Mensaje','La recepcion del tramite fue procesada');
							}
							else{
								Ext.MessageBox.alert('Error','Ha ocurrido un error en la operaci&#243;n, por favor intente de nuevo');
							}
							limpiarFormulario(formrecibirtramite);
				},
				failure: function ( result, request){ 
							Ext.MessageBox.alert('Error','Ha ocurrido un error en la operaci&#243;n, por favor intente de nuevo'); 
				} 
			});
		}
	}
	catch(e){
		//alert('error'+e);
	}
}

var banderaGrabar = true;
var grabarPersonalizado = grabarAsignacionTramite;

