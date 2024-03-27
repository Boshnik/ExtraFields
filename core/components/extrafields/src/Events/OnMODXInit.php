<?php

namespace Boshnik\ExtraFields\Events;

use Boshnik\ExtraFields\Processors\QueryProcessor;
use Boshnik\ExtraFields\Processors\HelpProcessor;

/**
 * class OnMODXInit
 */
class OnMODXInit extends Event
{
    use QueryProcessor;
    use HelpProcessor;

    public function run()
    {

        $rows = [
            [
                'className' => $this->modxversion === '3' ? \MODX\Revolution\modResource::class : \modResource::class,
                'fields' => $this->getFields()
            ], [
                'className' => $this->modxversion === '3' ? \MODX\Revolution\modUserProfile::class : \modUserProfile::class,
                'fields' => $this->getFields('modUserProfile')
            ], [
                'className' => \pbBlockValue::class,
                'fields' => $this->getFields('pbBlockValue')
            ], [
                'className' => \pbTableValue::class,
                'fields' => $this->getFields('pbTableValue')
            ]
        ];

        foreach ($rows as $row) {
            if (!count($row['fields'])) continue;
            $this->modx->loadClass($row['className']);
            $map = $this->modx->map[$row['className']];
            foreach ($row['fields'] as $field) {
                $meta = array_key_exists($field['field_type'], $this->fieldmeta) ? $this->fieldmeta[$field['field_type']] : $this->fieldmeta['richtext'];
                $map['fields'][$field['field_name']] = $field['field_default'];
                $map['fieldMeta'][$field['field_name']] = [
                    'dbtype' => $meta['dbtype'],
                    'phptype' => $meta['phptype'],
                    'null' => $field['field_null'],
                    'default' => $field['field_default'],
                ];
                if (isset($meta['precision'])) {
                    $map['fieldMeta'][$field['field_name']]['precision'] = $meta['precision'];
                }
            }

            $this->modx->map[$row['className']] = $map;
        }

    }
}