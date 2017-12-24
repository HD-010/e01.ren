<?php
namespace\mylib;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>右键菜单</title>

</head>
<body>
	<style>
.divfather {
	width: 300px;
	height: 40px;
	border: 1px solid skyblue;
	background-color: skyblue;
}

.divchild1 {
	width: 60px;
	height: 40px;
	border: 1px solid black;
	background-color: black;
	position: absolute;
}
</style>

	<div id="div1" class="divfather">
		<div id="divchild1" class="divchild1"></div>
	</div>
	<div id="viewer"></div>

	<script>
console.log(document.documentElement.div)
</script>
<?php

/**
 *
 * @param unknown $param
 */
function cdd($param)
{
    echo "ok";
}
?>
</body>
</html>