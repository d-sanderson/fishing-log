<?php
/**
 * Hook in and add a metabox that only appears on the 'Author' Page
 */
if(!function_exists('register_fishing_spot_metabox')) {
    function register_fishing_spot_metabox() {
        $prefix = '_fishing_spot_';
        $cmb = new_cmb2_box(array(
            'id' => $prefix . 'metabox',
            'title' => 'FishingSpotMeta',
            'object_types' => array('fishingspots'),
            'context' => 'normal',
            'priority' => 'high',
            'show_names' => true
        ));


        $cmb->add_field( array(
            'name' => __( 'GeoJson', 'cmb2' ),
            'desc' => __( 'Geojson', 'cmb2' ),
            'id'   => $prefix . 'geojson',
            'type' => 'text'
        ));

    }
    add_action('cmb2_admin_init', 'register_fishing_spot_metabox');
}
