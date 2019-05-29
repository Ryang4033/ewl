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
	echo '    <div class="col span_12  ">' . "\n" . '        ';
	$i = 0;
	echo '        ';

	while ($the_query->have_posts()) {
		$the_query->the_post();
		echo '        ';
		$show_home_page = intval(get_post_meta(get_the_ID(), '_verdict_show_home_page', true));
		echo '        ';

		if ($show_home_page == 1) {
			echo '        ';
			$i += 1;
			echo "\n\n" . '        <div  class="vc_col-sm-4 wpb_column column_container col no-extra-padding"  data-padding-pos="all" data-has-bg-color="false" data-bg-color="" data-bg-opacity="1" data-hover-bg="" data-hover-bg-opacity="1" data-animation="" data-delay="0">' . "\n" . '            <div class="wpb_wrapper">' . "\n\n" . '                <div class="wpb_text_column wpb_content_element ">' . "\n" . '                    <div class="wpb_wrapper">' . "\n" . '                        <div class="title-verdict">';
			the_title();
			echo '</div>' . "\n" . '                        <div class="desc-verdict">– ';
			echo get_the_content();
			echo '</div>' . "\n" . '                        <div class="total-verdict">' . "\n" . '                            ';
			$amount = get_post_meta(get_the_ID(), '_verdict_amount', true);
			echo '                            ';

			if (trim($amount) != '') {
				echo '                            <p>Total amount to client:</p>' . "\n" . '                            <div class="price-verdict">';
				echo $amount;
				echo '</div>' . "\n" . '                            ';
			}

			echo '                        </div>' . "\n\n" . '                    </div>' . "\n" . '                </div>' . "\n\n" . '            </div>' . "\n" . '        </div>' . "\n" . '        ';
		}

		echo "\n\n" . '        ';

		if ($i == 3) {
			break;
		}

		echo '        ';
	}

	echo '    </div>' . "\n\n\n\n\n";
}

?> 
