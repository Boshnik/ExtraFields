ExtraFields.grid.Fields = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        baseParams: {
            action: 'mgr/field/getlist',
            class_name: ExtraFields.config.class_name,
            sort: 'rank',
            dir: 'asc',
        },
        ddAction: 'mgr/field/sort',
        multi_select: true,
    });

    ExtraFields.grid.Fields.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.grid.Fields, ExtraFields.grid.Default, {

    createObject: function (btn, e) {
        var w = MODx.load({
            xtype: 'ef-field-window-create',
            id: Ext.id(),
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
            class_name: ExtraFields.config.class_name,
            active: true
        });
        w.show(e.target);
    },

    getFields: function () {
        return ['id', 'name', 'label', 'fieldtype', 'active', 'actions'];
    },

    getColumns: function () {
        return [{
            header: _('ef_field_id'),
            dataIndex: 'id',
            sortable: true,
            width: 70
        }, {
            header: _('ef_field_name'),
            dataIndex: 'name',
            sortable: true,
            width: 200,
        }, {
            header: _('ef_field_label'),
            dataIndex: 'label',
            sortable: false,
            width: 150,
        }, {
            header: _('ef_field_fieldtype'),
            dataIndex: 'fieldtype',
            sortable: false,
            width: 150,
        }, {
            header: _('ef_field_active'),
            dataIndex: 'active',
            renderer: ExtraFields.utils.renderBoolean,
            sortable: true,
            width: 100,
        }, {
            header: _('ef_grid_actions'),
            dataIndex: 'actions',
            renderer: ExtraFields.utils.renderActions,
            sortable: false,
            width: 100,
            id: 'actions'
        }];
    },

});
Ext.reg('ef-grid-fields', ExtraFields.grid.Fields);
