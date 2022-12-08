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
var formcerratramite = null;

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
						{name: 'numasi'},
						{name: 'coddocenv'},
						{name: 'tipdocenv'},
						{name: 'fecenv'},
						{name: 'tipprop'},
						{name: 'codprop'}      
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
	var arrcamposocultos=['tipprop','codprop'];
	//fin creando arreglo de campos ocultos para setear con el catalogo
	
	//componente campo catalogo para el tramite asignado
	var comcampocatalogotramite = new com.sigesp.vista.comCampoCatalogo({
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
	function getFormCierreTramite(){
		Ext.QuickTips.init();
		var Xpos = ((screen.width/2)-(800/2));
		formcerratramite = new Ext.FormPanel({
						width: 740,
						height: 280,
						applyTo: 'formulario_cerrartramite',
						title: 'Cerrar Tramite',
						frame:true,
						style:'position:absolute;margin-left:'+Xpos+'px;margin-top:80px;',
						items:[	comcampocatalogotramite.fieldsetCatalogo,
								{
								layout: "column",
								defaults: {border: false},
								style:'position:absolute;left:15px;top:45px',
								width:405,
								items: [{
										layout : "form",
					        			border : false,
										labelWidth: 130,
					        			columnWidth : 0.7,
					        			items : [{
													xtype: 'textfield',
													fieldLabel: 'Documento',
													labelSeparator: '',
													id: 'coddocfin',
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
													iconCls: 'menubuscar',
													handler: function (){
																mostrarCatalogoDocumento2('coddocfin','tipdocfin');
													}
												}]
											}]
								},{
								layout : "column",
					        	defaults : {border : false},
								style:'position:absolute;left:15px;top:75px',
								items :[{
										layout : "form",
					        			border : false,
										labelWidth: 130,
					        			columnWidth : 1,
					        			items : [{
													xtype: 'textarea',
													fieldLabel: 'Observacion',
													labelSeparator :'',
													id: 'obsfintra',
													width: 450,
													binding:true,
												   	hiddenvalue:'',
												   	defaultvalue:'',
												   	allowBlank:false
												}]
										}]
								},{
									xtype: 'hidden',
									id: 'tipdocfin',
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
	getFormCierreTramite();
});

function grabarCierreTramite(){
	var cadjson = getItems(formcerratramite,"incluir","N",null,null);
	try {
		var objjson = Ext.util.JSON.decode(cadjson);
		if (typeof(objjson) == 'object') {
			var parametros = 'ObjSon='+cadjson;
			Ext.Ajax.request({
				url : '../../controlador/sst/sigesp_ctr_sst_cerrartramite.php',
				params : parametros,
				method: 'POST',
				success: function ( resultad, request ){ 
	        				datos = resultad.responseText;
	       					resultado = datos.split("|");
							if(resultado[1]=="1"){
								Ext.MessageBox.alert('Mensaje','El tramite fue cerrado');
							}
							else{
								Ext.MessageBox.alert('Error','Ha ocurrido un error en la operaci&#243;n, por favor intente de nuevo');
							}
							limpiarFormulario(formcerratramite);
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
var grabarPersonalizado = grabarCierreTramite;

