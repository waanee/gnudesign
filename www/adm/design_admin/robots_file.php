<?php
$sub_menu = "700050";
include_once('_common.php');

auth_check($auth[$sub_menu], 'r');
/*
if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');
*/
$g5['title'] = "ROBOTS 파일설정";
include_once(G5_ADMIN_PATH.'/admin.head.php');

?>

<form name="fconfigform" id="fconfigform" method="post" action="./robots_file_update.php" onsubmit="return fconfigform_submit(this);" enctype="MULTIPART/FORM-DATA">
<input type="hidden" name="token" value="" id="token">

<div class="local_desc01 local_desc">
  <p>사이트의 외부 로봇의 접근을 설정합니다.</p>
</div>

<section>
    <h2 class="h2_frm">ROBOTS 파일생성및 삭제</h2>

    <div class="tbl_frm01 tbl_wrap">
      <input type="submit" value="파일생성" name="act_button" class="btn_submit btn" accesskey="s">
      <input type="submit" value="파일삭제" name="act_button" class="btn_submit btn" accesskey="s">
    </div>
</section>

<section>
    <h2 class="h2_frm">ROBOTS 설정</h2>

    <div class="tbl_frm01 tbl_wrap">
      <?php
      $robotsFile = $fp = fopen(G5_PATH.'/robots.txt', 'r');
      if ($robotsFile) {
         $robots_txt = '';
         while ($line = fgets($fp, 1024)) {
            $robots_txt .= $line;
         }
      }
      $robots_txt_re = str_replace('</textarea>','</ textarea>',$robots_txt);
      if($robotsFile){
      ?>
      <textarea name="robots_txt" id="robots_txt" style="height:400px;"><?=$robots_txt_re?></textarea>
      <?php
      }
       fclose($robotsFile); ?>

    </div>
</section>



<div class="btn_fixed_top btn_confirm">
  <input type="submit" value="설정" name="act_button" class="btn_submit btn" accesskey="s">
</div>



</form>

<script>
// script
</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>
