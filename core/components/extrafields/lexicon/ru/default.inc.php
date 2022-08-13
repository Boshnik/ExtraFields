<?php
include_once 'tab.inc.php';
include_once 'category.inc.php';
include_once 'field.inc.php';

$_lang['extrafields'] = 'ExtraFields';
$_lang['extrafields_menu_desc'] = 'Дополнительные поля';
$_lang['extrafields_intro_msg'] = 'Вы можете выделять сразу несколько полей при помощи Shift или Ctrl.';

$_lang['extrafields_resource'] = 'Ресурс (modResource)';
$_lang['extrafields_resource_menu_desc'] = 'Управление полями ресурса';

$_lang['extrafields_user'] = 'Пользователь (modUserProfile)';
$_lang['extrafields_user_menu_desc'] = 'Управление полями пользователя';

$_lang['extrafields_pb_block'] = 'PageBlocks (pbBlockValue)';
$_lang['extrafields_pb_block_menu_desc'] = 'Управление полями блока';

$_lang['extrafields_pb_table'] = 'PageBlocks (pbTableValue)';
$_lang['extrafields_pb_table_menu_desc'] = 'Управление полями таблицы';

$_lang['ef_row_create'] = 'Создать';
$_lang['ef_row_update'] = 'Изменить';
$_lang['ef_row_enable'] = 'Включить';
$_lang['ef_rows_enable'] = 'Включить выбранные элементы';
$_lang['ef_row_disable'] = 'Отключить';
$_lang['ef_rows_disable'] = 'Отключить выбранные элементы';
$_lang['ef_row_remove'] = 'Удалить';
$_lang['ef_rows_remove'] = 'Удалить выбранные элементы';
$_lang['ef_row_remove_title'] = 'Удаление';
$_lang['ef_row_remove_confirm'] = 'Вы уверены, что хотите удалить этот элемент?';
$_lang['ef_rows_remove_confirm'] = 'Вы уверены, что хотите удалить эти элементы?';
$_lang['ef_row_active'] = 'Включено';

$_lang['ef_grid_active'] = 'Активно';
$_lang['ef_grid_search'] = 'Поиск';
$_lang['ef_grid_actions'] = 'Действия';

$_lang['ef_fields'] = 'Поля';
$_lang['ef_field_id'] = 'ID';
$_lang['ef_field_caption'] = 'Подпись';
$_lang['ef_field_name'] = 'Название поля';
$_lang['ef_field_name_desc'] = 'Допускаются только латинские буквы в нижнем регистре';
$_lang['ef_field_err_name_cyrillic'] = 'Ошибка в название поля';
$_lang['ef_field_err_name_reserved'] = 'Название поля уже занято в родной таблице';
$_lang['ef_field_type'] = 'Тип поля';
$_lang['ef_field_source_path'] = 'Путь к файлам';
$_lang['ef_field_width'] = 'Ширина поля';
$_lang['ef_field_width_desc'] = 'От 0 до 100';
$_lang['ef_field_values'] = 'Возможные значения';
$_lang['ef_field_values_desc'] = 'варианты, разделенные ||, например Кошка||Собака или Белый==#ffffff||Черный==#000000';
$_lang['ef_field_xtype'] = 'Xtype поле';
$_lang['ef_field_xtype_desc'] = 'Список полей - https://bobsguides.com/xtype-list.html';
$_lang['ef_field_default'] = 'Значение по умолчанию';
$_lang['ef_field_help'] = 'Описание поля';
$_lang['ef_field_required'] = 'Обязательное поле';
$_lang['ef_field_tab'] = 'Вкладка';
$_lang['ef_field_category'] = 'Категория';
$_lang['ef_field_sort'] = 'Поле для сортировки';
$_lang['ef_field_dir'] = 'Направление сортировки';
$_lang['ef_field_areas'] = 'Область';
$_lang['ef_field_index'] = 'Индекс';
$_lang['ef_field_active'] = 'Активно';

$_lang['ef_combo_empty'] = 'Нажмите для выбора';
$_lang['textfield'] = 'Текстовое поле';
$_lang['xcheckbox'] = 'Флажок';
$_lang['readonly'] = 'Только чтение';
$_lang['efxtype'] = 'Xtype поле';
$_lang['ef_table'] = 'Таблица PageBlocks';

$_lang['ef_settings'] = 'Настройки поля';
$_lang['ef_position'] = 'Расположение';
$_lang['ef_accessibility'] = 'Доступность';
$_lang['templates'] = 'Шаблоны';
$_lang['parents'] = 'Родители (ID)';
$_lang['ab_user_group'] = 'Группы пользователей';
$_lang['ab_users'] = 'Пользователи (ID)';

$_lang['modx-resource-settings'] = 'Документ';
$_lang['modx-resource-main-left'] = 'Документ (modx-resource-main-left)';
$_lang['modx-resource-main-right'] = 'Документ (modx-resource-main-right)';
$_lang['modx-page-settings'] = 'Настройки';
$_lang['modx-page-settings-left'] = 'Настройки (modx-page-settings-left)';
$_lang['modx-page-settings-right'] = 'Настройки (modx-page-settings-right)';
$_lang['modx-page-settings-right-box-left'] = 'Настройки (modx-page-settings-right-box-left)';
$_lang['modx-page-settings-right-box-right'] = 'Настройки (modx-page-settings-right-box-right)';
$_lang['modx-resource-access-permissions'] = 'Группы ресурсов';

$_lang['user_tab_0'] = 'Общая информация';
$_lang['user_tab_0_1'] = 'Общая информация (левая колонка)';
$_lang['user_tab_0_2'] = 'Общая информация (правая колонка)';
$_lang['user_tab_1'] = 'Настройки';
$_lang['user_tab_2'] = 'Права доступа';
$_lang['user_tab_3'] = 'Дополнительные поля данных';