
var newsId = {};
$(".news_count").each(function(i){
	newsId[i] = $(this).attr("news-id");
});


var url = "/index.php?c=index&a=getCount";
$.post(url,newsId,function(result){
	if(result.status == 1){
		var counts = result.data;
		$.each(counts,function(news_id,count){
			$(".node-"+news_id).html(count);
		});
	}
},'JSON');