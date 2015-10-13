/** Google maps API **/

+function ($) {
    $(document).ready(function(){
        mobile_device = false;
        tablet_device = false;
        isWeb = true;

        //  mobile detection
        if (
            navigator.userAgent.match(/Phone/i) ||
            navigator.userAgent.match(/DROID/i) ||
            navigator.userAgent.match(/Android/i) ||
            navigator.userAgent.match(/webOS/i) ||
            navigator.userAgent.match(/iPhone/i) ||
            navigator.userAgent.match(/iPod/i) ||
            navigator.userAgent.match(/BlackBerry/) ||
            navigator.userAgent.match(/Windows Phone/i) ||
            navigator.userAgent.match(/ZuneWP7/i) ||
            navigator.userAgent.match(/IEMobile/i)
        ){ var mobile_device = true; var isWeb = false; }

        // tablet detection
        if (
            navigator.userAgent.match(/Tablet/i) ||
            navigator.userAgent.match(/iPad/i) ||
            navigator.userAgent.match(/Kindle/i) ||
            navigator.userAgent.match(/Playbook/i) ||
            navigator.userAgent.match(/Nexus/i) ||
            navigator.userAgent.match(/Xoom/i) ||
            navigator.userAgent.match(/SM-N900T/i) || //Samsung Note 3
            navigator.userAgent.match(/GT-N7100/i) || //Samsung Note 2
            navigator.userAgent.match(/SAMSUNG-SGH-I717/i) || //Samsung Note
            navigator.userAgent.match(/SM-T330NU/i) //Samsung Tab 4

        ){ var tablet_device = true; var isWeb = false; }


        function initialize()
        {
            var bounds = new google.maps.LatLngBounds();
            var mapOptions = {
                draggable: isWeb,
                scrollwheel: false,
                zoomControl: isWeb
            };
            var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
            google.maps.event.trigger(map, 'resize');
            var markers = [];
            var itemJson = $(".itemData").val();
            var objectData = jQuery.parseJSON(itemJson);

            for( i = 0; i < objectData.length; i++)
            {
                item = objectData[i];

                markers.push([
                    item.latitude ? item.latitude : "",
                    item.longitude ? item.longitude : "",
                    item.title ? item.title : "Untitled",
                    item.caption ? item.caption : " ",
                    item.images[0].imageFileIcon ? item.images[0].imageFileIcon : "",
                    item.images[0].imageFileSized ? item.images[0].imageFileSized : "",
                    item.creator ? item.creator : "",
                    item.objectId ? item.objectId : ""
                ]);
            }
            var infoWindow = new google.maps.InfoWindow(), marker, i;

            var infoWindowcontent = [];
            var linkaddress;

            for( i = 0; i < markers.length; i++)
            {
                if(isWeb)
                {
                    linkaddress = 'data-target="#myModal-' + markers[i][7] + '"';
                }

                infoWindowcontent.push(['<div class="info_content">' +
                '<a data-toggle="modal" ' + linkaddress + '>' + '<img src="' + markers[i][4] + '"></a>'
                ]);



                var infoWindow = new google.maps.InfoWindow(), marker, i;
                var position = new google.maps.LatLng(markers[i][0],markers[i][1]);
                bounds.extend(position);
                marker = new google.maps.Marker({
                    position: position,
                    map: map,
                    animation: google.maps.Animation.DROP,
                    title: "ArtMoi Artwalk"
                });
                if(isWeb){
                google.maps.event.addListener(marker, 'click', (function(marker, i) {
                    return function() {
                        infoWindow.setContent(infoWindowcontent[i][0]);
                        infoWindow.open(map, marker);
                    }
                })(marker, i));
                }
            }
            google.maps.event.trigger(map, 'resize');

            map.fitBounds(bounds);
            var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event){
                google.maps.event.removeListener(boundsListener);
            });

        }google.maps.event.addDomListener(window, 'load', initialize);

    });

}(jQuery);