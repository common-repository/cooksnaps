<?php
    header("Content-type: text/css; charset: UTF-8");

    $absolute_path = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
    $wp_load = $absolute_path[0] . 'wp-load.php';
    require_once($wp_load);
?>
.column-cooksnap {
  width: 18px;
  text-align: center;
}
th.column-cooksnap #CS{
  background: url('<?php echo plugins_url('img/cooksnaps_16_bw.png', dirname(__FILE__)) ?>') no-repeat;
  height:18px;
  width:18px;
}
