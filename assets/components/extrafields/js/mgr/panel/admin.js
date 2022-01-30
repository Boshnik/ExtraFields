ExtraFields.panel.Admin = function (config) {
    config = config || {};
    Ext.apply(config, {
        baseCls: 'modx-formpanel',
        layout: 'anchor',
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
            id: 'extrafields-panel-tabs',
            stateful: true,
            stateId: 'extrafields-panel-admin',
            stateEvents: ['tabchange'],
            getState: function () {
                return {
                    activeTab: this.items.indexOf(this.getActiveTab())
                };
            },
            items: [{
                title: _('extraresource_tabs'),
                layout: 'anchor',
                items: [{
                    html: _('extrafields_intro_msg'),
                    cls: 'panel-desc extrafields-panel-desc',
                }, {
                    xtype: 'extraresource-grid-tabs',
                    cls: 'main-wrapper',
                }]
            }, {
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
    ExtraFields.panel.Admin.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.panel.Admin, MODx.Panel);
Ext.reg('extrafields-panel-admin', ExtraFields.panel.Admin);
