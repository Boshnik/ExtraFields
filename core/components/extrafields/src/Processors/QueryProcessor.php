<?php

namespace Boshnik\ExtraFields\Processors;

trait QueryProcessor
{
    use ValuesProcessor;

    /**
     * @param $className
     * @param $class_name
     * @return mixed
     */
    public function getFetchAll($className, array $where = [])
    {
        $q = $this->modx->newQuery($className);
        $q->select($this->modx->getSelectColumns($className, $className, '', '', false));
        $q->where($where);
        $q->sortby('menuindex', 'asc');
        $q->prepare();
        $q->stmt->execute();
        $results = $q->stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $results;
    }


    /**
     * Getting fields from efTab table
     * @return array
     */
    public function getTabs($class_name = 'modResource')
    {
        $tabs = $this->getFetchAll(\efTab::class, [
            'class_name' => $class_name,
            'active' => 1
        ]);

        foreach ($tabs as $idx => $tab) {
            $tabs[$idx]['categories'] = $this->getFetchAll(\efCategory::class, [
                'tab_id' => $tab['id'],
                'active' => 1
            ]);
        }

        return $tabs;
    }

    /**
     * Get fields from efField table
     * @return array
     */
    public function getFields($class_name = 'modResource')
    {
        $fields = $this->getFetchAll(\efField::class, [
            'class_name' => $class_name,
            'active' => 1
        ]);

        if (!count($fields)) return [];
        foreach ($fields as $idx => $field) {
            $fields[$idx]['abs'] = [];
            $abs = $this->getFetchAll(\efFieldAbs::class, [
                'field_id' => $field['id'],
                'active' => 1
            ]);
            foreach ($abs as $item) {
                $item['values'] = $this->processBindings($item['values']);
                if (isset($this->config['pageblocks'])) {
                    $columns = $this->getFetchAll(\pbTableColumn::class, [
                        'table_id' => $item['table_id'],
                    ]);
                    foreach ($columns as $column) {
                        $field = $this->modx->getObject(\pbField::class, $column['field_id']);
                        if ($field) {
                            $column['name'] = $field->name;
                            $column['caption'] = $field->caption;
                            $item['table_columns'][] = $column;
                        }
                    }
                }
                $fields[$idx]['abs'][] = $item;
            }
        }
        return $fields;
    }
}