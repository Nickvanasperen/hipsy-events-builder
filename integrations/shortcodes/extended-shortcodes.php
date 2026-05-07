<?php
/**
 * Extended Shortcodes v4.0
 * 
 * Builder-onafhankelijke shortcodes met Query ID support.
 * Voor gebruik in Salient, WPBakery, Gutenberg, etc.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Shortcode: Filter Bar
 * 
 * [hipsy_filter query_id="agenda"]
 * [hipsy_filter query_id="homepage" show_search="yes" show_categories="yes"]
 */
add_shortcode( 'hipsy_filter', 'hipsy_filter_shortcode' );

function hipsy_filter_shortcode( $atts ) {
    $args = shortcode_atts( [
        'query_id'          => 'events',
        'show_search'       => 'yes',
        'show_categories'   => 'yes',
        'show_location'     => 'no',
        'search_placeholder' => 'Zoek een event...',
        'all_label'         => 'Alle events',
    ], $atts );
    
    $query_id  = sanitize_key( $args['query_id'] );
    $widget_id = 'hfw-sc-' . uniqid();
    $target_id = 'heg-' . $query_id;
    
    ob_start();
    
    echo '<div id="' . esc_attr( $widget_id ) . '" class="hfw-wrapper hfw-shortcode" data-target-id="' . esc_attr( $target_id ) . '" data-query-id="' . esc_attr( $query_id ) . '">';
    
    // Search bar
    if ( $args['show_search'] === 'yes' ) {
        echo '<div class="hfw-search-wrap">';
        echo '<svg class="hfw-search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>';
        echo '<input type="text" class="hfw-search-input" placeholder="' . esc_attr( $args['search_placeholder'] ) . '" autocomplete="off">';
        echo '</div>';
    }
    
    // Category filters
    if ( $args['show_categories'] === 'yes' ) {
        $terms = get_terms( [ 'taxonomy' => 'event_categorie', 'hide_empty' => true ] ) ?: [];
        
        if ( $terms ) {
            echo '<div class="hfw-filters">';
            echo '<button class="hfw-filter-btn is-active" data-category="">' . esc_html( $args['all_label'] ) . '</button>';
            foreach ( $terms as $term ) {
                echo '<button class="hfw-filter-btn" data-category="' . esc_attr( $term->slug ) . '">' . esc_html( $term->name ) . '</button>';
            }
            echo '</div>';
        }
    }
    
    // Location filter
    if ( $args['show_location'] === 'yes' ) {
        $locaties = hipsy_get_unique_locations();
        if ( $locaties ) {
            echo '<div class="hfw-location-wrap">';
            echo '<select class="hfw-location-select">';
            echo '<option value="">Alle locaties</option>';
            foreach ( $locaties as $loc ) {
                echo '<option value="' . esc_attr( $loc ) . '">' . esc_html( $loc ) . '</option>';
            }
            echo '</select>';
            echo '</div>';
        }
    }
    
    echo '</div>';
    
    // Inline styles
    ?>
    <style>
    .hfw-shortcode {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-bottom: 2rem;
    }
    .hfw-search-wrap {
        position: relative;
    }
    .hfw-search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        pointer-events: none;
    }
    .hfw-search-input {
        width: 100%;
        padding: 10px 12px 10px 40px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        outline: none;
    }
    .hfw-search-input:focus {
        border-color: #7c3aed;
        box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
    }
    .hfw-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .hfw-filter-btn {
        padding: 8px 16px;
        background: #f3f4f6;
        color: #374151;
        border: none;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }
    .hfw-filter-btn:hover {
        background: #e5e7eb;
    }
    .hfw-filter-btn.is-active {
        background: #7c3aed;
        color: #ffffff;
    }
    .hfw-location-select {
        width: 100%;
        max-width: 300px;
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        outline: none;
    }
    </style>
    <?php
    
    return ob_get_clean();
}

/**
 * Shortcode: Events Grid
 * 
 * [hipsy_events_grid query_id="agenda" columns="3" aantal="6"]
 * [hipsy_events_grid query_id="homepage" layout="grid" alleen_toekomst="yes"]
 */
add_shortcode( 'hipsy_events_grid', 'hipsy_events_grid_shortcode' );

function hipsy_events_grid_shortcode( $atts ) {
    $args = shortcode_atts( [
        'query_id'         => '',
        'layout'           => 'grid',
        'columns'          => '3',
        'aantal'           => '6',
        'alleen_toekomst'  => 'yes',
        'categorie'        => '',
        'show_image'       => 'yes',
        'show_date'        => 'yes',
        'show_time'        => 'yes',
        'show_title'       => 'yes',
        'show_location'    => 'yes',
        'show_description' => 'yes',
        'show_price'       => 'yes',
        'show_button'      => 'yes',
        'max_words'        => '20',
        'button_text'      => 'Bestel tickets',
    ], $atts );
    
    // Build query
    $query_params = [
        'posts_per_page'   => intval( $args['aantal'] ),
        'alleen_toekomst'  => $args['alleen_toekomst'],
    ];
    
    if ( ! empty( $args['categorie'] ) ) {
        $query_params['filter_categorie'] = $args['categorie'];
    }
    
    // Register query if Query ID provided
    $query_id = sanitize_key( $args['query_id'] );
    if ( $query_id ) {
        hipsy_register_query( $query_id, $query_params );
    }
    
    // Execute query
    $query = hipsy_get_events_query( $query_params );
    
    ob_start();
    
    // Wrapper ID voor AJAX filtering
    $wrapper_id = $query_id ? 'heg-' . $query_id : '';
    $wrapper_attrs = $wrapper_id ? ' id="' . esc_attr( $wrapper_id ) . '" data-query-id="' . esc_attr( $query_id ) . '" data-layout="' . esc_attr( $args['layout'] ) . '"' : '';
    
    echo '<div class="hipsy-events-shortcode"' . $wrapper_attrs . '>';
    
    if ( $query->have_posts() ) {
        
        // Grid container
        $columns = intval( $args['columns'] );
        echo '<div class="hew-grid hew-shortcode-grid" style="display: grid; grid-template-columns: repeat(' . $columns . ', 1fr); gap: 24px;">';
        
        while ( $query->have_posts() ) {
            $query->the_post();
            
            // Render event card
            hipsy_render_event_card( get_the_ID(), [
                'layout'             => $args['layout'],
                'show_image'         => $args['show_image'] === 'yes',
                'show_date'          => $args['show_date'] === 'yes',
                'show_time'          => $args['show_time'] === 'yes',
                'show_title'         => $args['show_title'] === 'yes',
                'show_location'      => $args['show_location'] === 'yes',
                'show_description'   => $args['show_description'] === 'yes',
                'show_price'         => $args['show_price'] === 'yes',
                'show_button'        => $args['show_button'] === 'yes',
                'max_words'          => intval( $args['max_words'] ),
                'button_text'        => $args['button_text'],
            ] );
        }
        
        echo '</div>';
        
        wp_reset_postdata();
        
    } else {
        echo '<p>Geen events gevonden.</p>';
    }
    
    echo '</div>';
    
    // Responsive CSS
    ?>
    <style>
    @media (max-width: 768px) {
        .hew-shortcode-grid {
            grid-template-columns: 1fr !important;
        }
    }
    @media (min-width: 769px) and (max-width: 1024px) {
        .hew-shortcode-grid {
            grid-template-columns: repeat(2, 1fr) !important;
        }
    }
    .hew-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    .hew-card-img {
        width: 100%;
        aspect-ratio: 16/9;
        overflow: hidden;
    }
    .hew-card-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .hew-card-body {
        padding: 18px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .hew-datum,
    .hew-tijd,
    .hew-locatie {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
        color: #6b7280;
    }
    .hew-datum svg,
    .hew-tijd svg,
    .hew-locatie svg {
        flex-shrink: 0;
        color: #9ca3af;
    }
    .hew-titel {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        line-height: 1.4;
    }
    .hew-titel a {
        color: inherit;
        text-decoration: none;
    }
    .hew-titel a:hover {
        color: #7c3aed;
    }
    .hew-desc {
        margin: 0;
        font-size: 14px;
        line-height: 1.6;
        color: #6b7280;
    }
    .hew-prijs {
        font-weight: 600;
        color: #059669;
    }
    .hew-card-actions {
        display: flex;
        gap: 8px;
        margin-top: 8px;
    }
    .hew-ticket-btn {
        padding: 10px 20px;
        background: #7c3aed;
        color: #ffffff;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        display: inline-block;
        transition: background 0.2s;
    }
    .hew-ticket-btn:hover {
        background: #6d28d9;
    }
    </style>
    <?php
    
    return ob_get_clean();
}

/**
 * Shortcode: Events List
 * 
 * [hipsy_events_list query_id="agenda" aantal="6"]
 */
add_shortcode( 'hipsy_events_list', 'hipsy_events_list_shortcode' );

function hipsy_events_list_shortcode( $atts ) {
    $args = shortcode_atts( [
        'query_id'        => '',
        'aantal'          => '6',
        'alleen_toekomst' => 'yes',
        'categorie'       => '',
    ], $atts );
    
    // Use grid shortcode with list-specific styling
    return hipsy_events_grid_shortcode( array_merge( $args, [
        'layout'  => 'list',
        'columns' => '1',
    ] ) );
}
