var IdPadre = '';
var Listo2 = false;
var Oper='';
var DatosNuevo ='';
var tabs='';
var tabsplan='';
var combo1=''; 
var combo2=''; 
var Formulario1 = '';
var DataStore6='';
var denActual ='';
var DatosNuevo='';
var simple='';
var valor1='';  
var TipoEstructura='';
var valor2='';
var gridIntFuente='';
var gridIntProb='';
var gridIntVar='';
var FormEst='';
var anchoCombo=90;
var anchoTextoCombo=600;  
var nivelUbicaciones='';
var RecordDefPlaPre='';
var ObjFuente ='';
var RecordIntePlan='';
var winAd=null;
var RecordDefProb='';
var winUb=null; 
var RecordDefAd='';
var CatPreUnavez=false;
var CatAdUnavez=false;
var CatPlanUnavez=false;
var CatUbUnavez=false;
var RecordDefUb='';
var gridIntFuente2=null;
var Busqueda=false;
var DataStoreProb='';
var DataStoreAdmin='';
var DataStoreUb='';
var DataStoreGastos='';
var DataStoreVar='';
var DsBusqueda='';
var RecordDefEstPre='';
var GrabarInt='';
var Rec="";
ruta2='../../procesos/sigesp_spe_comboubgeopr.php';
ruta ='../../procesos/sigesp_spe_comboestpr.php';
rutaGrid ='../../procesos/sigesp_sfp_fuentefinpr.php';
rutaIntepr='../../procesos/sigesp_spe_integracionpr.php';
var pantalla='sigesp_spe_integracion.php';

Ext.onReady(function()
{

ObtenerSesion(rutaIntepr,pantalla)

function getobject()
{
	var myJSONObject ={
		"oper": 'datosInt',
		"tipodato": 'fuentefin', 
		"cod_fuenfin": "", 
		"denfuefin": "",
		"expfuefin":""
	};	
			
		//alert(DatosNuevo)	
			RecordIntePlan = Ext.data.Record.create([
			{name: 'cod_fuenfin'}, 
			{name: 'codinte'},
			{name: 'montot'},
			{name: 'denfuefin'}	
			]);
			var DatosNuevo={"raiz":[{"cod_fuenfin":'',"codinte":'',"montot":'',"denfuenfin":''}]};
			
			DataStore =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                     RecordIntePlan
			      ),
				data: DatosNuevo
                 });
			gridIntFuente = new Ext.grid.EditorGridPanel({
			width:770,
			id:'fuentefin',
			autoScroll:true,
                        border:true,
                        ds:DataStore,
                        cm: new Ext.grid.ColumnModel([
                            {header: "Fuente de Financiamiento", width: 150, sortable: true,   dataIndex: 'denfuefin'},
                            {header: "Monto", width: 350, sortable: true, dataIndex: 'montot',editor: new Ext.form.NumberField({allowBlank: false})}			
 ]),

selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                        forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
                   });
		gridIntFuente.render('grid-fuentes');
		
}


function ActualizarGridsDetalles()
{
		GridActual=ObtenerGrid(tabActual);
		var myJSONObject ={
		"oper": 'datosInt',
		"tipodato":GridActual.getId(), 
		"codinte":IdPadre
	};
	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : rutaIntepr,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ){ 
	datos = resultado.responseText;
		 if(datos!='')
		 {
			var DatosNuevo = eval('(' + datos + ')');
			GridActual.store.loadData(DatosNuevo);
		 }
		}
	})	
			
}

function getGridAd()
{
			var myJSONObject ={
				"oper": 'datosInt',
				"tipodato": 'uniAds', 
				"cod_fuenfin": "", 
				"denfuefin": "",
				"expfuefin":""
			};	

			RecordDefAd = Ext.data.Record.create
			([
				{name: 'coduac'},    
				{name: 'denuac'}, 
				{name: 'nivel'},
				{name: 'codinte'}
			]);
			
			var DatosNuevo={"raiz":[{"coduac":'',"denuac":'',"codinte":'',"nivel":'nivel'}]};	
			DataStoreAdmin =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',               
			    id: "id"   
			    },
                      RecordDefAd
			    ),
				data: DatosNuevo
                });
		
			 gridIntAd = new Ext.grid.EditorGridPanel({
			 id:'uniAds',	
			 width:770,
			 autoScroll:true,
                        border:true,
                        ds:DataStoreAdmin,
                        cm: new Ext.grid.ColumnModel([
                            {header: "Denominaci&#243;n", width: 350, sortable: true, dataIndex: 'denuac'}
]),

selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                        forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
                   });
		   
		gridIntAd.render('grid-uniads');
		
}
function getGridUb()
{
	
			var myJSONObject ={
				"oper": 'datosInt',
				"tipodato":'ubGeo'			
				};	
	
			RecordDefUb = Ext.data.Record.create
			([
				{name: 'codubgeo1'}, 
				{name: 'codubgeo2'},
				{name: 'codubgeo3'},
				{name: 'codubgeo4'},               
				{name: 'codubgeo5'}, 
				{name: 'nivel'}, 
				{name: 'denominacion'}
			]);
			
			var DatosNuevo={"raiz":[{"coduac":'',"denuac":'',"codinte":''}]};	
			DataStoreUb =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',               
			    id: "id"   
			    },
                      RecordDefUb
			    ),
				data: DatosNuevo
                });
			
			 gridIntUb = new Ext.grid.EditorGridPanel({
			 id:'uniAds',	
			width:770,
			autoScroll:true,
                        border:true,
                        ds:DataStoreUb,
                        cm: new Ext.grid.ColumnModel([
                        {header: "Denominaci&#243;n", width: 350, sortable: true, dataIndex: 'denominacion',editor: new Ext.form.TextField({allowBlank: false})}
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:
						{
                        forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
                   });				   		   
		gridIntUb.render('grid-ubgeos');
}

function getGridProb()
{
	
			var myJSONObject ={
				"oper": 'datosInt',
				"tipodato":'ubGeo'			
			};	
	
			RecordDefProb = Ext.data.Record.create
			([
				{name: 'codinte'}, 
				{name: 'codprob'},
				{name: 'descripcion'},
				{name: 'denominacion'},
				{name: 'causa'},
				{name: 'efecto'}
			]);
			
			var DatosNuevo={"raiz":[{"codprob":'',"denominacion":'',"causa":''}]};	
			DataStorePro =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',               
			    id: "id"   
			    },
                      RecordDefProb
			    ),
				data: DatosNuevo
                });
			
			 gridIntProb = new Ext.grid.GridPanel({
			 id:'Probs',	
			width:770,
			autoScroll:true,
                        border:true,
                        ds:DataStorePro,
                        cm: new Ext.grid.ColumnModel([
                            {header: "Causas Asociadas al Problema", width: 350, sortable: true, dataIndex: 'denominacion',editor: new Ext.form.TextField({allowBlank: false})}
]),

selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                        forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
                   });				   		   
		gridIntProb.render('grid-probs');
}


function getGridVar()
{
			var myJSONObject ={
				"oper": 'datosInt',
				"tipodato":'ubGeo'			
			};	
	
			RecordDefVar = Ext.data.Record.create 
			([
				{name: 'cod_var'}, 
				{name: 'montoglobal'},
				{name: 'meta'},
				{name: 'NuevoRegistro'},
				{name: 'enero_masc'},
				{name: 'febrero_masc'},
				{name: 'marzo_masc'},
				{name: 'abril_masc'},
				{name: 'mayo_masc'},
				{name: 'junio_masc'},
				{name: 'julio_masc'},
				{name: 'agosto_masc'},
				{name: 'septiembre_masc'},
				{name: 'octubre_masc'},
				{name: 'noviembre_masc'},
				{name: 'diciembre_masc'},
				{name: 'enero_fem'},
				{name: 'febrero_fem'},
				{name: 'marzo_fem'},
				{name: 'abril_fem'},
				{name: 'mayo_fem'},
				{name: 'junio_fem'},
				{name: 'julio_fem'},
				{name: 'agosto_fem'},
				{name: 'septiembre_fem'},
				{name: 'octubre_fem'},
				{name: 'noviembre_fem'},
				{name: 'diciembre_fem'}
			]);
			
			var DatosNuevo={"raiz":[{"cod_var":'',"meta":'',"unidad":'',"genero":''}]};	
			DataStoreVar =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',               
			    id: "id"   
			    },
                      RecordDefVar
			    ),
				data: DatosNuevo
                });
			
			gridIntVar = new Ext.grid.GridPanel({
			id:'Metas',	
			width:770,
			autoScroll:true,
            border:true,
            ds:DataStoreVar,
            cm: new Ext.grid.ColumnModel([
            {header: "Denominaci&#243;n", width: 350, sortable: true, dataIndex: 'meta'}
]),

selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:
						{
                        	forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
                   });				   		   
		gridIntVar.render('grid-vars');
		gridIntVar.addListener('celldblclick',getGridMontosMetas);
}


function getGridInd()
{
	
			var myJSONObject ={
				"oper": 'datosInt',
				"tipodato":'ubGeo'			
			};	
	
			RecordDef = Ext.data.Record.create
			([
				{name: 'codinte'}, 
				{name: 'codprob'},
				{name: 'denominacion'}
			]);
			
			var DatosNuevo={"raiz":[{"coduac":'',"denuac":'',"codinte":''}]};	
			DataStore =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',               
			    id: "id"   
			    },
                      RecordDef
			    ),
				data: DatosNuevo
                });
			
			 gridIntInd = new Ext.grid.EditorGridPanel({
			id:'Probs',	
			width:770,
			autoScroll:true,
            border:true,
            ds:DataStore,
            cm: new Ext.grid.ColumnModel([
            {header: "Denominaci&#243;n", width: 350, sortable: true, dataIndex: 'denominacion',editor: new Ext.form.NumberField({allowBlank: false})}
]),

selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
             viewConfig:{
             forceFit:true
              },
			autoHeight:true,
			stripeRows: true
                   });				   		   
		 gridIntInd.render('grid-ind');
}


function getGridGastos()
{
			var myJSONObject ={
				"oper": 'datosInt',
				"tipodato":'ubGeo'			
			};	
	
			RecordDef = Ext.data.Record.create
			([
				{name: 'spg_cuenta'}, 
				{name: 'denominacion'},
				{name: 'codinte'},
				{name: 'montoglobal'},
				{name: 'NuevoRegistro'},
				{name: 'ano_presupuesto'},
				{name: 'enero'},
				{name: 'febrero'},
				{name: 'marzo'},
				{name: 'abril'},
				{name: 'mayo'},
				{name: 'junio'},
				{name: 'julio'},
				{name: 'agosto'},
				{name: 'septiembre'},
				{name: 'octubre'},
				{name: 'noviembre'},
				{name: 'diciembre'},
				{name: 'montoanoanterior'},
				{name: 'montoanoactual'},
				{name: 'codigohaber'},
				{name: 'denhaber'},
				{name: 'codigodebe'},
				{name: 'dendebe'},
				{name: 'montoanoactual'},
				{name: 'codigohaber'},
				{name: 'denhaber'},
				{name: 'codigodebe'},
				{name: 'dendebe'},
				{name: 'codvarhaber'},
				{name: 'denvarhaber'},
				{name: 'codvardebe'},
				{name: 'denvardebe'},
				{name: 'codcaif'},		
				{name: 'montoanpre'},
				{name: 'montoanant'},
				{name: 'montoanreal'},
				{name: 'cuentadebe'},
				{name: 'cuentahaber'},
				{name: 'fuentes'}
			]);
			
			var DatosNuevo={"raiz":[{"spg_cuenta":'',"denominacion":'',"codinte":''}]};	
			
			DataStoreGastos =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',               
			    id: "id"   
			    },
                      RecordDef
			    ),
				data: DatosNuevo
                });
                
            
			gridIntGastos = new Ext.grid.EditorGridPanel({
			id:'Probs',	
			height:200,
			width:770,
			autoScroll:true,
                        border:true,
                        ds:DataStoreGastos,
                        cm: new Ext.grid.ColumnModel([
                            {header: "Codigo de la Cuenta", width: 100, sortable: true, dataIndex: 'spg_cuenta'},
                            {header: "Denominaci&#243;n", width: 250, sortable: true, dataIndex: 'denominacion'},
							{header: "Monto Global", width: 250, sortable: true, dataIndex:'montoglobal',editor: new Ext.form.NumberField({allowBlank: false})}                            
]),

selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                        forceFit:true
                        },
			stripeRows: true
        });				   		   
		gridIntGastos.render('grid-Gastos');
		gridIntGastos.addListener('celldblclick',getGridMontosGastos);
		
}

		

   		Ext.QuickTips.init(); 
		
		 tabsProb= new Ext.TabPanel(
        {
          baseCls:'x-plain',
		  renderTo: 'tabs1',
		  activeTab: 0,
		  frame:true,
		  autoScroll:true,
          width:1024,
          height:500,		
          modal: true,
          closeAction:'hide',
          plain: false
		    ,defaults: {frame:true, width:800, height: 200}
          ,items:[
				{
                    	title:'Estructura Administrativa',
                    	id:'Itab1',
                    	contentEl:'grid-uniads'
                	}
					,
					{
                    	title:'Ubicaci&#243;n Geogr�fica',
                    	id:'Itab2',
                    	contentEl:'grid-ubgeos'
                	}
  			]     
        });


Ext.get('ImgRestar').on('click', function()
{

	var selectedKeys = grid.selModel.selections.keys;
        if(selectedKeys.length > 0) {
            Ext.Msg.confirm('ALERTA!','Realmente desea eliminar el registro?', deleteRecord);
        } 
        else
        {
            Ext.Msg.alert('ALERTA!','Seleccione un registro para eliminar');
        }
});

 function deleteRecord(btn) 
 {
	  if (btn=='yes') 
	  {
		var selectedRow = grid.getSelectionModel().getSelected();
		if(selectedRow)
		{
			DataStore.remove(selectedRow);
		}
	  } 
}


 Ext.get('ImgSumar').on('click', function(){
	var myJSONObject ={
		"oper": 'catalogo', 
		"cod_fuenfin": "", 
		"denfuefin":"",
		"expfuefin":""
	};
	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ){ 
		  datos = resultado.responseText;
		//  alert(datos);
		  var myObject = eval('(' + datos + ')');
		  var RecordDef = Ext.data.Record.create([
		{name: 'cod_fuenfin'},     
		{name: 'denfuefin'},
		{name: 'expfuefin'}	// This field will use "occupation" as the mapping.
		]);
                  if (!gridOnOff)
                  {
		            grid2 = new Ext.grid.GridPanel({
					width:770,
					autoScroll:true,
		            border:true,
                    ds: new Ext.data.Store({
					proxy: new Ext.data.MemoryProxy(myObject),
					reader: new Ext.data.JsonReader({
				    root: 'raiz', 
			     	id: "id"   
			    
					},
                        RecordDef
			     
			       ),
				data: myObject
                        }),
                        cm: new Ext.grid.ColumnModel([
                            new Ext.grid.RowNumberer(),
{header: "C&#243;digo", width: 30, sortable: true,   dataIndex: 'cod_fuenfin'},
{header:"Denominaci&#243;n", width: 50, sortable: true, dataIndex: 'denfuefin'},
{header: "Explicaci&#243;n", width: 70, sortable: true, dataIndex: 'expfuefin'}
							
                        ]),

                        viewConfig: {
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
                   });
                   gridOnOff = true;
                 }
                  else
                  {
                  	grid2.store.loadData(myObject);
                  } 
				  
				  
		var simple = new Ext.FormPanel({
        labelWidth: 75,
        frame:true,
        title: 'B�squeda',
        bodyStyle:'padding:5px 5px 0',
        width: 350,
		height:120,
        defaults: {width: 230},
        defaultType: 'textfield',
		items: [{
                fieldLabel: 'C&#243;digo',
                name: 'cod',
				id:'cod',
				changeCheck: function(){
							  var v = this.getValue();
							 ActualizarData('cod_fuenfin',v);
							if(String(v) !== String(this.startValue)){
								this.fireEvent('change', this, v, this.startValue);
							} 
							 },
							 
							initEvents : function()
							{
								AgregarKeyPress(this);
							}
               
            },{
                fieldLabel: 'Denominaci&#243;n',
                name: 'den',
				changeCheck: function(){
							  var v = this.getValue();
							 ActualizarData('denfuefin',v);
							if(String(v) !== String(this.startValue))
							{
								this.fireEvent('change', this, v, this.startValue);
							} 
							 },
							 
							initEvents : function()
							{
								AgregarKeyPress(this);
							}

            }]
		});
		
					  
                  if(!winOnOff)
                  {
                   //var win = new Ext.Window(
					var ventanaFuenteFinan 	= new Ext.Window(					  
                   {
                    //layout:'fit',
                    title: 'Cat&aacute;logo de Fuente de Financiamiento',
		    		autoScroll:true,
                    width:800,
                    height:400,
                    modal: true,
                    closeAction:'hide',
                    plain: false,
                    items:[simple,grid2],
                    buttons: [{
                     text:'Aceptar',  
                     handler: function()
                     {
 					 var p = new RecordDef(
                    {cod_fuenfin:'nuevo',
                    denfuefin: 'nuevo',
                    expfuefin: 'nuevo'}	                   
	                );
	                //grid.stopEditing();
	                DataStore.insert(0, p);
	                grid.startEditing(0, 0);
				    //win.hide();
					ventanaFuenteFinan.destroy();
                     }
                    },
                    {
                     text: 'Salir',
                     handler: function()
                     {
                      //win.hide();
                      ventanaFuenteFinan.destroy();
                     }
                    }]
                   });
                   //winOnOff = true;
                   //estaba alla donde dice aqui
                  }
                  else
                  {
                   //win.add(grid);
                   //alert(win.title);
                  }
                  //estaba aqui
                  //win.show();
				  ventanaFuenteFinan.show();
                   if(!unavez)
                   {
                    grid.render('miGrid');
                    unavez=false;
                   }
                   grid.getSelectionModel().selectFirstRow();
        }
		,
        failure: function ( resultado, request) { 
                   Ext.MessageBox.alert('Error', resultado.responseText); 
        }
	
   });

 });


function AgregarKeyPress(Obj)
{
		Ext.form.TextField.superclass.initEvents.call(Obj);
		if(Obj.validationEvent == 'keyup')
		{
			Obj.validationTask = new Ext.util.DelayedTask(Obj.validate, Obj);
			Obj.el.on('keyup', Obj.filterValidation, Obj);
		}
		else if(Obj.validationEvent !== false)
		{
			Obj.el.on(Obj.validationEvent, Obj.validate, Obj, {buffer: Obj.validationDelay});
		}
		if(Obj.selectOnFocus || Obj.emptyText)
		{
			Obj.on("focus", Obj.preFocus, Obj);
			if(Obj.emptyText)
			{
				Obj.on('blur', Obj.postBlur, Obj);
				Obj.applyEmptyText();
			}
		}
		if(Obj.maskRe || (Obj.vtype && Obj.disableKeyFilter !== true && (Obj.maskRe = Ext.form.VTypes[Obj.vtype+'Mask']))){
			Obj.el.on("keypress", Obj.filterKeys, Obj);
		}
		if(Obj.grow)
		{
			Obj.el.on("keyup", Obj.onKeyUp,  Obj, {buffer:50});
			Obj.el.on("click", Obj.autoSize,  Obj);
		}
			Obj.el.on("keyup", Obj.changeCheck, Obj);
}


getGridAd();
//getobject();
getGridUb();
getGridProb();
getGridVar();
getGridInd();

		function irQuitarEstPre()
		{
				var meta = gridEstPreSelec.selModel.selections.keys;
				if(meta.length > 0)
				{
					Ext.Msg.confirm('Alerta!','Realmente desea eliminar el registro?', borrarEstructura);
				} 
				else 
				{
					Ext.Msg.alert('Alerta!','Seleccione un registro para eliminar');
				}			
			//alert('prueba');
		}

		Ext.get('BtnElim').on('click',function(){
			var meta = gridEstPreSelec.selModel.selections.keys;
			if(meta.length > 0)
			{
				Ext.Msg.confirm('Alerta!','Realmente desea eliminar el registro?', EliminarIntegracion);
			} 
			else 
			{
				Ext.Msg.alert('Alerta!','Seleccione un registro para eliminar');
			}			
		})
		
		
		function EliminarIntegracion()
		{
				var fila = gridEstPreSelec.getSelectionModel().getSelected();
				if (fila)
				{
					var myJSONObject =
					{
						"oper": 'EliminarInt',
						"codinte":fila.get('codinte')
					};
					ObjSon=JSON.stringify(myJSONObject);
					parametros = 'ObjSon='+ObjSon; 
					Ext.Ajax.request({
					url : rutaIntepr,
					params : parametros,
					method: 'POST',
					success: function( resultado, request ){ 
					datos = resultado.responseText;
					if(datos!='')
					{
						 Ext.Msg.alert('Mensaje','La integraci&#243;n se elimin&#243; exitosamente');
						 EstadoInicial();
					}
					}
					})
				}	
			function CambiarPantalla()
			{		
			   Ext.Msg.show({
			   title:'Mensaje',
			   msg: 'La integraci&#243;n se elimin&#243; exitosamente',
			   buttons: Ext.Msg.OK,
			   fn: processResult,
			   animEl: 'elId',
			   icon: Ext.MessageBox.INFO
			   });	
			}		
		}
	function borrarEstructura(btn)
	{		
			if(btn=='yes') 
			{
				var fila = gridEstPreSelec.getSelectionModel().getSelected();
				if (fila)
				{
					gridEstPreSelec.store.remove(fila);
						
				}
		
			}
	}		
		var agregarEstPlan = new Ext.Action(
		{
			text: 'Agregar',
			handler: irAgregarEstPlan,
			iconCls: 'bmenuagregar',
        	tooltip: 'Agregar Estructura del Plan'
		});		
		var quitarEstPlan = new Ext.Action(
		{
			text: 'Quitar',
		//	handler: irQuitarEstPlan,
			iconCls: 'bmenuquitar',
        	tooltip: 'Quitar Estructura del Plan'
		});	

		var agregarEstPre = new Ext.Action(
		{
			text: 'Agregar',
			handler: irAgregarEstPre,
			iconCls: 'bmenuagregar',
        	tooltip: 'Agregar Estrucutura Presupuestaria'
		});		
		var quitarEstPre = new Ext.Action(
		{
			text: 'Quitar',
			handler: irQuitarEstPre,
			iconCls: 'bmenuquitar',
        	tooltip: 'Quitar Estrucutura Presupuestaria'
		});  	
		
		
		
		   var expander = new Ext.grid.RowExpander({
		        tpl : new Ext.Template(
		            '<p><b>Descripci�n:</b> {DenSel}</p><br>'
		        )
		    });
		
			
		    var DatosNuevo={"raiz":[{"NombreNivel":'',"CodSel":'',"DenSel":''}]};
			RecordDefPlaPre = Ext.data.Record.create
			([
				{name: 'NombreNivel'},     // "mapping" property not needed if it's the same as "name"
				{name: 'CodSel'},
				{name: 'DenSel'}	// This field will use "occupation" as the mapping.
			]);

			DataStorePlan =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    }
			    ,
                    RecordDefPlaPre
			     
			    )
			    /*,
			data: DatosNuevo //Modificado por Arnaldo */
              });

		 gridEstPlanSelec = new Ext.grid.EditorGridPanel({
		 width:500,
		 autoScroll:true,		
         border:true,
         height:170,
         ds:DataStorePlan,
         tbar:[agregarEstPlan,quitarEstPlan],
          cm: new Ext.grid.ColumnModel([
          					expander,
                            {header: "", width: 150, sortable: true,   dataIndex: 'NombreNivel'},
                            {header: "", width: 350, sortable: true, dataIndex: 'CodSel'}
							
                        ]),
                        
			        plugins: expander,
			        collapsible: true,
			        animCollapse: false,
					selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                        forceFit:true
                        },
			stripeRows: true
     });
     
     	 	gridEstPlanSelec.render('PlanSeleccionado');
     	 	RecordDefEstPre = Ext.data.Record.create
			([
				{name: 'codigo'},     // "mapping" property not needed if it's the same as "name"
				{name: 'descripcion'},
				{name: 'codestpro1'},
				{name: 'codestpro2'},
				{name: 'codestpro3'},
				{name: 'codestpro4'},
				{name: 'codestpro5'},
				{name: 'codinte'}
			]);
     	 	DataStorePre =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                     RecordDefEstPre
			     
			    )
              });
     	
     	 var expander2 = new Ext.grid.RowExpander({
		        tpl : new Ext.Template(
		            '<p><b>Descripci�n:</b>{descripcion}</p><br>'
		        )
		}); 
     	DsBusqueda =  new Ext.data.Store({
		proxy: new Ext.data.MemoryProxy(DatosNuevo),
		reader: new Ext.data.JsonReader({
			root: 'raiz',                
			id: "id"   
			},
            RecordDefPlaPre
			),
			data: DatosNuevo
         });   
		 gridEstPreSelec = new Ext.grid.GridPanel({
		 width:460,
		 height:160,
		 plugins: expander2,
		 collapsible: true,
		 animCollapse: false,
		 autoScroll:true,
         border:true,
         ds:DataStorePre,
         tbar:[agregarEstPre,quitarEstPre],
         cm: new Ext.grid.ColumnModel([
         					expander2,
                            {header: "C&#243;digo de la estructura", width: 180, sortable: true,dataIndex: 'codigo'}  
							
                        ]),
		selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                        forceFit:true
                        },
			stripeRows: true
                   });
		gridEstPreSelec.render('EstSeleccionado');
		gridEstPreSelec.on('rowdblclick',function(Obj,i){
			Rec = Obj.getSelectionModel().getSelected();
			Titulogrid='Codigo de la Estructura: '+Rec.get('codigo')+' '+'Descripcion: '+Rec.get('descripcion');
			Ext.getCmp('panelDetalles').setTitle(Titulogrid);
			IdPadre=Rec.get('codinte');
		})
		
	simple2 = new Ext.Panel({
	renderTo:'progra',
	layout:'table',
	width:1024,
	height:200,
	layoutConfig:{columns:0},
	 defaults: {frame:true, width:512, height: 512},
	id:'main-panel',
    items:[{
	    title:'Estructura del Plan',
	    contentEl:'PlanSeleccionado',
     }
     ,
	 {
	    title:'Estructura Presupuestaria',
	    contentEl:'EstSeleccionado',
     }
	]
	});  		
  		
  		var agregarEstPlan = new Ext.Action(
		{
			text: 'Agregar',
			//handler: irAgregarEstPlan,
			iconCls: 'bmenuagregar',
        	tooltip: 'Agregar cuenta'
		});		
		var quitarEstPlan = new Ext.Action(
		{
			text: 'Agregar',
		//	handler: irQuitarEstPlan,
			iconCls: 'bmenuagregar',
        	tooltip: 'Agregar cuenta'
		});
		var agregarEstPre = new Ext.Action(
		{
			text: 'Agregar',
			//handler: irAgregarEstPre,
			iconCls: 'bmenuagregar',
        	tooltip: 'Agregar cuenta'
		});		
		var quitarEstPre = new Ext.Action(
		{
			text: 'Quitar',
			//handler: irQuitarEstPre,
			iconCls: 'bmenuquitar',
        	tooltip: 'Eliminar registro'
		});
  		

  		var agregarEjeFis = new Ext.Action(
		{
			text: 'Agregar',
			handler: irAgregarEjeFis,
			iconCls: 'bmenuagregar',
        	tooltip: 'Agregar cuenta'
		});
		
		var quitarEjeFis = new Ext.Action(
		{
			text: 'Quitar',
			handler: irQuitarEjeFis,
			iconCls: 'bmenuquitar',
        	tooltip: 'Eliminar registro'
		});
		
		var agregarEjeFin = new Ext.Action(
		{
			text: 'Agregar',
			handler: irAgregarEjeFin,
			iconCls: 'bmenuagregar',
        	tooltip: 'Agregar cuenta'
		});
		
		var quitarEjeFin = new Ext.Action(
		{
			text: 'Quitar',
			handler: irQuitarEjeFin,
			iconCls: 'bmenuquitar',
        	tooltip: 'Eliminar registro'
		});	
		
function DesabilitarGrids(valor)
{
	item1.setDisabled(valor);
	item2.setDisabled(valor);	
}
		
		var item1 = new Ext.Panel({
		    title: 'Informaci&#243;n asociada a la ejecuci&#243;n f�sica del proyecto',
		    contentEl:'tabs1',
		    id:'panelDetalles',
		    tbar:[agregarEjeFis,quitarEjeFis],
			cls:'empty'
	     });
		//Ext.getCmp('panelDetalles').title='este';
		
 	  Ext.state.Manager.setProvider(new Ext.state.CookieProvider());        
		
       var viewport = new Ext.Viewport({
            layout:'border',
            items:[
                new Ext.BoxComponent({ // raw
                    region:'north',
                    el: 'norte',
                    height:100
                })
				,
                new Ext.Panel({
                region:'south',
                width:210,
                height:250,
                style:"height:250px",
                bodyStyle:'background-color:#DFE8F6',
              //  layout:'accordion',
                items: [item1]
            })
			,
               new Ext.TabPanel({
                            border:false,
                            activeTab:0,
                            height:400,
                            autoScroll:true,
                            region:'center',
                            items:
							[
								{
	                                contentEl:'progra',
	                                title: 'Integraci&#243;n Presupuestaria',
	                                autoScroll:true
	
	                            }
								
							]
                    
                })]
         })	
;
function MostrarCatest()
{
	      //win = new Ext.Window(
	      var ventanaEstructura = new Ext.Window( 
          {
          	//layout:'fit',
            title: 'Cat&aacute;logo de Estructuras',
		    autoScroll:true,
            width:800,
            height:400,
            modal: true,
            closeAction:'hide',
            plain: false,
            items:[Formulario1,tabsplan],
            buttons:[{
            text:'Aceptar',  
            handler: function()
            {            	
            if(ListoUltimoPlan==true)
            { 
                if(valorPlan1!='')
                 {
                	PasarDatosGrids(Ext.get('nivelPlan1').dom.innerHTML,
valorPlan1,denPlan1,gridEstPlanSelec);
				 }
				 if(valorPlan2!='')
				 {
					PasarDatosGrids(Ext.get('nivelPlan2').dom.innerHTML,
valorPlan2,denPlan2,gridEstPlanSelec);					
				 }
  				if(valorPlan3!='')
  				{
					PasarDatosGrids(Ext.get('nivelPlan3').dom.innerHTML,
valorPlan3,denPlan3,gridEstPlanSelec);				
						
				}
				if(valorPlan4!='')
  				{
					PasarDatosGrids(Ext.get('nivelPlan4').dom.innerHTML,
valorPlan4,denPlan4,gridEstPlanSelec);				
			
				}
	
				if(valorPlan5!='')
  				{
  				
					PasarDatosGrids(Ext.get('nivelPlan5').dom.innerHTML,
valorPlan5,denPlan5,gridEstPlanSelec);				
						
				}
				if(valorPlanActual!='')				
				{
			
					PasarDatosGrids(Ext.get('nivelPlan'+cantidadPlan).dom.innerHTML,
valorPlanActual,denPlanActual,gridEstPlanSelec);				
						
				}
		      	//win.hide(); 
				ventanaEstructura.destroy();
            }
            else
            {
				Ext.Msg.alert('Mensaje','Debe seleccionar toda la estructura');
			}
            }
            },
             {
            text: 'Salir',
            handler: function()
            {
                //win.hide();
				ventanaEstructura.destroy();
            }
            }]
            });
		   //win.show()
            ventanaEstructura.show();
}

function MostrarCatUb()
{
	      winUb = new Ext.Window(
          {
          	//layout:'fit',
            title: 'Cat&aacute;logo de Ubicaci&#243;n Geogr�fica',
		    autoScroll:true,
            width:800,
            height:400,
            modal: true,
            closeAction:'hide',
            plain: false,
            items:[Formulario1,tabsUb],
            buttons:[{
            text:'Aceptar',  
            handler: function()
            {
            if(ListoUltimoUb==true)
            { 
				if(valorUbActual!='')				
				{
					switch(cantidadUb)
					{
						case "1":
						valorUb1=valorUbActual;
						break;
						case "2":
						valorUb2=valorUbActual;
						break;
						case "3":
						valorUb3=valorUbActual;
						break;
						case "4":
						valorUb4=valorUbActual;
						break;
						case "5":
						valorUb5=valorUbActual;
						break;
					}
				
				}
				
					r=new RecordDefUb({
						'codubgeo1':'',
						'codubgeo2':'',
						'codubgeo3':'',
						'codubgeo4':'',
						'codubgeo5':'',
						'nivel':'',
						'denominacion':''	
					});
					CantidadDatos = gridIntUb.store.getCount()-1;
					gridIntUb.store.insert(CantidadDatos,r);		
					r.set('codubgeo1',valorUb1);
					r.set('codubgeo2',valorUb2);
					r.set('codubgeo3',valorUb3);
					r.set('codubgeo4',valorUb4);
					r.set('codubgeo5',valorUb5);
					r.set('nivel',cantidadUb);
					r.set('denominacion',denUbActual);
					r.set('codinte',1);	
		      		winUb.hide();       
            }
            else
            {
				Ext.Msg.alert('Mensaje','Debe seleccionar toda la estructura');
			}
            }
            }
			,
            {
            text: 'Salir',
            handler: function()
            {
                winUb.hide();
            }
            }]
            });	
            winUb.show();
}



function MostrarCatestpre()
{
	      //var win2 = new Ext.Window(
		   var ventanaEstructuraPresu = new Ext.Window(
          {
          	//layout:'fit',
            title: 'Cat&aacute;logo de Estructuras',
		    autoScroll:true,
            width:800,
            height:400,
            modal: true,
            closeAction:'hide',
            plain: false,
            items:[Formulario2,tabs],
            buttons:[{
            text:'Aceptar',  
            id:'BtnAceptarCatpre',
            handler: function()
            {
          //  alert(TipoEstructura);
            if(ListoUltimo==true)
            {
                if(Busqueda==false)
                {   
            	for(i=0;i<Arsel.length;i++)
				{
					CodigoEst='';
					DenEst='';			
					if(Arsel[i].get('codestpro1') && CodigoEst=='')
					{
						CodigoEst =Arsel[i].get('codestpro1');
					}
					if(Arsel[i].get('codestpro2'))
					{
						CodigoEst =CodigoEst+'-'+Arsel[i].get('codestpro2');
					}
					
					if(Arsel[i].get('codestpro3'))
					{
						CodigoEst =CodigoEst+'-'+Arsel[i].get('codestpro3');				
					}
					
					if(Arsel[i].get('codestpro4'))
					{
						CodigoEst =CodigoEst+'-'+Arsel[i].get('codestpro4');
					}
					
					if(Arsel[i].get('codestpro5'))
					{
						CodigoEst =CodigoEst+'-'+Arsel[i].get('codestpro5');					
					}
					
					
					
					if(Arsel[i].get('denestpro1'))
						DenEst =Arsel[i].get('denestpro1'); 
					if(Arsel[i].get('denestpro2'))
						DenEst =Arsel[i].get('denestpro2');
					if(Arsel[i].get('denestpro3'))
						DenEst =Arsel[i].get('denestpro3');
					if(Arsel[i].get('denestpro4'))
						DenEst =Arsel[i].get('denestpro4');
					if(Arsel[i].get('denestpro5'))
						DenEst =Arsel[i].get('denestpro5');					

						
					r=new RecordDefPlaPre
					(
						{
							'codigo':'',
							'descripcion':'',
							'codestpro1':'',
							'codestpro2':'',
							'codestpro3':'',
							'codestpro4':'',
							'codestpro5':''		
						}
					);
					gridEstPreSelec.store.insert(gridEstPreSelec.store.getCount(),r);
	        		r.set('codigo',CodigoEst);
	        		r.set('descripcion',DenEst);
	        		r.set('codestpro1',Arsel[i].get('codestpro1'));
	        		r.set('codestpro2',Arsel[i].get('codestpro2'));
	        		r.set('codestpro3',Arsel[i].get('codestpro3'));
	        		r.set('codestpro4',Arsel[i].get('codestpro4'));
	        		r.set('codestpro5',Arsel[i].get('codestpro5'));
            	}
             }else

				if(Busqueda==true)
				{
				//	DsBusqueda.removeAll();
					ActualizarTodosGrids();	
				}
		      	//win2.hide();    
				ventanaEstructuraPresu.destroy();
		      	DsBusqueda.removeAll();   
            }
            else
            {
				Ext.Msg.alert('Mensaje','Debe seleccionar toda la estructura');
			}
            }
            },
            {
            text: 'Salir',
            handler: function()
            {
                //win2.hide();
				ventanaEstructuraPresu.destroy();
                DsBusqueda.removeAll();
            }
            }]
            });	
            //win2.show();
			ventanaEstructuraPresu.show();
         //   
}


	Ext.get('BtnImp').on('click',function(){
	var myJSONObject =
	{
		"oper": 'LeerTodos'
	};
	ObjSon=JSON.stringify(myJSONObject);
	//alert(ObjSon);
	//return false;
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : rutaIntepr,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ){ 
		  datos = resultado.responseText;
		  if(datos!='')
		  {
		  	Abrir_ventana (datos);
		  }
	
	}
	})
})


function MostrarCatestAd()
{
		//winAd = new Ext.Window(
		  var ventanaEstructuraAdmin = new Ext.Window(
          {
          	//layout:'fit',
            title: 'Cat&aacute;logo de Estructuras',
		    autoScroll:true,
            width:800,
            height:400,
            modal: true,
            closeAction:'hide',
            plain: false,
            items:[FormularioAd,tabsAd],
            buttons:[{
            text:'Aceptar',  
            handler: function()
            {
            	 	
			if(ListoUltimoAd==true)
			{
					r=new RecordDefAd
					({
						'coduac':'',
						'denuac':'',
						'nivel': '' ,
						'codinte':''	
					});
					
					CantidadDatos = gridIntAd.store.getCount()-1;
					gridIntAd.store.insert(CantidadDatos,r);  
					r.set('coduac',valorAdActual);
					r.set('denuac',denAdActual);
					r.set('nivel',ObjActual);
					r.set('codinte',1);
                    //winAd.hide(); 
					ventanaEstructuraAdmin.destroy();
			}
			else
			{
					Ext.Msg.alert('Mensaje','Debe seleccionar toda la estructura');
			}  
					  		      
            }
            },
             {
            text: 'Salir',
            handler: function()
            {
                //winAd.hide();
				ventanaEstructuraAdmin.destroy();
            }
            }]
            });	
            //winAd.show();
			ventanaEstructuraAdmin.show();
}


function ActualizarTodosGrids()
{
	//Arra = new Array();
	//Arra = DsBusqueda.getRange(0,DsBusqueda.getCount()-1);
	
	Arra = Arsel;
	//return false;
	if(Arra[0].get('codestpro1')!='')
	{
		//alert(Arra[0].get('CodSel'));
		auxcod1=ue_rellenarcampo(Arra[0].get('codestpro1'),25);
	}
	else
	{
		auxcod1 = '0000000000000000000000000';
	}
	
	if(Arra[0].get('codestpro2')!='')
	{
		
		auxcod2=ue_rellenarcampo(Arra[0].get('codestpro2'),25)
	}
	else
	{
		auxcod2 = '0000000000000000000000000';
	}

	if(Arra[0].get('codestpro3')!='')
	{
			
		auxcod3=ue_rellenarcampo(Arra[0].get('codestpro3'),25)
	}
	else
	{
		auxcod3 = '0000000000000000000000000';
	}
	if(Arra[0].get('codestpro4')!='')
	{
		auxcod4=ue_rellenarcampo(Arra[0].get('codestpro4'),25);
	}
	else
	{
		auxcod4 = '0000000000000000000000000';
	}
	if(Arra[0].get('codestpro5')!='')
	{
		auxcod5=ue_rellenarcampo(Arra[0].get('codestpro5'),25);
	}
	else
	{
		auxcod5 = '0000000000000000000000000';
	}
	
	var myJSONObject =
	{
		"oper": 'buscaruna',
		"codestpro1": auxcod1,
		"codestpro2": auxcod2,
		"codestpro3": auxcod3,	
		"codestpro4": auxcod4, 
		"codestpro5": auxcod5
	};
	ObjSon=JSON.stringify(myJSONObject);
	//alert(ObjSon);
	//return false;
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : rutaIntepr,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ) { 
		  datos = resultado.responseText;
		 //alert(datos);
	// Ext.get('norte').dom.innerHTML=datos;
		  if(datos!='|0')
		  {
		  	ArrayObject = datos.split('|');
		  	var DatJson = eval('(' + ArrayObject[0] + ')');
		  	var DatJsonProg = eval('(' + ArrayObject[1] + ')');
		  	var DatJsonPlan = eval('(' + ArrayObject[2] + ')');
		  	
		  //	alert(ArrayObject[3]);
		  	
		  	var DatJsonUb = eval('(' + ArrayObject[3] + ')');
		  	var DatJsonAd = eval('(' + ArrayObject[4] + ')');
		  	nivelUbicaciones = ArrayObject[5];
		  	if(DatJson.raiz!=null)
		  	{
		  		IdPadre=DatJson.raiz[0].codinte;
                                AuxCadJson='{';
		  		if(DatJson.raiz[0].codest1)
		  		{
				AuxCadJson = AuxCadJson+"raiz:[{'NombreNivel':'"+DatJsonPlan.raiz[0].nombre_pest+"','CodSel':'"+DatJson.raiz[0].codest1+"','DenSel':'"+DatJson.raiz[0].denest1+"'}";
				}
				if(DatJson.raiz[0].codest2)
		  		{
				AuxCadJson = AuxCadJson+",{'NombreNivel':'"+DatJsonPlan.raiz[1].nombre_pest+"','CodSel':'"+DatJson.raiz[0].codest2+"','DenSel':'"+DatJson.raiz[0].denest2+"'}";
				}


	if(DatJson.raiz[0].codest3)
	{
			AuxCadJson = AuxCadJson+",{'NombreNivel':'"+DatJsonPlan.raiz[2].nombre_pest+"','CodSel':'"+DatJson.raiz[0].codest3+"','DenSel':'"+DatJson.raiz[0].denest3+"'}";
	}

	if(DatJson.raiz[0].codest4)
	{
			AuxCadJson = AuxCadJson+ ",{'NombreNivel':'"+DatJsonPlan.raiz[3].nombre_pest+"','CodSel':'"+DatJson.raiz[0].codest4+"','DenSel':'"+DatJson.raiz[0].denest4+"'}";
	}

	if(DatJson.raiz[0].codest5)
	{
			AuxCadJson = AuxCadJson+ ",{'NombreNivel':'"+DatJsonPlan.raiz[4].nombre_pest+"','CodSel':'"+DatJson.raiz[0].codest5+"','DenSel':'"+DatJson.raiz[0].denest5+"'}";
	}
        AuxCadJson = AuxCadJson+']}';
        
        
        
        r=new RecordDefPlaPre
         (
		{
                  'codigo':'',
                  'descripcion':''
								
		}
            );
	gridEstPreSelec.store.insert(gridEstPreSelec.store.getCount(),r);
	r.set('codigo',DatJson.raiz[0].codigo);
	r.set('descripcion',DatJson.raiz[0].descripcion);
    r.set('codinte',DatJson.raiz[0].codinte);
    r.set('codestpro1',DatJson.raiz[0].codestpro1);
    r.set('codestpro2',DatJson.raiz[0].codestpro2);
    r.set('codestpro3',DatJson.raiz[0].codestpro3);
    r.set('codestpro4',DatJson.raiz[0].codestpro4);
    r.set('codestpro5',DatJson.raiz[0].codestpro5);
        //IdPadre=DatJson.raiz[0].codinte;
        
        
        
        
        
        
        
        
        
                        /*
		AuxCadJson = AuxCadJson+']}';
	if(DatJson.raiz[0].codestpro1)
	{
				AuxCadJson2 = AuxCadJson2+"raiz:[{'NombreNivel':'"+DatJsonProg.raiz[0].nombre_pest+"','CodSel':'"+DatJson.raiz[0].codestpro1+"','DenSel':'"+DatJson.raiz[0].denestpro1+"'}";
	}
	
	if(DatJson.raiz[0].codestpro2)
	{
				AuxCadJson2 = AuxCadJson2+",{'NombreNivel':'"+DatJsonProg.raiz[1].nombre_pest+"','CodSel':'"+DatJson.raiz[0].codestpro2+"','DenSel':'"+DatJson.raiz[0].denestpro2+"'}";
	}

	if(DatJson.raiz[0].codestpro3)
	{
			AuxCadJson2 = AuxCadJson2+",{'NombreNivel':'"+DatJsonProg.raiz[2].nombre_pest+"','CodSel':'"+DatJson.raiz[0].codestpro3+"','DenSel':'"+DatJson.raiz[0].denestpro3+"'}";
	}


	if(DatJson.raiz[0].codestpro4)
	{
		AuxCadJson2 = AuxCadJson2+ ",{'NombreNivel':'"+DatJsonProg.raiz[3].nombre_pest+"','CodSel':'"+DatJson.raiz[0].codestpro4+"','DenSel':'"+DatJson.raiz[0].denestpro4+"'}";
	}

			if(DatJson.raiz[0].codestpro5)
		  	{
				AuxCadJson2 = AuxCadJson2+ ",{'NombreNivel':'"+DatJsonProg.raiz[4].nombre_pest+"','CodSel':'"+DatJson.raiz[0].codestpro5+"','DenSel':'"+DatJson.raiz[0].denestpro5+"'}";
			}
				
				AuxCadJson2 = AuxCadJson2+']}'
			*/	
			}
			
		  	//CadJson2 = eval('(' + AuxCadJson2 + ')');
                      //  alert(AuxCadJson);
			CadJson = eval('(' + AuxCadJson + ')');
		  	gridEstPlanSelec.store.loadData(CadJson);
		  //	gridEstPreSelec.store.loadData(CadJson2);
		  	
		    if(DatJsonAd.raiz!=null)
		  	{
		    	gridIntAd.store.loadData(DatJsonAd);
		    }
		    if(DatJsonUb.raiz!=null)
		  	{
		    	gridIntUb.store.loadData(DatJsonUb);
		    }
		   
}
else
{
	Ext.Msg.alert('Mensaje','No existen datos asociados a la estructura seleccionada');	
}
}
});
}


function ActualizarGridsDetalles(Json,GridActual)
{

	var myJSONObject =
	{
		"oper":'buscardetalles',
		"codinte": IdPadre
	};
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request
	(
		{
			url : rutaIntepr,
			params : parametros,
			method: 'POST',
			success: function ( resultado, request ) 
			{ 
				  datos2 = resultado.responseText;
//				  alert(datos2);
		
			}
		}
	)
		
}

Ext.get('BtnNuevo').on('click', function()
{
	window.location.reload();
	EstadoInicial();
})	


Ext.get('grabar').on('click', function()
{
	switch (cantidad)
	{
			case '1':
			valor1=valorActual;
			valor2='0000000000000000000000000';
			valor3='0000000000000000000000000';
			valor4='0000000000000000000000000';
			valor5='0000000000000000000000000';
			break;
			case '2':
			valor2=valorActual;
			valor3='0000000000000000000000000';
			valor4='0000000000000000000000000';
			valor5='0000000000000000000000000';
			break;
			case '3':
			valor3=valorActual;
			valor4='0000000000000000000000000';
			valor5='0000000000000000000000000';
			break;
			case '4':
			valor4=valorActual;
			valor5='0000000000000000000000000';
			break;
			case '5':
			valor5=valorActual;
			break;
	}
		
	switch (cantidadPlan)
	{
		case '1':
			valorPlan1=valorPlanActual;
			valorPlan2='0000000000000000000000000';
			valorPlan3='0000000000000000000000000';
			valorPlan4='0000000000000000000000000';
			valorPlan5='0000000000000000000000000';
		break;
		case '2':
			valorPlan2=valorPlanActual;
			valorPlan3='0000000000000000000000000';
			valorPlan4='0000000000000000000000000';
			valorPlan5='0000000000000000000000000';
		break;
		case '3':
			valorPlan3=valorPlanActual;
			valorPlan4='0000000000000000000000000';
			valorPlan5='0000000000000000000000000';
			break;
			case '4':
			valorPlan4=valorPlanActual;
			valorPlan5='0000000000000000000000000';
			break;
			case '5':
			valorPlan5=valorPlanActual;
			break;
		}
		valorPlan1=ue_rellenarcampo(valorPlan1,25);
		valorPlan2=ue_rellenarcampo(valorPlan2,25);	
		valorPlan3=ue_rellenarcampo(valorPlan3,25);
		valorPlan4=ue_rellenarcampo(valorPlan4,25);	
		valorPlan5=ue_rellenarcampo(valorPlan5,25);		
	//estructura de la integracion


	if(IdPadre=='')
	{
		ArrEst=gridEstPreSelec.store.getModifiedRecords();
		var reg = "{'oper':'incluirInt',ArrEst:[";
		for(i=0;i<ArrEst.length;i++)
		{
			AuxArr=ArrEst[i].get('codigo').split('-');
			if(ArrEst[i].get('codestpro1'))
			{
				valor1=ArrEst[i].get('codestpro1');
			}
			valor1=ue_rellenarcampo(valor1,25);	
			if(ArrEst[i].get('codestpro2'))
			{
				valor2=ArrEst[i].get('codestpro2');
			}
			valor2=ue_rellenarcampo(valor2,25);	
			if(ArrEst[i].get('codestpro3'))
			{
				valor3=ArrEst[i].get('codestpro3');
			}
			valor3=ue_rellenarcampo(valor3,25);	
			if(ArrEst[i].get('codestpro4'))
			{
				valor4=ArrEst[i].get('codestpro4');
			}
			valor4=ue_rellenarcampo(valor4,25);	
			if(ArrEst[i].get('codestpro5'))
			{
				valor5=ArrEst[i].get('codestpro5');
			}
			valor5=ue_rellenarcampo(valor4,25);
		
			if(i==0)
			{
				reg = reg + "{'oper':'incluirInt','codinte':'','CODEST1':'"+valorPlan1+"','CODEST2':'"+valorPlan2+"','CODEST3':'"+valorPlan3+"','CODEST4':'"+valorPlan4+"','CODEST5':'"+valorPlan5+"','CODORGEJ':'0001','estcla_p':'T','estcla':'"+TipoEstructura+"','CODESTPRO1':'"+valor1+"','CODESTPRO2':'"+valor2+"','CODESTPRO3':'"+valor3+"','CODESTPRO4':'"+valor4+"','CODESTPRO5':'"+valor5+"'}";		
			}
			else
			{
				reg = reg + ",{'oper':'incluirInt','codinte':'','CODEST1':'"+valorPlan1+"','CODEST2':'"+valorPlan2+"','CODEST3':'"+valorPlan3+"','CODEST4':'"+valorPlan4+"','CODEST5':'"+valorPlan5+"','CODORGEJ':'0001','estcla_p':'T','estcla':'"+TipoEstructura+"','CODESTPRO1':'"+valor1+"','CODESTPRO2':'"+valor2+"','CODESTPRO3':'"+valor3+"','CODESTPRO4':'"+valor4+"','CODESTPRO5':'"+valor5+"'}";
			}
		}
		 reg =reg+ "]";
		
		arrAd = gridIntAd.store.getModifiedRecords();
		if(arrAd.length>0)
		{	
			reg=reg+ ",DatosAd:[";
			for(i=0;i<arrAd.length;i++)
			{
				if(i==0)
				{	
					//reg= reg +"{'codemp':'0001','coduac':'"+ arrAd[i].get('coduac')+ "','codinte':'"+IdPadre+"','nivel':'"+ arrAd[i].get('nivel') +"'}";				
					reg= reg +"{'coduac':'"+ arrAd[i].get('coduac')+ "','codinte':'"+IdPadre+"','nivel':'"+ arrAd[i].get('nivel') +"'}";				
				}
				else
				{
					//reg= reg +",{'codemp':'0001','coduac':'"+ arrAd[i].get('coduac')+ "','codinte':'"+IdPadre+"','nivel':'"+ arrAd[i].get('nivel') +"'}";
					reg= reg +",{'coduac':'"+ arrAd[i].get('coduac')+ "','codinte':'"+IdPadre+"','nivel':'"+ arrAd[i].get('nivel') +"'}";
				}
			}
			reg = reg + "]";
		}
	
	//arrUb = gridIntUb.store.getRange(0,hasta);
		arrUb = gridIntUb.store.getModifiedRecords();
		if(arrUb.length>0)
		{	
			reg=reg+ ",NivelUb:'"+cantidadUb+"',DatosUb:[";
			for(i=0;i<arrUb.length;i++)
			{
				if(i==0)
				{
					
					//reg= reg +"{'codemp':'0001','codubgeo1':'"+ arrUb[i].get('codubgeo1')+ "','codubgeo2':'"+ arrUb[i].get('codubgeo2')+ "','codinte':'','codubgeo3':'"+ arrUb[i].get('codubgeo3')+ "','codubgeo4':'"+ arrUb[i].get('codubgeo4')+ "','codubgeo5':'"+ arrUb[i].get('codubgeo5')+ "','denominacion':'"+ arrUb[i].get('denominacion')+ "','nivel':'"+ arrUb[i].get('nivel') +"'}";
					reg= reg +"{'codubgeo1':'"+ arrUb[i].get('codubgeo1')+ "','codubgeo2':'"+ arrUb[i].get('codubgeo2')+ "','codinte':'"+IdPadre+"','codubgeo3':'"+ arrUb[i].get('codubgeo3')+ "','codubgeo4':'"+ arrUb[i].get('codubgeo4')+ "','codubgeo5':'"+ arrUb[i].get('codubgeo5')+ "','denominacion':'"+ arrUb[i].get('denominacion')+ "','nivel':'"+ arrUb[i].get('nivel') +"'}";
					
				}
				else
				{
				
					//reg = reg +",{'codemp':'0001','codubgeo1':'"+ arrUb[i].get('codubgeo1')+ "','codubgeo2':'"+ arrUb[i].get('codubgeo2')+ "','codinte':'','codubgeo3':'"+ arrUb[i].get('codubgeo3')+ "','codubgeo4':'"+ arrUb[i].get('codubgeo4')+ "','codubgeo5':'"+ arrUb[i].get('codubgeo5')+ "','denominacion':'"+ arrUb[i].get('denominacion')+ "','nivel':'"+ arrUb[i].get('nivel') +"'}";
					reg = reg +",{'codubgeo1':'"+ arrUb[i].get('codubgeo1')+ "','codubgeo2':'"+ arrUb[i].get('codubgeo2')+ "','codinte':'"+IdPadre+"','codubgeo3':'"+ arrUb[i].get('codubgeo3')+ "','codubgeo4':'"+ arrUb[i].get('codubgeo4')+ "','codubgeo5':'"+ arrUb[i].get('codubgeo5')+ "','denominacion':'"+ arrUb[i].get('denominacion')+ "','nivel':'"+ arrUb[i].get('nivel') +"'}";
	
				}
			 }
			reg = reg + "]";
		}	
		 reg = reg + "}";
		
		
	}
	else
	{
		var reg = "{";
		//reg = reg + "'oper':'actualizarInt','codemp':'0001','codinte':'"+IdPadre+"'";
		reg = reg + "'oper':'actualizarInt','codinte':'"+IdPadre+"'";
	
	arrAd = gridIntAd.store.getModifiedRecords();
	if(arrAd.length>0)
	{	
		reg=reg+ ",DatosAd:[";
		for(i=0;i<arrAd.length;i++)
		{
			if(i==0)
			{	
				//reg= reg +"{'codemp':'0001','coduac':'"+ arrAd[i].get('coduac')+ "','codinte':'"+IdPadre+"','nivel':'"+ arrAd[i].get('nivel') +"'}";				
				reg= reg +"{'coduac':'"+ arrAd[i].get('coduac')+ "','codinte':'"+IdPadre+"','nivel':'"+ arrAd[i].get('nivel') +"'}";				
			}
			else
			{
				//reg= reg +",{'codemp':'0001','coduac':'"+ arrAd[i].get('coduac')+ "','codinte':'"+IdPadre+"','nivel':'"+ arrAd[i].get('nivel') +"'}";
				reg= reg +",{'coduac':'"+ arrAd[i].get('coduac')+ "','codinte':'"+IdPadre+"','nivel':'"+ arrAd[i].get('nivel') +"'}";
			}
		}
		reg = reg + "]";
	}
	
	//arrUb = gridIntUb.store.getRange(0,hasta);
	arrUb = gridIntUb.store.getModifiedRecords();
	if(arrUb.length>0)
	{	
		reg=reg+ ",NivelUb:'"+cantidadUb+"',DatosUb:[";
		for(i=0;i<arrUb.length;i++)
		{
			if(i==0)
			{
				
				//reg= reg +"{'codemp':'0001','codubgeo1':'"+ arrUb[i].get('codubgeo1')+ "','codubgeo2':'"+ arrUb[i].get('codubgeo2')+ "','codinte':'','codubgeo3':'"+ arrUb[i].get('codubgeo3')+ "','codubgeo4':'"+ arrUb[i].get('codubgeo4')+ "','codubgeo5':'"+ arrUb[i].get('codubgeo5')+ "','denominacion':'"+ arrUb[i].get('denominacion')+ "','nivel':'"+ arrUb[i].get('nivel') +"'}";
				reg= reg +"{'codubgeo1':'"+ arrUb[i].get('codubgeo1')+ "','codubgeo2':'"+ arrUb[i].get('codubgeo2')+ "','codinte':'"+IdPadre+"','codubgeo3':'"+ arrUb[i].get('codubgeo3')+ "','codubgeo4':'"+ arrUb[i].get('codubgeo4')+ "','codubgeo5':'"+ arrUb[i].get('codubgeo5')+ "','denominacion':'"+ arrUb[i].get('denominacion')+ "','nivel':'"+ arrUb[i].get('nivel') +"'}";
				
			}
			else
			{
			
				//reg = reg +",{'codemp':'0001','codubgeo1':'"+ arrUb[i].get('codubgeo1')+ "','codubgeo2':'"+ arrUb[i].get('codubgeo2')+ "','codinte':'','codubgeo3':'"+ arrUb[i].get('codubgeo3')+ "','codubgeo4':'"+ arrUb[i].get('codubgeo4')+ "','codubgeo5':'"+ arrUb[i].get('codubgeo5')+ "','denominacion':'"+ arrUb[i].get('denominacion')+ "','nivel':'"+ arrUb[i].get('nivel') +"'}";
				reg = reg +",{'codubgeo1':'"+ arrUb[i].get('codubgeo1')+ "','codubgeo2':'"+ arrUb[i].get('codubgeo2')+ "','codinte':'"+IdPadre+"','codubgeo3':'"+ arrUb[i].get('codubgeo3')+ "','codubgeo4':'"+ arrUb[i].get('codubgeo4')+ "','codubgeo5':'"+ arrUb[i].get('codubgeo5')+ "','denominacion':'"+ arrUb[i].get('denominacion')+ "','nivel':'"+ arrUb[i].get('nivel') +"'}";

			}
		 }
		reg = reg + "]";
		}	
	 reg = reg + "}";
	}
	
	//reg = reg + "}";
	//alert(reg);
	Obj= eval('(' + reg + ')');
	ObjSon=JSON.stringify(Obj);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : rutaIntepr,
	params : parametros,
	method: 'POST',
	success: function ( resultad, request ){ 
        datos = resultad.responseText;
		// alert(datos);
		//Ext.get('norte').dom.innerHTML=datos;
		 
				if (datos!= '0')
				{
					 if(Obj.oper=='incluirInt')
					 {
					 //	alert(datos);
						Ext.MessageBox.alert('Mensaje','La integraci&#243;n fue incluida con �xito')
					//	EstadoInicial();	
						var DatJsonProg = eval('(' + datos + ')');
						//alert(DatJsonProg);
						gridEstPreSelec.store.loadData(DatJsonProg);
						//location.href='sigesp_spe_inteprog.php';							
					 }
					 else if(Obj.oper=='actualizarInt')
					 {
					 	Ext.MessageBox.alert('Mensaje','La Estructura Presupuestaria fue procesada con �xito');
					 	EstadoInicial2();
					 }
				 }		 
				 else 
				 {
				  	Ext.MessageBox.alert('Error', 'La integraci&#243;n presupuestaria seleccionada ya existe, la combinaci&#243;n de la estructura del plan y la estructura presupuestaria seleccionada ya fue registrada, verifique mediante el cat�logo');
				  	EstadoInicial();
				 }
      },
	failure: function ( result, request)
	 { 
		Ext.MessageBox.alert('Error', result.responseText); 
	 } 
      }); 
});

Ext.get('BtnSalir').on('click',function()
{
	location.href='sigesp_windowblank2.php';
})


//Ext.get('SumEstPla').on('click', function()
function irAgregarEstPlan()
{
	Busqueda=false;
	if(CatPlanUnavez==false)
	{
		getDatosPlan('getSesion');
		CatPlanUnavez=true;
	}
	else
	{
		EstadoInicialPlan();
	}
	MostrarCatest();

}
//);

//Ext.get('SumEstPre').on('click', function()
function irAgregarEstPre()
{
	Busqueda=false;
	if(DataStorePlan.getCount()>0)
	{
		if(CatPreUnavez==false)
		{
			getDatos('getSesion');
			CatPreUnavez=true;
		
		}
		else
		{
			EstadoInicialPre();
		}
		
		
		 MostrarCatestpre();	
		//}
    }
	else
	{
		Ext.Msg.alert('Mensaje','Debe indicar la Estructura del Plan, verifique por favor');
	}

}
//);


Ext.get('BtnCat').on('click', function()
{
	if(CatPreUnavez==false)
	{
		getDatos('getSesion');
		CatPreUnavez=true;
	}
	else
	{
		EstadoInicialPre();
	}
		MostrarCatestpre();	
		Busqueda=true;
});


function EstadoInicial()
{
	/*DatosNuevo={"raiz":[{"NombreNivel":'',"CodSel":'',"DenSel":''}]};
	DataStorePlan.loadData(DatosNuevo);*/
	DataStorePre.removeAll();
	DatosNuevoAd={"raiz":[{"NombreNivel":'',"CodSel":'',"DenSel":''}]};
	gridIntAd.store.loadData(DatosNuevoAd);
	DatosNuevoUb={"raiz":[{"coduac":'',"denuac":'',"codinte":''}]};
	gridIntUb.store.loadData(DatosNuevoUb);
	//DatosNuevoProb={"raiz":[{"codprob":'',"denominacion":'',"causa":''}]};	

}


function EstadoInicial2()
{
	DatosNuevoAd={"raiz":[{"NombreNivel":'',"CodSel":'',"DenSel":''}]};
	gridIntAd.store.loadData(DatosNuevoAd);
	DatosNuevoUb={"raiz":[{"coduac":'',"denuac":'',"codinte":''}]};
	gridIntUb.store.loadData(DatosNuevoUb);
}

function ObtenergridInt(tab)
{
	//alert(tab);
	switch(tab)
	{
		case '0':
			return gridIntFuente;
			break;
		case '1':
			return gridIntAd;
			break;
		case '2':
			return gridAd3;
			break;
		case '3':
			return gridAd4;
			break;
		case '4':
			return gridAd5;
			break;
	}    
	
}


function PasarDatosGrids(NombreNivel,codigo,Deno,ParamGrid)
{
	r=new RecordDefPlaPre
	(
		{
			'NombreNivel':NombreNivel,
			'CodSel':codigo,
			'DenSel': Deno 	
		}
	);
	
	CantidadDatos = ParamGrid.store.getCount()-1;
	CanDatos = DsBusqueda.getCount()-1;
	if(Busqueda==false)
	{
		//alert(CantidadDatos);
		ParamGrid.store.insert(CantidadDatos,r);	
	}
	else
	{
		if(CanDatos<0)
		{
			CanDatos=0;
			DsBusqueda.insert(CanDatos,r);
		}
		//alert('llenando los datos para la busqueda: '+codigo);
		DsBusqueda.insert(CanDatos,r);
	}	
}
//Ext.get('LlamarCatalogosTabs1').on('click', function()
function irAgregarEjeFis()
{
	tabActual = tabsProb.getActiveTab().id;
	switch(tabActual)
	{
		case 'Itab1':
		if(CatAdUnavez==false)
		{
			getDatosAd('getSesion');
			CatAdUnavez=true;
		}
		else
		{
			EstadoInicialAd();
		}
		MostrarCatestAd();
		break;	
		case 'Itab2':
		if(CatUbUnavez==false)
		{
			getDatosUb('getSesion');
			CatUbUnavez=true;
		}
		else
		{
			EstadoInicialUb();
		}
		MostrarCatUb();
		break;	
		case 'Itab3':
			ObjProb = new CatProb();
			ParamGridTarget = gridIntProb;
			ObjProb.MostrarCatalogo();
			break;
		case 'Itab4':
		//alert('si metas');
			ObjMeta = new CatMetas();
			ParamGridTarget = gridIntVar;
			ObjMeta.MostrarCatalogo();
		break;	
	}
	
}
//);

function irQuitarEjeFis()
{
	tabActual = tabsProb.getActiveTab().id;
	switch(tabActual)
	{
		case 'Itab1':
			var meta = gridIntAd.selModel.selections.keys;
			if(meta.length > 0)
			{
				Ext.Msg.confirm('Alerta!','Realmente desea eliminar el registro?', borrarUniads);
			} 
			else 
			{
				Ext.Msg.alert('Alerta!','Seleccione un registro para eliminar');
			}			
		break;	
		case 'Itab2':
			var Ubs = gridIntUb.selModel.selections.keys;
			if(Ubs.length>0)
			{
				Ext.Msg.confirm('Alerta!','Realmente desea eliminar el registro?', borrarUbs);
			} 
			else 
			{
				Ext.Msg.alert('Alerta!','Seleccione un registro para eliminar');
			}
		break;
		case 'Itab3':
			var problema = gridIntProb.selModel.selections.keys;
			if(problema.length > 0)
			{
				Ext.Msg.confirm('Alerta!','Realmente desea eliminar el registro?', borrarProblema);
			} 
			else 
			{
				Ext.Msg.alert('Alerta!','Seleccione un registro para eliminar');
			}
		break;
		case 'Itab4':
			var meta = gridIntVar.selModel.selections.keys;
			if(meta.length > 0)
			{
				Ext.Msg.confirm('Alerta!','Realmente desea eliminar el registro?', borrarMeta);
			} 
			else 
			{
				Ext.Msg.alert('Alerta!','Seleccione un registro para eliminar');
			}
		break;

	}
	
}
function borrarMeta(btn) 
{
//alert('eliminar metas');
	if (btn=='yes') 
	{
		var fila = gridIntVar.getSelectionModel().getSelected();
		if (fila)
		{		
				codinte = IdPadre;
				codmeta = gridIntVar.getSelectionModel().getSelected().get('cod_var');
				ano_presupuesto = gridIntVar.getSelectionModel().getSelected().get('ano_presupuesto');
				//reg = "{'oper':'eliminarMetas',DatosMetas:[{'cod_var':'"+codmeta+"','codemp':'0001','codinte':'"+codinte+"'}]}";
				reg = "{'oper':'eliminarMetas',DatosMetas:[{'cod_var':'"+codmeta+"','codinte':'"+codinte+"'}]}";
 				//alert(reg);
 				Obj= eval('(' + reg + ')');
				ObjSon=JSON.stringify(Obj);
				parametros = 'ObjSon='+ObjSon; 
				Ext.Ajax.request({
				url : rutaIntepr,
				params : parametros,
				method: 'POST',
				success: function(resultad,request ){ 
				datos = resultad.responseText;
				//alert(datos);
				var Registros = datos.split("|");
				Cod = Registros[1];
					if(Cod=='1')
					{
						Ext.MessageBox.alert('Mensaje','Registro eliminado con �xito');
						gridIntVar.store.remove(fila);
						gridIntVar.store.commitChanges();
					}
					else
					{
						Ext.MessageBox.alert('Error', 'El registro');				
					}
				      },
						failure: function ( result, request)
					 { 
						Ext.MessageBox.alert('Error', result.responseText); 
					 } 
				    });
			gridIntProb.store.remove(fila);
			Ext.Msg.alert('Exito','Registro eliminado');				
			
			
			gridIntVar.store.remove(fila);
			Ext.Msg.alert('Exito','Registro eliminado');				
		}
	} 
}
	
	
function borrarUniads(btn)
{
	if (btn=='yes') 
	{
		var selectedRow = gridIntAd.getSelectionModel().getSelected();
		if(selectedRow)
		{
					Coduac = gridIntAd.getSelectionModel().getSelected().get('coduac');
					Nivel = gridIntAd.getSelectionModel().getSelected().get('nivel');
					//reg = "{'oper':'eliminarUnis',DatosAd:[{'coduac':'"+Coduac+"','nivel':'" + Nivel +"','codinte':'"+IdPadre+"','codemp':'0001'}]}";
					reg = "{'oper':'eliminarUnis',DatosAd:[{'coduac':'"+Coduac+"','nivel':'" + Nivel +"','codinte':'"+IdPadre+"'}]}";
 					Obj= eval('(' + reg + ')');
					ObjSon=JSON.stringify(Obj);
					parametros = 'ObjSon='+ObjSon; 
					Ext.Ajax.request({
					url : rutaIntepr,
					params : parametros,
					method: 'POST',
					success: function ( resultad, request ){ 
				    datos = resultad.responseText; 
					var Registros = datos.split("|");
					Cod = Registros[1];
						if(Cod=='1')
						{
							Ext.MessageBox.alert('Mensaje', 'Registro eliminado con �xito');
							gridIntAd.store.remove(selectedRow);
							gridIntAd.store.commitChanges();
							//alert(grid2.store.getCount());
							//ActualizarData();
						}
						else
						{
							Ext.MessageBox.alert('Error', 'El registro');				
						}
				      },
					failure: function ( result, request)
					 { 
						Ext.MessageBox.alert('Error', result.responseText); 
					 } 
				    });
			
	     		DataStore.remove(selectedRow);
		}
	  } 

}	

function borrarUbs(btn)
{
	if (btn=='yes') 
	{
		var selectedRow = gridIntUb.getSelectionModel().getSelected();
		if(selectedRow)
		{
					codubgeo1 = gridIntUb.getSelectionModel().getSelected().get('codubgeo1');
					codubgeo2 = gridIntUb.getSelectionModel().getSelected().get('codubgeo2');
					codubgeo3 = gridIntUb.getSelectionModel().getSelected().get('codubgeo3');
					codubgeo4 = gridIntUb.getSelectionModel().getSelected().get('codubgeo4'); 
					Nivel = gridIntUb.getSelectionModel().getSelected().get('nivel');
					//reg = "{'oper':'eliminarUbs','NivelUb':'"+nivelUbicaciones+"',DatosUb:[{'codubgeo1':'"+codubgeo1+"','codubgeo2':'" + codubgeo2 +"','codinte':'"+IdPadre+"','codemp':'0001','codubgeo3':'"+codubgeo3+"','codubgeo4':'" +codubgeo4+"'}]}";
					reg = "{'oper':'eliminarUbs','NivelUb':'"+nivelUbicaciones+"',DatosUb:[{'codubgeo1':'"+codubgeo1+"','codubgeo2':'" + codubgeo2 +"','codinte':'"+IdPadre+"','codubgeo3':'"+codubgeo3+"','codubgeo4':'" +codubgeo4+"'}]}";
 					//alert(reg);
 					Obj= eval('(' + reg + ')');
					ObjSon=JSON.stringify(Obj);
					parametros = 'ObjSon='+ObjSon; 
					Ext.Ajax.request({
					url : rutaIntepr,
					params : parametros,
					method: 'POST',
					success: function( resultad, request ){ 
				    datos = resultad.responseText;
					var Registros = datos.split("|");
					Cod = Registros[1];
						if(Cod=='1')
						{
							Ext.MessageBox.alert('Mensaje', 'Registro eliminado con �xito');
							gridIntUb.store.remove(selectedRow);
							gridIntUb.store.commitChanges();
							//alert(grid2.store.getCount());
							//ActualizarData();
						}
						else
						{
							Ext.MessageBox.alert('Error', 'El registro');				
						}
				      },
					failure: function ( result, request)
					 { 
						Ext.MessageBox.alert('Error', result.responseText); 
					 } 
				    });
			
	     		DataStore.remove(selectedRow);
		}
	  } 
}	

function irQuitarEjeFin()
{
	var meta = gridIntGastos.selModel.selections.keys;
	if(meta.length > 0)
	{
		Ext.Msg.confirm('Alerta!','Realmente desea eliminar el registro?', borrarCuentas);
	} 
	else 
	{
		Ext.Msg.alert('Alerta!','Seleccione un registro para eliminar');
	}			
}

function borrarCuentas(btn)
{
	if (btn=='yes') 
	{
		var fila = gridIntGastos.getSelectionModel().getSelected();
		if (fila)
		{	
			codinte = IdPadre;
			codcuenta = gridIntGastos.getSelectionModel().getSelected().get('spg_cuenta');
			ano_presupuesto = gridIntGastos.getSelectionModel().getSelected().get('ano_presupuesto');
			//reg = "{'oper':'eliminarCuentas',DatosGas:[{'sig_cuenta':'"+codcuenta+"','codemp':'0001','codinte':'"+codinte+"','ano_presupuesto':'"+ano_presupuesto+"'}]}";
			reg = "{'oper':'eliminarCuentas',DatosGas:[{'sig_cuenta':'"+codcuenta+"','codinte':'"+codinte+"','ano_presupuesto':'"+ano_presupuesto+"'}]}";
 			Obj= eval('(' + reg + ')');
			ObjSon=JSON.stringify(Obj);
			parametros = 'ObjSon='+ObjSon; 
			Ext.Ajax.request({
			url : rutaIntepr,
			params : parametros,
			method: 'POST',
			success: function(resultad,request ){ 
			datos = resultad.responseText;
			//Ext.get('norte').dom.innerHTML=datos;
			//alert(datos);
			var Registros = datos.split("|");
			Cod = Registros[1];
			if(Cod=='1')
			{
				Ext.MessageBox.alert('Mensaje','Registro eliminado con �xito');
				gridIntGastos.store.remove(fila);
				gridIntGastos.store.commitChanges();
			}
			else
			{
				Ext.MessageBox.alert('Error', 'El registro');				
			}
		  },
				failure: function ( result, request)
				{ 
					Ext.MessageBox.alert('Error', result.responseText); 
				} 
		});
		gridIntProb.store.remove(fila);
		Ext.Msg.alert('Exito','Registro eliminado');				
		}
	} 		
}
		
function borrarProblema(btn) 
{
	if (btn=='yes') 
	{
		var fila = gridIntProb.getSelectionModel().getSelected();
		if (fila)
		{			
				codinte = IdPadre;
				codproblema = gridIntProb.getSelectionModel().getSelected().get('codprob');
				//reg = "{'oper':'eliminarProbs',DatosPro:[{'codprob':'"+codproblema+"','codemp':'0001','codinte':'"+codinte+"'}]}";
				reg = "{'oper':'eliminarProbs',DatosPro:[{'codprob':'"+codproblema+"','codinte':'"+codinte+"'}]}";
 				Obj= eval('(' + reg + ')');
				ObjSon=JSON.stringify(Obj);
				parametros = 'ObjSon='+ObjSon; 
				Ext.Ajax.request({
				url : rutaIntepr,
				params : parametros,
				method: 'POST',
				success: function(resultad,request ){ 
				datos = resultad.responseText;
				var Registros = datos.split("|");
				Cod = Registros[1];
					if(Cod=='1')
					{
						Ext.MessageBox.alert('Mensaje','Registro eliminado con �xito');
						gridIntProb.store.remove(fila);
						gridIntProb.store.commitChanges();
					}
					else
					{
						Ext.MessageBox.alert('Error', 'El registro');				
					}
				      },
						failure: function ( result, request)
					 { 
						Ext.MessageBox.alert('Error', result.responseText); 
					 } 
				    });
			gridIntProb.store.remove(fila);
			Ext.Msg.alert('Exito','Registro eliminado');				
		}
	} 
}	

//Ext.get('LlamarCatalogosTabs2').on('click', function()
function irAgregarEjeFin()
{
	tabActual = tabs2.getActiveTab().id;
	switch(tabActual)
	{
		case 'Itab6':
			CatPlanCuentas();
			break;
			
	}
	
}
});