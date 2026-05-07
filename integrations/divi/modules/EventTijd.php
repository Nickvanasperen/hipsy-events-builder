<?php
/**
 * Divi Module: Hipsy Event Tijd
 */

class Hipsy_Divi_Event_Tijd extends ET_Builder_Module {

    public $slug       = 'hipsy_event_tijd';
    public $vb_support = 'on';

    public function init() {
        $this->name = esc_html__( 'Event Tijd', 'hipsy-events' );
    }

    public function get_fields() {
        return array(
            'time_format' => array(
                'label'           => esc_html__( 'Tijd Format', 'hipsy-events' ),
                'type'            => 'select',
                'option_category' => 'basic_option',
                'options'         => array(
                    'H:i'   => '14:30',
                    'H.i'   => '14.30',
                    'G:i'   => '14:30 (zonder leading zero)',
                    'g:i A' => '2:30 PM',
                ),
                'default'         => 'H:i',
                'toggle_slug'     => 'main_content',
            ),
            'show_end_time' => array(
                'label'   => esc_html__( 'Toon Eindtijd', 'hipsy-events' ),
                'type'    => 'yes_no_button',
                'options' => array(
                    'on'  => esc_html__( 'Ja', 'hipsy-events' ),
                    'off' => esc_html__( 'Nee', 'hipsy-events' ),
                ),
                'default'     => 'on',
                'toggle_slug' => 'main_content',
            ),
            'separator' => array(
                'label'           => esc_html__( 'Scheiding', 'hipsy-events' ),
                'type'            => 'text',
                'option_category' => 'basic_option',
                'default'         => ' - ',
                'show_if'         => array(
                    'show_end_time' => 'on',
                ),
                'toggle_slug'     => 'main_content',
            ),
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
        );
    }

    public function render( $attrs, $content, $render_slug ) {
        $event_id = get_the_ID();
        $date_start = get_post_meta( $event_id, 'hipsy_events_date', true );
        $date_end = get_post_meta( $event_id, 'hipsy_events_date_end', true );
        
        if ( empty( $date_start ) ) {
            return '<p>' . esc_html__( 'Geen tijd beschikbaar', 'hipsy-events' ) . '</p>';
        }

        $format = $this->props['time_format'];
        
        // Parse start tijd
        $start_obj = DateTime::createFromFormat( 'Y-m-d\TH:i', $date_start );
        $time_output = $start_obj ? $start_obj->format( $format ) : '';

        // Eindtijd
        if ( $this->props['show_end_time'] === 'on' && ! empty( $date_end ) ) {
            $end_obj = DateTime::createFromFormat( 'Y-m-d\TH:i', $date_end );
            if ( $end_obj ) {
                $time_output .= $this->props['separator'] . $end_obj->format( $format );
            }
        }

        $icon = $this->props['show_icon'] === 'on' ? '<span class="hipsy-icon">🕐</span> ' : '';

        return sprintf(
            '<div class="hipsy-event-tijd">%s%s</div>',
            $icon,
            esc_html( $time_output )
        );
    }
}

new Hipsy_Divi_Event_Tijd;
