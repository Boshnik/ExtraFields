<?php

class ExtraFieldsResourceGetListProcessor extends modObjectGetListProcessor
{
    public $classKey = 'modResource';
    public $languageTopics = array('resource');
    public $defaultSortField = 'pagetitle';


    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        if ($where = $this->properties['where']) {
            $where = json_decode($where,1);
            foreach ($where as $value) {
                $c->where($value);
            }
        }

        if ($this->properties['combo']) {
            $c->select('id,pagetitle');
        }
        if ($id = (int) $this->properties['id']) {
            $c->where(['id' => $id]);
        }
        if ($query = trim($this->properties['query'])) {
            $c->where(['pagetitle:LIKE' => "%{$query}%"]);
        }

        $this->modx->log(1, print_r($this->properties,1));

        return $c;
    }


    /**
     * @param xPDOObject $object
     *
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        if ($this->properties['combo']) {
            $array = [
                'id' => $object->id,
                'pagetitle' => '(' . $object->id . ') ' . $object->pagetitle,
            ];
        } else {
            $array = $object->toArray();
        }

        return $array;
    }
}

return 'ExtraFieldsResourceGetListProcessor';