Ext.ComponentMgr.onAvailable('modx-user-tabs', function() {
    let column = this.items[0].items[0].items;
    if (ExtraFields.userfields) {
        ExtraFields.userfields.forEach(function(field) {
            column[field.position].items.splice(field.index, 0, ExtraFields.getXtype(field));
        })
    }
});