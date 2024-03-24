<?php

class efTabRemoveProcessor extends modObjectRemoveProcessor
{
    public $classKey = efTab::class;
    public $objectType = 'ef_tab';
    public $languageTopics = ['extrafields'];

}

return 'efTabRemoveProcessor';