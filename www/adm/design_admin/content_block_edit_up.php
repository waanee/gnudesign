<?php
$sub_menu = "600200";
include_once('./_common.php');

check_demo();

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');
auth_check($auth[$sub_menu], 'w');


$name = $_POST['name'];
$css_file_str = $_POST['file_css'];
$css_file = stripslashes($css_file_str);

$html_file_str = $_POST['file_html'];
$html_file_str_re = str_replace('</ textarea>','</textarea>',$html_file_str);
$html_file = stripslashes($html_file_str_re);

$js_file_str = $_POST['file_js'];
$js_file_str_re = str_replace('</ textarea>','</textarea>',$js_file_str);
$js_file = stripslashes($js_file_str_re);

$app_file_str = $_POST['file_html'];
$app_file_re = str_replace('</ textarea>','</textarea>',$app_file_str);
$app_file_re = str_replace('<!-- 내용시작 -->',' ',$app_file_re);
$app_file_re = str_replace('<!-- 내용끝 -->',' ',$app_file_re);
$app_file_re = str_replace('<?php',' ',$app_file_re);
$app_file_re = str_replace('?>',' ',$app_file_re);

$app_file = stripslashes($app_file_re);
$app_file = str_replace('<script src="<?=G5_THEME_URL?>/template','',$app_file);
$app_file = str_replace('add_stylesheet("<link rel=\'stylesheet\' href=\'".G5_THEME_URL."/template/'.$_POST['name'].'/style.css\'>", 0);','',$app_file);


$common_dir = G5_THEME_PATH."/template/".$name."/";

// 내용 업데이트
$file_css = fopen($common_dir."style.css","w");
fwrite($file_css, $css_file);
fclose($file_css);

$file_html = fopen($common_dir."index.html","w");
fwrite($file_html, $html_file);
fclose($file_html);

$file_js = fopen($common_dir."script.js","w");
fwrite($file_js, $js_file);
fclose($file_js);

// app 내용 업데이트
$app_dir = G5_PATH.'/app/components/'.$name.'/';
$file_app = fopen($app_dir."index.js","w");
fwrite($file_app, '
export default{
  template:`
'.$app_file.'
`
}
');
fclose($file_app);



$common_dir = G5_THEME_PATH."/template/".$name.'/images';
if(!is_dir($common_dir)){
  @mkdir($common_dir,0777);
  @chmod($common_dir,0777);
}

// 이미지 삭제
for ($i=0; $i<count($_POST['img_del']); $i++) {
  @unlink(G5_THEME_PATH."/template/".$name."/images/".$_POST['img_del'][$i]);
}

// 이미지 업로드
for ($k=0; $k<count($_FILES['img_up']['name']); $k++) {
  $dest_path = G5_THEME_PATH."/template/".$name."/images/".$_FILES['img_up']['name'][$k];
  @move_uploaded_file($_FILES['img_up']['tmp_name'][$k], $dest_path);
}


// 업데이트 완료후 페이지 이동
goto_url('./content_block_edit.php?name='.$name, false);
?>
