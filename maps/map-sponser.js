
function initialize() {
      var styledMapType = new google.maps.StyledMapType(
            [
    {
        "featureType": "all",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "weight": "2.00"
            }
        ]
    },
    {
        "featureType": "all",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "color": "#9c9c9c"
            }
        ]
    },
    {
        "featureType": "all",
        "elementType": "labels.text",
        "stylers": [
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "landscape",
        "elementType": "all",
        "stylers": [
            {
                "color": "#f2f2f2"
            }
        ]
    },
    {
        "featureType": "landscape",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#ffffff"
            }
        ]
    },
    {
        "featureType": "landscape.man_made",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#ffffff"
            }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "all",
        "stylers": [
            {
                "saturation": -100
            },
            {
                "lightness": 45
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#eeeeee"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "color": "#7b7b7b"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "labels.text.stroke",
        "stylers": [
            {
                "color": "#ffffff"
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "simplified"
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "elementType": "labels.icon",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "transit",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "all",
        "stylers": [
            {
                "color": "#46bcec"
            },
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#c8d7d4"
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "color": "#070707"
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "labels.text.stroke",
        "stylers": [
            {
                "color": "#ffffff"
            }
        ]
    }
],
            {name: 'Styled Map'}
        );

        /*
        Initialize map with style specified above
        */
        var map = new google.maps.Map(document.getElementById('map_sponser'), {
            zoom: 2,
            center: {lat: 0.000000, lng: 0.000000}
        });
        map.mapTypes.set('styled_map', styledMapType);
        map.setMapTypeId('styled_map');

        /*
        @url: File that downloads data from an SQL db and returns it as XML
        @callback: the function called with the XML response from @url
        */
        function downloadUrl(url, callback) {
            var request = window.ActiveXObject ?
                new ActiveXObject('Microsoft.XMLHTTP') :
                new XMLHttpRequest;
            request.onreadystatechange = function () {
                if (request.readyState == 4) {
                    request.onreadystatechange = null;
                    callback(request, request.status);
                }
            };
            request.open('GET', url, true);
            request.send(null);

        }
		
		
		/*
        Create Staff markers of the response from SQL DB
        */
        downloadUrl('staffdb2xml.php', function (data) {
            var xml = data.responseXML;
            var markers = xml.documentElement.getElementsByTagName('marker');
            var markers_array = [];
            Array.prototype.forEach.call(markers, function (markerElem) {
                // Extract data about staff from XML
                var id = markerElem.getElementsByTagName('id')[0].innerHTML;
                var name = markerElem.getElementsByTagName('name')[0].innerHTML;
                var role = markerElem.getElementsByTagName('role')[0].innerHTML;
                var country = markerElem.getElementsByTagName('country')[0].innerHTML;
                var city = markerElem.getElementsByTagName('city')[0].innerHTML;
                var mail = markerElem.getElementsByTagName('mail')[0].innerHTML;
                var numbers = markerElem.getElementsByTagName('phone_numbers')[0].innerHTML;
                var point = new google.maps.LatLng(
                    parseFloat(markerElem.getElementsByTagName('lat')[0].innerHTML),
                    parseFloat(markerElem.getElementsByTagName('lng')[0].innerHTML)
                );

                // Create marker window content
                var infowincontent = document.createElement('div');
                infowincontent.setAttribute("class", "iw-content");

                var backgroundImg = document.createElement('div');
                backgroundImg.setAttribute("class", "location-background");
                infowincontent.appendChild(backgroundImg);

                var colorOverlay = document.createElement('div');
                colorOverlay.setAttribute("class", "color-overlay");
                infowincontent.appendChild(colorOverlay);

                var img = document.createElement('img');
                img.setAttribute("class", "image");
                img.src = "../" + markerElem.getElementsByTagName('pic')[0].innerHTML;
                if (img.getAttribute('src') == "../") {
                    img.src = "../admin/img/avatar.png";
                }
                img.width = 80;
                infowincontent.appendChild(img);

                var logo = document.createElement('img');
                logo.setAttribute("class", "gb-logo");
                logo.src = "img/globuzzerLogo.png";
                infowincontent.appendChild(logo);

                var detailDiv = document.createElement('div');
                detailDiv.setAttribute("class", "detailInfoStaff");

                var staffName = document.createElement('p');
                var textName = document.createElement('span');
                textName.innerHTML = name;
                staffName.setAttribute("class", "name");
                staffName.appendChild(textName);
                detailDiv.appendChild(staffName);

                var staffRole = document.createElement('p');
                var textRole = document.createElement('span');
                textRole.innerHTML = role;
                staffRole.setAttribute("class", "role");
                staffRole.appendChild(textRole);
                detailDiv.appendChild(staffRole);

                var contactDiv = document.createElement('div');
                contactDiv.setAttribute("class", "contact-info");

                var staffMail = document.createElement('p');
                var textContact = document.createElement('span');
                textContact.innerHTML = mail;
                staffMail.setAttribute("class", "contact");
                staffMail.appendChild(textContact);
                contactDiv.appendChild(staffMail);

                var staffPhone = document.createElement('p');
                var textPhone = document.createElement('span');
                textPhone.innerHTML = numbers;
                staffPhone.setAttribute("class", "contact");
                staffPhone.appendChild(textPhone);
                contactDiv.appendChild(staffPhone);

                infowincontent.appendChild(detailDiv);
                infowincontent.appendChild(contactDiv);

                // Add the marker to map
                var icon = {};
                var staffIcon = 'http://globuzzer.com/team/map/icons/staff_icon.png';
                var marker = new google.maps.Marker({
                    map: map_sponser,
                    position: point,
                    icon: staffIcon
                });
                markers_array.push(marker);

                // Make marker clickable and show infoWindow
                var infoBubble = new InfoBubble({
                    backgroundColor: '#ef474c',
                    borderWidth: 0,
                    borderRadius: 0,
                    minWidth: '200px',
                    autopanMargin: 5
                });
                /*
                google.maps.event.addListener(marker, 'click', function () {
                    infoBubble.setContent(infowincontent);
                    infoBubble.open(map, marker);
                });

                google.maps.event.addListener(map, 'click', function () {
                    infoBubble.close();
                });*/

                google.maps.event.addListener(infoBubble, 'domready', function () {

                    var iwOuter = $('.gm-style-iw:has(.detailInfoStaff)');

                    var iwBackground = iwOuter.prev();

                    var iwmain = iwBackground.children(':nth-child(2)');

                    iwBackground.children(':nth-child(4)').css({
                        'display': 'none'
                    });

                    var iwCloseBtn = iwOuter.next();
                });

                google.maps.event.addListener(marker, 'mouseover', function () {
                    infoBubble.setContent(infowincontent);
                    infoBubble.open(map, this);
                    setTimeout(function () { infoBubble.close(); }, 5000);
                });
                /*
                google.maps.event.addListener(marker, 'mouseout', function () {
                    infoBubble.close();
                });*/
            });
            // Add a marker clusterer to manage the markers.
            var markerCluster = new MarkerClusterer(map, markers_array,
                {imagePath: 'markerclusterer/m/m'});
        });






      

        /*
        Create Sponsor markers of the response from SQL DB
        */
        downloadUrl('maps/sponsorsdb2xml.php', function (data) {
            var xml = data.responseXML;
            var markers = xml.documentElement.getElementsByTagName('marker');
            var markers_array = [];
            Array.prototype.forEach.call(markers, function (markerElem) {
                // Extract data about staff from XML
                var id = markerElem.getElementsByTagName('id')[0].innerHTML;
                var name = markerElem.getElementsByTagName('name')[0].innerHTML;
                var description = markerElem.getElementsByTagName('description')[0].innerHTML;
                var address = markerElem.getElementsByTagName('address')[0].innerHTML;
                var email = markerElem.getElementsByTagName('email')[0].innerHTML;
                var resp_id = markerElem.getElementsByTagName('responsible_id')[0].innerHTML;
                var numbers = markerElem.getElementsByTagName('phone_numbers')[0].innerHTML;
                var point = new google.maps.LatLng(
                    parseFloat(markerElem.getElementsByTagName('lat')[0].innerHTML),
                    parseFloat(markerElem.getElementsByTagName('lng')[0].innerHTML)
                );

                // Create marker window content
                var infowincontent = document.createElement('div');
                infowincontent.setAttribute("class", "iw-content");

                var backgroundImg = document.createElement('div');
                backgroundImg.setAttribute("class", "location-background");
                infowincontent.appendChild(backgroundImg);

                var colorOverlay = document.createElement('div');
                colorOverlay.setAttribute("class", "color-overlay");
                infowincontent.appendChild(colorOverlay);

                var img = document.createElement('img');
                img.setAttribute("class", "image");
                img.src = "../" + markerElem.getElementsByTagName('logo')[0].innerHTML;
                img.width = 80;
                if (img.getAttribute('src') == "../") {
                    img.src = "../admin/img/logo.png";
                }
                infowincontent.appendChild(img);

                var logo = document.createElement('p');
                logo.setAttribute("class", "gb-logo");
                logo.innerHTML = 'Sponsor';
                infowincontent.appendChild(logo);

                var detailDiv = document.createElement('div');
                detailDiv.setAttribute("class", "detailInfoSponsors");

                var sponsorName = document.createElement('p');
                var textName = document.createElement('span');
                textName.innerHTML = name;
                sponsorName.setAttribute("class", "name");
                sponsorName.appendChild(textName);
                detailDiv.appendChild(sponsorName);

                var sponsorDescription = document.createElement('p');
                var textDesc = document.createElement('span');
                textDesc.innerHTML = description;
                sponsorDescription.setAttribute("class", "description");
                sponsorDescription.appendChild(textDesc);
                detailDiv.appendChild(sponsorDescription);

                var contactDiv = document.createElement('div');
                contactDiv.setAttribute("class", "contact-info-spon");

                var sponsorMail = document.createElement('p');
                var textMail = document.createElement('span');
                textMail.innerHTML = email;
                sponsorMail.setAttribute("class", "mail");
                sponsorMail.appendChild(textMail);
                contactDiv.appendChild(sponsorMail);

                var sponsorPhone = document.createElement('p');
                var textPhone = document.createElement('span');
                textPhone.innerHTML = numbers;
                sponsorPhone.setAttribute("class", "phone");
                sponsorPhone.appendChild(textPhone);
                contactDiv.appendChild(sponsorPhone);

                infowincontent.appendChild(detailDiv);
                infowincontent.appendChild(contactDiv);

                // Add the marker to map
                var icon = {};
                var sponsorIcon = 'http://globuzzer.com/team/map/icons/sponsor_icon.png';
                var marker = new google.maps.Marker({
                    map: map_sponser,
                    position: point,
                    icon: sponsorIcon
                });
                markers_array.push(marker);

                // Make marker clickable and show infoWindow
                var infoBubble = new InfoBubble({
                    backgroundColor: '#182A3F',
                    borderWidth: 0,
                    borderRadius: 0,
                    minWidth: '340px',
                    minHeight: '230px',
                    autopanMargin: 5
                });

                /*
                google.maps.event.addListener(marker, 'click', function () {
                    infoBubble.setContent(infowincontent);
                    infoBubble.open(map, marker);
                });

                google.maps.event.addListener(map, 'click', function () {
                    infoBubble.close();
                });*/

                google.maps.event.addListener(infoBubble, 'domready', function () {

                    var iwOuter = $('.gm-style-iw:has(.detailInfoSponsors)');

                    var iwBackground = iwOuter.prev();

                    var iwmain = iwBackground.children(':nth-child(2)');

                    iwBackground.children(':nth-child(4)').css({
                        'display': 'none'
                    });

                    var iwCloseBtn = iwOuter.next();
                });
                google.maps.event.addListener(marker, 'mouseover', function () {
                    infoBubble.setContent(infowincontent);
                    infoBubble.open(map, this);
                    setTimeout(function () { infoBubble.close(); }, 5000);
                });
            });

            // Add a marker clusterer to manage the markers.
            var markerCluster = new MarkerClusterer(map, markers_array,
                {imagePath: 'maps/markerclusterer/m/m'});
        });
    }
	
	
	
	

google.maps.event.addDomListener(window, 'load', initialize);