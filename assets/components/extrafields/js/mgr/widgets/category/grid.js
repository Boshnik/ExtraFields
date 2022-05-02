ExtraFields.grid.Categories = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        cls: 'ef-grid ef-grid-category',
        baseParams: {
            action: 'mgr/category/getlist',
            tab_id: config.tab_id,
            sort: 'colrank',
            dir: 'asc',
        },
        paging: false,
        pageSize: 100,
        ddAction: 'mgr/category/sort',
        multi_select: false,
    });
    ExtraFields.grid.Categories.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.grid.Categories, ExtraFields.grid.Default, {

    createObject: function (btn, e) {
        var w = MODx.load({
            xtype: 'ef-category-window-create',
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
            tab_id: this.config.tab_id,
            active: true
        });
        w.show(e.target);
    },

    getFields: function () {
        return ['id', 'name', 'active', 'actions'];
    },

    getColumns: function () {
        return [{
            header: _('ef_tab_name'),
            dataIndex: 'name',
            sortable: true,
            width: 200,
        }, {
            header: _('ef_field_active'),
            dataIndex: 'active',
            renderer: ExtraFields.utils.renderBoolean,
            sortable: true,
            width: 50,
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
Ext.reg('ef-grid-categories', ExtraFields.grid.Categories);
