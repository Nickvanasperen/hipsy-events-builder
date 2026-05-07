<?php
/**
 * Divi Module: Hipsy Event Titel
 */

class Hipsy_Divi_Event_Titel extends ET_Builder_Module {

    public $slug       = 'hipsy_event_titel';
    public $vb_support = 'on';

    public function init() {
        $this->name = esc_html__( 'Event Titel', 'hipsy-events' );
    }

    public function get_fields() {
        return array(
            'html_tag' => array(
                'label'           => esc_html__( 'HTML Tag', 'hipsy-events' ),
                'type'            => 'select',
                'option_category' => 'basic_option',
                'options'         => array(
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'p'  => 'P',
                    'div' => 'Div',
                ),
                'default'         => 'h2',
                'toggle_slug'     => 'main_content',
            ),
            'link_to_event' => array(
                'label'           => esc_html__( 'Link naar Event', 'hipsy-events' ),
                'type'            => 'yes_no_button',
                'option_category' => 'configuration',
                'options'         => array(
                    'on'  => esc_html__( 'Ja', 'hipsy-events' ),
                    'off' => esc_html__( 'Nee', 'hipsy-events' ),
                ),
                'default'         => 'on',
                'toggle_slug'     => 'main_content',
            ),
        );
    }

    public function render( $attrs, $content, $render_slug ) {
        $event_id = get_the_ID();
        $title = get_the_title( $event_id );
        $tag = $this->props['html_tag'];
        $link = $this->props['link_to_event'] === 'on';

        if ( $link ) {
            $output = sprintf(
                '<%1$s class="hipsy-event-titel"><a href="%2$s">%3$s</a></%1$s>',
                esc_attr( $tag ),
                esc_url( get_permalink( $event_id ) ),
                esc_html( $title )
            );
        } else {
            $output = sprintf(
                '<%1$s class="hipsy-event-titel">%2$s</%1$s>',
                esc_attr( $tag ),
                esc_html( $title )
            );
        }

        return $output;
    }
}

new Hipsy_Divi_Event_Titel;
