<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:48:"F:\study\ch_admin/app/admin\view\index\main.html";i:1504260618;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" href="__ADMIN__/plugins/layui/css/layui.css" media="all" />
    <link rel="stylesheet" href="__ADMIN__/css/global.css" media="all">
    <link rel="stylesheet" href="__ADMIN__/css/main.css" />
</head>
<body>
<div class="admin-main">
    <div class="layui-field-box table-responsive">
        <table class="layui-box layui-table" lay-even lay-skin="line">
            <colgroup>
                <col width="40%">
                <col>
            </colgroup>
            <thead>
            <tr>
                <th class="text-center" colspan="2"><?php echo lang('systemInfo'); ?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>网站域名</td>
                <td><?php echo $config['url']; ?></td>
            </tr>
            <tr>
                <td>网站目录</td>
                <td><?php echo $config['document_root']; ?></td>
            </tr>
            <tr>
                <td>服务器操作系统</td>
                <td><?php echo $config['server_os']; ?></td>
            </tr>
            <tr>
                <td>服务器端口</td>
                <td><?php echo $config['server_port']; ?></td>
            </tr>
            <tr>
                <td>服务器IP</td>
                <td><?php echo $config['server_ip']; ?></td>
            </tr>
            <tr>
                <td>WEB运行环境</td>
                <td><?php echo $config['server_soft']; ?></td>
            </tr>
            <tr>
                <td>MySQL数据库版本</td>
                <td><?php echo $config['mysql_version']; ?></td>
            </tr>
            <tr>
                <td>运行PHP版本</td>
                <td><?php echo $config['php_version']; ?></td>
            </tr>

            <tr>
                <td>最大上传限制</td>
                <td><?php echo $config['max_upload_size']; ?></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
</body>

</html>