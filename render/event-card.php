<?php
/**
 * Event Card Renderer — v4.0
 * 
 * Flexibel card systeem gebruikt door:
 * - Grid layout
 * - List layout
 * - Carousel layout
 * - Shortcodes
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Render een event card met alle configureerbare opties
 * 
 * @param int $event_id Event post ID
 * @param array $options Rendering options
 */
function hipsy_render_event_card( $event_id, $options = [] ) {
    
    $defaults = [
        'layout'             => 'grid',          // grid, list, carousel
        'orientation'        => 'column',        // column, row, column-reverse, row-reverse
        'show_image'         => true,
        'show_date'          => true,
        'show_time'          => true,
        'show_title'         => true,
        'show_location'      => true,
        'show_description'   => true,
        'show_price'         => true,
        'show_button'        => true,
        'show_icon_date'     => true,
        'show_icon_time'     => true,
        'show_icon_location' => true,
        'max_words'          => 20,
        'button_text'        => 'Bestel tickets',
        'date_format'        => 'j F Y',
        'wrapper_class'      => 'hew-card',
    ];
    
    $opts = wp_parse_args( $options, $defaults );
    
    // Get event data (juiste meta field namen)
    $titel         = get_the_title( $event_id );
    $link          = get_permalink( $event_id );
    $afbeelding    = get_the_post_thumbnail_url( $event_id, 'medium' );
    $start_datum   = get_post_meta( $event_id, 'hipsy_events_date', true );
    $eind_datum    = get_post_meta( $event_id, 'hipsy_events_date_end', true );
    $locatie       = get_post_meta( $event_id, 'hipsy_events_location', true );
    $ticket_url    = get_post_meta( $event_id, 'hipsy_events_link', true );
    $beschrijving  = get_the_excerpt( $event_id );
    $categorien    = wp_get_post_terms( $event_id, 'event_categorie' );
    
    // Parse tijd uit datetime velden
    $start_tijd = '';
    $eind_tijd = '';
    if ( $start_datum ) {
        $start_tijd = date( 'H:i', strtotime( $start_datum ) );
    }
    if ( $eind_datum ) {
        $eind_tijd = date( 'H:i', strtotime( $eind_datum ) );
    }
    
    // Prijs ophalen uit tickets
    $tickets = hipsy_get_tickets( $event_id );
    $prijs = '';
    if ( $tickets ) {
        $prijzen = array_filter( array_column( $tickets, 'price' ), fn($p) => (float)$p > 0 );
        if ( $prijzen ) {
            $prijs = min( $prijzen );
        }
    }
    
    // Format date
    if ( $start_datum ) {
        $datum_formatted = date_i18n( $opts['date_format'], strtotime( $start_datum ) );
    } else {
        $datum_formatted = '';
    }
    
    // Trim description
    if ( $opts['max_words'] > 0 && $beschrijving ) {
        $beschrijving = wp_trim_words( $beschrijving, $opts['max_words'], '...' );
    }
    
    // Build category slugs for filtering
    $cat_slugs = [];
    if ( $categorien && ! is_wp_error( $categorien ) ) {
        foreach ( $categorien as $cat ) {
            $cat_slugs[] = $cat->slug;
        }
    }
    $cat_data = implode( ',', $cat_slugs );
    
    // Start card
    echo '<div class="' . esc_attr( $opts['wrapper_class'] ) . '" data-categories="' . esc_attr( $cat_data ) . '" data-location="' . esc_attr( $locatie ) . '">';
    
    // Image
    if ( $opts['show_image'] && $afbeelding ) {
        echo '<div class="hew-card-img">';
        echo '<a href="' . esc_url( $link ) . '">';
        echo '<img src="' . esc_url( $afbeelding ) . '" alt="' . esc_attr( $titel ) . '" loading="lazy">';
        echo '</a>';
        echo '</div>';
    }
    
    // Body
    echo '<div class="hew-card-body">';
    
    // Date
    if ( $opts['show_date'] && $datum_formatted ) {
        echo '<div class="hew-datum">';
        if ( $opts['show_icon_date'] ) {
            echo '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>';
        }
        echo '<span>' . esc_html( $datum_formatted ) . '</span>';
        echo '</div>';
    }
    
    // Time
    if ( $opts['show_time'] && $start_tijd ) {
        $tijd_text = $start_tijd;
        if ( $eind_tijd ) {
            $tijd_text .= ' - ' . $eind_tijd;
        }
        echo '<div class="hew-tijd">';
        if ( $opts['show_icon_time'] ) {
            echo '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>';
        }
        echo '<span>' . esc_html( $tijd_text ) . '</span>';
        echo '</div>';
    }
    
    // Title
    if ( $opts['show_title'] ) {
        echo '<h3 class="hew-titel">';
        echo '<a href="' . esc_url( $link ) . '">' . esc_html( $titel ) . '</a>';
        echo '</h3>';
    }
    
    // Location
    if ( $opts['show_location'] && $locatie ) {
        echo '<div class="hew-locatie">';
        if ( $opts['show_icon_location'] ) {
            echo '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>';
        }
        echo '<span>' . esc_html( $locatie ) . '</span>';
        echo '</div>';
    }
    
    // Description
    if ( $opts['show_description'] && $beschrijving ) {
        echo '<div class="hew-desc">';
        echo '<p>' . esc_html( $beschrijving ) . '</p>';
        echo '</div>';
    }
    
    // Price
    if ( $opts['show_price'] && $prijs ) {
        echo '<div class="hew-prijs">';
        echo '<span>' . hipsy_format_prijs( $prijs ) . '</span>';
        echo '</div>';
    }
    
    // Buttons
    if ( $opts['show_button'] && $ticket_url ) {
        echo '<div class="hew-card-actions">';
        echo '<a href="' . esc_url( $ticket_url ) . '" class="hew-ticket-btn" target="_blank" rel="noopener">';
        echo esc_html( $opts['button_text'] );
        echo '</a>';
        echo '</div>';
    }
    
    echo '</div>'; // .hew-card-body
    echo '</div>'; // .hew-card
}
