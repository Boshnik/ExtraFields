ExtraFields.grid.Default = function (config) {
    config = config || {};
    config.id = Ext.id();

    if (typeof(config['multi_select']) !== 'undefined' && config['multi_select'] === true) {
        config.sm = new Ext.grid.CheckboxSelectionModel();
    }

    config.objectAction = config.baseParams.action.replace(/(\/\w+)$/, '').replace(/^(\w+\/)/, '')
    config.objectName = config.objectAction.split('/').join('-');

    Ext.applyIf(config, {
        url: ExtraFields.config.connectorUrl,
        baseParams: {},
        id: Ext.id(),
        cls: 'main-wrapper ef-grid ef-' + config.objectName,
        autoHeight: true,
        paging: true,
        remoteSort: true,
        stateful: false,
        stateId: config.id,
        ddGroup: config.id + 'DD',
        enableDragDrop: true,
        fields: this.getFields(config),
        columns: this.getColumns(config),
        tbar: this.getTopBar(config),
        listeners: this.getListeners(config),
        pageSize: 20,
        viewConfig: {
            forceFit: true,
            enableRowBody: true,
            autoFill: true,
            showPreview: true,
            scrollOffset: -10,
            getRowClass: function (rec) {
                return !rec.data.active
                    ? 'ef-grid-row-disabled'
                    : '';
            }
        },
    });
    ExtraFields.grid.Default.superclass.constructor.call(this, config);

    // Clear selection on grid refresh
    this.store.on('load', function () {
        if (this._getSelectedIds().length) {
            this.getSelectionModel().clearSelections();
        }
    }, this);

    if (config.enableDragDrop && config.ddAction) {
        this.on('render', function(grid) {
            grid._initDD(config);
        });
    }
};
Ext.extend(ExtraFields.grid.Default, MODx.grid.Grid, {

    getFields: function () {
        return [
            'id', 'actions', 'active'
        ];
    },

    getColumns: function () {
        return [{
            header: _('id'),
            dataIndex: 'id',
            width: 35,
            sortable: true,
        }, {
            header: _('ef_grid_actions'),
            dataIndex: 'actions',
            renderer: ExtraFields.utils.renderActions,
            sortable: false,
            width: 75,
            id: 'actions'
        }];
    },

    createObject: function (btn, e) {
        var w = MODx.load({
            xtype: 'ef-'+this.config.objectName+'-window-create',
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
        w.setValues({active: true});
        w.show(e.target);
    },

    updateObject: function (btn, e, row) {
        if (typeof(row) != 'undefined') {
            this.menu.record = row.data;
        }
        else if (!this.menu.record) {
            return false;
        }

        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/'+this.config.objectAction+'/get',
                id: this.menu.record.id
            },
            listeners: {
                success: {
                    fn: function (r) {
                        var w = MODx.load({
                            xtype: 'ef-'+this.config.objectName+'-window-update',
                            id: Ext.id(),
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
                        w.setValues(r.object);
                        w.show(e.target);
                    }, scope: this
                }
            }
        });
    },

    removeObject: function () {
        var ids = this._getSelectedIds();
        Ext.MessageBox.confirm(
            _('ef_row_remove_title'),
            ids.length > 1
                ? _('ef_rows_remove_confirm')
                : _('ef_row_remove_confirm'),
            function (val) {
                if (val === 'yes') {
                    this.multipleAction(this.config.objectAction+'/remove');
                }
            }, this
        );
    },

    copyObject: function () {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }

        Ext.MessageBox.confirm(
            _('ef_row_copy'),
            _('ef_row_copy_confirm'),
            function (val) {
                if (val === 'yes') {
                    this.multipleAction(this.config.objectAction+'/copy');
                }
            }, this
        );
    },

    disableObject: function () {
        this.multipleAction(this.config.objectAction + '/disable');
    },

    enableObject: function () {
        this.multipleAction(this.config.objectAction + '/enable');
    },

    multipleAction: function (method) {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        var el = this.getEl();
        el.mask(_('loading'), 'x-mask-loading');
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/multiple',
                method: method,
                ids: Ext.util.JSON.encode(ids),
            },
            listeners: {
                success: {
                    fn: function () {
                        el.unmask();
                        this.refresh();
                    }, scope: this
                },
                failure: {
                    fn: function (response) {
                        el.unmask();
                        MODx.msg.alert(_('error'), response.message);
                    }, scope: this
                },
            }
        });
    },

    getTopBar: function () {
        return [{
            text: '<i class="icon icon-plus"></i>&nbsp;' + _('ef_row_create'),
            handler: this.createObject,
            scope: this
        }, '->', this.getSearchField()];
    },

    getSearchField: function (width) {
        return {
            xtype: 'ef-field-search',
            width: width || 250,
            listeners: {
                search: {
                    fn: function (field) {
                        this._doSearch(field);
                    }, scope: this
                },
                clear: {
                    fn: function (field) {
                        field.setValue('');
                        this._clearSearch();
                    }, scope: this
                },
            }
        };
    },

    getListeners: function () {
        return {
            rowDblClick: function (grid, rowIndex, e) {
                var row = grid.store.getAt(rowIndex);
                this.updateObject(grid, e, row);
            },
        };
    },

    getMenu: function (grid, rowIndex) {
        var ids = this._getSelectedIds();
        var row = grid.getStore().getAt(rowIndex);
        var menu = ExtraFields.utils.getMenu(row.data['actions'], this, ids);

        this.addContextMenuItem(menu);
    },

    onClick: function (e) {
        var elem = e.getTarget();
        if (elem.nodeName === 'BUTTON') {
            var row = this.getSelectionModel().getSelected();
            if (typeof(row) != 'undefined') {
                var action = elem.getAttribute('action');
                if (action === 'showMenu') {
                    var ri = this.getStore().find('id', row.id);
                    return this._showMenu(this, ri, e);
                }
                else if (typeof this[action] === 'function') {
                    this.menu.record = row.data;
                    return this[action](this, e);
                }
            }
        }
        return this.processEvent('click', e);
    },

    refresh: function() {
        this.getStore().reload();
        if (this.config['enableDragDrop'] === true) {
            this.getSelectionModel().clearSelections(true);
        }
    },

    _doSearch: function (tf) {
        this.getStore().baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
    },

    _clearSearch: function () {
        this.getStore().baseParams.query = '';
        this.getBottomToolbar().changePage(1);
    },

    _getSelectedIds: function () {
        var ids = [];
        var selected = this.getSelectionModel().getSelections();

        for (var i in selected) {
            if (!selected.hasOwnProperty(i)) {
                continue;
            }
            ids.push(selected[i]['id']);
        }

        return ids;
    },

    _initDD: function (config) {
        var grid = this;
        var el = grid.getEl();

        new Ext.dd.DropTarget(el, {
            ddGroup: grid.ddGroup,
            notifyDrop: function (dd, e, data) {
                var store = grid.getStore();
                var target = store.getAt(dd.getDragData(e).rowIndex);
                var sources = [];
                if (data.selections.length < 1 || data.selections[0].id === target.id) {
                    return false;
                }
                for (var i in data.selections) {
                    if (!data.selections.hasOwnProperty(i)) {
                        continue;
                    }
                    var row = data.selections[i];
                    sources.push(row.id);
                }

                el.mask(_('loading'), 'x-mask-loading');
                MODx.Ajax.request({
                    url: config.url,
                    params: {
                        action: config.ddAction,
                        sources: Ext.util.JSON.encode(sources),
                        target: target.id,
                    },
                    listeners: {
                        success: {
                            fn: function () {
                                el.unmask();
                                grid.refresh();
                                if (typeof(grid.reloadTree) == 'function') {
                                    sources.push(target.id);
                                    grid.reloadTree(sources);
                                }
                            }, scope: grid
                        },
                        failure: {
                            fn: function () {
                                el.unmask();
                            }, scope: grid
                        },
                    }
                });
            },
        });
    },

    _loadStore: function () {
        this.store = new Ext.data.JsonStore({
            url: this.config.url,
            baseParams: this.config.baseParams || {action: this.config.action || 'getList'},
            fields: this.config.fields,
            root: 'results',
            totalProperty: 'total',
            remoteSort: this.config.remoteSort || false,
            storeId: this.config.storeId || Ext.id(),
            autoDestroy: true,
            listeners: {
                load: function (store, rows, data) {
                    store.sortInfo = {
                        field: data.params['sort'] || 'id',
                        direction: data.params['dir'] || 'ASC',
                    };
                    Ext.getCmp('modx-content').doLayout();
                }
            }
        });
    },

});
Ext.reg('ef-grid-default', ExtraFields.grid.Default);