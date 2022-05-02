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
                    $results = [];
                    foreach ($field['abs'] as $abs) {
                        // templates
                        if (!empty($abs['ab_templates']) && !in_array($resource->template, explode(',', $abs['ab_templates']))) {
                            continue;
                        }
//
//                      // parents
                        if (!empty($abs['ab_parents']) && !in_array($resource->parent, explode(',', $abs['ab_parents']))) {
                            continue;
                        }

                        // resource
                        if (!empty($abs['ab_resources']) && !in_array($resource->id, explode(',', $abs['ab_resources']))) {
                            continue;
                        }
                        if (count($results)) continue;
                        $results = $this->extrafields->getFetchAll('pbTableValue', [
                            'resource_id' => $resource->id,
                            'constructor_id' => 0,
                            'table_id' => $abs['table_id'],
                            'field_id' => 0,
                            'ef_field_id' => $field['id'],
                            'parent_id' => 0
                        ]);
                    }

                    $values = [];
                    foreach ($results as $result) {
                        $values[] = json_decode($result['values'], 1);
                    }
                    $resource->set($field['name'], json_encode($values, JSON_UNESCAPED_UNICODE));
                    break;
            }
        }

    }
}