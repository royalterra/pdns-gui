var Settings = new Ext.tree.TreePanel({
  title: 'Settings',
  iconCls: 'icon-cog',
  loader: new Ext.tree.TreeLoader(),
  rootVisible: false,
  lines: false,
  root: new Ext.tree.AsyncTreeNode({
    expanded: true,
    children: [
      {
        id: 'templates', 
        text: 'Templates', 
        icon: '/images/vcard.gif',
        leaf: true
      }
    ]
  }),
  listeners: {
    click: function(node){
      
      switch(node.id)
      {
        case 'templates':

          break;
      }
    }
  }
});
