<?php
/**
* @ PHP 5.6
* @ Decoder version : 1.0.0.4
* @ Release on : 24.03.2018
* @ Website    : http://EasyToYou.eu
*
* @ Zend guard decoder PHP 5.6
*/
class OxygenVerdicts
{
	public function __construct()
	{
		add_action('init', array($this, 'init'));
		add_action('save_post', array($this, 'post_save_meta'), 1, 2);
		add_shortcode('oxygen_verdicts_home', array($this, 'getVerdictsHome'));
		add_shortcode('oxygen_verdicts_main', array($this, 'getVerdictsMain'));
		add_filter('manage_o_verdicts_posts_columns', array($this, 'add_o_verdicts_columns'));
		add_action('manage_posts_custom_column', array($this, 'custom_columns'), 10, 2);
	}

	public function custom_columns($column, $post_id)
	{
		switch ($column) {
		case 'verdict_settlement':
			echo get_post_meta($post_id, '_verdict_amount', true);
			break;
		case 'verdict_order':
			$thispost = get_post($post_id);
			echo $thispost->menu_order;
			break;
		}
	}

	public function add_o_verdicts_columns($columns)
	{
		unset($columns['author']);
		return array_merge($columns, array('verdict_settlement' => __('Verdict/Settlement Amount'), 'verdict_order' => __('Priority')));
	}

	public function addDashToContent($content)
	{
		if (is_singular('o_verdicts')) {
			$content = ' - ' . $content;
		}

		return $content;
	}

	public function getVerdictsHome()
	{
		ob_start();
		include 'verdicts_home.php';
		return ob_get_clean();
	}

	public function getVerdictsMain()
	{
		ob_start();
		include 'verdicts_main.php';
		return ob_get_clean();
	}

	public function init()
	{
		$labels = array('name' => 'Verdicts and Settlements', 'singular_name' => 'Verdict or Settlement', 'add_new' => 'Add Verdict or Settlement', 'all_items' => 'All Verdicts and Settlements', 'add_new_item' => 'Add Verdict or Settlement', 'edit_item' => 'Edit Verdict or Settlement', 'new_item' => 'New Verdict or Settlement', 'view_item' => 'View Verdict or Settlement', 'search_items' => 'Search Verdicts and Settlements', 'not_found' => 'No Verdicts and Settlements found', 'not_found_in_trash' => 'No Verdicts and Settlements found in trash');
		$args = array(
			'labels'               => $labels,
			'public'               => true,
			'has_archive'          => false,
			'query_var'            => true,
			'rewrite'              => false,
			'capability_type'      => 'page',
			'hierarchical'         => false,
			'supports'             => array('title', 'editor', 'revisions', 'page-attributes'),
			'menu_position'        => 4,
			'exclude_from_search'  => true,
			'register_meta_box_cb' => array($this, 'add_post_type_metabox')
			);
		register_post_type('o_verdicts', $args);
	}

	public function add_post_type_metabox()
	{
		add_meta_box('oxygen_verdicts_metabox', 'Details', array($this, 'metabox'), NULL, 'normal');
	}

	public function add_team_metabox()
	{
		add_meta_box('oxygen_attorney_metabox', 'Details', array($this, 'team_metabox'), NULL, 'normal');
	}

	public function metabox()
	{
		global $post;
		echo '<input type="hidden" name="oxygen_verdicts_post_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
		$verdict_amount = get_post_meta($post->ID, '_verdict_amount', true);
		$show_home_page = intval(get_post_meta($post->ID, '_verdict_show_home_page', true));
		echo "\n" . '        <table class="form-table">' . "\n" . '            <tr>' . "\n" . '                <th>' . "\n" . '                    <label>Verdict/Settlement Amount</label>' . "\n" . '                </th>' . "\n" . '                <td>' . "\n" . '                    <input type="text" name="verdict_amount" class="regular-text" value="';
		echo $verdict_amount;
		echo '">' . "\n" . '                    <!-- classes: .small-text .regular-text .large-text -->' . "\n" . '                </td>' . "\n" . '            </tr>' . "\n" . '            <tr>' . "\n" . '                <th>' . "\n" . '                    <label>Show on home page</label>' . "\n" . '                </th>' . "\n" . '                <td>' . "\n" . '                    <input type="checkbox" name="verdict_show_home_page" ';

		if ($show_home_page) {
			echo 'checked';
		}

		echo '  value="1">' . "\n" . '                    <!-- classes: .small-text .regular-text .large-text -->' . "\n" . '                </td>' . "\n" . '            </tr>' . "\n\n" . '        </table>' . "\n" . '        ';
	}

	public function post_save_meta($post_id, $post)
	{
		if (!isset($_POST['oxygen_verdicts_post_noncename'])) {
			return NULL;
		}

		if (!wp_verify_nonce($_POST['oxygen_verdicts_post_noncename'], plugin_basename(__FILE__))) {
			return $post->ID;
		}

		if (!current_user_can('edit_post', $post->ID)) {
			return $post->ID;
		}

		$quote_post_meta['_verdict_amount'] = $_POST['verdict_amount'];
		$quote_post_meta['_verdict_show_home_page'] = $_POST['verdict_show_home_page'];

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

$OxygenVerdicts = new OxygenVerdicts();

?> 
