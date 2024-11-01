<?php

function ritl_enqueue_styles(){
	/*wp_register_style('ls-bootstrap-grid', plugin_dir_url( __FILE__ ) . 'css/ls-bootstrap-grid.css');
	wp_enqueue_style( 'ls-bootstrap-grid' ); */
}

function ritl_enqueue_scripts(){
	wp_enqueue_script( 'retirethailand-script', plugin_dir_url( __FILE__ ) . '../js/retirethailand.js', array('jquery') );
	wp_localize_script( 'retirethailand-script', 'retirethailand_ajax',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}

?>
