<?php
/**
 * Plugin Name: Saucal Test
 * Description: Saucal test
	Version: 1.0
	Author: David
	Author URI: 
 * Text Domain: saucal_test
 */

/* ----------------------------------------------
   Content Setup
------------------------------------------------*/
function saucal_content(){
		$transient_res = get_transient('saucal_test_remote_array');

		if ( ! $transient_res ){ 

			$response = wp_remote_get( 'https://jsonplaceholder.typicode.com/posts?userId=1' );
					     
			$body = json_decode( $response['body'] ); 
		?>

			<div class="saucal_test">
				<div class="saucal_test_title">Saucal Test</div>
				<div class="saucal_test_content">				     
				    <div class="saucal-test-list">
				        <ul>
				        <?php 
				        if (!$body) 
				                echo '<li>Nothing to display!</li>';
				        else
				        foreach ( $body as $b ) { ?>
				            <li><strong><?php echo $b->title; ?></strong><br>
				           <?php echo $b->body;?> </li>
				        <?php } ?>
				        </ul> 
				    </div>
				</div>
			</div>
			<?php 
				$transient_res = '<div class="saucal_test">
				<div class="saucal_test_title">Saucal Test</div>
				<div class="saucal_test_content">				     
				    <div class="saucal-test-list">
				        <ul>';
				        if (!$body) 
				                $transient_res .= '<li>Nothing to display!</li>';
				        else
				        foreach ( $body as $b ) { 
				            $transient_res .= '<li><strong>'.$b->title.'</strong><br>
				           '.$b->body.' </li>';
				        }
				        $transient_res .='</ul> 
				    </div>
				</div>
			</div>';
			?>
			<?php set_transient('saucal_test_remote_array', $transient_res, DAY_IN_SECONDS );?>

	<?php
		}else{
			echo get_transient('saucal_test_remote_array');
		}
}

class saucal_test_widget extends WP_Widget {



/* ------------------------------------------------
	Widget Setup
------------------------------------------------ */

	function saucal_test_widget() {
		$widget_ops = array( 'classname' => 'saucal_test', 'description' => __('Saucal Test', 'saucal_test') );
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'saucal_test' );
		$this->WP_Widget( 'saucal_test', __('Saucal Test', 'saucal_test'), $widget_ops, $control_ops );
	}

/* ------------------------------------------------
	Display Widget
------------------------------------------------ */
	
	function widget( $args, $instance ) {		
		echo $before_widget;

		saucal_content();
		
		echo $after_widget;
	}	
	
	
}

// Add widget function to widgets_init
add_action( 'widgets_init', 'saucal_test_init' );

// Register Widget
function saucal_test_init() {
	register_widget( 'saucal_test_widget' );
}

/* ------------------------------------------------
	Display New tab in My Account
------------------------------------------------ */
function saucal_test_tab() {
    add_rewrite_endpoint( 'saucal-test', EP_ROOT | EP_PAGES );
}
 
add_action( 'init', 'saucal_test_tab' );
 
 
function saucal_test_tab_query_vars( $vars ) {
    $vars[] = 'saucal-test';
    return $vars;
}
 
add_filter( 'query_vars', 'saucal_test_tab_query_vars', 0 );
 
 
function saucal_test_tab_link_my_account( $items ) {
    $items['saucal-test'] = 'Saucal Test';
    return $items;
}
 
add_filter( 'woocommerce_account_menu_items', 'saucal_test_tab_link_my_account' );
 

add_action( 'woocommerce_account_saucal-test_endpoint', 'saucal_content' );
