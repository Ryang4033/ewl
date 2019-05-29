<?php
/**
* @ PHP 5.6
* @ Decoder version : 1.0.0.4
* @ Release on : 24.03.2018
* @ Website    : http://EasyToYou.eu
*
* @ Zend guard decoder PHP 5.6
*/
$the_query = new WP_Query(array('post_type' => 'o_teams', 'orderby' => 'menu_order title', 'order' => 'ASC'));
echo "\n";

if ($the_query->have_posts()) {
	echo '    ';
	$i = 0;
	echo '    ';

	while ($the_query->have_posts()) {
		$the_query->the_post();
		echo '        ';
		$i += 1;
		echo '        <div class="team-box"><a class="item" href="';
		the_permalink();
		echo '">' . "\n" . '            <span class="image">  ';

		if (has_post_thumbnail()) {
			echo "\n" . '                    ';
			the_post_thumbnail('o_attorney_size');
			echo "\n" . '                ';
		}
		else {
			echo '                    <img src="/wp-content/uploads/2016/05/space.png" alt="" />' . "\n" . '                ';
		}

		echo '</span>' . "\n" . '<span class="desc">' . "\n" . '<span class="name-text">';
		the_title();
		echo '</span>' . "\n" . '<span class="job-text">';
		echo get_post_meta(get_the_ID(), '_attorney_position', true);
		echo '</span>' . "\n" . '</span>' . "\n" . '        </a>' . "\n\t\t" . '</div>' . "\n" . '        ';

		if (($i % 3) == 0) {
			echo '            <div class="clear"></div>' . "\n" . '        ';
		}

		echo "\n\n" . '    ';
	}

	echo '    <div class="clear"></div>' . "\n" . '    ' . "\n\n\n";
}

echo "\n";

?> 
