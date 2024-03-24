ExtraFields.panel.Home = function (config) {
    config = config || {};
    Ext.apply(config, {
        baseCls: 'modx-formpanel',
        layout: 'anchor',
        hideMode: 'offsets',
        items: [{
            html: '<h2>' + _('extrafields') + '</h2>',
            cls: 'ef-header-panel',
            style: {margin: '15px 0'}
        }, {
            xtype: 'modx-tabs',
            defaults: {border: false, autoHeight: true},
            border: true,
            hideMode: 'offsets',
            cls: 'ef-tab-panel',
            id: 'ef-panel-tabs',
            stateful: true,
            stateId: 'ef-panel-home',
            stateEvents: ['tabchange'],
            getState: function () {
                return {
                    activeTab: this.items.indexOf(this.getActiveTab())
                };
            },
            items: [{
                title: _('ef_fields'),
                layout: 'anchor',
                items: [{
                    html: _('extrafields_intro_msg'),
                    cls: 'panel-desc ef-panel-desc',
                }, {
                    xtype: 'ef-grid-fields',
                    cls: 'main-wrapper',
                }]
            }, {
                title: _('ef_tabs'),
                layout: 'anchor',
                items: [{
                    html: _('extrafields_intro_msg'),
                    cls: 'panel-desc ef-panel-desc',
                }, {
                    xtype: 'ef-grid-tabs',
                    cls: 'main-wrapper',
                }]
            }]
        }]
    });
    ExtraFields.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.panel.Home, MODx.Panel);
Ext.reg('ef-panel-home', ExtraFields.panel.Home);
