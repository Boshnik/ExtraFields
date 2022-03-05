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
            }
        }

    }
}