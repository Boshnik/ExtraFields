<?php

class efCategoryGetProcessor extends modObjectGetProcessor
{
    public $classKey = efCategory::class;
    public $objectType = 'ef_category';
    public $languageTopics = ['extrafields:default'];

}

return 'efCategoryGetProcessor';