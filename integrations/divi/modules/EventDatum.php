<?php
/**
 * Divi Module: Hipsy Event Datum
 */

class Hipsy_Divi_Event_Datum extends ET_Builder_Module {

    public $slug       = 'hipsy_event_datum';
    public $vb_support = 'on';

    public function init() {
        $this->name = esc_html__( 'Event Datum', 'hipsy-events' );
    }

    public function get_fields() {
        return array(
            'date_format' => array(
                'label'           => esc_html__( 'Datum Format', 'hipsy-events' ),
                'type'            => 'select',
                'option_category' => 'basic_option',
                'options'         => array(
                    'd-m-Y'     => '03-05-2025',
                    'd M Y'     => '03 Mei 2025',
                    'j F Y'     => '3 Mei 2025',
                    'l j F Y'   => 'Zaterdag 3 Mei 2025',
                    'D j M'     => 'Za 3 Mei',
                    'custom'    => 'Custom',
                ),
                'default'         => 'd M Y',
                'toggle_slug'     => 'main_content',
            ),
            'custom_format' => array(
                'label'           => esc_html__( 'Custom Format', 'hipsy-events' ),
                'type'            => 'text',
                'option_category' => 'basic_option',
                'default'         => 'd-m-Y',
                'show_if'         => array(
                    'date_format' => 'custom',
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
        $date_raw = get_post_meta( $event_id, 'hipsy_events_date', true );
        
        if ( empty( $date_raw ) ) {
            return '<p>' . esc_html__( 'Geen datum beschikbaar', 'hipsy-events' ) . '</p>';
        }

        // Parse datum
        $date_obj = DateTime::createFromFormat( 'Y-m-d\TH:i', $date_raw );
        if ( ! $date_obj ) {
            return '<p>' . esc_html( $date_raw ) . '</p>';
        }

        // Format bepalen
        $format = $this->props['date_format'];
        if ( $format === 'custom' ) {
            $format = $this->props['custom_format'];
        }

        // Nederlandse maanden
        $months_nl = array(
            'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maart',
            'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
            'July' => 'Juli', 'August' => 'Augustus', 'September' => 'September',
            'October' => 'Oktober', 'November' => 'November', 'December' => 'December',
            'Monday' => 'Maandag', 'Tuesday' => 'Dinsdag', 'Wednesday' => 'Woensdag',
            'Thursday' => 'Donderdag', 'Friday' => 'Vrijdag', 'Saturday' => 'Zaterdag', 'Sunday' => 'Zondag',
            'Mon' => 'Ma', 'Tue' => 'Di', 'Wed' => 'Wo', 'Thu' => 'Do', 
            'Fri' => 'Vr', 'Sat' => 'Za', 'Sun' => 'Zo',
        );

        $formatted_date = $date_obj->format( $format );
        $formatted_date = str_replace( array_keys( $months_nl ), array_values( $months_nl ), $formatted_date );

        $icon = $this->props['show_icon'] === 'on' ? '<span class="hipsy-icon">📅</span> ' : '';

        return sprintf(
            '<div class="hipsy-event-datum">%s%s</div>',
            $icon,
            esc_html( $formatted_date )
        );
    }
}

new Hipsy_Divi_Event_Datum;
