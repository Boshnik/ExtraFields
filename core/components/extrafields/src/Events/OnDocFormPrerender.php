<?php

namespace Boshnik\ExtraFields\Events;

/**
 * class OnDocFormPrerender
 */
class OnDocFormPrerender extends Event
{
    public function run()
    {
        /** @var \modResource $resource */
        $resource = $this->scriptProperties['resource'];
        if (!$resource) {
            return true;
        }
        $data = json_encode($resource->toArray(),1);

        $this->extrafields->loadRichTextEditor();
        $tabs = json_encode($this->extrafields->getTabs('modResource'),1);
        $fields = json_encode($this->extrafields->getFields('modResource'),1);

        $config = $this->extrafields->config;
        $config['class_name'] = 'modResource';
        $config['media_source'] = $this->extrafields->getMediaSources();

        $jsUrl = $config['jsUrl'] . 'mgr/';
        $cssUrl = $config['cssUrl'] . 'mgr/';

        $this->modx->controller->addCss($cssUrl . 'main.css');
        $this->modx->controller->addJavascript($jsUrl . 'extrafields.js');
        $this->modx->controller->addJavascript($jsUrl . 'misc/combo.js');
        $this->modx->controller->addJavascript($jsUrl . 'misc/utils.js');
        $this->modx->controller->addJavascript($jsUrl . 'misc/xtype.js');
        $this->modx->controller->addLastJavascript($jsUrl . 'inject/resource.js');

        $this->modx->controller->addHtml("<script>
            ExtraFields.config = ".json_encode($config).";
            ExtraFields.tabs = $tabs;
            ExtraFields.fields = $fields;
            ExtraFields.modxversion = $this->modxversion;
            ExtraFields.object = $data;
        </script>");

        $this->modx->controller->addHtml("<script> 
            MODx.clientconfig = ". json_encode($this->getClientConfigs($resource->context_key)) .";
        </script>");
    }

    public function getClientConfigs($context = '')
    {
        $configs = [];
        if ($clientconfig = $this->extrafields->getPackage('ClientConfig')) {
            $configs = $clientconfig->getSettings($context);
        }

        return $configs;
    }
}