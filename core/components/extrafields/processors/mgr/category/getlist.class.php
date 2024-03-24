<?php

class efCategoryGetListProcessor extends modObjectGetListProcessor
{
    public $classKey = efCategory::class;
    public $objectType = 'ef_category';
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'DESC';


    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->where([
            'tab_id' => (int) str_replace('modx-ef-tab-', '', $this->properties['tab_id'])
        ]);

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
                'id' => 'modx-ef-category-' . $object->id,
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
            switch ($this->properties['tab_id']) {
                case 'modx-resource-settings':
                    $array = array_merge([
                        ['id' => 'modx-resource-main-left', 'name' => $this->modx->lexicon('modx-resource-main-left')],
                        ['id' => 'modx-resource-main-right', 'name' => $this->modx->lexicon('modx-resource-main-right')],
                    ], $array);
                    break;
                case 'modx-page-settings':
                    $array = array_merge([
                        ['id' => 'modx-page-settings-left', 'name' => $this->modx->lexicon('modx-page-settings-left')],
                        ['id' => 'modx-page-settings-right', 'name' => $this->modx->lexicon('modx-page-settings-right')],
                        ['id' => 'modx-page-settings-right-box-left', 'name' => $this->modx->lexicon('modx-page-settings-right-box-left')],
                        ['id' => 'modx-page-settings-right-box-right', 'name' => $this->modx->lexicon('modx-page-settings-right-box-right')],
                    ], $array);
                    break;
                case 'user_tab_0':
                    $array = array_merge([
                        ['id' => 'user_tab_0_1', 'name' => $this->modx->lexicon('user_tab_0_1')],
                        ['id' => 'user_tab_0_2', 'name' => $this->modx->lexicon('user_tab_0_2')],
                    ], $array);
                    break;
            }
        }

        return '{"success":true,"total":"'.$count.'","results":'.$this->modx->toJSON($array).'}';
    }

}

return 'efCategoryGetListProcessor';