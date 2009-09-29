function TemplateWindow(template)
{
  var win_id = get_win_id(template);
  if (!win_id) return;
  
  if (template)
  {
    var title = 'Edit tempalte';
    var url = '<?php echo url_for('template/edit') ?>';
  }
  else
  {
    var title = 'Add tempalte';
    var url = '<?php echo url_for('template/add') ?>';
  }
  
  var grid = new Ext.grid.EditorGridPanel({
    store: new Ext.data.JsonStore({
      id: 'id',
      fields : [ 'id','name','type','content','ttl','prio' ],
      root: 'records'
    }),
    height: 200,
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
      }
    ],
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
            id: grid.store.getCount(),
            name: '%DOMAIN%',
            type: 'A',
            ttl: 86400
          }));
        }
      }
    ]
  });
  
  grid.store.loadData({ records: [
    { 
      id: 0, 
      name: '%DOMAIN%', 
      type: 'SOA',
      content: 'master.dns hostmaster.%DOMAIN% %SERIAL%',
      ttl: 86400
    },{
      id: 1, 
      name: '%DOMAIN%', 
      type: 'NS',
      content: 'master.dns',
      ttl: 86400
    },{
      id: 2, 
      name: '%DOMAIN%', 
      type: 'MX',
      content: 'mail.server',
      ttl: 86400,
      prio: 0
    }
  ]});
  
  var form = new Ext.form.FormPanel({
    url: url,
    border: false,
    labelWidth: 60,
    bodyStyle: 'padding: 10px;',
    height: 300,
    items: [
      {
        xtype: 'textfield',
        fieldLabel: 'Name',
        name: 'name',
        allowBlank: false
      },{
        xtype: 'combo',
        store: [["NATIVE","Native"],["MASTER","Master"],["SLAVE","Slave"]],
        fieldLabel: 'Type',
        displayField: 'field2',
        valueField: 'field1',
        width: 120,
        name: 'type',
        hiddenName: 'type',
        mode: 'local',
        triggerAction: 'all',
        forceSelection: true,
        editable: false,
        allowBlank: false,
        emptyText: 'Please select...'
      },
        grid
    ]
  });
  
  var win = new Ext.ux.Window({
    id: win_id,
    title: title,
    width: 450,
    items: form,
    doSubmit: function(){
      // remove all hidden fields
      Ext.each(form.find('xtype','hidden'),function(hidden){
        form.remove(hidden);
      });
      
      form.doLayout();
      
      var i = 0;
      grid.store.each(function(r){
        form.add({
          xtype: 'hidden',
          name: 'record['+i+'][name]',
          value: r.data.name
        });
        
        form.add({
          xtype: 'hidden',
          name: 'record['+i+'][type]',
          value: r.data.type
        });
        
        form.add({
          xtype: 'hidden',
          name: 'record['+i+'][content]',
          value: r.data.content
        });
        
        form.add({
          xtype: 'hidden',
          name: 'record['+i+'][ttl]',
          value: r.data.ttl
        });
        
        form.add({
          xtype: 'hidden',
          name: 'record['+i+'][prio]',
          value: r.data.prio
        });
        
        i++;
      });
      
      form.doLayout();
      
      form.form.submit({
        success: function(){
          
        }
      });
    },
    buttons: [
      {
        text: 'Close',
        handler: function() { win.close() }
      },{
        text: 'Submit',
        handler: function() { win.doSubmit() }
      }
    ]
  });
  
  win.show();
}
