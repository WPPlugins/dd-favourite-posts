function moveitem(l1,l2)
{
	var opt = document.createElement("option");
	var mysrc=document.getElementById(l1);
	var mytarget=document.getElementById(l2);
	var n=mysrc.selectedIndex;
	if(n>=0)
	{
		if(!itemexistintarget(mysrc.options[n].value,l2))
		{
		mytarget.options.add(opt);
		opt.text=mysrc.options[n].text;
		opt.value=mysrc.options[n].value;
		mysrc.remove(n);
		}
	}
	if(l2=='lista')sortitems(l2);
}

function itemexistintarget(n,l2)
{
	
	var flag=false;
	var mytarget=document.getElementById(l2);
	for (i = mytarget.length - 1; i>=0; i--) {
		if(mytarget.options[i].value==n)
		{
			flag=true;
		}
	}
	return flag;
}

function sortitems(l1) {
				var selElem=document.getElementById(l1);
                var tmpAry = new Array();
                for (var i=0;i<selElem.options.length;i++) {
                        tmpAry[i] = new Array();
                        tmpAry[i][0] = selElem.options[i].value;
                        tmpAry[i][1] = selElem.options[i].text;
                }
                tmpAry.sort();
                tmpAry.reverse();
                while (selElem.options.length > 0) {
                    selElem.options[0] = null;
                }
                for (var i=0;i<tmpAry.length;i++) {
                        var op = new Option(tmpAry[i][1], tmpAry[i][0]);
                        selElem.options[i] = op;
                }
                return;
}

function getitems(l2)
{
	var str='';
	var mytarget=document.getElementById(l2);
	for(i=0;i<mytarget.length;i++)str=str+','+mytarget.options[i].value;
	return '0'+str;
}

function changeitem(l2,dir)
{
	var temp_text,temp_value;
	var mytarget=document.getElementById(l2);
	var n=mytarget.selectedIndex;
	for (i = mytarget.length - 1; i>=0; i--) {
		if(i==n)
		{
			if(dir==1)
			{
				if(n>0)
				{
					temp_value=mytarget.options[n].value;
					temp_text=mytarget.options[n].text;
					mytarget.options[n].value=mytarget.options[n-1].value;
					mytarget.options[n].text=mytarget.options[n-1].text;
					mytarget.options[n-1].value=temp_value;
					mytarget.options[n-1].text=temp_text;
					mytarget.selectedIndex=n-1;					
				}
			}
			if(dir==2)
			{
				if(n<mytarget.length-1)
				{
					temp_value=mytarget.options[n].value;
					temp_text=mytarget.options[n].text;
					mytarget.options[n].value=mytarget.options[n+1].value;
					mytarget.options[n].text=mytarget.options[n+1].text;
					mytarget.options[n+1].value=temp_value;
					mytarget.options[n+1].text=temp_text;
					mytarget.selectedIndex=n+1;					
				}
			}			
		}
	}
	return '0'+str;
}

function searchitems(l1,l2)
{
	var mytarget=document.getElementById(l2);
	var mytext=document.getElementById(l1).value;	
	for(i=mytarget.length-1;i>=0;i--)
	{
		var mystring=new String(mytarget.options[i].text);
		mystring=mystring.toLowerCase();
		if(mystring.indexOf(mytext.toLowerCase())>=0)
		{
			mytarget.selectedIndex=i;					
		}
	}
}

