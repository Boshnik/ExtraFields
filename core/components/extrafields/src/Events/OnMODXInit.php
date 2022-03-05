<?php

namespace Boshnik\ExtraFields\Events;

/**
 * class OnMODXInit
 */
class OnMODXInit extends Event
{
    public function run()
    {
        $rows = [
            [
                'className' => $this->modxversion == 3 ? \MODX\Revolution\modResource::class : \modResource::class,
                'fields' => $this->extrafields->getFields('modResource')
            ], [
                'className' => $this->modxversion == 3 ? \MODX\Revolution\modUserProfile::class : \modUserProfile::class,
                'fields' => $this->extrafields->getFields('modUserProfile')
            ]
        ];

        $extrafields = $this->extrafields;
        $FIELDMETA = $extrafields::FIELDMETA;
        foreach ($rows as $row) {
            if ($this->modxversion == 2) {
                $this->modx->loadClass($row['className']);
            }
            $map = $this->modx->map[$row['className']];
            foreach ($row['fields'] as $field) {
                $meta = array_key_exists($field['type'], $FIELDMETA) ? $FIELDMETA[$field['type']] : $FIELDMETA['textfield'];
                $map['fields'][$field['name']] = $meta['default'];
                $map['fieldMeta'][$field['name']] = [
                    'dbtype' => $meta['dbtype'],
                    'precision' => $meta['precision'],
                    'phptype' => $meta['phptype'],
                    'null' => $meta['null'],
                    'default' => $meta['default'],
                ];
            }

            $this->modx->map[$row['className']] = $map;
        }
    }
}