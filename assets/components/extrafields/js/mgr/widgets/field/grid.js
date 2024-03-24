ExtraFields.grid.Fields = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        baseParams: {
            action: 'mgr/field/getlist',
            sort: 'menuindex',
            dir: 'asc',
        },
        ddAction: 'mgr/field/sort',
        multi_select: true,
    });

    ExtraFields.grid.Fields.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.grid.Fields, ExtraFields.grid.Default, {

    getFields: function () {
        return [
            'id', 'class_name', 'class_name_lexicon', 'field_name',
            'type_lexicon', 'field_null', 'field_default',
            'active', 'actions'
        ];
    },

    getColumns: function () {
        return [{
            header: _('ef_field_id'),
            dataIndex: 'id',
            sortable: true,
            width: 70,
            fixed: true,
        }, {
            header: _('ef_class_name'),
            dataIndex: 'class_name_lexicon',
            sortable: false,
            width: 'auto',
        }, {
            header: _('ef_field_type'),
            dataIndex: 'type_lexicon',
            sortable: false,
            width: 'auto',
        }, {
            header: _('ef_field_name'),
            dataIndex: 'field_name',
            sortable: true,
            width: 'auto',
        }, {
            header: _('ef_field_null'),
            dataIndex: 'field_null',
            renderer: ExtraFields.utils.renderBoolean,
            sortable: true,
            width: 'auto',
        }, {
            header: _('ef_field_default'),
            dataIndex: 'field_default',
            sortable: true,
            width: 'auto',
        }, {
            header: _('ef_field_active'),
            dataIndex: 'active',
            renderer: ExtraFields.utils.renderBoolean,
            sortable: true,
            width: 'auto',
        }, {
            header: _('ef_grid_actions'),
            dataIndex: 'actions',
            renderer: ExtraFields.utils.renderActions,
            sortable: false,
            width: 125,
            fixed: true,
            id: 'actions',
            hidden: ExtraFields.config.modxversion !== '2',
        }];
    },

});
Ext.reg('ef-grid-fields', ExtraFields.grid.Fields);