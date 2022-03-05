<?php
/**
 * ExtraFields
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

/** @var ExtraFields $extrafields */
if ($modx->services instanceof Psr\Http\Client\ClientInterface) {
    $extrafields = $modx->services->get('extrafields');
} else {
    $extrafields = $modx->getService('extrafields', 'ExtraFields', MODX_CORE_PATH . 'components/extrafields/model/');
}

$className = 'Boshnik\ExtraFields\Events\\' . $modx->event->name;
if (class_exists($className)) {
    $handler = new $className($modx, $scriptProperties);
    $handler->run();
}