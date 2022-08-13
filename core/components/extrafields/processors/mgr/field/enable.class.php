<?php

class efFieldEnableProcessor extends modObjectUpdateProcessor
{
    public $classKey = efField::class;
    public $objectType = 'ef_field';
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

return 'efFieldEnableProcessor';
