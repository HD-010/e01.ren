<?php 
use app\components\Header;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>eBug</title>
    <link rel="stylesheet" type="text/css" href="http://js.e01.ren/assets/css/eBug.css" />
</head>
<body>

<?=(new Header())->normal(); ?>

<div name=contents>
	<?=$content;?>
</div>

<div name=codeList></div>
</body>
</html>

