<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:71:"/Users/lvjiluan/project/beqadmin/app/admin/view/organization/basic.html";i:1520141959;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>机构基本信息</title>
    <link rel="stylesheet" href="__ADMIN__/plugins/layui/css/layui.css" media="all" />
    <link rel="stylesheet" href="__ADMIN__/css/global.css" media="all">
    <link rel="stylesheet" href="__ADMIN__/plugins/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="__ADMIN__/css/style.css" />
</head>

<body>
<div class="admin-main">
    <?php if(ORG_ID <= 0): ?><blockquote class="layui-elem-quote">
        <a href="<?php echo url('basicAdd'); ?>" class="layui-btn layui-btn-small">
            <i class="fa fa-plus"></i> <?php echo lang('add'); ?>机构
        </a>
    </blockquote><?php endif; ?>
    <fieldset class="layui-elem-field ">
        <legend>机构信息</legend>
        <div class="layui-field-box table-responsive">
            <table class=" layui-table table-hover">
                <thead>
                <tr>
                    <th>机构名称</th>
                    <th>评价星级</th>
                    <th>机构类型</th>
                    <th>机构级别</th>
                    <th>负责人姓名</th>
                    <th>负责人头像</th>
                    <th>联系方式</th>
                    <th>机构简介</th>
                    <th>机构地址</th>
                    <th>机构创建时间</th>
                    <th>最后修改时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                <tr>
                    <td><?php echo $v['org_name']; ?></td>
                    <td><?php echo $v['org_grade']; ?></td>
                    <td><?php echo $v['org_type']; ?></td>
                    <td><?php echo $v['ode_level']; ?></td>
                    <td><?php echo $v['org_chargename']; ?></td>
                    <td><img style="max-width:80px;max-height:60px;" src="<?php echo $v['org_chargepicurl']; ?>"/></td>
                    <td><?php echo $v['org_phone']; ?></td>
                    <td><?php echo $v['ode_detail']; ?></td>
                    <td><?php echo $v['org_address']; ?></td>
                    <td><?php echo $v['org_createtime']; ?></td>
                    <td><?php echo $v['org_updatetime']; ?></td>
                    <td>
                        <a href="<?php echo url('basicEdit',['org_id'=>$v['org_id']]); ?>" class="layui-btn layui-btn-mini"><?php echo lang('edit'); ?></a>
                        <?php if(ORG_ID <= 0): ?><a href="javascript:;" onclick="return del('<?php echo $v['org_id']; ?>')" class="layui-btn layui-btn-mini layui-btn-danger"><?php echo lang('del'); ?></a><?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
            </table>

        </div>
    </fieldset>
    <div class="admin-table-page">
        <div id="page" class="page">
            <?php echo $page; ?>
        </div>
    </div>
</div>
<script type="text/javascript" src="__ADMIN__/plugins/layui/layui.js"></script>
<script src="__STATIC__/js/jquery.2.1.1.min.js"></script>
<script>
    layui.use(['form', 'layer'], function() {
        var form = layui.form(), layer = layui.layer;
    });
    function del(id) {
        layer.confirm('<span style="color:red">删除机构牵扯庞大数据移除，请谨慎操作！！！</span>', {icon: 3}, function (index) {
            window.location.href = "<?php echo url('basicDel'); ?>?org_id="+id;
        });
    }

</script>
</body>

</html>