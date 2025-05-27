/**
 * Property Listing Block - Interactive Filtering with Widget Integration
 */
document.addEventListener('DOMContentLoaded', function() {
    const propertyListingBlocks = document.querySelectorAll('.carbon-block--property-listing');
    
    propertyListingBlocks.forEach(block => {
        initializePropertyFiltering(block);
    });
});

function initializePropertyFiltering(block) {
    // Get filter elements
    const typeFilter = block.querySelector('#property-type-filter');
    const locationFilter = block.querySelector('#location-filter');
    const priceFilter = block.querySelector('#price-filter');
    const bedroomsFilter = block.querySelector('#bedrooms-filter');
    const featuredFilter = block.querySelector('#featured-filter');
    const clearFiltersBtn = block.querySelector('.carbon-block--property-listing__clear-filters');
    const resultsCount = block.querySelector('#results-count');
    const propertiesGrid = block.querySelector('.carbon-block--property-listing__grid');
    const noResultsMessage = block.querySelector('.carbon-block--property-listing__empty');
    
    // Get all property cards
    const propertyCards = Array.from(block.querySelectorAll('.carbon-block--property-card'));
    
    // Store original properties for filtering
    const allProperties = propertyCards.map(card => ({
        element: card,
        type: card.dataset.propertyType || '',
        location: card.dataset.location || '',
        price: parseInt(card.dataset.price) || 0,
        bedrooms: parseInt(card.dataset.bedrooms) || 0,
        featured: card.dataset.featured === 'true'
    }));
    
    // Add event listeners to filters
    if (typeFilter) typeFilter.addEventListener('change', filterProperties);
    if (locationFilter) locationFilter.addEventListener('change', filterProperties);
    if (priceFilter) priceFilter.addEventListener('change', filterProperties);
    if (bedroomsFilter) bedroomsFilter.addEventListener('change', filterProperties);
    if (featuredFilter) featuredFilter.addEventListener('change', filterProperties);
    if (clearFiltersBtn) clearFiltersBtn.addEventListener('click', clearAllFilters);
    
    // Mobile filter selects
    const mobileSelects = block.querySelectorAll('.carbon-block--property-listing__mobile-select');
    mobileSelects.forEach((select, index) => {
        select.addEventListener('change', function() {
            if (index === 0 && typeFilter) {
                typeFilter.value = this.value;
            } else if (index === 1 && locationFilter) {
                locationFilter.value = this.value;
            }
            filterProperties();
        });
    });
    
    function filterProperties() {
        const filters = {
            type: typeFilter ? typeFilter.value : '',
            location: locationFilter ? locationFilter.value : '',
            price: priceFilter ? priceFilter.value : '',
            bedrooms: bedroomsFilter ? bedroomsFilter.value : '',
            featured: featuredFilter ? featuredFilter.value : ''
        };
        
        const filteredProperties = allProperties.filter(property => {
            // Type filter
            if (filters.type && property.type !== filters.type) {
                return false;
            }
            
            // Location filter
            if (filters.location && property.location !== filters.location) {
                return false;
            }
            
            // Price filter
            if (filters.price) {
                const [min, max] = filters.price.split('-').map(p => p.replace('+', ''));
                const minPrice = parseInt(min) || 0;
                const maxPrice = max ? parseInt(max) : Infinity;
                
                if (property.price < minPrice || property.price > maxPrice) {
                    return false;
                }
            }
            
            // Bedrooms filter
            if (filters.bedrooms) {
                const minBedrooms = parseInt(filters.bedrooms);
                if (property.bedrooms < minBedrooms) {
                    return false;
                }
            }
            
            // Featured filter
            if (filters.featured === 'featured' && !property.featured) {
                return false;
            }
            
            return true;
        });
        
        // Update display
        updatePropertyDisplay(filteredProperties);
        updateResultsCount(filteredProperties.length);
        
        // Sync mobile filters
        syncMobileFilters(filters);
    }
    
    function updatePropertyDisplay(filteredProperties) {
        // Hide all properties first
        allProperties.forEach(property => {
            property.element.style.display = 'none';
        });
        
        // Show filtered properties
        filteredProperties.forEach(property => {
            property.element.style.display = 'block';
        });
        
        // Show/hide no results message
        if (noResultsMessage) {
            noResultsMessage.style.display = filteredProperties.length === 0 ? 'block' : 'none';
        }
    }
    
    function updateResultsCount(count) {
        if (resultsCount) {
            resultsCount.textContent = count;
        }
    }
    
    function syncMobileFilters(filters) {
        mobileSelects.forEach((select, index) => {
            if (index === 0) {
                select.value = filters.type;
            } else if (index === 1) {
                select.value = filters.location;
            }
        });
    }
    
    function clearAllFilters() {
        // Reset all filter selects
        if (typeFilter) typeFilter.value = '';
        if (locationFilter) locationFilter.value = '';
        if (priceFilter) priceFilter.value = '';
        if (bedroomsFilter) bedroomsFilter.value = '';
        if (featuredFilter) featuredFilter.value = '';
        
        // Reset mobile filters
        mobileSelects.forEach(select => {
            select.value = '';
        });
        
        // Show all properties
        updatePropertyDisplay(allProperties);
        updateResultsCount(allProperties.length);
    }
    
    // Initialize display
    updateResultsCount(allProperties.length);
}
