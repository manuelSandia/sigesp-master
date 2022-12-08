/***********************************************************************************
* @Archivo JavaScript que incluye un componentes que construye un catalgo   
* @fecha de creacion: 29/12/2009
* @autor: Ing. Gerardo Cordero
************************************************************************************
* @fecha modificacion:
* @descripcion:
* @autor:
***********************************************************************************/
Ext.namespace('com.sigesp.vista');
var copiadatastorecatalogo = '';

com.sigesp.vista.comCatalogo = function(options){
	
	this.dataStoreCatalogo = options.datosgridcat;
	this.fnOnAceptar = options.fnOnAceptar;
	
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
       		stripeRows: true,
      		viewConfig: {forceFit:true}
		});
	//Fin Creando la instacia de la grid del catalogo
	
	//Eventos de la ventana catalogo
	this.cerrarVentana = function(){
		this.dataStoreCatalogo.removeAll();
		copiadatastorecatalogo = '';
		this.gridcatalogo.destroy();
		this.vencatalogo.destroy();
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
	
	this.setDataForm = function(componente,registro){
		if (componente.items != null) {
			arritem = componente.items;
			arritem.each(function(subcomponente){
				var valor = null;
				switch (subcomponente.getXType()) {
					case 'radiogroup':
						if (typeof(registro.get(subcomponente.getId())) != 'undefined') {
							valor = limpiarCadenaRegistro(registro.get(subcomponente.getId()));
							for (var j = 0; j < subcomponente.items.length; j++) {
								if (valor == subcomponente.items.items[j].inputValue) {
									subcomponente.items.items[j].setValue(true);
									break;
								}
							}
						}
						break;
							
					case 'checkbox':
						if (typeof(registro.get(subcomponente.getId())) != 'undefined') {
							valor = limpiarCadenaRegistro(registro.get(subcomponente.getId()));
							if (valor == subcomponente.inputValue) {
								subcomponente.setValue(true);
							}
						}
						break;
					
					case 'checkboxgroup':
						if (typeof(registro.get(subcomponente.getId())) != 'undefined') {
							valor = limpiarCadenaRegistro(registro.get(subcomponente.getId()));
							for (var j = 0; j < subcomponente.items.length; j++) {
								if (valor == subcomponente.items.items[j].inputValue) {
									subcomponente.items.items[j].setValue(true);
									break;
								}
							}
						}
						break;
							
					case 'hidden':
						if (typeof(registro.get(subcomponente.getId())) != 'undefined') {
							subcomponente.setValue(registro.get(subcomponente.getId()));
							
						}
						break;
							
					case 'textfield':
						if (typeof(registro.get(subcomponente.getId())) != 'undefined') {
							valor = limpiarCadenaRegistro(registro.get(subcomponente.getId()));
							
							if (esNumerico(valor, '.')) {
								subcomponente.setValue(formatoNumericoMostrar(valor, 2, '.', ',', '', '', '-', ''));
							}
							else {
								
								subcomponente.setValue(valor);
							}
						}
						break;
							
					case 'textarea':
						if (typeof(registro.get(subcomponente.getId())) != 'undefined') {
							valor = limpiarCadenaRegistro(registro.get(subcomponente.getId()));
							subcomponente.setValue(valor);
						}
				 		break;
							
					case 'combo':
						if (typeof(registro.get(subcomponente.getId())) != 'undefined') {
							subcomponente.setValue(registro.get(subcomponente.getId()));
						}
						break;
							
					case 'datefield':
						if (typeof(registro.get(subcomponente.getId())) != 'undefined') {
							subcomponente.setValue(registro.get(subcomponente.getId()));
						}
						break;
							
					case 'numberfield':
						if (typeof(registro.get(subcomponente.getId())) != 'undefined') {
							subcomponente.setValue(registro.get(subcomponente.getId()));
						}
				 		break;
							
					default:
						setDataFrom(subcomponente,registro);
						break;
				}
			});
		}
	}
	
	this.setData = function(){
		var registrocat = this.gridcatalogo.getSelectionModel().getSelected();
		
		if(options.setdatastyle=='F'){
			var fncSetDataFrom = this.setDataForm.createDelegate(this);
			fncSetDataFrom(options.formulario,registrocat);
			Actualizar=true;
		}
		else{
			for (var i = options.camposllenar.length - 1; i >= 0; i--) {
				var valorcampo = null;
				switch (options.camposllenar[i].tipo) {
					case 'numerico':
						valorcampo = formatoNumericoMostrar(registrocat.get(options.camposllenar[i].idDato), 2, '.', ',', '', '', '-', '');
						break;
						
					case 'fecha':
						if (registrocat.get(options.camposllenar[i].id) != "") {
							var fechanoguion = registrocat.get(options.camposllenar[i].idDato).replace('-', '/', 'g');
							var objfecha = new Date(fechanoguion);
							valorcampo = objfecha.format(Date.patterns.fechacorta);
						}
						break;
						
					case 'fechahora':
						if (registrocat.get(options.camposllenar[i].id) != "") {
							var fechanoguion = registrocat.get(options.camposllenar[i].idDato).replace('-', '/', 'g');
							var objfecha = new Date(fechanoguion);
							valorcampo = objfecha.format(Date.patterns.fechahoracorta);
						}
						break;
						
						
					case 'cadena':
						valorcampo = registrocat.get(options.camposllenar[i].idDato);
						break;
					
					case 'concatenado':
						var campos = options.camposllenar[i].idDato.split("|");
						var valorcampo =registrocat.get(campos[0])+'-'+registrocat.get(campos[1]);
						break;
				}
				
				Ext.getCmp(options.camposllenar[i].idCampo).setValue(valorcampo);
			}
		}
		
		if(options.datosocultos==1){
			for (var i = options.camposocultos.length - 1; i >= 0; i--){
				Ext.getCmp(options.camposocultos[i]).setValue(registrocat.get(options.camposocultos[i]));
			}
		}
		
		if(options.onAceptar){
			this.fnOnAceptar();
		}
		this.dataStoreCatalogo.removeAll();
		copiadatastorecatalogo = '';
		this.cerrarVentana();
	}
	//Fin de los eventos de la ventana catalogo
	
	//agregadon listener a la grid del catalogo para que cuando de dobleclick sobre el registro este se pase al formulario
	this.gridcatalogo.on({
		'celldblclick': {
			fn: this.setData.createDelegate(this)
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
			        	handler: this.setData.createDelegate(this)
			       	},{
			      		text: 'Salir',
			        	handler:this.cerrarVentana.createDelegate(this)
                  	}]
      	});
	//Fin creando la instacia de la window para la ventana del catalogo
	
}//Fin componente campo catalogo

