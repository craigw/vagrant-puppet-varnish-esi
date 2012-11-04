<?php
function content_items() {
  $fn = dirname(__FILE__) . '/content_items.db';
  $fp = fopen($fn, 'r');
  $str = fread($fp, 4096);
  $content_items = explode(',', $str);
  fclose($fp);
  return $content_items;
}

function my_used_items() {
  $me = my_id();
  $dbf = dirname(__FILE__) . "/${_GET['generation']}_${me}.db";
  $request = fopen($dbf, 'r');
  $s = fread($request, 4096);
  if($s == "") {
    return array();
  }
  $items = explode(',', $s);
  fclose($request);
  if($items == NULL) {
    $items = array();
  }
  return $items;
}

function free_my_used_items() {
  $ui = used_items();
  $mi = my_used_items();
  $fi = array_diff($ui, $mi);
  store_used_items($fi);
  $me = my_id();
  $dbf = dirname(__FILE__) . "/${_GET['generation']}_${me}.db";
  $r = fopen($dbf, 'w');
  fwrite($r, '');
  fclose($r);
}

function store_used_items($i) {
  sort($i);
  $dbf = dirname(__FILE__) . "/${_GET['generation']}.db";
  $s = join($i, ',');
  $f = fopen($dbf, 'w');
  fwrite($f, $s);
  fclose($f);
}

function used_items() {
  $dbf = dirname(__FILE__) . "/${_GET['generation']}.db";
  $request = fopen($dbf, 'r');
  $s = fread($request, 4096);
  if($s == "") {
    return array();
  }
  $items = explode(',', $s);
  fclose($request);
  if($items == NULL) {
    $items = array();
  }

  return $items;
}

function my_id() {
  return md5($_SERVER['SCRIPT_FILENAME']);
}

function store_used_item($item) {
  $m = my_used_items();
  array_push($m, $item);
  $me = my_id();
  $dbf = dirname(__FILE__) . "/${_GET['generation']}_${me}.db";
  $s = join($m, ',');
  $f = fopen($dbf, 'w');
  fwrite($f, $s);
  fclose($f);

  $i = used_items();
  array_push($i, $item);
  store_used_items($i);
}

function unused_items() {
  $ci = content_items();
  $ui = used_items();
  $diff = array_diff($ci, $ui);
  return $diff;
}

function pick_item() {
  $u = unused_items();
  $i = $u[array_rand($u)];
  $a = array($i);
  store_used_item($i);
  return $i;
}
?>
