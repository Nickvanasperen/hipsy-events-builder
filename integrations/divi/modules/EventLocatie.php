<?php
/**
 * Divi Module: Hipsy Event Locatie
 */

class Hipsy_Divi_Event_Locatie extends ET_Builder_Module {

    public $slug       = 'hipsy_event_locatie';
    public $vb_support = 'on';

    public function init() {
        $this->name = esc_html__( 'Event Locatie', 'hipsy-events' );
    }

    public function get_fields() {
        return array(
            'show_icon' => array(
                'label'   => esc_html__( 'Toon Icoon', 'hipsy-events' ),
                'type'    => 'yes_no_button',
                'options' => array(
                    'on'  => esc_html__( 'Ja', 'hipsy-events' ),
                    'off' => esc_html__( 'Nee', 'hipsy-events' ),
                ),
                'default'     => 'on',
                'toggle_slug' => 'main_content',
            ),
            'show_map' => array(
                'label'   => esc_html__( 'Toon Google Maps', 'hipsy-events' ),
                'type'    => 'yes_no_button',
                'options' => array(
                    'on'  => esc_html__( 'Ja', 'hipsy-events' ),
                    'off' => esc_html__( 'Nee', 'hipsy-events' ),
                ),
                'default'     => 'off',
                'toggle_slug' => 'main_content',
            ),
            'map_height' => array(
                'label'           => esc_html__( 'Map Hoogte', 'hipsy-events' ),
                'type'            => 'range',
                'option_category' => 'layout',
                'range_settings'  => array(
                    'min'  => 100,
                    'max'  => 600,
                    'step' => 10,
                ),
                'default'         => '300px',
                'default_unit'    => 'px',
                'show_if'         => array(
                    'show_map' => 'on',
                ),
                'toggle_slug'     => 'main_content',
            ),
            'link_to_maps' => array(
                'label'   => esc_html__( 'Link naar Google Maps', 'hipsy-events' ),
                'type'    => 'yes_no_button',
                'options' => array(
                    'on'  => esc_html__( 'Ja', 'hipsy-events' ),
                    'off' => esc_html__( 'Nee', 'hipsy-events' ),
                ),
                'default'     => 'on',
                'toggle_slug' => 'main_content',
            ),
        );
    }

    public function render( $attrs, $content, $render_slug ) {
        $event_id = get_the_ID();
        $location = get_post_meta( $event_id, 'hipsy_events_location', true );
        
        if ( empty( $location ) ) {
            return '<p>' . esc_html__( 'Geen locatie beschikbaar', 'hipsy-events' ) . '</p>';
        }

        $icon = $this->props['show_icon'] === 'on' ? '<span class="hipsy-icon">📍</span> ' : '';
        
        $output = '<div class="hipsy-event-locatie">';
        
        // Locatie tekst (met of zonder link)
        if ( $this->props['link_to_maps'] === 'on' ) {
            $maps_url = 'https://www.google.com/maps/search/?api=1&query=' . urlencode( $location );
            $output .= sprintf(
                '<div class="locatie-text">%s<a href="%s" target="_blank" rel="noopener">%s</a></div>',
                $icon,
                esc_url( $maps_url ),
                esc_html( $location )
            );
        } else {
            $output .= sprintf(
                '<div class="locatie-text">%s%s</div>',
                $icon,
                esc_html( $location )
            );
        }

        // Google Maps embed
        if ( $this->props['show_map'] === 'on' ) {
            $map_height = $this->props['map_height'];
            $embed_url = 'https://www.google.com/maps/embed/v1/place?key=YOUR_API_KEY&q=' . urlencode( $location );
            
            $output .= sprintf(
                '<div class="locatie-map" style="margin-top:15px;">
                    <iframe 
                        width="100%%" 
                        height="%s" 
                        frameborder="0" 
                        style="border:0" 
                        src="%s" 
                        allowfullscreen>
                    </iframe>
                </div>',
                esc_attr( $map_height ),
                esc_url( $embed_url )
            );
        }

        $output .= '</div>';

        return $output;
    }
}

new Hipsy_Divi_Event_Locatie;
