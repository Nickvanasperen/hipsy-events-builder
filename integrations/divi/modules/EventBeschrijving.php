<?php
/**
 * Divi Module: Hipsy Event Beschrijving
 */

class Hipsy_Divi_Event_Beschrijving extends ET_Builder_Module {

    public $slug       = 'hipsy_event_beschrijving';
    public $vb_support = 'on';

    public function init() {
        $this->name = esc_html__( 'Event Beschrijving', 'hipsy-events' );
    }

    public function get_fields() {
        return array(
            'length_type' => array(
                'label'           => esc_html__( 'Lengte', 'hipsy-events' ),
                'type'            => 'select',
                'option_category' => 'basic_option',
                'options'         => array(
                    'full'    => 'Volledige beschrijving',
                    'excerpt' => 'Excerpt (kort)',
                    'words'   => 'Beperkt aantal woorden',
                ),
                'default'         => 'full',
                'toggle_slug'     => 'main_content',
            ),
            'max_words' => array(
                'label'           => esc_html__( 'Max Woorden', 'hipsy-events' ),
                'type'            => 'range',
                'option_category' => 'basic_option',
                'range_settings'  => array(
                    'min'  => 10,
                    'max'  => 200,
                    'step' => 5,
                ),
                'default'         => '50',
                'show_if'         => array(
                    'length_type' => 'words',
                ),
                'toggle_slug'     => 'main_content',
            ),
            'show_read_more' => array(
                'label'   => esc_html__( 'Toon "Lees meer"', 'hipsy-events' ),
                'type'    => 'yes_no_button',
                'options' => array(
                    'on'  => esc_html__( 'Ja', 'hipsy-events' ),
                    'off' => esc_html__( 'Nee', 'hipsy-events' ),
                ),
                'default'     => 'on',
                'show_if'     => array(
                    'length_type' => array( 'excerpt', 'words' ),
                ),
                'toggle_slug' => 'main_content',
            ),
            'read_more_text' => array(
                'label'           => esc_html__( '"Lees meer" Tekst', 'hipsy-events' ),
                'type'            => 'text',
                'option_category' => 'basic_option',
                'default'         => 'Lees meer',
                'show_if'         => array(
                    'show_read_more' => 'on',
                ),
                'toggle_slug'     => 'main_content',
            ),
        );
    }

    public function render( $attrs, $content, $render_slug ) {
        $event_id = get_the_ID();
        $description = get_post_field( 'post_content', $event_id );
        
        if ( empty( $description ) ) {
            return '<p>' . esc_html__( 'Geen beschrijving beschikbaar', 'hipsy-events' ) . '</p>';
        }

        $length_type = $this->props['length_type'];
        $output = '';

        switch ( $length_type ) {
            case 'excerpt':
                $output = get_the_excerpt( $event_id );
                break;
                
            case 'words':
                $max_words = intval( $this->props['max_words'] );
                $words = explode( ' ', strip_tags( $description ) );
                if ( count( $words ) > $max_words ) {
                    $output = implode( ' ', array_slice( $words, 0, $max_words ) ) . '...';
                } else {
                    $output = implode( ' ', $words );
                }
                break;
                
            default: // full
                $output = apply_filters( 'the_content', $description );
                break;
        }

        // Read more link
        if ( $length_type !== 'full' && $this->props['show_read_more'] === 'on' ) {
            $read_more_text = $this->props['read_more_text'];
            $output .= sprintf(
                ' <a href="%s" class="hipsy-read-more">%s</a>',
                esc_url( get_permalink( $event_id ) ),
                esc_html( $read_more_text )
            );
        }

        return sprintf(
            '<div class="hipsy-event-beschrijving">%s</div>',
            $output
        );
    }
}

new Hipsy_Divi_Event_Beschrijving;
