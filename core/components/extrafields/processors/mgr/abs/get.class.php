<?php

class efFieldAbsGetProcessor extends modObjectGetProcessor
{
    public $classKey = efFieldAbs::class;
    public $objectType = 'ef_field';
    public $languageTopics = ['extrafields:default'];
    //public $permission = 'view';


    /**
     * Return the response
     * @return array
     */
    public function cleanup() {
        $array = $this->object->toArray();
        if (!empty($array['ab_templates'])) {
            $array['ab_templates'] = explode('||', $array['ab_templates']);
        }
        if (!empty($array['ab_user_group'])) {
            $array['ab_user_group'] = explode('||', $array['ab_user_group']);
        }
        $array['hide_time'] = $array['hide_time'] ? 1 : 0;

        return $this->success('', $array);
    }

}

return 'efFieldAbsGetProcessor';