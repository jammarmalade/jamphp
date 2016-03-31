<!-- sider -->
<div class="col-xs-6 col-md-4 hidden-xs">
    <div class="row sider-mp bottom-line">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="搜索文章">
            <span class="input-group-btn">
                <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></span></button>
            </span>
        </div><!-- /input-group -->
    </div>
    <!-- /clock -->
    <div class="row sider-mp" style="margin-top:20px;">
        <?php includeJSCSS('clock');?>        <div id="clock" class="light">
            <div class="display">
                <div class="weekdays"></div>
                <div class="ampm"></div>
                <div class="digits"></div>
                <div id="switch-theme" class="button-holder"></div>
            </div>
        </div>
    </div>
    <div class="row sider-mp" style="margin-top:20px;">
        <div class="panel-group" id="side_article" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="side_article_title" style="padding:5px 10px;line-height:5px;height:50px;background-color:#DFF0D8;">
                    <h4 class="panel-title">
                        <a class="collapsed" data-toggle="collapse" data-parent="#side_article" href="#side_article_by" aria-expanded="false" aria-controls="side_article_by">
                            <h4 style="margin-bottom:0px;"><span class="glyphicon glyphicon-book"></span> <b>近期文章</b></h4>
                        </a>
                    </h4>
                </div>
                <div id="side_article_by" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="side_article_title">
                    <div class="panel-body">
                        <ul class="list-unstyled">
                            <li>测试文章啊死擦死擦上安寺村洒出安寺村</li>
                            <li>测试文章洒出安寺村洒出洒出按错阿萨吹撒洒出</li>
                            <li>测试文章</li>
                            <li>测试文章</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row sider-mp">
        <div class="panel-group" id="side_comment" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="side_calendar_title" style="padding:5px 10px;line-height:5px;height:50px;background-color:#DFF0D8;">
                    <h4 class="panel-title">
                        <a class="collapsed" data-toggle="collapse" data-parent="#side_comment" href="#side_comment_by" aria-expanded="false" aria-controls="side_comment_by">
                            <h4 style="margin-bottom:0px;"><span class="glyphicon glyphicon-comment"></span> <b>近期评论</b></h4>
                        </a>
                    </h4>
                </div>
                <div id="side_comment_by" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="side_comment_title">
                    <div class="panel-body">
                        <ul class="list-unstyled">
                            <li>评论评论评论评论评论评论评论评论</li>
                            <li>评论评论评论评论评论评论评论评论</li>
                            <li>评论评论评论评论评论评论评论评论</li>
                            <li>评论评论评论评论评论评论评论评论</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row sider-mp">
        <div class="panel-group" id="side_classify" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="side_calendar_title" style="padding:5px 10px;line-height:5px;height:50px;background-color:#DFF0D8;">
                    <h4 class="panel-title">
                        <a class="collapsed" data-toggle="collapse" data-parent="#side_classify" href="#side_classify_by" aria-expanded="false" aria-controls="side_classify_by">
                            <h4 style="margin-bottom:0px;"><span class="glyphicon glyphicon-file"></span> <b>文章分类</b></h4>
                        </a>
                    </h4>
                </div>
                <div id="side_classify_by" class="panel-collapse collapse  in" role="tabpanel" aria-labelledby="side_classify_title">
                    <div class="panel-body">
                        <ul class="list-unstyled">
                            <li>PHP（90）</li>
                            <li>Mysql（80）</li>
                            <li>Linux（80）</li>
                            <li>DISCUZ（80）</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="row sider-mp">
        <div class="panel-group" id="side_calendar" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="side_calendar_title" style="padding:5px 10px;line-height:5px;height:50px;background-color:#DFF0D8;">
                    <h4 class="panel-title">
                        <a class="collapsed" data-toggle="collapse" data-parent="#side_calendar" href="#side_calendar_by" aria-expanded="false" aria-controls="side_calendar_by">
                            <h4 style="margin-bottom:0px;"><span class="glyphicon glyphicon-time"></span> <b>日历</b></h4>
                        </a>
                    </h4>
                </div>
                <div id="side_calendar_by" class="panel-collapse collapse  in" role="tabpanel" aria-labelledby="side_calendar_title">
                    <div class="panel-body">
                        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--	<div class="row sider-mp">
              <div class="alert alert-success" role="alert" style="padding:5px;margin-bottom:5px;"><h4 style="margin-bottom:0px;"><span class="glyphicon glyphicon-time"></span> <b>日期</b></h4></div>
            </div>
    -->
</div>