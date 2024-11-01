<?php namespace eBossApi\Controllers\admin;

class settingsController
{

    function __construct()
    {
        add_action('admin_init', array($this, 'pageConfig'));
    }

    public function pageConfigure()
    {
        add_action('admin_init', array($this, 'pageConfig'));
        $this->pageConfig();
        $this->ebossapi_options_page();
        // return view('@eBossApiAdmin/setting.twig', [
        // 		'settings_fields'   => $this->buffer('settings_fields', ['eBossApi']),
        // 		'do_settings_field' => $this->buffer('do_settings_sections', ['eBossApi']),
        // 		'dev_mode'          => get_option('dev_mode'),
        // 		'options_url'       => get_admin_url().'options.php',
        // 		// 'submit_button'     => get_submit_button()
        // 		'submit_button' => $this->buffer('submit_button', ['Submit']),
        // 	]);
    }

    function pageConfig()
    {
        register_setting('pluginPage', 'eBossApi');

        add_settings_section(
            'vc_eBossApi_section',
            __('Configure', 'eBossApi'),
            array($this, 'eBossApi_section_callback'),
            'eBossApi'
        );

        add_settings_field(
            'eBossApi_rgName',
            __('API Registered Name', 'eBossApi'),
            array($this, 'eBossApi_rgName_render'),
            'eBossApi',
            'vc_eBossApi_section'
        );

        add_settings_field(
            'eBossApi_username',
            __('API Username', 'eBossApi'),
            array($this, 'eBossApi_username_render'),
            'eBossApi',
            'vc_eBossApi_section'
        );

        add_settings_field(
            'eBossApi_password',
            __('API Password', 'eBossApi'),
            array($this, 'eBossApi_password_render'),
            'eBossApi',
            'vc_eBossApi_section'
        );

        add_settings_field(
            'eBossApi_key',
            __('API Key', 'eBossApi'),
            array($this, 'eBossApi_key_render'),
            'eBossApi',
            'vc_eBossApi_section'
        );

        add_settings_field(
            'eBossApi_resultPage',
            __('Result Page ID', 'eBossApi'),
            array($this, 'eBossApi_resultPage_render'),
            'eBossApi',
            'vc_eBossApi_section'
        );

        add_settings_field(
            'eBossApi_uploadCV',
            __('Upload CV ID', 'eBossApi'),
            array($this, 'eBossApi_uploadCV_render'),
            'eBossApi',
            'vc_eBossApi_section'
        );
    }

    function ebossapi_options_page()
    {

        ?>
        <form action='options.php' method='post'>

            <h2>eBossApi</h2>

            <?php
            settings_fields('eBossApi');
            do_settings_sections('eBossApi');
            submit_button();
            ?>
        </form>
        <?php

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

    /**
     * since so much of WP echos directly, here's an output buffer:
     * @param string $functionName
     * @param array $settings
     *
     * @return string
     */
    private function buffer($functionName = "", $settings = [])
    {
        ob_start();

        call_user_func_array($functionName, $settings);
        $buffer = ob_get_contents();

        ob_end_clean();

        return $buffer;
    }

}
