<?php
$expires = 8;
header("Pragma: public");
header("Cache-Control: max-age=".$expires);
?>
<p>I'm part 4. I was fetched at <?php echo date('Y-m-d\TH:i:s\Z', mktime()) . substr((string)microtime(), 1, 6) ?> (expires = 8s, generation = <?php echo $_GET["generation"] ?>)</p>
