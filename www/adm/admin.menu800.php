<?php
if($config['cf_theme'] == 'design'){
$menu['menu800'] = array (
    array('800000', 'VUE 프레임워크', ''.G5_ADMIN_URL.'/design_admin/page_coding.php', 'vue'),
    array('800010', '프레임워크 설정', ''.G5_ADMIN_URL.'/design_admin/admin_menu_set.php', 'vue'),
    array('800050', '라우터 설정', ''.G5_ADMIN_URL.'/design_admin/robots_file.php', 'vue'),
    array('800100', '컴포넌트 설정', ''.G5_ADMIN_URL.'/design_admin/rss_sitemap.php', 'vue'),
    array('800200', 'manifest 설정', ''.G5_ADMIN_URL.'/design_admin/rss_sitemap.php', 'vue'),
);
}
?>
