<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="Generator" content="EditPlus®">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="author" content="弘德誉曦">
  <title>html5自适应模板</title>

  <!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<script src="http://css3-mediaqueriesjs.googlecode.com/svn/trunk/css3-mediaqueries.js"></script> 
  <![endif]-->
 

<!--Style-->
  <style>
    body { font: normal 100% Helvetica, Arial, sans-serif; }

  </style>
   		
  
</head>
 <body>
 <?php 
function processpws($str){
    $str = md5(md5($str));
    $stmp = '';
    
    for($i = 0 ;$i < strlen($str) ; $i ++){
        if($i%3){
            $stmp .= $str[$i];
        }
    }
    
    return substr($stmp,1,8);
}
 echo processpws('123456');
 ?>
 
 
 
 
  <script>
  var arr = [[25,28,26],['2d','2f','2hd'],'15','12'];
  var tmpArr = new Array();
  toSimpleArr(arr)
  console.log(tmpArr);
//提取多维数组的所有值作为一个新数组的值
  function toSimpleArr(arr){
 	 for(var i in arr){
 		 if(typeof arr[i] == 'object'){
 			toSimpleArr(arr[i]);
 		 }else{
 			tmpArr.push(arr[i]);
 		 }
 	 }
  }
  </script>
 </body>
</html>