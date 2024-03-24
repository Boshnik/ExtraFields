<?php

class efFieldGetProcessor extends modObjectGetProcessor
{
    public $classKey = efField::class;
    public $objectType = 'ef_field';
    public $languageTopics = ['extrafields:default'];

}

return 'efFieldGetProcessor';