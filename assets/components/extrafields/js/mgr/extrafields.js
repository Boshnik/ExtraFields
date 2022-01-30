var ExtraFields = function (config) {
    config = config || {};
    ExtraFields.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields, Ext.Component, {
    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, form: {}, config: {}, view: {}, utils: {}
});
Ext.reg('extrafields', ExtraFields);
ExtraFields = new ExtraFields();