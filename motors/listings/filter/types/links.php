<?php $filter_links = stm_get_car_filter_links(); ?>

<?php if (!empty($filter_links) and !empty($filter['options'])): ?>
    <div class="stm-filter-links">
        <?php foreach ($filter_links as $filter_link) :

	        $options = $filter['options'];
            $slug = $filter_link['slug'];
	        $filter_links_default_expanded = 'false';

            if(isset($filter_link['filter_links_default_expanded']) AND $filter_link['filter_links_default_expanded'] == 'open'){
	            $filter_links_default_expanded = 'true';
            }

            if (!empty($options[$slug])) :
                $filter_links_cats = $options[$slug];

                if (!empty($filter_links_cats)): ?>

                    <style type="text/css">
                        .stm-filter_<?php echo esc_attr($slug) ?> {display: none;}
                    </style>

                    <div class="stm-accordion-single-unit" id="stm-filter-link-<?php echo esc_attr($filter_link['slug']); ?>">
                        <a class="title <?php echo (esc_attr($filter_links_default_expanded) == 'false') ? 'collapsed':''?> " data-toggle="collapse"
                           href="#<?php echo esc_attr($filter_link['slug']); ?>" aria-expanded="<?php echo esc_attr($filter_links_default_expanded); ?>">
                            <h5><?php stm_dynamic_string_translation_e('Filter Name', $filter_link['single_name']); ?></h5>
                            <span class="minus"></span>
                        </a>

                        <div class="stm-accordion-content">
                            <div class="collapsed collapse content <?php echo (esc_attr($filter_links_default_expanded) == 'true') ? 'in':''?> "
                                 id="<?php echo esc_attr($filter_link['slug']); ?>">
                                <ul class="list-style-3">
                                    <?php foreach ($filter_links_cats as $key => $filter_links_cat):

                                       if(empty($key) || empty($filter_links_cat['label'])) {
                                            continue;
                                        }
                                        $count = '0';
                                        if (!empty($filter_links_cat['count'])) {
                                            $count = $filter_links_cat['count'];
                                        }
                                        ?>
                                        <li
                                            class="stm-single-filter-link"
                                            data-slug="<?php echo esc_attr($filter_link['slug']) ?>"
                                            data-value="<?php echo esc_attr($key) ?>"
                                        >
                                            <a href="?<?php echo esc_attr($filter_link['slug'] . '=' . $key); ?>">
                                                <?php echo esc_attr($filter_links_cat['label']) . ' <span>(' . $count . ')</span>'; ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>