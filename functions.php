<?php
/**
 * OnePress functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package OnePress
 */

// UPDATE TEMA DESDE WP
function github_check_update( $transient ) {
$usergithub=farkbarn;
$ramastable=master;
    if ( empty( $transient->checked ) ) {
        return $transient;
    }
    $theme_data = wp_get_theme(wp_get_theme()->template);
    $theme_slug = $theme_data->get_template();
    $theme_uri_slug = preg_replace('/-'.$ramastable.'$/', '', $theme_slug);
   $remote_version = '0.0.0';
   $style_css = wp_remote_get("https://raw.githubusercontent.com/".$usergithub."/".$theme_uri_slug."/".$ramastable."/style.css")['body'];
   if ( preg_match( '/^[ \t\/*#@]*' . preg_quote( 'Version', '/' ) . ':(.*)$/mi', $style_css, $match ) && $match[1] )
       $remote_version = _cleanup_header_comment( $match[1] );
   if (version_compare($theme_data->version, $remote_version, '<')) {
       $transient->response[$theme_slug] = array(
           'theme'       => $theme_slug,
           'new_version' => $remote_version,
           'url'         => 'https://github.com/'.$usergithub.'/'.$theme_uri_slug,
           'package'     => 'https://github.com/'.$usergithub.'/'.$theme_uri_slug.'/archive/'.$ramastable.'.zip',
       );
   }
   return $transient;
}
add_filter( 'pre_set_site_transient_update_themes', 'github_check_update' );

// Cambiar el pie de pagina del panel de Administración
function change_footer_admin() {
    echo '&copy;2017 Copyright FBarrera. Todos los derechos reservados - Web creada por <a href="http://www.fbarrera.website">FBarrera</a>';  
}
add_filter('admin_footer_text', 'change_footer_admin');

//omitir info error login
add_filter('login_errors',create_function('$a', "return null;"));

// PERSONALIZAR EL ADMIN LOGIN
function style_adm() { ?>
    <style type="text/css">
	html{background:none;}
        .login h1 a {
	background-image:url(https://acerogroup.energy/wp-content/themes/EnergyOP-master/assets/images/loglogin.jpg) !important;
	border-radius: 25px 0px;
	border: 1px solid white;}

	.login form{
	border-radius: 50px 10px;
	border: solid rgba(0, 0, 0, 0.42);
	box-shadow: 10px 10px 8px #000;
	background-color: #A8A8A8;}

	.login form .input, .login form input[type="checkbox"], .login input[type="text"]{
	border-radius: 20px 5px;
	font-style: italic;}

	body{
	background:url(https://acerogroup.energy/wp-content/themes/EnergyOP-master/assets/images/bglogin.jpg) !important;
	font-size: 18px;
	font-style: italic;}

	.login label{
	color: white;
	font-size: 22px;
	font-style: italic;}

	.wp-core-ui .button-group.button-large .button, .wp-core-ui .button.button-large{
	border-radius: 15px 0;
	border-color: -moz-use-text-color;
	}

	.login #login_error {visibility: hidden;}

	.login .message{border-radius: 25px 0;}

	#title{width:400px;}
	.column-title{width:400px;}
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'style_adm' );

// [polylang lang="en"]English[/polylang][polylang lang="es"]Spanish[/polylang]
function polylang_shortcode($atts, $content = null)
{
        if (empty($content))
                return '';
        extract( shortcode_atts( array('lang' => ''), $atts ) );
        if (empty($lang))
                return "<h3>You must specify 'lang' using shortcode: polylang</h3>";

        return ($lang == pll_current_language()) ? $content : '';
}
add_shortcode('polylang', 'polylang_shortcode');

/***** Cadenas a traducir con polylang *****/
pll_register_string("bd_ep", "Energía y Petróleo", "Own");
pll_register_string("bd_oi", "Obras e Infraestructuras", "Own");
pll_register_string("bp_ds", "Defensa y Seguridad", "Own");

function onepress_footer_site_info() {
     ?>
     <?php printf(esc_html__('Copyright %1$s %2$s %3$s AceroGroup', 'onepress'), '&copy;', esc_attr(date('Y')), esc_attr(get_bloginfo())); ?>
     <?php
}
    add_action( 'onepress_footer_site_info', 'onepress_footer_site_info' );

if ( ! function_exists( 'onepress_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function onepress_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on OnePress, use a find and replace
		 * to change 'onepress' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'onepress', get_template_directory() . '/languages' );

		/*
		 * Add default posts and comments RSS feed links to head.
		 */
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/**
		 * Excerpt for page
		 */
		add_post_type_support( 'page', 'excerpt' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'onepress-blog-small', 300, 150, true );
		add_image_size( 'onepress-small', 480, 300, true );
		add_image_size( 'onepress-medium', 640, 400, true );

		/*
		 * This theme uses wp_nav_menu() in one location.
		 */
		register_nav_menus( array(
			'primary'      => esc_html__( 'Primary Menu', 'onepress' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		/*
		 * This theme styles the visual editor to resemble the theme style.
		 */
		add_editor_style( array( 'assets/css/editor-style.css', onepress_fonts_url() ) );

		/*
		 * WooCommerce support.
		 */
		add_theme_support( 'woocommerce' );

        /**
         * Add theme Support custom logo
         * @since WP 4.5
         * @sin 1.2.1
         */
        add_theme_support( 'custom-logo', array(
            'height'      => 36,
            'width'       => 160,
            'flex-height' => true,
            'flex-width'  => true,
            //'header-text' => array( 'site-title',  'site-description' ), //
        ) );


        // Recommend plugins
        add_theme_support( 'recommend-plugins', array(
            'contact-form-7' => array(
                'name' => esc_html__( 'Contact Form 7', 'onepress' ),
                'active_filename' => 'contact-form-7/wp-contact-form-7.php',
            ),
            'famethemes-demo-importer' => array(
                'name' => esc_html__( 'Famethemes Demo Importer', 'onepress' ),
                'active_filename' => 'famethemes-demo-importer/famethemes-demo-importer.php',
            ),
        ) );


        // Add theme support for selective refresh for widgets.
        add_theme_support( 'customize-selective-refresh-widgets' );

        // Add support for WooCommerce.
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );

	}
endif;
add_action( 'after_setup_theme', 'onepress_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function onepress_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'onepress_content_width', 800 );
}
add_action( 'after_setup_theme', 'onepress_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function onepress_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'onepress' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

    if ( class_exists( 'WooCommerce' ) ) {
        register_sidebar( array(
            'name'          => esc_html__( 'WooCommerce Sidebar', 'onepress' ),
            'id'            => 'sidebar-shop',
            'description'   => '',
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget'  => '</aside>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ) );
    }

}
add_action( 'widgets_init', 'onepress_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function onepress_scripts() {

    $theme = wp_get_theme( 'onepress' );
    $version = $theme->get( 'Version' );

	wp_enqueue_style( 'onepress-fonts', onepress_fonts_url(), array(), $version );
	wp_enqueue_style( 'onepress-animate', get_template_directory_uri() .'/assets/css/animate.min.css', array(), $version );
	wp_enqueue_style( 'onepress-fa', get_template_directory_uri() .'/assets/css/font-awesome.min.css', array(), '4.7.0' );
	wp_enqueue_style( 'onepress-bootstrap', get_template_directory_uri() .'/assets/css/bootstrap.min.css', false, $version );
	wp_enqueue_style( 'onepress-style', get_template_directory_uri().'/style.css' );

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'onepress-js-plugins', get_template_directory_uri() . '/assets/js/plugins.js', array( 'jquery' ), $version, true );
	wp_enqueue_script( 'onepress-js-bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array(), $version, true );

    // Animation from settings.
    $onepress_js_settings = array(
        'onepress_disable_animation'     => get_theme_mod( 'onepress_animation_disable' ),
        'onepress_disable_sticky_header' => get_theme_mod( 'onepress_sticky_header_disable' ),
        'onepress_vertical_align_menu'   => get_theme_mod( 'onepress_vertical_align_menu' ),
        'hero_animation'   				 => get_theme_mod( 'onepress_hero_option_animation', 'flipInX' ),
        'hero_speed'   					 => intval( get_theme_mod( 'onepress_hero_option_speed', 5000 ) ),
        'hero_fade'   					 => intval( get_theme_mod( 'onepress_hero_slider_fade', 750 ) ),
        'hero_duration'   				 => intval( get_theme_mod( 'onepress_hero_slider_duration', 5000 ) ),
        'is_home'   					 => '',
        'gallery_enable'   				 => '',
    );
    // Load gallery scripts
    $galley_disable  = get_theme_mod( 'onepress_gallery_disable' ) ==  1 ? true : false;
    $is_shop = false;
    if ( function_exists( 'is_woocommerce' ) ) {
        if ( is_woocommerce() ) {
            $is_shop = true;
        }
    }

    // Don't load scripts for woocommerce because it don't need.
    if ( ! $is_shop ) {
        if ( ! $galley_disable || is_customize_preview()) {
            $onepress_js_settings['gallery_enable'] = 1;
            $display = get_theme_mod('onepress_gallery_display', 'grid');
            if (!is_customize_preview()) {
                switch ($display) {
                    case 'masonry':
                        wp_enqueue_script('onepress-gallery-masonry', get_template_directory_uri() . '/assets/js/isotope.pkgd.min.js', array(), $version, true);
                        break;
                    case 'justified':
                        wp_enqueue_script('onepress-gallery-justified', get_template_directory_uri() . '/assets/js/jquery.justifiedGallery.min.js', array(), $version, true);
                        break;
                    case 'slider':
                    case 'carousel':
                        wp_enqueue_script('onepress-gallery-carousel', get_template_directory_uri() . '/assets/js/owl.carousel.min.js', array(), $version, true);
                        break;
                    default:
                        break;
                }
            } else {
                wp_enqueue_script('onepress-gallery-masonry', get_template_directory_uri() . '/assets/js/isotope.pkgd.min.js', array(), $version, true);
                wp_enqueue_script('onepress-gallery-justified', get_template_directory_uri() . '/assets/js/jquery.justifiedGallery.min.js', array(), $version, true);
                wp_enqueue_script('onepress-gallery-carousel', get_template_directory_uri() . '/assets/js/owl.carousel.min.js', array(), $version, true);
            }

        }
        wp_enqueue_style('onepress-gallery-lightgallery', get_template_directory_uri() . '/assets/css/lightgallery.css');
    }

	wp_enqueue_script( 'onepress-theme', get_template_directory_uri() . '/assets/js/theme.js', array(), $version, true );
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

    if ( is_front_page() && is_page_template( 'template-frontpage.php' ) ) {
        if ( get_theme_mod( 'onepress_header_scroll_logo' ) ) {
            $onepress_js_settings['is_home'] = 1;
        }
    }
	wp_localize_script( 'jquery', 'onepress_js_settings', $onepress_js_settings );

}
add_action( 'wp_enqueue_scripts', 'onepress_scripts' );


if ( ! function_exists( 'onepress_fonts_url' ) ) :
	/**
	 * Register default Google fonts
	 */
	function onepress_fonts_url() {
	    $fonts_url = '';

	 	/* Translators: If there are characters in your language that are not
	    * supported by Open Sans, translate this to 'off'. Do not translate
	    * into your own language.
	    */
	    $open_sans = _x( 'on', 'Open Sans font: on or off', 'onepress' );

	    /* Translators: If there are characters in your language that are not
	    * supported by Raleway, translate this to 'off'. Do not translate
	    * into your own language.
	    */
	    $raleway = _x( 'on', 'Raleway font: on or off', 'onepress' );

	    if ( 'off' !== $raleway || 'off' !== $open_sans ) {
	        $font_families = array();

	        if ( 'off' !== $raleway ) {
	            $font_families[] = 'Raleway:400,500,600,700,300,100,800,900';
	        }

	        if ( 'off' !== $open_sans ) {
	            $font_families[] = 'Open Sans:400,300,300italic,400italic,600,600italic,700,700italic';
	        }

	        $query_args = array(
	            'family' => urlencode( implode( '|', $font_families ) ),
	            'subset' => urlencode( 'latin,latin-ext' ),
	        );

	        $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	    }

	    return esc_url_raw( $fonts_url );
	}
endif;


if ( ! function_exists( 'onepress_register_required_plugins' ) ) :
	/**
	 * Register the required plugins for this theme.
	 *
	 * In this example, we register five plugins:
	 * - one included with the TGMPA library
	 * - two from an external source, one from an arbitrary source, one from a GitHub repository
	 * - two from the .org repo, where one demonstrates the use of the `is_callable` argument
	 *
	 * The variable passed to tgmpa_register_plugins() should be an array of plugin
	 * arrays.
	 *
	 * This function is hooked into tgmpa_init, which is fired within the
	 * TGM_Plugin_Activation class constructor.
	 */
	function onepress_register_required_plugins() {
		/*
		 * Array of plugin arrays. Required keys are name and slug.
		 * If the source is NOT from the .org repo, then source is also required.
		 */
		$plugins = array(
			array(
				'name'               => 'Contact Form 7', // The plugin name.
				'slug'               => 'contact-form-7', // The plugin slug (typically the folder name).
				'source'             => '', // The plugin source.
				'required'           => false, // If false, the plugin is only 'recommended' instead of required.
				'version'            => '4.2', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
				'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
				'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
				'external_url'       => '', // If set, overrides default API URL and points to an external URL.
			),
		);

		/*
		 * Array of configuration settings. Amend each line as needed.
		 *
		 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
		 * strings available, please help us make TGMPA even better by giving us access to these translations or by
		 * sending in a pull-request with .po file(s) with the translations.
		 *
		 * Only uncomment the strings in the config array if you want to customize the strings.
		 */
		$config = array(
			'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' => '',                      // Default absolute path to bundled plugins.
			'menu'         => 'tgmpa-install-plugins', // Menu slug.
			'parent_slug'  => 'themes.php',            // Parent menu slug.
			'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
			'has_notices'  => true,                    // Show admin notices or not.
			'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => false,                   // Automatically activate plugins after installation or not.
			'message'      => '',                      // Message to output right before the plugins table.

			'strings'      => array(
				'page_title'                      => esc_html__( 'Install Required Plugins', 'onepress' ),
				'menu_title'                      => esc_html__( 'Install Plugins', 'onepress' ),
				'installing'                      => esc_html__( 'Installing Plugin: %s', 'onepress' ), // %s = plugin name.
				'oops'                            => esc_html__( 'Something went wrong with the plugin API.', 'onepress' ),
				'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'onepress' ), // %1$s = plugin name(s).
				'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'onepress' ), // %1$s = plugin name(s).
				'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %1$s plugin.', 'Sorry, but you do not have the correct permissions to install the %1$s plugins.', 'onepress' ), // %1$s = plugin name(s).
				'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'onepress' ), // %1$s = plugin name(s).
				'notice_ask_to_update_maybe'      => _n_noop( 'There is an update available for: %1$s.', 'There are updates available for the following plugins: %1$s.', 'onepress' ), // %1$s = plugin name(s).
				'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %1$s plugin.', 'Sorry, but you do not have the correct permissions to update the %1$s plugins.', 'onepress' ), // %1$s = plugin name(s).
				'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'onepress' ), // %1$s = plugin name(s).
				'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'onepress' ), // %1$s = plugin name(s).
				'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %1$s plugin.', 'Sorry, but you do not have the correct permissions to activate the %1$s plugins.', 'onepress' ), // %1$s = plugin name(s).
				'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'onepress' ),
				'update_link' 					  => _n_noop( 'Begin updating plugin', 'Begin updating plugins', 'onepress' ),
				'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins', 'onepress' ),
				'return'                          => esc_html__( 'Return to Required Plugins Installer', 'onepress' ),
				'plugin_activated'                => esc_html__( 'Plugin activated successfully.', 'onepress' ),
				'activated_successfully'          => esc_html__( 'The following plugin was activated successfully:', 'onepress' ),
				'plugin_already_active'           => esc_html__( 'No action taken. Plugin %1$s was already active.', 'onepress' ),  // %1$s = plugin name(s).
				'plugin_needs_higher_version'     => esc_html__( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'onepress' ),  // %1$s = plugin name(s).
				'complete'                        => esc_html__( 'All plugins installed and activated successfully. %1$s', 'onepress' ), // %s = dashboard link.
				'contact_admin'                   => esc_html__( 'Please contact the administrator of this site for help.', 'onepress' ),
				'nag_type'                        => 'updated', // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
			),

		);

		tgmpa( $plugins, $config );
	}

endif;
add_action( 'tgmpa_register', 'onepress_register_required_plugins' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Add theme info page
 */
require get_template_directory() . '/inc/dashboard.php';
