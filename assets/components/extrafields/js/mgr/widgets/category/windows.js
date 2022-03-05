ExtraFields.window.CreateCategory = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'ef-category-window-create';
    }
    Ext.applyIf(config, {
        title: _('ef_row_create'),
        width: 550,
        action: 'mgr/category/create',
    });
    ExtraFields.window.CreateCategory.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.window.CreateCategory, ExtraFields.window.Default, {

    getFields: function (config) {
        return [{
            xtype: 'hidden',
            name: 'id',
            id: config.id + '-id',
        },{
            xtype: 'hidden',
            name: 'tab_id',
            id: config.id + '-tab_id',
        },{
            xtype: 'textfield',
            fieldLabel: _('ef_category_name'),
            name: 'name',
            id: config.id + '-name',
            anchor: '99%',
            allowBlank: false,
        },{
            xtype: 'textarea',
            fieldLabel: _('ef_category_description'),
            name: 'description',
            id: config.id + '-description',
            anchor: '99%',
            allowBlank: true,
        },{
            xtype: 'fieldset',
            title: _('ef_accessibility'),
            columnWidth: 1,
            collapsible: true,
            items: ExtraFields.utils.getAbs(config)
        },{
            xtype: 'xcheckbox',
            boxLabel: _('ef_field_active'),
            name: 'active',
            id: config.id + '-active',
            checked: true,
        }];
    },

});
Ext.reg('ef-category-window-create', ExtraFields.window.CreateCategory);


ExtraFields.window.UpdateCategory = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'ef-category-window-update';
    }
    Ext.applyIf(config, {
        title: _('ef_row_update') + ': ' + config.record.object.name,
        action: 'mgr/category/update',
    });
    ExtraFields.window.UpdateCategory.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.window.UpdateCategory, ExtraFields.window.CreateCategory);
Ext.reg('ef-category-window-update', ExtraFields.window.UpdateCategory);