<?php

$fields = [
    'textfield' => [
        'dbtype' => 'varchar',
        'precision' => '255',
        'phptype' => 'string',
        'null' => true,
        'default' => null,
    ],
    'textarea' => [
        'dbtype' => 'text',
        'phptype' => 'string',
        'null' => true,
        'default' => null,
    ],
    'richtext' => [
        'dbtype' => 'text',
        'phptype' => 'string',
        'null' => true,
        'default' => null,
    ],
    'numberfield' => [
        'dbtype' => 'int',
        'precision' => 10,
        'attributes' => 'unsigned',
        'phptype' => 'integer',
        'null' => false,
        'default' => 0,
    ],
    'xcheckbox' => [
        'dbtype' => 'tinyint',
        'precision' => 1,
        'attributes' => 'unsigned',
        'phptype' => 'integer',
        'null' => false,
        'default' => 0,
    ],
    'combo-boolean' => [
        'dbtype' => 'tinyint',
        'precision' => 1,
        'attributes' => 'unsigned',
        'phptype' => 'boolean',
        'null' => false,
        'default' => 0,
    ],
    'datefield' => [
        'dbtype' => 'int',
        'precision' => 20,
        'phptype' => 'timestamp',
        'null' => false,
        'default' => 0,
    ],
    'timefield' => [
        'dbtype' => 'varchar',
        'precision' => '100',
        'phptype' => 'string',
        'null' => true,
        'default' => null,
    ],
    'xdatetime' => [
        'dbtype' => 'int',
        'precision' => 20,
        'phptype' => 'timestamp',
        'null' => false,
        'default' => 0,
    ],
    'displayfield' => [
        'dbtype' => 'varchar',
        'precision' => '255',
        'phptype' => 'string',
        'null' => true,
        'default' => null,
    ],
    'readonly' => [
        'dbtype' => 'varchar',
        'precision' => '255',
        'phptype' => 'string',
        'null' => true,
        'default' => null,
    ],
];

/** @var xPDOTransport $transport */
/** @var array $options */
/** @var modX $modx */
if ($transport->xpdo) {
    $modx =& $transport->xpdo;
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:

            $modx->addPackage('extrafields', MODX_CORE_PATH . 'components/extrafields/model/');

            foreach ($fields as $name => $field) {

                if ($modx->getCount(ExtraMetaField::class, ['name' => $name])) {
                    continue;
                }

                $row = $modx->newObject(ExtraMetaField::class, ['name' => $name]);
                $row->fromArray($field);
                $row->save();
            }

            break;
        case xPDOTransport::ACTION_UNINSTALL:
            break;
    }
}
return true;