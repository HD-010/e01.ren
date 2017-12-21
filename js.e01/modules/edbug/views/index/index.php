<?php
    use yii\helpers\Html;
    use yii\grid\GridView;

    /* @var $this yii\web\View */
    /* @var $searchModel app\models\GamesSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '文章列表');
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile("/js/Framework.js");

//$this->registerJsFile("/js/admin/admingames/games_index.js");

//加载弹出确认提示框的css文件
$this->registerCssFile("/css/sweet-alert/sweet-alert.css");
$this->registerJs("
//加载没有选择用户组的提示框
IanInclude('System.shared');

//加载确认提示框
IanInclude('System.sweet_alert.sweet_alert_min');

//加载loading提示模态窗口
IanInclude('System.jquery.blockUI.jquery_blockUI');

//加载分页组件
IanInclude('System.ianPager');

var pager = new ianPager();
pager.PageSize({$pages->pageSize});
pager.Width('100%');
pager.SubmitButtonStyle('height:22;FONT-SIZE: 10px;');
pager.InputBoxStyle('width:30px;FONT-SIZE: 10px;');
pager.PrevPageText('上一页');
pager.NextPageText('下一页');
pager.FirstPageText('首页');
pager.LastPageText('尾页');
pager.PagingButtonSpacingStyle('display: inline-block;width:10px;');
pager.ShowCustomInfoSection('Left');
pager.AlwaysShow(true);
pager.ShowPageIndex(false);
pager.ShowWriteButtonSpace(true);
pager.ShowInputBox('Always');
pager.TextBeforeInputBox('跳到&nbsp;');
pager.TextAfterInputBox('&nbsp;页&nbsp;&nbsp;');
pager.RecordCount({$pages->totalCount});
pager.CurrentPageIndex({$currentPage});
pager.CustomInfoText(pager.Format('&nbsp;&nbsp;页次: <span style=\'color:red;\'>{0}</span> / <span style=\'color:red;\'>{1}</span>页&nbsp;&nbsp;<span style=\'color:red;\'>{2}</span>篇文章/页&nbsp;&nbsp;共有&nbsp;<span style=\'color:red;\'>{3}</span>&nbsp;篇文章&nbsp;', pager.CurrentPageIndex(), pager.PageCount(), pager.PageSize(), pager.RecordCount()));
pager.UrlPageIndexName('currentpage');
$('#ian_pager_articles').html(pager.Html());
");
?>

<div class="games-index">
    <div class="row">
        <div class="col-lg-12">
            <div class=" panel-default">
                <div class="dataTables_wrapper form-inline">

            <div style="margin:10px 0;">
                <?php if(in_array(774, $userPower)){ ?>
                <button type="button" id="doRecycle" class="btn btn-outline btn-primary" >批量删除</button>&nbsp;&nbsp;
                <?php } ?>
                <?php if(in_array(772, $userPower)){ ?>
                <?=Html::a ( Yii::t ( 'app', '添加文章', [ 'modelClass' => 'Articles' ] ), [ 'create' ], [ 'class' => 'btn btn-default' ] )?>
                <?php } ?>
            </div>

            <table class="table table-striped table-bordered" width="100%">
                <thead>
                <tr>
                    <th ><input type="checkbox" id="selectall" class="checkall" /></th>
                    <th >ID</th>
                    <th>文章封面</th>
                    <th >文章标题</th>
                    <th >更新时间</th>
                    <th >操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($dataProvider as $data):?>
                <tr  class="text-center">
                    <td align="center"><input type="checkbox"  name="chk_list" value="<?php echo $data['id'] ; ?>"></td>
                    <td><?php echo Html::encode($data["id"]) ?></td>
                    <td style="padding:5px 20px"><img height="40" width="40" src="<?php echo Yii::$app->params['ImageServerHost'].$data["pic_thumb"] ?>" height="50px" width="50px" /></td>
                    <td><?php echo Html::encode($data["title"]) ?></td>
                    <td><?php echo Html::encode(($data["updatetime"])) ?></td>
                    <td>
                        <?php
                        if(in_array(773, $userPower)){
                        echo "&nbsp;";
                        echo Html::a(Yii::t('yii', '修改'), yii::$app->urlManager->createUrl(["/adminapparticles/index/update","id"=>$data["id"]]),[
                        "class"=>"btn btn-outline btn-primary btn-xs",
                        'title' => Yii::t('yii', 'Update'),
                        'data-pjax' => '0'
                        ]);
                        }
                        if(in_array(774, $userPower)){
                        echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                        echo Html::a(Yii::t('yii', '删除'), yii::$app->urlManager->createUrl(["/adminapparticles/index/delete","id"=>$data["id"]]),[
                        "class"=>"btn btn-outline btn-danger btn-xs deleteone",
                        'title' => Yii::t('yii', 'Delete'),
                        'data-method' => 'post',
                        'data-pjax' => '0',
                        ]);
                        }
                        ?>
                    </td>
                </tr>
                <?php endforeach;?>
                </tbody>
            </table>
            <div id="ian_pager_articles" style="padding-bottom:20px;"></div>
            <form action="<?php echo Yii::$app->urlManager->createUrl('/adminapparticles/index/recycles') ?>" method="get" id="allformrecycle" accept-charset="utf-8">
                <input type="hidden" name="ids" />
            </form>
        </div>


        <script type="text/javascript">
            window.onload = laoding;
            function laoding(){

                //全部选中操作
                $('.checkall').click(function(){
                    var ischeck = $(this).is(":checked");
                    $("input[name='chk_list']").each(function(){
                        this.checked = ischeck;
                    });
                });

                //删除单个出现弹出框
                $('.deleteone').click(function(){

                    var href = $(this).attr('href');
                    $(this).attr('href','');
                    swal({
                        title: "",
                        text:"确定要删除吗",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: '确认',
                        cancelButtonColor: '#DD6B55',
                        cancelButtonText:'取消',
                        imageSize:"40*40"
                    },function(){

                        location.href = href;
                    });
                    return false;
                });


                //批量删除弹出层

                $('#doRecycle').click(function(){
                    doIdsSub('allformrecycle');
                });

                function doIdsSub(allform){
                    var ids = '';
                    $("input[name='chk_list']").each(function(){
                        if($(this).is(":checked")){
                            ids += $(this).val() + ',';
                        }
                    });

                    if(!ids){
                        ShowTips('请选择',0,0);
                        return ;
                    }

                    swal({
                        title: "",
                        text:"确认要操作选中的项吗?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: '确认',
                        cancelButtonColor: '#DD6B55',
                        cancelButtonText:'取消',
                        imageSize:"40*40"
                    },function(){

                        $("input[name='ids']").val(ids);

                        $("#" + allform).submit();
                    });
                }

            }
        </script>
