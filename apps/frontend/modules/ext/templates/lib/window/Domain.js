function DomainWindow(domain)
{
  var win_id = get_win_id(domain);
  if (!win_id) return;
  
  var form = new Ext.form.FormPanel({
    height: 260,
    border: false,
    url: '<?php echo url_for('domain/edit') ?>',
    defaults: { allowBlank: false },
    items: [
      {
        xtype: 'hidden',
        name: 'id',
        value: domain.id
      },{
        layout: 'fit',
        border: false,
        items: new Ext.ux.RecordsGrid({
          records: domain.records, 
          defaultName: domain.name,
          border: false
        })
      }
    ]
  });
  
  var win = new Ext.ux.Window({
    id: win_id,
    title: domain.name + ' ('+domain.type+')',
    width: 450,
    items: form,
    doSubmit: function(){
      // remove all hidden fields
      Ext.each(form.find('xtype','hidden'),function(hidden){
        if (hidden.name != 'id')
        {
          form.remove(hidden);
        }
      });
      
      form.doLayout();
      
      var grid = form.items.items[form.items.items.length-1].items.items[0];
      
      var i = 0;
      grid.store.each(function(r){
        
        console.log(r);
        
        form.add({
          xtype: 'hidden',
          name: 'record['+i+'][id]',
          value: r.data.id
        });
        
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
        success: function(form, action){
          
          win.close();
          
          Ext.Msg.alert('Info',action.result.info);
          
          DomainStore.load();
        },
        failure: function(form, action){
          
          Ext.Msg.alert('Error',action.result.errors.record);
        }
      });
      
    },
    buttons: [
      {
        text: 'Submit',
        handler: function() { win.doSubmit() }
      },{
        text: 'Close',
        handler: function() { win.close() }
      }
    ]
  });
  
  win.show();
}
