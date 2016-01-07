var funGetSelected = function(element) {
    if (!window.getSelection) { 
        //IE浏览器
        return document.selection.createRange().text;
    } else {
        return element.value.substr(element.selectionStart, element.selectionEnd - element.selectionStart);
    }
}, funInsertTopic = function(textObj,topic,start) {
	var value = textObj.value, index = value.indexOf(topic);
    if (index === -1) {
        funTextAsTopic(textObj, topic);
    } 
    var value = textObj.value;
    var index = value.indexOf(topic);
    if (textObj.createTextRange) {
        var range = textObj.createTextRange();
		range.moveEnd("character", -1 * value.length);
        range.moveEnd("character", index + start);
        range.moveStart("character", index + start);
        range.select();    
    } else {
        textObj.setSelectionRange(index + start, index + start);
        textObj.focus();
    }
}, funTextAsTopic = function(textObj, textFeildValue , endlen) {
    textObj.focus();
    if (textObj.createTextRange) {
        var caretPos = document.selection.createRange().duplicate();
        document.selection.empty();
        caretPos.text = textFeildValue;
		var value = textObj.value;
		var index = value.indexOf(textFeildValue) + textFeildValue.length - endlen;
		var range = textObj.createTextRange();
		range.moveEnd("character", -1 * value.length);
        range.moveEnd("character", index);
        range.moveStart("character", index);
        range.select();  
    } else if (textObj.setSelectionRange) {
        var rangeStart = textObj.selectionStart;
        var rangeEnd = textObj.selectionEnd;
        var tempStr1 = textObj.value.substring(0, rangeStart);
        var tempStr2 = textObj.value.substring(rangeEnd);
        textObj.value = tempStr1 + textFeildValue + tempStr2;
		var index = textObj.value.indexOf(textFeildValue) + textFeildValue.length - endlen;
		textObj.setSelectionRange(index, index);
        textObj.focus();
    }
};
function editor(editarea,cmd,language){
	var oTextarea = document.getElementById(editarea);
	var start=end=fullcode=insertTxt='';
	switch(cmd) {
		case 'b':
			cmd='b';
			start='[b]';
			end='[/b]';
			fullcode='[b][/b]';
			break;
		case 'i':
			cmd='i';
			start='[i]';
			end='[/i]';
			fullcode='[i][/i]';
			break;
		case 'code':
			cmd='code';
			start='[code='+language+']';
			end='[/code]';
			fullcode='[code='+language+'][/code]';
			break;
		case 'quote':
			cmd='quote';
			start='[quote]';
			end='[/quote]';
			fullcode='[quote][/quote]';
			break;
		case 'kbd':
			cmd='kbd';
			start='[kbd]';
			end='[/kbd]';
			fullcode='[kbd][/kbd]';
			break;
		case 'delformat':
			cmd='delformat';
			break;
		default:
			cmd='';
			break;
	}
	if(cmd==''){
		return false;
	}
	var textSelection = funGetSelected(oTextarea);
    if (!textSelection) {
		if(cmd!='delformat'){
			//没有文字选中，光标处插入
			funInsertTopic(oTextarea,fullcode,start.length);
		}
    } else {
		if(cmd=='delformat'){
			insertTxt=textSelection.replace(/\[\/*[a-z]+(=[a-z])*\]/gi,'');
		}else{
			insertTxt=start + textSelection + end;
		}
        funTextAsTopic(oTextarea,insertTxt ,end.length);
    }
}