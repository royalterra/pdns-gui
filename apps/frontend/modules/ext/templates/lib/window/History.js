function HistoryWindow()
{
  var win_id = get_win_id();
  if (!win_id) return;
  
  var grid = new Ext.grid.GridPanel({
		height: 400,
		border: false,
    loadMask: true,
		store: new Ext.data.JsonStore({
  		url: '<?php echo url_for('ext/audit') ?>',
			autoLoad: true
		}),
		columns: [
			{
				header: 'Timestamp',
				dataIndex: 'created_at',
        menuDisabled: true,
				width: 110,
				fixed: true
			},{
				header: '',
				dataIndex: 'type',
				width: 30,
				fixed:true,
        renderer: function(v){
          switch (v){
            case 'ADD':
              return '<div style="width: 16px; height: 16px;" class="icon-add" ext:qtip="Added" />'
              break;
            case 'UPDATE':
              return '<div style="width: 16px; height: 16px;" class="icon-cog" ext:qtip="Updated" />'
              break;
            case 'DELETE':
              return '<div style="width: 16px; height: 16px;" class="icon-bin" ext:qtip="Deleted" />'
              break;
            default:
              return '?';
          }
        }
			},{
				header: 'Name',
        menuDisabled: true,
        width: 120,
        fixed: true,
				dataIndex: 'changes',
				renderer: function(v){
          return v.Name;
				}
			},{
				header: 'Type',
        menuDisabled: true,
        width: 50,
        fixed: true,
				dataIndex: 'changes',
				renderer: function(v){
          return v.Type;
				}
			},{
				header: 'Content',
        menuDisabled: true,
				dataIndex: 'changes',
				renderer: function(v){
          return v.Content;
				}
			},{
				header: 'TTL',
        width: 40,
        fixed: true,
        menuDisabled: true,
				dataIndex: 'changes',
				renderer: function(v){
          return v.Ttl;
				}
			},{
				header: 'Prio',
        width: 30,
        fixed: true,
        menuDisabled: true,
				dataIndex: 'changes',
				renderer: function(v){
          return v.Prio;
				}
			}
		],
    viewConfig: {
      forceFit: true
		}
  });
	
  var win = new Ext.ux.Window({
    id: win_id,
    title: 'History',
    width: 650,
    items: grid,
    resizable: true,
    buttons: [
            new Ext.app.SearchField({
          store: grid.store,
          width: 140
      }),{
        style: 'margin-left: 140px;',
        text: 'Close',
        handler: function() { win.close() }
      }
      

    ]
  });
  
  win.show();
  /*
  win.addListener('resize',function(win,width,height){
    form.items.items[1].items.items[0].setHeight(height-72);
  });
  */
  
}
