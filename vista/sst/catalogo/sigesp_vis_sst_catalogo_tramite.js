/***********************************************************************************
* @Archivo JavaScript que incluye tanto los componentes como los eventos asociados 
* al catalogo de tramites
* @fecha de creacion: 23/01/2010
* @autor: Ing. Gerardo Cordero
************************************************************************************
* @fecha modificacion:
* @descripcion:
* @autor:
***********************************************************************************/
var dscattramite        = null;  //datastore del catalogo 
var formbusquedatramite = null;

function buscarDataTramite(btn){
	if(btn=="yes"){
		var cadenajson = getItems(formbusquedatramite,'catalogo','N',null,null);
		var parametros = 'ObjSon='+cadenajson; 
		Ext.Ajax.request({
			url : '../../controlador/sst/sigesp_ctr_sst_registrotramite.php',
			params : parametros,
			method: 'POST',
			success: function ( resultado, request){ 
				var datos = resultado.responseText;
				var objetodata = eval('(' + datos + ')');
				if(objetodata != ''){
					if(objetodata.raiz == null){
						Ext.MessageBox.alert('Informaci&#243;n','No se encontraron datos')
					}
					else{
						dscattramite.loadData(objetodata);
					}
				}
			}	
		});
	}
}

function buscarDataTramiteCon(btn){
	if(btn=="yes"){
		var cadenajson = getItems(formbusquedatramite,'catalogotramitecon','N',null,null);
		var parametros = 'ObjSon='+cadenajson; 
		Ext.Ajax.request({
			url : '../../controlador/sst/sigesp_ctr_sst_catalogosst.php',
			params : parametros,
			method: 'POST',
			success: function ( resultado, request){ 
				var datos = resultado.responseText;
				var objetodata = eval('(' + datos + ')');
				if(objetodata != ''){
					if(objetodata.raiz == null){
						Ext.MessageBox.alert('Informaci&#243;n','No se encontraron datos')
					}
					else{
						dscattramite.loadData(objetodata);
					}
				}
			}	
		});
	}
}


function getGridCatTramite(){
	
	//creando datastore del catalogo
	var registro_tramite = Ext.data.Record.create([
							{name: 'numtramite'},
							{name: 'tipprop'},
							{name: 'codprop'},
							{name: 'nomprop'},
							{name: 'codprobene'},
							{name: 'obstramite'},    
							{name: 'fecini'},
							{name: 'codusurec'},
							{name: 'codunienv'},
							{name: 'codunirec'},
							{name: 'codusuact'},
							{name: 'nomusuini'},
							{name: 'nomusuact'},
							{name: 'coddocini'},
							{name: 'denunienv'},
							{name: 'denunirec'}
						]);
	
	dscattramite =  new Ext.data.Store({
			reader: new Ext.data.JsonReader({root: 'raiz',id: "id"},registro_tramite)
	  	});
	//fin creando datastore del catalogo
	
	//creando la grid del catalgo
	var gridcattramite = new Ext.grid.GridPanel({
	 	width:850,
	 	height:200,
	 	autoScroll:true,
     	border:true,
     	ds: dscattramite,
     	cm: new Ext.grid.ColumnModel([
			{header: "Numero", width: 30, sortable: true,   dataIndex: 'numtramite'},
          	{header: "Proveedor/Beneficiario", width: 40, sortable: true, dataIndex: 'nomprop'},
			{header: "Fecha de Inicio", width: 40, sortable: true, dataIndex: 'fecini',renderer:formatoFechaGrid},
			{header: "Usuario Inicial", width: 40, sortable: true, dataIndex: 'nomusuini'},
			{header: "Usuario Actual", width: 40, sortable: true, dataIndex: 'nomusuact'}
		]),
		stripeRows: true,
      	viewConfig: {
      	forceFit:true
    }});
	//fin creando la grid del catalgo
	
	return gridcattramite;
} 

function getGridCatTramiteCon(){
	
	//creando datastore del catalogo
	var registro_tramite = Ext.data.Record.create([
							{name: 'numtramite',
							 type: 'string'},
							{name: 'codprobene'},
							{name: 'codusuact'},
							{name: 'codusuini'},
							{name: 'fecini'},
							{name: 'coddocini'},
							{name: 'tipdocini'},
							{name: 'fecfin'},
							{name: 'coddocfin'},
							{name: 'tipdocfin'}
				]);
	
	dscattramite =  new Ext.data.Store({
			reader: new Ext.data.JsonReader({root: 'raiz',id: "id"},registro_tramite)
	  	});
	//fin creando datastore del catalogo
	
	//creando la grid del catalgo
	var gridcattramitecon = new Ext.grid.GridPanel({
	 	width:800,
	 	height:150,
	 	autoScroll:true,
     	border:true,
     	ds: dscattramite,
     	cm: new Ext.grid.ColumnModel([
			{header: "Numero", width: 20, sortable: true,   dataIndex: 'numtramite'},
          	{header: "Proveedor/Beneficiario", width: 50, sortable: true, dataIndex: 'codprobene'},
			{header: "Fecha de Inicio", width: 20, sortable: true, dataIndex: 'fecini',renderer:formatoFechaGrid}
		]),
		stripeRows: true,
      	viewConfig: {
      	forceFit:true
    }});
	//fin creando la grid del catalgo
	
	return gridcattramitecon;
}

function getFromBusqueda(catalogo){
	var fecha  = new Date();
	
	//creando store para el combo tipo destino
	var tipopropietario = [['Proveedor', 'P'], ['Beneficiario', 'B']]
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
            	id: 'tipo_destino',
				typeAhead: true,
				triggerAction: 'all',
            	mode: 'local',
				binding:true,
				hiddenvalue:'',
				defaultvalue:'',
				allowBlank:true
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
				camposllenar: [	{'idCampo': 'catcodprobene','idDato': 'cod_pro|nompro','tipo': 'concatenado'},
								{'idCampo': 'codprovben','idDato': 'cod_pro','tipo': 'cadena'}],
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
									{'idCampo': 'codprovben','idDato': 'ced_bene','tipo': 'cadena'}],
					onAceptar: false,
					fnOnAceptar: null
				});
				comcatprovbene.mostrarVentana();
			//fin componente catalogo de beneficiario
			}
	}
	//fin creando funcion para que agregue al formulario el catalogo de prov/bene
	
	//creando formulario de busqueda
	formbusquedatramite = new Ext.FormPanel({
        frame:true,
        title: 'Busqueda',
		width: 800,
		height:180,
		items: [{
			layout: "column",
			border: false,
			style: 'position:absolute;left:15px;top:5px',
			items: [{
				layout: "form",
				border: false,
				labelWidth: 130,
				columnWidth: 1,
				items: [{
					xtype: 'textfield',
					fieldLabel: 'Numero',
					labelSeparator: '',
					id: 'catnumtramite',
					changeCheck: function(){
						var textvalor = this.getValue();
						dscattramite.filter('numtramite', textvalor);
						if (String(textvalor) !== String(this.startValue)) {
							this.fireEvent('change', this, textvalor, this.startValue);
						}
					},
					initEvents: function(){
						AgregarKeyPress(this);
					},
					binding:true,
					hiddenvalue:'',
					defaultvalue:'',
					allowBlank:true
				}]
			}]
		},{
			layout: "column",
			border: false,
			style: 'position:absolute;left:15px;top:35px',
			width:750,
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
						id: 'catcodprobene',
						width: 450,
						style:'border:none;background:#DFE8F6;',
						readOnly: true
						},{
						xtype: 'hidden',
						id: 'codprovben',
						binding:true,
						defaultvalue:''
						}]							
				}]
		},{
			layout: "column",
			defaults: {border: false},
			style: 'position:absolute;left:15px;top:65px',
			width:400,
			items: [{
				layout: "form",
				border: false,
				labelWidth: 130,
				columnWidth: 0.7,
				items: [{
					xtype: 'textfield',
					fieldLabel: 'Documento',
					labelSeparator: '',
					id: 'catnumdoc',
					autoCreate: {
						tag: 'input',
						type: 'text',
						size: '100',
						autocomplete: 'off',
						maxlength: '100'
					},
					width: 130,
					binding:true,
					hiddenvalue:'',
					defaultvalue:'',
					allowBlank:true
				}]
			}, {
				layout: "form",
				border: false,
				labelWidth: 0,
				columnWidth: 0.3,
				items: [{
					xtype: 'button',
					iconCls: 'menubuscar',
					handler: function(){
						mostrarCatalogoDocumento2('catnumdoc','');
					}
				}]
			}]
		},{
			layout: "column",
			style: 'position:absolute;left:15px;top:95px',
			border: false,
			width:500,
			items: [{
				layout: "form",
				border: false,
				labelWidth:130,
				columnWidth: 0.5,
				items: [{
					xtype: "datefield",
					fieldLabel: "Fecha de Inicio",
					labelSeparator: '',
					width: 100,
					id: "fecdescat",
					endDateField: 'fechascat',
					readOnly: true,
					vtype: 'daterange',
					binding: true,
					hiddenvalue: '',
					defaultvalue: fecha.getFirstDateOfMonth().format(Date.patterns.bdfecha),
					allowBlank: true
				}]
			},{
				layout: "form",
				border: false,
				labelWidth: 50,
				columnWidth: 0.5,
				items: [{
					xtype: "datefield",
					fieldLabel: "Hasta",
					labelSeparator: '',
					width: 100,
					id: "fechascat",
					startDateField: 'fecdescat',
					value:fecha.format(Date.patterns.fechacorta),
					readOnly: true,
					vtype: 'daterange',
					binding: true,
					hiddenvalue: '',
					defaultvalue: '',
					allowBlank: false
				}]
			}]
		},{
			layout: "column",
			border: false,
			style: 'position:absolute;left:550px;top:110px',
			items: [{
				layout: "form",
				border: false,
				labelWidth: 130,
				columnWidth: 1,
				items: [{
					xtype: 'button',
					id: 'botcattramite',
					iconCls: 'menubuscar',
					handler: function(){
							if(catalogo==1){	
								if (formbusquedatramite.findById('fecdescat').getValue() == "") {
									Ext.Msg.show({
										title: 'Advertencia',
										msg: 'Usted no indico la fecha desde para la busqueda. El sistema tomara por defecto el primer dia del mes en curso para la busqueda desea proseguir?',
										buttons: Ext.Msg.YESNO,
										fn: buscarDataTramite,
										animEl: 'elId',
										icon: Ext.MessageBox.QUESTION
									});
								}
								else{
									buscarDataTramite("yes");
								}
							}
							else{
								if (formbusquedatramite.findById('fecdescat').getValue() == "") {
									Ext.Msg.show({
										title: 'Advertencia',
										msg: 'Usted no indico la fecha desde para la busqueda. El sistema tomara por defecto el primer dia del mes en curso para la busqueda desea proseguir?',
										buttons: Ext.Msg.YESNO,
										fn: buscarDataTramiteCon,
										animEl: 'elId',
										icon: Ext.MessageBox.QUESTION
									});
								}
								else{
									buscarDataTramiteCon("yes");
								}
							}
					}
				}]
			}]
		}]
	});
	//fin creando formulario de busqueda
	
	//return formbusquedatramite;
}

function catalogoTramite(tipocarga,destinocarga){
	
	//creando formulario de busqueda
	getFromBusqueda(1);
	//fin creando formulario de busqueda
	
	//creando grid
	var gridcattramite = getGridCatTramite();
	//fin creando grid
	
	//creando ventana del catalogo				   
    var vencatsolicitud = new Ext.Window({
    	title: 'Cat&#225;logo de Tramites',
		autoScroll:true,
        width:900,
        height:500,
        modal: true,
        closable:false,
        plain: false,
        items:[formbusquedatramite,gridcattramite],
        buttons: [{
					text:'Aceptar',  
			        handler: function(){
							var registro = gridcattramite.getSelectionModel().getSelected();
							if(tipocarga=='C'){
								Ext.getCmp(destinocarga).setValue(registro.get('numsol'));
							}
							else if(tipocarga=='F'){
								setDataFrom(formregistrotramite,registro);
								var campos = new Array('numtramite','coddocini','btncatdocumento','codunienv','codunirec','codusurec','botcatunienv','botcatunirec','botcatusu');
								deshabilitarComponentes(formregistrotramite,campos,false);
								var bancatalogo = formregistrotramite.findById("bancatalogo");
								if(bancatalogo!=null){
									bancatalogo.setValue(1);
								}
							}
							gridcattramite.destroy();
			      			vencatsolicitud.destroy();
						}
			       },
			       {
			      	text: 'Salir',
			        handler: function()
			      		{
			      			gridcattramite.destroy();
			      			vencatsolicitud.destroy();
			       		}
                  }]
	});
    vencatsolicitud.show();
}

function catalogoTramiteCon(formconsulta){
	
	//creando formulario de busqueda
	getFromBusqueda(0);
	//fin creando formulario de busqueda
	
	//creando grid
	var gridcattramitecon = getGridCatTramiteCon();
	//fin creando grid
	
	//creando ventana del catalogo				   
    var vencatsolicitud = new Ext.Window({
    	title: 'Cat&#225;logo de Tramites',
		autoScroll:true,
        width:900,
        height:500,
        modal: true,
        closable:false,
        plain: false,
        items:[formbusquedatramite,gridcattramitecon],
        buttons: [{
					text:'Aceptar',  
			        handler: function(){
							var registro = gridcattramitecon.getSelectionModel().getSelected();
							setDataFrom(formconsulta,registro);
							buscarTramite();
							gridcattramitecon.destroy();
			      			vencatsolicitud.destroy();
						}
			       },
			       {
			      	text: 'Salir',
			        handler: function()
			      		{
			      			gridcattramitecon.destroy();
			      			vencatsolicitud.destroy();
			       		}
                  }]
	});
    vencatsolicitud.show();
}