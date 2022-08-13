<?php

class efTabEnableProcessor extends modObjectUpdateProcessor
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
            'active' => 1,
        ];

        return true;
    }

}

return 'efTabEnableProcessor';
