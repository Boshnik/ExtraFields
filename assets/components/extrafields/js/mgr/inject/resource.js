Ext.ComponentMgr.onAvailable('modx-resource-tabs', function() {
    let tabs = this.items;
    let fields = [];

    tabs.forEach(tab => {
        ExtraFields.fields.forEach(field => {
            if (fields.includes(field.id)) return;

            let issetField = false;
            field.abs.forEach(abs => {
                if (issetField) return;
                if (tab.id == abs.areas) {
                    if (ExtraFields.utils.checkAbs(abs)) return;
                    field = Object.assign(field, abs);
                    if (tab.id == 'modx-resource-access-permissions') {
                        field.cls = 'main-wrapper';
                    }
                    tab.items.splice(abs.index, 0, ExtraFields.utils.getXtype(field));
                    if (!fields.includes(field.id)) {
                        fields.push(field.id);
                    }
                    issetField = true;
                }
            });

            if (issetField || !tab.items) return;
            let columns = tab.items[0];
            if (columns.layout != 'column') return;

            columns.items.forEach(column => {
                field.abs.forEach(abs => {
                    if (issetField) return;
                    if (column.id == abs.areas) {
                        if (ExtraFields.utils.checkAbs(abs)) return;
                        field = Object.assign(field, abs);
                        column.items.splice(abs.index, 0, ExtraFields.utils.getXtype(field));
                        if (!fields.includes(field.id)) {
                            fields.push(field.id);
                        }
                        issetField = true;
                    }
                });

                if (issetField) return;

                if (column.id == 'modx-page-settings-right') {
                    if (!column.items[3].items) return;
                    let boxes = column.items[3].items[0].items;
                    boxes.forEach(box => {
                        field.abs.forEach(abs => {
                            if (issetField) return;
                            if (box.id == abs.areas) {
                                if (ExtraFields.utils.checkAbs(abs)) return;
                                field = Object.assign(field, abs);
                                box.items.splice(abs.index, 0, ExtraFields.utils.getXtype(field));
                                if (!fields.includes(field.id)) {
                                    fields.push(field.id);
                                }
                                issetField = true;
                            }
                        });
                    });
                }

            });
        });
    });

    ExtraFields.tabs.forEach(tab => {
        if (ExtraFields.utils.checkAbs(tab)) return;

        let items = [];
        if (tab.categories.length) {
            let categories = [];
            tab.categories.forEach(category => {
                if (ExtraFields.utils.checkAbs(category)) return;

                let c_items = [];
                if (category.description) {
                    c_items.push({
                        html: category.description,
                        cls: 'panel-desc ef-panel-desc',
                    });
                }

                ExtraFields.fields.forEach(field => {
                    if (fields.includes(field.id)) return;
                    let issetField = false;
                    field.abs.forEach(abs => {
                        if (issetField) return;
                        if (tab.id == abs.tab_id && category.id == abs.category_id) {
                            if (ExtraFields.utils.checkAbs(abs)) return;
                            field = Object.assign(field, abs);
                            c_items.push(ExtraFields.utils.getXtype(field));
                            if (!fields.includes(field.id)) {
                                fields.push(field.id);
                            }
                            issetField = true;
                        }
                    });
                });

                categories.push({
                    title: category.name,
                    layout: 'anchor',
                    items: c_items
                });
            });
            items = [{
                xtype: 'modx-vtabs',
                items: categories
            }]
        } else {
            ExtraFields.fields.forEach(field => {
                if (fields.includes(field.id)) return;
                let issetField = false;
                field.abs.forEach(abs => {
                    if (issetField) return;
                    if (tab.id == abs.tab_id) {
                        if (ExtraFields.utils.checkAbs(abs)) return;
                        field = Object.assign(field, abs);
                        items.push(ExtraFields.utils.getXtype(field));
                        if (!fields.includes(field.id)) {
                            fields.push(field.id);
                        }
                        issetField = true;
                    }
                });
            });
            items = [{
                layout: 'form',
                labelAlign: 'top',
                width: '100%',
                items: items
            }];
        }

        tabs.splice(tab.index, 0, {
            title: tab.name,
            layout: 'form',
            bodyCssClass: 'main-wrapper',
            cls: 'modx-extrafields-tab extrafields-' + (tab.categories.length ? 'vtabs' : 'tab'),
            id: 'modx-extrafields-tab-' + tab.id,
            hideMode: 'offsets',
            items: items
        });

    });
});