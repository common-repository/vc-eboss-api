<?php namespace eBossApi;

/** @var \Herbert\Framework\Shortcode $shortcode */

$shortcode->add(
    'basicSearch',
    __NAMESPACE__ . '\Controllers\FrontController@basicSearch'
);

$shortcode->add(
    'radiusSearch',
    __NAMESPACE__ . '\Controllers\FrontController@radiusSearch'
);

$shortcode->add(
    'singleSearch',
    __NAMESPACE__ . '\Controllers\FrontController@singleSearch'
);

$shortcode->add(
    'registerCV',
    __NAMESPACE__ . '\Controllers\FrontController@registerCV'
);

$shortcode->add(
    'register',
    __NAMESPACE__ . '\Controllers\FrontController@register'
);

$shortcode->add(
    'resultPage',
    __NAMESPACE__ . '\Controllers\FrontController@resultPage'
);

$shortcode->add(
    'uploadcvPage',
    __NAMESPACE__ . '\Controllers\FrontController@uploadcvPage'
);

$shortcode->add(
    'jobApplicationPage',
    __NAMESPACE__ . '\Controllers\FrontController@jobApplicationPage'
);

$shortcode->add(
    'cvParsePage',
    __NAMESPACE__ . '\Controllers\FrontController@cvParsePage'
);

$shortcode->add(
    'thankyouPage',
    __NAMESPACE__ . '\Controllers\FrontController@thankyouPage'
);

$shortcode->add(
    'shortcodeJobSearch',
    __NAMESPACE__ . '\Controllers\FrontController@shortcodeJobSearch'
);

$shortcode->add(
    'pageJobListing',
    __NAMESPACE__ . '\Controllers\FrontController@pageJobListing'
);

$shortcode->add(
    'topJobs',
    __NAMESPACE__ . '\Controllers\FrontController@topJobs',
    [
        'rp' => 'rp'
    ]
);
