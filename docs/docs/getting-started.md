# Getting Started

## Create field

[![create field](/img/create_field.jpg)](/img/create_field.jpg){data-fancybox="field"}

При сохранении будет создано поле в базе данных (class modxResource):

SQL:
```sql
ALTER TABLE `modx_site_content` ADD `author` varchar(255) NULL DEFAULT 'Boshnik';
```

Подробнее обо всех поля можно ознакомиться в этом [разделе](/docs/fields).

## Settings field

После создания поля можно вывести его в соответсвующую панель(ресурс или пользователь). Для этого нужно дополнительно настроить его.

[![settings field](/img/settings_field.jpg)](/img/settings_field.jpg){data-fancybox="field"}

Теперь это поле будет добавлено в соответсвующий раздел.

[![resource field](/img/resource_field.jpg)](/img/resource_field.jpg){data-fancybox="field"}

Кроме стандартных вкладок, вы можете создать [свои собственные](/docs/tabs) и там же разместить эти поля.

### Availability
Criteria by which the availability of a field in the resource will be determined
 - Templates (можно выбрать несколько шаблонов)
 - Parents (список id ресурсов через запятую, которые являются родителями)
 - Resources (список id ресурсов через запятую)

Для страницы пользователя свои критерии:
 - Groups of users (можно выбрать несколько групп)
 - Users (список id пользователей через запятую)