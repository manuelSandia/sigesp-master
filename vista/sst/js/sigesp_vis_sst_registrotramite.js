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
var formregistrotramite          = '';
var comcampocatalogoproveedor    = '';
var comcampocatalogobeneficiario = '';

//manejo de la session
var rutaSesion    = '../../controlador/sss/sigesp_ctr_sss_sesion.php';
validarSesion(rutaSesion,vista);
mostrarInformacionUsuario();
//fin manejo de la session

Ext.onReady(function(){
	Ext.BLANK_IMAGE_URL = '../../base/librerias/js/ext/resources/images/default/s.gif';
	
	//creando store para el combo tipo destino
	var tipopropietario = [['Ninguno', 'N'],['Proveedor', 'P'], ['Beneficiario', 'B']]
	var storetipopropietario = new Ext.data.SimpleStore({
            fields: ['col', 'tipo'],
            data: tipopropietario
	});
	//fin creando store para el combo tipo destino
	
	//creando objeto combo tipo destino
	var combotipodestino = new Ext.form.ComboBox({
				store: storetipopropietario,
				fieldLabel: 'Proveedor/Beneficiario',
				labelSeparator :'',
            	editable: false,
            	displayField: 'col',
            	valueField: 'tipo',
            	name: 'tipo',
            	id: 'tipprop',
				typeAhead: true,
				triggerAction: 'all',
            	mode: 'local',
				binding:true,
				hiddenvalue:'',
				defaultvalue:'',
				allowBlank:false
	});
	//fin creando objeto combo tipo destino
	
	//creando listener para el combo de tipo destino
	combotipodestino.addListener('select',agregarCatalogoProvBene);
	//creando listener para el combo de tipo destino
	
	//creando funcion para que agregue al formulario el catalogo de propietario
	function agregarCatalogoProvBene(objcombo,registro){
		objcombo.disable();
		if (registro.get('tipo') == 'P') {
			//creando datastore y columnmodel para el catalogo de proveedores
			registro_provbene = Ext.data.Record.create([{
				name: 'cod_pro'
			}, {
				name: 'nompro'
			}, {
				name: 'rifpro'
			}]);
			
			dsprovbene = new Ext.data.Store({
				reader: new Ext.data.JsonReader({
					root: 'raiz',
					id: "id"
				}, registro_provbene)
			});
			
			colmodelcatprovbene = new Ext.grid.ColumnModel([{
				header: "Codigo",
				width: 20,
				sortable: true,
				dataIndex: 'cod_pro'
			}, {
				header: "Denominacion",
				width: 40,
				sortable: true,
				dataIndex: 'nompro'
			}]);
			//fin creando datastore y columnmodel para el catalogo de proveedores 
			
			//componente catalogo de proveedores
			comcatprovbene = new com.sigesp.vista.comCatalogo({
				titvencat: 'Catalogo de Proveedores',
				anchoformbus: 450,
				altoformbus: 130,
				anchogrid: 450,
				altogrid: 400,
				anchoven: 500,
				altoven: 400,
				datosgridcat: dsprovbene,
				colmodelocat: colmodelcatprovbene,
				rutacontrolador: '../../controlador/rpc/sigesp_ctr_rpc_catalogoprovbene.php',
				parametros: "ObjSon={'operacion': 'catalogoproveedor'",
				arrfiltro: [{
					etiqueta: 'Codigo',
					id: 'codigopro',
					valor: 'cod_pro'
				}, {
					etiqueta: 'Descripcion',
					id: 'descpro',
					valor: 'nompro'
				}, {
					etiqueta: 'Rif',
					id: 'prorif',
					valor: 'rifpro'
				}],
				tipbus: 'P',
				posbotbus: 'position:absolute;left:350px;top:60px;',
				camposllenar: [	{'idCampo': 'codprobene','idDato': 'cod_pro|nompro','tipo': 'concatenado'},
								{'idCampo': 'codprop','idDato': 'cod_pro','tipo': 'cadena'},
								{'idCampo': 'nomprop','idDato': 'nompro','tipo': 'cadena'}],
				onAceptar: false,
				fnOnAceptar: null
			});
			comcatprovbene.mostrarVentana();
		}
		else 
			if (registro.get('tipo') == 'B') {
				//creando datastore y columnmodel para el catalogo de beneficiarios
				registro_provbene = Ext.data.Record.create([{
					name: 'ced_bene'
				}, {
					name: 'nombene'
				}]);
				
				dsprovbene = new Ext.data.Store({
					reader: new Ext.data.JsonReader({
						root: 'raiz',
						id: "id"
					}, registro_provbene)
				});
				
				colmodelcatprovbene = new Ext.grid.ColumnModel([{
					header: "Codigo",
					width: 20,
					sortable: true,
					dataIndex: 'ced_bene'
				}, {
					header: "Denominacion",
					width: 40,
					sortable: true,
					dataIndex: 'nombene'
				}]);
				//fin creando datastore y columnmodel para el catalogo de beneficiarios 
				
				//componente catalogo de beneficiario
				comcatprovbene = new com.sigesp.vista.comCatalogo({
					titvencat: 'Catalogo de Beneficiarios',
					anchoformbus: 450,
					altoformbus: 130,
					anchogrid: 450,
					altogrid: 400,
					anchoven: 500,
					altoven: 400,
					datosgridcat: dsprovbene,
					colmodelocat: colmodelcatprovbene,
					rutacontrolador: '../../controlador/rpc/sigesp_ctr_rpc_catalogoprovbene.php',
					parametros: "ObjSon={'operacion': 'catalogobeneficiario'",
					arrfiltro: [{
						etiqueta: 'C&#233;dula',
						id: 'cedubene',
						valor: 'ced_bene'
					}, {
						etiqueta: 'Nombre',
						id: 'nobene',
						valor: 'nombene'
					}, {
						etiqueta: 'Apellido',
						id: 'apbene',
						valor: 'apebene'
					}],
					tipbus: 'P',
					posbotbus: 'position:absolute;left:350px;top:60px;',
					camposllenar: [	{'idCampo': 'codprobene','idDato': 'ced_bene|nombene','tipo': 'concatenado'},
									{'idCampo': 'codprop','idDato': 'ced_bene','tipo': 'cadena'},
									{'idCampo': 'nomprop','idDato': 'nombene','tipo': 'cadena'}],
					onAceptar: false,
					fnOnAceptar: null
				});
				comcatprovbene.mostrarVentana();
			//fin componente catalogo de beneficiario
			}
	}
	//fin creando funcion para que agregue al formulario el catalogo de prov/bene
	
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
	
	//componente campo catalogo para el campo unidad emisora
	comcampocatalogounienv = new com.sigesp.vista.comCampoCatalogo({
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
							posicion:'position:absolute;margin-left:5px;margin-top:0px',
							idfieldset:'catunidadenv',
							tittxt:'Unidad Emisora',
							idtxt:'codunienv',
							campovalue:'coduniadm',
							anchoetiquetatext:130,
							anchotext:100,
							anchocoltext:0.40,
							idlabel:'denunienv',
							labelvalue:'denuniadm',
							anchocoletiqueta:0.55,
							anchoetiqueta:200,
							idboton:'botcatunienv',
							tipbus:'L',
							binding:'C',
							hiddenvalue:'',
							defaultvalue:'',
							allowblank:false
						});
	//fin componente campo catalogo para el campo unidad emisora
	
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
							arrfiltro:[{etiqueta:'Codigo',id:'counrec',valor:'coduniadm'},
									   {etiqueta:'Descripcion',id:'deunrec',valor:'denuniadm'}],
							posicion:'position:absolute;margin-left:5px;margin-top:30px',
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
							anchoetiqueta:200,
							idboton:'botcatunirec',
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
							posicion:'position:absolute;margin-left:5px;margin-top:60px',
							idfieldset:'catusu',
							tittxt:'Asignar a Usuario',
							idtxt:'codusurec',
							campovalue:'codusu',
							anchoetiquetatext:130,
							anchotext:100,
							anchocoltext:0.40,
							idlabel:'nomusuini',
							labelvalue:'nomusu',
							anchocoletiqueta:0.55,
							anchoetiqueta:200,
							idboton:'botcatusu',
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
		formregistrotramite = new Ext.FormPanel({
						width: 800,
						height: 400,
						applyTo: 'formulario_registrotramite',
						title: 'Registro de Tramites',
						frame:true,
						style:'position:absolute;margin-left:'+Xpos+'px;margin-top:70px;',
						items:[{
								layout : "column",
					        	defaults : {border : false},
								style:'position:absolute;left:17px;top:5px',
								items : [{
										  layout : "form",
										  border : false,
										  labelWidth: 130,
										  columnWidth : 1,
										  items : [{
													 xtype: 'textfield',
													 fieldLabel: 'N&#250;mero Tramite',
													 labelSeparator :'',
													 id: 'numtramite',
													 autoCreate: {tag: 'input', type: 'text', size: '15', autocomplete: 'off', maxlength: '15'},
													 width: 120,
													 readOnly:true,
													 binding:true,
													 hiddenvalue:'',
													 defaultvalue:'',
													 allowBlank:false
													}]
						   				}]
								},{
								layout: "column",
								border: false,
								style: 'position:absolute;left:17px;top:35px',
								width:800,
								items: [{
										layout: "form",
										border: false,
										labelWidth: 130,
										columnWidth: 0.40,
										items: [combotipodestino]
										},{
										layout: "form",
										border: false,
										columnWidth: 0.60,
										items: [{
												  xtype: 'textfield',
												  hideLabel : true,
												  labelSeparator: '',
												  id: 'codprobene',
												  width: 450,
												  style:'border:none;background:#DFE8F6;',
												  readOnly: true
											    },{
												  xtype: 'hidden',
												  id: 'codprop',
												  binding:true,
												  defaultvalue:'NINGUNO'
												},{
												  xtype: 'hidden',
												  id: 'nomprop',
												  binding:true,
												  defaultvalue:'NINGUNO'
												}]							
										}]
								},{
								layout: "column",
								defaults: {border: false},
								width:400,
								style: 'position:absolute;left:17px;top:65px',
								items: [{
										 layout : "form",
										 border : false,
										 labelWidth: 130,
										 columnWidth : 0.7,
										 items : [{
												   xtype: 'textfield',
												   fieldLabel: 'Documento',
												   labelSeparator: '',
												   id: 'coddocini',
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
												  id:'btncatdocumento',
												  iconCls: 'menubuscar',
												  handler: function (){
															mostrarCatalogoDocumento()
														}
												}]
										}]
								},{
									xtype: 'hidden',
									id: 'tipdocini',
									binding:true,
									defaultvalue:''
								},{
								layout : "column",
					        	defaults : {border : false},
								style:'position:absolute;left:17px;top:95px',
								items : [{
												layout : "form",
					        					border : false,
												labelWidth: 130,
					        					columnWidth : 1,
					        					items : [{
															xtype: 'textarea',
															fieldLabel: 'Observaci&#243;n',
															labelSeparator :'',
															id: 'obstramite',
															width: 450,
															binding:true,
												   			hiddenvalue:'',
												   			defaultvalue:'',
												   			allowBlank:false
														}]
										}]
								},{
								xtype: 'fieldset',
								width:700,
								height:150,
								style:'position:absolute;left:17px;top:175px',
								title:'Asignaci&#243;n Inicial',
								border:true,
								items:[comcampocatalogounienv.fieldsetCatalogo,						
										comcampocatalogousuarios.fieldsetCatalogo,
										comcampocatalogounirec.fieldsetCatalogo]
								},{
									xtype: 'hidden',
									id: 'bancatalogo',
									binding:true,
									defaultvalue:0
								}]
		});
	}
	//fin funcion para la construcion del formulario de la solicitud
	
	//llamado a funciones.........
	getFormRegistroSolicitud();
});

function nuevoTramite(){	
	limpiarFormulario(formregistrotramite);
	var combotipodestino = formregistrotramite.findById("tipprop");
	var myJSONObject 	 = {"operacion":"buscarcodigo"};
	var campos           = new Array('numtramite','coddocini','btncatdocumento','codunienv','codunirec','codusurec','botcatunienv','botcatunirec','botcatusu');
	deshabilitarComponentes(formregistrotramite,campos,true);
	if(combotipodestino.disabled){
		combotipodestino.enable();
	}
	ObjSon=Ext.util.JSON.encode(myJSONObject);
	var parametros ='ObjSon='+ObjSon;
	Ext.Ajax.request({
		url: '../../controlador/sst/sigesp_ctr_sst_registrotramite.php',
		params: parametros,
		method: 'POST',
		success: function ( result, request ) { 
            var datos = result.responseText;
			var	resultado = datos.split("|");
			var codigo = resultado[1];
			if (codigo != "") {
				Ext.getCmp('numtramite').setValue(codigo);
			}
		},
		failure: function ( result, request){ 
		Ext.MessageBox.alert('Error', 'El Registro no pudo ser '+mensaje); 
		}
	});		
}
var banderaNuevo=true;
var nuevoPersonalizado = nuevoTramite;

function grabarTramite(){
	var cadjson          = "";
	var bancatalogo      = formregistrotramite.findById("bancatalogo");
	var combotipodestino = formregistrotramite.findById("tipprop");
	var campos           = new Array('numtramite','coddocini','btncatdocumento','codunienv','codunirec','codusurec','botcatunienv','botcatunirec','botcatusu');
	
	if(bancatalogo.getValue()==1){
		cadjson = getItems(formregistrotramite,"modificar","N",null,null);
	}
	else{
		cadjson = getItems(formregistrotramite,"incluir","N",null,null);
	}
	
	try {
		var objjson = Ext.util.JSON.decode(cadjson);
		if (typeof(objjson) == 'object') {
			var parametros = 'ObjSon='+cadjson;
			Ext.Ajax.request({
					url : '../../controlador/sst/sigesp_ctr_sst_registrotramite.php',
					params : parametros,
					method: 'POST',
					success: function ( resultad, request ){ 
	        					datos = resultad.responseText;
	       						resultado = datos.split("|");
								switch (resultado[1]) {
									case "0":
										Ext.MessageBox.alert('Error', 'Ha ocurrido un error en la operaci&#243;n, por favor intente de nuevo');
										limpiarFormulario(formregistrotramite);
										if(combotipodestino.disabled){
											combotipodestino.enable();
										}
										break;
									
									case "1":
										Ext.MessageBox.alert('Mensaje', 'El registro fue actualizado');
										limpiarFormulario(formregistrotramite);
										if(combotipodestino.disabled){
											combotipodestino.enable();
										}
										deshabilitarComponentes(formregistrotramite,campos,true);
										break;
									
									case "2":
										Ext.MessageBox.alert('Mensaje', 'El registro fue incluido');
										limpiarFormulario(formregistrotramite);
										if(combotipodestino.disabled){
											combotipodestino.enable();
										}
										break;
									
									case "3":
										Ext.MessageBox.alert('Mensaje', 'El registro fue incluido con el numero '+resultado[2]);
										limpiarFormulario(formregistrotramite);
										if(combotipodestino.disabled){
											combotipodestino.enable();
										}
										break;
								}
					},
					failure: function ( result, request){ 
								Ext.MessageBox.alert('Error','Ha ocurrido un error en la operaci&#243;n, por favor intente de nuevo');
								limpiarFormulario(formregistrotramite);
								if(combotipodestino.disabled){
									combotipodestino.enable();
								}
								deshabilitarComponentes(formregistrotramite,campos,true);
					} 
			});
		}
	}
	catch(e){
		//alert('error'+e);
	}	
}

var banderaGrabar = true;
var grabarPersonalizado = grabarTramite;

function eliminarTramite(){	
	Ext.Msg.show({
		title: 'Advertencia',
		msg: 'Realmente desea eliminar este registro?',
		buttons: Ext.Msg.YESNO,
		fn:  function (btn) {
			if(btn=="yes"){
			 	var cadjson = getItems(formregistrotramite,"eliminar","N",null,null);
				var parametros = 'ObjSon='+cadjson;
				var campos = new Array('numtramite','coddocini','btncatdocumento','codunienv','codunirec','codusurec','botcatunienv','botcatunirec','botcatusu');
				Ext.Ajax.request({
					url: '../../controlador/sst/sigesp_ctr_sst_registrotramite.php',
					params: parametros,
					method: 'POST',
					success: function ( result, request ) { 
            			var datos = result.responseText;
						var	resultado = datos.split("|");
						switch (resultado[1]) {
							case "0":
								Ext.MessageBox.alert('Error', 'Ha ocurrido un error en la operaci&#243;n, por favor intente de nuevo');
								limpiarFormulario(formregistrotramite);
								deshabilitarComponentes(formregistrotramite,campos,true);
								break;
							
							case "3":
								Ext.MessageBox.alert('Error', 'El registro no pudo ser eliminado');
								limpiarFormulario(formregistrotramite);
								deshabilitarComponentes(formregistrotramite,campos,true);
								break;
							
							case "4":
								Ext.MessageBox.alert('Mensaje', 'El registro fue eliminado');
								limpiarFormulario(formregistrotramite);
								deshabilitarComponentes(formregistrotramite,campos,true);
								break;
							
							case "5":
								Ext.MessageBox.alert('Mensaje', 'El registro no puede ser eliminado, ya que no es el ultimo registro');
								limpiarFormulario(formregistrotramite);
								deshabilitarComponentes(formregistrotramite,campos,true);
								break;
						}
					},
					failure: function ( result, request){ 
							Ext.MessageBox.alert('Error', 'El Registro no pudo ser eliminado'); 
					}
				});	
			}
		},
		animEl: 'elId',
		icon: Ext.MessageBox.QUESTION
	});
			
}
var banderaEliminar=true;
var eliminarPersonalizado = eliminarTramite;

function buscarTramite(){
	catalogoTramite('F','');
}

var banderaCatalogo = 'generica';
var buscarGenerica = buscarTramite;
