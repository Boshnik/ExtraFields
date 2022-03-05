<?php

namespace Boshnik\ExtraFields\Events;

/**
 * class OnDocFormSave
 */
class OnDocFormSave extends Event
{
    public function run()
    {
        /** @var \modResource $resource */
        $resource = $this->scriptProperties['resource'];

        $fields = $this->extrafields->getFields('modResource');
        foreach ($fields as $field) {
            unset($resource->_fields[$field['name']]);
        }
    }
}