<?php

class efFieldAbsDisableProcessor extends modObjectUpdateProcessor
{
    public $classKey = efFieldAbs::class;
    public $objectType = 'ef_field';
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

return 'efFieldAbsDisableProcessor';
