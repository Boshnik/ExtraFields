<?php

class efCategoryEnableProcessor extends modObjectUpdateProcessor
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
            'active' => 1,
        ];

        return true;
    }

}

return 'efCategoryEnableProcessor';
