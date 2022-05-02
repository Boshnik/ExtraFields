ExtraFields.grid.FieldAbs = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        baseParams: {
            action: 'mgr/abs/getlist',
            field_id: config.field_id,
            sort: 'colrank',
            dir: 'asc',
        },
        cls: '',
        ddAction: 'mgr/abs/sort',
        multi_select: true,
        pageSize: 10,
    });

    ExtraFields.grid.FieldAbs.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.grid.FieldAbs, ExtraFields.grid.Default, {

    createObject: function (btn, e) {

        let type = Ext.getCmp(this.config.config_id + '-type').getValue();
        if (Ext.isEmpty(type)) {
            MODx.msg.alert(_('error'), _('ef_field_empty_type'));
            return;
        }

        var w = MODx.load({
            xtype: 'ef-abs-window-create',
            id: Ext.id(),
            type: type,
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        });
        w.reset();
        w.setValues({
            field_id: this.config.field_id,
            active: true
        });
        w.show(e.target);
    },

    updateObject: function (btn, e, row) {
        if (typeof(row) != 'undefined') {
            this.menu.record = row.data;
        }
        else if (!this.menu.record) {
            return false;
        }

        let type = Ext.getCmp(this.config.config_id + '-type').getValue();
        if (Ext.isEmpty(type)) {
            MODx.msg.alert(_('error'), _('ef_field_empty_type'));
            return;
        }

        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/abs/get',
                id: this.menu.record.id
            },
            listeners: {
                success: {
                    fn: function (r) {
                        var w = MODx.load({
                            xtype: 'ef-abs-window-update',
                            id: Ext.id(),
                            type: type,
                            record: r,
                            listeners: {
                                success: {
                                    fn: function () {
                                        this.refresh();
                                    }, scope: this
                                }
                            }
                        });
                        w.reset();
                        if (!ExtraFields.config.pageblocks) {
                            delete r.object['table_id'];
                        }
                        w.setValues(r.object);
                        w.show(e.target);
                    }, scope: this
                }
            }
        });
    },

    getFields: function () {
        return ['id', 'caption', 'field', 'tab', 'category', 'active', 'actions'];
    },

    getColumns: function () {
        return [{
            header: _('ef_field_caption'),
            dataIndex: 'caption',
            sortable: true,
            width: 200,
        }, {
            header: _('ef_field_tab'),
            dataIndex: 'tab',
            sortable: false,
            width: 150,
        }, {
            header: _('ef_field_category'),
            dataIndex: 'category',
            sortable: false,
            width: 150,
        }, {
            header: _('ef_grid_actions'),
            dataIndex: 'actions',
            renderer: ExtraFields.utils.renderActions,
            sortable: false,
            width: 100,
            id: 'actions'
        }];
    },

    getTopBar: function () {
        return [{
            text: _('ef_row_create'),
            handler: this.createObject,
            scope: this
        }];
    },

    getSearchField: function () {
        return '';
    }

});
Ext.reg('ef-grid-field-abs', ExtraFields.grid.FieldAbs);
