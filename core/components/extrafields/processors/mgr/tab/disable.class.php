<?php

class efTabDisableProcessor extends modObjectUpdateProcessor
{

    public $classKey = efTab::class;
    public $objectType = 'ef_tab';
    public $languageTopics = ['extrafields'];


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $this->properties = [
            'active' => 0,
        ];

        return true;
    }
}

return 'efTabDisableProcessor';
