ExtraFields.utils.renderBoolean = function (value) {
    return value
        ? String.format('<span class="green">{0}</span>', _('yes'))
        : String.format('<span class="red">{0}</span>', _('no'));
};

ExtraFields.utils.getMenu = function (actions, grid, selected) {
    var menu = [];
    var cls, icon, title, action;

    var has_delete = false;
    for (var i in actions) {
        if (!actions.hasOwnProperty(i)) {
            continue;
        }

        var a = actions[i];
        if (!a['menu']) {
            if (a == '-') {
                menu.push('-');
            }
            continue;
        }
        else if (menu.length > 0 && !has_delete && (/^remove/i.test(a['action']) || /^delete/i.test(a['action']))) {
            menu.push('-');
            has_delete = true;
        }

        if (selected.length > 1) {
            if (!a['multiple']) {
                continue;
            }
            else if (typeof(a['multiple']) == 'string') {
                a['title'] = a['multiple'];
            }
        }

        icon = a['icon'] ? a['icon'] : '';
        if (typeof(a['cls']) == 'object') {
            if (typeof(a['cls']['menu']) != 'undefined') {
                icon += ' ' + a['cls']['menu'];
            }
        }
        else {
            cls = a['cls'] ? a['cls'] : '';
        }
        title = a['title'] ? a['title'] : a['title'];
        action = a['action'] ? grid[a['action']] : '';

        menu.push({
            handler: action,
            text: String.format(
                '<span class="{0}"><i class="x-menu-item-icon {1}"></i>{2}</span>',
                cls, icon, title
            ),
            scope: grid
        });
    }

    return menu;
};

ExtraFields.utils.renderActions = function (value, props, row) {
    var res = [];
    var cls, icon, title, action, item;
    for (var i in row.data.actions) {
        if (!row.data.actions.hasOwnProperty(i)) {
            continue;
        }
        var a = row.data.actions[i];
        if (!a['button']) {
            continue;
        }

        icon = a['icon'] ? a['icon'] : '';
        if (typeof(a['cls']) == 'object') {
            if (typeof(a['cls']['button']) != 'undefined') {
                icon += ' ' + a['cls']['button'];
            }
        }
        else {
            cls = a['cls'] ? a['cls'] : '';
        }
        action = a['action'] ? a['action'] : '';
        title = a['title'] ? a['title'] : '';

        item = String.format(
            '<li class="{0}"><button class="ef-btn ef-btn-default {1}" action="{2}" title="{3}"></button></li>',
            cls, icon, action, title
        );

        res.push(item);
    }

    return String.format(
        '<ul class="ef-row-actions">{0}</ul>',
        res.join('')
    );
};

ExtraFields.utils.renderImage = function (value) {

    if (Ext.isEmpty(value)) return '';
    if (!Ext.isEmpty(value)) {
        if (!/\/\//.test(value)) {
            if (!/^\//.test(value)) {
                value = '/' + value;
            }
        }
    }
    if (value.indexOf('.svg') === -1) {
        value = MODx.config.connectors_url+'system/phpthumb.php?h=200&f=png&src='+value+'&source=1';
    }

    return String.format('<img src="{0}" style="max-width:100%;height:auto;margin:10px auto"/>', value);
};

ExtraFields.utils.updateImage = function (id, image) {
    let preview = Ext.getCmp(id + '-preview');
    let value = ExtraFields.utils.renderImage(image);
    if (preview) {
        preview.update(value);
    }
}

ExtraFields.utils.renderRTE = function (el) {
    if (el && MODx.loadRTE) {
        MODx.loadRTE(el.id);
    }
}

ExtraFields.utils.setDefaultValue = function (el, defaultValue) {
    setTimeout(() => {
        if(!Ext.isEmpty(defaultValue) && Ext.isEmpty(el.getValue())) {
            if(typeof el.setValue == 'function') {
                switch (el.xtype) {
                    case 'ef-combo-listbox-multiple':
                        defaultValue = defaultValue.split('||');
                        break;
                }
                el.setValue(defaultValue);
            }
        }
    },0);
}

ExtraFields.utils.getAbs = function (config, class_name = ExtraFields.config.class_name) {
    let fields = [];
    switch (class_name) {
        case 'modResource':
            fields = [{
                layout: 'form',
                defaults: {msgTarget: 'under'},
                items: [{
                    xtype: 'ef-combo-getlist-multiple',
                    fieldLabel: _('templates'),
                    name: 'ab_templates',
                    url: MODx.config.connector_url,
                    displayField: 'templatename',
                    valueField: 'id',
                    fields: ['templatename', 'id'],
                    id: config.id + '-ab_templates',
                    baseParams: {
                        action: ExtraFields.config.modxversion === '3'
                            ? 'Element/Template/GetList'
                            : 'element/template/getlist',
                        combo: 1,
                        limit: 0,
                    },
                    listeners: {
                        afterrender: function (el) {
                            setTimeout(() => {
                                let values = config.record.object ? config.record.object.ab_templates : '';
                                if (!Ext.isEmpty(values)) {
                                    el.setValue(values.split('||'));
                                }
                            }, 500);
                        },
                    },
                }, ]
            }, {
                layout: 'column',
                items: [{
                    columnWidth: .5,
                    layout: 'form',
                    defaults: {msgTarget: 'under'},
                    cls: 'x-column-100',
                    items: [{
                        xtype: 'textfield',
                        fieldLabel: _('parents'),
                        name: 'ab_parents',
                        id: config.id + '-ab_parents',
                        anchor: '99%',
                        allowBlank: true,
                        listeners: {
                            afterrender: el => {
                                setTimeout(() => {
                                    el.setWidth('95%');
                                }, 0);
                            }
                        }
                    }]
                }, {
                    columnWidth: .5,
                    layout: 'form',
                    defaults: {msgTarget: 'under'},
                    cls: 'x-column-100',
                    items: [{
                        xtype: 'textfield',
                        fieldLabel: _('resources') + ' (ID)',
                        name: 'ab_resources',
                        id: config.id + '-ab_resources',
                        anchor: '99%',
                        allowBlank: true,
                        listeners: {
                            afterrender: el => {
                                setTimeout(() => {
                                    el.setWidth('95%');
                                }, 0);
                            }
                        }
                    }]
                }]
            }];
            break;

        case 'modUserProfile':
            fields = [{
                xtype: 'ef-combo-getlist-multiple',
                fieldLabel: _('ab_user_group'),
                name: 'ab_user_group',
                url: MODx.config.connector_url,
                displayField: 'name',
                valueField: 'id',
                fields: ['name', 'id'],
                id: config.id + '-ab_user_group',
                baseParams: {
                    action: ExtraFields.config.modxversion === '3'
                        ? 'Security/Group/GetList'
                        : 'security/group/getlist',
                    combo: 1
                },
                listeners: {
                    afterrender: function (el) {
                        setTimeout(() => {
                            let values = config.record.object ? config.record.object.ab_user_group : '';
                            if (!Ext.isEmpty(values)) {
                                el.setValue(values.split('||'));
                            }
                        }, 500);
                    },
                },
            }, {
                xtype: 'textfield',
                fieldLabel: _('ab_users'),
                name: 'ab_users',
                id: config.id + '-ab_users',
                anchor: '99%',
                allowBlank: true,
            }];

            break;
    }

    return fields;
}

ExtraFields.utils.checkAbs = function (object) {

    let result = false;
    switch (ExtraFields.config.class_name) {
        case 'modResource':
            // templates
            let templates = Ext.isEmpty(object.ab_templates) ? [] : object.ab_templates.split('||');
            if (templates.length) {
                if (!templates.includes(ExtraFields.object.template.toString())) result = true;
            }

            // parents
            let parents = Ext.isEmpty(object.ab_parents) ? [] : object.ab_parents.split(',');
            if (parents.length) {
                if (!parents.includes(ExtraFields.object.parent.toString())) result = true;
            }

            // resources
            let resources = Ext.isEmpty(object.ab_resources) ? [] : object.ab_resources.split(',');
            if (resources.length) {
                if (!resources.includes(ExtraFields.object.id.toString())) result = true;
            }
            break;

        case 'modUserProfile':
            // user group
            let groups = Ext.isEmpty(object.ab_user_group) ? [] : object.ab_user_group.split('||');
            if (groups.length) {
                if (!groups.includes(ExtraFields.object.primary_group.toString())) result = true;
            }

            // users
            let users = Ext.isEmpty(object.ab_users) ? [] : object.ab_users.split(',');
            if (users.length) {
                if (!users.includes(ExtraFields.object.id.toString())) result = true;
            }
            break;
    }

    return result;
}