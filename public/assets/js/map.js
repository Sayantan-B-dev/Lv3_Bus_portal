/**
 * public/assets/js/map.js
 * Handles Leaflet.js map rendering, route drawing, and animations.
 */

class BusMap {
    constructor(elementId, config = {}) {
        this.map = L.map(elementId, {
            zoomControl: false,
            ...config
        });

        // Add Dark Matter tiles
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
            subdomains: 'abcd',
            maxZoom: 20
        }).addTo(this.map);

        L.control.zoom({ position: 'bottomright' }).addTo(this.map);
        
        this.markers = [];
        this.polyline = null;
        this.busMarker = null;
    }

    /**
     * Draw a route with stops and animation
     */
    drawRoute(stops, options = {}) {
        this.clear();
        
        const coords = stops.map(s => [s.lat, s.lng]);
        if (coords.length === 0) return;

        // Draw Polyline
        this.polyline = L.polyline(coords, {
            color: getComputedStyle(document.documentElement).getPropertyValue('--accent').trim() || '#e84025',
            weight: 5,
            opacity: 0.8,
            className: 'route-draw-animation'
        }).addTo(this.map);

        // Add Stop Markers
        stops.forEach((stop, idx) => {
            const isEndpoint = idx === 0 || idx === stops.length - 1;
            const color = isEndpoint ? (idx === 0 ? '#E8B84B' : '#27AE60') : (stop.is_major ? '#F5A623' : '#6B6B6B');
            
            const marker = L.circleMarker([stop.lat, stop.lng], {
                radius: isEndpoint ? 7 : 5,
                fillColor: color,
                color: '#000',
                weight: 2,
                opacity: 1,
                fillOpacity: 1
            }).addTo(this.map);

            marker.bindPopup(`<strong>${stop.name}</strong>`);
            this.markers.push(marker);
        });

        // Fit bounds
        this.map.fitBounds(this.polyline.getBounds(), { padding: [50, 50] });

        // Animate Bus
        if (options.animateBus) {
            this.animateBus(coords);
        }
    }

    animateBus(coords) {
        if (this.busMarker) this.map.removeLayer(this.busMarker);
        
        const busIcon = L.divIcon({
            className: 'bus-marker',
            html: '🚌',
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        });

        this.busMarker = L.marker(coords[0], { icon: busIcon }).addTo(this.map);
        
        let i = 0;
        const interval = setInterval(() => {
            i++;
            if (i >= coords.length) {
                clearInterval(interval);
                return;
            }
            this.busMarker.setLatLng(coords[i]);
        }, 800);
    }

    clear() {
        if (this.polyline) this.map.removeLayer(this.polyline);
        if (this.busMarker) this.map.removeLayer(this.busMarker);
        this.markers.forEach(m => this.map.removeLayer(m));
        this.markers = [];
    }
}

// Auto-initialize if data exists
document.addEventListener('DOMContentLoaded', () => {
    if (window.MAP_DATA && document.getElementById('routeMap')) {
        const busMap = new BusMap('routeMap', {
            center: [window.MAP_DATA.city.lat, window.MAP_DATA.city.lng],
            zoom: window.MAP_DATA.city.zoom
        });
        busMap.drawRoute(window.MAP_DATA.route.stops, { animateBus: true });
    }

    if (window.CITY_DATA && document.getElementById('plannerMap')) {
        window.plannerMap = new BusMap('plannerMap', {
            center: [window.CITY_DATA.lat, window.CITY_DATA.lng],
            zoom: window.CITY_DATA.zoom
        });
    }
});
