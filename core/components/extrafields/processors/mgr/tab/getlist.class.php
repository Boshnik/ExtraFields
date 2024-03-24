<?php

class efTabGetListProcessor extends modObjectGetListProcessor
{
    public $classKey = efTab::class;
    public $objectType = 'ef_tab';
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'DESC';


    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        if (isset($this->properties['class_name'])) {
            $c->where([
                'class_name' => trim($this->properties['class_name']),
            ]);
        }

        $query = trim($this->properties['query']);
        if ($query) {
            $c->where([
                'name:LIKE' => "%{$query}%",
                'OR:description:LIKE' => "%{$query}%",
            ]);
        }

        if ($this->properties['combo']) {
            $c->where(['active' => 1]);
            $c->select('id,name');
        }

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
                'id' => 'modx-ef-tab-' . $object->id,
                'name' => $object->name,
            ];

            return $array;
        }

        $array = $object->toArray();
        $array['actions'] = [];

        // Edit
        $array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-edit',
            'title' => $this->modx->lexicon('ef_row_update'),
            'action' => 'updateObject',
            'button' => true,
            'menu' => true,
        ];

        if (!$array['active']) {
            $array['actions'][] = [
                'cls' => '',
                'icon' => 'icon icon-power-off action-green',
                'title' => $this->modx->lexicon('ef_row_enable'),
                'multiple' => $this->modx->lexicon('ef_rows_enable'),
                'action' => 'enableObject',
                'button' => true,
                'menu' => true,
            ];
        } else {
            $array['actions'][] = [
                'cls' => '',
                'icon' => 'icon icon-power-off action-gray',
                'title' => $this->modx->lexicon('ef_row_disable'),
                'multiple' => $this->modx->lexicon('ef_rows_disable'),
                'action' => 'disableObject',
                'button' => true,
                'menu' => true,
            ];
        }

        // Remove
        $array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-trash-o action-red',
            'title' => $this->modx->lexicon('ef_row_remove'),
            'multiple' => $this->modx->lexicon('ef_rows_remove'),
            'action' => 'removeObject',
            'button' => true,
            'menu' => true,
        ];

        return $array;
    }

    public function outputArray(array $array,$count = false) {
        if ($count === false) { $count = count($array); }

        if ($this->properties['combo']) {
            switch ($this->properties['class_name']) {
                case 'modResource':
                    $array = array_merge([
                        ['id' => 'modx-resource-settings', 'name' => $this->modx->lexicon('modx-resource-settings')],
                        ['id' => 'modx-page-settings', 'name' => $this->modx->lexicon('modx-page-settings')],
                        ['id' => 'modx-resource-access-permissions', 'name' => $this->modx->lexicon('modx-resource-access-permissions')],
                    ], $array);
                    break;
                case 'modUserProfile':
                    $array = array_merge([
                        ['id' => 'user_tab_0', 'name' => $this->modx->lexicon('user_tab_0')],
                        ['id' => 'user_tab_1', 'name' => $this->modx->lexicon('user_tab_1')],
                        ['id' => 'user_tab_2', 'name' => $this->modx->lexicon('user_tab_2')],
                        ['id' => 'user_tab_3', 'name' => $this->modx->lexicon('user_tab_3')],
                    ], $array);
                    break;
            }
        }

        return '{"success":true,"total":"'.$count.'","results":'.$this->modx->toJSON($array).'}';
    }

}

return 'efTabGetListProcessor';