<?php

class efTabSortProcessor extends modObjectProcessor
{
    public $classKey = efTab::class;



    /**
     * @return array|string
     */
    public function process()
    {
        if (!$this->modx->getCount($this->classKey, $this->properties['target'])) {
            return $this->failure();
        }

        $sources = json_decode($this->properties['sources'], true);
        if (!is_array($sources)) {
            return $this->failure();
        }
        foreach ($sources as $id) {
            /** @var efTab $source */
            $source = $this->modx->getObject($this->classKey, compact('id'));
            /** @var efTab $target */
            $target = $this->modx->getObject($this->classKey, [
                'id' => $this->properties['target']
            ]);
            $this->sort($source, $target);
        }
        $this->updateIndex();

        return $this->modx->error->success();
    }


    /**
     * @param efTab $source
     * @param efTab $target
     *
     * @return array|string
     */
    public function sort(efField $source, efField $target)
    {
        $c = $this->modx->newQuery($this->classKey);
        $c->command('UPDATE');
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


    /**
     *
     */
    public function updateIndex()
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
        $c->select('id');
        $c->sortby('menuindex ASC, id', 'ASC');
        if ($c->prepare() && $c->stmt->execute()) {
            $table = $this->modx->getTableName($this->classKey);
            $update = $this->modx->prepare("UPDATE {$table} SET menuindex = ? WHERE id = ?");
            $i = 0;
            while ($id = $c->stmt->fetch(PDO::FETCH_COLUMN)) {
                $update->execute(array($i, $id));
                $i++;
            }
        }
    }
}

return 'efTabSortProcessor';