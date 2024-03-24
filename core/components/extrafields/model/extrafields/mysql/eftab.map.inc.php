<?php
$xpdo_meta_map['efTab']= array (
  'package' => 'extrafields',
  'version' => '1.1',
  'table' => 'ef_tabs',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
    'class_name' => '',
    'name' => '',
    'index' => 0,
    'ab_templates' => '',
    'ab_parents' => '',
    'ab_resources' => '',
    'ab_user_group' => '',
    'ab_users' => '',
    'menuindex' => 0,
    'active' => 1,
  ),
  'fieldMeta' => 
  array (
    'class_name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'index' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => true,
      'default' => 0,
    ),
    'ab_templates' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'ab_parents' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'ab_resources' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'ab_user_group' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'ab_users' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'menuindex' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => true,
      'default' => 0,
    ),
    'active' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'null' => true,
      'default' => 1,
    ),
  ),
  'composites' => 
  array (
    'Categories' => 
    array (
      'class' => 'efCategory',
      'local' => 'id',
      'foreign' => 'tab_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Abs' => 
    array (
      'class' => 'efFieldAbs',
      'local' => 'id',
      'foreign' => 'tab_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
