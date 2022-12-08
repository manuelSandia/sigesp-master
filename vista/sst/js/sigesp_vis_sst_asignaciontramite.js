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
var formasignaciontramite    = null;
var comcampocatalogotramite  = null;
var comcampocatalogousuarios = null;

//manejo de la session
var rutaSesion    = '../../controlador/sss/sigesp_ctr_sss_sesion.php';
validarSesion(rutaSesion,vista);
mostrarInformacionUsuario();
//fin manejo de la session

Ext.onReady(function(){
	Ext.BLANK_IMAGE_URL = '../../base/librerias/js/ext/resources/images/default/s.gif';
	
	//creando datastore y columnmodel para el catalogo de asiganciones
	var registro_asignacion = Ext.data.Record.create([
						{name: 'numtramite'},
						{name: 'numasiant'},
						{name: 'codprop'},
						{name: 'tipprop'},
						{name: 'coddocenv'},
						{name: 'tipdocenv'},
						{name: 'fecenv'},
						{name: 'codunienv'}
					]);
	
	var dsasignacion =  new Ext.data.Store({
			reader: new Ext.data.JsonReader({root: 'raiz',id: "id"},registro_asignacion)
		});
						
	var colmodelcatasignacion = new Ext.grid.ColumnModel([
          				{header: "Tramite N&#250;mero", width: 40, sortable: true,   dataIndex: 'numtramite'},
          				{header: "Documeto", width: 40, sortable: true, dataIndex: 'coddocenv'},
          				{header: "Tipo", width: 20, sortable: true, dataIndex: 'tipdocenv'},
          				{header: "Fecha", width: 35, sortable: true, dataIndex: 'fecenv',renderer:formatoFechaHoraGrid}
        		]);
	//fin creando datastore y columnmodel para el catalogo de asiganciones
	
	//creando arreglo de campos ocultos para setear con el catalogo
	var arrcamposocultos=['numasiant','codunienv','tipprop','codprop'];
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
							parametros: "ObjSon={'operacion': 'catalogoasignaenv'}",
							arrfiltro:[	{etiqueta:'Numero',id:'codigotra',valor:'numtramite'},
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
							labelvalue:'',
							anchocoletiqueta:0.55,
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
	
	//creando datastore y columnmodel para el catalogo de unidades
	var registro_unidad = Ext.data.Record.create([
						{name: 'coduniadm'},
						{name: 'denuniadm'}   
					]);
	
	var dsunidad =  new Ext.data.Store({
			reader: new Ext.data.JsonReader({root: 'raiz',id: "id"},registro_unidad)
		});
						
	var colmodelcatunidad = new Ext.grid.ColumnModel([
          				{header: "Codigo", width: 20, sortable: true,   dataIndex: 'coduniadm'},
          				{header: "Descripcion", width: 40, sortable: true, dataIndex: 'denuniadm'}
        		]);
	//fin creando datastore y columnmodel para el catalogo de unidades ejecutoras
	
	
	
	//componente campo catalogo para el campo unidad receptora
	comcampocatalogounirec = new com.sigesp.vista.comCampoCatalogo({
							titvencat: 'Catalogo de Unidades',
							anchoformbus: 450,
							altoformbus:130,
							anchogrid: 450,
							altogrid: 400,
							anchoven: 500,
							altoven: 400,
							datosgridcat: dsunidad,
							colmodelocat: colmodelcatunidad,
							rutacontrolador:'../../controlador/sst/sigesp_ctr_sst_catalogosst.php',
							parametros: "ObjSon={'operacion': 'catalogounidad'}",
							arrfiltro:[{etiqueta:'Codigo',id:'counen',valor:'coduniadm'},
									   {etiqueta:'Descripcion',id:'deunen',valor:'denuniadm'}],
							posicion:'position:absolute;left:5px;top:35px',
							idfieldset:'catunidadrec',
							tittxt:'Unidad Receptora',
							idtxt:'codunirec',
							campovalue:'coduniadm',
							anchoetiquetatext:130,
							anchotext:100,
							anchocoltext:0.40,
							idlabel:'denunirec',
							labelvalue:'denuniadm',
							anchocoletiqueta:0.55,
							anchoetiqueta:150,
							tipbus:'L',
							binding:'C',
							hiddenvalue:'',
							defaultvalue:'',
							allowblank:false
						});
	//fin componente campo catalogo para el campo unidad receptora 
	
	//creando datastore y columnmodel para el catalogo de usuarios
	var registro_usuario = Ext.data.Record.create([
						{name: 'codusu'},
						{name: 'nomusu'}   
					]);
	
	var dsusuario =  new Ext.data.Store({
			reader: new Ext.data.JsonReader({root: 'raiz',id: "id"},registro_usuario)
		});
						
	var colmodelcatusuario = new Ext.grid.ColumnModel([
          				{header: "Codigo", width: 20, sortable: true,   dataIndex: 'codusu'},
          				{header: "Nombre", width: 40, sortable: true, dataIndex: 'nomusu'}
        		]);
	//fin creando datastore y columnmodel para el catalogo de unidades ejecutoras
	
	//componente campo catalogo para el campo usuario
	comcampocatalogousuarios = new com.sigesp.vista.comCampoCatalogo({
							titvencat: 'Catalogo de Usuarios',
							anchoformbus: 450,
							altoformbus:130,
							anchogrid: 450,
							altogrid: 400,
							anchoven: 500,
							altoven: 400,
							datosgridcat: dsusuario,
							colmodelocat: colmodelcatusuario,
							rutacontrolador:'../../controlador/sst/sigesp_ctr_sst_catalogosst.php',
							parametros: "ObjSon={'operacion': 'catalogousuarios'}",
							arrfiltro:[{etiqueta:'Codigo',id:'codiusu',valor:'codusu'},
									   {etiqueta:'Descripcion',id:'descusu',valor:'nomusu'}],
							posicion:'position:absolute;left:5px;top:65px',
							idfieldset:'catusu',
							tittxt:'Asignar a Usuario',
							idtxt:'codusurec',
							campovalue:'codusu',
							anchoetiquetatext:130,
							anchotext:100,
							anchocoltext:0.40,
							idlabel:'nomusu',
							labelvalue:'nomusu',
							anchocoletiqueta:0.55,
							anchoetiqueta:150,
							tipbus:'L',
							binding:'C',
							hiddenvalue:'',
							defaultvalue:'',
							allowblank:false
						});
	//fin componente campo catalogo para el campo usuario
	
	
	//funcion para la construcion del formulario de la solicitud
	function getFormRegistroSolicitud(){
		Ext.QuickTips.init();
		var Xpos = ((screen.width/2)-(800/2));
		formasignaciontramite = new Ext.FormPanel({
						width: 800,
						height: 300,
						applyTo: 'formulario_asignaciontramite',
						title: 'Asignar Tramites',
						frame:true,
						style:'position:absolute;margin-left:'+Xpos+'px;margin-top:90px;',
						items:[	comcampocatalogotramite.fieldsetCatalogo,
								comcampocatalogounirec.fieldsetCatalogo,
								comcampocatalogousuarios.fieldsetCatalogo,
								{
								layout: "column",
								defaults: {border: false},
								style:'position:absolute;left:15px;top:105px',
								width:405,
								items: [{
										layout : "form",
					        			border : false,
										labelWidth: 130,
					        			columnWidth : 0.7,
					        			items : [{
													xtype: 'textfield',
													fieldLabel: 'Documento a Enviar',
													labelSeparator: '',
													id: 'coddocenv',
													autoCreate: {tag: 'input',type: 'text',size: '100',autocomplete: 'off',maxlength: '100'},
													width: 130,
													binding:true,
												   	hiddenvalue:'',
												   	defaultvalue:'',
												   	allowBlank:false
												}]
										},{
										layout : "form",
					        			border : false,
										labelWidth: 0,
					        			columnWidth :0.3,
					        			items : [{
													xtype:'button',
													id:'botdoc',
													iconCls: 'menubuscar',
													handler: function (){
															mostrarCatalogoDocumento2('coddocenv','tipdocenv');
													}
												}]
										}]
								},{
								layout : "column",
					        	defaults : {border : false},
								style:'position:absolute;left:15px;top:135px',
								items : [{
										layout : "form",
					        			border : false,
										labelWidth: 130,
					        			columnWidth : 1,
					        			items : [{
													xtype: 'textarea',
													fieldLabel: 'Observaci&#243;n',
													labelSeparator :'',
													id: 'obsenv',
													width: 450,
													binding:true,
												   	hiddenvalue:'',
												   	defaultvalue:'',
												   	allowBlank:false
												}]
										}]
								},{
									xtype: 'hidden',
									id: 'tipdocenv',
									binding:true,
									defaultvalue:''
								},{
									xtype: 'hidden',
									id: 'numasiant',
									binding:true,
									defaultvalue:''
								},{
									xtype: 'hidden',
									id: 'codunienv',
									binding:true,
									defaultvalue:''
								},{
									xtype: 'hidden',
									id: 'tipprop'
								},{
									xtype: 'hidden',
									id: 'codprop'
								}]
		});
	}
	//fin funcion para la construcion del formulario de la solicitud
	
	//llamado a funciones.........
	getFormRegistroSolicitud();
});

function grabarAsignacionTramite(){
	var cadjson = getItems(formasignaciontramite,"incluir","N",null,null);
	try {
		var objjson = Ext.util.JSON.decode(cadjson);
		if (typeof(objjson) == 'object') {
			var parametros = 'ObjSon='+cadjson;
			Ext.Ajax.request({
				url : '../../controlador/sst/sigesp_ctr_sst_asignaciontramite.php',
				params : parametros,
				method: 'POST',
				success: function ( resultad, request ){ 
	        				datos = resultad.responseText;
	       					resultado = datos.split("|");
							if(resultado[1]=="1"){
								Ext.MessageBox.alert('Mensaje','El tramite fue asignado');
							}
							else{
								Ext.MessageBox.alert('Error','Ha ocurrido un error en la operaci&#243;n, por favor intente de nuevo');
							}
							limpiarFormulario(formasignaciontramite);
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

