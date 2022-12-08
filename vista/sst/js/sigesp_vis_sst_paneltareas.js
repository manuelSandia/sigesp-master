/**
 * 
 */
var dsTramite=null;
Ext.onReady(function(){
	Ext.BLANK_IMAGE_URL = '../../base/librerias/js/ext/resources/images/default/s.gif';
	var registro_tramite = Ext.data.Record.create([
					{name: 'numtramite'},
					{name: 'coddocenv'},
					{name: 'tipdocenv'},
					{name: 'fecenv'},
					{name: 'estrec'}
			]);

	dsTramite = new Ext.data.GroupingStore({
					groupField: 'estrec',
					sortInfo:{field: 'numtramite', direction: "ASC"},
					reader: new Ext.data.JsonReader({root: 'raiz',id: "id"},registro_tramite)
			});
	
	function getEstado(estado){
		switch(estado){
			case "S":
			 return "En Proceso";
			 break;
			case "N":
			 return "Por Procesar";
			 break;
		}
	}	
					
	var colmodTramite = new Ext.grid.ColumnModel([
      			{header: "Numero Tramite", width: 50, sortable: true,   dataIndex: 'numtramite'},
      			{header: "Documento", width: 50, sortable: true,   dataIndex: 'coddocenv'},
      			{header: "Tipo", width: 30, sortable: true,   dataIndex: 'tipdocenv'},
      			{header: "Fecha", width:30, sortable: true, dataIndex: 'fecenv',renderer:formatoFechaHoraGrid},
      			{header: "Estado", width:30, sortable: true, dataIndex: 'estrec',renderer:getEstado}
			]);
	//fin creando datastore y columnmodel para el catalogo de unidades ejecutoras
	
	var gridtramites = new Ext.grid.GridPanel({
 							title:'Asignaciones del usuario',
 							width:700,
 							height:300,
							ds: dsTramite,
							style:'position:absolute;left:5px;top:80px',
   							cm: colmodTramite,
   							stripeRows: true,
  							view: new Ext.grid.GroupingView({startCollapsed:true,forceFit: true,groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Items" : "Items"]})'})
					});
	
	//funcion para llenar el combo de tipo solicitud
	function getDataTramite(){
		var cadenaJson="{'operacion':'paneltramite'}";
		var parametros = 'ObjSon='+cadenaJson;
		Ext.Ajax.request({
			url : '../../controlador/sst/sigesp_ctr_sst_catalogosst.php',
			params : parametros,
			method: 'POST',
			success: function ( resultado, request)	{ 
				var datos = resultado.responseText;
				var objetotiposol = eval('(' + datos + ')');
				if(objetotiposol!=''){
					dsTramite.loadData(objetotiposol);
				}
			}
		})
	}
	//fin funcion para llenar el combo de tipo solicitud
	getDataTramite();
	var Xpos = ((screen.width/2)-(930/2));
	var ventana_principal = new Ext.Panel({
                 autoScroll:true,
                 style: 'position:absolute;margin-left:'+Xpos+'px;margin-top:30px',
                 width:930,
				 height:525,
                 frame:true,
                 closable:false,
                 plain: false,
                 items:[gridtramites],
                 applyTo: 'grid_tareas'
        });
	 ventana_principal.show();
});