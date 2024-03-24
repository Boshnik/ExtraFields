<?php

namespace Boshnik\ExtraFields\Events;

use Boshnik\ExtraFields\Processors\QueryProcessor;
/**
 * class OnDocFormSave
 */
class OnDocFormSave extends Event
{
    use QueryProcessor;

    public function run()
    {
        /** @var \modResource $resource */
        $resource = $this->scriptProperties['resource'];

        $fields = $this->getFields();
        foreach ($fields as $field) {
            unset($resource->_fields[$field['name']]);
        }
    }
}