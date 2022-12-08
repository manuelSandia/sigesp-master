/**
 * @author gerco
 */
 
Ext.onReady(function(){
	Ext.BLANK_IMAGE_URL = '../../base/librerias/js/ext/resources/images/default/s.gif';
		
	//definicion de opciones del menu procesos
	var menu_procesos = new Ext.menu.Menu(
	{
		id: 'mainMenu'
	});
	
	var registro = new  Ext.menu.Item({
		text: 'Registrar Tramite',
		id:'registro',
       	iconCls: 'menuicono',
       	href:'sigesp_vis_sst_registrotramite.php',
       
	});
	
	var asignacion = new  Ext.menu.Item(
	{
		text: 'Asignar Tramite',
		id:'asignacion',
	   	iconCls: 'menuicono',
	   	href: 'sigesp_vis_sst_asignaciontramite.php'
	});
	
	var recepcion = new  Ext.menu.Item(
	{
		text: 'Registrar Recepci&#243;n',
		id:'recepcion',
	   	iconCls: 'menuicono',
	   	href: 'sigesp_vis_sst_recibirtramite.php'
	});
		
	var cierre = new  Ext.menu.Item(
	{
		text: 'Cerrar Tramite',
		id:'cerrar',
       	iconCls: 'menuicono',
       	href: 'sigesp_vis_sst_cerrartramite.php'
	});
	 
	var reverso = new  Ext.menu.Item(
	{
		text: 'Reversar Operaci&#243;n',
		id:'reversar',
       	iconCls: 'menuicono',
       	href: 'sigesp_vis_sst_reversaroperacion.php'
	});
	
	
	//Opciones del menu procesos
	menu_procesos.addItem(registro);
	menu_procesos.addItem(recepcion); 
	menu_procesos.addItem(asignacion);
	menu_procesos.addItem(cierre);
	menu_procesos.addItem(reverso);
	
	
	
	// Menu reportes
	var menu_reportes = new Ext.menu.Menu(
	{
		id: 'menu_modulos'
	});	
	
	var consulta = new  Ext.menu.Item(
	{
		text: 'Consultar Estado Tramite',
		id:'consulta',
       	iconCls: 'menuicono',
       	href:'sigesp_vis_sst_consultaestadotramite.php'
	});
	
	//Opciones del menu procesos
	menu_reportes.addItem(consulta);
	
	// Menu para devolverse al index Principal
	var menu_principal = new Ext.menu.Menu(
	{
		id: 'menu_principal'
	});
	
	var menu_index_modulos = new  Ext.menu.Item(
	{
		text: 'Volver',
	   	iconCls: 'menuicono',
		href:'../../index_modules.php'
	});
	
	menu_principal.addItem(menu_index_modulos);
	
	
	// Tool Bar que va a obtener las Opciones de Menu
	var Xpos = ((screen.width/2)-(930/2));
	var barramenu = new Ext.Toolbar({
		width:925,
		style:'position:absolute;margin-left:'+Xpos+'px;margin-top:0px'
		
	});
	barramenu.render('menu_principal');
    barramenu.add(
		{
            text:'Procesos',
            iconCls: 'menuitem',  // <-- icon
            menu: menu_procesos// assign menu by instance
        },'-',
        {
            text:'Reportes',
            iconCls: 'menuitem',  // <-- icon
            menu: menu_reportes  // assign menu by instance
        },'-',
        {
			text:'Menu Principal',
			iconCls: 'menuitem',  // <-- icon
			menu: menu_principal
		}
	);
});