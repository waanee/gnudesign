<?php
//$sub_menu = '100000';
include_once('../_common.php');

@include_once('../safe_check.php');
if(function_exists('social_log_file_delete')){
    social_log_file_delete(86400);      //소셜로그인 디버그 파일 24시간 지난것은 삭제
}

$g5['title'] = '관리자';
include_once ('././admin.head.php');

include_once(G5_LIB_PATH.'/latest.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');

$new_member_rows = 5;
$new_point_rows = 5;
$new_write_rows = 5;

$sql_common = " from {$g5['member_table']} ";

$sql_search = " where (1) ";

if ($is_admin != 'super')
    $sql_search .= " and mb_level <= '{$member['mb_level']}' ";

if (!$sst) {
    $sst = "mb_datetime";
    $sod = "desc";
}

$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

// 탈퇴회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_leave_date <> '' {$sql_order} ";
$row = sql_fetch($sql);
$leave_count = $row['cnt'];

// 차단회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_intercept_date <> '' {$sql_order} ";
$row = sql_fetch($sql);
$intercept_count = $row['cnt'];

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$new_member_rows} ";
$result = sql_query($sql);

$colspan = 12;

//get_visit_info('2018-10-01', $to_date='');
?>

<!---
<section>
    <h2>사이트 접속통계</h2>
    <div class="local_desc02 local_desc">
        <?php //echo number_format($visit['visit_today']);?>
    </div>
    <div id="chart">
      <?php echo $vi_domain;?>
    </div>
</section>
-->

<div class="uk-child-width-1-2@m" uk-grid>
    <div>
        <div class="uk-card uk-card-default uk-text-center uk-card-body admin-main">
          <div style="text-align:left;"><img src="./design_admin/img/decore-left.png" style="height:80px;"></div>
          <!--<span uk-icon="icon: cog; ratio: 1.6" class="main-icon"></span>-->
          <h2><?=$config['cf_title']?> adminator</h2>
          <p>관리자페이지에 오신것을 환영 합니다.</p>
          <p>페이지의 디자인및, 게시판 설정을 관리 합니다.</p>
        </div>
    </div>
    <div>
      <div class="uk-child-width-1-2@m" uk-grid>
        <div>
            <div class="uk-card uk-card-default uk-text-right uk-card-body user">
              <h2 style="color:rgb(28, 126, 196)">가입회원 수</h2>
              <span uk-icon="icon: user; ratio: 1.4" class="user-icon"></span>
              <span class="num"><?php echo number_format($total_count) ?></span><br>
              차단 <?php echo number_format($intercept_count) ?>명<br> 탈퇴 <?php echo number_format($leave_count) ?>명
              <div class="uk-card-footer">
                  <a href="./member_list.php" class="uk-button uk-button-text">Read more</a>
              </div>
            </div>
        </div>
        <div>
            <div class="uk-card uk-card-default uk-text-right uk-card-body user">
              <h2 style="color:rgb(249, 151, 48)">오늘 방문자 수</h2>
              <span uk-icon="icon: users; ratio: 1.4" class="user-icon"></span>
              <?=visit('theme/admin');?>
              <div class="uk-card-footer">
                  <a href="./visit_list.php" class="uk-button uk-button-text">Read more</a>
              </div>
            </div>
        </div>
      </div>
    </div>



    <div>
        <div class="uk-card uk-card-default uk-card-body">Empty Item</div>
    </div>
    <div>
        <div class="uk-card uk-card-default uk-card-body">Empty Item</div>
    </div>

</div>

<!-- sample img -->
<img src="./design_admin/img/main.png" alt=""/ style="display:none">


<?php
$sql_common = " from {$g5['board_new_table']} a, {$g5['board_table']} b, {$g5['group_table']} c where a.bo_table = b.bo_table and b.gr_id = c.gr_id ";

if ($gr_id)
    $sql_common .= " and b.gr_id = '$gr_id' ";
if ($view) {
    if ($view == 'w')
        $sql_common .= " and a.wr_id = a.wr_parent ";
    else if ($view == 'c')
        $sql_common .= " and a.wr_id <> a.wr_parent ";
}
$sql_order = " order by a.bn_id desc ";

$sql = " select count(*) as cnt {$sql_common} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$colspan = 5;
?>

<section>
    <h2>최근게시물</h2>

    <div class="tbl_head01 tbl_wrap">
        <table>
        <caption>최근게시물</caption>
        <thead>
        <tr>
            <th scope="col">그룹</th>
            <th scope="col">게시판</th>
            <th scope="col">제목</th>
            <th scope="col">이름</th>
            <th scope="col">일시</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sql = " select a.*, b.bo_subject, c.gr_subject, c.gr_id {$sql_common} {$sql_order} limit {$new_write_rows} ";
        $result = sql_query($sql);
        for ($i=0; $row=sql_fetch_array($result); $i++)
        {
            $tmp_write_table = $g5['write_prefix'] . $row['bo_table'];

            if ($row['wr_id'] == $row['wr_parent']) // 원글
            {
                $comment = "";
                $comment_link = "";
                $row2 = sql_fetch(" select * from $tmp_write_table where wr_id = '{$row['wr_id']}' ");

                $name = get_sideview($row2['mb_id'], get_text(cut_str($row2['wr_name'], $config['cf_cut_name'])), $row2['wr_email'], $row2['wr_homepage']);
                // 당일인 경우 시간으로 표시함
                $datetime = substr($row2['wr_datetime'],0,10);
                $datetime2 = $row2['wr_datetime'];
                if ($datetime == G5_TIME_YMD)
                    $datetime2 = substr($datetime2,11,5);
                else
                    $datetime2 = substr($datetime2,5,5);

            }
            else // 코멘트
            {
                $comment = '댓글. ';
                $comment_link = '#c_'.$row['wr_id'];
                $row2 = sql_fetch(" select * from {$tmp_write_table} where wr_id = '{$row['wr_parent']}' ");
                $row3 = sql_fetch(" select mb_id, wr_name, wr_email, wr_homepage, wr_datetime from {$tmp_write_table} where wr_id = '{$row['wr_id']}' ");

                $name = get_sideview($row3['mb_id'], get_text(cut_str($row3['wr_name'], $config['cf_cut_name'])), $row3['wr_email'], $row3['wr_homepage']);
                // 당일인 경우 시간으로 표시함
                $datetime = substr($row3['wr_datetime'],0,10);
                $datetime2 = $row3['wr_datetime'];
                if ($datetime == G5_TIME_YMD)
                    $datetime2 = substr($datetime2,11,5);
                else
                    $datetime2 = substr($datetime2,5,5);
            }
        ?>

        <tr>
            <td class="td_category"><a href="<?php echo G5_BBS_URL ?>/new.php?gr_id=<?php echo $row['gr_id'] ?>"><?php echo cut_str($row['gr_subject'],10) ?></a></td>
            <td class="td_category"><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=<?php echo $row['bo_table'] ?>"><?php echo cut_str($row['bo_subject'],20) ?></a></td>
            <td><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=<?php echo $row['bo_table'] ?>&amp;wr_id=<?php echo $row2['wr_id'] ?><?php echo $comment_link ?>"><?php echo $comment ?><?php echo conv_subject($row2['wr_subject'], 100) ?></a></td>
            <td class="td_mbname"><div><?php echo $name ?></div></td>
            <td class="td_datetime"><?php echo $datetime ?></td>
        </tr>

        <?php
        }
        if ($i == 0)
            echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
        ?>
        </tbody>
        </table>
    </div>

    <div class="btn_list03 btn_list">
        <a href="<?php echo G5_BBS_URL ?>/new.php">최근게시물 더보기</a>
    </div>
</section>

<?php
$sql_common = " from {$g5['point_table']} ";
$sql_search = " where (1) ";
$sql_order = " order by po_id desc ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$new_point_rows} ";
$result = sql_query($sql);

$colspan = 7;
?>

<section>
    <h2>최근 포인트 발생내역</h2>
    <div class="local_desc02 local_desc">
        전체 <?php echo number_format($total_count) ?> 건 중 <?php echo $new_point_rows ?>건 목록
    </div>

    <div class="tbl_head01 tbl_wrap">
        <table>
        <caption>최근 포인트 발생내역</caption>
        <thead>
        <tr>
            <th scope="col">회원아이디</th>
            <th scope="col">이름</th>
            <th scope="col">닉네임</th>
            <th scope="col">일시</th>
            <th scope="col">포인트 내용</th>
            <th scope="col">포인트</th>
            <th scope="col">포인트합</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $row2['mb_id'] = '';
        for ($i=0; $row=sql_fetch_array($result); $i++)
        {
            if ($row2['mb_id'] != $row['mb_id'])
            {
                $sql2 = " select mb_id, mb_name, mb_nick, mb_email, mb_homepage, mb_point from {$g5['member_table']} where mb_id = '{$row['mb_id']}' ";
                $row2 = sql_fetch($sql2);
            }

            $mb_nick = get_sideview($row['mb_id'], $row2['mb_nick'], $row2['mb_email'], $row2['mb_homepage']);

            $link1 = $link2 = "";
            if (!preg_match("/^\@/", $row['po_rel_table']) && $row['po_rel_table'])
            {
                $link1 = '<a href="'.G5_BBS_URL.'/board.php?bo_table='.$row['po_rel_table'].'&amp;wr_id='.$row['po_rel_id'].'" target="_blank">';
                $link2 = '</a>';
            }
        ?>

        <tr>
            <td class="td_mbid"><a href="./point_list.php?sfl=mb_id&amp;stx=<?php echo $row['mb_id'] ?>"><?php echo $row['mb_id'] ?></a></td>
            <td class="td_mbname"><?php echo get_text($row2['mb_name']); ?></td>
            <td class="td_name sv_use"><div><?php echo $mb_nick ?></div></td>
            <td class="td_datetime"><?php echo $row['po_datetime'] ?></td>
            <td><?php echo $link1.$row['po_content'].$link2 ?></td>
            <td class="td_numbig"><?php echo number_format($row['po_point']) ?></td>
            <td class="td_numbig"><?php echo number_format($row['po_mb_point']) ?></td>
        </tr>

        <?php
        }

        if ($i == 0)
            echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
        ?>
        </tbody>
        </table>
    </div>

    <div class="btn_list03 btn_list">
        <a href="./point_list.php">포인트내역 전체보기</a>
    </div>
</section>

<?php
include_once ('././admin.tail.php');
?>
