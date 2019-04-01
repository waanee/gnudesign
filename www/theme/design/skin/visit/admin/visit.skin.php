<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
?>

<span class="num"><?php echo number_format($visit[1]) ?></span>
<br>
<span> 어제  <?php echo number_format($visit[2]) ?></span> /
<span> 최대  <?php echo number_format($visit[3]) ?></span><br>
<span> 전체  <?php echo number_format($visit[4]) ?></span>
