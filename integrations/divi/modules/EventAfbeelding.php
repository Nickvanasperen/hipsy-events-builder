<?php
/**
 * Divi Module: Hipsy Event Afbeelding
 */

class Hipsy_Divi_Event_Afbeelding extends ET_Builder_Module {

    public $slug       = 'hipsy_event_afbeelding';
    public $vb_support = 'on';

    public function init() {
        $this->name = esc_html__( 'Event Afbeelding', 'hipsy-events' );
    }

    public function get_fields() {
        return array(
            'image_size' => array(
                'label'           => esc_html__( 'Afbeelding Grootte', 'hipsy-events' ),
                'type'            => 'select',
                'option_category' => 'layout',
                'options'         => array(
                    'thumbnail' => 'Thumbnail (150x150)',
                    'medium'    => 'Medium (300x300)',
                    'large'     => 'Large (1024x1024)',
                    'full'      => 'Full (origineel)',
                ),
                'default'         => 'large',
                'toggle_slug'     => 'main_content',
            ),
            'link_to_event' => array(
                'label'   => esc_html__( 'Link naar Event', 'hipsy-events' ),
                'type'    => 'yes_no_button',
                'options' => array(
                    'on'  => esc_html__( 'Ja', 'hipsy-events' ),
                    'off' => esc_html__( 'Nee', 'hipsy-events' ),
                ),
                'default'     => 'on',
                'toggle_slug' => 'main_content',
            ),
            'enable_lightbox' => array(
                'label'   => esc_html__( 'Lightbox', 'hipsy-events' ),
                'type'    => 'yes_no_button',
                'options' => array(
                    'on'  => esc_html__( 'Ja', 'hipsy-events' ),
                    'off' => esc_html__( 'Nee', 'hipsy-events' ),
                ),
                'default'     => 'off',
                'show_if'     => array(
                    'link_to_event' => 'off',
                ),
                'toggle_slug' => 'main_content',
            ),
            'border_radius' => array(
                'label'           => esc_html__( 'Border Radius', 'hipsy-events' ),
                'type'            => 'range',
                'option_category' => 'layout',
                'range_settings'  => array(
                    'min'  => 0,
                    'max'  => 50,
                    'step' => 1,
                ),
                'default'         => '0px',
                'default_unit'    => 'px',
                'toggle_slug'     => 'main_content',
            ),
        );
    }

    public function render( $attrs, $content, $render_slug ) {
        $event_id = get_the_ID();
        $image_url = get_the_post_thumbnail_url( $event_id, $this->props['image_size'] );
        
        if ( empty( $image_url ) ) {
            return '<p>' . esc_html__( 'Geen afbeelding beschikbaar', 'hipsy-events' ) . '</p>';
        }

        $border_radius = $this->props['border_radius'];
        $style = ! empty( $border_radius ) ? sprintf( 'border-radius:%s;', esc_attr( $border_radius ) ) : '';

        $img_tag = sprintf(
            '<img src="%s" alt="%s" class="hipsy-event-image" style="%s">',
            esc_url( $image_url ),
            esc_attr( get_the_title( $event_id ) ),
            $style
        );

        // Wrap in link
        if ( $this->props['link_to_event'] === 'on' ) {
            $output = sprintf(
                '<a href="%s" class="hipsy-event-image-link">%s</a>',
                esc_url( get_permalink( $event_id ) ),
                $img_tag
            );
        } elseif ( $this->props['enable_lightbox'] === 'on' ) {
            $full_image_url = get_the_post_thumbnail_url( $event_id, 'full' );
            $output = sprintf(
                '<a href="%s" class="hipsy-event-image-link" data-lightbox="event-%s">%s</a>',
                esc_url( $full_image_url ),
                esc_attr( $event_id ),
                $img_tag
            );
        } else {
            $output = $img_tag;
        }

        return sprintf(
            '<div class="hipsy-event-afbeelding">%s</div>',
            $output
        );
    }
}

new Hipsy_Divi_Event_Afbeelding;
