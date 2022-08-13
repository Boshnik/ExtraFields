<?php

class efCategoryDisableProcessor extends modObjectUpdateProcessor
{
    public $classKey = efCategory::class;
    public $objectType = 'ef_category';
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

return 'efCategoryDisableProcessor';
