<?php
/**
 * Divi Module: Hipsy Events Grid
 */

class Hipsy_Divi_Events_Grid extends ET_Builder_Module {

    public $slug       = 'hipsy_events_grid';
    public $vb_support = 'on';

    protected $module_credits = array(
        'module_uri' => 'https://youngsoulbusiness.com',
        'author'     => 'Young Soul Business',
        'author_uri' => 'https://youngsoulbusiness.com',
    );

    public function init() {
        $this->name = esc_html__( 'Hipsy Events Grid', 'hipsy-events' );
        $this->icon_path = plugin_dir_path( __FILE__ ) . 'icon.svg';
    }

    private function is_divi_visual_builder() {
        if ( function_exists( 'et_core_is_fb_enabled' ) && et_core_is_fb_enabled() ) {
            return true;
        }

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_REQUEST['et_fb'] ) ) {
            return true;
        }

        return false;
    }

    public function get_fields() {
        return array(
            'layout' => array(
                'label' => esc_html__( 'Layout', 'hipsy-events' ),
                'type' => 'select',
                'options' => array(
                    'grid' => esc_html__( 'Grid', 'hipsy-events' ),
                    'list' => esc_html__( 'List', 'hipsy-events' ),
                ),
                'default' => 'grid',
            ),
            'columns' => array(
                'label' => esc_html__( 'Kolommen', 'hipsy-events' ),
                'type' => 'range',
                'default' => '3',
            ),
            'gap' => array(
                'label' => esc_html__( 'Tussenruimte', 'hipsy-events' ),
                'type' => 'range',
                'default' => '24px',
            ),
            'aantal' => array(
                'label' => esc_html__( 'Aantal Events', 'hipsy-events' ),
                'type' => 'range',
                'default' => '6',
            ),
        );
    }

    public function render( $attrs, $content, $render_slug ) {
        $is_visual_builder = $this->is_divi_visual_builder();

        $args = array(
            'post_type'      => 'events',
            'posts_per_page' => intval( $this->props['aantal'] ?? 6 ),
            'post_status'    => 'publish',
        );

        $query = new WP_Query( $args );

        ob_start();

        if ( $is_visual_builder ) {
            echo '<div class="hipsy-divi-editor-preview">';
            echo '<div class="hipsy-divi-preview-notice">Hipsy Events Grid preview (Divi Visual Builder)</div>';
        }

        if ( $query->have_posts() ) {
            echo '<div class="hipsy-divi-grid">';

            while ( $query->have_posts() ) {
                $query->the_post();

                if ( function_exists( 'hipsy_render_event_card' ) ) {
                    hipsy_render_event_card( get_the_ID(), array() );
                } else {
                    echo '<div class="hew-card">';
                    echo '<h3>' . esc_html( get_the_title() ) . '</h3>';
                    echo '</div>';
                }
            }

            echo '</div>';
            wp_reset_postdata();
        } else {
            echo '<p>Geen events gevonden.</p>';
        }

        if ( $is_visual_builder ) {
            echo '</div>';
        }

        return ob_get_clean();
    }
}

new Hipsy_Divi_Events_Grid;
