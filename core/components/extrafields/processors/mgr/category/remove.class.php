<?php

class efCategoryRemoveProcessor extends modObjectRemoveProcessor
{
    public $classKey = efCategory::class;
    public $objectType = 'ef_category';
    public $languageTopics = ['extrafields'];

}

return 'efCategoryRemoveProcessor';