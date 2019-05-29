<?php
/**
* @ PHP 5.6
* @ Decoder version : 1.0.0.4
* @ Release on : 24.03.2018
* @ Website    : http://EasyToYou.eu
*
* @ Zend guard decoder PHP 5.6
*/
class OxygenAttorneys
{
	public function __construct()
	{
		add_action('init', array($this, 'init'));
		add_action('save_post', array($this, 'post_save_meta'), 1, 2);
		add_action('save_post', array($this, 'team_post_save_meta'), 1, 2);
		add_image_size('o_attorney_size', 384, 384, true);
		add_image_size('o_attorney_size_small', 187, 187, true);
		add_action('do_meta_boxes', array($this, 'change_image_box'));
		add_action('current_screen', array($this, 'add_filters'));
		add_shortcode('oxygen_attorneys_home', array($this, 'getAttorneysHome'));
		add_shortcode('oxygen_attorneys_main', array($this, 'getAttorneysMain'));
		add_shortcode('oxygen_team_main', array($this, 'getTeamMain'));
		register_activation_hook(__FILE__, array($this, 'my_rewrite_flush'));
	}

	public function my_rewrite_flush()
	{
		$this->init();
		flush_rewrite_rules();
	}

	public function getAttorneysHome()
	{
		include 'attorneys_home.php';
	}

	public function getAttorneysMain()
	{
		ob_start();
		include 'attorneys_main.php';
		return ob_get_clean();
	}

	public function getTeamMain()
	{
		ob_start();
		include 'team_main.php';
		return ob_get_clean();
	}

	public function add_filters()
	{
		$screen = get_current_screen();

		if (isset($screen->post_type) && ($screen->post_type == 'o_attorneys')) {
			add_filter('admin_post_thumbnail_html', array($this, 'changeFeaturedImageLinks'));
		}

		if (isset($screen->post_type) && ($screen->post_type == 'o_teams')) {
			add_filter('admin_post_thumbnail_html', array($this, 'changeFeaturedImageLinks'));
		}
	}

	public function changeFeaturedImageLinks($content)
	{
		$content = str_replace(__('Set featured image'), __('Set Photo'), $content);
		$content = str_replace(__('Remove featured image'), __('Remove Photo'), $content);
		return $content;
	}

	public function change_image_box()
	{
		remove_meta_box('postimagediv', 'o_attorneys', 'side');
		add_meta_box('postimagediv', __('Photo'), 'post_thumbnail_meta_box', 'o_attorneys', 'normal', 'high');
		remove_meta_box('postimagediv', 'o_teams', 'side');
		add_meta_box('postimagediv', __('Photo'), 'post_thumbnail_meta_box', 'o_teams', 'normal', 'high');
	}

	public function init()
	{
		$labels = array('name' => 'Attorneys', 'singular_name' => 'Attorney', 'add_new' => 'Add Attorney', 'all_items' => 'All Attorneys', 'add_new_item' => 'Add Attorney', 'edit_item' => 'Edit Attorney', 'new_item' => 'New Attorney', 'view_item' => 'View Attorney', 'search_items' => 'Search Attorneys', 'not_found' => 'No Attorneys found', 'not_found_in_trash' => 'No Attorneys found in trash');
		$args = array(
			'labels'               => $labels,
			'public'               => true,
			'has_archive'          => false,
			'query_var'            => true,
			'rewrite'              => true,
			'capability_type'      => 'page',
			'hierarchical'         => false,
			'supports'             => array('title', 'editor', 'thumbnail', 'revisions', 'page-attributes'),
			'menu_position'        => 4,
			'exclude_from_search'  => false,
			'register_meta_box_cb' => array($this, 'add_post_type_metabox'),
			'rewrite'              => array('slug' => 'attorneys')
			);
		register_post_type('o_attorneys', $args);
		$labels = array('name' => 'Team Members', 'singular_name' => 'Team Member', 'add_new' => 'Add Team Member', 'all_items' => 'All Team Members', 'add_new_item' => 'Add Team Member', 'edit_item' => 'Edit Team Member', 'new_item' => 'New Team Member', 'view_item' => 'View Team Member', 'search_items' => 'Search Team Members', 'not_found' => 'No Team Members found', 'not_found_in_trash' => 'No Team Members found in trash');
		$args = array(
			'labels'               => $labels,
			'public'               => true,
			'has_archive'          => false,
			'query_var'            => true,
			'rewrite'              => true,
			'capability_type'      => 'page',
			'hierarchical'         => false,
			'supports'             => array('title', 'editor', 'thumbnail', 'revisions', 'page-attributes'),
			'menu_position'        => 4,
			'exclude_from_search'  => false,
			'register_meta_box_cb' => array($this, 'add_team_metabox'),
			'rewrite'              => array('slug' => 'team')
			);
		register_post_type('o_teams', $args);
	}

	public function add_post_type_metabox()
	{
		add_meta_box('oxygen_attorney_metabox', 'Details', array($this, 'metabox'), NULL, 'normal');
	}

	public function add_team_metabox()
	{
		add_meta_box('oxygen_attorney_metabox', 'Details', array($this, 'team_metabox'), NULL, 'normal');
	}

	public function metabox()
	{
		global $post;
		echo '<input type="hidden" name="oxygen_attorney_post_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
		$position = get_post_meta($post->ID, '_attorney_position', true);
		$show_home_page = intval(get_post_meta($post->ID, '_attorney_show_home_page', true));
		$education = get_post_meta($post->ID, '_education', true);
		echo "\n" . '        <table class="form-table">' . "\n" . '            <tr>' . "\n" . '                <th>' . "\n" . '                    <label>Education / Awards</label>' . "\n" . '                </th>' . "\n" . '                <td>' . "\n" . '                    ';
		wp_editor($education, 'attorney_education');
		echo '                    <!-- classes: .small-text .regular-text .large-text -->' . "\n" . '                </td>' . "\n" . '            </tr>' . "\n" . '            <tr>' . "\n" . '                <th>' . "\n" . '                    <label>Position</label>' . "\n" . '                </th>' . "\n" . '                <td>' . "\n" . '                    <input type="text" name="attorney_position" class="regular-text" value="';
		echo $position;
		echo '">' . "\n" . '                    <!-- classes: .small-text .regular-text .large-text -->' . "\n" . '                </td>' . "\n" . '            </tr>' . "\n" . '            <tr>' . "\n" . '                <th>' . "\n" . '                    <label>Show on home page</label>' . "\n" . '                </th>' . "\n" . '                <td>' . "\n" . '                    <input type="checkbox" name="attorney_show_home_page" ';

		if ($show_home_page) {
			echo 'checked';
		}

		echo '  value="1">' . "\n" . '                    <!-- classes: .small-text .regular-text .large-text -->' . "\n" . '                </td>' . "\n" . '            </tr>' . "\n\n" . '        </table>' . "\n" . '        ';
	}

	public function team_metabox()
	{
		global $post;
		echo '<input type="hidden" name="oxygen_team_post_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
		$position = get_post_meta($post->ID, '_attorney_position', true);
		$show_home_page = intval(get_post_meta($post->ID, '_attorney_show_home_page', true));
		echo "\n" . '        <table class="form-table">' . "\n" . '            <tr>' . "\n" . '                <th>' . "\n" . '                    <label>Position</label>' . "\n" . '                </th>' . "\n" . '                <td>' . "\n" . '                    <input type="text" name="attorney_position" class="regular-text" value="';
		echo $position;
		echo '">' . "\n" . '                    <!-- classes: .small-text .regular-text .large-text -->' . "\n" . '                </td>' . "\n" . '            </tr>' . "\n\n" . '        </table>' . "\n" . '        ';
	}

	public function post_save_meta($post_id, $post)
	{
		if (!isset($_POST['oxygen_attorney_post_noncename'])) {
			return NULL;
		}

		if (!wp_verify_nonce($_POST['oxygen_attorney_post_noncename'], plugin_basename(__FILE__))) {
			return $post->ID;
		}

		if (!current_user_can('edit_post', $post->ID)) {
			return $post->ID;
		}

		$quote_post_meta['_attorney_position'] = $_POST['attorney_position'];
		$quote_post_meta['_education'] = $_POST['attorney_education'];
		$quote_post_meta['_attorney_show_home_page'] = $_POST['attorney_show_home_page'];

		foreach ($quote_post_meta as $key => $value) {
			$value = implode(',', (array) $value);

			if (get_post_meta($post->ID, $key, false)) {
				update_post_meta($post->ID, $key, $value);
			}
			else {
				add_post_meta($post->ID, $key, $value);
			}

			if (!$value) {
				delete_post_meta($post->ID, $key);
			}
		}
	}

	public function team_post_save_meta($post_id, $post)
	{
		if (!isset($_POST['oxygen_team_post_noncename'])) {
			return NULL;
		}

		if (!wp_verify_nonce($_POST['oxygen_team_post_noncename'], plugin_basename(__FILE__))) {
			return $post->ID;
		}

		if (!current_user_can('edit_post', $post->ID)) {
			return $post->ID;
		}

		$quote_post_meta['_attorney_position'] = $_POST['attorney_position'];

		foreach ($quote_post_meta as $key => $value) {
			$value = implode(',', (array) $value);

			if (get_post_meta($post->ID, $key, false)) {
				update_post_meta($post->ID, $key, $value);
			}
			else {
				add_post_meta($post->ID, $key, $value);
			}

			if (!$value) {
				delete_post_meta($post->ID, $key);
			}
		}
	}
}

$OxygenAttorneys = new OxygenAttorneys();

?> 
