<?php

class efFieldAbsUpdateProcessor extends modObjectUpdateProcessor
{
    public $classKey = efFieldAbs::class;
    public $objectType = 'ef_field';
    public $languageTopics = ['extrafields'];

    /**
     * @return bool
     */
    public function beforeSet()
    {
        if (!empty($this->properties['ab_templates'])) {
            $this->properties['ab_templates'] = implode('||', $this->properties['ab_templates']);
        }
        if (!empty($this->properties['ab_user_group'])) {
            $this->properties['ab_user_group'] = implode('||', $this->properties['ab_user_group']);
        }

        return parent::beforeSet();
    }


}

return 'efFieldAbsUpdateProcessor';
