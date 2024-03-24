<?php

namespace Boshnik\ExtraFields\Events;

use Boshnik\ExtraFields\Processors\QueryProcessor;

/**
 * class OnBeforeDocFormSave
 */
class OnBeforeDocFormSave extends Event
{
    use QueryProcessor;

    public function run()
    {
        if ($this->scriptProperties['mode'] == 'new') return;

        /** @var \modResource $resource */
        $resource = $this->scriptProperties['resource'];
        $fields = $this->getFields();

        foreach ($fields as $field) {
            switch ($field['field_type']) {
                case 'listbox-multiple':
                case 'checkboxgroup':
                    $value = $resource->get($field['field_name']);
                    if (is_array($value)) {
                        $value = implode('||', $value);
                    }
                    $resource->set($field['field_name'], $value);
                    break;
//                case 'pb-table':
//                    $results = [];
//                    foreach ($field['abs'] as $abs) {
//                        // templates
//                        if (!empty($abs['ab_templates']) && !in_array($resource->template, explode(',', $abs['ab_templates']))) {
//                            continue;
//                        }
//
//                       // parents
//                        if (!empty($abs['ab_parents']) && !in_array($resource->parent, explode(',', $abs['ab_parents']))) {
//                            continue;
//                        }
//
//                        // resource
//                        if (!empty($abs['ab_resources']) && !in_array($resource->id, explode(',', $abs['ab_resources']))) {
//                            continue;
//                        }
//                        if (count($results)) continue;
//                        $results = $this->getFetchAll('pbTableValue', [
//                            'model_type' => $resource->_class,
//                            'model_id' => $resource->id,
//                            'constructor_id' => 0,
//                            'table_id' => $abs['table_id'],
//                            'field_id' => 0,
//                            'ef_field_id' => $field['id'],
//                            'published' => 1
//                        ]);
//                    }
//
//                    $values = [];
//                    foreach ($results as $result) {
//                        $values[] = json_decode($result['values'], 1);
//                    }
//                    $resource->set($field['field_name'], json_encode($values, JSON_UNESCAPED_UNICODE));
//                    break;

                case 'pb-gallery':
                    $results = [];
                    foreach ($field['abs'] as $abs) {
                        // templates
                        if (!empty($abs['ab_templates']) && !in_array($resource->template, explode(',', $abs['ab_templates']))) {
                            continue;
                        }

                        // parents
                        if (!empty($abs['ab_parents']) && !in_array($resource->parent, explode(',', $abs['ab_parents']))) {
                            continue;
                        }

                        // resource
                        if (!empty($abs['ab_resources']) && !in_array($resource->id, explode(',', $abs['ab_resources']))) {
                            continue;
                        }
                        if (count($results)) continue;

                        $results = $this->getFetchAll('pbFile', [
                            'model_type' => $resource->_class,
                            'model_id' => $resource->id,
                            'field_id' => 0,
                            'ef_field_id' => $field['id'],
                            'published' => 1
                        ]);
                    }

                    $values = [];
                    foreach ($results as $result) {
                        $values[] = [
                            'path' => $result['path'],
                            'filename' => $result['filename'],
                            'name' => $result['name'],
                            'title' => $result['title'],
                            'description' => $result['description'],
                            'url' => $result['url'],
                            'type' => $result['type'],
                        ];
                    }
                    $resource->set($field['field_name'], json_encode($values, JSON_UNESCAPED_UNICODE));
                    break;
            }
        }

    }
}