<?php
defined('ABSPATH') || exit;

add_filter('manage_fg_antrag_posts_columns', function($cols){
    $new = [];
    foreach($cols as $k=>$v){
        $new[$k]=$v;
        if($k==='title'){ $new['fg_status']='Status'; $new['fg_datum']='Datum'; $new['fg_pdf']='PDF'; }
    }
    return $new;
});

add_action('manage_fg_antrag_posts_custom_column', function($col, $id){
    if($col==='fg_status'){
        $s = get_post_meta($id,'_fg_antrag_status',true) ?: 'eingereicht';
        $colors = ['angenommen'=>'#28a745','abgelehnt'=>'#dc3545','eingereicht'=>'#F5A623'];
        $labels = ['angenommen'=>'Angenommen ✓','abgelehnt'=>'Abgelehnt ✗','eingereicht'=>'Eingereicht'];
        echo '<span style="background:'.esc_attr($colors[$s]??'#F5A623').';color:#fff;padding:2px 8px;border-radius:3px;font-size:12px;">'.esc_html($labels[$s]??'Eingereicht').'</span>';
    }
    if($col==='fg_datum'){ $d=get_post_meta($id,'_fg_antrag_datum',true); echo $d?esc_html(date_i18n('d.m.Y',strtotime($d))):'—'; }
    if($col==='fg_pdf'){   $p=get_post_meta($id,'_fg_antrag_pdf',  true); echo $p?'<a href="'.esc_url($p).'" target="_blank">📄 PDF</a>':'—'; }
}, 10, 2);
