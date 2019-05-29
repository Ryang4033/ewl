<?php
/**
* @ PHP 5.6
* @ Decoder version : 1.0.0.4
* @ Release on : 24.03.2018
* @ Website    : http://EasyToYou.eu
*
* @ Zend guard decoder PHP 5.6
*/
$the_query = new WP_Query(array('post_type' => 'o_attorneys', 'orderby' => 'menu_order title', 'order' => 'ASC', 'posts_per_page' => -1));
echo "\n";

if ($the_query->have_posts()) {
	echo "\n" . '    <div class="tmm tmm_your-team-of-attorneys">' . "\n" . '        <div class="tmm_3_columns tmm_wrap ">' . "\n" . '            <span class="tmm_two_containers_tablet"></span>' . "\n" . '            ';
	$i = 0;
	echo '            ';

	while ($the_query->have_posts()) {
		$the_query->the_post();
		echo '                ';
		$show_home_page = intval(get_post_meta(get_the_ID(), '_attorney_show_home_page', true));
		echo '                ';

		if ($show_home_page == 1) {
			echo '            ';
			$i += 1;
			echo '            ';

			if (($i % 3) == 1) {
				echo '            <div class="tmm_container">' . "\n" . '                ';
			}

			echo "\n" . '                <div class="tmm_member" style="border-top: solid 5px;">' . "\n" . '                    <a  href="';
			the_permalink();
			echo '" title="';
			the_title();
			echo '">' . "\n" . '                        ';

			if (has_post_thumbnail()) {
				$thumb = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'thumbnail_size');
				$url = $thumb[0];
			}

			echo '                        <div class="tmm_photo tmm_phover_your-team-of-attorneys_0" style="';

			if (has_post_thumbnail()) {
				echo 'background: url(';
				echo $url;
				echo ');';
			}
			else {
				echo 'background-color:black;';
			}

			echo ' margin-left:auto; margin-right:auto; background-size:cover !important;"></div>' . "\n" . '                    </a>' . "\n" . '                    <div class="tmm_textblock">' . "\n" . '                        <div class="tmm_names">' . "\n" . '                            <span class="tmm_fname">';
			the_title();
			echo '</span>' . "\n" . '                        </div>' . "\n" . '                        <div class="tmm_job">';
			echo get_post_meta(get_the_ID(), '_attorney_position', true);
			echo '</div>' . "\n" . '                        <div class="tmm_scblock"></div>' . "\n" . '                    </div>' . "\n" . '                </div>' . "\n" . '                ';

			if (($i % 3) == 0) {
				echo '            </div>' . "\n\n" . '            <span class="tmm_columns_containers_desktop"></span>' . "\n" . '            ';
			}

			echo '                    ';
		}

		echo '            ';
	}

	echo '                    </div>' . "\n" . '                    ';

	if (($i % 3) != 0) {
		echo '                </div>' . "\n" . '                ';
	}

	echo '<div style="clear:both;"></div>' . "\n" . '            </div>' . "\n" . '        </div>' . "\n" . '    </div>' . "\n\n";
}

?> 
