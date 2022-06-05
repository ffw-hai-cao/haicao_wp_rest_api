<?php
/**
 * Plugin Name: MyCasa Rest API
 * Plugin URI:  https://dev-mycasa.pantheonsite.io/
 * Description: Create Rest API
 * Version:     1.0
 * Author:      MyCasa
 * Author URI:  https://dev-mycasa.pantheonsite.io/
 * License:     GPL-2.0+
*/

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

define( 'MYCASA_RESTAPI_PLUGIN_PATH', '/wp-content/plugins/mycasa-rest-api' );

add_action( 'rest_api_init', function() {
  register_rest_route( 'api/v1', '/stocks', [
    'methods' => 'GET',
    'callback' => 'mycasa_restapi_get_stocks',
    'permission_callback' => '__return_true',
  ] );
} );

// Get all projects and assign thumbnail
function mycasa_restapi_get_stocks( $params ) {
  $whitelist = [
    '172.27.0.6', // Local env
    '108.129.6.116/32',
    '34.248.101.243/32',
    '52.51.195.1/32',
    '34.251.220.39/32'
  ];

  if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
    wp_redirect(home_url());
    exit();
  } else {
    /*$xml_header = '<?xml version="1.0" encoding="UTF-8"?><listings></listings>';
    $xml = new SimpleXMLElement($xml_header);*/

    $xml = new XMLWriter();
    $xml->openMemory();
    $xml->startDocument('1.0', 'UTF-8'); // Start XML Document
    $xml->startElement('listings'); // start lisstings root element

    $args =  [
      'post_type' => 'property',
      'post_status' => 'publish',
      'posts_per_page' => -1

    ];

    $the_query = new WP_Query( $args );

    // The Loop
    if ( $the_query->have_posts() ) :
      while ( $the_query->have_posts() ) : $the_query->the_post();
        $data_pid = get_the_ID();
        $data_id = get_post_meta($data_pid, 'fave_property_id', true) ? get_post_meta($data_pid, 'fave_property_id', true) : $data_pid;
        $data_phone = '+84906899300';
        $data_instagram = 'https://www.instagram.com/mycasagroup/';
        $data_facebook = 'https://www.facebook.com/mycasagroup';
        $data_youtube = 'https://www.youtube.com/channel/UCGHUJzJ5hZL-fu1HyZK4M_Q';
        $data_tiktok = 'https://vt.tiktok.com/ZSeAyNyop/';
        $data_linkedin = 'https://www.linkedin.com/company/mycasagroup';
        $data_name = 'Mycasa group';
        $data_title = trim(get_the_title());
        $data_content = trim(strip_tags(get_the_content()));
        $data_rent_usd = get_post_meta($data_pid, 'fave_property_price', true) ? get_post_meta($data_pid, 'fave_property_price', true) : 0;
        $data_rent_vnd = get_post_meta($data_pid, 'fave_rent-vnd', true) ? get_post_meta($data_pid, 'fave_rent-vnd', true) : 0;
        $data_sale_usd = get_post_meta($data_pid, 'fave_resale-usd', true) ? get_post_meta($data_pid, 'fave_resale-usd', true) : 0;
        $data_sale_vnd = get_post_meta($data_pid, 'fave_resale-vnd', true) ? get_post_meta($data_pid, 'fave_resale-vnd', true) : 0;
        $data_type = wp_get_post_terms($data_pid, 'property_type', array('fields' => 'names')) ? implode(',', wp_get_post_terms($data_pid, 'property_type', array('fields' => 'names'))) : null;
        $data_project = get_post_meta($data_pid, 'fave_project-name', true) ? get_post_meta($data_pid, 'fave_project-name', true) : '';
        $data_location = get_post_meta($data_pid, 'fave_property_location', true) ? get_post_meta($data_pid, 'fave_property_location', true) : '0,0';
        $data_lat = explode(",", $data_location)[0];
        $data_lon = explode(",", $data_location)[1];
        $data_year = get_post_meta($data_pid, 'fave_property_year', true) ? date('Y', strtotime(get_post_meta($data_pid, 'fave_property_year', true))) : date('Y');
        $data_contract = '6 year';
        $data_bedrooms = get_post_meta($data_pid, 'fave_property_bedrooms', true) ? get_post_meta($data_pid, 'fave_property_bedrooms', true) : 2;
        $data_bathrooms = get_post_meta($data_pid, 'fave_property_bathrooms', true) ? get_post_meta($data_pid, 'fave_property_bathrooms', true) : 2;
        $data_floor = null;
        $data_furnished = get_post_meta($data_pid, 'fave_furnished', true) ? get_post_meta($data_pid, 'fave_furnished', true) : 'UNFURNISHED';
        $data_floorarea = get_post_meta($data_pid, 'fave_property_size', true) ? get_post_meta($data_pid, 'fave_property_size', true) : 'contact';
        $data_floorarea_unit = get_post_meta($data_pid, 'fave_property_size_prefix', true) ? get_post_meta($data_pid, 'fave_property_size_prefix', true) : 'sqm';
        $data_plotarea = $data_floorarea;
        $data_plotarea_unit = $data_floorarea_unit;
        $data_images = get_field( 'gallery_url', $data_pid );
        $data_images_upload = get_field( 'gallery_upload', $data_pid );
        $data_virtualtour = null;
        $data_amenities = wp_get_post_terms($data_pid, 'property_feature', array('fields' => 'names')) ? wp_get_post_terms($data_pid, 'property_feature', array('fields' => 'names')) : null;
        $data_nearbys = [];

        // Listing Item
        $xml->startElement('listing'); // Start Listing Item

        $xml->writeElement('reference_id', sprintf('<![CDATA[%s]]>', $data_id));

        $xml->startElement('contact'); // Start Contact Element
        $xml->writeElement('phone', sprintf('<![CDATA[%s]]>', $data_phone));
        $xml->writeElement('instagram', sprintf('<![CDATA[%s]]>', $data_instagram));
        $xml->writeElement('facebook', sprintf('<![CDATA[%s]]>', $data_facebook));
        $xml->writeElement('youtube', sprintf('<![CDATA[%s]]>', $data_youtube));
        $xml->writeElement('tiktok', sprintf('<![CDATA[%s]]>', $data_tiktok));
        $xml->writeElement('linkedin', sprintf('<![CDATA[%s]]>', $data_linkedin));
        $xml->writeElement('name', sprintf('<![CDATA[%s]]>', $data_name));
        $xml->endElement(); // End Contact Element

        $xml->startElement('titles'); // Start Titles List Element
        $xml->startElement('title'); // Start Title Item Element
        $xml->writeAttribute('lang', 'en');
        $xml->text(sprintf('<![CDATA[%s]]>', $data_title));
        $xml->endElement(); // End Title Item Element
        $xml->endElement(); // End Titles List Element

        $xml->startElement('descriptions'); // Start Descriptions List Element
        $xml->startElement('description'); // Start Description Item Element
        $xml->writeAttribute('lang', 'en');
        $xml->text(sprintf('<![CDATA[%s]]>', $data_content));
        $xml->endElement(); // End Description Item Element
        $xml->endElement(); // End Descriptions List Element

        $xml->startElement('prices'); // Start Prices List Element
        $xml->startElement('price'); // Start Price Rent USD Item Element
        $xml->writeAttribute('currency', 'USD');
        $xml->writeAttribute('operation', 'rent');
        $xml->writeAttribute('tenure', 'freehold');
        $xml->text($data_rent_usd);
        $xml->endElement(); // End Price Rent USD Item Element
        $xml->startElement('price'); // Start Price Rent VND Item Element
        $xml->writeAttribute('currency', 'VND');
        $xml->writeAttribute('operation', 'rent');
        $xml->writeAttribute('tenure', 'freehold');
        $xml->text($data_rent_vnd);
        $xml->endElement(); // End Price Rent VND Item Element
        $xml->startElement('price'); // Start Price Sale USD Item Element
        $xml->writeAttribute('currency', 'USD');
        $xml->writeAttribute('operation', 'resale');
        $xml->writeAttribute('tenure', 'freehold');
        $xml->text($data_sale_usd);
        $xml->endElement(); // End Price Sale USD Item Element
        $xml->startElement('price'); // Start Price Sale VND Item Element
        $xml->writeAttribute('currency', 'VND');
        $xml->writeAttribute('operation', 'resale');
        $xml->writeAttribute('tenure', 'freehold');
        $xml->text($data_sale_vnd);
        $xml->endElement(); // End Price Sale VND Item Element
        $xml->endElement(); // End Prices List Element

        $xml->writeElement('propertyType', sprintf('<![CDATA[%s]]>', $data_type));
        $xml->writeElement('project', sprintf('<![CDATA[%s]]>', $data_project));

        $xml->startElement('coordinates');
        $xml->writeElement('latitude', sprintf('<![CDATA[%s]]>', $data_lat));
        $xml->writeElement('longitude', sprintf('<![CDATA[%s]]>', $data_lon));
        $xml->endElement();

        $xml->writeElement('year', sprintf('<![CDATA[%s]]>', $data_year));
        $xml->writeElement('contract_term', sprintf('<![CDATA[%s]]>', $data_contract));
        $xml->writeElement('bedrooms', sprintf('<![CDATA[%s]]>', $data_bedrooms));
        $xml->writeElement('bathrooms', sprintf('<![CDATA[%s]]>', $data_bathrooms));
        $xml->writeElement('floor', $data_floor);
        $xml->writeElement('furnished', sprintf('<![CDATA[%s]]>', $data_furnished));

        $xml->startElement('floorArea');
        $xml->writeAttribute('unit', $data_floorarea_unit);
        $xml->text($data_floorarea);
        $xml->endElement();

        $xml->startElement('plotArea');
        $xml->writeAttribute('unit', $data_plotarea_unit);
        $xml->text($data_plotarea);
        $xml->endElement();

        $xml->startElement('pictures'); // Start Pictures Element
        if (!empty($data_images_upload)) {
          $data_pictures = $data_images_upload;

          foreach ($data_pictures as $url) {
            $xml->writeElement('url', sprintf('<![CDATA[%s]]>', esc_attr( $url )));
          }
        } elseif(!empty($data_images)) {
          $data_pictures = $data_images;

          foreach ($data_pictures as $url) {
            $xml->writeElement('url', sprintf('<![CDATA[https://adztvetajq.cloudimg.io/%s]]>', esc_attr( $url['picture_attachement_ids'] )));
          }
        } else {
          $xml->writeElement('url', null);
        }
        $xml->endElement(); // End Pictures Element

        $xml->writeElement('virtualTour', $data_virtualtour);

        $xml->startElement('amenities'); // Start Features List Element
        if ( $data_amenities ) {
          foreach ($data_amenities as $amenity) {
            $xml->writeElement('amenity', sprintf('<![CDATA[%s]]>', $amenity));
          }
        } else {
          $xml->writeElement('amenity', null);
        }
        $xml->endElement(); // End Features List Element

        $xml->startElement('nearbys'); // Start Nearbys List Element
        if ( $data_nearbys ) {
          foreach ($data_nearbys as $nearby) {
            $xml->writeElement('nearby', sprintf('<![CDATA[%s]]>', $amenity));
          }
        } else {
          $xml->writeElement('nearby', null);
        }
        $xml->endElement(); // End Nearbys List Element

        $xml->endElement(); // End Listing Item

        /*$row = $xml->addChild('listing');

        $row->addChild('reference_id', sprintf('<![CDATA[%s]]>', $data_id));

        $contact = $row->addChild('contact');
        $contact->addChild('phone', sprintf('<![CDATA[%s]]>', $data_phone));
        $contact->addChild('instagram', sprintf('<![CDATA[%s]]>', $data_instagram));
        $contact->addChild('facebook', sprintf('<![CDATA[%s]]>', $data_facebook));
        $contact->addChild('youtube', sprintf('<![CDATA[%s]]>', $data_youtube));
        $contact->addChild('tiktok', sprintf('<![CDATA[%s]]>', $data_tiktok));
        $contact->addChild('linkedin', sprintf('<![CDATA[%s]]>', $data_linkedin));
        $contact->addChild('name', sprintf('<![CDATA[%s]]>', $data_name));

        $titles = $row->addChild('titles');
        $title = $titles->addChild('title', sprintf('<![CDATA[%s]]>', $data_title));
        $title->addAttribute('lang', 'en');

        $descriptions = $row->addChild('descriptions');
        $description = $descriptions->addChild('description', sprintf('<![CDATA[%s]]>', $data_content));
        $description->addAttribute('lang', 'en');

        $prices = $row->addChild('prices');
        $price_rent_usd = $prices->addChild('price', $data_rent_usd);
        $price_rent_usd->addAttribute('currency', 'USD');
        $price_rent_usd->addAttribute('operation', 'rent');
        $price_rent_usd->addAttribute('tenure', 'freehold');
        $price_rent_vnd = $prices->addChild('price', $data_rent_vnd ? $data_rent_vnd : 0);
        $price_rent_vnd->addAttribute('currency', 'VND');
        $price_rent_vnd->addAttribute('operation', 'rent');
        $price_rent_vnd->addAttribute('tenure', 'freehold');
        $price_sale_usd = $prices->addChild('price', $data_sale_usd ? $data_sale_usd : 0);
        $price_sale_usd->addAttribute('currency', 'USD');
        $price_sale_usd->addAttribute('operation', 'resale');
        $price_sale_usd->addAttribute('tenure', 'freehold');
        $price_sale_vnd = $prices->addChild('price', $data_sale_vnd ? $data_sale_vnd : 0);
        $price_sale_vnd->addAttribute('currency', 'VND');
        $price_sale_vnd->addAttribute('operation', 'resale');
        $price_sale_vnd->addAttribute('tenure', 'freehold');

        $row->addChild('propertyType', sprintf('<![CDATA[%s]]>', $data_type));*/

      endwhile;
    endif;

    //$output = $xml->asXML();
    $xml->endElement(); // End lisstings root element
    $xml->endDocument(); // End XML Document
    $output = $xml->flush();

    return $output;
  }
}

function mycasa_restapi_get_stocks_feed( $served, $result, $request, $server ) {
    // Bail if the route of the current REST API request is not our custom route.
    if ( '/api/v1/stocks' !== $request->get_route() ||
        // Also check that the callback is smg_feed().
        'mycasa_restapi_get_stocks' !== $request->get_attributes()['callback'] ) {
        return $served;
    }

    // Send headers.
    $server->send_header( 'Content-Type', 'text/xml' );

    // Echo the XML that's returned by smg_feed().
    echo $result->get_data();

    // And then exit.
    exit;
}
add_filter( 'rest_pre_serve_request', 'mycasa_restapi_get_stocks_feed', 10, 4 );
