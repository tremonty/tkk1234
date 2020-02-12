<?php $labels = stm_get_car_listings(); ?>
<?php if ( !empty( $labels ) ): ?>
    <div class="car-meta-bottom">
        <ul>
            <?php foreach ( $labels as $label ): ?>
                <?php $label_meta = get_post_meta( get_the_id(), $label['slug'], true ); ?>
                <?php if ( $label_meta !== '' and $label_meta != 'none' and $label['slug'] != 'price' ): ?>
                    <li>
                        <?php if ( !empty( $label['font'] ) ): ?>
                            <i class="<?php echo esc_attr( $label['font'] ) ?>"></i>
                        <?php endif; ?>

                        <?php if ( !empty( $label['numeric'] ) and $label['numeric'] ):
                            $affix = '';
                            if ( !empty( $label['number_field_affix'] ) ) {
                                $affix = esc_html__( $label['number_field_affix'], 'motors' );
                            }
                            ?>
                            <span><?php echo esc_html( $label_meta . ' ' . $affix ); ?></span>
                        <?php else: ?>

                            <?php
                            $data_meta_array = explode( ',', $label_meta );
                            $datas = array();

                            if ( !empty( $data_meta_array ) ) {
                                foreach ( $data_meta_array as $data_meta_single ) {
                                    $data_meta = get_term_by( 'slug', $data_meta_single, $label['slug'] );
                                    if ( !empty( $data_meta->name ) ) {
                                        $datas[] = esc_attr( $data_meta->name );
                                    }
                                }
                            }
                            ?>

                            <?php if ( !empty( $datas ) ): ?>

                                <?php
                                if ( count( $datas ) > 1 ) { ?>

                                    <span
                                            class="stm-tooltip-link"
                                            data-toggle="tooltip"
                                            data-placement="bottom"
                                            title="<?php echo esc_attr( implode( ', ', $datas ) ); ?>">
														<?php echo stm_do_lmth( $datas[0] ) . '<span class="stm-dots dots-aligned">...</span>'; ?>
													</span>

                                <?php } else { ?>
                                    <span><?php echo implode( ', ', $datas ); ?></span>
                                <?php }
                                ?>
                            <?php endif; ?>

                        <?php endif; ?>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>