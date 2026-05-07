<?php
/**
 * Hipsy Events - Individual Field Shortcodes
 * 
 * Voor custom templates met volledige controle.
 * Gebruik: [event_title], [event_date], etc.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ═══════════════════════════════════════════════════════
// INDIVIDUAL FIELD SHORTCODES
// ═══════════════════════════════════════════════════════

/**
 * Event Title
 * [event_title link="yes" tag="h2"]
 */
add_shortcode( 'event_title', function( $atts ) {
    $atts = shortcode_atts( array(
        'link' => 'no',
        'tag'  => 'span',
    ), $atts );
    
    $title = get_the_title();
    
    if ( $atts['link'] === 'yes' ) {
        return sprintf(
            '<%1$s><a href="%2$s">%3$s</a></%1$s>',
            esc_attr( $atts['tag'] ),
            esc_url( get_permalink() ),
            esc_html( $title )
        );
    }
    
    return sprintf( '<%s>%s</%s>', esc_attr( $atts['tag'] ), esc_html( $title ), esc_attr( $atts['tag'] ) );
});

/**
 * Event Date
 * [event_date format="d M Y"]
 */
add_shortcode( 'event_date', function( $atts ) {
    $atts = shortcode_atts( array(
        'format' => 'd M Y',
    ), $atts );
    
    $date_raw = get_post_meta( get_the_ID(), 'hipsy_events_date', true );
    if ( empty( $date_raw ) ) return '';
    
    $date_obj = DateTime::createFromFormat( 'Y-m-d\TH:i', $date_raw );
    if ( ! $date_obj ) return '';
    
    $formatted = $date_obj->format( $atts['format'] );
    
    // Nederlandse maanden
    $nl_months = array(
        'January' => 'januari', 'February' => 'februari', 'March' => 'maart',
        'April' => 'april', 'May' => 'mei', 'June' => 'juni',
        'July' => 'juli', 'August' => 'augustus', 'September' => 'september',
        'October' => 'oktober', 'November' => 'november', 'December' => 'december',
        'Jan' => 'jan', 'Feb' => 'feb', 'Mar' => 'mrt', 'Apr' => 'apr',
        'May' => 'mei', 'Jun' => 'jun', 'Jul' => 'jul', 'Aug' => 'aug',
        'Sep' => 'sep', 'Oct' => 'okt', 'Nov' => 'nov', 'Dec' => 'dec',
        'Monday' => 'maandag', 'Tuesday' => 'dinsdag', 'Wednesday' => 'woensdag',
        'Thursday' => 'donderdag', 'Friday' => 'vrijdag', 'Saturday' => 'zaterdag', 'Sunday' => 'zondag',
        'Mon' => 'ma', 'Tue' => 'di', 'Wed' => 'wo', 'Thu' => 'do',
        'Fri' => 'vr', 'Sat' => 'za', 'Sun' => 'zo',
    );
    
    return str_replace( array_keys( $nl_months ), array_values( $nl_months ), $formatted );
});

/**
 * Event Time
 * [event_time format="H:i" end="yes" separator=" - "]
 */
add_shortcode( 'event_time', function( $atts ) {
    $atts = shortcode_atts( array(
        'format'    => 'H:i',
        'end'       => 'yes',
        'separator' => ' - ',
    ), $atts );
    
    $date_start = get_post_meta( get_the_ID(), 'hipsy_events_date', true );
    if ( empty( $date_start ) ) return '';
    
    $start_obj = DateTime::createFromFormat( 'Y-m-d\TH:i', $date_start );
    if ( ! $start_obj ) return '';
    
    $output = $start_obj->format( $atts['format'] );
    
    if ( $atts['end'] === 'yes' ) {
        $date_end = get_post_meta( get_the_ID(), 'hipsy_events_date_end', true );
        if ( ! empty( $date_end ) ) {
            $end_obj = DateTime::createFromFormat( 'Y-m-d\TH:i', $date_end );
            if ( $end_obj ) {
                $output .= $atts['separator'] . $end_obj->format( $atts['format'] );
            }
        }
    }
    
    return $output;
});

/**
 * Event Location
 * [event_location link="yes"]
 */
add_shortcode( 'event_location', function( $atts ) {
    $atts = shortcode_atts( array(
        'link' => 'no',
    ), $atts );
    
    $location = get_post_meta( get_the_ID(), 'hipsy_events_location', true );
    if ( empty( $location ) ) return '';
    
    if ( $atts['link'] === 'yes' ) {
        $maps_url = 'https://www.google.com/maps/search/?api=1&query=' . urlencode( $location );
        return sprintf(
            '<a href="%s" target="_blank" rel="noopener">%s</a>',
            esc_url( $maps_url ),
            esc_html( $location )
        );
    }
    
    return esc_html( $location );
});

/**
 * Event Description
 * [event_description length="100" readmore="yes"]
 */
add_shortcode( 'event_description', function( $atts ) {
    $atts = shortcode_atts( array(
        'length'   => '0',
        'readmore' => 'no',
    ), $atts );
    
    $description = get_post_field( 'post_content', get_the_ID() );
    if ( empty( $description ) ) return '';
    
    $length = intval( $atts['length'] );
    
    if ( $length > 0 ) {
        $words = explode( ' ', strip_tags( $description ) );
        if ( count( $words ) > $length ) {
            $description = implode( ' ', array_slice( $words, 0, $length ) ) . '...';
            
            if ( $atts['readmore'] === 'yes' ) {
                $description .= ' <a href="' . esc_url( get_permalink() ) . '">Lees meer</a>';
            }
        } else {
            $description = implode( ' ', $words );
        }
        
        return $description;
    }
    
    return apply_filters( 'the_content', $description );
});

/**
 * Event Image
 * [event_image size="large" link="yes"]
 */
add_shortcode( 'event_image', function( $atts ) {
    $atts = shortcode_atts( array(
        'size' => 'large',
        'link' => 'no',
    ), $atts );
    
    $image_url = get_the_post_thumbnail_url( get_the_ID(), $atts['size'] );
    if ( empty( $image_url ) ) return '';
    
    $img_tag = sprintf(
        '<img src="%s" alt="%s">',
        esc_url( $image_url ),
        esc_attr( get_the_title() )
    );
    
    if ( $atts['link'] === 'yes' ) {
        return sprintf(
            '<a href="%s">%s</a>',
            esc_url( get_permalink() ),
            $img_tag
        );
    }
    
    return $img_tag;
});

/**
 * Event Categories
 * [event_categories separator=", " link="yes"]
 */
add_shortcode( 'event_categories', function( $atts ) {
    $atts = shortcode_atts( array(
        'separator' => ', ',
        'link'      => 'no',
    ), $atts );
    
    $terms = get_the_terms( get_the_ID(), 'event_categorie' );
    if ( ! $terms || is_wp_error( $terms ) ) return '';
    
    $output = array();
    
    foreach ( $terms as $term ) {
        if ( $atts['link'] === 'yes' ) {
            $output[] = sprintf(
                '<a href="%s">%s</a>',
                esc_url( get_term_link( $term ) ),
                esc_html( $term->name )
            );
        } else {
            $output[] = esc_html( $term->name );
        }
    }
    
    return implode( $atts['separator'], $output );
});

/**
 * Event Price (from first ticket)
 * [event_price prefix="Vanaf €"]
 */
add_shortcode( 'event_price', function( $atts ) {
    $atts = shortcode_atts( array(
        'prefix' => '',
        'free_text' => 'Gratis',
    ), $atts );
    
    $ticket_info = get_post_meta( get_the_ID(), 'hipsy_ticket_info', true );
    if ( empty( $ticket_info ) ) return '';
    
    $tickets = maybe_unserialize( $ticket_info );
    if ( ! is_array( $tickets ) || empty( $tickets ) ) return '';
    
    // Vind laagste prijs
    $lowest = null;
    foreach ( $tickets as $ticket ) {
        $price = isset( $ticket['price'] ) ? floatval( $ticket['price'] ) : 0;
        if ( $lowest === null || $price < $lowest ) {
            $lowest = $price;
        }
    }
    
    if ( $lowest === 0 ) {
        return esc_html( $atts['free_text'] );
    }
    
    return $atts['prefix'] . ' €' . number_format( $lowest, 2, ',', '.' );
});

/**
 * Event Ticket URL
 * [event_ticket_url]
 */
add_shortcode( 'event_ticket_url', function() {
    return esc_url( get_post_meta( get_the_ID(), 'hipsy_events_link', true ) );
});

/**
 * Event Permalink
 * [event_link]
 */
add_shortcode( 'event_link', function() {
    return esc_url( get_permalink() );
});

/**
 * Event Button
 * [event_button text="Aanmelden →" class="btn-aanmelden"]
 */
add_shortcode( 'event_button', function( $atts ) {
    $atts = shortcode_atts( array(
        'text'   => 'Bestel tickets',
        'class'  => 'hipsy-ticket-btn',
        'target' => '_blank',
    ), $atts );
    
    $ticket_url = get_post_meta( get_the_ID(), 'hipsy_events_link', true );
    if ( empty( $ticket_url ) ) return '';
    
    return sprintf(
        '<a href="%s" class="%s" target="%s" rel="noopener">%s</a>',
        esc_url( $ticket_url ),
        esc_attr( $atts['class'] ),
        esc_attr( $atts['target'] ),
        esc_html( $atts['text'] )
    );
});
