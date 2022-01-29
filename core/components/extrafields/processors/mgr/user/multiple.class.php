<?php

class ExtraUserFieldMultipleProcessor extends modProcessor
{

    /**
     * @return array|string
     */
    public function process()
    {
        if (!$method = $this->getProperty('method', false)) {
            return $this->failure();
        }
        $ids = json_decode($this->getProperty('ids'), true);
        if (empty($ids)) {
            return $this->success();
        }

        /** @var ExtraFields $extrafields */
        $extrafields = $this->modx->getService('extrafields', 'ExtraFields', MODX_CORE_PATH . 'components/extrafields/model/');

        foreach ($ids as $id) {
            /** @var modProcessorResponse $response */
            $response = $extrafields->runProcessor('mgr/user/' . $method, ['id' => $id]);
            if ($response->isError()) {
                return $response->getResponse();
            }
        }

        return $this->success();
    }

}

return 'ExtraUserFieldMultipleProcessor';