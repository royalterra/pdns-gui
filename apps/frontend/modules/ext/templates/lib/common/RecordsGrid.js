/* Records */
Ext.ux.RecordsGrid = function(cfg){

  if (!cfg) var cfg = {};
  
  if (!cfg.records)
  {
    cfg.records = [
      { 
        name: '%DOMAIN%', 
        type: 'SOA',
        content: 'master.dns hostmaster.%DOMAIN% %SERIAL%',
        ttl: 86400
      },{
        name: '%DOMAIN%', 
        type: 'NS',
        content: 'master.dns',
        ttl: 86400
      },{
        name: '%DOMAIN%', 
        type: 'MX',
        content: 'mail.server',
        ttl: 86400,
        prio: 0
      }
    ];
  }
  
  console.log(cfg.records);

  var defaultCfg = {
    store: new Ext.data.JsonStore({
      fields : [ 'id','name','type','content','ttl','prio' ],
      root: 'records',
      data: cfg
    }),
    height: 260,
    enableHdMenu: false,
    enableColumnResize: false,
    enableColumnMove: false,
    clicksToEdit: 1,
    columns: [
      {
        header: 'Name',
        dataIndex: 'name',
        editor: new Ext.form.TextField({
          allowBlank: false
        })
      },{
        header: 'Type',
        dataIndex: 'type',
        width: 50,
        fixed: true,
        editor: new Ext.form.ComboBox({
          store: [
            ["SOA","SOA"],
            ["NS","NS"],
            ["MX","MX"],
            ["A","A"],
            ["CNAME","CNAME"]
          ],
          displayField: 'field2',
          valueField: 'field1',
          width: 120,
          name: 'type',
          hiddenName: 'type',
          mode: 'local',
          triggerAction: 'all',
          forceSelection: true,
          editable: false
        })
      },{
        header: 'Content',
        dataIndex: 'content',
        editor: new Ext.form.TextField({
          allowBlank: false
        })
      },{
        header: 'TTL',
        dataIndex: 'ttl',
        width: 50,
        fixed: true,
        editor: new Ext.form.TextField({
          allowBlank: false,
          maskRe: /^[0-9]$/
        })
      },{
        header: 'Prio',
        dataIndex: 'prio',
        width: 40,
        fixed: true,
        editor: new Ext.form.TextField({
          maskRe: /^[0-9]$/
        })
      },{
        id: 'delete',
        header: '',
        dataIndex: 'id',
        width: 24,
        fixed: true,
        renderer: function(v){
          return '<img src="/images/bin.gif" alt="Delete" />';
        }
      }
    ],
    listeners: {
      cellclick: function(grid, rowIndex, columnIndex, e){
        
        var columnId = grid.getColumnModel().getColumnId(columnIndex);
        
        /* Delete clicked */
        if (columnId == 'delete')
        {
          <?php echo ext_log('delete clicked') ?>
          var record = grid.getStore().getAt(rowIndex);
          grid.store.remove(record);
        }
      }
    },
    viewConfig:{
      forceFit: true,
      deferEmptyText: false,
      emptyText: 'No records to display'
    },
    bbar: [
      {
        xtype: 'button',
        text: 'Add record',
        icon: '/images/add.gif',
        handler: function(){
          grid.store.add(new grid.store.recordType({
            name: cfg.defaultName,
            type: 'A',
            ttl: 86400
          }));
          
          grid.getView().focusRow(grid.store.getCount() - 1);
        }
      }
    ]
  };
  
  Ext.applyIf(cfg, defaultCfg);
  
  var grid = new Ext.grid.EditorGridPanel(cfg);
  
  return grid;
}
