<?php
/** @var modX $modx */

switch ($modx->event->name) {

    case 'OnDocFormPrerender':
        $extrafields = $modx->getService('extrafields', 'ExtraFields', MODX_CORE_PATH . 'components/extrafields/model/');
        $extrafields->loadRichTextEditor();
        $resourcetabs = json_encode($extrafields->getResourceTabs(),1);
        $modx->controller->addHtml("<script>
                let ExtraFields = {};
                ExtraFields.resourcetabs = $resourcetabs;
            </script>");
        $modx->controller->addLastJavascript('/assets/components/extrafields/js/mgr/form/resource.js');
        break;

    case 'OnUserFormPrerender':
        $extrafields = $modx->getService('extrafields', 'ExtraFields', MODX_CORE_PATH . 'components/extrafields/model/');
        $extrafields->loadRichTextEditor();
        $usercolumns = json_encode($extrafields->getUserColumns(),1);
        $modx->controller->addHtml("<script>
                let ExtraFields = {};
                ExtraFields.userfields = $usercolumns;
            </script>");

        $modx->controller->addLastJavascript('/assets/components/extrafields/js/mgr/form/user.js');
        break;

    case 'OnMODXInit':
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
        break;
}