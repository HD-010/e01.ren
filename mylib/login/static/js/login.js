 /*******************************************/
 /*****************判断用户是否登录**************/
 /*******************************************/
var login = new Object();
login.isLogin = function (){
	if(!sessionStorage.fromUin){
		location.href='http://localhost/mylib/chatroom/login.html';
	}
}