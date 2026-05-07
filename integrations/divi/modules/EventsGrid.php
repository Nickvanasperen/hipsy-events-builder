<?php
/**
 * Divi Module: Hipsy Events Grid
 * 
 * Native Divi module voor event weergave met visuele controls.
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

    public function get_fields() {
        return array(
            
            // ═══════════════════════════════════════
            // LAYOUT SETTINGS
            // ═══════════════════════════════════════
            'layout' => array(
                'label'           => esc_html__( 'Layout', 'hipsy-events' ),
                'type'            => 'select',
                'option_category' => 'layout',
                'options'         => array(
                    'grid' => esc_html__( 'Grid', 'hipsy-events' ),
                    'list' => esc_html__( 'List', 'hipsy-events' ),
                ),
                'default'         => 'grid',
                'description'     => esc_html__( 'Kies hoe events worden weergegeven.', 'hipsy-events' ),
                'toggle_slug'     => 'layout',
            ),

            'columns' => array(
                'label'           => esc_html__( 'Kolommen', 'hipsy-events' ),
                'type'            => 'range',
                'option_category' => 'layout',
                'range_settings'  => array(
                    'min'  => 1,
                    'max'  => 4,
                    'step' => 1,
                ),
                'default'         => '3',
                'mobile_options'  => true,
                'responsive'      => true,
                'description'     => esc_html__( 'Aantal kolommen in grid layout.', 'hipsy-events' ),
                'toggle_slug'     => 'layout',
                'show_if'         => array(
                    'layout' => 'grid',
                ),
            ),

            'gap' => array(
                'label'           => esc_html__( 'Tussenruimte', 'hipsy-events' ),
                'type'            => 'range',
                'option_category' => 'layout',
                'range_settings'  => array(
                    'min'  => 0,
                    'max'  => 80,
                    'step' => 1,
                ),
                'default'         => '24px',
                'default_unit'    => 'px',
                'description'     => esc_html__( 'Ruimte tussen event cards.', 'hipsy-events' ),
                'toggle_slug'     => 'layout',
            ),

            // ═══════════════════════════════════════
            // QUERY SETTINGS
            // ═══════════════════════════════════════
            'aantal' => array(
                'label'           => esc_html__( 'Aantal Events', 'hipsy-events' ),
                'type'            => 'range',
                'option_category' => 'basic_option',
                'range_settings'  => array(
                    'min'  => 1,
                    'max'  => 50,
                    'step' => 1,
                ),
                'default'         => '6',
                'description'     => esc_html__( 'Maximum aantal events om te tonen.', 'hipsy-events' ),
                'toggle_slug'     => 'query',
            ),

            'alleen_toekomst' => array(
                'label'           => esc_html__( 'Alleen Aankomende Events', 'hipsy-events' ),
                'type'            => 'yes_no_button',
                'option_category' => 'basic_option',
                'options'         => array(
                    'on'  => esc_html__( 'Ja', 'hipsy-events' ),
                    'off' => esc_html__( 'Nee', 'hipsy-events' ),
                ),
                'default'         => 'on',
                'description'     => esc_html__( 'Toon alleen toekomstige events.', 'hipsy-events' ),
                'toggle_slug'     => 'query',
            ),

            'categorie' => array(
                'label'           => esc_html__( 'Categorie Filter', 'hipsy-events' ),
                'type'            => 'select',
                'option_category' => 'basic_option',
                'options'         => $this->get_categories_options(),
                'default'         => '',
                'description'     => esc_html__( 'Filter op specifieke categorie.', 'hipsy-events' ),
                'toggle_slug'     => 'query',
            ),

            // ═══════════════════════════════════════
            // CONTENT TOGGLES
            // ═══════════════════════════════════════
            'show_image' => array(
                'label'           => esc_html__( 'Toon Afbeelding', 'hipsy-events' ),
                'type'            => 'yes_no_button',
                'option_category' => 'configuration',
                'options'         => array(
                    'on'  => esc_html__( 'Ja', 'hipsy-events' ),
                    'off' => esc_html__( 'Nee', 'hipsy-events' ),
                ),
                'default'         => 'on',
                'toggle_slug'     => 'content',
            ),

            'show_date' => array(
                'label'   => esc_html__( 'Toon Datum', 'hipsy-events' ),
                'type'    => 'yes_no_button',
                'options' => array(
                    'on'  => esc_html__( 'Ja', 'hipsy-events' ),
                    'off' => esc_html__( 'Nee', 'hipsy-events' ),
                ),
                'default'     => 'on',
                'toggle_slug' => 'content',
            ),

            'show_time' => array(
                'label'   => esc_html__( 'Toon Tijd', 'hipsy-events' ),
                'type'    => 'yes_no_button',
                'options' => array(
                    'on'  => esc_html__( 'Ja', 'hipsy-events' ),
                    'off' => esc_html__( 'Nee', 'hipsy-events' ),
                ),
                'default'     => 'on',
                'toggle_slug' => 'content',
            ),

            'show_title' => array(
                'label'   => esc_html__( 'Toon Titel', 'hipsy-events' ),
                'type'    => 'yes_no_button',
                'options' => array(
                    'on'  => esc_html__( 'Ja', 'hipsy-events' ),
                    'off' => esc_html__( 'Nee', 'hipsy-events' ),
                ),
                'default'     => 'on',
                'toggle_slug' => 'content',
            ),

            'show_location' => array(
                'label'   => esc_html__( 'Toon Locatie', 'hipsy-events' ),
                'type'    => 'yes_no_button',
                'options' => array(
                    'on'  => esc_html__( 'Ja', 'hipsy-events' ),
                    'off' => esc_html__( 'Nee', 'hipsy-events' ),
                ),
                'default'     => 'on',
                'toggle_slug' => 'content',
            ),

            'show_description' => array(
                'label'   => esc_html__( 'Toon Beschrijving', 'hipsy-events' ),
                'type'    => 'yes_no_button',
                'options' => array(
                    'on'  => esc_html__( 'Ja', 'hipsy-events' ),
                    'off' => esc_html__( 'Nee', 'hipsy-events' ),
                ),
                'default'     => 'on',
                'toggle_slug' => 'content',
            ),

            'show_price' => array(
                'label'   => esc_html__( 'Toon Prijs', 'hipsy-events' ),
                'type'    => 'yes_no_button',
                'options' => array(
                    'on'  => esc_html__( 'Ja', 'hipsy-events' ),
                    'off' => esc_html__( 'Nee', 'hipsy-events' ),
                ),
                'default'     => 'on',
                'toggle_slug' => 'content',
            ),

            'show_button' => array(
                'label'   => esc_html__( 'Toon Button', 'hipsy-events' ),
                'type'    => 'yes_no_button',
                'options' => array(
                    'on'  => esc_html__( 'Ja', 'hipsy-events' ),
                    'off' => esc_html__( 'Nee', 'hipsy-events' ),
                ),
                'default'     => 'on',
                'toggle_slug' => 'content',
            ),

            'max_words' => array(
                'label'           => esc_html__( 'Max Woorden Beschrijving', 'hipsy-events' ),
                'type'            => 'range',
                'option_category' => 'configuration',
                'range_settings'  => array(
                    'min'  => 0,
                    'max'  => 100,
                    'step' => 5,
                ),
                'default'         => '20',
                'description'     => esc_html__( 'Maximum aantal woorden in beschrijving.', 'hipsy-events' ),
                'toggle_slug'     => 'content',
                'show_if'         => array(
                    'show_description' => 'on',
                ),
            ),

            'button_text' => array(
                'label'           => esc_html__( 'Button Tekst', 'hipsy-events' ),
                'type'            => 'text',
                'option_category' => 'basic_option',
                'default'         => 'Bestel tickets',
                'description'     => esc_html__( 'Tekst op de ticket button.', 'hipsy-events' ),
                'toggle_slug'     => 'content',
                'show_if'         => array(
                    'show_button' => 'on',
                ),
            ),
        );
    }

    public function get_settings_modal_toggles() {
        return array(
            'general' => array(
                'toggles' => array(
                    'layout'  => esc_html__( 'Layout', 'hipsy-events' ),
                    'query'   => esc_html__( 'Query & Filtering', 'hipsy-events' ),
                    'content' => esc_html__( 'Velden Tonen/Verbergen', 'hipsy-events' ),
                ),
            ),
        );
    }

    protected function get_categories_options() {
        $options = array( '' => esc_html__( 'Alle Categorieën', 'hipsy-events' ) );
        
        $terms = get_terms( array(
            'taxonomy'   => 'event_categorie',
            'hide_empty' => false,
        ) );

        if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
            foreach ( $terms as $term ) {
                $options[ $term->term_id ] = $term->name;
            }
        }

        return $options;
    }

    public function render( $attrs, $content, $render_slug ) {
        // Build WP_Query args
        $args = array(
            'post_type'      => 'events',
            'posts_per_page' => intval( $this->props['aantal'] ),
            'post_status'    => 'publish',
            'orderby'        => 'meta_value',
            'meta_key'       => 'hipsy_events_date',
            'order'          => 'ASC',
        );

        // Filter alleen toekomst events
        if ( $this->props['alleen_toekomst'] === 'on' ) {
            $args['meta_query'] = array(
                array(
                    'key'     => 'hipsy_events_date',
                    'value'   => date( 'Y-m-d H:i:s' ),
                    'compare' => '>=',
                    'type'    => 'DATETIME',
                ),
            );
        }

        // Filter op categorie
        if ( ! empty( $this->props['categorie'] ) ) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'event_categorie',
                    'field'    => 'term_id',
                    'terms'    => intval( $this->props['categorie'] ),
                ),
            );
        }

        // Get events
        $query = new WP_Query( $args );

        // Start output
        ob_start();

        if ( $query->have_posts() ) {
            $layout  = $this->props['layout'];
            $columns = $this->props['columns'];
            $gap     = $this->props['gap'];

            // Wrapper
            echo sprintf(
                '<div class="hipsy-divi-grid hipsy-divi-layout-%s" style="display: grid; grid-template-columns: repeat(%s, 1fr); gap: %s;">',
                esc_attr( $layout ),
                esc_attr( $columns ),
                esc_attr( $gap )
            );

            while ( $query->have_posts() ) {
                $query->the_post();

                // Render event card
                if ( function_exists( 'hipsy_render_event_card' ) ) {
                    hipsy_render_event_card( get_the_ID(), array(
                        'layout'             => $layout,
                        'show_image'         => $this->props['show_image'] === 'on',
                        'show_date'          => $this->props['show_date'] === 'on',
                        'show_time'          => $this->props['show_time'] === 'on',
                        'show_title'         => $this->props['show_title'] === 'on',
                        'show_location'      => $this->props['show_location'] === 'on',
                        'show_description'   => $this->props['show_description'] === 'on',
                        'show_price'         => $this->props['show_price'] === 'on',
                        'show_button'        => $this->props['show_button'] === 'on',
                        'max_words'          => intval( $this->props['max_words'] ),
                        'button_text'        => $this->props['button_text'],
                    ) );
                } else {
                    // Fallback als render functie niet beschikbaar is
                    echo '<div class="hew-card">';
                    echo '<h3>' . get_the_title() . '</h3>';
                    echo '<p>' . get_the_excerpt() . '</p>';
                    echo '</div>';
                }
            }

            echo '</div>';

            wp_reset_postdata();
        } else {
            echo '<p>' . esc_html__( 'Geen events gevonden.', 'hipsy-events' ) . '</p>';
        }

        return ob_get_clean();
    }
}

new Hipsy_Divi_Events_Grid;
