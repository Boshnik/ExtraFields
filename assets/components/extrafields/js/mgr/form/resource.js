Ext.ComponentMgr.onAvailable('modx-resource-tabs', function() {
    let tabs = this.items;
    ExtraFields.resourcetabs.forEach(function(tab) {
        console.log(tab);
        tabs.splice(tab.index, 0, {
            title: tab.name,
            layout: 'form',
            bodyCssClass: 'main-wrapper',
            cls: 'modx-resource-tab',
            id: 'modx-resource-extrafields-' + tab.id,
            hideMode: 'offsets',
            items: []
        });
    });
});