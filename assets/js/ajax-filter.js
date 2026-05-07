/**
 * Hipsy AJAX Filter — v4.0
 * 
 * Handles AJAX filtering for Hipsy Events
 * Works with Query IDs to connect filters to specific grids
 */

(function($) {
    'use strict';
    
    /**
     * Initialize filter for a specific widget instance
     */
    function initHipsyFilter(filterId, targetId) {
        const $filter = $('#' + filterId);
        const $target = $('#' + targetId);
        
        if (!$filter.length || !$target.length) {
            return;
        }
        
        const queryId = $target.data('query-id') || '';
        const layout = $target.data('layout') || 'grid';
        
        // Handle search input
        const $searchInput = $filter.find('.hfw-search-input');
        if ($searchInput.length) {
            let searchTimeout;
            $searchInput.on('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    applyFilters();
                }, 300);
            });
        }
        
        // Handle category filters
        $filter.find('.hfw-filter-btn').on('click', function(e) {
            e.preventDefault();
            
            // Update active state
            $(this).siblings('.hfw-filter-btn').removeClass('is-active');
            $(this).addClass('is-active');
            
            applyFilters();
        });
        
        // Handle location filters (if present)
        $filter.find('.hfw-location-select').on('change', function() {
            applyFilters();
        });
        
        /**
         * Apply all active filters via AJAX
         */
        function applyFilters() {
            const categorie = $filter.find('.hfw-filter-btn.is-active').data('category') || '';
            const locatie = $filter.find('.hfw-location-select').val() || '';
            const zoekterm = $searchInput.val() || '';
            
            // Show loading state
            $target.addClass('is-loading');
            
            $.ajax({
                url: hipsyAjax.ajaxurl,
                type: 'POST',
                data: {
                    action: 'hipsy_filter_events',
                    nonce: hipsyAjax.nonce,
                    query_id: queryId,
                    categorie: categorie,
                    locatie: locatie,
                    zoekterm: zoekterm,
                    layout: layout
                },
                success: function(response) {
                    if (response.success && response.data.html) {
                        // Replace content
                        $target.find('.hew-grid, .hew-lijst, .swiper-wrapper').html(response.data.html);
                        
                        // Reinit carousel if needed
                        if (layout === 'carrousel' && window.Swiper) {
                            const $carousel = $target.find('.swiper');
                            if ($carousel.length && $carousel[0].swiper) {
                                $carousel[0].swiper.update();
                            }
                        }
                        
                        // Trigger custom event
                        $target.trigger('hipsy:filtered', [response.data]);
                    }
                },
                error: function() {
                    console.error('Hipsy filter error');
                },
                complete: function() {
                    $target.removeClass('is-loading');
                }
            });
        }
    }
    
    /**
     * Auto-init filters on page load
     */
    $(document).ready(function() {
        // Find all filter widgets and connect them to their targets
        $('.hfw-wrapper[data-target-id]').each(function() {
            const filterId = $(this).attr('id');
            const targetId = $(this).data('target-id');
            
            if (filterId && targetId) {
                initHipsyFilter(filterId, targetId);
            }
        });
    });
    
    /**
     * Expose init function globally for dynamic widgets
     */
    window.hipsyInitFilter = initHipsyFilter;
    
})(jQuery);
