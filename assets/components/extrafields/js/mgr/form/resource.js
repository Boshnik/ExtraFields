Ext.ComponentMgr.onAvailable('modx-resource-tabs', function() {
    let tabs = this.items;

    tabs.forEach(function(tab) {
        if (tab.id == 'modx-resource-settings') {
            tab.items.forEach(function(field) {
                if (field.id == 'modx-resource-main-columns') {
                    let column = field.items;
                    ExtraFields.resourcefields.forEach(function(field) {
                        if (field.position == 0 || field.position == 255) {
                            let position = field.position == 255 ? 1 : 0;
                            column[position].items.splice(field.index, 0, ExtraFields.getXtype(field))
                        }
                    })
                }
            })
        }
    })

    ExtraFields.resourcetabs.forEach(function(tab) {

        let items = [];
        ExtraFields.resourcefields.forEach(function(field) {
            if (tab.id == field.position) {
                items.push(ExtraFields.getXtype(field));
            }
        });

        tabs.splice(tab.index, 0, {
            title: tab.name,
            layout: 'form',
            bodyCssClass: 'main-wrapper',
            cls: 'modx-resource-tab',
            id: 'modx-resource-extrafields-' + tab.id,
            hideMode: 'offsets',
            items: {
                layout: 'form',
                labelAlign: 'top',
                width: '100%',
                items: items
            }
        });

    });
});