//<![CDATA[
var map;
var geocoder;
var params;

function getBaseURL() {
    var url = location.href;
    var baseURL = url.substring(0, url.indexOf('/', 14));


    if (baseURL.indexOf('http://localhost') != -1) {

        var url = location.href;
        var pathname = location.pathname;  
        var index1 = url.indexOf(pathname);
        var index2 = url.indexOf("/", index1 + 1);
        var baseLocalUrl = url.substr(0, index2);

        return baseLocalUrl + "/";
    }
    else {
        return baseURL + "/";
    }

}

function load() {
    if (GBrowserIsCompatible()) {
        geocoder = new GClientGeocoder();
        map = new GMap2(document.getElementById('map'));
        map.addControl(new GSmallMapControl());
        map.addControl(new GMapTypeControl());
        map.setCenter(new GLatLng(40, -100), 4);
    }
}

function searchLocations() {
    var address = document.getElementById('addressInput').value;
    geocoder.getLatLng(address,
    function(latlng) {
        if (!latlng) {
            alert(address + ' not found');
        } else {
            searchLocationsNear(latlng);
        }
    });
}

function searchLocationsNear(center) {
    var radius = document.getElementById('radiusSelect').value;
    var searchUrl = getBaseURL() + 'search_genxml';

    params = 'lat=' + center.lat() + '&lng=' + center.lng() + '&radius=' + radius;

    GDownloadUrl(searchUrl,
    function(data) {
        var xml = GXml.parse(data);
        var markers = xml.documentElement.getElementsByTagName('marker');
        map.clearOverlays();

        var sidebar = document.getElementById('sidebar');
        sidebar.innerHTML = '';
        if (markers.length == 0) {
            sidebar.innerHTML = '<p style="color:#977778;font-size:14px;font-weight:bold">No results found.</p>';
            map.setCenter(new GLatLng(40, -100), 4);
            return;
        }

        var bounds = new GLatLngBounds();
        for (var i = 0; i < markers.length; i++) {
            var name = markers[i].getAttribute('name');
            var address = markers[i].getAttribute('address');
            var distance = parseFloat(markers[i].getAttribute('distance'));
            var point = new GLatLng(parseFloat(markers[i].getAttribute('lat')),
            parseFloat(markers[i].getAttribute('lng')));

            var marker = createMarker(point, name, address);
            map.addOverlay(marker);
            var sidebarEntry = createSidebarEntry(marker, name, address, distance);
            sidebar.appendChild(sidebarEntry);
            bounds.extend(point);
        }
        map.setCenter(bounds.getCenter(), map.getBoundsZoomLevel(bounds));
    },
    params);
}

function createMarker(point, name, address) {
    var marker = new GMarker(point);
    var html = '<b>' + name + '</b> <br/>' + address;
    GEvent.addListener(marker, 'click',
    function() {
        marker.openInfoWindowHtml(html);
    });
    return marker;
}

function createSidebarEntry(marker, name, address, distance) {
    var div = document.createElement('div');
    var html = '<b>' + name + '</b> (' + distance.toFixed(1) + ')<br/>' + address;
    div.innerHTML = html;
    div.style.cursor = 'pointer';
    div.style.marginBottom = '5px';
    GEvent.addDomListener(div, 'click',
    function() {
        GEvent.trigger(marker, 'click');
    });
    GEvent.addDomListener(div, 'mouseover',
    function() {
        div.style.backgroundColor = '#eee';
    });
    GEvent.addDomListener(div, 'mouseout',
    function() {
        div.style.backgroundColor = '#fff';
    });
    return div;
}
//]]>
