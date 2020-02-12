<!--Location inputs-->
<?php
$stm_lat = $stm_lng = $stm_location = '';
if ( isset( $_GET['ca_location'] ) ) {
	$stm_location = sanitize_text_field($_GET['ca_location']);
}
if ( isset( $_GET['stm_lng'] ) ) {
	$stm_lng = sanitize_text_field($_GET['stm_lng']);
}
if ( isset( $_GET['stm_lat'] ) ) {
	$stm_lat = sanitize_text_field($_GET['stm_lat']);
}
$radius = (!empty(get_theme_mod("distance_search", ""))) ? get_theme_mod('distance_search', '') : 100;

$radiusArr = array();
for($q=1;$q<=$radius;$q++) {
    $radiusArr[$q] = array("label" => $q);
}

?>

<?php if ( (stm_enable_location() and is_listing()) or ( stm_enable_location() && stm_is_boats()) or ( stm_enable_location() && stm_is_car_dealer()) ): ?>
	<div class="col-md-12 col-sm-12">
		<div class="form-group boats-location">
			<div class="stm-location-search-unit">
				<input type="text" id="ca_location_listing_filter"
				       class="stm_listing_search_location" name="ca_location"
				       value="<?php echo esc_attr( $stm_location ); ?>"/>
				<input type="hidden" name="stm_lat"
				       value="<?php echo esc_attr( floatval( $stm_lat ) ); ?>">
				<input type="hidden" name="stm_lng"
				       value="<?php echo esc_attr( floatval( $stm_lng ) ); ?>">
			</div>
		</div>
	</div>
    <?php if ( (stm_enable_location() and is_listing()) or ( stm_enable_location() && stm_is_boats()) or ( stm_enable_location() && stm_is_car_dealer()) ) {
        stm_listings_load_template('filter/types/slide', array(
            'taxonomy' => array("slug" => "search_radius", "single_name" => esc_html__("Search radius", 'motors')),
            'options' => $radiusArr
        ));
    }
    ?>
<?php endif; ?>

