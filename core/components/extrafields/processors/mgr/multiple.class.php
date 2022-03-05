<?php

class ExtraFieldsMultipleProcessor extends modProcessor
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
        if ($this->modx->services instanceof Psr\Http\Client\ClientInterface) {
            $extrafields = $this->modx->services->get('extrafields');
        } else {
            $extrafields = $this->modx->getService('extrafields', 'ExtraFields', MODX_CORE_PATH . 'components/extrafields/model/');
        }

        foreach ($ids as $id) {
            /** @var modProcessorResponse $response */
            $response = $extrafields->runProcessor('mgr/' . $method, ['id' => $id]);
            if ($response->isError()) {
                return $response->getResponse();
            }
        }

        return $this->success();
    }

}

return 'ExtraFieldsMultipleProcessor';