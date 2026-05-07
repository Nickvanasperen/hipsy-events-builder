<?php
/**
 * Divi Module: Hipsy Event Ticket Knop
 */

class Hipsy_Divi_Event_Ticketknop extends ET_Builder_Module {

    public $slug       = 'hipsy_event_ticketknop';
    public $vb_support = 'on';

    public function init() {
        $this->name = esc_html__( 'Event Ticket Knop', 'hipsy-events' );
    }

    public function get_fields() {
        return array(
            'button_text' => array(
                'label'           => esc_html__( 'Button Tekst', 'hipsy-events' ),
                'type'            => 'text',
                'option_category' => 'basic_option',
                'default'         => 'Bestel tickets',
                'toggle_slug'     => 'main_content',
            ),
            'button_style' => array(
                'label'           => esc_html__( 'Button Stijl', 'hipsy-events' ),
                'type'            => 'select',
                'option_category' => 'layout',
                'options'         => array(
                    'primary'   => 'Primary (paars)',
                    'secondary' => 'Secondary (groen)',
                    'outline'   => 'Outline',
                    'custom'    => 'Custom',
                ),
                'default'         => 'primary',
                'toggle_slug'     => 'design',
            ),
            'button_size' => array(
                'label'           => esc_html__( 'Button Grootte', 'hipsy-events' ),
                'type'            => 'select',
                'option_category' => 'layout',
                'options'         => array(
                    'small'  => 'Klein',
                    'medium' => 'Medium',
                    'large'  => 'Groot',
                ),
                'default'         => 'medium',
                'toggle_slug'     => 'design',
            ),
            'full_width' => array(
                'label'   => esc_html__( 'Volledige Breedte', 'hipsy-events' ),
                'type'    => 'yes_no_button',
                'options' => array(
                    'on'  => esc_html__( 'Ja', 'hipsy-events' ),
                    'off' => esc_html__( 'Nee', 'hipsy-events' ),
                ),
                'default'     => 'off',
                'toggle_slug' => 'design',
            ),
            'icon' => array(
                'label'           => esc_html__( 'Icoon', 'hipsy-events' ),
                'type'            => 'select',
                'option_category' => 'configuration',
                'options'         => array(
                    'none'   => 'Geen',
                    'ticket' => '🎟️ Ticket',
                    'arrow'  => '→ Pijl',
                    'cart'   => '🛒 Winkelwagen',
                ),
                'default'         => 'ticket',
                'toggle_slug'     => 'main_content',
            ),
            'open_new_tab' => array(
                'label'   => esc_html__( 'Open in Nieuw Tabblad', 'hipsy-events' ),
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

    public function get_settings_modal_toggles() {
        return array(
            'general' => array(
                'toggles' => array(
                    'main_content' => esc_html__( 'Content', 'hipsy-events' ),
                    'design'       => esc_html__( 'Design', 'hipsy-events' ),
                ),
            ),
        );
    }

    public function render( $attrs, $content, $render_slug ) {
        $event_id = get_the_ID();
        $ticket_url = get_post_meta( $event_id, 'hipsy_events_link', true );
        
        if ( empty( $ticket_url ) ) {
            return '<p>' . esc_html__( 'Geen ticket link beschikbaar', 'hipsy-events' ) . '</p>';
        }

        // Button classes
        $classes = array( 'hipsy-ticket-btn' );
        $classes[] = 'hipsy-btn-' . $this->props['button_style'];
        $classes[] = 'hipsy-btn-' . $this->props['button_size'];
        if ( $this->props['full_width'] === 'on' ) {
            $classes[] = 'hipsy-btn-fullwidth';
        }

        // Icon
        $icon = '';
        switch ( $this->props['icon'] ) {
            case 'ticket':
                $icon = '🎟️ ';
                break;
            case 'arrow':
                $icon = '→ ';
                break;
            case 'cart':
                $icon = '🛒 ';
                break;
        }

        // Target
        $target = $this->props['open_new_tab'] === 'on' ? ' target="_blank" rel="noopener"' : '';

        return sprintf(
            '<a href="%s" class="%s"%s>%s%s</a>',
            esc_url( $ticket_url ),
            esc_attr( implode( ' ', $classes ) ),
            $target,
            $icon,
            esc_html( $this->props['button_text'] )
        );
    }
}

new Hipsy_Divi_Event_Ticketknop;
