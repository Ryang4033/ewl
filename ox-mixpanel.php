<?php
/**
* @ PHP 5.6
* @ Decoder version : 1.0.0.4
* @ Release on : 24.03.2018
* @ Website    : http://EasyToYou.eu
*
* @ Zend guard decoder PHP 5.6
*/
namespace oxygen;

class oxMixPanel
{
	public function __construct()
	{
		add_action('wp_head', array($this, 'add_code'));
		add_action('wp_footer', array($this, 'add_event'));
		add_action('admin_init', array($this, 'register_settings'));
		add_action('admin_menu', array($this, 'create_menu'));
	}

	public function add_code()
	{
		$token_id = get_option('oxygen_mp_token_id');

		if (!empty($token_id)) {
			include 'mixpanelCode.php';
		}
	}

	public function add_event()
	{
		$token_id = get_option('oxygen_mp_token_id');

		if (!empty($token_id)) {
			echo '            <script type=\'text/javascript\'>' . "\n" . '                var rightNow = new Date();' . "\n" . '                var humanDate = rightNow.toDateString();' . "\n\n" . '                //            mixpanel.register_once({' . "\n" . '                //                \'first_wp_page\': document.title,' . "\n" . '                //                \'first_wp_contact\': humanDate' . "\n" . '                //            });' . "\n\n" . '                mixpanel.track("Viewed Page", {' . "\n" . '                    \'Page Name\': document.title, \'Page URL\': window.location.pathname' . "\n" . '                });' . "\n" . '            </script>' . "\n\n\n" . '            ';
		}
	}

	public function create_menu()
	{
		add_options_page('Mixpanel', 'Mixpanel', 'manage_options', 'ox-mixpanel-admin', array($this, 'settings_page'));
	}

	public function register_settings()
	{
		add_settings_section('oxygen_mp_section', 'Enter your Mixpanel settings below:', array($this, 'display_header_options_content'), 'oxygen-mp-options');
		add_settings_field('oxygen_mp_token_id', 'Token ID', array($this, 'display_main_token_id'), 'oxygen-mp-options', 'oxygen_mp_section');
		register_setting('oxygen_mp_section', 'oxygen_mp_token_id');
	}

	public function display_main_token_id()
	{
		echo '        <input type="text" name="oxygen_mp_token_id" id="oxygen_mp_token_id" value="';
		echo esc_attr(get_option('oxygen_mp_token_id'));
		echo '" />' . "\n" . '        ';
	}

	public function display_header_options_content()
	{
		echo '';
	}

	public function settings_page()
	{
		if (!current_user_can('manage_options')) {
			wp_die(__('You do not have sufficient permissions to access this page.'));
		}

		echo '        <div class="wrap">' . "\n" . '        <h2>Mixpanel Settings</h2>' . "\n" . '        <form method="post" action="options.php" enctype="multipart/form-data">' . "\n" . '            ';
		settings_fields('oxygen_mp_section');
		do_settings_sections('oxygen-mp-options');
		submit_button();
		echo '        </form>' . "\n" . '        ';
	}
}

require 'plugin-updates/plugin-update-checker.php';
$MyUpdateChecker = PucFactory::buildUpdateChecker('http://pluginupdate.qz123.org/?action=get_metadata&slug=ox-mixpanel', __FILE__, 'ox-mixpanel');
$oxMixPanel = new oxMixPanel();

?> 
