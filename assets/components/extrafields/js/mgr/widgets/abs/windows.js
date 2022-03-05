ExtraFields.window.CreateFieldAbs = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = Ext.id();
    }
    Ext.applyIf(config, {
        title: _('ef_row_create'),
        action: 'mgr/abs/create',
    });

    ExtraFields.window.CreateFieldAbs.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.window.CreateFieldAbs, ExtraFields.window.Default, {

    getFields: function (config) {
        let fields = [{
            xtype: 'hidden',
            name: 'id',
            id: config.id + '-id',
        }, {
            xtype: 'hidden',
            name: 'field_id',
            id: config.id + '-field_id',
        }, {
            layout: 'column',
            defaults: {msgTarget: 'under'},
            renderTo: Ext.getBody(),
            items: [{
                columnWidth: .3,
                layout: 'form',
                defaults: {msgTarget: 'under'},
                items: [{
                    xtype: 'textfield',
                    fieldLabel: _('ef_field_caption'),
                    name: 'caption',
                    id: config.id + '-caption',
                    anchor: '99%',
                    allowBlank: false,
                }]
            }, {
                columnWidth: .7,
                layout: 'form',
                defaults: {msgTarget: 'under'},
                items: [{
                    xtype: 'textfield',
                    fieldLabel: _('ef_field_help'),
                    name: 'help',
                    id: config.id + '-help',
                    anchor: '99%',
                    allowBlank: true,
                }]
            }]
        }, {
            layout: 'column',
            items: [{
                columnWidth: .5,
                layout: 'form',
                defaults: {msgTarget: 'under'},
                cls: 'x-superboxselect ef-superboxselect',
                items: [{
                    xtype: 'ef-combo-areas',
                    fieldLabel: _('ef_field_areas'),
                    name: 'areas',
                    id: config.id + '-areas',
                    allowBlank: true,
                    anchor: '100%',
                    width: 100,
                },{
                    xtype: 'ef-combo-getlist',
                    fieldLabel: _('ef_field_tab'),
                    name: 'tab_id',
                    id: config.id + '-tab-id',
                    allowBlank: true,
                    baseParams: {
                        action: 'mgr/tab/getlist',
                        class_name: ExtraFields.config.class_name,
                        sort: 'rank',
                        dir: 'asc',
                        combo: 1,
                    },
                    triggerConfig: {
                        tag: 'div',
                        cls: 'x-superboxselect-btns',
                        cn: [
                            {tag: 'div', cls: 'x-superboxselect-btn-expand x-form-trigger'},
                            {tag: 'div', cls: 'x-superboxselect-btn-clear x-form-trigger'}
                        ]
                    },
                    onTriggerClick: function(event, btn){
                        if (btn && Ext.get(btn).hasClass('x-superboxselect-btn-clear')) {
                            Ext.getCmp(config.id + '-tab-id').setValue();
                            this.fireEvent('render', this);
                        } else {
                            MODx.combo.ComboBox.superclass.onTriggerClick.call(this);
                        }
                    },
                    listeners: {
                        render: {
                            fn: this.changeTabId,
                            scope: this,
                        },
                        select: {
                            fn: this.changeTabId,
                            scope: this,
                        }
                    }
                }]
            }, {
                columnWidth: .5,
                layout: 'form',
                defaults: {msgTarget: 'under'},
                items: [{
                    xtype: 'numberfield',
                    inputType: 'number',
                    cls: 'x-form-text',
                    fieldLabel: _('ef_field_index'),
                    name: 'index',
                    id: config.id + '-index',
                    allowBlank: true,
                    anchor: '100%',
                    width: 100,
                    listeners: {
                        render: function (el) {
                            if(Ext.isEmpty(el.value)) {
                                el.setValue(0);
                            }
                        }
                    }
                }, {
                    xtype: 'ef-combo-getlist',
                    fieldLabel: _('ef_field_category'),
                    name: 'category_id',
                    id: config.id + '-category-id',
                    allowBlank: true,
                    baseParams: {
                        action: 'mgr/category/getlist',
                        tab_id: config.record ? config.record.object.tab_id : 0,
                        sort: 'rank',
                        dir: 'asc',
                        combo: 1,
                    }
                }]
            }]
        }];

        fields = [].concat(fields, ExtraFields.utils.getAbs(config));
        fields = [].concat(fields, [{
            xtype: 'xcheckbox',
            boxLabel: _('ef_field_active'),
            name: 'active',
            id: config.id + '-active',
            checked: true,
        }]);

        return fields;
    },


    changeTabId: function (combo, row) {
        let category = Ext.getCmp(this.id + '-category-id');
        category.allowBlank = true;
        let categoryEl = document.getElementById(category.id);
        let store = category.getStore();
        store.baseParams.tab_id = combo.value;

        if (row) {
            category.setValue('');
            store.load();
            categoryEl.value = '';
        } else {
            if (!combo.value) {
                category.setValue('');
                store.load();
            }
        }
        setTimeout(() => {
            category.allowBlank = !store.totalLength;
        }, 300);
    }

});
Ext.reg('ef-abs-window-create', ExtraFields.window.CreateFieldAbs);


ExtraFields.window.UpdateFieldAbs = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = Exy.id();
    }
    Ext.applyIf(config, {
        title: _('ef_row_update') + ': ' + config.record.object.caption,
        action: 'mgr/abs/update',
    });

    ExtraFields.window.UpdateFieldAbs.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.window.UpdateFieldAbs, ExtraFields.window.CreateFieldAbs);
Ext.reg('ef-abs-window-update', ExtraFields.window.UpdateFieldAbs);