<?php
/** @var modX $modx */

if ($modx->services instanceof Psr\Http\Client\ClientInterface) {
    $extrafields = $modx->services->get('extrafields');
} else {
    $extrafields = $modx->getService('extrafields', 'ExtraFields', MODX_CORE_PATH . 'components/extrafields/model/');
}
$modxversion = $extrafields->config['modxversion'];

switch ($modx->event->name) {

    case 'OnDocFormPrerender':
        $resourcetabs = json_encode($extrafields->getResourceTabs(),1);
        $resourcefields = json_encode($extrafields->getTableFields(ExtraResourceField::class),1);
        $modx->controller->addHtml("<script>
                let ExtraFields = {};
                ExtraFields.resourcetabs = $resourcetabs;
                ExtraFields.resourcefields = $resourcefields;
                ExtraFields.modxversion = $modxversion;
            </script>");
        $modx->controller->addLastJavascript('/assets/components/extrafields/js/mgr/misc/xtype.js');
        $modx->controller->addLastJavascript('/assets/components/extrafields/js/mgr/form/resource.js');
        break;

    case 'OnUserFormPrerender':
        $extrafields->loadRichTextEditor();
        $userfields = json_encode($extrafields->getTableFields(ExtraUserField::class),1);
        $modx->controller->addHtml("<script>
                let ExtraFields = {};
                ExtraFields.userfields = $userfields;
                ExtraFields.modxversion = $modxversion;
            </script>");

        $modx->controller->addLastJavascript('/assets/components/extrafields/js/mgr/misc/xtype.js');
        $modx->controller->addLastJavascript('/assets/components/extrafields/js/mgr/form/user.js');
        break;

    case 'OnMODXInit':

        $rows = [
            [
                'className' => $modxversion == 3 ? MODX\Revolution\modResource::class : modResource::class,
                'fields' => $extrafields->getTableFields(ExtraResourceField::class)
            ], [
                'className' => $modxversion == 3 ? MODX\Revolution\modUserProfile::class : modUserProfile::class,
                'fields' => $extrafields->getTableFields(ExtraUserField::class)
            ]
        ];

        foreach ($rows as $row) {
            if ($modxversion == 2) {
                $modx->loadClass($row['className']);
            }
            $map = $modx->map[$row['className']];
            foreach ($row['fields'] as $field) {
                $map['fields'][$field['name']] = $field['default'];
                $map['fieldMeta'][$field['name']] = array(
                    'dbtype' => $field['dbtype'],
                    'precision' => $field['precision'],
                    'phptype' => $field['phptype'],
                    'null' => $field['null'],
                    'default' => $field['default'],
                );
            }

            $modx->map[$row['className']] = $map;
        }

        break;

    case 'OnDocFormSave':
        /** @var modResource $resource */
        $fields = $extrafields->getTableFields(ExtraResourceField::class);
        foreach ($fields as $field) {
            unset($resource->_fields[$field['name']]);
        }

        break;
}