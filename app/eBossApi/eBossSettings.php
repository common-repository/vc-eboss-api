<?php namespace eBossApi;

class eBossSettings
{

    function __construct()
    {
        add_action('admin_menu', array($this, 'vc_add_admin_menu'));
        add_action('admin_init', array($this, 'eBossApi_init'));
    }

    function vc_add_admin_menu()
    {
        add_menu_page(
            Helper::get('pluginName'),
            Helper::get('pluginName'),
            'manage_options',
            'eBossApi',
            array($this, 'ebossapi_options_page'),
            Helper::assetUrl('images/logo.png', 'myclass')
        );
    }

    function eBossApi_init()
    {

        register_setting('pluginPage', 'eBossApi');

        add_settings_section(
            'vc_pluginPage_section',
            __(Helper::get('pluginName'), 'eBossApi'),
            array($this, 'eBossApi_section_callback'),
            'pluginPage'
        );

        add_settings_field(
            'eBossApi_rgName',
            __('API Registered Name', 'eBossApi'),
            array($this, 'eBossApi_rgName_render'),
            'pluginPage',
            'vc_pluginPage_section'
        );

        add_settings_field(
            'eBossApi_username',
            __('API Username', 'eBossApi'),
            array($this, 'eBossApi_username_render'),
            'pluginPage',
            'vc_pluginPage_section'
        );

        add_settings_field(
            'eBossApi_password',
            __('API Password', 'eBossApi'),
            array($this, 'eBossApi_password_render'),
            'pluginPage',
            'vc_pluginPage_section'
        );

        add_settings_field(
            'eBossApi_key',
            __('API Key', 'eBossApi'),
            array($this, 'eBossApi_key_render'),
            'pluginPage',
            'vc_pluginPage_section'
        );

        add_settings_field(
            'eBossApi_resultPage',
            __('Result Page ID', 'eBossApi'),
            array($this, 'eBossApi_resultPage_render'),
            'pluginPage',
            'vc_pluginPage_section'
        );

        add_settings_field(
            'eBossApi_uploadCV',
            __('Upload CV ID', 'eBossApi'),
            array($this, 'eBossApi_uploadCV_render'),
            'pluginPage',
            'vc_pluginPage_section'
        );

    }

    function eBossApi_resultPage_render()
    {

        $options = get_option('eBossApi');
        ?>
        <input type='text' name='eBossApi[eBossApi_resultPage]' value='<?php echo $options['eBossApi_resultPage']; ?>'>
        <?php

    }

    function eBossApi_uploadCV_render()
    {

        $options = get_option('eBossApi');
        ?>
        <input type='text' name='eBossApi[eBossApi_uploadCV]' value='<?php echo $options['eBossApi_uploadCV']; ?>'>
        <?php

    }

    function eBossApi_rgName_render()
    {

        $options = get_option('eBossApi');
        ?>
        <input type='text' name='eBossApi[eBossApi_rgName]' value='<?php echo $options['eBossApi_rgName']; ?>'>
        <?php

    }

    function eBossApi_username_render()
    {

        $options = get_option('eBossApi');
        ?>
        <input type='text' name='eBossApi[eBossApi_username]' value='<?php echo $options['eBossApi_username']; ?>'>
        <?php

    }

    function eBossApi_password_render()
    {

        $options = get_option('eBossApi');
        ?>
        <input type='text' name='eBossApi[eBossApi_password]' value='<?php echo $options['eBossApi_password']; ?>'>
        <?php

    }

    function eBossApi_key_render()
    {

        $options = get_option('eBossApi');
        ?>
        <input type='text' name='eBossApi[eBossApi_key]' value='<?php echo $options['eBossApi_key']; ?>'>
        <?php

    }

    function eBossApi_section_callback()
    {

        echo __('https://demo.api.recruits-online.com/', 'eBossApi');

    }

    function ebossapi_options_page()
    {

        ?>
        <form action='options.php' method='post'>

            <h2>eBossApi</h2>

            <?php
            settings_fields('pluginPage');
            do_settings_sections('pluginPage');
            submit_button();
            ?>
        </form>
        <?php

    }

}

$eBossSettings = new eBossSettings();
?>
