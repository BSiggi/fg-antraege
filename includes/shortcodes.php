<?php
defined('ABSPATH') || exit;

add_action('wp_enqueue_scripts', function(){
    wp_enqueue_style( 'fg-antraege', FG_ANTRAEGE_URL.'assets/fg-antraege.css', [], FG_ANTRAEGE_VERSION);
    wp_enqueue_script('fg-antraege', FG_ANTRAEGE_URL.'assets/fg-antraege.js',  [], FG_ANTRAEGE_VERSION, true);
});

add_shortcode('fg_antraege_counter', 'fg_antraege_counter_shortcode');
add_shortcode('fg_antraege_liste',   'fg_antraege_liste_shortcode');

function fg_antraege_get_counts() {
    $c = ['gesamt'=>0,'angenommen'=>0,'abgelehnt'=>0,'eingereicht'=>0];
    foreach (get_posts(['post_type'=>'fg_antrag','numberposts'=>-1,'post_status'=>'publish']) as $p) {
        $s = get_post_meta($p->ID, '_fg_antrag_status', true) ?: 'eingereicht';
        $c['gesamt']++;
        if (isset($c[$s])) $c[$s]++;
    }
    return $c;
}

function fg_antraege_counter_shortcode() {
    $c = fg_antraege_get_counts();
    ob_start(); ?>
    <div class="fg-antraege-counter">
        <div class="fg-counter-box"><span class="fg-counter-zahl"><?php echo esc_html($c['gesamt']);?></span><span class="fg-counter-label">Anträge gesamt</span></div>
        <div class="fg-counter-box"><span class="fg-counter-zahl fg-color-angenommen"><?php echo esc_html($c['angenommen']);?></span><span class="fg-counter-label">Angenommen</span></div>
        <div class="fg-counter-box"><span class="fg-counter-zahl fg-color-abgelehnt"><?php echo esc_html($c['abgelehnt']);?></span><span class="fg-counter-label">Abgelehnt</span></div>
        <div class="fg-counter-box"><span class="fg-counter-zahl fg-color-eingereicht"><?php echo esc_html($c['eingereicht']);?></span><span class="fg-counter-label">Eingereicht</span></div>
    </div>
    <?php return ob_get_clean();
}

function fg_antraege_liste_shortcode() {
    $posts = get_posts(['post_type'=>'fg_antrag','numberposts'=>-1,'post_status'=>'publish','orderby'=>'date','order'=>'DESC']);
    $slabels = ['angenommen'=>'Angenommen ✓','abgelehnt'=>'Abgelehnt ✗','eingereicht'=>'Eingereicht'];
    ob_start(); ?>
    <div class="fg-antraege-liste">
        <div class="fg-filter-buttons">
            <button class="fg-filter-btn active" data-filter="alle">Alle</button>
            <button class="fg-filter-btn" data-filter="angenommen">Angenommen</button>
            <button class="fg-filter-btn" data-filter="abgelehnt">Abgelehnt</button>
            <button class="fg-filter-btn" data-filter="eingereicht">Eingereicht</button>
        </div>
        <?php foreach ($posts as $post):
            $status  = get_post_meta($post->ID,'_fg_antrag_status',true) ?: 'eingereicht';
            $datum   = get_post_meta($post->ID,'_fg_antrag_datum', true);
            $pdf     = get_post_meta($post->ID,'_fg_antrag_pdf',   true);
            $datum_f = $datum ? date_i18n('d.m.Y', strtotime($datum)) : '';
        ?>
        <div class="fg-antrag-item" data-status="<?php echo esc_attr($status);?>">
            <div class="fg-antrag-header">
                <span class="fg-antrag-titel"><?php echo esc_html($post->post_title);?></span>
                <span class="fg-status-badge fg-status-<?php echo esc_attr($status);?>"><?php echo esc_html($slabels[$status]??'Eingereicht');?></span>
                <?php if($datum_f):?><span class="fg-antrag-datum"><?php echo esc_html($datum_f);?></span><?php endif;?>
                <span class="fg-accordion-toggle">▶</span>
            </div>
            <div class="fg-antrag-body">
                <?php echo wp_kses_post(apply_filters('the_content',$post->post_content));?>
                <?php if($pdf):?><a href="<?php echo esc_url($pdf);?>" class="fg-pdf-btn" target="_blank" rel="noopener">📄 PDF herunterladen</a><?php endif;?>
            </div>
        </div>
        <?php endforeach;?>
    </div>
    <?php return ob_get_clean();
}
