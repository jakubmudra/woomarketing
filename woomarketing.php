<?php

/*
   Plugin Name: Woomarketing 
   Plugin URI: http://woomarketing.jakubmudra.cz
   Version: 0.1 Beta
   Author: Jakub Mudra
   Description:
   Text Domain: Woomarketing
   License: GPLv3
  */


//Register all setings  - like keys, checkboxs and others
function woomarketing_register_setings() {

	$options = ["woomarketing_toplist_key" => "", 
				"woomarketing_sklik_key" => "",
				"woomarketing_analytics_key" => "",
				"woomarketing_gdr_conversion_key" => ""];


	foreach ($options as $key => $value) {
		add_option($key,$value);
		register_setting('woomarketing_keys',$key,'my_callback');
	}

}
//Add page to options
function woomarketing_register_options_page() {
  add_options_page('WooMarketing', 'WooMarketing', 'manage_options', 'myplugin', 'woomarketing_options_page');
}

//Add actions
add_action('admin_menu', 'woomarketing_register_options_page');
add_action( 'admin_init', 'woomarketing_register_setings' );

//Custom  page
require_once(__DIR__.'/admin.php');





//Main Function and hooks
function toplist_code(){

	if(get_option('woomarketing_toplist_key')){
		


		$key = get_option('woomarketing_toplist_key');

		echo '<a href="http://www.toplist.cz/" target="_top"><img src="https://toplist.cz/count.asp?id='.$key.'" alt="TOPlist" border="0"></a>';
	}
}






//Google analytics code
function ganalytics_code(){

	if(get_option('woomarketing_analytics_key')){

		$key = get_option('woomarketing_analytics_key');
		echo "<!-- Google Analytics -->
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', '".$key."', 'auto');
ga('send', 'pageview');
</script>
<!-- End Google Analytics -->";
	}
}

add_action('wp_head','toplist_code');
add_action('wp_head','ganalytics_code');








//Check if is woocommerce plugin activated.
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

    //Yeah, woocommerce is activated!

	function sklik_code($order){

		if(get_option('woomarketing_sklik_key')){

			$key = get_option("woomarketing_sklik_key");

			$order = wc_get_order( $order );

			echo '<!--SKLIK--><iframe width="119" height="22" frameborder="0" scrolling="no" src="//c.imedia.cz/checkConversion?c='.$key.'&amp;color=ffffff&amp;v='.$order->get_total().'"></iframe>';
		}

	}
	add_action( 'woocommerce_thankyou', 'sklik_code', 10, 1);


		add_action( 'woocommerce_thankyou', 'check_order_product_id', 10, 1);
		  function check_order_product_id( $order_id ){
		      # Get an instance of WC_Order object

		      $order = wc_get_order( $order_id );
		      $array = array();
		      # Iterating through each order items (WC_Order_Item_Product objects in WC 3+)
		      foreach ( $order->get_items() as $item_id => $item_values ) {

		          // Product_id
		          $product_id = $item_values->get_product_id(); 

		          // OR the Product id from the item data
		          $item_data = $item_values->get_data();
		          $product_id = $item_data['product_id'];
		          $array[] = $product_id;
		         
		      }
		      
		      $GLOBALS['TOTAL'] = $order->get_total();
		      $GLOBALS['idcka'] = $array;
		  }




		function woomarketing_gdr_script() {
 		 if(is_cart()){$page = "cart";}elseif(is_shop()){$page = "shop";}elseif(is_checkout()){ $page = "checkout";
	      }elseif(is_account_page()){$page = "account_page";}elseif(is_wc_endpoint_url( 'lost-password' )){$page = "lost password";}elseif (is_wc_endpoint_url( 'customer-logout' )) {$page = "customer logout";}elseif (is_wc_endpoint_url( 'view-order' )) {$page = "view order";}else $page = "home";

	      if(is_product()){
	        global $product;
	        $page = "product";
	        $id = "ecomm_prodid: ".$product->id.",";
	      }elseif(is_wc_endpoint_url( 'order-received' )){
	        $id = $GLOBALS['idcka'];
	        $page = "order-received";
	        $echo = "[";
	        $i = 1;
	        foreach ($id as $key => $value) {
	          $echo .= ($i==1) ? $value : ",".$value;
	          $i++;
	        }
	        $echo.= "]";
	        $total = "ecomm_totalvalue: ".$GLOBALS['TOTAL']."";
	        $id = "ecomm_prodid: ".$echo.",";

	      }else $id = "";
	      ?>

	      <script type="text/javascript">
	      var google_tag_params = {
	      // ecomm_prodid: 'REPLACE_WITH_VALUE',
	      <?php echo $id; ?>
	      ecomm_pagetype: '<?php echo $page; ?>',

	        <?php echo $total; ?>
	      };
	      </script>



	      <script type="text/javascript">
	      /* <![CDATA[ */
	      var google_conversion_id = <?php echo get_option('woomarketing_gdr_conversion_key'); ?>;
	      var google_custom_params = window.google_tag_params;
	      var google_remarketing_only = true;
	      /* ]]> */
	      </script>
	      <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
	      </script>
	      <noscript>
	      <div style="display:inline;">
	      <img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/<?php echo get_option('woomarketing_gdr_conversion_key'); ?>/?guid=ON&amp;script=0"/>
	      </div>
	      </noscript><?php
	}
	add_action( 'wp_footer', 'woomarketing_gdr_script' );

}else{




}
