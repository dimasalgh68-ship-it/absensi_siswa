import './bootstrap';

Alpine.store('darkMode', {
    on: localStorage.getItem('isDark') === 'true',
    init() {
        this.on = localStorage.getItem('isDark') === 'true' ?? window.matchMedia('(prefers-color-scheme: dark)').matches;
        if (this.on) document.documentElement.classList.add('dark');
    },
    toggle() {
        this.on = !this.on;
        localStorage.setItem('isDark', this.on);
        this.on ?
            document.documentElement.classList.add('dark') :
            document.documentElement.classList.remove('dark');
    }
});

Alpine.store('pageTransition', {
    transition: 'fade', // Use only one modern transition
    init() {
        // Apply transition in on page load if stored
        const storedTransition = localStorage.getItem('pageTransition');
        if (storedTransition) {
            const pageContent = document.querySelector('.page-content');
            if (pageContent) {
                pageContent.classList.add(`${this.transition}-in`);
                // Remove after animation
                setTimeout(() => {
                    pageContent.classList.remove(`${this.transition}-in`);
                }, 800);
            }
            localStorage.removeItem('pageTransition');
        }

        // DISABLED: Global click interceptor is causing UI conflicts with sidebar and dropdowns
        /*
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a[href]');
            
            if (!link) return;
            
            const href = link.getAttribute('href');
            
            if (!href || 
                href.startsWith('#') || 
                href === 'javascript:void(0)' ||
                link.hasAttribute('data-toggle') || 
                link.hasAttribute('data-target') ||
                link.closest('.dropdown-menu') ||
                link.classList.contains('dropdown-toggle')) {
                return;
            }

            if (this.isInternalLink(link.href)) {
                e.preventDefault();
                this.handleNavigation(link.href);
            }
        }, true);
        */
    },
    isInternalLink(href) {
        const url = new URL(href, window.location.origin);
        return url.origin === window.location.origin;
    },
    handleNavigation(href) {
        const pageContent = document.querySelector('.page-content');

        if (pageContent) {
            // Apply out transition
            pageContent.classList.add(`${this.transition}-out`);

            // Store transition for next page
            localStorage.setItem('pageTransition', this.transition);

            // Navigate after delay
            setTimeout(() => {
                window.location.href = href;
            }, 400);
        } else {
            // No page-content, navigate immediately
            window.location.href = href;
        }
    }
});

let map;

window.initializeMap = ({ onUpdate, location }) => {
    // Initialize the map centered at a default location
    let defaultLocation = location ?? [-6.928334121065185, 107.60809121537025];
    map = L.map('map').setView(defaultLocation, 13);

    // Set up the OSM layer
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 21,
    }).addTo(map);

    // Create a marker at the center of the map
    let marker = L.marker(defaultLocation, {
        draggable: true,
    }).addTo(map);

    // Update coordinates when the marker is dragged
    marker.on('dragend', function (event) {
        let position = marker.getLatLng();
        updateCoordinates(position.lat, position.lng);
    });

    // Update coordinates when the map is moved
    map.on('move', function () {
        let center = map.getCenter();
        marker.setLatLng(center);
        updateCoordinates(center.lat, center.lng);
    });

    // Initial coordinates display
    updateCoordinates(defaultLocation[0], defaultLocation[1]);

    function updateCoordinates(lat, lng) {
        onUpdate(lat, lng);
    }
}

window.setMapLocation = ({ location }) => {
    if (location == null) return;

    map.setView(location, 13);
}
