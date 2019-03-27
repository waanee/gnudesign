<?php
// m3 google sitemap ver 1.22 by mahler83 2009-07-30
// please give feedbacks to bomool.net
// Edit by happyjung.com  2017-01-17  http://www.happyjung.com/contents/item.php?it_id=1539566838
// Update 2018-10-22 14:53 ver.17

// 그누보드 common.php 까지의 경로를 설정하세요
include_once("./common.php");

// RSS/사이트맵 설정DB 가져오기.
$sitemap_sql = " select * from g5_rss_sitemap where id = 1";
$result_sitemap = sql_fetch($sitemap_sql);

$sitemap_result = explode('|',$result_sitemap['sitemap_result']);
$sitemap_result1 = $sitemap_result[0];
$sitemap_result2 = $sitemap_result[1];
$sitemap_result3 = $sitemap_result[2];
$sitemap_result4 = $sitemap_result[3];

// SITEMAP 갯수를 임의로 추출하고자 하는 경우에는 아래에 숫자를 넣으세요
if($result_sitemap['sitemap_count']=='0'){
  $sitemap_rows = "";
}else{
  $sitemap_rows = $result_sitemap['sitemap_count'];
}

// 짧은주소를 사용할때는 Y , 사용 안할때는 N
$sort_link = "N";

//////////////////////////////////////////////////////////////////////////
///////////////         아래는 수정사항이 없습니다.        ///////////////
//////////////////////////////////////////////////////////////////////////

$result = sql_query(" select bo_table from {$g5['board_table']} where bo_use_rss_view='1' and bo_read_level='1' and bo_use_search='1' order by bo_order asc ");
for ($i=0; $row=sql_fetch_array($result); $i++) {
	$bo[$i] = $row['bo_table'];
}
/*
if (!$i) {
    echo "<meta charset='utf-8'>";
    echo 'RSS허용+ 비회원읽기+ 검색허용 게시판이 없습니다.';
    exit;
}
*/
if (!$sitemap_rows) {
	$sitemap_rows = $config['cf_page_rows']; // 환경설정의 게시글 기본 추출수
}


header("Content-type: text/xml;charset=utf-8");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
  xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
  xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">
	<?php
	// 컨텐츠
	if($sitemap_result1 == '컨텐츠'){
    $query = sql_query("select co_id from {$g5['content_table']} " );
    while($row = sql_fetch_array($query)) {
    ?>
        <url>
            <loc><?php echo G5_BBS_URL ?>/content.php?co_id=<?php echo $row['co_id']; ?></loc>
            <lastmod><?php echo date(Y)."-".date(m)."-".date(d); ?></lastmod>
            <?php // content 는 날짜 필드가 없어서 오늘 날짜를 표시 ?>
            <changefreq>monthly</changefreq>
            <priority>1.0</priority>
        </url>
    <?php
    }
	}



	// 게시판
	if($sitemap_result2 == '게시물'){
    //$sql = "select bo_table from {$g5['board_table']} where bo_read_level='1' order by bo_order asc";
    $sql = "select bo_table from {$g5['board_table']} where bo_use_rss_view = '1' and bo_read_level='1' and bo_use_search='1' order by bo_order asc";

	$tot = sql_num_rows(sql_query($sql));
	if ($tot > 1) {

    	$query = sql_query($sql);
		while($temp = sql_fetch_array($query)) {
			$bo_arr[] = $temp['bo_table'];
		}

		$i = 1;

		foreach($bo_arr as $bo) {
		// list of bo_table
			if ($sort_link=="Y") {
				$board_url = G5_URL ."/". $bo; // 짧은주소
			} else {
				$board_url = G5_BBS_URL ."/board.php?bo_table=". $bo;
			}
			?>
			<url>
				<loc><?php echo $board_url ?></loc>
				<?php
				$temp = sql_fetch("select wr_last,wr_datetime from ".$g5['write_prefix'].$bo." order by wr_last DESC");
				//$wr_last = $temp['wr_last'];
				//$wr_datetime = $temp['wr_datetime'];
				if ($temp['wr_last']) {
					$lastmod = substr($temp['wr_last'], 0, 10);
				} elseif ($temp['wr_datetime']) {
					$lastmod = substr($temp['wr_datetime'], 0, 10);
				} else {
					$lastmod = date(Y)."-".date(m)."-".date(d);
				}
				?>
				<lastmod><?php echo $lastmod; ?></lastmod>
				<changefreq>monthly</changefreq>
				<priority>1.0</priority>
			</url>
			<?php


			// 게시글 추출
			//$query = sql_query("select wr_id, wr_last from ".$g5['write_prefix'].$bo." where wr_is_comment='0' AND wr_option NOT LIKE '%secret%' order by wr_id DESC limit 100 ");
			$query = sql_query("select wr_id, wr_last from ".$g5['write_prefix'].$bo." where wr_is_comment='0' AND wr_option NOT LIKE '%secret%' order by wr_id DESC limit 0, {$sitemap_rows} ", false);
			while($row = sql_fetch_array($query)) {
			// list of each article
				if ($sort_link=="Y") {
					$board_url = G5_URL ."/". $bo ."/". $row['wr_id']; // 짧은주소
				} else {
					$board_url = G5_BBS_URL ."/board.php?bo_table=". $bo ."&amp;wr_id=". $row['wr_id'];
				}
			?>
			<url>
				<loc><?php echo $board_url; ?></loc>
				<?php
				$temp = sql_fetch("select wr_last from ".$g5['write_prefix'].$bo." where wr_parent='{$row['wr_id']}' order by wr_id DESC");
				$lastmod = substr($temp['wr_last'], 0, 10); // 2016-01-16
				if(!$lastmod) {
					$temp = sql_fetch("select wr_datetime from ".$g5['write_prefix'].$bo." where wr_id='{$row['wr_id']}'");
					$lastmod = substr($temp['wr_datetime'], 0, 10);
				}
				?>
				<lastmod><?php echo $lastmod; ?></lastmod>
				<changefreq>daily</changefreq>
				<priority>1.0</priority>
			</url>
			<?php
			}
			$i++;
		}
	}
	}


	// 그누컨텐츠 사용시
	if (defined('G5_GNUCONTENTS_VER')) {
		if($sitemap_result4 == '컨텐츠몰'){
		//$query = sql_query("select it_id, it_time from {$g5['g5_shop_item_table']} where it_use='1' order by it_time desc");
		$query = sql_query("select it_id, it_time from {$g5['g5_contents_item_table']} where it_use='1' order by it_time desc limit 0, {$sitemap_rows} ", false);
		while($row = sql_fetch_array($query)) {
			?>
			<url>
				<loc><?php echo G5_CONTENTS_URL ?>/item.php?it_id=<?php echo $row['it_id'];?></loc>
				<lastmod><?php echo substr($row['it_time'], 0, 10); ?></lastmod>
				<changefreq>daily</changefreq>
				<priority>1.0</priority>
			</url>
			<?php
		}
	}
	}


	// 영카트 사용시
	if (defined('G5_YOUNGCART_VER')) {
		if($sitemap_result3 == '영카트'){
		//$query = sql_query("select it_id, it_time from {$g5['g5_shop_item_table']} where it_use='1' order by it_time desc");
		$query = sql_query("select it_id, it_time from {$g5['g5_shop_item_table']} where it_use='1' order by it_time desc limit 0, {$sitemap_rows} ", false);
		while($row = sql_fetch_array($query)) {
			?>
			<url>
				<loc><?php echo G5_SHOP_URL ?>/item.php?it_id=<?php echo $row['it_id'];?></loc>
				<lastmod><?php echo substr($row['it_time'], 0, 10); ?></lastmod>
				<changefreq>daily</changefreq>
				<priority>1.0</priority>
			</url>
			<?php
		}
	}
	}
	?>
</urlset>
