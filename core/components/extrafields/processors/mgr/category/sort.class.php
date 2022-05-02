<?php

class efCategorySortProcessor extends modObjectProcessor
{
    public $classKey = efCategory::class;


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
        /** @var efCategory $target */
        $target = $this->modx->getObject($this->classKey, ['id' => $this->getProperty('target')]);
        $where = [
            'tab_id' => $target->tab_id
        ];
        foreach ($sources as $id) {
            /** @var efCategory $source */
            $source = $this->modx->getObject($this->classKey, compact('id'));
            $this->sort($source, $target, $where);
        }
        $this->updateIndex($where);

        return $this->modx->error->success();
    }


    /**
     * @param efCategory $source
     * @param efCategory $target
     * @param array $where
     */
    public function sort(efCategory $source, efCategory $target, $where = [])
    {
        $c = $this->modx->newQuery($this->classKey);
        $c->command('UPDATE');
        $c->where($where);
        if ($source->get('colrank') < $target->get('colrank')) {
            $c->query['set']['menuindex'] = [
                'value' => '`menuindex` - 1',
                'type' => false,
            ];
            $c->andCondition([
                'colrank:<=' => $target->colrank,
                'colrank:>' => $source->colrank,
            ]);
            $c->andCondition([
                'colrank:>' => 0,
            ]);
        } else {
            $c->query['set']['colrank'] = [
                'value' => '`colrank` + 1',
                'type' => false,
            ];
            $c->andCondition([
                'colrank:>=' => $target->colrank,
                'colrank:<' => $source->colrank,
            ]);
        }
        $c->prepare();
        $c->stmt->execute();

        $source->set('colrank', $target->colrank);
        $source->save();
    }


    public function updateIndex($where = [])
    {
        // Check if need to update indexes
        $c = $this->modx->newQuery($this->classKey);
        $c->groupby('colrank');
        $c->select('COUNT(colrank) as idx');
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
        $c->sortby('colrank ASC, id', 'ASC');
        if ($c->prepare() && $c->stmt->execute()) {
            $table = $this->modx->getTableName($this->classKey);
            $update = $this->modx->prepare("UPDATE {$table} SET colrank = ? WHERE id = ?");
            $i = 0;
            while ($id = $c->stmt->fetch(PDO::FETCH_COLUMN)) {
                $update->execute([$i, $id]);
                $i++;
            }
        }
    }
}

return 'efCategorySortProcessor';