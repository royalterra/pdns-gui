var settingsMenu = new Ext.menu.Menu({
  items: [
    {
      text: 'Templates',
      handler: function(){ TemplateWindow() }
    }
  ]
});

var Domains = new Ext.grid.GridPanel({
  title: 'Domains',
  iconCls: 'icon-world',
  store: DomainStore,
  hideHeaders: true,
  disableSelection: true,
  columns: [
    {
      dataIndex: 'name',
      width: 180
    }
  ],
  viewConfig:{
    forceFit: true,
    scrollOffset: 1,
    emptyText: 'No domains to display.'
  },
  listeners: {
    cellclick: function(grid, rowIndex, columnIndex, e){
      
      var columnId = grid.getColumnModel().getColumnId(columnIndex);
      
      var record = grid.getStore().getAt(rowIndex).data;
    }
  },
  tbar: [
    {
      text: 'Settings',
      icon: '/images/cog.gif',
      menu: settingsMenu
    },{
      xtype: 'tbfill'
    },{
      text: 'Add domain',
      icon: '/images/add.gif',
      handler: function(){
        DomainWindow();
      }
    }
  ]
});
