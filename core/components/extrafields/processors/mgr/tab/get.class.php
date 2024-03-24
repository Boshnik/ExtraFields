<?php

class efTabGetProcessor extends modObjectGetProcessor
{
    public $classKey = efTab::class;
    public $objectType = 'ef_tab';
    public $languageTopics = ['extrafields:default'];

}

return 'efTabGetProcessor';