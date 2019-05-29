<?php
/**
* @ PHP 5.6
* @ Decoder version : 1.0.0.4
* @ Release on : 24.03.2018
* @ Website    : http://EasyToYou.eu
*
* @ Zend guard decoder PHP 5.6
*/
$the_query = new WP_Query(array('post_type' => 'o_verdicts', 'orderby' => 'menu_order title', 'order' => 'ASC', 'posts_per_page' => -1));
echo "\n";

if ($the_query->have_posts()) {
	$i = 0;
	echo '    <div class="page-verdict">' . "\n";

	while ($the_query->have_posts()) {
		$the_query->the_post();
		$i += 1;
		echo '        <div class="item"><div class="title-verdict">';
		the_title();
		echo '</div>' . "\n" . '        - ';
		echo get_the_content();
		echo '        ';
		$amount = get_post_meta(get_the_ID(), '_verdict_amount', true);
		echo '        ';

		if (trim($amount) != '') {
			echo '        <div class="total-verdict">Total amount to client: <span>';
			echo get_post_meta(get_the_ID(), '_verdict_amount', true);
			echo '</span></div>' . "\n" . '        ';
		}

		echo '    </div>' . "\n\n\n" . '        ';
	}

	echo '    </div>' . "\n\n\n";
}

echo "\n";

?> 
