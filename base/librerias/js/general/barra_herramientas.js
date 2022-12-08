 /**
 * @author ojeniffer
 */
var tbguardar = false;
var sistemaParametro = "";
var vistaParametro = "";
var salirAlterno =""

Ext.onReady(function(){
	Ext.QuickTips.init();
	if(sistemaParametro!=""){
		var modulo = sistemaParametro.toLowerCase();
	}
	else {
		var modulo = sistema.toLowerCase();
	}
	
	var Xpos = ((screen.width/2)-(930/2));
	var tb_barraherramienta = new Ext.Toolbar({
		width:925,
		style:'position:absolute;margin-left:'+Xpos+'px;margin-top:25px'
	});
			
	var nuevo = new Ext.Action ({
		text: 'Nuevo',
		iconCls: 'menunuevo',
		tooltip: 'Crear un nuevo registro',
		id: 'nuevo',
		handler:LlamarNuevo
		});
			
	var guardar = new Ext.Action ({
		text: 'Guardar',
		iconCls: 'menuguardar',
		tooltip: 'Guardar o actualizar un registro',
		id:'guardar',
		handler:LlamarActualizar
		});
			
	var eliminar = new Ext.Action ({
		text: 'Eliminar',
		iconCls: 'menueliminar',
		tooltip: 'Eliminar un registro',
		id:'eliminar',
		handler:LlamarEliminar
		});

	var buscar = new Ext.Action ({
		text: 'Buscar',
		iconCls: 'menubuscar',
		tooltip: 'Buscar un registro',
		id:'buscar',
	    handler: function(){
	    		switch (banderaCatalogo) {
	    			case 'estandar':
	    				mostrar_catalogo();
	    				break;
	    			case 'generica':
	    				buscarGenerica();
	    				break;
	    			case 'personalizado':
	    				registroActual = grid4.getSelectionModel().getSelected();
	    				catalogoPersonalizado(registroActual)
	    				break;
	    		}
			}
		});
			
	var imprimir = new Ext.Action ({
		text: 'Imprimir',
		iconCls: 'menuimprimir',
		tooltip: 'Imprimir un registro',
		id:'imprimir',
		handler:LlamarImprimir
		});
			
	var ayuda = new Ext.Action({
		text: 'Ayuda',
		iconCls: 'menuayuda',
		tooltip: 'Ayuda',
		id:'ayuda'
		});
			
	var salir = new Ext.Action ({
		text: 'Salir',
		iconCls: 'menusalir',
		tooltip: 'Salir',
		id:'salir',
		handler:function(){
		        if(salirAlterno!=''){
		        	location.href=salirAlterno;
		        }
		        else{
		        	location.href='sigesp_vis_'+modulo+'_index.php'
		        }
				
			}
		});
	 	
		
	var myJSONObject = null;
		
	if(sistemaParametro!="" && vistaParametro != ""){
		myJSONObject = {'oper': 'barraherramienta','codsis': sistemaParametro,'nomven': vistaParametro};
	}
	else{
		myJSONObject = {'oper': 'barraherramienta','codsis': sistema,'nomven': vista};
	}
		
	var ObjSon = Ext.util.JSON.encode(myJSONObject);
	var parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
		url : '../../controlador/sss/sigesp_ctr_sss_barra_herramientas.php',
		params : parametros,
		method: 'POST',
		success: function ( resultado, request){
			var datos  = resultado.responseText;
			var objeto = eval('(' + datos + ')');
			if (objeto.raiz[0].visible==0){
				Ext.Msg.show({
					   title:'Seguridad',
					   msg: 'Usted no tiene permiso para visualizar esta pantalla',
					   buttons: Ext.Msg.OK,
					   animEl: 'elId',
					   icon: Ext.MessageBox.WARNING
					});
			}
			else{
				codmenu    = objeto.raiz[0].codmenu;
				if (objeto.raiz[0].incluir==1){
					tbnuevo = true;
					tb_barraherramienta.add(nuevo);
				}
				
				if (objeto.raiz[0].cambiar==1){
						tbactualizar = true;
				}
				
				if (((tbnuevo==true)|| (tbactualizar==true)) && (tbguardar==false)){
					tbguardar = true;
					tb_barraherramienta.add(guardar);
				}
				
				if (objeto.raiz[0].leer==1){
					tb_barraherramienta.add(buscar);
				}
				
				if (objeto.raiz[0].eliminar==1){
					tb_barraherramienta.add(eliminar);
				}
				
				if (objeto.raiz[0].anular==1){
					//tb_barraherramienta.add(anular);
				}
				
				if (objeto.raiz[0].ejecutar==1){
					//tb_barraherramienta.add(procesar);
				}
				
				if (objeto.raiz[0].administrativo==1){
					tbadministrativo = true;
				}
				
				if (objeto.raiz[0].imprimir==1){
					tb_barraherramienta.add(imprimir);
				}
					
				tb_barraherramienta.add(salir);
			}
		},
		failure: function (resultado,request){ 
			Ext.MessageBox.alert('Error', request); 
		}
	});
		
	tb_barraherramienta.render('barra_herramientas');
});

function setSistemaVentana(codsis,nomven) {
	sistemaParametro = codsis;
	vistaParametro = nomven;
}