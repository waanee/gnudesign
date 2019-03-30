<?php
if (!defined('_GNUBOARD_')) exit;

$begin_time = get_microtime();

add_stylesheet('<link rel="stylesheet" href="'.G5_ADMIN_URL.'/css/c3.min.css">', 0);
add_stylesheet('<link rel="stylesheet" href="'.G5_ADMIN_URL.'/css/uikit.min.css">', 0);

$files = glob(G5_ADMIN_PATH.'/css/admin_extend_*');
if (is_array($files)) {
    foreach ((array) $files as $k=>$css_file) {

        $fileinfo = pathinfo($css_file);
        $ext = $fileinfo['extension'];

        if( $ext !== 'css' ) continue;

        $css_file = str_replace(G5_ADMIN_PATH, G5_ADMIN_URL, $css_file);
        add_stylesheet('<link rel="stylesheet" href="'.$css_file.'">', $k);
    }
}

//추가 라이브러리 가져오기
include_once(G5_ADMIN_PATH.'/design_admin/lib/newlib.php');

include_once(G5_PATH.'/head.sub.php');

function print_menu1($key, $no='')
{
    global $menu, $is_admin, $member;

    if($member['mb_id'] == 'admin' && $is_admin == 'super'){
      //alert(1);
      $str = print_menu2($key, $no);
    }else{
      //alert(2);
      $str = print_menu3($key, $no);
    }

    return $str;
}

function print_menu2($key, $no='')
{
    global $menu, $auth_menu, $is_admin, $auth, $g5, $sub_menu;

    $str .= "<ul>";
    for($i=1; $i<count($menu[$key]); $i++)
    {
        if ($is_admin != 'super' && (!array_key_exists($menu[$key][$i][0],$auth) || !strstr($auth[$menu[$key][$i][0]], 'r')))
            continue;

        if (($menu[$key][$i][4] == 1 && $gnb_grp_style == false) || ($menu[$key][$i][4] != 1 && $gnb_grp_style == true)) $gnb_grp_div = 'gnb_grp_div';
        else $gnb_grp_div = '';

        if ($menu[$key][$i][4] == 1) $gnb_grp_style = 'gnb_grp_style';
        else $gnb_grp_style = '';

        $current_class = '';

        if ($menu[$key][$i][0] == $sub_menu){
            $current_class = ' on';
        }

        $str .= '<li data-menu="'.$menu[$key][$i][0].'"><a href="'.$menu[$key][$i][2].'" class="gnb_2da '.$gnb_grp_style.' '.$gnb_grp_div.$current_class.'">'.$menu[$key][$i][1].'</a></li>';

        $auth_menu[$menu[$key][$i][0]] = $menu[$key][$i][1];
    }
    $str .= "</ul>";

    return $str;
}
function print_menu3($key, $no='')
{
    global $menu, $auth_menu, $is_admin, $auth, $g5, $sub_menu;

    $str .= "<ul>";
    for($i=1; $i<count($menu[$key]); $i++)
    {
        if ($member['mb_id'] != 'admin' && (!array_key_exists($menu[$key][$i][0],$auth) || !strstr($auth[$menu[$key][$i][0]], 'r')))
            continue;

        if (($menu[$key][$i][4] == 1 && $gnb_grp_style == false) || ($menu[$key][$i][4] != 1 && $gnb_grp_style == true)) $gnb_grp_div = 'gnb_grp_div';
        else $gnb_grp_div = '';

        if ($menu[$key][$i][4] == 1) $gnb_grp_style = 'gnb_grp_style';
        else $gnb_grp_style = '';

        $current_class = '';

        if ($menu[$key][$i][0] == $sub_menu){
            $current_class = ' on';
        }

        $str .= '<li data-menu="'.$menu[$key][$i][0].'"><a href="'.$menu[$key][$i][2].'" class="gnb_2da '.$gnb_grp_style.' '.$gnb_grp_div.$current_class.'">'.$menu[$key][$i][1].'</a></li>';

        $auth_menu[$menu[$key][$i][0]] = $menu[$key][$i][1];
    }
    $str .= "</ul>";

    return $str;
}

$adm_menu_cookie = array(
'container' => '',
'gnb'       => '',
'btn_gnb'   => '',
);

if( ! empty($_COOKIE['g5_admin_btn_gnb']) ){
    $adm_menu_cookie['container'] = 'container-small';
    $adm_menu_cookie['gnb'] = 'gnb_small';
    $adm_menu_cookie['btn_gnb'] = 'btn_gnb_open';
}

echo '<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10,user-scalable=yes">'.PHP_EOL;
?>
<script src="<?php echo G5_ADMIN_URL ?>/design_admin/js/jquery.cookie.js"></script>
<script src="<?php echo G5_ADMIN_URL ?>/js/c3.min.js"></script>
<script src="<?php echo G5_ADMIN_URL ?>/js/uikit.min.js"></script>
<script src="<?php echo G5_ADMIN_URL ?>/js/uikit-icons.min.js"></script>

<?php
//관리자 테마 관련 스타일가져오기
include_once(G5_ADMIN_PATH.'/design_admin/admin_theme_color.php');
?>

  <script>
    var tempX = 0;
    var tempY = 0;

    function imageview(id, w, h) {

      menu(id);

      var el_id = document.getElementById(id);

      //submenu = eval(name+".style");
      submenu = el_id.style;
      submenu.left = tempX - (w + 11);
      submenu.top = tempY - (h / 2);

      selectBoxVisible();

      if (el_id.style.display != 'none')
        selectBoxHidden(id);
    }
  </script>

  <div id="color-theme">
  <div class="theme-box">
    <div class="color-theme">
      <span uk-icon="icon:settings"></span>
    </div>

    <div class="theme-content">
      <span class="title">관리자 테마색상</span>
      <div style="height:30px;"></div>
      <div id="color-theme-01" class="color-cycle" style="background:#fff; border:1px solid #e3e3e3;"></div>
      <div id="color-theme-02" class="color-cycle" style="background:#373737"></div>
      <div id="color-theme-03" class="color-cycle" style="background:#d01395"></div>
      <div id="color-theme-04" class="color-cycle" style="background:#916EFC"></div>
      <div id="color-theme-05" class="color-cycle" style="background:#026E94"></div>
      <div id="color-theme-06" class="color-cycle" style="background:#ec4500"></div>

    </div>
  </div>
  </div>


  <script>
  $('.color-theme').toggle(function(){
    $('#color-theme').css('right','0px');
  },function(){
    $('#color-theme').css('right','-151px');
  });

  $('.color-cycle').each(function(){
    var id = $(this).attr('id');

    $(this).on('click',function(){
      if($(this).attr('id') == 'color-theme-01'){
        $.removeCookie('admin-color-theme');
      }else{
        $.removeCookie('admin-color-theme');
        $.cookie('admin-color-theme', $(this).attr('id'),{expires: 365, path: "/adm"});
      }

      location.reload();
    });

  });

  $('#color-theme-01').on('click',function(){
    $.removeCookie('admin-color-theme');
    location.reload();
  });

  $('.uk-nav-sub li .on').css('background','#fff');
  </script>

  <a id="target" class="uk-button scroll-top" href="#top" uk-scroll=""><span uk-icon="icon:chevron-up"></span></a>


  <div class="uk-offcanvas-content" style="background:#F5F4F9;">
    <div id="offcanvas-push" uk-offcanvas="mode: slide; overlay:true " class="uk-offcanvas uk-open">
      <div class="uk-offcanvas-bar uk-flex uk-flex-column" style="overflow:hidden;">
        <div style="overflow-y:auto; width:280px;">

          <div class="side-profile" style="display:none;">
            <div class="inner" onclick="location.href='<?=G5_ADMIN_URL?>'">
              <?php
              $logo_img3 = G5_DATA_PATH."/common/logo_img3";
              if (file_exists($logo_img3)){
              ?>
              <img src="<?php echo G5_DATA_URL; ?>/common/logo_img3" style="width:100%;">
              <?php }?>
            </div>
          </div>

          <div class="site-title">
            <a href="/adm">
              <?=$config['cf_title']?>
            </a>
          </div>

          <div class="top-menu">
            <?php
            echo $is_youngcart;
            ?>
            <a href="<?php echo G5_URL ?>/" target="_blank" uk-icon="icon: home" uk-tooltip="title: 사이트 바로가기; pos: bottom"></a>

            <a href="<?php echo G5_BBS_URL ?>/logout.php" uk-icon="icon: sign-out" style="padding-left:15px;" uk-tooltip="title: 로그아웃; pos: bottom"></a>
          </div>

          <ul class="uk-nav-default uk-nav-parent-icon" uk-nav style="margin-top:30px;">

            <?php
            // 새로추가된 관리자메뉴 출력
            $menu_sql = " select * from g5_admin_menu where sub_id = '' ";
            $menu_result = sql_query($menu_sql);

            for ($i=0; $menu_row=sql_fetch_array($menu_result); $i++) {
              if($menu_row['id'] == $admin_sub_menu){
                $current_class = "uk-open";
              }else{
                $current_class = "";
              }
            ?>
            <li class="uk-parent <?=$current_class?>">
              <a><span uk-icon='icon: menu; ratio: 0.8' style="margin-right:10px;"></span><?=$menu_row['name']?></a>
              <ul class="uk-nav-sub">
              <?php
              $menu_sql1 = " select * from g5_admin_menu where sub_id = '".$menu_row['id']."' ";
              $menu_result1 = sql_query($menu_sql1);

              for ($j=0; $menu_row1=sql_fetch_array($menu_result1); $j++) {
                if($menu_row1['id'] == $admin_sub_menu_id){
                  $current_class2 = "on";
                }else{
                  $current_class2 = "";
                }
              ?>
              <li><a href="<?=G5_ADMIN_URL?>/design_admin/bbs/board.php?bo_table=<?=$menu_row1['board_id']?>" class="<?=$current_class2?>"><?=$menu_row1['name']?></a></li>
              <?php }?>
              </ul>
            </li>
            <?php }?>

            <div style="<?=$admin_underLine?> width:90%; margin:0 auto; padding:5px 0px 5px; margin-bottom:10px;"></div>
            <?php
                    $jj = 1;
                    foreach($amenu as $key=>$value) {
                        $href1 = $href2 = '';

                        if ($menu['menu'.$key][0][2]) {
                            $href1 = '<a href="'.$menu['menu'.$key][0][2].'">';
                            $href2 = '</a>';
                        } else {
                            continue;
                        }

                        $current_class = "";
                        if (isset($sub_menu) && (substr($sub_menu, 0, 3) == substr($menu['menu'.$key][0][0], 0, 3)))
                            $current_class = "uk-open";

                        $button_title = $menu['menu'.$key][0][1];
                    ?>
              <li class="uk-parent <?php echo $current_class;?>">
                <a href="<?php echo $menu['menu'.$key][0][2];?>">
                  <?php
                  if($menu['menu'.$key][0][3] == 'config'){ $icon = 'settings'; }
                  if($menu['menu'.$key][0][3] == 'member'){ $icon = 'users'; }
                  if($menu['menu'.$key][0][3] == 'board'){ $icon = 'table'; }
                  if($menu['menu'.$key][0][3] == 'design'){ $icon = 'paint-bucket'; }
                  if($menu['menu'.$key][0][3] == 'config2'){ $icon = 'cog'; }
                  if($menu['menu'.$key][0][3] == 'shop_config'){ $icon = 'cart'; }
                  if($menu['menu'.$key][0][3] == 'shop_stats'){ $icon = 'database'; }
                  if($menu['menu'.$key][0][3] == 'sms5'){ $icon = 'comments'; }
                  if($menu['menu'.$key][0][3] == 'vue'){ $icon = 'file-edit'; }
                  if($menu['menu'.$key][0][3] == 'tistory'){ $icon = 'pencil'; }
                  ?>
                  <span uk-icon='icon: <?=$icon?>; ratio: 1.2' style="margin-right:10px;"></span>
                  <?php echo $button_title; ?>
                </a>
                <ul class="uk-nav-sub">
                  <?php echo print_menu1('menu'.$key, 1); ?>
                </ul>
              </li>
              <?php
                    $jj++;
                    }     //end foreach
                    ?>
          </ul>


          <div class="sidebar-copyright">
            <!--design by gnuadmin-->
          </div>
        </div>
      </div>
    </div>


    <!--// header //-->
    <div uk-sticky="sel-target: .uk-navbar-container; " class="header">
      <div class="uk-container">
        <nav class="uk-navbar-container uk-navbar header_bg" uk-navbar="dropbar: true;">
          <div class="test-overlay uk-navbar-left" aria-hidden="false" style="">

            <div class="uk-navbar-left">
              <?php if($_SERVER['REQUEST_URI']!='/') {?>
              <a href="#offcanvas-push" uk-toggle="" uk-navbar-toggle-icon="" class="uk-navbar-item uk-icon-link back-buttom"></a>
              <?php }?>
              <div class="logo-left">
                <div class="hide-menu" id="hide-menu"><span uk-icon="icon:menu"></span></div>

                <?php
                if($g5['title'] == '관리자메인'){
                  $pp = '';
                }else{
                  $pp = '<li><span>'.$g5['title'].'</span></li>';
                }
                ?>
                <ul class="uk-breadcrumb" style="float:left; padding-left:30px;">
                    <li><a href="<?=G5_ADMIN_URL?>">관리자메인</a></li>
                    <?=$pp?>
                </ul>

              </div>
            </div>

            <div class="uk-navbar-center">
              <div class="logo-center">
                <a class="uk-navbar-item uk-logo" href="<?php echo G5_ADMIN_URL;?>">
                  ADMINATOR
                </a>
              </div>

            </div>
          </div>

          <div class="test-overlay uk-navbar-right" aria-hidden="false" style="">
            <!--<a href="<?php echo G5_BBS_URL ?>/logout.php" class="uk-navbar-item uk-icon-link mobile-show" uk-icon="icon: sign-out" style="padding-left:15px;" uk-tooltip="title: 로그아웃; pos: bottom"></a>-->
          </div>

        </nav>

      </div>
    </div>
    <!--// header-end //-->


    <div class="uk-container">


      <!-- 콘텐츠 시작 { -->
      <div id="wrapper">
        <div id="container">
