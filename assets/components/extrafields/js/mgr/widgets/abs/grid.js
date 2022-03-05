ExtraFields.grid.FieldAbs = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        baseParams: {
            action: 'mgr/abs/getlist',
            field_id: config.field_id,
            sort: 'rank',
            dir: 'asc',
        },
        cls: '',
        ddAction: 'mgr/abs/sort',
        multi_select: true,
    });

    ExtraFields.grid.FieldAbs.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.grid.FieldAbs, ExtraFields.grid.Default, {

    createObject: function (btn, e) {
        var w = MODx.load({
            xtype: 'ef-abs-window-create',
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
            field_id: this.config.field_id,
            caption: this.config.field_caption,
            active: true
        });
        w.show(e.target);
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
