ExtraFields.panel.Home = function (config) {
    config = config || {};
    Ext.apply(config, {
        baseCls: 'modx-formpanel',
        layout: 'anchor',
        /*
         stateful: true,
         stateId: 'extrafields-panel-home',
         stateEvents: ['tabchange'],
         getState:function() {return {activeTab:this.items.indexOf(this.getActiveTab())};},
         */
        hideMode: 'offsets',
        items: [{
            html: '<h2>' + _('extrafields') + '</h2>',
            cls: 'extrafields-header-panel',
            style: {margin: '15px 0'}
        }, {
            xtype: 'modx-tabs',
            defaults: {border: false, autoHeight: true},
            border: true,
            hideMode: 'offsets',
            cls: 'extrafields-tab-panel',
            items: [{
                title: _('extrameta_fields'),
                layout: 'anchor',
                items: [{
                    html: _('extrafields_intro_msg'),
                    cls: 'panel-desc extrafields-panel-desc',
                }, {
                    xtype: 'extrameta-grid-fields',
                    cls: 'main-wrapper',
                }]
            }]
        }]
    });
    ExtraFields.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.panel.Home, MODx.Panel);
Ext.reg('extrafields-panel-home', ExtraFields.panel.Home);
