<?php

class efTabCreateProcessor extends modObjectCreateProcessor
{
    public $classKey = efTab::class;
    public $objectType = 'ef_tab';
    public $languageTopics = ['extrafields'];
    //public $permission = 'create';


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $name = trim($this->getProperty('name'));
        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('ef_tab_err_name'));
        } elseif ($this->modx->getCount($this->classKey, [
            'name' => $name,
            'class_name' => $this->getProperty('class_name'),
        ])) {
            $this->modx->error->addField('name', $this->modx->lexicon('ef_tab_err_ae'));
        }

        $this->properties['ab_templates'] = implode('||', $this->getProperty('ab_templates'));
        $this->properties['ab_user_group'] = implode('||', $this->getProperty('ab_user_group'));

        return parent::beforeSet();
    }

    /**
     * @return bool
     */
    public function beforeSave()
    {
        $this->object->fromArray([
            'rank' => $this->modx->getCount($this->classKey, [
                'class_name' => $this->getProperty('class_name'),
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