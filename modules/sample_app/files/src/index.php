<?php
$expires = 120;
header("Pragma: public");
header("Cache-Control: max-age=".$expires);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Example Webpage for ESI</title>
    <meta http-equiv="Content-Language" content="English" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="Robots" content="noindex,nofollow" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script type="text/javascript">setTimeout(function() { document.location.reload(true) }, 1000);</script>
  </head>
  <body>
    <p>I'm the container. I was fetched at <?php echo date('Y-m-d\TH:i:s\Z', mktime()) . substr((string)microtime(), 1, 6) ?> (expires = 120s)</p>
    <esi:include src="/part1.php"/>
    <esi:include src="/part2.php"/>
    <esi:include src="/part3.php"/>
    <esi:include src="/part4.php"/>
    <esi:include src="/part5.php"/>
  </body>
</html>
