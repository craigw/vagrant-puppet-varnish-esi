<?php
$expires = 4;
header("Pragma: public");
header("Cache-Control: max-age=".$expires);
?>
<p>I'm part 3. I was fetched at <?php echo date('Y-m-d\TH:i:s\Z', mktime()) . substr((string)microtime(), 1, 6) ?> (expires = 4s, generation = <?php echo $_GET["generation"] ?>)</p>

