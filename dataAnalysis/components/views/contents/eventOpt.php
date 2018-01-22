<style>
*{
    margin:0;
    padding:0;
    text-decoration: none;
}
.eventsOpt{
    width: 100%;
    border: 1px solid silver;
    display: block;
    background-color: #EBEBEB;
}
.eventsOpt ul{
    border:0;
    background-color:#F8F9FA;
    margin-top:1px;
}
.eventsOpt .map{
    height:26px;
    line-height:26px;
    background-color:#FFFFFF;
}
.eventsOpt .map li{
    height:26px;
    line-height:22px;
}

.eventsOpt ul li{
    display: inline-block;
    height:38px;
    line-height:38px;
    margin-left:16px;
}

.eventsOpt select{
    height:28px;
    line-height:38px;
    border:1px solid #A887C8;
    border-radius: 3px;
    background-color:#F8F9FA;
}
.eventsOpt a{
    height:28px;
    line-height:38px;
    border:1px solid #F8F9FA;
    background-color:#F8F9FA;
    color:#4A90E2;
}
</style>


<div class="eventsOpt">
<form>
<ul class="map">
<li>事件分析</li>
</ul>
<ul>
<li>显示</li>
<li>
<select name="eventName">
<option>任意事件</option>
</select>
</li>
<li>的</li>
<li>
<select name="eventOpt">
<option>总次数</option>
</select>
</li>
<li>
<a onclick="function(){return false;}" href="#">✛</a>
</li>
</ul>
<ul>
<li>显示</li>
<li>
<select name="eventName">
<option>任意事件</option>
</select>
</li>
<li>的</li>
<li>
<select name="eventOpt">
<option>总次数</option>
</select>
</li>
<li>查看<a onclick="function(){return false;}" href="#">✛</a></li>
</ul>
<ul>
<li>
<a onclick="function(){return false;}" href="#">✛ 筛选条件</a>
</li>
</ul>
</form>


</div>