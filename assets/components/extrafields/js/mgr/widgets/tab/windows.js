ExtraFields.window.CreateTab = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'ef-tab-window-create';
    }
    Ext.applyIf(config, {
        title: _('ef_row_create'),
        width: 650,
        action: 'mgr/tab/create',
    });
    ExtraFields.window.CreateTab.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.window.CreateTab, ExtraFields.window.Default, {

    getFields: function (config) {
        return [{
            xtype: 'hidden',
            name: 'id',
            id: config.id + '-id',
        },{
            xtype: 'hidden',
            name: 'class_name',
            id: config.id + '-class_name',
        }, {
            layout: 'column',
            items: [{
                layout: 'form',
                columnWidth: .8,
                defaults: {msgTarget: 'under'},
                items: [{
                    xtype: 'textfield',
                    fieldLabel: _('ef_tab_name'),
                    name: 'name',
                    id: config.id + '-name',
                    anchor: '99%',
                    allowBlank: false,
                }]
            }, {
                layout: 'form',
                columnWidth: .2,
                defaults: {msgTarget: 'under'},
                items: [{
                    xtype: 'numberfield',
                    inputType: 'number',
                    cls: 'x-form-text',
                    fieldLabel: _('ef_tab_index'),
                    name: 'index',
                    id: config.id + '-index',
                    anchor: '99%',
                    value: 99,
                    maxValue: 99,
                    minValue: 0
                }]
            }]
        }, {
            xtype: 'modx-tabs',
            defaults: {border: false, autoHeight: true},
            hideMode: 'offsets',
            style: {margin: '15px 0'},
            cls: 'modx' + ExtraFields.config.modxversion,
            items: [{
                title: _('ef_categories'),
                layout: 'form',
                cls: '',
                items: [{
                    xtype: 'ef-grid-categories',
                    tab_id: config.record ? config.record.object.id : 0,
                }]
            }, {
                title: _('ef_accessibility'),
                layout: 'form',
                cls: '',
                items: ExtraFields.utils.getAbs(config)
            }]
        },{
            xtype: 'xcheckbox',
            boxLabel: _('ef_field_active'),
            name: 'active',
            id: config.id + '-active',
            checked: true,
        }];
    },

});
Ext.reg('ef-tab-window-create', ExtraFields.window.CreateTab);


ExtraFields.window.UpdateTab = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'ef-tab-window-update';
    }
    Ext.applyIf(config, {
        title: _('ef_row_update') + ': ' + config.record.object.name,
        action: 'mgr/tab/update',
    });
    ExtraFields.window.UpdateTab.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.window.UpdateTab, ExtraFields.window.CreateTab);
Ext.reg('ef-tab-window-update', ExtraFields.window.UpdateTab);