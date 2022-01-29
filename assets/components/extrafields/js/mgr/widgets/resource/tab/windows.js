ExtraFields.window.CreateResourceTab = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'extraresource-tab-window-create';
    }
    Ext.applyIf(config, {
        title: _('extraresource_tab_create'),
        width: 550,
        autoHeight: true,
        url: ExtraFields.config.connectorUrl,
        action: 'mgr/resource/tab/create',
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit();
            }, scope: this
        }]
    });
    ExtraFields.window.CreateResourceTab.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.window.CreateResourceTab, MODx.Window, {

    getFields: function (config) {
        return [{
            xtype: 'hidden',
            name: 'id',
            id: config.id + '-id',
        },{
            xtype: 'textfield',
            fieldLabel: _('extraresource_tab_name'),
            name: 'name',
            id: config.id + '-name',
            anchor: '99%',
            allowBlank: false,
        },{
            xtype: 'numberfield',
            inputType: 'number',
            cls: 'x-form-text',
            fieldLabel: _('extraresource_tab_index'),
            name: 'index',
            id: config.id + '-index',
            anchor: '99%',
            value: 99,
            maxValue: 99,
            minValue: 0
        },{
            xtype: 'xcheckbox',
            boxLabel: _('extraresource_tab_active'),
            name: 'active',
            id: config.id + '-active',
            checked: true,
        }];
    },

});
Ext.reg('extraresource-tab-window-create', ExtraFields.window.CreateResourceTab);


ExtraFields.window.UpdateResourceTab = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'extraresource-tab-window-update';
    }
    Ext.applyIf(config, {
        title: _('extraresource_tab_update'),
        width: 550,
        autoHeight: true,
        url: ExtraFields.config.connectorUrl,
        action: 'mgr/resource/tab/update',
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
    ExtraFields.window.UpdateResourceTab.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.window.UpdateResourceTab, MODx.Window, {

    getFields: function (config) {
        return ExtraFields.window.CreateResourceTab.prototype.getFields.call(this, config);
    },

});
Ext.reg('extraresource-tab-window-update', ExtraFields.window.UpdateResourceTab);