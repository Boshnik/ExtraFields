<?php
/**
 * ExtraFields
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

$className = 'Boshnik\ExtraFields\Events\\' . $modx->event->name;
if (class_exists($className)) {
    $handler = new $className($modx, $scriptProperties);
    $handler->run();
}