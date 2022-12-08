var dsEmp="";
var gridEmp="";
var formBusEmp="";
var RecordDefEmp="";
function crearDataStoreEmp()
{
		 ruta ='../../procesos/sigesp_sfp_indicadorpr.php';
		 RecordDefEmp = Ext.data.Record.create([
				{name: 'cod_ind'},    
				{name: 'denominacion'},
				{name: 'formula'},
				{name: 'tipo'}	
		]);
			var myObject={"raiz":[{"cod_ind":'',"denominacion":''}]};
			 dsEmp =  new Ext.data.Store({
				 proxy: new Ext.data.MemoryProxy(myObject),
				 reader: new Ext.data.JsonReader({
				 root: 'raiz',             
				 id: "id"   
				}
				,
		        	RecordDefEmp   
				),
				data: myObject
	  		})	
		

		var myJSONObject ={
		"oper": 'catalogo'
		}
		ObjSon=JSON.stringify(myJSONObject);
		parametros = 'ObjSon='+ObjSon; 
		Ext.Ajax.request({
		url : ruta,
		params : parametros,
		method: 'POST',
		success: function ( resultado, request) 
		{ 
			datos = resultado.responseText;
			var myObject = eval('(' + datos + ')');
			if(myObject!='')
			{
				dsEmp.loadData(myObject);
			}
		}	
	})
}

function actDataStoreEmp(criterio,cadena)
{
	dsEmp.filter(criterio,cadena);
}


function crearFormBusqueda()
{
		formBusEmp = new Ext.FormPanel({
        labelWidth: 75, // label settings here cascade unless overridden
        url:'save-form.php',
        frame:true,
        title: 'B�squeda',
        bodyStyle:'padding:5px 5px 0',
        width: 350,
		height:120,
        defaults: {width: 230},
        defaultType: 'textfield',
		items: [{
                fieldLabel: 'C�digo',
                name: 'cod',
				id:'cod',
				changeCheck: function(){
							var v = this.getValue();
							actDataStoreEmp('cod_ind',v);
							if(String(v) !== String(this.startValue))
							{
								this.fireEvent('change', this, v, this.startValue);
							} 
							},							 
							initEvents : function()
							{
								AgregarKeyPress(this);
							}               
            },{
                fieldLabel: 'Denominacion',
                name: 'den',
			changeCheck: function()
			{
							var v = this.getValue();
							actDataStoreEmp('denominacion',v);
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

}

function CrearGrid()
{
	 	crearFormBusqueda();
		crearDataStoreEmp();
		 
	 gridEmp = new Ext.grid.GridPanel({
	 width:770,
	 height:400,
	 tbar:formBusEmp,
	 autoScroll:true,
     border:true,
     ds:dsEmp,
     cm: new Ext.grid.ColumnModel([
          {header: "C�digo", width: 30, sortable: true,   dataIndex: 'cod_ind'},
          {header: "Nombre", width: 50, sortable: true, dataIndex: 'denominacion'}
       ]),
       stripeRows: true,
      viewConfig: {
      	forceFit:true
      }
      ,
      });            
} 


function PasDatosGridGrid(Registro,objeto)
{
	var p = new RecordDefEmp
	(
	    {
			cod_ind:Registro.get('cod_ind'),
		    denominacion:Registro.get('denominacion')
		}
    );
	objeto.store.insert(0,p);	
}

function MostrarCatEmp(fuente,objeto)
{
				   CrearGrid();
				   ObjetoFuente=fuente;
                   winCatEmp = new Ext.Window(
                   {
                    //layout:'fit',
                    title: 'Cat&aacute;logo de Indicadores',
		    		autoScroll:true,
                    width:800,
                    height:400,
                    modal: true,
                    closable:false,
                    plain: false,
                    items:[gridEmp],
                    buttons: [{
                    text:'Aceptar',  
                    handler: function()
                    { 
                    	Registro = gridEmp.getSelectionModel().getSelected();
                    	switch(ObjetoFuente)   
                    	{
                    		case 'definicion':
                    			limpiarCampos();
	                    		PasDatosGridDef(Registro);
	                    	break;
                    		case 'grid':
		                    	PasDatosGridGrid(Registro,objeto);
	                    	break;
                    		case 'objeto':
	                    		PasDatosGridObj(Registro);
	                    	break;
 
                    		
                    	}          
                    	gridEmp.destroy();
		      			winCatEmp.destroy();                      
                    }
                    }
                    ,
                    {
                     text: 'Salir',
                     handler: function()
                     {
                     	
                      	gridEmp.destroy();
		      			winCatEmp.destroy();
                     }
                    }]
                    
                   });
                  winCatEmp.show();       
 }