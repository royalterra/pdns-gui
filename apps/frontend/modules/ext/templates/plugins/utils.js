function roundNumber(num, dec) {
  var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
  return result;
}

function trim(str, chars) {
    return ltrim(rtrim(str, chars), chars);
}

function ltrim(str, chars) {
    chars = chars || "\\s";
    return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
}

function rtrim(str, chars) {
    chars = chars || "\\s";
    return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
}

/* Ext.Container.removeAll() */
Ext.override(Ext.Container, {
  removeAll : function(autoDestroy){
    var item, removed=[];
    while(this.items && ( item = this.items.last()) ){
      removed.unshift ( this.remove(item,autoDestroy) );
    }
    if(!!removed.length) { this.doLayout(); }
    //an array in original layout order
    return !!removed.length ? removed : null;
  }
});


var imgButtonTpl = new Ext.Template(
  '<table border="0" cellpadding="0" cellspacing="0"><tbody><tr>' +
  '<td><i> </i></td><td>' +
  '<button type="button"><img src="{0}"></button>' +
  '</td><td ><i> </i></td>' +
  '</tr></tbody></table>');

Ext.ux.collapsedPanelTitlePlugin = function ()
{
  this.init = function(p) {
    if (p.collapsible)
    {
      var r = p.region;
      if ((r == 'north') || (r == 'south'))
      {
        p.on ('render', function()
          {
              var ct = p.ownerCt;
              ct.on ('afterlayout', function()
                {
                  if (ct.layout[r].collapsedEl)
                  {
                      p.collapsedTitleEl = ct.layout[r].collapsedEl.createChild ({
                          tag: 'span',
                          cls: 'x-panel-collapsed-text',
                          html: p.title
                      });
                      p.setTitle = Ext.Panel.prototype.setTitle.createSequence (function(t)
                          {p.collapsedTitleEl.dom.innerHTML = t;});
                  }
              }, false, {single:true});
          p.on ('collapse', function()
              {
                  if (ct.layout[r].collapsedEl && !p.collapsedTitleEl)
                  {
                      p.collapsedTitleEl = ct.layout[r].collapsedEl.createChild ({
                          tag: 'span',
                          cls: 'x-panel-collapsed-text',
                          html: p.title
                      });
                      p.setTitle = Ext.Panel.prototype.setTitle.createSequence (function(t)
                          {p.collapsedTitleEl.dom.innerHTML = t;});
                  }
                }, false, {single:true});
          });
      }
    }
  };
};
