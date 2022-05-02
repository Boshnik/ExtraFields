<?php

class efFieldSortProcessor extends modObjectProcessor
{
    public $classKey = efField::class;


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
        foreach ($sources as $id) {
            /** @var efField $source */
            $source = $this->modx->getObject($this->classKey, compact('id'));
            /** @var efField $target */
            $target = $this->modx->getObject($this->classKey, array('id' => $this->getProperty('target')));
            $this->sort($source, $target);
        }
        $this->updateIndex();

        return $this->modx->error->success();
    }


    /**
     * @param efField $source
     * @param efField $target
     *
     * @return array|string
     */
    public function sort(efField $source, efField $target)
    {
        $c = $this->modx->newQuery($this->classKey);
        $c->command('UPDATE');
        if ($source->get('colrank') < $target->get('colrank')) {
            $c->query['set']['menuindex'] = array(
                'value' => '`menuindex` - 1',
                'type' => false,
            );
            $c->andCondition(array(
                'colrank:<=' => $target->colrank,
                'colrank:>' => $source->colrank,
            ));
            $c->andCondition(array(
                'colrank:>' => 0,
            ));
        } else {
            $c->query['set']['colrank'] = array(
                'value' => '`colrank` + 1',
                'type' => false,
            );
            $c->andCondition(array(
                'colrank:>=' => $target->colrank,
                'colrank:<' => $source->colrank,
            ));
        }
        $c->prepare();
        $c->stmt->execute();

        $source->set('colrank', $target->colrank);
        $source->save();
    }


    /**
     *
     */
    public function updateIndex()
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
        $c->select('id');
        $c->sortby('colrank ASC, id', 'ASC');
        if ($c->prepare() && $c->stmt->execute()) {
            $table = $this->modx->getTableName($this->classKey);
            $update = $this->modx->prepare("UPDATE {$table} SET colrank = ? WHERE id = ?");
            $i = 0;
            while ($id = $c->stmt->fetch(PDO::FETCH_COLUMN)) {
                $update->execute(array($i, $id));
                $i++;
            }
        }
    }
}

return 'efFieldSortProcessor';