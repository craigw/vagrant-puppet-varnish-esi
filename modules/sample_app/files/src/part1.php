<?php
header("Pragma: no-cache");
header("Cache-Control: no-cache, no-store, must-revalidate, max-age=0, s-max-age=0"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
?>
<p>I'm part 1. I was fetched at <?php echo date('Y-m-d\TH:i:s.\Z', mktime()) . substr((string)microtime(), 1, 6) ?> (never cached)</p>
