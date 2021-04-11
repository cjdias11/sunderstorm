jQuery( document ).ready( function( $ ) {

// Make sure the widget input field, and the widget settings var exists before continuing
if ( $( "#wpsl-widget-search" ).length && typeof wpslWidgetSettings !== "undefined" ) {

    // Check if we need to enable autocomplete for the search field
    if ( wpslWidgetSettings.autoComplete == 1 ) {
        activateAutocomplete();
    }

    /*
     * Check if we need to attempt to auto locate the user.
     *
     * But don't do this on the main WPSL page if the location
     * is submitted through the search widget.
     */
    if ( wpslWidgetSettings.autoLocate == 1 && !$( ".wpsl-search" ).hasClass( "wpsl-widget" ) ) {
        checkGeolocation();
    }
}

/**
 * Activate the autocomplete for the input field
 *
 * @since    1.1.0
 * @returns {void}
 */
function activateAutocomplete() {
    var input,
        options = {};

    // Check if we need to set the geocode component restrictions.
    if ( typeof wpslWidgetSettings.geocodeComponents !== "undefined" && !$.isEmptyObject( wpslWidgetSettings.geocodeComponents ) ) {
        options.componentRestrictions = wpslWidgetSettings.geocodeComponents;
    }

    input = document.getElementById( "wpsl-widget-search" );

    new google.maps.places.Autocomplete( input, options );
}

/**
 * Activate the autocomplete for the input field
 *
 * @since    1.1.0
 * @returns {void}
 */
function checkGeolocation() {
    var latLng, timeout = Number( wpslWidgetSettings.geoLocationTimout );

    if ( navigator.geolocation ) {
        var geolocationInProgress;

        geolocationInProgress = setInterval( function() {
            $( ".wpsl-icon-direction" ).toggleClass( "wpsl-active-icon" );
        }, 600 );

        navigator.geolocation.getCurrentPosition( function( position ) {
            if ( typeof( position ) !== "undefined" ) {
                latLng = new google.maps.LatLng( position.coords.latitude, position.coords.longitude );

                clearInterval( geolocationInProgress );
                reverseGeocode( latLng );
            }
        }, function( error ) {
            switch ( error.code ) {
                case error.PERMISSION_DENIED:
                    alert( wpslGeolocationErrors.denied );
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert( wpslGeolocationErrors.unavailable );
                    break;
                case error.TIMEOUT:
                    alert( wpslGeolocationErrors.timeout );
                    break;
                default:
                    alert( wpslGeolocationErrors.generalError );
                    break;
            }

            clearInterval( geolocationInProgress );
            $( ".wpsl-icon-direction" ).removeClass( "wpsl-active-icon" );
        }, { maximumAge: 60000, timeout: timeout, enableHighAccuracy: true } );
    }
}

/**
 * Geocode the coordinates from the geolocation request in the browser.
 *
 * @since   1.1.0
 * @param   {object} latLng The coordinates returned by the geolocation request
 * @returns {void}
 */
function reverseGeocode( latLng ) {
    var response, geocoder = new google.maps.Geocoder();

    geocoder.geocode({ 'latLng': latLng }, function( response, status ) {
        response = filterApiResponse( response );

        if ( status == google.maps.GeocoderStatus.OK ) {
            $( "#wpsl-widget-search" ).val( response );
        }
    });
}

/**
 * Filter out the address details from the API response.
 *
 * Defaults to zip, but can be changed with the
 * wpsl_geolocation_filter_pattern filter to take out
 * other data like the full address, city name etc.
 *
 * @since	1.0.0
 * @param	{object} response The complete Google API response
 * @returns {string} zipcode  The zipcode
 */
function filterApiResponse( response ) {
    var responseType, i, userLocation, addressLength,
        filterMatches = wpslWidgetSettings.filterPattern;

    if ( filterMatches == 'formatted_address' ) {
        userLocation = response[0].formatted_address;
    } else {
        addressLength = response[0].address_components.length;

        // Loop over the API response.
        for ( i = 0; i < addressLength; i++ ) {
            responseType = response[0].address_components[i].types;

            if ( filterMatches.indexOf( responseType[0] ) > -1 ) {
                userLocation = response[0].address_components[i].long_name;
            }
        }

        // If no data is found, then default to the formatted address.
        if ( !userLocation ) {
            userLocation = response[0].formatted_address;
        }
    }

    return userLocation;
}

// Bind the direction button to trigger a new geolocation request.
$( ".wpsl-icon-direction" ).on( "click", function() {
    checkGeolocation();
});

// Check if the input field contains input before submitting the form
$( "#wpsl-widget-submit" ).bind( "click", function( e ) {
    var input = $.trim( $( "#wpsl-widget-search" ).val() );

    if ( !input.length ) {
        $( "#wpsl-widget-search" ).addClass( "wpsl-error" ).focus();

        return false;
    } else {
        $( "#wpsl-widget-search" ).removeClass( "wpsl-error" );
    }
});

});
