<?php
/**
 * Divi Module: Hipsy Event Tickets
 */

class Hipsy_Divi_Event_Tickets extends ET_Builder_Module {

    public $slug       = 'hipsy_event_tickets';
    public $vb_support = 'on';

    public function init() {
        $this->name = esc_html__( 'Event Tickets Info', 'hipsy-events' );
    }

    public function get_fields() {
        return array(
            'show_title' => array(
                'label'   => esc_html__( 'Toon "Tickets" Titel', 'hipsy-events' ),
                'type'    => 'yes_no_button',
                'options' => array(
                    'on'  => esc_html__( 'Ja', 'hipsy-events' ),
                    'off' => esc_html__( 'Nee', 'hipsy-events' ),
                ),
                'default'     => 'on',
                'toggle_slug' => 'main_content',
            ),
            'title_text' => array(
                'label'           => esc_html__( 'Titel Tekst', 'hipsy-events' ),
                'type'            => 'text',
                'option_category' => 'basic_option',
                'default'         => 'Tickets',
                'show_if'         => array(
                    'show_title' => 'on',
                ),
                'toggle_slug'     => 'main_content',
            ),
            'show_prices' => array(
                'label'   => esc_html__( 'Toon Prijzen', 'hipsy-events' ),
                'type'    => 'yes_no_button',
                'options' => array(
                    'on'  => esc_html__( 'Ja', 'hipsy-events' ),
                    'off' => esc_html__( 'Nee', 'hipsy-events' ),
                ),
                'default'     => 'on',
                'toggle_slug' => 'main_content',
            ),
            'show_availability' => array(
                'label'   => esc_html__( 'Toon Beschikbaarheid', 'hipsy-events' ),
                'type'    => 'yes_no_button',
                'options' => array(
                    'on'  => esc_html__( 'Ja', 'hipsy-events' ),
                    'off' => esc_html__( 'Nee', 'hipsy-events' ),
                ),
                'default'     => 'on',
                'toggle_slug' => 'main_content',
            ),
            'layout' => array(
                'label'           => esc_html__( 'Layout', 'hipsy-events' ),
                'type'            => 'select',
                'option_category' => 'layout',
                'options'         => array(
                    'list'  => 'Lijst',
                    'table' => 'Tabel',
                    'cards' => 'Cards',
                ),
                'default'         => 'list',
                'toggle_slug'     => 'main_content',
            ),
        );
    }

    public function render( $attrs, $content, $render_slug ) {
        $event_id = get_the_ID();
        $ticket_info = get_post_meta( $event_id, 'hipsy_ticket_info', true );
        
        if ( empty( $ticket_info ) ) {
            return '<p>' . esc_html__( 'Geen ticket informatie beschikbaar', 'hipsy-events' ) . '</p>';
        }

        $tickets = maybe_unserialize( $ticket_info );
        if ( ! is_array( $tickets ) || empty( $tickets ) ) {
            return '<p>' . esc_html__( 'Geen tickets beschikbaar', 'hipsy-events' ) . '</p>';
        }

        $output = '<div class="hipsy-event-tickets">';

        // Title
        if ( $this->props['show_title'] === 'on' ) {
            $output .= sprintf(
                '<h3 class="tickets-title">%s</h3>',
                esc_html( $this->props['title_text'] )
            );
        }

        // Layout
        $layout = $this->props['layout'];
        
        switch ( $layout ) {
            case 'table':
                $output .= '<table class="tickets-table">';
                $output .= '<thead><tr>';
                $output .= '<th>Ticket Type</th>';
                if ( $this->props['show_prices'] === 'on' ) {
                    $output .= '<th>Prijs</th>';
                }
                if ( $this->props['show_availability'] === 'on' ) {
                    $output .= '<th>Beschikbaar</th>';
                }
                $output .= '</tr></thead><tbody>';
                
                foreach ( $tickets as $ticket ) {
                    $output .= '<tr>';
                    $output .= '<td>' . esc_html( $ticket['name'] ?? 'Ticket' ) . '</td>';
                    if ( $this->props['show_prices'] === 'on' ) {
                        $output .= '<td>€' . esc_html( $ticket['price'] ?? '0.00' ) . '</td>';
                    }
                    if ( $this->props['show_availability'] === 'on' ) {
                        $sold_out = isset( $ticket['sold_out'] ) && $ticket['sold_out'];
                        $output .= '<td>' . ( $sold_out ? '❌ Uitverkocht' : '✅ Beschikbaar' ) . '</td>';
                    }
                    $output .= '</tr>';
                }
                
                $output .= '</tbody></table>';
                break;

            case 'cards':
                $output .= '<div class="tickets-cards">';
                foreach ( $tickets as $ticket ) {
                    $output .= '<div class="ticket-card">';
                    $output .= '<div class="ticket-name">' . esc_html( $ticket['name'] ?? 'Ticket' ) . '</div>';
                    if ( $this->props['show_prices'] === 'on' ) {
                        $output .= '<div class="ticket-price">€' . esc_html( $ticket['price'] ?? '0.00' ) . '</div>';
                    }
                    if ( $this->props['show_availability'] === 'on' ) {
                        $sold_out = isset( $ticket['sold_out'] ) && $ticket['sold_out'];
                        $output .= '<div class="ticket-status">' . ( $sold_out ? '❌ Uitverkocht' : '✅ Beschikbaar' ) . '</div>';
                    }
                    $output .= '</div>';
                }
                $output .= '</div>';
                break;

            default: // list
                $output .= '<ul class="tickets-list">';
                foreach ( $tickets as $ticket ) {
                    $output .= '<li class="ticket-item">';
                    $output .= '<span class="ticket-name">' . esc_html( $ticket['name'] ?? 'Ticket' ) . '</span>';
                    if ( $this->props['show_prices'] === 'on' ) {
                        $output .= ' - <span class="ticket-price">€' . esc_html( $ticket['price'] ?? '0.00' ) . '</span>';
                    }
                    if ( $this->props['show_availability'] === 'on' ) {
                        $sold_out = isset( $ticket['sold_out'] ) && $ticket['sold_out'];
                        $output .= ' <span class="ticket-status">' . ( $sold_out ? '(Uitverkocht)' : '' ) . '</span>';
                    }
                    $output .= '</li>';
                }
                $output .= '</ul>';
                break;
        }

        $output .= '</div>';

        return $output;
    }
}

new Hipsy_Divi_Event_Tickets;
