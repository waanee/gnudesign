<?php
if($config['cf_theme'] == 'design'){
$menu['menu600'] = array (
    array('600000', '컴포넌트 관리', ''.G5_ADMIN_URL.'/design_admin/logo_config.php', 'design'),
    array('600050', 'DB 설치', ''.G5_ADMIN_URL.'/design_admin/db_setup.php', 'design'),
    array('600100', '로고설정', ''.G5_ADMIN_URL.'/design_admin/logo_config.php', 'design'),
    array('600200', '컴포넌트', ''.G5_ADMIN_URL.'/design_admin/content_block.php', 'design'),
    array('600250', '최신게시물스킨 관리', ''.G5_ADMIN_URL.'/design_admin/latest_skin.php', 'design'),
    array('600260', '게시판스킨 관리', ''.G5_ADMIN_URL.'/design_admin/board_skin.php', 'design'),
    array('600300', '레이아웃 설정', ''.G5_ADMIN_URL.'/design_admin/block_setup.php', 'design'),
    array('600400', '페이지디자인 설정', ''.G5_ADMIN_URL.'/design_admin/page_design.php', 'design'),
    array('600500', '메뉴설정', ''.G5_ADMIN_URL.'/design_admin/menu_list.php', 'design'),
);
}
?>
