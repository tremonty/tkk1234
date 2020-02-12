<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

extract( $atts );

stm_motors_enqueue_scripts_styles('stm_aircraft_data_table');

$css_class = ( !empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

?>

<div class="stm-data-table">
    <?php if ( !empty( $title ) ): ?>
        <h3><?php stm_dynamic_string_translation_e( 'Aircraft data table title', $title ); ?></h3>
    <?php endif; ?>
    <div class="stm-data-table-wrap">
        <?php
        $cats = explode( ',', $taxonomy_list_col_one );
        foreach ( $cats as $cat ) {
            $taxName = get_taxonomy( $cat );
            if($taxName) {
                $taxData = get_the_terms( get_the_ID(), $cat );
                $taxVal = '';

                if ( count( $taxData ) > 1 ) {
                    foreach ( $taxData as $k => $tax ) {
                        if ( $k != 0 ) $taxVal .= ', ';
                        $taxVal .= $tax->name;
                    }
                } else {
                    $taxVal = ( $taxData ) ? $taxData[0]->name : '';
                }

                ?>
                <div class="data-row-wrap heading-font">
                    <div class="left">
                        <span><?php stm_dynamic_string_translation_e( 'Aircraft taxonomy name', $taxName->label ); ?></span>
                    </div>
                    <div class="right">
                        <span><?php stm_dynamic_string_translation_e( 'Aircraft taxonomy value', $taxVal ); ?></span>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>
</div>