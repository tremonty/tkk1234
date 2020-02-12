<?php
if ($items) {
    if (!empty($id)) {
        $features_car = get_post_meta($id, 'additional_features', true);
        $features_car = explode(',', addslashes($features_car));
    } else {
        $features_car = array();
    }

    foreach ($items as $item) { ?>
        <?php if(isset($item['tab_title_single'])): ?>
            <div class="stm-single-feature">
                <div class="heading-font"><?php echo esc_html($item['tab_title_single']); ?></div>
                <?php $features = explode(',', $item['tab_title_labels']); ?>
                <?php if (!empty($features)): ?>
                    <?php foreach ($features as $feature): ?>
                        <?php
                        $checked = '';

                        if (in_array($feature, $features_car)) {
                            $checked = 'checked';
                        };

                        ?>
                        <div class="feature-single">
                            <label>
                                <input type="checkbox" value="<?php echo esc_attr($feature); ?>"
                                       name="stm_car_features_labels[]" <?php echo stm_do_lmth($checked); ?>/>
                                <span><?php echo esc_attr($feature); ?></span>
                            </label>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php }
}?>