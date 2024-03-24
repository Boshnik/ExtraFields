ExtraFields.grid.Tabs = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        baseParams: {
            action: 'mgr/tab/getlist',
            sort: 'menuindex',
            dir: 'asc',
        },
        ddAction: 'mgr/tab/sort',
        multi_select: false,
    });
    ExtraFields.grid.Tabs.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.grid.Tabs, ExtraFields.grid.Default, {

    createObject: function (btn, e) {
        var w = MODx.load({
            xtype: 'ef-tab-window-create',
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
            active: true
        });
        w.show(e.target);
    },

    getFields: function () {
        return ['id', 'name', 'index', 'active', 'actions'];
    },

    getColumns: function () {
        return [{
            header: _('ef_tab_id'),
            dataIndex: 'id',
            sortable: true,
            width: 70
        }, {
            header: _('ef_tab_name'),
            dataIndex: 'name',
            sortable: true,
            width: 300,
        }, {
            header: _('ef_tab_index'),
            dataIndex: 'index',
            sortable: false,
            width: 100,
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
            id: 'actions',
            hidden: ExtraFields.config.modxversion !== '2',
        }];
    },

});
Ext.reg('ef-grid-tabs', ExtraFields.grid.Tabs);
