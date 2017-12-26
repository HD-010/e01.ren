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
<div name=top>
	<div name=log>eBug</div>
	<ul name=state>
		<li>弘德誉曦</li>
	</ul>
	<ul name=func>
		<li name=t1><a href="">关于</a>
			<ul name=t2>
				<li><a name="setSys">个性设置</a></li>
				<li><a href="javascript:logObj.clear()">清空日志</a></li>
				<li><a href="javascript:alert()">退出 [->]</a></li>
			</ul>
		</li>
	</ul>
</div>

<div class=space></div>

<div name=contents>
	<?=$content;?>
</div>

<div name=codeList></div>
</body>
</html>

