<?php namespace eBossApi;

/** @var \Herbert\Framework\Panel $panel */

/**
 * Adds a panel to manage eBoss+ API Credentials.
 */
$panel->add([
    'type' => 'panel',
    'as' => 'eBossApi',
    'title' => Helper::get('pluginName'),
    'slug' => 'eBossApi',
    'rename' => 'Configure',
    'icon' => Helper::assetUrl('images/logo.png'),
    'parent' => 'options-general.php',
    'uses' => __NAMESPACE__ . '\Controllers\admin\settingsController@pageConfigure'
]);

$panel->add([
    'type' => 'sub-panel',
    'parent' => 'eBossApi',
    'as' => 'Candidates',
    'title' => 'Candidates',
    'slug' => 'eBoss-candidate',
    'uses' => __NAMESPACE__ . '\Controllers\admin\candidateController@pageCandidate'
]);

$panel->add([
    'type' => 'sub-panel',
    'parent' => 'eBossApi',
    'as' => 'Clients',
    'title' => 'Clients',
    'slug' => 'eBoss-clients',
    'uses' => __NAMESPACE__ . '\Controllers\admin\clientController@pageClient'
]);
