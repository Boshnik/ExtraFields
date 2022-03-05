<?php

namespace Boshnik\ExtraFields\Events;

/**
 * class OnBeforeDocFormSave
 */
class OnBeforeDocFormSave extends Event
{
    public function run()
    {
        /** @var \modResource $resource */
        $resource = $this->scriptProperties['resource'];
        $fields = $this->extrafields->getFields('modResource');
        foreach ($fields as $field) {
            switch ($field['type']) {
                case 'listbox-multiple':
                case 'checkboxgroup':
                    $value = implode('||', $resource->get($field['name']));
                    $resource->set($field['name'], $value);
                    break;
                case 'pageblocks':
                    $values = [];
                    $results = $this->extrafields->getFetchAll('pbResourceTable', [
                        'resource_id' => $resource->id,
                        'table_id' => $field['table_id'],
                        'field_name' => $field['name']
                    ]);
                    foreach ($results as $result) {
                        $values[] = json_decode($result['values'], 1);
                    }
                    $resource->set($field['name'], json_encode($values, JSON_UNESCAPED_UNICODE));
                    break;
            }
        }

    }
}