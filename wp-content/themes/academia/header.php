<?php
	$g5plus_options = &G5Plus_Global::get_options();
	$prefix = 'g5plus_';
	$header_responsive = isset($g5plus_options['mobile_header_responsive_breakpoint']) && !empty($g5plus_options['mobile_header_responsive_breakpoint'])
						 ? $g5plus_options['mobile_header_responsive_breakpoint'] : '991';

	$header_layout = rwmb_meta($prefix . 'header_layout');
	if (($header_layout === '') || ($header_layout == '-1')) {
		$header_layout = $g5plus_options['header_layout'];
	}
?>
<!DOCTYPE html>
<!-- Open Html -->
<html <?php language_attributes(); ?>>
	<!-- Open Head -->
	<head>
		<?php wp_head(); ?>
		<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window,document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
 fbq('init', '2457560727618365'); 
fbq('track', 'PageView');
</script>
<noscript>
 <img height="1" width="1" 
src="https://www.facebook.com/tr?id=2457560727618365&ev=PageView
&noscript=1"/>
</noscript>
<!-- End Facebook Pixel Code -->
	</head>
	<!-- Close Head -->
	<body <?php body_class(); ?> data-responsive="<?php echo esc_attr($header_responsive)?>" data-header="<?php echo esc_attr($header_layout) ?>">
<!-- Global site tag (gtag.js) - Google Ads: 774719617 -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-774719617"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-774719617');
</script>
<!-- Event snippet for Adományozás conversion page
In your html page, add the snippet and call gtag_report_conversion when someone clicks on the chosen link or button. -->
<script>
function gtag_report_conversion(url) {
  var callback = function () {
    if (typeof(url) != 'undefined') {
      window.location = url;
    }
  };
  gtag('event', 'conversion', {
      'send_to': 'AW-774719617/XD_BCM3ajJEBEIGRtfEC',
      'value': 1.0,
      'currency': 'HUF',
      'transaction_id': '',
      'event_callback': callback
  });
  return false;
}
</script>

		<?php
			/**
			 * @hooked - g5plus_site_loading - 5
			 **/
			do_action('g5plus_before_page_wrapper');
		?>
		<!-- Open Wrapper -->
		<div id="wrapper">

		<?php
		/**
		 * @hooked - g5plus_before_page_wrapper_content - 10
		 * @hooked - g5plus_page_header - 15
		 **/
		do_action('g5plus_before_page_wrapper_content');
		?>

			<!-- Open Wrapper Content -->
			<div id="wrapper-content" class="clearfix">

			<?php
			/**
			 **/
			do_action('g5plus_main_wrapper_content_start');
			?>
			<script>
jQuery(document).ready(function(){
	
	var href = jQuery('.post-982 .product-name a').attr('href');
	jQuery('.post-982 .button-view-more a').attr('href',href).text('Tovább');
	
	
		var eredeti = ''
	jQuery('.product-item-wrap').on({

		mouseenter: function() {
		  eredeti = jQuery(this).find('.hoverproductimg').attr('src');
		  jQuery(this).find('.hoverproductimg').attr('src', jQuery(this).find('.hoverproductimg').attr('anim'));
		},
		mouseleave: function() {
		   jQuery(this).find('.hoverproductimg').attr('src', eredeti);
		}
	});
})
</script>