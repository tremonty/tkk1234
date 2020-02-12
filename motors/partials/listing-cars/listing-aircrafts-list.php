<?php

if(empty($modern_filter)){
    $modern_filter = false;
}

$hide_labels = get_theme_mod('hide_price_labels', true);

if ($hide_labels) {
    $hide_labels = 'stm-listing-no-price-labels';
} else {
    $hide_labels = '';
}

stm_listings_load_template('loop/start', array('modern' => $modern_filter)); ?>
    <?php stm_listings_load_template('loop/classified/list/image'); ?>

    <div class="content">

        <!--Title-->
        <?php stm_listings_load_template('loop/classified/list/title_price', array('hide_labels' => $hide_labels)); ?>

        <!--Item parameters-->
        <?php stm_listings_load_template('loop/default/list/options'); ?>

        <!--Item options-->
        <div class="meta-bottom">
            <?php stm_listings_load_template('loop/default/list/features'); ?>
        </div>
    </div>
</div>