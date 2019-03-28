<?php
include_once('./_lib.php');

if ( !isset( $_GET['format'] ) || $_GET['format']  == "") {

	header('Content-Type:application/json; charset=utf-8');
  header("Access-Control-Allow-Origin: *");

	$json_array = array();

    $wr_id = $id;
		$tmp_write_table = $g5['write_prefix'] . $bo_table; // 게시판 테이블 전체이름
        $sql = " select * from {$tmp_write_table} where wr_id = '{$wr_id}' order by wr_num";
        $result = sql_query($sql);
        for ($i=0; $row = sql_fetch_array($result); $i++) {
           	//$json_array[] =$row ;
			$json_array[$i]["wr_id"]= $row["wr_id"];
			if ($row["ca_name"] ){
				$json_array[$i]["ca_name"] = $row["ca_name"];
			}
			$json_array[$i]["wr_subject"] = $row["wr_subject"];
			$json_array[$i]["wr_content"] = $row["wr_content"];
			if ($row["wr_link1"] ){
				$json_array[$i]["wr_link1"] = $row["wr_link1"];
			}
			if ($row["wr_link2"] ){
				$json_array[$i]["wr_link2"] = $row["wr_link2"];
			}
			if ($row["wr_link1_hit"] ){
				$json_array[$i]["wr_link1_hit"] = $row["wr_link1_hit"];
			}
			if ($row["wr_link2_hit"] ){
				$json_array[$i]["wr_link2_hit"] = $row["wr_link2_hit"];
			}
			if ($row["wr_hit"] ){
				$json_array[$i]["wr_hit"] = $row["wr_hit"];
			}
			if ($row["wr_good"] ){
				$json_array[$i]["wr_good"] = $row["wr_good"];
			}
			if ($row["wr_nogood"] ){
				$json_array[$i]["wr_nogood"] = $row["wr_nogood"];
			}
			if ($row["mb_id"] ){
				$json_array[$i]["mb_id"] = $row["mb_id"];
			}
			$json_array[$i]["wr_name"] = $row["wr_name"];
			if ($row["wr_homepage"] ){
				$json_array[$i]["wr_homepage"] = $row["wr_homepage"];
			}
			$json_array[$i]["wr_datetime"] = $row["wr_datetime"];
			$json_array[$i]["wr_last"] = $row["wr_last"];
			if ($row["wr_facebook_user"] ){
				$json_array[$i]["wr_facebook_user"] = $row["wr_facebook_user"];
			}
			if ($row["wr_twitter_user"] ){
				$json_array[$i]["wr_twitter_user"] = $row["wr_twitter_user"];
			}
			if ($row["wr_1"] ){
				$json_array[$i]["wr_1"] = $row["wr_1"];
			}
			if ($row["wr_2"] ){
				$json_array[$i]["wr_2"] = $row["wr_2"];
			}
			if ($row["wr_3"] ){
				$json_array[$i]["wr_3"] = $row["wr_3"];
			}
			if ($row["wr_4"] ){
				$json_array[$i]["wr_4"] = $row["wr_4"];
			}
			if ($row["wr_5"] ){
				$json_array[$i]["wr_5"] = $row["wr_5"];
			}
			if ($row["wr_6"] ){
				$json_array[$i]["wr_6"] = $row["wr_6"];
			}
			if ($row["wr_7"] ){
				$json_array[$i]["wr_7"] = $row["wr_7"];
			}
			if ($row["wr_8"] ){
				$json_array[$i]["wr_8"] = $row["wr_8"];
			}
			if ($row["wr_9"] ){
				$json_array[$i]["wr_9"] = $row["wr_9"];
			}
			if ($row["wr_10"] ){
				$json_array[$i]["wr_10"] = $row["wr_10"];
			}

		}

		$json_array = json_encode($json_array, JSON_UNESCAPED_UNICODE );

		$json_array = prettyPrint( $json_array );

		echo prettyPrint( $json_array );
	}

?>
