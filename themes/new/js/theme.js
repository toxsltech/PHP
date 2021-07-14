$(document).ready(function(){
  $('.set-ht').css('height', $('.get-ht').height() / 2 - 15  + 'px');
});

$(window).resize(function(){
  $('.set-ht').css('height', $('.get-ht').height() / 2 - 15  + 'px');
});

(function($) {
  "use strict";

  var nav_offset_top = $('header').height();
  /*-------------------------------------------------------------------------------
  Navbar
  -------------------------------------------------------------------------------*/

  //* Navbar Fixed

  (function() {

    window.inputNumber = function(el) {

      var min = el.attr('min') || false;
      var max = el.attr('max') || false;

      var els = {};

      els.dec = el.prev();
      els.inc = el.next();

      el.each(function() {
        init($(this));
      });

      function init(el) {

        els.dec.on('click', decrement);
        els.inc.on('click', increment);

        function decrement() {
          var value = el[0].value;
          value--;
          if(!min || value >= min) {
            el[0].value = value;
          }
        }

        function increment() {
          var value = el[0].value;
          value++;
          if(!max || value <= max) {
            el[0].value = value++;
          }
        }
      }
    }
  })();
  $('.extra-fields-customer').click(function() {
    $('.customer_records').clone().appendTo('.customer_records_dynamic');
    $('.customer_records_dynamic .customer_records').addClass('single remove');
    $('.single .extra-fields-customer').remove();
    $('.single').append('<a href="#" class="remove-field btn-remove-customer">Remove Fields</a>');
    $('.customer_records_dynamic > .single').attr("class", "remove");

    $('.customer_records_dynamic input').each(function() {
      var count = 0;
      var fieldname = $(this).attr("name");
      $(this).attr('name', fieldname + count);
      count++;
    });

  });

  $(document).on('click', '.remove-field', function(e) {
    $(this).parent('.remove').remove();
    e.preventDefault();
  });

  inputNumber($('.input-number'));

  $('#type-box').owlCarousel({
    loop: true,
    margin: 30,
    nav: false,
    nav: true,
    navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
    responsive:{
      0:{
        items:1,
        nav:true
      },
      600:{
        items:2,
        nav:false
      },
      1000:{
        items:3,
        nav:true,
        loop:false
      }
    }
  })
  $(function() {
    $('.same-description').matchHeight();
  });

  /* SaysCarus */
  function saysCarus(){
    if ( $('.customers_says_row').length ){
      $('.says_carousel').owlCarousel({
        loop: true,
        margin: 30,
        nav: false,
        items: 1,
        responsive: {
          0: {
            items: 1
          },
          600: {
            items: 1
          },
          1000: {
            items: 2
          }
        },
      });
    };
  };


  /* Counter Js */
  function counterUp(){
    if ( $('.counter').length ){
      $('.counter').counterUp({
        delay: 10,
        time: 400
      });
    };
  };


  //* Isotope js
  function gallery_isotope(){
    if ( $('.grid_gallery_area').length ){
      // Activate isotope in container
      $(".grid_gallery_item_inner").imagesLoaded( function() {
        $(".grid_gallery_item_inner").isotope({
          layoutMode: 'fitRows',
          columnWidth: 1,
          percentPosition: true,
        });
      });

      // Add isotope click function
      $(".gallery_filter li").on('click',function(){
        $(".gallery_filter li").removeClass("active");
        $(this).addClass("active");
        var selector = $(this).attr("data-filter");
        $(".grid_gallery_item_inner").isotope({
          filter: selector,
          animationOptions: {
            duration: 450,
            easing: "linear",
            queue: false,
          }
        });
        return false;
      });
    };
  };
  //* Isotope js
  function nice_Select(){
    if ( $('.product_select').length ){
      $('select').niceSelect();
    };
  };

  //*  Google map js
  if ( $('#mapBox').length ){
    var $lat = $('#mapBox').data('lat');
    var $lon = $('#mapBox').data('lon');
    var $zoom = $('#mapBox').data('zoom');
    var $marker = $('#mapBox').data('marker');
    var $info = $('#mapBox').data('info');
    var $markerLat = $('#mapBox').data('mlat');
    var $markerLon = $('#mapBox').data('mlon');
    var map = new GMaps({
      el: '#mapBox',
      lat: $lat,
      lng: $lon,
      scrollwheel: false,
      scaleControl: true,
      streetViewControl: false,
      panControl: true,
      disableDoubleClickZoom: true,
      mapTypeControl: false,
      zoom: $zoom,
      styles: [
        {
          "featureType": "water",
          "elementType": "geometry.fill",
          "stylers": [
            {
              "color": "#dcdfe6"
            }
          ]
        },
        {
          "featureType": "transit",
          "stylers": [
            {
              "color": "#808080"
            },
            {
              "visibility": "off"
            }
          ]
        },
        {
          "featureType": "road.highway",
          "elementType": "geometry.stroke",
          "stylers": [
            {
              "visibility": "on"
            },
            {
              "color": "#dcdfe6"
            }
          ]
        },
        {
          "featureType": "road.highway",
          "elementType": "geometry.fill",
          "stylers": [
            {
              "color": "#ffffff"
            }
          ]
        },
        {
          "featureType": "road.local",
          "elementType": "geometry.fill",
          "stylers": [
            {
              "visibility": "on"
            },
            {
              "color": "#ffffff"
            },
            {
              "weight": 1.8
            }
          ]
        },
        {
          "featureType": "road.local",
          "elementType": "geometry.stroke",
          "stylers": [
            {
              "color": "#d7d7d7"
            }
          ]
        },
        {
          "featureType": "poi",
          "elementType": "geometry.fill",
          "stylers": [
            {
              "visibility": "on"
            },
            {
              "color": "#ebebeb"
            }
          ]
        },
        {
          "featureType": "administrative",
          "elementType": "geometry",
          "stylers": [
            {
              "color": "#a7a7a7"
            }
          ]
        },
        {
          "featureType": "road.arterial",
          "elementType": "geometry.fill",
          "stylers": [
            {
              "color": "#ffffff"
            }
          ]
        },
        {
          "featureType": "road.arterial",
          "elementType": "geometry.fill",
          "stylers": [
            {
              "color": "#ffffff"
            }
          ]
        },
        {
          "featureType": "landscape",
          "elementType": "geometry.fill",
          "stylers": [
            {
              "visibility": "on"
            },
            {
              "color": "#efefef"
            }
          ]
        },
        {
          "featureType": "road",
          "elementType": "labels.text.fill",
          "stylers": [
            {
              "color": "#696969"
            }
          ]
        },
        {
          "featureType": "administrative",
          "elementType": "labels.text.fill",
          "stylers": [
            {
              "visibility": "on"
            },
            {
              "color": "#737373"
            }
          ]
        },
        {
          "featureType": "poi",
          "elementType": "labels.icon",
          "stylers": [
            {
              "visibility": "off"
            }
          ]
        },
        {
          "featureType": "poi",
          "elementType": "labels",
          "stylers": [
            {
              "visibility": "off"
            }
          ]
        },
        {
          "featureType": "road.arterial",
          "elementType": "geometry.stroke",
          "stylers": [
            {
              "color": "#d6d6d6"
            }
          ]
        },
        {
          "featureType": "road",
          "elementType": "labels.icon",
          "stylers": [
            {
              "visibility": "off"
            }
          ]
        },
        {},
        {
          "featureType": "poi",
          "elementType": "geometry.fill",
          "stylers": [
            {
              "color": "#dadada"
            }
          ]
        }
      ]
    });

    map.addMarker({
      lat: $markerLat,
      lng: $markerLon,
      icon: $marker,
      infoWindow: {
        content: $info
      }
    })
  }

  /*Function Calls*/
  


})(jQuery);
