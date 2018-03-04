$("#style_color").spectrum({
    showPaletteOnly: true,
    showPalette:true,
    hideAfterPaletteSelect:true,
    palette: [
        ['#009688', '#5FB878', '#393D49', '#1E9FFF'],
        ['#F7B824', '#FF5722', '#01AAED', '#2F4056']
    ]
});

$(function(){
    // 初始化插件
    $("#images").zyUpload({
        width            :   "",                 // 宽度
        height           :   "",                 // 宽度
        itemWidth        :   "140px",                 // 文件项的宽度
        itemHeight       :   "115px",                 // 文件项的高度
        url              :   UPURL,              // 上传文件的路径
        fileType         :   ["jpg","png","bmp","gif"],// 上传文件的类型
        fileSize         :   51200000,                // 上传文件的大小
        multiple         :   true,                    // 是否可以多个文件上传
        dragDrop         :   false,                   // 是否可以拖动上传文件
        tailor           :   false,                   // 是否可以裁剪图片
        del              :   true,                    // 是否可以删除文件
        finishDel        :   true,  				  // 是否在上传文件完成后删除预览
        /* 外部获得的回调接口 */
        onSelect: function(selectFiles, allFiles){    // 选择文件的回调方法  selectFile:当前选中的文件  allFiles:还没上传的全部文件
            /*console.info("当前选择了以下文件：");
             console.info(selectFiles);*/
        },
        onDelete: function(file, files){              // 删除一个文件的回调方法 file:当前删除的文件  files:删除之后的文件
            /*console.info("当前删除了此文件：");
             console.info(file.name);*/
        },
        onSuccess: function(file, response){          // 文件上传成功的回调方法
            /*console.info("此文件上传成功：");
             console.info(file);
             console.info("此文件上传到服务器地址：");
             console.info(response);*/
            var html = '';
            var num = $('#upImg').attr('data-i');
            var inputNum = parseInt(num)+parseInt(file.index)+1;
            html+='<div class="layui-input-3 imgBox"><i class="jdImg fa fa-times" data-url="'+response+'"></i>';
            html+='<div class="imageBox"><a href="'+PUBLIC+response+'" target="_blank" title="点击查看"><img src="'+PUBLIC+response+'"></a></div>';
            html+='<div><input name="pics['+inputNum+']" class="layui-input pics" value="'+response+'"></div>';
            html+='</div>';
            $("#upImg").append(html);
        },
        onFailure: function(file, response){          // 文件上传失败的回调方法
            /* console.info("此文件上传失败：");
             console.info(file.name);*/
        },
        onComplete: function(response){           	  // 上传完成的回调方法
            /*console.info("文件上传完成");
             console.info(response);*/
        }
    });

});
