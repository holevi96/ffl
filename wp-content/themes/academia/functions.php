<?php
define( 'G5PLUS_HOME_URL', trailingslashit( home_url() ) );
define( 'G5PLUS_THEME_DIR', trailingslashit( get_template_directory() ) );
define( 'G5PLUS_THEME_URL', trailingslashit( get_template_directory_uri() ) );

require_once (realpath(dirname(__FILE__)) . '/../../../wp-content/plugins/pay-via-barion-for-woocommerce/barion-library/library/BarionClient.php');





if (!function_exists('g5plus_include_theme_options')) {
	function g5plus_include_theme_options() {
		if (!class_exists( 'ReduxFramework' )) {
			require_once( G5PLUS_THEME_DIR . 'g5plus-framework/options/framework.php' );
		}
		require_once( G5PLUS_THEME_DIR . 'g5plus-framework/option-extensions/loader.php' );
		require_once( G5PLUS_THEME_DIR . 'includes/options-config.php' );
	}
	g5plus_include_theme_options();
}

if (!function_exists('g5plus_add_custom_mime_types')) {
    function g5plus_add_custom_mime_types($mimes) {
        return array_merge($mimes, array(
            'eot'  => 'application/vnd.ms-fontobject',
            'woff' => 'application/x-font-woff',
            'ttf'  => 'application/x-font-truetype',
            'svg'  => 'image/svg+xml',
        ));
    }
    add_filter('upload_mimes','g5plus_add_custom_mime_types');
}


if (!function_exists('g5plus_include_library')) {
	function g5plus_include_library() {

        require_once(G5PLUS_THEME_DIR . 'g5plus-framework/g5plus-framework.php');
		require_once(G5PLUS_THEME_DIR . 'includes/register-require-plugin.php');
		require_once(G5PLUS_THEME_DIR . 'includes/theme-setup.php');
		require_once(G5PLUS_THEME_DIR . 'includes/sidebar.php');
		require_once(G5PLUS_THEME_DIR . 'includes/meta-boxes.php');
		require_once(G5PLUS_THEME_DIR . 'includes/admin-enqueue.php');
		require_once(G5PLUS_THEME_DIR . 'includes/theme-functions.php');
		require_once(G5PLUS_THEME_DIR . 'includes/theme-action.php');
		require_once(G5PLUS_THEME_DIR . 'includes/theme-filter.php');
		require_once(G5PLUS_THEME_DIR . 'includes/frontend-enqueue.php');
		require_once(G5PLUS_THEME_DIR . 'includes/tax-meta.php');
		if(class_exists('Vc_Manager')){
			require_once(G5PLUS_THEME_DIR . 'includes/vc-functions.php');
		}
    }
	g5plus_include_library();
}

if(!function_exists('g5plus_course_meta')){
    function g5plus_course_meta(){
        if (!class_exists('WPAlchemy_MetaBox')) {
            require_once(G5PLUS_THEME_DIR . 'g5plus-framework/wpalchemy/MetaBox.php');
        }
        require_once(G5PLUS_THEME_DIR . 'woocommerce/course/meta-box.php');
    }
    g5plus_course_meta();
}

remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

add_filter('wp_list_categories', 'g5plus_add_span_cat_count');
function g5plus_add_span_cat_count($links) {
	$links = str_replace('(','<div class="categories-count"><span class="count">',$links);
	$links = str_replace(')','</span></div>',$links);
	return $links;
}
if ( ! function_exists('g5plus_tribe_events_before_html_filter')) {
	function g5plus_tribe_events_before_html_filter($before) {
		return preg_replace('/\<span\sclass=\"tribe-events-ajax-loading">[^~]*?<\/span\>/','',$before);
	}
	add_filter('tribe_events_before_html', 'g5plus_tribe_events_before_html_filter');
}

add_filter( 'tribe_events_admin_show_cost_field', '__return_true', 100 );

function my_custom_add_to_cart_redirect( $url ) {
	$g5plus_options = G5Plus_Global::get_options();
	$course_action_enroll = !is_null($g5plus_options['course_action_enroll']) ? $g5plus_options['course_action_enroll'] : '0';
	if($course_action_enroll!='0' && $course_action_enroll!=''){
		if($course_action_enroll=='1' && function_exists('wc_get_checkout_url')){
			$url = wc_get_cart_url();
		}
		if($course_action_enroll=='2' && function_exists('wc_get_checkout_url')){
			$url = wc_get_checkout_url();
		}
		if($course_action_enroll=='3' && !is_null($g5plus_options['course_action_another_page']) && $g5plus_options['course_action_another_page']!=''){
			$url = $g5plus_options['course_action_another_page'];
		}
		return $url;
	}

}
add_filter( 'woocommerce_add_to_cart_redirect', 'my_custom_add_to_cart_redirect' );

/**
 * Clears WC Cart on Page Load
 * (Only when not on cart/checkout page)
 */
 
add_action( 'wp_head', 'bryce_clear_cart' );
function bryce_clear_cart() {
	if ( wc_get_page_id( 'cart' ) == get_the_ID() || wc_get_page_id( 'checkout' ) == get_the_ID() ) {
		return;
	}
	WC()->cart->empty_cart( true );
}



// Add the custom field "favorite_color"
add_action( 'woocommerce_edit_account_form_start', 'add_deatils_to_account_page_form' );
function add_deatils_to_account_page_form() {
    $user = wp_get_current_user();
    ?>
    <legend>Rendszeres utalással kapcsolatos információk</legend>
<p>
    <h2 for="adomanyozni_kivant_osszeg">Adományozni kívánt összeg: <b><?php echo get_field('adomanyozni_kivant_osszeg','user_'.get_current_user_id()); ?> Ft.</b></h2>

    <!--<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="adomanyozni_kivant_osszeg" id="adomanyozni_kivant_osszeg" value="<?php echo get_field('adomanyozni_kivant_osszeg','user_'.get_current_user_id()); ?>" />-->
 <?php
    global $wpdb;
    $results = $wpdb->get_results( " SELECT * from recurrent_barion_payments where user_id = ".get_current_user_id()." order by date desc", OBJECT );
    $myPosKey = "94d1a66fa6a5411384b94f7a2b9d9741"; // <-- Replace this with your POSKey!
    $myEmailAddress = "info@karitativ.hu"; // <-- Replace this with your e-mail address in Barion!
    $BC = new BarionClient($myPosKey, 2, BarionEnvironment::Prod);
    $paymentDetails = $BC->GetPaymentState($results[0]->paymentID);
    ?>
    <h2 for="milyen_idoszakonkent">Adományozás rendszeressége: <b>
            <?php $idoszak = get_field('milyen_idoszakonkent','user_'.get_current_user_id()); echo  $idoszak["label"]; ?></b></h2>
    <!-- <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="milyen_idoszakonkent" id="milyen_idoszakonkent" value="<?php echo get_field('milyen_idoszakonkent','user_'.get_current_user_id()); ?>" />-->

    <?php if (count($results) > 0 && ($paymentDetails->Status != "Canceled" && $paymentDetails->Status != "Failed" && $paymentDetails->Status != "Prepared")) {
        if ($paymentDetails->Status == "Canceled" || $paymentDetails->Status == "Failed" || $paymentDetails->Status == "Prepared") {
            $wpdb->query("DELETE FROM recurrent_barion_payments WHERE user_id=" . $user->ID);
        } else {
            $idoszak = get_field('milyen_idoszakonkent', 'user_' . $user->ID);
            $next_timestamp = strtotime($results[0]->date) + $idoszak['value'] * 60 * 60 * 24 * 7; //az időszak egy szám ami azt mutatja, hány hetente kéri az utalást. ?>
            <h5>Legközelebbi utalás időpontja: <?php echo date('Y-m-d', $next_timestamp); ?></h5>
            <button class="barion-recurrent"><?php echo do_shortcode("[plugin_delete_me /]"); ?></button>
        <?php } ?>
    <?php } else { ?>
        <button class="barion-recurrent first"><a href="/barion-test">Rendszeres fizetés megkezdése</a></button>
        <p id="checkbox-box">
            Elfogadom az <a href="/aszf">ÁSZF</a>-et.
            <input type="checkbox" id="recurrent-checkbox">
        </p>

        <img src="https://eteltazeletert.hu/wp-content/uploads/2018/12/Barion-card-payment-mark-2017-400px.png" alt="">
    <?php }
    ?>

</p>
    <br>
<?php
}

function add_recurrent_button(){?>
    <button class="barion-recurrent single"><a href="">Ha rendszeresen szeretne fizetni, kattintson ide!</a></button>
    <p>Ahhoz hogy rendszeresen adományozzon, létre kell hoznia egy fiókot. A gomb megnyomása után az eddig kitöltött adataival fiókot hozhat létre.</p>
<?php }
add_action('woocommerce_review_order_before_payment','add_recurrent_button',10,1);

// Save the custom field 'favorite_color'
add_action( 'woocommerce_save_account_details', 'save_favorite_color_account_details', 12, 1 );
function save_favorite_color_account_details( $user_id ) {
    // adomanyozni_kivant_osszeg
    if( isset( $_POST['adomanyozni_kivant_osszeg'] ) )
        update_field('adomanyozni_kivant_osszeg', $_POST['adomanyozni_kivant_osszeg'], 'user_'.get_current_user_id());
    // For Billing email (added related to your comment)
    if( isset( $_POST['milyen_idoszakonkent'] ) )
        update_field('milyen_idoszakonkent', $_POST['milyen_idoszakonkent'], 'user_'.get_current_user_id());
}
if(is_user_logged_in() && strtok($_SERVER["REQUEST_URI"],'?') == '/bejelentkezes-regisztracio/'){
    wp_redirect("/bejelentkezes-regisztracio/edit-account/");
    exit;
}

/**
 * @snippet       Add First & Last Name to My Account Register Form - WooCommerce
 * @how-to        Watch tutorial @ https://businessbloomer.com/?p=19055
 * @sourcecode    https://businessbloomer.com/?p=21974
 * @author        Rodolfo Melogli
 * @credits       Claudio SM Web
 * @compatible    WC 3.5.2
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */

///////////////////////////////
// 1. ADD FIELDS

add_action( 'woocommerce_register_form_start', 'bbloomer_add_name_woo_account_registration' );

function bbloomer_add_name_woo_account_registration() {
    ?>

    <p class="form-row form-row-first">
        <label for="reg_billing_first_name"><?php _e( 'First name', 'woocommerce' ); ?> <span class="required">*</span></label>
        <input type="text" class="input-text" name="billing_first_name" id="reg_billing_first_name" value="<?php if ( ! empty( $_POST['billing_first_name'] ) ) esc_attr_e( $_POST['billing_first_name'] ); ?>" />
    </p>

    <p class="form-row form-row-last">
        <label for="reg_billing_last_name"><?php _e( 'Last name', 'woocommerce' ); ?> <span class="required">*</span></label>
        <input type="text" class="input-text" name="billing_last_name" id="reg_billing_last_name" value="<?php if ( ! empty( $_POST['billing_last_name'] ) ) esc_attr_e( $_POST['billing_last_name'] ); ?>" />
    </p>

    <div class="clear"></div>

    <?php
}

///////////////////////////////
// 2. VALIDATE FIELDS

add_filter( 'woocommerce_registration_errors', 'bbloomer_validate_name_fields', 10, 3 );

function bbloomer_validate_name_fields( $errors, $username, $email ) {
    if ( isset( $_POST['billing_first_name'] ) && empty( $_POST['billing_first_name'] ) ) {
        $errors->add( 'billing_first_name_error', __( '<strong>Error</strong>: First name is required!', 'woocommerce' ) );
    }
    if ( isset( $_POST['billing_last_name'] ) && empty( $_POST['billing_last_name'] ) ) {
        $errors->add( 'billing_last_name_error', __( '<strong>Error</strong>: Last name is required!.', 'woocommerce' ) );
    }
    return $errors;
}

///////////////////////////////
// 3. SAVE FIELDS

add_action( 'woocommerce_created_customer', 'bbloomer_save_name_fields' );

function bbloomer_save_name_fields( $customer_id ) {
    if ( isset( $_POST['billing_first_name'] ) ) {
        update_user_meta( $customer_id, 'billing_first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
        update_user_meta( $customer_id, 'first_name', sanitize_text_field($_POST['billing_first_name']) );
    }
    if ( isset( $_POST['billing_last_name'] ) ) {
        update_user_meta( $customer_id, 'billing_last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
        update_user_meta( $customer_id, 'last_name', sanitize_text_field($_POST['billing_last_name']) );
    }

}