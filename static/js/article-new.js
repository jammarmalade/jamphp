//window.onbeforeunload = function() {
//	return("有内容正在编辑，确定离开？");
//}
$(function(){
	$('#article_content').summernote({
		lang: 'zh-CN',
		height: 300,
		minHeight: null,
		maxHeight: null,
		focus: true ,
		styleTags: ['p', 'blockquote', 'pre', 'h3'],
		toolbar: [
			['style', ['style']],
			['style', ['bold', 'italic', 'underline', 'clear']],
			['insert', ['picture', 'link', 'hr']],
			['kbd', ['kbd']],
		],
		onImageUpload: function(files, editor, $editable) {
			for(var i=0;i<files.length;i++){
				sendFile(files[i],editor,$editable);
			}
		}
	});
	
	function sendFile(file, editor, $editable){

		data = new FormData();
		data.append("file", file);
		$.ajax({
			data: data,
			type: "POST",
			url: "index.php?m=upload&do=article-new",
			cache: false,
			async: false,
			contentType: false,
			processData: false,
			success: function(data) {
				res=eval("("+data+")");
				if(res['status']==1){
					var imgwidth=600;
					var imgurl=res['data']['url'];
					var aid=res['data']['aid'];
					editor.insertImage($editable,imgurl,aid,imgwidth);
				}else{
					alert(res['data']);
				}
			},
			error:function(){
				alert('err');
			}
		});
	}
	
	$('button[action-type=article-new]').unbind("click").click(function(){
		var aid = $(this).attr('action-data');
		var subject=$('#subject').val();
		var content=$('#article_content').code();
		var tmpsub=tmpcontent='';
		tmpsub=subject.replace(/&nbsp;/g, '');
		tmpcontent=strip_tags(content);
		tmpcontent=tmpcontent.replace(/&nbsp;/g, '');
		if(tmpsub=='' || tmpcontent==''){
			alert('标题或内容不能为空');
			return false;
		}
		if(subject.length>80){
			alert('标题不能超过 80 个字符');
			return false;
		}
		if(aid!=0){
			var url='index.php?m=article&do=update';
			var data={subject:subject,content:content,type:'update',aid:aid};
		}else{
			var url='index.php?m=article&do=new';
			var data={subject:subject,content:content,type:'new'};
		}
		
		_ajax(url,data,function(res){
			callback_article_new(res);
		});
	})
	function callback_article_new(res){
		ajaxSending=false;
		if(res['status']==-1){
			alert('请求失败');
			return false;
		}
		if(res['status']==1){
			alert(res['data']);
		}else{
			alert(res['data']);
		}
	}
	//编辑时将内容填入富文本编辑器
	$('#article_content').code(ubb2html(defaultcontent));
	//替换 ubb
	function ubb2html(content){
		var str = '';
		str = content.replace(/\[div\]/ig, '<div>');
		str = str.replace(/\[\/div\]/ig, '</div>');
		str = str.replace(/\[code([^\]]*?)\]([\s\S]*?)\[\/code\]/ig, "<pre>$2</pre>");
		str = str.replace(/\[quote\](.+?)\[\/quote\]/ig, "<blockquote>$1</blockquote>");
		str = str.replace(/\[h3\](.+?)\[\/h3\]/ig, "<h3>$1</h3>");
		str = str.replace(/\[url=([^\]]+?)\](.+?)\[\/url\]/ig, "<a href=\"$1\" target=\"_blank\">$2</a>");
		str = str.replace(/\[(b|i|u|kbd)\](.+?)\[\/\1\]/ig, "<$1>$2</$1>");
		str = str.replace(/\[hr\]/ig, "<hr>");
		str = str.replace(/\[img=([^\]]+?)\]/ig, "<img src=\"$1\" width=\"600px\">");
		//代码区域
		str = str.replace(/\[code=(\w+)\](.+?)\[\/code\]/ig, function($1,$2,$3) {
			var tmpcode = preg_replace(['&amp;', '&quot;'],['&','"'] ,$3);
			return '[code='+$2+']'+tmpcode+'[/code]';
		});
	//	str = preg_replace(['&amp;', '&quot;','&#039;','&lt;','&gt;'],['&','"',"'",'<','>'] ,str);

		return str;
	}
});

