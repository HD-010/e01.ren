<style>
body {
    overflow: hidden;
}
.layout{
    background-color: #2E2733;
    position: fixed;
    width: 100%;
    height: 100%;
    bottom: 0;
    left: 0;
}
.homeChart1{
	width: 60%;
    height: 100%;
    border: 0;
	position: absolute;
	margin-left:5%;
	
}
.sing{
    width:40%;
	height:30%;
	border:0;
	margin-top:25%;
	margin-left:65%;
}
.singTitle{
	color:#AEAEAE;
	font-weight:400;
}
.singTitle font{
	color:coral;
}
.sing li{
	height:42px;
	line-height:42px;
}
.sing li input{
	height:26px;
	border:1px solid #696969;
	background-color:#2E2733;
	color:#AEAEAE;
	text-align:center;
}
.sing li input[name="username"],
.sing li input[name="password"],
.sing li input[name="password1"]{
	width:200px;
}
.sing li input[name="verify"]{
	width:120px;
}
.sing .up{
	display:none;
}
.sing li span[name="verifyNum"]{
	width:74px;
	height:26px;
	border:1px solid #696969;
	color:#AEAEAE;
	display:inline-block;
	vertical-align: middle;
	text-align:center;
	line-height:26px;
}
.sing li input[type="reset"],.sing li input[type="submit"]{
	width:50px;
}
.sing li input[type="submit"]{
	margin-left:96px;
}

</style>
<script src="/assets/js/app/home.js"></script> 
<?php //echo $data;?>

<div class="layout">
	<div class="homeChart1" id="homeChart1"></div>
	<form name="sing">
    	<ul class="sing" id="sing">
    		<li class="singTitle">用户<font>登录</font><font>&nbsp;注册</font></li>
    		<li><input type="text" name="username" placeholder="请输入用户名" value="" /></li>
    		<li><input type="password" name="password" placeholder="请输入密码" value="" /></li>
    		<li class="up"><input type="password" name="password1" placeholder="请再次输入密码" value="" /></li>
    		<li class="in up">
    			<input type="text" name="verify" placeholder="请输入验证码" value="" />
    			<span name="verifyNum">453543</span>
    		</li>
    		<li>
    			<input class="bt" type="reset"  value=" 重置 " />
    			<input class="in" type="submit"  value=" 登录 " />
    			<input class="up" type="submit" disabled="disabled" value=" 注册 " />
    		</li>
    	</ul>
	</form>
</div>