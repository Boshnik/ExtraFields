<?php

namespace Boshnik\ExtraFields\Events;

use Boshnik\ExtraFields\Processors\QueryProcessor;
use Boshnik\ExtraFields\Processors\HelpProcessor;

/**
 * class OnUserFormPrerender
 */
class OnUserFormPrerender extends Event
{
    use QueryProcessor;
    use HelpProcessor;

    public function run()
    {

        /** @var \modUser $user */
        $user = $this->scriptProperties['user'];
        if (!$user) {
            return true;
        }
        $profile = $user->getOne('Profile')->toArray();
        $data = json_encode(array_merge($profile, [
            'id' => $user->id,
            'primary_group' => $user->primary_group,
            'sudo' => $user->sudo,
            'username' => $user->username,
        ]),1);

        $this->extrafields->loadRichTextEditor();
        $tabs = json_encode($this->getTabs('modUserProfile'),1);
        $fields = json_encode($this->getFields('modUserProfile'),1);

        $config = $this->extrafields->config;
        $config['class_name'] = 'modUserProfile';
        $config['media_source'] = $this->getMediaSources();

        $jsUrl = $this->extrafields->config['jsUrl'] . 'mgr/';
        $cssUrl = $this->extrafields->config['cssUrl'] . 'mgr/';

        $this->modx->controller->addCss($cssUrl . 'main.css');
        $this->modx->controller->addJavascript($jsUrl . 'extrafields.js');
        $this->modx->controller->addJavascript($jsUrl . 'misc/combo.js');
        $this->modx->controller->addJavascript($jsUrl . 'misc/utils.js');
        $this->modx->controller->addJavascript($jsUrl . 'misc/xtype.js');
        $this->modx->controller->addLastJavascript($jsUrl . 'inject/user.js');

        // PageBlocks
        if ($config['pageblocks']) {
            $cssUrl = $config['pageblocks']['cssUrl'];
            $jsUrl = $config['pageblocks']['jsUrl'];

            $this->modx->controller->addCss($cssUrl . 'mgr/resource.css');

            $this->modx->controller->addJavascript($jsUrl . 'mgr/pageblocks.js');
            $this->modx->controller->addJavascript($jsUrl . 'mgr/misc/utils.js');
            $this->modx->controller->addJavascript($jsUrl . 'mgr/misc/combo.js');
            $this->modx->controller->addJavascript($jsUrl . 'mgr/misc/default.grid.js');
            $this->modx->controller->addJavascript($jsUrl . 'mgr/misc/default.window.js');

            // Gallery
            $this->modx->controller->addJavascript($jsUrl . 'mgr/misc/plupload/plupload.full.min.js');
            $this->modx->controller->addJavascript($jsUrl . 'mgr/misc/ext.ddview.js');
            $this->modx->controller->addJavascript($jsUrl . 'web/gallery/panel.js');
            $this->modx->controller->addJavascript($jsUrl . 'web/gallery/view.js');
            $this->modx->controller->addJavascript($jsUrl . 'web/gallery/window.js');

            $resource = json_encode([
                'id' => $user->id,
                'context_key' => 'web',
                'class_key' => 'modUserProfile',
            ],1);

            $config['pageblocks']['media_source'] = $this->getMediaSources();
            $config_pageblocks = json_encode($config['pageblocks'],1);

            $this->modx->controller->addHtml("<script>
                Ext.onReady(() => {
                    PageBlocks.resource = $resource;
                    PageBlocks.config = $config_pageblocks;
                    PageBlocks.grid.create = 1;
                    PageBlocks.window.save = 1;
                });
            </script>");
        }


        $config = json_encode($config,1);
        $this->modx->controller->addHtml("<script>
                Ext.onReady(() => {
                    ExtraFields.config = $config;
                    ExtraFields.tabs = $tabs;
                    ExtraFields.fields = $fields;
                    ExtraFields.modxversion = $this->modxversion;
                    ExtraFields.object = $data;
                });
            </script>");
    }
}