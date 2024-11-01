<?php namespace eBossApi;

/** @var \Herbert\Framework\Enqueue $enqueue */

$enqueue->admin([
    'as' => 'adminbootstrapCSS',
    'src' => '//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css',
    'filter' => ['panel' => ['mainPanel', 'eBossmainPanel']]
]);

$enqueue->admin([
    'as' => 'bootstrapCSS',
    'src' => '//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css'
]);

$enqueue->admin([
    'as' => 'bootstrapJS',
    'src' => '//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js'
], 'footer');

$enqueue->front([
    'as' => 'bootstrapCSS',
    'src' => '//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css'
]);

$enqueue->front([
    'as' => 'stylecss',
    'src' => Helper::assetUrl('css/style.css')
]);

$enqueue->admin([
    'as' => 'stylecss',
    'src' => Helper::assetUrl('css/style.css')
]);

$enqueue->front([
    'as' => 'bootstrapJS',
    'src' => '//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js'
], 'footer');

$enqueue->front([
    'as' => 'jQueryJS',
    'src' => '//code.jquery.com/jquery-1.10.2.js'
], 'footer');

$enqueue->front([
    'as' => 'readmorejs',
    'src' => Helper::assetUrl('/readmore/readmore.min.js')
], 'footer');

$enqueue->admin([
    'as' => 'readmorejs',
    'src' => Helper::assetUrl('/readmore/readmore.min.js')
], 'footer');

$enqueue->front([
    'as' => 'ajax-script',
    'src' => Helper::assetUrl('/js/main.js')
], 'footer');

$enqueue->admin([
    'as' => 'ajax-script',
    'src' => Helper::assetUrl('/js/main.js')
], 'footer');
