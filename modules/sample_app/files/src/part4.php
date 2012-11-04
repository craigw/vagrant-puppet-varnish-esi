<?php
$expires = 8;
header("Pragma: public");
header("Cache-Control: max-age=".$expires);
include_once './content_items.php';
free_my_used_items();
?>
<p>Items = <?php echo pick_item(); ?>, <?php echo pick_item(); ?>. I'm part 4. I was fetched at <?php echo date('Y-m-d\TH:i:s', mktime()) . substr((string)microtime(), 1, 6) ?>Z (expires = 8s, generation = <?php echo $_GET["generation"] ?>)</p>
