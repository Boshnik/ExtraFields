<?php
/** @var modX $modx */

$extrafields = $modx->getService('extrafields', 'ExtraFields', MODX_CORE_PATH . 'components/extrafields/model/');
switch ($modx->event->name) {

    case 'OnDocFormPrerender':
        $extrafields->loadRichTextEditor();
        $resourcetabs = json_encode($extrafields->getResourceTabs(),1);
        $resourcefields = json_encode($extrafields->getTableFields(ExtraResourceField::class),1);
        $modx->controller->addHtml("<script>
                let ExtraFields = {};
                ExtraFields.resourcetabs = $resourcetabs;
                ExtraFields.resourcefields = $resourcefields;
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
            </script>");

        $modx->controller->addLastJavascript('/assets/components/extrafields/js/mgr/misc/xtype.js');
        $modx->controller->addLastJavascript('/assets/components/extrafields/js/mgr/form/user.js');
        break;

    case 'OnMODXInit':

        $mapClass = [
            'resource' => modResource::class,
            'user' => modUserProfile::class,
        ];

        if ($extrafields->config['modxversion'] == 3) {
            $mapClass = [
                'resource' => MODX\Revolution\modResource::class,
                'user' => MODX\Revolution\modUserProfile::class,
            ];
        }

        $rows = [
            [
                'className' => $mapClass['resource'],
                'fields' => $extrafields->getTableFields(ExtraResourceField::class)
            ], [
                'className' => $mapClass['user'],
                'fields' => $extrafields->getTableFields(ExtraUserField::class)
            ]
        ];

        foreach ($rows as $row) {
            $modx->loadClass($row['className']);
            foreach ($row['fields'] as $field) {
                $map = $modx->map[$row['className']];
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
}