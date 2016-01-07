
var ajaxSending=false;
$(function(){
	
	$('[data-toggle="tooltip"]').tooltip({container: 'body'});
	//注册
	$('#regfrom').unbind("submit").submit(function(){
		var username=$('#username').val();
		var email=$('#email').val();
		var pwd=$('#pwd').val();
		var pwd2=$('#pwd2').val();
		var autologin=$('#autologin').is(':checked');
		var regbtn=1;
		if(username=='' || email=='' || pwd=='' || pwd2==''){
			alert('请填写完整的资料');
			return false;
		}
		$(this).find('span').each(function() {
			if($(this).text()!=''){
				alert('请填写正确的资料');
				$(this).prev('input').focus();
				return false;
			}
		});
		var data={username:username,email:email,pwd:pwd,pwd2:pwd2,autologin:autologin,regbtn:regbtn};
		var url='index.php?m=user&do=reg';
		_ajax(url,data,function(res){
			callback_reg(res);
		});
		return false;
	})
	function callback_reg(res){
		ajaxSending=false;
		if(res['status']==-1){
			alert('请求失败');
			return false;
		}
		if(res['status']==2){
			if(res['data']['id']!='error'){
				var id=res['data']['id'];
				errormessage(id,res['data']['msg']);
				$('#'+id).focus();
			}else{
				alert(res['data']['msg']);
			}
			return false;
		}
		if(res['status']==1){
			var url=$('#referer').val();
			window.location.href=url;
		}
	}
	$('#regfrom .form-control').unbind("blur").blur(function(){
		var id=$(this).attr('id');
		var value=$.trim($(this).val());
		//防止相同值重复提交
		var authval=$('#auth'+id).val();
		if(authval==value){
			return false;
		}
		switch(id){
			case "username":
				errormessage(id,'');
				if(value.match(/<|"/ig)) {
					errormessage(id, '用户名包含敏感字符');
					return false;
				}
				var unlen = value.replace(/[^\x00-\xff]/g, "**").length;
				if(unlen < 3 || unlen > 15) {
					errormessage(id, unlen < 3 ? '用户名不得小于 3 个字符' : '用户名不得超过 15 个字符');
					return false;
				}
				break;
			case "email":
				errormessage(id,'');
				if(value.match(/<|"/ig)) {
					errormessage(id, 'Email 包含敏感字符');
					return false;
				}
				if(!value.match(/^([a-z0-9\-_.+]+)@([a-z0-9\-]+[.][a-z0-9\-.]+)$/ig)) {
					errormessage(id, '不是有效的 Email');
					return false;
				}
				break;
			case "pwd":
				errormessage(id,'');
				if(value.length < 6) {
					errormessage(id, '密码太短，不得少于 6 个字符');
					return false;
				}
				break;
			case "pwd2":
				errormessage(id,'');
				var pwd=$.trim($('#pwd').val());
				if(value != pwd) {
					errormessage(id, '两次输入的密码不一致');
					return false;
				}
				break;
		}
		var checkarr=['username','email'];
		if(in_array(id,checkarr) && value!=''){
			var url='index.php?m=user&do=check';
			var data={type:id,data:value};
			_ajax(url,data,function(res){
				callback_check(res);
			});
		}
	});
	//验证用户名或邮箱
	function callback_check(res){
		ajaxSending=false;
		if(res['status']==-1){
			alert('请求失败');
			return false;
		}
		var id='';
		id=res['data']['type'];
		//防止相同值重复提交
		$('#auth'+id).val(res['data']['val']);
		if(res['status']==1){
			$('#'+id).parent("div").parent("div").addClass('has-success');
		}else{
			errormessage(id,res['data']['msg']);
		}
		
	}
	function errormessage(id,msg){
		if(msg==''){
			$('#'+id).parent("div").parent("div").removeClass('has-success');
			$('#'+id).next('span').text('');
		}else{
			$('#'+id).next('span').text(msg);
		}
		
	}
	//登录
	//获取登录框视图模版
	$('.login').click(function(){
		showLogin();
	})
	
	//dropdown
	$('.dropdown').unbind("mouseover").mouseover(function(){
		$(this).addClass('open');
	})
	$('.dropdown').unbind("mouseout").mouseout(function(){
		$(this).removeClass('open');
	})

	//add comment
	$('#addcomment').unbind("click").click(function(){
		var aid=$('#article_subject').attr('data');
		var content=$('#comment_edit').val();
	//	content=strip_tags(content);//涉及到代码的显示，先注释
		content=content.replace(/&nbsp;/g, '');
		if(content==''){
			alert('评论内容不能为空');
			return false;
		}
		var rcid=$(this).attr('data');
		var data={aid:aid,content:content,rcid:rcid};
		var url='index.php?m=comment&do=add';
		_ajax(url,data,function(res){
			callback_addcomment(res);
		});
		return false;
	})
	function callback_addcomment(res){
		ajaxSending=false;
		if(res['status']==-1){
			alert('请求失败');
			return false;
		}
		if(res['status']==1){
			$('#comment_edit').val('');
			$('.no-conmment').remove();
			$('.comments-list').append(res['data']);
		}else{
			$('.editor-notice').text(res['data']);
		}
	}
	//load more
	$('.loadmore').unbind("click").click(function(){
		var dom=$(this);
		var url=dom.attr('data');
		if(url==''){
			alert('请求失败');
			return false;
		}
		dom.text('正在加载...');
		dom.attr('disabled',true);

		_ajax(url,'',function(res){
			callback_getcomment(res);
		});

	})
	function callback_getcomment(res){
		var dom=$('.loadmore');
		ajaxSending=false;
		if(res['status']==-1){
			alert('请求失败');
			return false;
		}
		if(res['status']==1){
			$('.comments-list').append(res['data']['content']);
			//reset bind
			$('.com-tip-recom').unbind("click").click(function(){
				reply_comment($(this));
			})
			$('.com-tip-like').unbind("click").click(function(){
				zan_comment($(this));
			})
			if(res['data']['next']==''){
				dom.remove();
				return true;
			}
		}else{
			alert(res['data']);
		}
		dom.text('加载更多');
		dom.attr('data',res['data']['next']);
		dom.attr('disabled',false);
	}
	//reply comment
	$('.com-tip-recom').unbind("click").click(function(){
		reply_comment($(this));
	})
	//zan comment
	$('.com-tip-like').unbind("click").click(function(){
		zan_comment($(this));
	})
	function zan_comment(dom){
		var cid=dom.attr('data');
		var data={cid:cid};
		var url='index.php?m=comment&do=zan';
		_ajax(url,data,function(res){
			ajaxSending=false;
			if(res['status']==-1){
				alert('请求失败');
				return false;
			}
			if(res['status']==1){
				var num=dom.find('span').text();
				if(num==''){
					num = 1;
				}else{
					if(res['data']==1){
						num = parseInt(num)+1;
					}else if(res['data']==-1){
						if(num!=0){
							num = parseInt(num)-1;
						}
					}
				}
				dom.find('span').text(num);
			}else{
				alert(res['data']);
			}
		});
	}

	//文章列表页简单样式
	$(".row-bottom").mouseover(function(){
		$(this).addClass('box-shadow');
	});
	$(".row-bottom").mouseleave(function(){
		$(this).removeClass('box-shadow');
	});

	//搜索标签
	$("#tags_ipt_add").autocomplete('index.php?m=tag&do=search',{
		matchContains:true,
		delay:500,
		cacheLength:100,
		matchSubset:true,
		minChars:1,
		width:170,
		max:20,
		scrollHeight:500,
		parse: parseData,
		formatItem: formatItem
	}).result(function(even,item){
		addTag(item.id,item.tagname,'article');
	});
	//删除话题文章关系
	$(".t-rem").unbind("click").click(function(){
		removetag($(this));
	})
	//显示/隐藏tag 编辑区域
	$('.tag-edit').click(function(){
		showTagArea();
	})
	$('.a-btn').click(function(){
		showTagArea();
	})
	//文章点赞
	$('.like-btn').click(function(){
		var id=$('#article_subject').attr('data');
		_ajax('?m=article&do=zan',{'aid':id},function(res){
			callback_like(res);
		});
	})
	function callback_like(res){
		ajaxSending=false;
		if(res['status']==1){
			var num = $('.article-like').text();
			if(res['data']=='add'){
				num = ++num;
			}else{
				num = --num;
			}
			if(num>0){
				$('.article-like').text(num);
			}
		}
	}
	
})
function showLogin(){
	$.post('?m=user&do=login',{},function(d){
		var d = eval('('+d+')');
		showDialog('登录',d['data'],'登录',function(){
			var l_name=$.trim($('#li_username').val());
			var l_pwd=$.trim($('#li_pwd').val());
			var l_autologin=$('#li_autologin').is(':checked');
			var loginbtn=1;
			if(l_name=='' || l_pwd==''){
				alert('用户名或密码不能为空');
				$('#li_username').focus();
				return false;
			}
			var data={username:l_name,pwd:l_pwd,autologin:l_autologin,loginbtn:loginbtn};
			var url='index.php?m=user&do=login';
			_ajax(url,data,function(res){
				callback_login(res);
			});
			return false;
		});
	})
}
function callback_login(res){
	ajaxSending=false;
	if(res['status']==-1){
		alert('请求失败');
		return false;
	}
	if(res['status']==1){
		window.location.reload();
	}else{
		$('#li_error').text(res['data']);
	}
}

function showDialog(title,content,yestitle,yesfun){
	if(isUndefined('yestitle')){
		yestitle = '确认';
	}
	layer.open({
		type: 1,//0（信息框，默认）1（页面层）2（iframe层）3（加载层）4（tips层）
		title: title,
		closeBtn: 1,//右上角关闭按钮
		shade:0.1,//遮罩样式
		shadeClose: false,//点击遮罩就关闭弹框
		skin: '',//皮肤样式
	//	area: ['500px', '300px'],//宽高
		btn:[yestitle,'关闭'],//回调分别为 yes 和 cancel
		yes:yesfun,
		shift:0,//出场动画，0-6(css3 ie6-9不支持)
		content: content
	});
}
function showMessage(content,style){
	//icon 1 成功 , 2 失败 , 3 询问 , 4 锁定 , 5 哭脸 , 6 笑脸
	//layer.alert(content, {icon: 6});
	if(in_array(style,[1,2,3,4,5,6])){
		style = 1;
	}
	layer.open({
		type: 0,//0（信息框，默认）1（页面层）2（iframe层）3（加载层）4（tips层）
		title: '提示',
		closeBtn: 1,//右上角关闭按钮
		icon: style,//图标样式
	//	time:3000,//自动关闭时间
		content: content
	});
}
//添加标签
function addTag(id,tagname,type){
	$('#tags_ipt_add').val("");
	if(id==-1 || id==-2){
        return false;
    }
    var length=mb_strlen(tagname);
    var sublen=Math.ceil(length/2);
    if(sublen>25){
        alert('话题长度只能是50个字符或25个汉字');
        return false;
    }
	var spancount=$('#tags_item_add span').length;
    if(spancount>=5){
        alert('只能有5个标签');
        return false;
    }
    var errortitle='';
    $('#tags_item_add span').each(function() {
        if(tagname==$(this).text()){
            errortitle='该标签已存在';
            return false;
        }
    });
    $('#tags_item_add a').each(function() {
        if(id==$(this).attr("data")){
            errortitle='该标签已存在';
            return false;
        }
    });
    if(errortitle!=''){
        alert(errortitle);
        return false;
    }
    var success=true;
    if(id==0){
		_ajax('index.php?m=tag&do=add',{tagname:tagname},function(res){
			if(res['status']==1){
				id = res['data'];
			}else{
				alert(res['data']);
				success = false;
			}
		},false);
		ajaxSending=false;
    }
    if(!success){
        return false;
    }
	//已存在该关系
	if(!addRelation(id)){
		return false;
	}
    
	var html = '<div><span>'+tagname+'</span><a href="javascript:;" data="'+id+'" class="t-rem" name="removetag"></a></div>';
	$('#tags_item_add').append(html);
	
	//将新添加的标签append到显示区域
	var showHtml = '<a href="?m=tag&do=view&tid='+id+'" data="'+id+'">'+tagname+'</a>';
	$('.tag-show-area span').append(showHtml);
	//重新绑定删除
	$(".t-rem").unbind("click").click(function(){
		removetag($(this));
	})
}
function removetag(_this){
	var id = _this.attr('data');
	var tagArea = $('.tag-show-area span a');
	if(tagArea.length==1){
		alert('至少保留一个标签');
		return false;
	}
	_this.parent('div').remove();
	tagArea.each(function() {
		if($(this).attr('data')==id){
			$(this).remove();
			return false;
		}
	});
	addRelation(id,'delRelation');
}
//添加/删除标签文章关系
function addRelation(id,dotype){
	var status=true;
	var aid = $('#article_subject').attr('data');
	var dotype = dotype=='delRelation' ? 'delRelation' : 'addRelation';
	_ajax('index.php?m=tag&do='+dotype,{'tagid':id,'aid':aid},function(res){
		if(res['status']==1){
			//添加/删除关系成功
		}else{
			alert(res['data']);
			status=false;
		}
		ajaxSending=false;
	},false);
	return status;
}
//显示/隐藏tag
function showTagArea(){
	var showarea=$('.tag-show-area');
	var editarea=$('.tag-edit-area');
	if(showarea.is(':visible')){
		showarea.hide();
		editarea.show();
	}else{
		showarea.show();
		editarea.hide();
	}
}
function parseData(data){
	return $.map(eval(data), function(row) {
		return {
			data: row,
			value: row.tagname,
			result: row.id
		}
	});
}
function formatItem(row, i, max){
	if(row.id==0){
		return '<span>创建 '+row.tagname+' 话题</span>';
	}
	if(row.id==-1){
		return '<span>'+row.tagname+'　正在审核</span>';
	}
	if(row.id==-2){
		return '<span>请使用已存在的话题</span>';
	}
	return '<span>'+row.tagname+'</span>';
}
function reply_comment(dom){
	var cid=dom.attr('data');
	var author=dom.parents('.media-body').find('.com-author').text();
	$('#addcomment').attr('data',cid);
	var html='<span style="color:#c1c1c1;">回复<span style="margin:0px 10px;">'+author+'</span><a href="javascript:;" onclick="removereply(this);">X</a></span>';
	$('.edit-bottom-left').html(html);
}

function removereply(dom){
	$('#addcomment').attr('data','');
	$(dom).parents('.edit-bottom-left').html('');
}
//仿php in_array
function in_array(needle, haystack) {
	if(typeof needle == 'string' || typeof needle == 'number') {
		for(var i in haystack) {
			if(haystack[i] == needle) {
				return true;
			}
		}
	}
	return false;
}
//ajax 
function _ajax(url,data,callback){
	if(ajaxSending==true){
		alert('有请求正在执行...');
		return false;
	}
	var async = arguments[3]==false ? arguments[3] : true;
	$.ajax({
		type:'post',
		url:url,
		dataType:'json',
		data:data,
		async:async,
		beforeSend:function(){
			ajaxSending=true;
		},
		success:callback,
		error:function(){
			ajaxSending=false;
		}
	});
}
function _ajax_return(res){
	ajaxSending=false;
	if(res['status']==-1){
		return 0;
	}
	return res;
}
function isUndefined(variable) {
	return typeof variable == 'undefined' ? true : false;
}
function mb_strlen(str) {
	var len = 0;
	for(var i = 0; i < str.length; i++) {
		len += str.charCodeAt(i) < 0 || str.charCodeAt(i) > 255 ? 3 : 1;
	}
	return len;
}
//js strip_tags
function strip_tags(input, allowed) {
  allowed = (((allowed || '') + '')
    .toLowerCase()
    .match(/<[a-z][a-z0-9]*>/g) || [])
    .join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
  var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
    commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
  return input.replace(commentsAndPhpTags, '')
    .replace(tags, function($0, $1) {
      return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
    });
}
//延迟加载
$(function(){
	$("img.lazy").lazyload({
		effect : "fadeIn"
	});
})
/*
search		查找(数组)
replace		替换(数组)
str			在该字符串中查找
regswitch	正则修饰符
*/
function preg_replace(search, replace, str, regswitch) {
	var regswitch = !regswitch ? 'ig' : regswitch;
	var len = search.length;
	for(var i = 0; i < len; i++) {
		re = new RegExp(search[i], regswitch);
		str = str.replace(re, typeof replace == 'string' ? replace : (replace[i] ? replace[i] : replace[0]));
	}
	return str;
}
//打印js 对象
function printarr(obj){ 
	var description = ""; 
	for(var i in obj){   
		var property=obj[i];   
		description+=i+" = "+property+"\n";  
	}   
	alert(description); 
}

