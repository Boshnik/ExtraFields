<?php
/** @var modX $modx */

if ($modx->event->name == 'OnUserFormPrerender') {
    if ($mode == 'upd') {
        $extrafields = $modx->getService('extrafields', 'ExtraFields', MODX_CORE_PATH . 'components/extrafields/model/');
        $extrafields->loadRichTextEditor();
        $usercolumns = json_encode($extrafields->getUserColumns(),1);
        $modx->controller->addHtml("<script>
            let ExtraFields = {};
            ExtraFields.userfields = $usercolumns;
        </script>");

        $modx->controller->addLastJavascript('/assets/components/extrafields/js/mgr/user/fields.js');
    }
}

if ($modx->event->name == 'OnMODXInit') {
    $extrafields = $modx->getService('extrafields', 'ExtraFields', MODX_CORE_PATH . 'components/extrafields/model/');
    $usercolumns = $extrafields->getUserColumns();

    $userClass = modUserProfile::class;
    $modx->loadClass($userClass);
    foreach ($usercolumns as $column) {
        $modx->map[$userClass]['fields'][$column['name']] = $column['default'];
        $modx->map[$userClass]['fieldMeta'][$column['name']] = array(
            'dbtype' => $column['dbtype'],
            'precision' => $column['precision'],
            'phptype' => $column['phptype'],
            'null' => $column['null'],
            'default' => $column['default'],
        );
    }
}