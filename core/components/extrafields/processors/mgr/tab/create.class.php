<?php

class efTabCreateProcessor extends modObjectCreateProcessor
{
    public $classKey = efTab::class;
    public $objectType = 'ef_tab';
    public $languageTopics = ['extrafields'];


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $name = trim($this->properties['name']);
        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('ef_tab_err_name'));
        } elseif ($this->modx->getCount($this->classKey, [
            'name' => $name,
            'class_name' => $this->properties['class_name'],
        ])) {
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

    /**
     * @return bool
     */
    public function beforeSave()
    {
        $this->object->fromArray([
            'menuindex' => $this->modx->getCount($this->classKey, [
                'class_name' => $this->properties['class_name'],
            ]),
        ]);

        return true;
    }

    /**
     * @return bool
     */
    public function afterSave()
    {
        $categories = $this->modx->getIterator(efCategory::class, ['tab_id' => 0]);
        foreach ($categories as $category) {
            $category->set('tab_id', $this->object->id);
            $category->save();
        }

        return true;
    }

}

return 'efTabCreateProcessor';