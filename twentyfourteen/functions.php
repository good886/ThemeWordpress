<?php
/**
 * Twenty Fourteen functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * @link https://codex.wordpress.org/Plugin_API
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

/**
 * Set up the content width value based on the theme's design.
 *
 * @see twentyfourteen_content_width()
 *
 * @since Twenty Fourteen 1.0
 */
if ( ! isset( $content_width ) ) {
	$content_width = 474;
}

/**
 * Twenty Fourteen only works in WordPress 3.6 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '3.6', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
}

if ( ! function_exists( 'twentyfourteen_setup' ) ) :
/**
 * Twenty Fourteen setup.
 *
 * Set up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support post thumbnails.
 *
 * @since Twenty Fourteen 1.0
 */
function twentyfourteen_setup() {

	/*
	 * Make Twenty Fourteen available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Twenty Fourteen, use a find and
	 * replace to change 'twentyfourteen' to the name of your theme in all
	 * template files.
	 */
	load_theme_textdomain( 'twentyfourteen', get_template_directory() . '/languages' );

	// This theme styles the visual editor to resemble the theme style.
	add_editor_style( array( 'css/editor-style.css', twentyfourteen_font_url(), 'genericons/genericons.css' ) );

	// Add RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// Enable support for Post Thumbnails, and declare two sizes.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 672, 372, true );
	add_image_size( 'twentyfourteen-full-width', 1038, 576, true );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary'   => __( 'Top primary menu', 'twentyfourteen' ),
		'secondary' => __( 'Secondary menu in left sidebar', 'twentyfourteen' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );

	/*
	 * Enable support for Post Formats.
	 * See https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'audio', 'quote', 'link', 'gallery',
	) );

	// This theme allows users to set a custom background.
	add_theme_support( 'custom-background', apply_filters( 'twentyfourteen_custom_background_args', array(
		'default-color' => 'f5f5f5',
	) ) );

	// Add support for featured content.
	add_theme_support( 'featured-content', array(
		'featured_content_filter' => 'twentyfourteen_get_featured_posts',
		'max_posts' => 6,
	) );

	// This theme uses its own gallery styles.
	add_filter( 'use_default_gallery_style', '__return_false' );

	// Indicate widget sidebars can use selective refresh in the Customizer.
	add_theme_support( 'customize-selective-refresh-widgets' );
}
endif; // twentyfourteen_setup
add_action( 'after_setup_theme', 'twentyfourteen_setup' );

/**
 * Adjust content_width value for image attachment template.
 *
 * @since Twenty Fourteen 1.0
 */
function twentyfourteen_content_width() {
	if ( is_attachment() && wp_attachment_is_image() ) {
		$GLOBALS['content_width'] = 810;
	}
}
add_action( 'template_redirect', 'twentyfourteen_content_width' );

/**
 * Getter function for Featured Content Plugin.
 *
 * @since Twenty Fourteen 1.0
 *
 * @return array An array of WP_Post objects.
 */
function twentyfourteen_get_featured_posts() {
	/**
	 * Filter the featured posts to return in Twenty Fourteen.
	 *
	 * @since Twenty Fourteen 1.0
	 *
	 * @param array|bool $posts Array of featured posts, otherwise false.
	 */
	return apply_filters( 'twentyfourteen_get_featured_posts', array() );
}

/**
 * A helper conditional function that returns a boolean value.
 *
 * @since Twenty Fourteen 1.0
 *
 * @return bool Whether there are featured posts.
 */
function twentyfourteen_has_featured_posts() {
	return ! is_paged() && (bool) twentyfourteen_get_featured_posts();
}

/**
 * Register three Twenty Fourteen widget areas.
 *
 * @since Twenty Fourteen 1.0
 */
function twentyfourteen_widgets_init() {
	require get_template_directory() . '/inc/widgets.php';
	register_widget( 'Twenty_Fourteen_Ephemera_Widget' );

	register_sidebar( array(
		'name'          => __( 'Primary Sidebar', 'twentyfourteen' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Main sidebar that appears on the left.', 'twentyfourteen' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	register_sidebar( array(
		'name'          => __( 'Content Sidebar', 'twentyfourteen' ),
		'id'            => 'sidebar-2',
		'description'   => __( 'Additional sidebar that appears on the right.', 'twentyfourteen' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Widget Area', 'twentyfourteen' ),
		'id'            => 'sidebar-3',
		'description'   => __( 'Appears in the footer section of the site.', 'twentyfourteen' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'twentyfourteen_widgets_init' );

/**
 * Register Lato Google font for Twenty Fourteen.
 *
 * @since Twenty Fourteen 1.0
 *
 * @return string
 */
function twentyfourteen_font_url() {
	$font_url = '';
	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Lato, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Lato font: on or off', 'twentyfourteen' ) ) {
		$query_args = array(
			'family' => urlencode( 'Lato:300,400,700,900,300italic,400italic,700italic' ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);
		$font_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return $font_url;
}

/**
 * Enqueue scripts and styles for the front end.
 *
 * @since Twenty Fourteen 1.0
 */
function twentyfourteen_scripts() {
	// Add Lato font, used in the main stylesheet.
	wp_enqueue_style( 'twentyfourteen-lato', twentyfourteen_font_url(), array(), null );

	// Add Genericons font, used in the main stylesheet.
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.0.3' );

	// Load our main stylesheet.
	wp_enqueue_style( 'twentyfourteen-style', get_stylesheet_uri() );

	// Load the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'twentyfourteen-ie', get_template_directory_uri() . '/css/ie.css', array( 'twentyfourteen-style' ), '20131205' );
	wp_style_add_data( 'twentyfourteen-ie', 'conditional', 'lt IE 9' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'twentyfourteen-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20130402' );
	}

	if ( is_active_sidebar( 'sidebar-3' ) ) {
		wp_enqueue_script( 'jquery-masonry' );
	}

	if ( is_front_page() && 'slider' == get_theme_mod( 'featured_content_layout' ) ) {
		wp_enqueue_script( 'twentyfourteen-slider', get_template_directory_uri() . '/js/slider.js', array( 'jquery' ), '20131205', true );
		wp_localize_script( 'twentyfourteen-slider', 'featuredSliderDefaults', array(
			'prevText' => __( 'Previous', 'twentyfourteen' ),
			'nextText' => __( 'Next', 'twentyfourteen' )
		) );
	}

	wp_enqueue_script( 'twentyfourteen-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20150315', true );
}
add_action( 'wp_enqueue_scripts', 'twentyfourteen_scripts' );

/**
 * Enqueue Google fonts style to admin screen for custom header display.
 *
 * @since Twenty Fourteen 1.0
 */
function twentyfourteen_admin_fonts() {
	wp_enqueue_style( 'twentyfourteen-lato', twentyfourteen_font_url(), array(), null );
}
add_action( 'admin_print_scripts-appearance_page_custom-header', 'twentyfourteen_admin_fonts' );

if ( ! function_exists( 'twentyfourteen_the_attached_image' ) ) :
/**
 * Print the attached image with a link to the next attached image.
 *
 * @since Twenty Fourteen 1.0
 */
function twentyfourteen_the_attached_image() {
	$post                = get_post();
	/**
	 * Filter the default Twenty Fourteen attachment size.
	 *
	 * @since Twenty Fourteen 1.0
	 *
	 * @param array $dimensions {
	 *     An array of height and width dimensions.
	 *
	 *     @type int $height Height of the image in pixels. Default 810.
	 *     @type int $width  Width of the image in pixels. Default 810.
	 * }
	 */
	$attachment_size     = apply_filters( 'twentyfourteen_attachment_size', array( 810, 810 ) );
	$next_attachment_url = wp_get_attachment_url();

	/*
	 * Grab the IDs of all the image attachments in a gallery so we can get the URL
	 * of the next adjacent image in a gallery, or the first image (if we're
	 * looking at the last image in a gallery), or, in a gallery of one, just the
	 * link to that image file.
	 */
	$attachment_ids = get_posts( array(
		'post_parent'    => $post->post_parent,
		'fields'         => 'ids',
		'numberposts'    => -1,
		'post_status'    => 'inherit',
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'order'          => 'ASC',
		'orderby'        => 'menu_order ID',
	) );

	// If there is more than 1 attachment in a gallery...
	if ( count( $attachment_ids ) > 1 ) {
		foreach ( $attachment_ids as $attachment_id ) {
			if ( $attachment_id == $post->ID ) {
				$next_id = current( $attachment_ids );
				break;
			}
		}

		// get the URL of the next image attachment...
		if ( $next_id ) {
			$next_attachment_url = get_attachment_link( $next_id );
		}

		// or get the URL of the first image attachment.
		else {
			$next_attachment_url = get_attachment_link( reset( $attachment_ids ) );
		}
	}

	printf( '<a href="%1$s" rel="attachment">%2$s</a>',
		esc_url( $next_attachment_url ),
		wp_get_attachment_image( $post->ID, $attachment_size )
	);
}
endif;

if ( ! function_exists( 'twentyfourteen_list_authors' ) ) :
/**
 * Print a list of all site contributors who published at least one post.
 *
 * @since Twenty Fourteen 1.0
 */
function twentyfourteen_list_authors() {
	$contributor_ids = get_users( array(
		'fields'  => 'ID',
		'orderby' => 'post_count',
		'order'   => 'DESC',
		'who'     => 'authors',
	) );

	foreach ( $contributor_ids as $contributor_id ) :
		$post_count = count_user_posts( $contributor_id );

		// Move on if user has not published a post (yet).
		if ( ! $post_count ) {
			continue;
		}
	?>

	<div class="contributor">
		<div class="contributor-info">
			<div class="contributor-avatar"><?php echo get_avatar( $contributor_id, 132 ); ?></div>
			<div class="contributor-summary">
				<h2 class="contributor-name"><?php echo get_the_author_meta( 'display_name', $contributor_id ); ?></h2>
				<p class="contributor-bio">
					<?php echo get_the_author_meta( 'description', $contributor_id ); ?>
				</p>
				<a class="button contributor-posts-link" href="<?php echo esc_url( get_author_posts_url( $contributor_id ) ); ?>">
					<?php printf( _n( '%d Article', '%d Articles', $post_count, 'twentyfourteen' ), $post_count ); ?>
				</a>
			</div><!-- .contributor-summary -->
		</div><!-- .contributor-info -->
	</div><!-- .contributor -->

	<?php
	endforeach;
}
endif;

/**
 * Extend the default WordPress body classes.
 *
 * Adds body classes to denote:
 * 1. Single or multiple authors.
 * 2. Presence of header image except in Multisite signup and activate pages.
 * 3. Index views.
 * 4. Full-width content layout.
 * 5. Presence of footer widgets.
 * 6. Single views.
 * 7. Featured content layout.
 *
 * @since Twenty Fourteen 1.0
 *
 * @param array $classes A list of existing body class values.
 * @return array The filtered body class list.
 */
function twentyfourteen_body_classes( $classes ) {
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	if ( get_header_image() ) {
		$classes[] = 'header-image';
	} elseif ( ! in_array( $GLOBALS['pagenow'], array( 'wp-activate.php', 'wp-signup.php' ) ) ) {
		$classes[] = 'masthead-fixed';
	}

	if ( is_archive() || is_search() || is_home() ) {
		$classes[] = 'list-view';
	}

	if ( ( ! is_active_sidebar( 'sidebar-2' ) )
		|| is_page_template( 'page-templates/full-width.php' )
		|| is_page_template( 'page-templates/contributors.php' )
		|| is_attachment() ) {
		$classes[] = 'full-width';
	}

	if ( is_active_sidebar( 'sidebar-3' ) ) {
		$classes[] = 'footer-widgets';
	}

	if ( is_singular() && ! is_front_page() ) {
		$classes[] = 'singular';
	}

	if ( is_front_page() && 'slider' == get_theme_mod( 'featured_content_layout' ) ) {
		$classes[] = 'slider';
	} elseif ( is_front_page() ) {
		$classes[] = 'grid';
	}

	return $classes;
}
add_filter( 'body_class', 'twentyfourteen_body_classes' );

/**
 * Extend the default WordPress post classes.
 *
 * Adds a post class to denote:
 * Non-password protected page with a post thumbnail.
 *
 * @since Twenty Fourteen 1.0
 *
 * @param array $classes A list of existing post class values.
 * @return array The filtered post class list.
 */
function twentyfourteen_post_classes( $classes ) {
	if ( ! post_password_required() && ! is_attachment() && has_post_thumbnail() ) {
		$classes[] = 'has-post-thumbnail';
	}

	return $classes;
}
add_filter( 'post_class', 'twentyfourteen_post_classes' );

/**
 * Create a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @since Twenty Fourteen 1.0
 *
 * @global int $paged WordPress archive pagination page count.
 * @global int $page  WordPress paginated post page count.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function twentyfourteen_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() ) {
		return $title;
	}

	// Add the site name.
	$title .= get_bloginfo( 'name', 'display' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $site_description";
	}

	// Add a page number if necessary.
	if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
		$title = "$title $sep " . sprintf( __( 'Page %s', 'twentyfourteen' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'twentyfourteen_wp_title', 10, 2 );

// Implement Custom Header features.
require get_template_directory() . '/inc/custom-header.php';

// Custom template tags for this theme.
require get_template_directory() . '/inc/template-tags.php';

// Add Customizer functionality.
require get_template_directory() . '/inc/customizer.php';

/*
 * Add Featured Content functionality.
 *
 * To overwrite in a plugin, define your own Featured_Content class on or
 * before the 'setup_theme' hook.
 */
if ( ! class_exists( 'Featured_Content' ) && 'plugins.php' !== $GLOBALS['pagenow'] ) {
	require get_template_directory() . '/inc/featured-content.php';
}





// Creates Movie Custom Post Type
if ( ! function_exists('crear_movie') ) {

   // Register Custom Post Type
   function crear_pelicula() {

      $labels = array(
         'name' => _x( 'Peliculas', 'Post Type General Name', 'movie' ),
         'singular_name' => _x( 'Peliculas', 'Post Type Singular Name', 'movie' ),
         'menu_name' => __( 'Peliculas', 'movie' ),
         'name_admin_bar' => __( 'Peliculas', 'movie' ),
         'parent_item_colon' => __( 'Superior:', 'movie' ),
         'all_items' => __( 'Todas las Peliculas', 'movie' ),
         'add_new_item' => __( 'Añadir Pelicula', 'movie' ),
         'add_new' => __( 'Añadir', 'movie' ),
         'new_item' => __( 'Nueva Pelicula', 'movie' ),
         'edit_item' => __( 'Editar', 'movie' ),
         'update_item' => __( 'Actualizar', 'movie' ),
         'view_item' => __( 'Mostrar', 'movie' ),
         'search_items' => __( 'Buscar', 'movie' ),
         'not_found' => __( 'Ninguna encontrada', 'movie' ),
         'not_found_in_trash' => __( 'Ninguna encontrada en la Papelera', 'movie' ),
      );
      $args = array(
         'label' => __( 'Peliculas', 'movie' ),
         'description' => __( 'Peliculas', 'movie' ),
         'labels' => $labels,
         'supports' => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', 'Director', 'MovieCategory', ),
         'taxonomies' => array( 'pelicula' ),
         'hierarchical' => false,
         'public' => true,
         'show_ui' => true,
         'show_in_menu' => true,
         'menu_position' => 20,
         'menu_icon' => 'dashicons-editor-video',
         'show_in_admin_bar' => true,
         'show_in_nav_menus' => false,
         'can_export' => true,
         'has_archive' => true,
         'exclude_from_search' => true,
         'publicly_queryable' => true,
         'capability_type' => 'post',
      );
      register_post_type( 'pelicula', $args );

   }
   add_action( 'init', 'crear_pelicula', 0 );

}


/*
* Creating a function to create our CPT
*/


	/**
     * Registamos a categoria de filmes para o tipo de post film
     */
    register_taxonomy( 'film_category', array( 'pelicula' ), array(
        'hierarchical' => true,
        'label' => __( 'Categoria de Peliculas' ),
        'labels' => array( // Labels customizadas
        'name' => _x( 'Categorias', 'taxonomy general name' ),
        'singular_name' => _x( 'Categoria', 'taxonomy singular name' ),
        'search_items' =>  __( 'Buscar Categorias' ),
        'all_items' => __( 'Todas las Categorias' ),
        'parent_item' => __( 'Categoria Principal' ),
        'parent_item_colon' => __( 'Categoria Principal:' ),
        'edit_item' => __( 'Editar Categoria' ),
        'update_item' => __( 'Actualizar Categoria' ),
        'add_new_item' => __( 'Agregar Nueva Categoria' ),
        'new_item_name' => __( 'Nombre Nueva Categoria' ),
        'menu_name' => __( 'Categoria' ),
    ),
        'show_ui' => true,
        'show_in_tag_cloud' => true,
        'query_var' => true,
        'rewrite' => array(
            'slug' => 'peliculas/categorias',
            'with_front' => false,
        ),
        )
    );
    
    /** 
     * Esta função associa tipos de categorias com tipos de posts.
     * Aqui associamos as tags ao tipo de post film.
     */
    register_taxonomy_for_object_type( 'tags', 'pelicula' );


  /** 
     * filter custom type.
    */

add_action('restrict_manage_posts', 'tsm_filter_post_type_by_taxonomy');
function tsm_filter_post_type_by_taxonomy() {
    global $typenow;
    $post_type = 'pelicula'; // change to your post type
    $taxonomy  = 'pelicula'; // change to your taxonomy
    if ($typenow == $post_type) {
        $selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
        $info_taxonomy = get_taxonomy($taxonomy);
        wp_dropdown_categories(array(
            'show_option_all' => __("Mostrar Peliculas {$info_taxonomy->label}"),
            'taxonomy'        => $taxonomy,
            'name'            => $taxonomy,
            'orderby'         => 'name',
            'selected'        => $selected,
            'show_count'      => true,
            'hide_empty'      => true,
        ));
    };
}
/**
 * Filter posts by taxonomy in admin
*/
add_filter('parse_query', 'tsm_convert_id_to_term_in_query');
function tsm_convert_id_to_term_in_query($query) {
    global $pagenow;
    $post_type = 'pelicula'; // change to your post type
    $taxonomy  = 'pelicula'; // change to your taxonomy
    $q_vars    = &$query->query_vars;
    if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0 ) {
        $term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
        $q_vars[$taxonomy] = $term->slug;
    }
}


add_action( 'pre_get_posts', 'add_my_post_types_to_query' );

function add_my_post_types_to_query( $query ) {
	if ( is_home() && $query->is_main_query() )
		$query->set( 'post_type', array( 'post', 'peliculas' ) );
	return $query;
}







function actores_actrices_get_meta( $value ) {
	global $post;

	$field = get_post_meta( $post->ID, $value, true );
	if ( ! empty( $field ) ) {
		return is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) );
	} else {
		return false;
	}
}

function actores_actrices_add_meta_box() {
	add_meta_box(
		'actores_actrices-actores-actrices',
		__( 'Actores/actrices', 'actores_actrices' ),
		'actores_actrices_html',
		'post',
		'normal',
		'high'
	);
	add_meta_box(
		'actores_actrices-actores-actrices',
		__( 'Actores/actrices', 'actores_actrices' ),
		'actores_actrices_html',
		'pelicula',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'actores_actrices_add_meta_box' );

function actores_actrices_html( $post) {
	wp_nonce_field( '_actores_actrices_nonce', 'actores_actrices_nonce' ); ?>

	<p>
		<label for="actores_actrices_actores"><?php _e( 'Actores', 'actores_actrices' ); ?></label><br>
		<textarea name="actores_actrices_actores" id="actores_actrices_actores" ><?php echo actores_actrices_get_meta( 'actores_actrices_actores' ); ?></textarea>
	
	</p><?php
}

function actores_actrices_save( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! isset( $_POST['actores_actrices_nonce'] ) || ! wp_verify_nonce( $_POST['actores_actrices_nonce'], '_actores_actrices_nonce' ) ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	if ( isset( $_POST['actores_actrices_actores'] ) )
		update_post_meta( $post_id, 'actores_actrices_actores', esc_attr( $_POST['actores_actrices_actores'] ) );
}
add_action( 'save_post', 'actores_actrices_save' );

/*
	Usage: actores_actrices_get_meta( 'actores_actrices_actores' )
*/



function director_get_meta( $value ) {
	global $post;

	$field = get_post_meta( $post->ID, $value, true );
	if ( ! empty( $field ) ) {
		return is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) );
	} else {
		return false;
	}
}

function director_add_meta_box() {
	add_meta_box(
		'director-director',
		__( 'Director', 'director' ),
		'director_html',
		'post',
		'normal',
		'high'
	);
	add_meta_box(
		'director-director',
		__( 'Director', 'director' ),
		'director_html',
		'pelicula',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'director_add_meta_box' );

function director_html( $post) {
	wp_nonce_field( '_director_nonce', 'director_nonce' ); ?>

	<p>
		<label for="director_director"><?php _e( 'Director', 'director' ); ?></label><br>
		<textarea name="director_director" id="director_director" ><?php echo director_get_meta( 'director_director' ); ?></textarea>
	
	</p><?php
}

function director_save( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! isset( $_POST['director_nonce'] ) || ! wp_verify_nonce( $_POST['director_nonce'], '_director_nonce' ) ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	if ( isset( $_POST['director_director'] ) )
		update_post_meta( $post_id, 'director_director', esc_attr( $_POST['director_director'] ) );
}
add_action( 'save_post', 'director_save' );

/*
	Usage: director_get_meta( 'director_director' )
*/


function ao_get_meta( $value ) {
	global $post;

	$field = get_post_meta( $post->ID, $value, true );
	if ( ! empty( $field ) ) {
		return is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) );
	} else {
		return false;
	}
}

function ao_add_meta_box() {
	add_meta_box(
		'ao-ao',
		__( 'Año', 'ao' ),
		'ao_html',
		'post',
		'normal',
		'high'
	);
	add_meta_box(
		'ao-ao',
		__( 'Año', 'ao' ),
		'ao_html',
		'pelicula',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'ao_add_meta_box' );

function ao_html( $post) {
	wp_nonce_field( '_ao_nonce', 'ao_nonce' ); ?>

	<p>
		<label for="ao_ao"><?php _e( 'Año', 'ao' ); ?></label><br>
		<select name="ao_ao" id="ao_ao">
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1900' ) ? 'selected' : '' ?>>1900</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1901' ) ? 'selected' : '' ?>>1901</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1902' ) ? 'selected' : '' ?>>1902</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1903' ) ? 'selected' : '' ?>>1903</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1904' ) ? 'selected' : '' ?>>1904</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1905' ) ? 'selected' : '' ?>>1905</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1906' ) ? 'selected' : '' ?>>1906</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1907' ) ? 'selected' : '' ?>>1907</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1908' ) ? 'selected' : '' ?>>1908</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1909' ) ? 'selected' : '' ?>>1909</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1910' ) ? 'selected' : '' ?>>1910</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1911' ) ? 'selected' : '' ?>>1911</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1912' ) ? 'selected' : '' ?>>1912</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1913' ) ? 'selected' : '' ?>>1913</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1914' ) ? 'selected' : '' ?>>1914</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1915' ) ? 'selected' : '' ?>>1915</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1916' ) ? 'selected' : '' ?>>1916</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1917' ) ? 'selected' : '' ?>>1917</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1918' ) ? 'selected' : '' ?>>1918</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1919' ) ? 'selected' : '' ?>>1919</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1920' ) ? 'selected' : '' ?>>1920</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1921' ) ? 'selected' : '' ?>>1921</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1922' ) ? 'selected' : '' ?>>1922</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1923' ) ? 'selected' : '' ?>>1923</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1924' ) ? 'selected' : '' ?>>1924</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1925' ) ? 'selected' : '' ?>>1925</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1926' ) ? 'selected' : '' ?>>1926</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1927' ) ? 'selected' : '' ?>>1927</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1928' ) ? 'selected' : '' ?>>1928</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1929' ) ? 'selected' : '' ?>>1929</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1930' ) ? 'selected' : '' ?>>1930</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1931' ) ? 'selected' : '' ?>>1931</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1932' ) ? 'selected' : '' ?>>1932</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1933' ) ? 'selected' : '' ?>>1933</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1934' ) ? 'selected' : '' ?>>1934</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1935' ) ? 'selected' : '' ?>>1935</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1936' ) ? 'selected' : '' ?>>1936</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1937' ) ? 'selected' : '' ?>>1937</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1938' ) ? 'selected' : '' ?>>1938</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1939' ) ? 'selected' : '' ?>>1939</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1940' ) ? 'selected' : '' ?>>1940</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1941' ) ? 'selected' : '' ?>>1941</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1942' ) ? 'selected' : '' ?>>1942</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1943' ) ? 'selected' : '' ?>>1943</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1944' ) ? 'selected' : '' ?>>1944</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1945' ) ? 'selected' : '' ?>>1945</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1946' ) ? 'selected' : '' ?>>1946</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1947' ) ? 'selected' : '' ?>>1947</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1948' ) ? 'selected' : '' ?>>1948</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1949' ) ? 'selected' : '' ?>>1949</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1950' ) ? 'selected' : '' ?>>1950</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1951' ) ? 'selected' : '' ?>>1951</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1952' ) ? 'selected' : '' ?>>1952</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1953' ) ? 'selected' : '' ?>>1953</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1954' ) ? 'selected' : '' ?>>1954</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1955' ) ? 'selected' : '' ?>>1955</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1956' ) ? 'selected' : '' ?>>1956</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1957' ) ? 'selected' : '' ?>>1957</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1958' ) ? 'selected' : '' ?>>1958</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1959' ) ? 'selected' : '' ?>>1959</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1960' ) ? 'selected' : '' ?>>1960</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1961' ) ? 'selected' : '' ?>>1961</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1962' ) ? 'selected' : '' ?>>1962</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1963' ) ? 'selected' : '' ?>>1963</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1964' ) ? 'selected' : '' ?>>1964</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1965' ) ? 'selected' : '' ?>>1965</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1966' ) ? 'selected' : '' ?>>1966</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1967' ) ? 'selected' : '' ?>>1967</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1968' ) ? 'selected' : '' ?>>1968</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1969' ) ? 'selected' : '' ?>>1969</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1970' ) ? 'selected' : '' ?>>1970</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1971' ) ? 'selected' : '' ?>>1971</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1972' ) ? 'selected' : '' ?>>1972</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1973' ) ? 'selected' : '' ?>>1973</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1974' ) ? 'selected' : '' ?>>1974</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1975' ) ? 'selected' : '' ?>>1975</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1976' ) ? 'selected' : '' ?>>1976</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1977' ) ? 'selected' : '' ?>>1977</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1978' ) ? 'selected' : '' ?>>1978</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1979' ) ? 'selected' : '' ?>>1979</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1980' ) ? 'selected' : '' ?>>1980</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1981' ) ? 'selected' : '' ?>>1981</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1982' ) ? 'selected' : '' ?>>1982</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1983' ) ? 'selected' : '' ?>>1983</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1984' ) ? 'selected' : '' ?>>1984</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1985' ) ? 'selected' : '' ?>>1985</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1986' ) ? 'selected' : '' ?>>1986</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1987' ) ? 'selected' : '' ?>>1987</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1988' ) ? 'selected' : '' ?>>1988</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1989' ) ? 'selected' : '' ?>>1989</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1990' ) ? 'selected' : '' ?>>1990</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1991' ) ? 'selected' : '' ?>>1991</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1992' ) ? 'selected' : '' ?>>1992</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1993' ) ? 'selected' : '' ?>>1993</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1994' ) ? 'selected' : '' ?>>1994</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1995' ) ? 'selected' : '' ?>>1995</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1996' ) ? 'selected' : '' ?>>1996</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1997' ) ? 'selected' : '' ?>>1997</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1998' ) ? 'selected' : '' ?>>1998</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '1999' ) ? 'selected' : '' ?>>1999</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '2000' ) ? 'selected' : '' ?>>2000</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '2001' ) ? 'selected' : '' ?>>2001</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '2002' ) ? 'selected' : '' ?>>2002</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '2003' ) ? 'selected' : '' ?>>2003</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '2004' ) ? 'selected' : '' ?>>2004</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '2005' ) ? 'selected' : '' ?>>2005</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '2006' ) ? 'selected' : '' ?>>2006</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '2007' ) ? 'selected' : '' ?>>2007</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '2008' ) ? 'selected' : '' ?>>2008</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '2009' ) ? 'selected' : '' ?>>2009</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '2010' ) ? 'selected' : '' ?>>2010</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '2011' ) ? 'selected' : '' ?>>2011</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '2012' ) ? 'selected' : '' ?>>2012</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '2013' ) ? 'selected' : '' ?>>2013</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '2014' ) ? 'selected' : '' ?>>2014</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '2015' ) ? 'selected' : '' ?>>2015</option>
			<option <?php echo (ao_get_meta( 'ao_ao' ) === '2016' ) ? 'selected' : '' ?>>2016</option>
		</select>
	</p><?php
}

function ao_save( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! isset( $_POST['ao_nonce'] ) || ! wp_verify_nonce( $_POST['ao_nonce'], '_ao_nonce' ) ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	if ( isset( $_POST['ao_ao'] ) )
		update_post_meta( $post_id, 'ao_ao', esc_attr( $_POST['ao_ao'] ) );
}
add_action( 'save_post', 'ao_save' );

/*
	Usage: ao_get_meta( 'ao_ao' )
*/



/**
 * Generated by the WordPress Meta Box generator
 * at http://jeremyhixon.com/tool/wordpress-meta-box-generator/
 */

function categora_de_pelcula_get_meta( $value ) {
	global $post;

	$field = get_post_meta( $post->ID, $value, true );
	if ( ! empty( $field ) ) {
		return is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) );
	} else {
		return false;
	}
}

function categora_de_pelcula_add_meta_box() {
	add_meta_box(
		'categora_de_pelcula-categora-de-pelcula',
		__( 'Categoría de película', 'categora_de_pelcula' ),
		'categora_de_pelcula_html',
		'post',
		'normal',
		'high'
	);
	add_meta_box(
		'categora_de_pelcula-categora-de-pelcula',
		__( 'Categoría de película', 'categora_de_pelcula' ),
		'categora_de_pelcula_html',
		'pelicula',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'categora_de_pelcula_add_meta_box' );

function categora_de_pelcula_html( $post) {
	wp_nonce_field( '_categora_de_pelcula_nonce', 'categora_de_pelcula_nonce' ); ?>

	<p>
		<label for="categora_de_pelcula_categora"><?php _e( 'Categoría', 'categora_de_pelcula' ); ?></label><br>
		<textarea name="categora_de_pelcula_categora" id="categora_de_pelcula_categora" ><?php echo categora_de_pelcula_get_meta( 'categora_de_pelcula_categora' ); ?></textarea>
	
	</p><?php
}

function categora_de_pelcula_save( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! isset( $_POST['categora_de_pelcula_nonce'] ) || ! wp_verify_nonce( $_POST['categora_de_pelcula_nonce'], '_categora_de_pelcula_nonce' ) ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	if ( isset( $_POST['categora_de_pelcula_categora'] ) )
		update_post_meta( $post_id, 'categora_de_pelcula_categora', esc_attr( $_POST['categora_de_pelcula_categora'] ) );
}
add_action( 'save_post', 'categora_de_pelcula_save' );

/*
	Usage: categora_de_pelcula_get_meta( 'categora_de_pelcula_categora' )
*/

