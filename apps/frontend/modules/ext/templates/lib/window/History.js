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
              return '<img src="/images/add.gif" ext:qtip="Added" />'
              break;
            case 'UPDATE':
              return '<img src="/images/cog.gif" ext:qtip="Updated" />'
              break;
            case 'DELETE':
              return '<img src="/images/bin.gif" ext:qtip="Deleted" />'
              break;
            default:
              return '?';
          }
        }
			},{
				header: 'Domain',
        menuDisabled: true,
        width: 120,
        fixed: true,
				dataIndex: 'domain_id',
				renderer: function(v,meta,r){
          return DomainStore.getById(v).data.name;
				}
			},{
				header: 'Data',
        menuDisabled: true,
				dataIndex: 'changes',
				renderer: function(v,meta,r){
          if (r.data.type == 'DELETE')
          {
						r = r.data;
						var key = grid.store.find('add_key','ADD-' + r.object + '-' + r.object_key);
						
						if (key == -1)
						{
							return 'Not found';
						}
						else
						{
							var parent = grid.store.getAt(key).data;
							
							return parent.changes.Name + ' <b>' + parent.changes.Type + '</b>';
						}
          }
          else
          {
            if (!v.Name || !v.Type)
            {
              r = r.data;
              var key = grid.store.find('add_key','ADD-' + r.object + '-' + r.object_key);
              
              if (key == -1)
              {
                return 'Not found';
              }
              else
              {
                var parent = grid.store.getAt(key).data;
                
                return parent.changes.Name + ' <b>' + parent.changes.Type + '</b> ' + parent.changes.Ttl;
              }
            }
            else
            {
              return v.Name + ' <b>' + v.Type + '</b> ' + v.Content + ' ' + v.Ttl;
            }
          }
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
