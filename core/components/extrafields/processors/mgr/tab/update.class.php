<?php

class efTabUpdateProcessor extends modObjectUpdateProcessor
{
    public $classKey = efTab::class;
    public $objectType = 'ef_tab';
    public $languageTopics = ['extrafields'];


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $id = (int)$this->properties['id'];
        $name = trim($this->properties['name']);
        if (empty($id)) {
            return $this->modx->lexicon('ef_tab_err_ns');
        }

        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('ef_tab_err_name'));
        } elseif ($this->modx->getCount($this->classKey, ['name' => $name, 'id:!=' => $id])) {
            $this->modx->error->addField('name', $this->modx->lexicon('ef_tab_err_ae'));
        }

        if ($this->properties['ab_templates']) {
            $this->properties['ab_templates'] = implode('||', $this->properties['ab_templates']);
        }
        if ($this->properties['ab_user_group']) {
            $this->properties['ab_user_group'] = implode('||', $this->properties['ab_user_group']);
        }

        return parent::beforeSet();
    }
}

return 'efTabUpdateProcessor';
