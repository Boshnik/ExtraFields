<?php

class efFieldAbsSortProcessor extends modObjectProcessor
{
    public $classKey = efFieldAbs::class;


    /**
     * @return bool|null|string
     */
    public function initialize()
    {

        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        return parent::initialize();
    }


    /**
     * @return array|string
     */
    public function process()
    {
        if (!$this->modx->getCount($this->classKey, $this->getProperty('target'))) {
            return $this->failure();
        }

        $sources = json_decode($this->getProperty('sources'), true);
        if (!is_array($sources)) {
            return $this->failure();
        }
        /** @var efFieldAbs $target */
        $target = $this->modx->getObject($this->classKey, ['id' => $this->getProperty('target')]);
        $where = [
            'field_id' => $target->field_id
        ];
        foreach ($sources as $id) {
            /** @var efFieldAbs $source */
            $source = $this->modx->getObject($this->classKey, compact('id'));
            $this->sort($source, $target, $where);
        }
        $this->updateIndex($where);

        return $this->modx->error->success();
    }


    /**
     * @param efFieldAbs $source
     * @param efFieldAbs $target
     * @param array $where
     */
    public function sort(efFieldAbs $source, efFieldAbs $target, $where = [])
    {
        $c = $this->modx->newQuery($this->classKey);
        $c->command('UPDATE');
        $c->where($where);
        if ($source->get('menuindex') < $target->get('menuindex')) {
            $c->query['set']['menuindex'] = [
                'value' => '`menuindex` - 1',
                'type' => false,
            ];
            $c->andCondition([
                'menuindex:<=' => $target->menuindex,
                'menuindex:>' => $source->menuindex,
            ]);
            $c->andCondition([
                'menuindex:>' => 0,
            ]);
        } else {
            $c->query['set']['menuindex'] = [
                'value' => '`menuindex` + 1',
                'type' => false,
            ];
            $c->andCondition([
                'menuindex:>=' => $target->menuindex,
                'menuindex:<' => $source->menuindex,
            ]);
        }
        $c->prepare();
        $c->stmt->execute();

        $source->set('menuindex', $target->menuindex);
        $source->save();
    }


    public function updateIndex($where = [])
    {
        // Check if need to update indexes
        $c = $this->modx->newQuery($this->classKey);
        $c->groupby('menuindex');
        $c->select('COUNT(menuindex) as idx');
        $c->sortby('idx', 'DESC');
        $c->limit(1);
        if ($c->prepare() && $c->stmt->execute()) {
            if ($c->stmt->fetchColumn() == 1) {
                return;
            }
        }

        // Update indexes
        $c = $this->modx->newQuery($this->classKey);
        $c->where($where);
        $c->select('id');
        $c->sortby('menuindex ASC, id', 'ASC');
        if ($c->prepare() && $c->stmt->execute()) {
            $table = $this->modx->getTableName($this->classKey);
            $update = $this->modx->prepare("UPDATE {$table} SET menuindex = ? WHERE id = ?");
            $i = 0;
            while ($id = $c->stmt->fetch(PDO::FETCH_COLUMN)) {
                $update->execute([$i, $id]);
                $i++;
            }
        }
    }
}

return 'efFieldAbsSortProcessor';