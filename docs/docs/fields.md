# Fields

## Text field

SQL:
```sql
ALTER TABLE `modx_site_content` ADD `field_name` varchar(255) NULL;
```

Output:

[![resource field](/img/resource_field.jpg)](/img/resource_field.jpg){data-fancybox="field"}

## Textarea

SQL:
```sql
ALTER TABLE `modx_site_content` ADD `field_name` text NULL;
```

Output:

[![field textarea](/img/field_textarea.jpg)](/img/field_textarea.jpg){data-fancybox="field"}

## Text editor

SQL:
```sql
ALTER TABLE `modx_site_content` ADD `field_name` mediumtext NULL;
```

Output:

[![field texteditor](/img/field_texteditor.jpg)](/img/field_texteditor.jpg){data-fancybox="field"}

## ACE

SQL:
```sql
ALTER TABLE `modx_site_content` ADD `field_name` mediumtext NULL;
```

Output:

[![field ace](/img/field_ace.jpg)](/img/field_ace.jpg){data-fancybox="field"}

## Listbox

SQL:
```sql
ALTER TABLE `modx_site_content` ADD `field_name` varchar(255) NULL;
```

Output:

[![field listbox](/img/field_listbox.jpg)](/img/field_listbox.jpg){data-fancybox="field"}

## Listbox (multiple)

SQL:
```sql
ALTER TABLE `modx_site_content` ADD `field_name` text NULL;
```

Output:

[![field listbox_multiple](/img/field_listbox_multiple.jpg)](/img/field_listbox_multiple.jpg){data-fancybox="field"}

## Resource list

SQL:
```sql
ALTER TABLE `modx_site_content` ADD `field_name` varchar(255) NULL;
```

Output:

[![field resourcelist](/img/field_resourcelist.jpg)](/img/field_resourcelist.jpg){data-fancybox="field"}

## Yes/No list

SQL:
```sql
ALTER TABLE `modx_site_content` ADD `field_name` tinyint(1) NOT NULL;
```

Output:

[![field yesno](/img/field_yesno.jpg)](/img/field_yesno.jpg){data-fancybox="field"}

## Numeric field

SQL:
```sql
ALTER TABLE `modx_site_content` ADD `field_name` int(1) NULL;
```

Output:

[![field numeric](/img/field_numeric.jpg)](/img/field_numeric.jpg){data-fancybox="field"}

## Price field

SQL:
```sql
ALTER TABLE `modx_site_content` ADD `field_name` decimal(12,2) NULL;
```

Output:

[![field price](/img/field_price.jpg)](/img/field_price.jpg){data-fancybox="field"}

## Checkbox field

SQL:
```sql
ALTER TABLE `modx_site_content` ADD `field_name` tinyint(1) NOT NULL;
```

Output:

[![field checkbox](/img/field_checkbox.jpg)](/img/field_checkbox.jpg){data-fancybox="field"}

## Checkbox group field

SQL:
```sql
ALTER TABLE `modx_site_content` ADD `field_name` varchar(255) NULL;
```

Output:

[![field checkbox_group](/img/field_checkbox_group.jpg)](/img/field_checkbox_group.jpg){data-fancybox="field"}

## Radio button

SQL:
```sql
ALTER TABLE `modx_site_content` ADD `field_name` varchar(255) NULL;
```

Output:

[![field radio](/img/field_radio.jpg)](/img/field_radio.jpg){data-fancybox="field"}

## Image field

SQL:
```sql
ALTER TABLE `modx_site_content` ADD `field_name` varchar(255) NULL;
```

Output:

[![field image](/img/field_image.jpg)](/img/field_image.jpg){data-fancybox="field"}

## File field

SQL:
```sql
ALTER TABLE `modx_site_content` ADD `field_name` varchar(255) NULL;
```

Output:

[![field image](/img/field_image.jpg)](/img/field_image.jpg){data-fancybox="field"}


## Gallery field

Доступно, если установлен компонент PageBlocks (PRO)

SQL:
```sql
ALTER TABLE `modx_site_content` ADD `field_name` mediumtext NULL;
```

Output:

[![field gallery](/img/field_gallery.jpg)](/img/field_gallery.jpg){data-fancybox="field"}

## Video field

Доступно, если установлен компонент PageBlocks (PRO)

SQL:
```sql
ALTER TABLE `modx_site_content` ADD `field_name` text NULL;
```

Output:

[![field video](/img/field_video.jpg)](/img/field_video.jpg){data-fancybox="field"}


## DateTime field

SQL:
```sql
ALTER TABLE `modx_site_content` ADD `field_name` int(20) NULL;
```

Output:

[![field date](/img/field_date.jpg)](/img/field_date.jpg){data-fancybox="field"}

## Reading only

SQL:
```sql
ALTER TABLE `modx_site_content` ADD `field_name` varchar(255) NULL;
```

Output:

[![field readonly](/img/field_readonly.jpg)](/img/field_readonly.jpg){data-fancybox="field"}

## Hidden Field

SQL:
```sql
ALTER TABLE `modx_site_content` ADD `field_name` varchar(255) NULL;
```

Output:

Не отображается.

## Xtype field

В настройке поля указываете любое xtype поле, например modx-combo-template

SQL:
```sql
ALTER TABLE `modx_site_content` ADD `field_name` varchar(255) NULL;
```

Output:

[![field xtype](/img/field_xtype.jpg)](/img/field_xtype.jpg){data-fancybox="field"}