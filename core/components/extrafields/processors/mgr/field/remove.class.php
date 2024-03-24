<?php

use Boshnik\ExtraFields\Processors\HelpProcessor;

class efFieldRemoveProcessor extends modObjectRemoveProcessor
{
    use HelpProcessor;

    public $classKey = efField::class;
    public $objectType = 'ef_field';
    public $languageTopics = ['extrafields'];


    /**
     * @return bool
     */
    public function afterRemove()
    {
        $this->removeTableColumn($this->object);

        return true;
    }

}

return 'efFieldRemoveProcessor';