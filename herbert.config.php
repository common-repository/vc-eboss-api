<?php


return [

    'salaryRange' => array(
        '0-10000',
        '10001-12000',
        '12001-13000',
        '13001-15000',
        '15001-17000',
        '17001-19000',
        '19001-21000',
        '21001-24000',
        '24001-26000',
        '26001-29000',
        '29001-34000',
        '34001-39000',
        '39001=45000',
        '45001-50000',
        '50001-60000'
    ),

    /**
     * My Plugin Name
     */
    'pluginName' => 'eBoss+ Recruitment API',

    /**
     * My Table Prefix
     */
    'prefix' => 'eBoss',

    /**
     * The Herbert version constraint.
     */
    'constraint' => '~0.9.9',

    /**
     * Auto-load all required files.
     */
    'requires' => [
        __DIR__ . '/app/customPostTypes.php',
        __DIR__ . '/app/eBossApi/eBossSettings.php',
        __DIR__ . '/app/eBossApi/eBossApiClass.php',
        __DIR__ . '/app/eBossApi/JobSearch.php',
        __DIR__ . '/app/eBossApi/ListEntity.php',
        __DIR__ . '/app/eBossApi/ResumeParser.php',
        __DIR__ . '/app/eBossApi/StandardEntity.php'
    ],

    /**
     * The tables to manage.
     */
    'tables' => [
    ],


    /**
     * Activate
     */
    'activators' => [
        __DIR__ . '/app/activate.php'
    ],

    /**
     * Activate
     */
    'deactivators' => [
        __DIR__ . '/app/deactivate.php'
    ],

    /**
     * The shortcodes to auto-load.
     */
    'shortcodes' => [
        __DIR__ . '/app/shortcodes.php'
    ],

    /**
     * The widgets to auto-load.
     */
    'widgets' => [
        __DIR__ . '/app/widgets.php'
    ],

    /**
     * The widgets to auto-load.
     */
    'enqueue' => [
        __DIR__ . '/app/enqueue.php'
    ],

    /**
     * The routes to auto-load.
     */
    'routes' => [
        'eBossApi' => __DIR__ . '/app/routes.php'
    ],

    /**
     * The panels to auto-load.
     */
    'panels' => [
        'eBossApi' => __DIR__ . '/app/panels.php'
    ],

    /**
     * The APIs to auto-load.
     */
    'apis' => [
        'eBossApi' => __DIR__ . '/app/api.php'
    ],

    /**
     * The view paths to register.
     *
     * E.G: 'eBossApi' => __DIR__ . '/views'
     * can be referenced via @eBossApi/
     * when rendering a view in twig.
     */
    'views' => [
        'eBossApi' => __DIR__ . '/resources/views',
        'eBossApiFront' => __DIR__ . '/resources/views/front',
        'eBossApiAdmin' => __DIR__ . '/resources/views/admin'
    ],

    /**
     * The view globals.
     */
    'viewGlobals' => [

    ],

    /**
     * The asset path.
     */
    'assets' => '/resources/assets/'

];
