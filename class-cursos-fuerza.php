<?php
/**
 * Plugin Name: Cursos Fuerza
 * Description: Desafio WordPress para programadores back-end interessados em trabalhar na Fuerza.
 * Plugin URI: https://github.com/mamanogit
 * Text Domain: cursos-fuerza
 * Version: 0.0.1
 * Author: mamanogit
 * Author URI: https://github.com/mamanogit
 * Network: false
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path: /lang
 *
 * You should have received a copy of the GNU General Public License
 * along with WP Admin Pages Pro. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author   Marcelo Assis Santos marcelo.assis.santos@live.com
 * @category Core
 * @package  Cursos_Fuerza
 * @version  0.0.1
 */

if ( ! class_exists( 'Cursos_fuerza' ) ) :

	class Cursos_Fuerza { // phpcs:ignore


		/**
		 * Version of the Plugin
		 *
		 * @var string
		 */
		public $version = '1.3.0';

		/**
		 * Makes sure we are only using one instance of the plugin
		 *
		 * @var object Cursos_Fuerza
		 */
		public static $instance;

		/**
		 * Returns the instance of Cursos_Fuerza
		 *
		 * @return Cursos_Fuerza A Cursos_Fuerza instance
		 */
		public static function get_instance() {

			if ( null === self::$instance ) {

				self::$instance = new self();

			} // end if;

			return self::$instance;

		} // end get_instance;

		/**
		 * Initializes the plugins
		 */
		public function __construct() {

			add_action( 'init', array( $this, 'register_post_type' ) );

			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

			add_action( 'save_post_cursos_fuerza_cpt', array( $this, 'save_custom_meta_data' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

			add_action( 'wp_enqueue_scripts', array( $this, 'front_enqueue_scripts' ), 9999 );

			add_action( 'wp_ajax_callback_form_cf', array( $this, 'callback_form_cf' ) );
			add_action( 'wp_ajax_nopriv_callback_form_cf', array( $this, 'callback_form_cf' ) );

			add_filter( 'the_content', array( $this, 'render_single' ) );

		}

		public function callback_form_cf() {

			wp_send_json_success(
				array(
					'status' => 'sucesso!',
				)
			);

		}

		public function render_single( $content ) {

			if ( is_single() && get_post_type() === 'cursos_fuerza_cpt' ) {

				require __DIR__ . '/views/cursos-fuerza-single.php';
				return;
			}

			return $content;

		}

		/**
		 * Front enqueue scripts.
		 */
		public function front_enqueue_scripts() {

			wp_register_script( 'cursos_fuerza_script', plugin_dir_url( __FILE__ ) . 'js/cursos-fuerza.js', array( 'jquery' ), '1.0.0', true );
			wp_localize_script( 'cursos_fuerza_script', 'cursos_fuerza_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

		}

		/**
		 * Admin enqueue scripts.
		 */
		public function admin_enqueue_scripts() {

			wp_enqueue_script( 'jquery-ui-datepicker' );

			wp_register_style( 'jquery-ui', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css' );
			wp_enqueue_style( 'jquery-ui' );

		}

		/**
		 * Register Post Type
		 *
		 * @return void
		 */
		public function register_post_type() {

			if ( ! post_type_exists( 'cursos_fuerza_cpt' ) ) {

				register_post_type(
					'cursos_fuerza_cpt',
					array(
						'labels'             => array(
							'name'          => __( 'Curso Fuerza' ),
							'singular_name' => __( 'Curso Fuerzas' ),
							'add_new'       => __( 'Add new Curso' ),
							'add_new_item'  => __( 'Add new Curso' ),
						),
						'show_in_menu'       => true,
						'public'             => true,
						'has_archive'        => true,
						'publicly_queryable' => true,
						'query_var'          => true,
						'capability_type'    => 'post',
						'show_in_rest'       => true,
						'hierarchical'       => false,
						'menu_position'      => 99,
						'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
					)
				);

			}

		}

		/**
		 * Add meta boxes
		 *
		 * @return void
		 */
		public function add_meta_boxes() {

			add_meta_box( 'mb_subscribe_url', 'Subscribre Link', array( $this, 'subscribe_link_html' ), 'cursos_fuerza_cpt' );
			add_meta_box( 'mb_waste_hour', 'Waste Hours', array( $this, 'waste_hour_html' ), 'cursos_fuerza_cpt' );
			add_meta_box( 'mb_limit_date', 'Limit Date', array( $this, 'limit_date_html' ), 'cursos_fuerza_cpt' );

		}

		/**
		 * Callback render html
		 *
		 * @param array $post Post.
		 * @return void
		 */
		public function waste_hour_html( $post ) {

			$value = get_post_meta( $post->ID, 'waste_hour', true );

			?>

			<input type="number" name="waste_hour" id="waste_hour" class="postbox" min="1" max="240" value="<?php echo $value ? $value : '1'; ?>" />

			<?php
		}

		/**
		 * Callback render html
		 *
		 * @param array $post Post.
		 * @return void
		 */
		public function limit_date_html( $post ) {

			$value = get_post_meta( $post->ID, 'limit_date', true );

			?>

			<script>
				jQuery(document).ready(function($) {

					$("#limit_date_div").datepicker({
						dateFormat: 'dd-mm-yy',
						onSelect: function(dt){
							$('#limit_date').val(dt);
							//$('#limit_date_div').html(dt);
						}  
					});
				});
			</script>

			<div id="limit_date_div"></div>
			<input type="text" readonly name="limit_date" id="limit_date" class="postbox" value="<?php echo $value; ?>" />

			<?php
		}

		/**
		 * Callback render html
		 *
		 * @param array $post Post.
		 * @return void
		 */
		public function subscribe_link_html( $post ) {

			$value = get_post_meta( $post->ID, 'subscribe_url', true );

			?>

			<input type="text" name="subscribe_url" id="subscribe_url" class="postbox" value="<?php echo $value; ?>" />

			<?php
		}

		/**
		 * Handle save post to custom data.
		 *
		 * @param integer $post_id Post id.
		 * @return void
		 */
		public function save_custom_meta_data( $post_id ) {

			if ( array_key_exists( 'subscribe_url', $_POST ) ) {

				update_post_meta( $post_id, 'subscribe_url', $_POST['subscribe_url'] );

			}

			if ( array_key_exists( 'limit_date', $_POST ) ) {

				update_post_meta( $post_id, 'limit_date', $_POST['limit_date'] );

			}

			if ( array_key_exists( 'waste_hour', $_POST ) ) {

				update_post_meta( $post_id, 'waste_hour', $_POST['waste_hour'] );

			}

		}

	} // end class Cursos_fuerza;

	/**
	 * Returns the active instance of the plugin
	 *
	 * @return Cursos_Fuerza
	 */
	function cursosfuerza() {

		require __DIR__ . '/vendor/autoload.php';

		return Cursos_Fuerza::get_instance();

	} // end cursosfuerza;

	cursosfuerza();

endif;
