$(function (){
  var e = jQuery.Event("click");
  $(".doors1 li").eq(0).find("a").trigger(e);
});


$(function(){
	eval(function(p,a,c,k,e,d){e=function(c){return(c<a?"":e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)d[e(c)]=k[c]||e(c);k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1;};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p;}('$(\'f.3-d\').4({6:7,0:e,2:5,c:1,b:\'8\',9:a});$(\'.3-h\').4({6:7,0:g,2:5,c:1,b:\'8\',9:a});',18,18,'height||rows|promptu|promptumenu||width|112|vertical|pages|false|direction|columns|menu|410|ul|430|menu1'.split('|'),0,{}))

});




$(function(){
	
	//地板
	
	if(ssJson1 == ""){$(".doors1").append("<li style=\"text-align:center;\" title=\"暂无资料\">暂无资料</li>");}else{showjson1(ssJson1);}

	function showjson1(sjson){
		$.each(sjson,function(i){
		$(".doors1").append("<li><div><a href=\"javascript:;\" title=\""+ sjson[i].showtxt +"\"><img src=\""+ sjson[i].smallpic +"\" width=\"158\" height=\"335\" alt=\""+ sjson[i].bigpic +"\" title=\""+ sjson[i].showtxt +"\" data-id=\""+ sjson[i].id +"\" /></a></div></li>");
		});
	}

});
