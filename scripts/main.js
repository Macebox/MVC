function bbcode_ins(fieldId, tag)
{
	field=document.getElementById(fieldId);
	if(tag == 'b' || tag == 'i' || tag == 'u' || tag == 'code' || tag == 'img'
	|| tag == 'url' || tag == 'quote')
	{
		if (document.selection)
		{
			field.focus();
			var selected = document.selection.createRange().text;
			var ins = '[' + tag + ']' + selected + '[/' + tag +']';
			var selected2 = document.selection.createRange();
			var sel = document.selection.createRange();
			selected2.moveStart ('character', -field.value.length);
			sel.text = '[' + tag + ']' + selected + '[/' + tag+']';
			sel.moveStart('character', selected2.text.length + ins.length - selected.length);
		}
		//MOZILLA/NETSCAPE/SAFARI support
		else if (field.selectionStart || field.selectionStart == 0)
		{
			var startPos = field.selectionStart;
			var endPos = field.selectionEnd;
			field.focus();
			field.value = field.value.substring(0, startPos)
			+ '[' + tag + ']'+ field.value.substring(startPos, endPos) +'[/' + tag+']'
			+ field.value.substring(endPos, field.value.length);
			field.setSelectionRange(endPos+tag.length, endPos+tag.length);
		}
	} else if (tag == 'url=')
	{
		if (document.selection)
		{
			field.focus();
			var selected = document.selection.createRange().text;
			var ins = '[' + tag + ']' + selected + '[/' + tag +']';
			var selected2 = document.selection.createRange();
			var sel = document.selection.createRange();
			selected2.moveStart ('character', -field.value.length);
			sel.text = '[' + tag + selected + ']' + selected + '[/' + tag+']';
			sel.moveStart('character', selected2.text.length + ins.length - selected.length);
		}
		//MOZILLA/NETSCAPE/SAFARI support
		else if (field.selectionStart || field.selectionStart == 0)
		{
			var startPos = field.selectionStart;
			var endPos = field.selectionEnd;
			field.focus();
			field.value = field.value.substring(0, startPos)
			+ '[' + tag + field.value.substring(startPos, endPos) + ']'+ field.value.substring(startPos, endPos) +'[/' + tag.substring(0, tag.length-1)+']'
			+ field.value.substring(endPos, field.value.length);
			field.setSelectionRange(endPos+tag.length+1, endPos+tag.length);
		}
	} else if (tag == 'color' || tag == 'size')
	{
		if (document.selection)
		{
			field.focus();
			var selected = document.selection.createRange().text;
			var ins = '[' + tag + ']' + selected + '[/' + tag +']';
			var selected2 = document.selection.createRange();
			var sel = document.selection.createRange();
			selected2.moveStart ('character', -field.value.length);
			sel.text = '[' + tag + selected + ']' + selected + '[/' + tag+']';
			sel.moveStart('character', selected2.text.length + ins.length - selected.length);
		}
		//MOZILLA/NETSCAPE/SAFARI support
		else if (field.selectionStart || field.selectionStart == 0)
		{
			var startPos = field.selectionStart;
			var endPos = field.selectionEnd;
			field.focus();
			field.value = field.value.substring(0, startPos)
			+ '[' + tag + '=]'+ field.value.substring(startPos, endPos) +'[/' + tag+']'
			+ field.value.substring(endPos, field.value.length);
			field.setSelectionRange(endPos+tag.length+1, endPos+tag.length);
		}
	}
}