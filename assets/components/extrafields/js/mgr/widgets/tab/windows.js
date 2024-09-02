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
        }, {
            xtype: 'ef-combo-table-class',
            fieldLabel: _('ef_class_name'),
            name: 'class_name',
            hiddenName: 'class_name',
            id: config.id + '-class_name',
            combo: config.combo,
            anchor: '100%',
            width: 100,
            allowBlank: false,
            listeners: {
                afterrender: {
                    fn: this.changeFields,
                    scope: this,
                },
                select: {
                    fn: this.changeFields,
                    scope: this,
                }
            }
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
                    minValue: 0,
                    listeners: {
                        afterrender: (el) => {
                            if (Ext.isEmpty(el.value)) {
                                el.setValue(1);
                            }
                        }
                    }
                }]
            }]
        }, {
            xtype: 'fieldset',
            title: _('ef_accessibility'),
            id: config.id + '-abs',
            layout: 'form',
            columnWidth: 1,
            collapsible: true,
            hidden: true,
            items: [].concat(
                ExtraFields.utils.getAbs(config, 'modResource'),
                ExtraFields.utils.getAbs(config, 'modUserProfile')
            ),
        }, {
            xtype: 'fieldset',
            title: _('ef_categories'),
            id: config.id + '-categories',
            layout: 'form',
            columnWidth: 1,
            collapsible: true,
            hidden: true,
            items: [{
                xtype: 'ef-grid-categories',
                tab_id: config.record?.id ?? 0,
            }]
        }, {
            xtype: 'xcheckbox',
            boxLabel: _('ef_field_active'),
            name: 'active',
            id: config.id + '-active',
            checked: true,
        }];
    },


    changeFields: function (combo, row) {
        let fixId = this.id;

        if (!Ext.isEmpty(combo.value)) {
            ExtraFields.config.class_name = combo.value;
        }

        let panelAbs = Ext.getCmp(fixId + '-abs');
        let panelCategories = Ext.getCmp(fixId + '-categories');

        let ab_templates = Ext.getCmp(fixId + '-ab_templates');
        let ab_parents = Ext.getCmp(fixId + '-ab_parents');
        let ab_resources = Ext.getCmp(fixId + '-ab_resources');
        let ab_userGroup = Ext.getCmp(fixId + '-ab_user_group');
        let ab_users = Ext.getCmp(fixId + '-ab_users');

        ab_templates.hide();
        ab_parents.hide();
        ab_resources.hide();
        ab_userGroup.hide();
        ab_users.hide();

        switch (combo.value) {
            case 'modResource':
                ab_templates.show();
                ab_parents.show();
                ab_resources.show();
                break;
            case 'modUserProfile':
                ab_userGroup.show();
                ab_users.show();
        }

        if (panelAbs) {
            panelAbs.setVisible(true);
        }
        if (panelCategories) {
            panelCategories.setVisible(true);
        }

        // if (panelAbs) {
            // panelAbs.removeAll(true);
            // let fields = ExtraFields.utils.getAbs(this.config);
            // fields.forEach(function(field) {
            //     if (field.name) {
            //         field.id = fixId + '-' + field.name;
            //     }
            //     panelAbs.add(field);
            // });
            // panelAbs.setVisible(true);
            // panelAbs.doLayout();
        // }
    },


});
Ext.reg('ef-tab-window-create', ExtraFields.window.CreateTab);


ExtraFields.window.UpdateTab = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'ef-tab-window-update';
    }
    Ext.applyIf(config, {
        title: _('ef_row_update') + ': ' + config.record.name,
        action: 'mgr/tab/update',
    });
    ExtraFields.window.UpdateTab.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.window.UpdateTab, ExtraFields.window.CreateTab);
Ext.reg('ef-tab-window-update', ExtraFields.window.UpdateTab);