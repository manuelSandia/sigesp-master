/***********************************************************************************
* @Archivo JavaScript que incluye un componente que construye una grid con un catalogo 
* asociado para insertar la data en dicho grid  
* @fecha de creacion: 19/08/2009
* @autor: Ing. Gerardo Cordero
************************************************************************************
* @fecha modificacion:
* @descripcion:
* @autor:
***********************************************************************************/
Ext.namespace('com.sigesp.vista');
var copiadatastorecatalogo = '';

com.sigesp.vista.comListaCatalogo = function(options){
	if(options.guardarEliminados){
		this.dataStoreEliminados = 	 new Ext.data.Store({
			reader: new Ext.data.JsonReader({root: 'raiz',id: "id"},options.rgeliminar)
		});
	}
	 
	
	this.dataStoreCatalogo   = options.datosgridcat;
	this.fnOnAceptar 	     = options.fnOnAceptar;
	
	//Creando el Json para la configuracion de los items del formulario de busqueda
	var cadenafiltro="[";
	for (var i = 0; i < options.arrfiltro.length; i++) {
       	if(i==options.arrfiltro.length-1){
			cadenafiltro =  cadenafiltro + "{fieldLabel:'"+options.arrfiltro[i].etiqueta+"',id:'"+options.arrfiltro[i].id+"',"+
							"changeCheck: function(){"+
							"var valor = this.getValue();"+
							"copiadatastorecatalogo.filter('"+options.arrfiltro[i].valor+"',valor,true);"+
							"if(String(valor) !== String(this.startValue)){"+
								"this.fireEvent('change', this, valor, this.startValue);"+
							"}"+ 
							"},"+								 
							"initEvents : function(){"+
								"AgregarKeyPress(this);"+
							"}"+              
    						"}";
		}else{
			cadenafiltro =  cadenafiltro + "{fieldLabel:'"+options.arrfiltro[i].etiqueta+"',id:'"+options.arrfiltro[i].id+"',"+
							"changeCheck: function(){"+
							"var valor = this.getValue();"+
							"copiadatastorecatalogo.filter('"+options.arrfiltro[i].valor+"',valor,true);"+
							"if(String(valor) !== String(this.startValue)){"+
								"this.fireEvent('change', this, valor, this.startValue);"+
							"}"+ 
							"},"+							 
							"initEvents : function(){"+
								"AgregarKeyPress(this);"+
							"}"+               
     						"},";
		}
	}
	
	cadenafiltro = cadenafiltro + "]";
	
								 
	var objetofiltro = Ext.util.JSON.decode(cadenafiltro);
	//Fin creando el Json para la configuracion de los items del formulario de busqueda	
	
	//Creando formulario de busqueda del catalogo
	this.formBusquedaCat = new Ext.FormPanel({
        	labelWidth: 80, 
			frame:true,
        	title: 'Busqueda',
        	bodyStyle:'padding:5px 5px 0',
        	width: options.anchoformbus,
			height: options.altoformbus,
        	defaults: {width: 230},
        	defaultType: 'textfield',
			items: objetofiltro
		});
	//Fin creando formulario de busqueda del catalogo
		
	//Creando la instacia de la grid del catalogo
	this.gridcatalogo = new Ext.grid.GridPanel({
	 		width:options.anchogrid,
	 		height:options.altogrid,
	 		tbar: this.formBusquedaCat,
	 		autoScroll:true,
     		border:true,
     		ds: this.dataStoreCatalogo,
       		cm: options.colmodelocat,
       		sm: options.selmodelocat,
       		stripeRows: true,
      		viewConfig: {forceFit:true}
		});
	//Fin Creando la instacia de la grid del catalogo
	
	//Eventos de la ventana catalogo
	this.cerrarVentana = function(){
		this.dataStoreCatalogo.removeAll();
		copiadatastorecatalogo = '';
		this.vencatalogo.hide();
	}
	
	this.cargarDatosCat = function (){
		var datos = arguments[0].responseText;
		var objetodata = eval('(' + datos + ')');
		if(objetodata!=''){
			if(objetodata.raiz == null){
				Ext.MessageBox.alert('Informaci&#243;n','No se encontraron datos')
			}
			else{
				copiadatastorecatalogo = options.datosgridcat;
				this.dataStoreCatalogo.loadData(objetodata);
				copiadatastorecatalogo.loadData(objetodata);
				Ext.MessageBox.hide();
			}
			
		}
	}
	
	this.buscarDataCatalogo = function(){
		var nuevosparamentros = options.parametros;
		for (var i = 0; i < options.arrfiltro.length; i++) {
       		if(i==options.arrfiltro.length-1){
				nuevosparamentros = nuevosparamentros +",'"+options.arrfiltro[i].id+"':'"+this.formBusquedaCat.getComponent(options.arrfiltro[i].id).getValue()+"'}";
			}else{
				nuevosparamentros = nuevosparamentros +",'"+options.arrfiltro[i].id+"':'"+this.formBusquedaCat.getComponent(options.arrfiltro[i].id).getValue()+"'";
			}
		}
		Ext.MessageBox.show({
						           msg: 'Buscando informaci&#243;n',
						           title: 'Progreso',
						           progressText: 'Buscando informaci&#243;n',
						           width:300,
						           wait:true,
						           waitConfig:{interval:150},	
						           animEl: 'mb7'
					      	});
		Ext.Ajax.request({
			url : options.rutacontrolador,
			params : nuevosparamentros,
			method: 'POST',
			success: this.cargarDatosCat.createDelegate(this, arguments, 2)
		});
	}
		
	this.mostrarVentana = function(){
		
		switch(options.tipbus) {
			case 'L':
	    		Ext.Ajax.request({
					url : options.rutacontrolador,
					params : options.parametros,
					method: 'POST',
					success: this.cargarDatosCat.createDelegate(this, arguments, 2)
				});
				break;
			
			case 'LF':
				var nuevosparamentros = options.parametros;
				for (var i = 0; i < options.arrtxtfiltro.length; i++) {
       				if(i==options.arrtxtfiltro.length-1){
						nuevosparamentros = nuevosparamentros +",'"+options.arrtxtfiltro[i]+"':'"+Ext.getCmp(options.arrtxtfiltro[i]).getValue()+"'}";
					}else{
						nuevosparamentros = nuevosparamentros +",'"+options.arrtxtfiltro[i]+"':'"+Ext.getCmp(options.arrtxtfiltro[i]).getValue()+"'";
					}
				}
				Ext.Ajax.request({
					url : options.rutacontrolador,
					params : nuevosparamentros,
					method: 'POST',
					success: this.cargarDatosCat.createDelegate(this, arguments, 2)
				});
				break;
			
			case 'P':
				if (this.formBusquedaCat.getComponent('botbusqueda') == null) {
					var botbusqueda = new Ext.Button({
						id: 'botbusqueda',
						iconCls: 'menubuscar',
						style: options.posbotbus,
						handler: this.buscarDataCatalogo.createDelegate(this)
					});
					this.formBusquedaCat.add(botbusqueda);
					this.formBusquedaCat.doLayout();
				}
				break;
		}
		
		this.vencatalogo.show();
	}
	
	this.setDataGrid = function(){
		var arregloreg = this.gridcatalogo.getSelectionModel().getSelections();
        for (i=0; i<arregloreg.length; i++){
			var validareg = arregloreg[i];
			if(validarExistenciaRegistroStore(validareg,this.dataGrid.getStore(),options.arrcampovalidaori,options.arrcampovalidades)){
				arregloreg[i].set('registrocat','1');
				this.dataGrid.getStore().insert(0,arregloreg[i]);
			}else{
				Ext.MessageBox.alert('Advertencia','El item seleccionado ya fue cargado');
			}
		}
		
		if(options.onAceptar){
			this.fnOnAceptar();
		}
		this.dataStoreCatalogo.removeAll();
		copiadatastorecatalogo = '';
		this.vencatalogo.hide();
	}
	//Fin de los eventos de la ventana catalogo
	
	//agregadon listener a la grid del catalogo para que cuando de dobleclick sobre el registro este se pase al formulario
	this.gridcatalogo.on({
		'celldblclick': {
			fn: this.setDataGrid.createDelegate(this)
		}
	});
	//fin agregadon listener a la grid del catalogo para que cuando de dobleclick sobre el registro este se pase al formulario
	
	//Creando la instacia de la window para la ventana del catalogo
	this.vencatalogo = new Ext.Window({
    		title: options.titvencat,
			autoScroll:true,
        	width:options.anchoven,
        	height:options.altoven,
        	modal: true,
        	closable:false,
        	plain: false,
			items:[this.gridcatalogo],
			buttons: [{
						text:'Aceptar',  
			        	handler: this.setDataGrid.createDelegate(this)
			       	},{
			      		text: 'Salir',
			        	handler:this.cerrarVentana.createDelegate(this)
                  	}]
      	});
	//Fin creando la instacia de la window para la ventana del catalogo
	
	//Creando funcion para la eliminacion de registros de la grid
	this.eliminarRegistro = function (){
		var arregloregistros = this.dataGrid.getSelectionModel().getSelections();
		if (arregloregistros.length >0){
			for (var i = arregloregistros.length - 1; i >= 0; i--){
				this.dataGrid.getStore().remove(arregloregistros[i]);
				if(arregloregistros[i].get('registrocat')!='1' && options.guardarEliminados){
					this.dataStoreEliminados.add(arregloregistros[i]);
				}
			};
		}
	}
	
	//Fin creando funcion para la eliminacion de registros de la grid
	
	//Creando grid de datos que se llenara con el catalgo
	this.dataGrid =new Ext.grid.GridPanel({
        id: options.idgrid,
		width:options.ancho,
        height:options.alto,
       	style:options.posicion,
        title:options.titgrid,
	    ds: options.datosgrid,
       	cm: options.colmodelo,
       	sm: options.selmodelo,
        frame:true,
       	viewConfig: {forceFit:true},
        columnLines: true,
        tbar:[{
            text:'Agregar',
            tooltip:'Agregar un registro',
            iconCls:'agregar',
            id:'agregar',
			handler: this.mostrarVentana.createDelegate(this)
        	}, '-', {
            text:'Eliminar',
            tooltip:'Eliminar un registro',
            iconCls:'remover',
            id:'remover',
			handler: this.eliminarRegistro.createDelegate(this)
			}]
	    
	});
	//Fin creando grid de datos que se llenara con el catalgo
}