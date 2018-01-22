<style>
*{
    margin:0;
    padding:0;
    text-decoration: none;
}
.ebugNave {
    border: 1px solid silver;
    width: 220px;
    position: fixed;
    height: 100%;
    display: inherit;
    min-height: 48px;
    line-height: 48px;
    background-color: #425069;
    color: #98AABF;
}
.ebugNave li {
    border:0;
    border-top:1px solid #3A475D;
    display: inherit;
    padding-left:16px;

}
.ebugNave li span :hover {
    cursor: pointer;
}
.ebugNave1 li {
    height: 32px;
    line-height: 32px;
    border:0;
    color: #D7DEE6;
    /* display:none; */
}
.ebugNaveBottom{
    width: 220px;
    height: 42px;
    border: 0;
    display: block;
    position: fixed;
    bottom: 0;
    background-color:#3A475D;
}
.ebugNaveBottom a{
    display: table-cell;
    border: 0;
    width: 73px;
    text-align: center;
    color: #D7DEE6;
    border-left:1px solid #242C3A;
}

</style>
<div class="ebugNave">
<ul>
<li>
<span><font>☛ </font><font>数据概览</font></span>
<span><font>+</font></span>
</li>
<li>
<span><font>☟ </font><font>用户行为分析</font></span>
<ul class="ebugNave1">
<li><span><font>✐ </font><font>事件分析</font></span></li>
<li><span><font>▼ </font><font>漏斗分析</font></span></li>
<li><span><font>♨ </font><font>留存分析</font></span></li>
<li><span><font>☀ </font><font>分布分析</font></span></li>
<li><span><font>☈ </font><font>用户路径</font></span></li>
<li><span><font>➹ </font><font>点击分析</font></span></li>
</ul>
</li>
<li>
<span><font>›</font><font>用户分析</font></span>
<ul  class="ebugNave1">
<li><span><font>☣ </font><font>用户分类</font></span></li>
<li><span><font>☯ </font><font>属性分析</font></span></li>
</ul>
</li>
<li>
<span><font>›</font><font>自定义查询</font></span>
</li>
</ul>
<div class="ebugNaveBottom">
<a href="#"><span>☷</span><span>元数据</span></a>
<a href="#"><span>⊙</span><span>埋点</span></a>
<a href="#"><span>✪</span><span>书签</span></a>
</div>
</div>
