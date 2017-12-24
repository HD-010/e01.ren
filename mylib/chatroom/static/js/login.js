 /*******************************************/
 /*****************判断用户是否登录**************/
 /*******************************************/
var login = new Object();
login.api = 'http://192.168.1.100/mylib/chatroom/login.html'
login.isLogin = function (){
	if(!sessionStorage.fromUin){
		location.href = this.api;
	}
}
//退出系统
function exit(){
	sessionStorage.fromUin='';
	location.reload();
}
