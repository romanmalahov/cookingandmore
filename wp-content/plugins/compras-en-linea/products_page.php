<?php

global $wpdb,$wp_query;

$siteurl = get_option('siteurl');







if(is_numeric($_GET['category']) || is_numeric($wp_query->query_vars['product_category']) || is_numeric(get_option('wpsc_default_category'))) {

  if(is_numeric($wp_query->query_vars['product_category'])) {

    $category_id = $wp_query->query_vars['product_category'];

	} else if(is_numeric($_GET['category'])) {

    $category_id = $_GET['category'];

	} else { 

    $category_id = get_option('wpsc_default_category');

	}

  $group_type = TXT_WPSC_CATEGORYNOCAP;

}



$category_data = $wpdb->get_row("SELECT * FROM `".$wpdb->prefix."product_categories` WHERE `id`='".$category_id."' LIMIT 1",ARRAY_A);



if($_GET['cart']== 'empty') {

  $_SESSION['nzshpcrt_cart'] = '';

  $_SESSION['nzshpcrt_cart'] = Array();

}

  

?>

<div id='products_page_container' class="wrap wpsc_container">

<?php

if(function_exists('fancy_notifications')) {

  echo fancy_notifications();

}



  $num = 0; 

  

  //else if(is_numeric($_GET['category']) || (is_numeric(get_option('wpsc_default_category')) && (get_option('show_categorybrands') != 3)))

  if(is_numeric($category_id) || is_numeric(get_option('wpsc_default_category')) || (is_numeric($_GET['product_id'])) || (get_option('wpsc_default_category') == 'all') ) {

		$display_items = true;

	} else if($_GET['product_search'] != '') {

		$display_items = true;

	}

      

  if($display_items == true) {

    if(get_option('permalink_structure') != '') {

      $seperator ="?";

		} else {

			$seperator ="&amp;";

		}

		if($wp_query->query_vars['product_name'] != null){

			$product_id = $wpdb->get_var("SELECT `product_id` FROM `".$wpdb->prefix."wpsc_productmeta` WHERE `meta_key` IN ( 'url_name' ) AND `meta_value` IN ( '".$wp_query->query_vars['product_name']."' ) ORDER BY `product_id` DESC LIMIT 1");

		} else {

			$product_id = $_GET['product_id'];

		}





			if(is_numeric($product_id)) { 

				echo single_product_display($product_id);

			} else {

				 if(function_exists('gold_shpcrt_search_form') && get_option('show_search') == 1) {

					 echo gold_shpcrt_search_form();

				 }



         //echo nzshpcrt_display_categories_groups();

				if($_GET['product_search'] != null) {

					echo "<br /><strong class='cattitles'>".TXT_WPSC_SEARCH_FOR." : ".stripslashes($_GET['product_search'])."</strong>";

				} else {

					$category_image = '';

					if((get_option('show_category_thumbnails') == 1) && ($category_data['image'] != null)) {

						$category_image = "<img src='".WPSC_CATEGORY_URL.$category_data['image']."' class='category_image' alt='' title='' />";

					}

					echo "".$category_image."<strong class='cattitles'>".stripslashes($category_data['name'])."</strong>";

					if((get_option('wpsc_category_description') == 'true') && ($category_data['description'] != '')) {

						//echo "<p>".stripslashes($category_data['description'])."</p>";

						echo "<p>".wpautop($category_data['description'])."</p>";

					}

				}

				

				if(get_option('fancy_notifications') != 1) {

					echo "<span id='loadingindicator'><img id='loadingimage' src='".WPSC_URL."/images/indicator.gif' alt='Loading' title='Loading' /> ".TXT_WPSC_UDPATING."...</span><br />";

				}

				

				if (isset($GET['item_per_page'])) {

					$item_per_page = $_GET['item_per_page'];

					$_SESSION['item_per_page'] = $item_per_page;

					update_option('use_pagination',1);

				} 

				

				if(((get_option('show_advanced_search') != 1) || (get_option('show_search') != 1)) && (get_option('product_view') == 'grid') ) {

				  $_SESSION['customer_view'] = 'grid';

				} else if(((get_option('show_advanced_search') != 1) || (get_option('show_search') != 1)) && (get_option('product_view') == 'default')) {

				  $_SESSION['customer_view'] = 'default';

				}

				

				if($category_data['display_type'] != '') {

					$display_type = $category_data['display_type'];

				} else {

					$display_type = get_option('product_view');

				}

				switch($display_type) {

					case "grid":

					if(function_exists('product_display_grid')) {

						echo product_display_grid($product_list, $group_type, $group_sql, $search_sql);

					break; // only break if we have the function;

					}

				  

				  case "list":

					if(function_exists('product_display_list')) {

						echo product_display_list($product_list, $group_type, $group_sql, $search_sql);

					break; // only break if we have the function;

					}

				  

				  case "default":  // this may be redundant :D

				  default:

						echo product_display_default($product_list, $group_type, $group_sql, $search_sql);

				  break;

				}

			}

		} else {

      echo "<a name='products' ></a><strong class='prodtitles'>".TXT_WPSC_PLEASECHOOSEAGROUP."</strong><br />";

      echo nzshpcrt_display_categories_groups();

		}

  ?>

</div>