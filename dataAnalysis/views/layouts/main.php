<?php 
use app\components\Header;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>eAnalysis</title>
    <link rel="stylesheet" type="text/css" href="http://data-analysis.e01.ren/assets/css/analysis.css" />
    <script data-main="/assets/js/app/main" src="/assets/js/require.js"></script> 
</head>
<body>

<?=(new Header())->normal(); ?>

<div name=contents>
	<?=$content;?>
</div>

<div name=codeList></div>
</body>
</html>