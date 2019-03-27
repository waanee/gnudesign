<?php
// rss by happyjung 2017-10-22
// please give feedbacks to http://www.happyjung.com/contents/item.php?it_id=1536113583
// Update 2018-10-29 17:26 ver.39

// 그누보드 common.php 까지의 경로를 설정하세요
include_once("./common.php");

// RSS/사이트맵 설정DB 가져오기.
$rss_sql = " select * from g5_rss_sitemap where id = 1";
$result_rss = sql_fetch($rss_sql);

$rss_result = explode('|',$result_rss['rss_result']);
$rss_result1 = $rss_result[0];
$rss_result2 = $rss_result[1];
$rss_result3 = $rss_result[2];
$rss_result4 = $rss_result[3];

// 아래에 사이트 설명문구를 넣으세요
$description = $result_rss['rss_descript'];

$sort_link = "N"; // 짧은주소를 사용할때는 Y , 사용 안할때는 N

// RSS 갯수를 임의로 추출하고자 하는 경우에는 아래에 숫자를 넣으세요
if($result_rss['rss_count']=='0'){
  $rss_rows = "";
}else{
  $rss_rows = $result_rss['rss_count'];
}

//////////////////////////////////////////////////////////////////////////
///////////////         아래는 수정사항이 없습니다.        ///////////////
//////////////////////////////////////////////////////////////////////////

if (!$description) {
    echo "<meta charset='utf-8'>";
    echo '관리자 > 기타설정 > RSS/사이트맵에서 RSS설정 사이트설명을 넣으세요';
    exit;
}

$result = sql_query(" select bo_table from {$g5['board_table']} where bo_use_rss_view='1' and bo_read_level='1' and bo_use_search='1' order by bo_order asc ");
for ($i=0; $row=sql_fetch_array($result); $i++) {
	$bo[$i] = $row['bo_table'];
}

/*
if (!$i) {
    echo "<meta charset='utf-8'>";
    echo 'RSS보기+ 비회원읽기+ 검색허용 게시판이 없습니다.';
    exit;
}
*/

// RSS 갯수
if (!$rss_rows) {
	// 환경설정의 게시글 기본 추출수
	$rss_rows = $config['cf_page_rows'];
}


header('Content-type: text/xml');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');

echo '<?xml version="1.0" encoding="utf-8"?>'."\n";

// 환경설정 추출
$cf_admin_email = $config['cf_admin_email'];
$cf_title = $config['cf_title'];
$cf_title = strip_tags(preg_replace("/&(?!#?[a-z0-9]+;)/", "&amp;",$cf_title)); // title의 내용중에 & 를 &amp; 로 변경 (2018-11-24 추가)

// 마지막 게시글 작성일자 추출
$sql = "select bn_datetime from {$g5['board_new_table']} where order by bn_id desc";
$row = sql_fetch_array($sql);
$bn_datetime = $row['bn_datetime'];
//$date = substr($date,0,10) . "T" . substr($date,11,8) . "+09:00";
$date = date('r', strtotime($bn_datetime));

// 서버 중 일부에서 $_SERVER['HTTPS'] 는 정의되지 않은 변수이므로 오류가 발생합니다.
if(isset($_SERVER['HTTPS'])) {
    if ($_SERVER['HTTPS'] == "on") {
        $secure_connection = true;
        $base_URL = "https://".$_SERVER['HTTP_HOST'];
    }
} else {
    $base_URL = "http://".$_SERVER['HTTP_HOST'];
}
?>
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/">
    <channel>
        <title><?php echo $cf_title; ?></title>
        <link><?php echo $base_URL; ?></link>
        <description><?php echo $description; ?></description>
        <language>ko</language>
	<?php
  if($rss_result2 == '게시물'){
	// 게시판
  $sql = "select bo_table from {$g5['board_table']} where bo_use_rss_view='1' and bo_read_level='1' and bo_use_search='1' order by bo_order asc";

	$tot = sql_num_rows(sql_query($sql));
	if ($tot > 0) {

    	$query = sql_query($sql);
		while($temp = sql_fetch_array($query)) {
			$bo_arr[] = $temp['bo_table'];
		}

		$i = 1;

		foreach($bo_arr as $bo) {
				// list of bo_table
				$temp = sql_fetch("select wr_subject,wr_option,wr_name,wr_content,wr_last,wr_datetime from ".$g5['write_prefix'].$bo." order by wr_last DESC limit 0, $rss_rows ", false);
				if (strstr($temp['wr_option'], 'html'))
					$html = 1;
				else
					$html = 0;

				if ($temp['wr_last']) {
					$lastmod = $temp['wr_last'];
				} elseif ($temp['wr_datetime']) {
					$lastmod = $temp['wr_datetime'];
				} else {
					$lastmod = date("Y-m-d H:i:s");
				}
				//$date = substr($date,0,10) . "T" . substr($date,11,8) . "+09:00";
				$date = date('r', strtotime($lastmod));

				$wr_subject = $temp['wr_subject'];
				$wr_subject = str_replace('< ', '<', $wr_subject); // title 의 첫부분은 < 으로 공백이 포함될수 없습니다. (2018-08-07 추가)
				$wr_subject = str_replace(' <', '<', $wr_subject); // title 의 내용중부분은 < 의 앞글자에는 공백불가. (2018-08-10 추가)
				$wr_subject = str_replace('&nbsp;', ' ', $wr_subject); // &nbsp; 를 공백으로 교체하기 (2018-06-28 추가)
				$wr_subject = str_replace('', ' ', $wr_subject); // 를 공백으로 교체하기 (2018-07-12 추가)
				$wr_subject = preg_replace('/[\x00-\x1F\x7F]/', '', $wr_subject); // 이상한 특수문자를 제어하는 코드 추가 ( 2018-04-27 추가 )
				$wr_subject = strip_tags(preg_replace("/&(?!#?[a-z0-9]+;)/", "&amp;",$wr_subject));
				//$wr_subject = conv_content($wr_subject, $html);

				$wr_content = $temp['wr_content'];

				$wr_name = $temp['wr_name'];
				$wr_name = str_replace('< ', '<', $wr_name); // title 의 첫부분은 < 으로 공백이 포함될수 없습니다. (2018-08-07 추가)
				$wr_name = str_replace(' <', '<', $wr_name); // title 의 내용중부분은 < 의 앞글자에는 공백이 포함될수 없습니다. (2018-08-10 추가)
				$wr_name = str_replace('&nbsp;', ' ', $wr_name); // &nbsp; 를 공백으로 교체하기 (2018-06-28 추가)
				$wr_name = str_replace('', ' ', $wr_name); // 를 공백으로 교체하기 (2018-07-12 추가)
				$wr_name = preg_replace('/[\x00-\x1F\x7F]/', '', $wr_name); // 이상한 특수문자를 제어하는 코드 추가 ( 2018-04-27 추가 )
				$wr_name = strip_tags(preg_replace("/&(?!#?[a-z0-9]+;)/", "&amp;",$wr_name));
				//$wr_name = conv_content($wr_name, $html);

				if ($sort_link=="Y") {
					$board_url = G5_URL ."/". $bo ;
				} else {
					$board_url = G5_BBS_URL ."/board.php?bo_table=". $bo ;
				}
		?>
		<item>
			<title><?php echo $wr_subject; ?></title>
			<link><?php echo $board_url; ?></link>
			<description><![CDATA[<?php echo $wr_content; ?>]]></description>
			<dc:creator><?php echo $wr_name; ?></dc:creator>
			<dc:date><?php echo $date ?></dc:date>
		</item>
		<?php
			// 게시글 추출
			$query = sql_query("select wr_id,wr_subject,wr_content,wr_name from ".$g5['write_prefix'].$bo." where wr_is_comment='0' AND wr_option NOT LIKE '%secret%' order by wr_id DESC limit 0, {$rss_rows} ", false);
			while($row = sql_fetch_array($query)) {
				// list of each article
				if (strstr($temp['wr_option'], 'html'))
					$html = 1;
				else
					$html = 0;

				$temp = sql_fetch("select wr_last from ".$g5['write_prefix'].$bo." where wr_parent='{$row['wr_id']}' order by wr_id DESC limit 0, {$rss_rows} ", false);
				$lastmod = $temp['wr_last'];
				if(!$lastmod) {
					$temp = sql_fetch("select * from ".$g5['write_prefix'].$bo." where wr_id='{$row['wr_id']}'");
					$lastmod = $temp['wr_datetime'];
				}
				//$date = substr($date,0,10) . "T" . substr($date,11,8) . "+09:00";
				$date = date('r', strtotime($lastmod));

				$wr_subject = $row['wr_subject'];
				$wr_subject = str_replace('< ', '<', $wr_subject); // title 의 첫부분은 < 으로 공백이 포함될수 없습니다. (2018-08-07 추가)
				$wr_subject = str_replace(' <', '<', $wr_subject); // title 의 내용중부분은 < 의 앞글자에는 공백이 포함될수 없습니다. (2018-08-10 추가)
				$wr_subject = str_replace('&nbsp;', ' ', $wr_subject); // &nbsp; 를 공백으로 교체하기 (2018-06-28 추가)
				$wr_subject = str_replace('', ' ', $wr_subject); // 를 공백으로 교체하기 (2018-07-12 추가)
				$wr_subject = preg_replace('/[\x00-\x1F\x7F]/', '', $wr_subject); // 이상한 특수문자를 제어하는 코드 추가 ( 2018-04-27 추가 )
				$wr_subject = strip_tags(preg_replace("/&(?!#?[a-z0-9]+;)/", "&amp;",$wr_subject));
				//$wr_subject = conv_content($wr_subject, $html);

				$wr_content = $row['wr_content'];

				$wr_name = $row['wr_name'];
				$wr_name = str_replace('< ', '<', $wr_name); // title 의 첫부분은 < 으로 공백이 포함될수 없습니다. (2018-08-07 추가)
				$wr_name = str_replace(' <', '<', $wr_name); // title 의 내용중부분은 < 의 앞글자에는 공백이 포함될수 없습니다. (2018-08-10 추가)
				$wr_name = str_replace('&nbsp;', ' ', $wr_name); // &nbsp; 를 공백으로 교체하기 (2018-06-28 추가)
				$wr_name = str_replace('', ' ', $wr_name); // 를 공백으로 교체하기 (2018-07-12 추가)
				$wr_name = preg_replace('/[\x00-\x1F\x7F]/', '', $wr_name); // 이상한 특수문자를 제어하는 코드 추가 ( 2018-04-27 추가 )
				$wr_name = strip_tags(preg_replace("/&(?!#?[a-z0-9]+;)/", "&amp;",$wr_name));
				//$wr_name = conv_content($wr_name, $html);

				if ($sort_link=="Y") {
					$board_url = G5_URL ."/". $bo ."/". $row['wr_id'];
				} else {
					$board_url = G5_BBS_URL ."/board.php?bo_table=". $bo ."&amp;wr_id=". $row['wr_id'];
				}
		?>
        <item>
            <title><?php echo $wr_subject; ?></title>
            <link><?php echo $board_url; ?></link>
            <description><![CDATA[<?php echo $wr_content; ?>]]></description>
            <dc:creator><?php echo $wr_name; ?></dc:creator>
            <dc:date><?php echo $date ?></dc:date>
        </item>
		<?php
			}
			$i++;
		}
	}
  }


	// 영카트
	if (defined('G5_YOUNGCART_VER')) {
    if($rss_result3 == '영카트'){
		$query = sql_query("select it_id, it_name, it_basic, it_time, it_img1, it_price, it_stock_qty, ca_id, ca_id2, ca_id3  from {$g5['g5_shop_item_table']} where it_use='1' order by it_time desc limit 0, {$rss_rows} ", false);
		while($row = sql_fetch_array($query)) {
				$html = 1;
				//$date = substr($date,0,10) . "T" . substr($date,11,8) . "+09:00";
				$date = date('r', strtotime($row['it_time']));

				$wr_subject = $row['it_name'];
				$wr_subject = str_replace('< ', '<', $wr_subject); // title 의 첫부분은 < 으로 공백이 포함될수 없습니다. (2018-08-07 추가)
				$wr_subject = str_replace(' <', '<', $wr_subject); // title 의 내용중부분은 < 의 앞글자에는 공백이 포함될수 없습니다. (2018-08-10 추가)
				$wr_subject = str_replace('&nbsp;', ' ', $wr_subject); // &nbsp; 를 공백으로 교체하기 (2018-06-28 추가)
				$wr_subject = str_replace('', ' ', $wr_subject); // 를 공백으로 교체하기 (2018-07-12 추가)
				$wr_subject = preg_replace('/[\x00-\x1F\x7F]/', '', $wr_subject); // 이상한 특수문자를 제어하는 코드 추가 ( 2018-04-27 추가 )
				$wr_subject = strip_tags(preg_replace("/&(?!#?[a-z0-9]+;)/", "&amp;",$wr_subject));
				//$wr_subject = conv_content($wr_subject, $html);

				$wr_content = $row['it_basic'];
				//$wr_content = conv_content($wr_content, $html);
	?>
    <item>
        <title><?php echo conv_content($wr_subject, $html); ?></title>
        <link><?php echo G5_SHOP_URL ?>/item.php?it_id=<?php echo $row['it_id'];?></link>
        <description><![CDATA[<?php echo $wr_content; ?>]]></description>
        <?php if ($row['it_img1']) { ?><image><?php echo G5_DATA_URL ?>/item/<?php echo $row['it_img1'];?></image><?php } ?>
        <?php if ($row['it_img1']) { ?><thumb><?php echo G5_DATA_URL ?>/item/<?php echo $row['it_img1'];?></thumb><?php } ?>
        <price><?php echo $row['it_price'];?></price>
        <quantity><?php echo $row['it_stock_qty'];?></quantity>
        <category>
			<?php
            if ($row['ca_id']) {
                $temp = sql_fetch("select ca_name from {$g5['g5_shop_category_table']} where ca_id='{$row['ca_id']}' ");
                $ca_name = $temp['ca_name'];
            }
            if ($row['ca_id2']) {
                $temp = sql_fetch("select ca_name from {$g5['g5_shop_category_table']} where ca_id='{$row['ca_id2']}' ");
                $ca_name2 = $temp['ca_name'];
            }
            if ($row['ca_id3']) {
                $temp = sql_fetch("select ca_name from {$g5['g5_shop_category_table']} where ca_id='{$row['ca_id3']}' ");
                $ca_name3 = $temp['ca_name'];
            }
			?>
            <?php if ($row['ca_id']) { ?><first id="<?php echo $row['ca_id']; ?>"><![CDATA[<?php echo $ca_name; ?>]]></first><?php } ?>
            <?php if ($row['ca_id2']) { ?><second id="<?php echo $row['ca_id2']; ?>"><![CDATA[<?php echo $ca_name2; ?>]]></second><?php } ?>
            <?php if ($row['ca_id3']) { ?><third id="<?php echo $row['ca_id3']; ?>"><![CDATA[<?php echo $ca_name3; ?> ]]></third><?php } ?>
        </category>
        <returnInfo>
        	<?php
			$temp = sql_fetch("select de_admin_company_zip, de_admin_company_addr, de_admin_company_name, de_admin_company_tel from {$g5['g5_shop_default_table']} ");
			?>
            <zipcode><![CDATA[ <?php echo $temp['de_admin_company_zip']; ?> ]]></zipcode>
            <address1><![CDATA[ <?php echo $temp['de_admin_company_addr']; ?> ]]></address1>
            <address2><![CDATA[ <?php //echo $temp['de_admin_company_addr']; ?> ]]></address2>
            <sellername><![CDATA[ <?php echo $temp['de_admin_company_name']; ?> ]]></sellername>
            <contact1><![CDATA[ <?php echo $temp['de_admin_company_tel']; ?> ]]></contact1>
        </returnInfo>
        <dc:creator>Admin</dc:creator>
        <dc:date><?php echo $date ?></dc:date>
    </item>
			<?php
		}
  }
	}


	// 컨텐츠몰
	if (defined('G5_GNUCONTENTS_VER')) {
    if($rss_result4 == '컨텐츠몰'){
		$query = sql_query("select it_id, it_name, it_basic, it_time, it_img1, it_price, ca_id, ca_id2, ca_id3  from {$g5['g5_contents_item_table']} where it_use='1' order by it_time desc limit 0, {$rss_rows} ", false);
		while($row = sql_fetch_array($query)) {
				$html = 1;
				//$date = substr($date,0,10) . "T" . substr($date,11,8) . "+09:00";
				$date = date('r', strtotime($row['it_time']));

				$wr_subject = $row['it_name'];
				$wr_subject = str_replace('< ', '<', $wr_subject); // title 의 첫부분은 < 으로 공백이 포함될수 없습니다. (2018-08-07 추가)
				$wr_subject = str_replace(' <', '<', $wr_subject); // title 의 내용중부분은 < 의 앞글자에는 공백이 포함될수 없습니다. (2018-08-10 추가)
				$wr_subject = str_replace('&nbsp;', ' ', $wr_subject); // &nbsp; 를 공백으로 교체하기 (2018-06-28 추가)
				$wr_subject = str_replace('', ' ', $wr_subject); // 를 공백으로 교체하기 (2018-07-12 추가)
				$wr_subject = preg_replace('/[\x00-\x1F\x7F]/', '', $wr_subject); // 이상한 특수문자를 제어하는 코드 추가 ( 2018-04-27 추가 )
				$wr_subject = strip_tags(preg_replace("/&(?!#?[a-z0-9]+;)/", "&amp;",$wr_subject));
				//$wr_subject = conv_content($wr_subject, $html);

				$wr_content = $row['it_basic'];
				//$wr_content = conv_content($wr_content, $html);
	?>
    <item>
        <title><?php echo conv_content($wr_subject, $html); ?></title>
        <link><?php echo G5_CONTENTS_URL ?>/item.php?it_id=<?php echo $row['it_id'];?></link>
        <description><![CDATA[<?php echo $wr_content; ?>]]></description>
        <?php if ($row['it_img1']) { ?><image><?php echo G5_DATA_URL ?>/item/<?php echo $row['it_img1'];?></image><?php } ?>
        <?php if ($row['it_img1']) { ?><thumb><?php echo G5_DATA_URL ?>/item/<?php echo $row['it_img1'];?></thumb><?php } ?>
        <price><?php echo $row['it_price'];?></price>
        <category>
			<?php
            if ($row['ca_id']) {
                $temp = sql_fetch("select ca_name from {$g5['g5_contents_category_table']} where ca_id='{$row['ca_id']}' ");
                $ca_name = $temp['ca_name'];
            }
            if ($row['ca_id2']) {
                $temp = sql_fetch("select ca_name from {$g5['g5_contents_category_table']} where ca_id='{$row['ca_id2']}' ");
                $ca_name2 = $temp['ca_name'];
            }
            if ($row['ca_id3']) {
                $temp = sql_fetch("select ca_name from {$g5['g5_contents_category_table']} where ca_id='{$row['ca_id3']}' ");
                $ca_name3 = $temp['ca_name'];
            }
			?>
            <?php if ($row['ca_id']) { ?><first id="<?php echo $row['ca_id']; ?>"><![CDATA[<?php echo $ca_name; ?>]]></first><?php } ?>
            <?php if ($row['ca_id2']) { ?><second id="<?php echo $row['ca_id2']; ?>"><![CDATA[<?php echo $ca_name2; ?>]]></second><?php } ?>
            <?php if ($row['ca_id3']) { ?><third id="<?php echo $row['ca_id3']; ?>"><![CDATA[<?php echo $ca_name3; ?> ]]></third><?php } ?>
        </category>
        <returnInfo>
        	<?php
			$temp = sql_fetch("select de_admin_company_zip, de_admin_company_addr, de_admin_company_name, de_admin_company_tel from {$g5['g5_contents_default_table']} ");
			?>
            <zipcode><![CDATA[ <?php echo $temp['de_admin_company_zip']; ?> ]]></zipcode>
            <address1><![CDATA[ <?php echo $temp['de_admin_company_addr']; ?> ]]></address1>
            <address2><![CDATA[ <?php //echo $temp['de_admin_company_addr']; ?> ]]></address2>
            <sellername><![CDATA[ <?php echo $temp['de_admin_company_name']; ?> ]]></sellername>
            <contact1><![CDATA[ <?php echo $temp['de_admin_company_tel']; ?> ]]></contact1>
        </returnInfo>
        <dc:creator>Admin</dc:creator>
        <dc:date><?php echo $date ?></dc:date>
    </item>
			<?php
		}
  }
	}


	// 컨텐츠
  if($rss_result1 == '컨텐츠'){
	$query = sql_query("select * from {$g5['content_table']} " );
    while($row = sql_fetch_array($query)) {
		$html = 1;
		//$date = substr($date,0,10) . "T" . substr($date,11,8) . "+09:00";
		$date = date('r', strtotime(date("Y-m-d H:i:s")));
		?>
        <item>
            <title><?php echo conv_content($row['co_subject'], $html); ?></title>
            <link><?php echo G5_BBS_URL ?>/content.php?co_id=<?php echo $row['co_id']; ?></link>
            <description><![CDATA[<?php echo conv_content($row['co_content'], $html); ?>]]></description>
            <dc:creator>Admin</dc:creator>
            <dc:date><?php echo $date ?></dc:date>
        </item>
    <?php
    }
  }
	?>
	</channel>
</rss>
