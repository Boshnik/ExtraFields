<?php
/**
 * ExtraFields connector
 *
 * @var modX $modx
 */

require_once dirname(__FILE__, 4) . '/config.core.php';
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CONNECTORS_PATH . 'index.php';

/** @var ExtraFields $extrafields */
$extrafields = $modx->getService('extrafields', 'ExtraFields', MODX_CORE_PATH . 'components/extrafields/model/');

// Handle request
$modx->request->handleRequest([
    'processors_path' => $extrafields->config['processorsPath'],
    'location' => ''
]);