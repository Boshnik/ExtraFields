**Language:** English, **[Russian](/README.md)**

# ExtraFields
The component is designed to add new fields to the resource and user profile.

## Resource (modResource)

When a field is created, the corresponding column is added to the site_content (modResource) table.
When updating, you can change everything except the name of the field itself in the table.
Deleting a field deletes a column in the table.

### Field setting
![resource-field](https://user-images.githubusercontent.com/6839253/151792034-e6677229-45d4-4e8b-94ff-1c81f3cbdbdd.png)

- **Field name** - table column name.
- **label field** - the title of the field that is displayed above the field.
- **Location** - the place where the field will be inserted. The main resource tab has 2 columns: left (modx-resource-main-left) and right (modx-resource-main-right). You can place the field in any column or in your tab.
- **Index** - the position of the field relative to other fields. If 0, then the field will be displayed at the very top, above all other fields in this column or tab.
- **Field type** - the type of the displayed field. It can be a text or numeric field, it can be a checkbox, etc. See the **Fields** section.
- **Required field** - prevents the resource from being saved if the field is empty.
- **Active** - show field in resource or not.


## Resource tabs

To create additional tabs, you need to go to the administration panel (button at the top right).

### Tab setting
![resource-tab](https://user-images.githubusercontent.com/6839253/151792154-42216a9f-5ada-4e8f-9be1-9ac83fa28eb3.png)

- **Name** - tab name
- **Position** - the position of the tab in relation to other tabs. 0 - will be displayed at the very beginning, and 99 - at the very end.
- **Active** - show tab in resource or not.


## User profile (modUserProfile)

When a field is created, the corresponding column is added to the user_attributes (modUserProfile) table.

### Field setting
![user-field](https://user-images.githubusercontent.com/6839253/151792225-d811b26d-c804-4558-8575-2663da1d5074.png)

- **Field name** - table column name.
- **label field** - the title of the field that is displayed above the field.
- **Location** - the place where the field will be added on the main tab of the user profile.
- **Index** - the position of the field relative to other fields. If 0, then the field will be displayed at the very top, above all other fields in this column or tab.
- **Field type** - the type of the displayed field. It can be a text or numeric field, it can be a checkbox, etc. See the **Fields** section.
- **Required field** - prevents the user from saving if the field is empty.
- **Active** - show field in resource or not.


## Fields

Table of possible field types. To edit, go to the administration panel.
Available fields:
 - [textfield](http://cdn.sencha.com/ext/gpl/3.4.1.1/docs/#!/api/Ext.form.TextField)
 - [textarea](http://cdn.sencha.com/ext/gpl/3.4.1.1/docs/#!/api/Ext.form.TextArea)
 - richtext (textarea + editor)
 - [numberfield](http://cdn.sencha.com/ext/gpl/3.4.1.1/docs/#!/api/Ext.form.NumberField)
 - [xcheckbox](http://cdn.sencha.com/ext/gpl/3.4.1.1/docs/#!/api/Ext.form.Checkbox)
 - [combo-boolean](http://cdn.sencha.com/ext/gpl/3.4.1.1/docs/#!/api/Ext.form.ComboBox)
 - [datefield](http://cdn.sencha.com/ext/gpl/3.4.1.1/docs/#!/api/Ext.form.DateField)
 - [timefield](http://cdn.sencha.com/ext/gpl/3.4.1.1/docs/#!/api/Ext.form.TimeField)
 - xdatetime (datefield + timefield)
 - [displayfield](http://cdn.sencha.com/ext/gpl/3.4.1.1/docs/#!/api/Ext.form.DisplayField)
 - readonly (textfield + readOnly true)

You can also add your own fields.

### Field setting
![fields](https://user-images.githubusercontent.com/6839253/151792289-86f21a3a-a3e4-4ea9-a576-22720c714d68.png)

- **Name** - xtype field
- **dbtype** - field type in the database
- **precision** - field size
- **phptype** - variable type in php
- **null** - whether the field can be null or not.
- **default** - default value