$(function() {
	$(".btn-small").click(function(){
		var user_id = $(this).attr("id");
			$.ajax({
                type: "POST",
                url: "/ggxxsns/resourcerest/create.json",
                dataType : 'json',
                data: "key=" +user_id,
                success: function( res )
                {
                    //alert(res.key);
					$("#"+res.key).attr('disabled','disabled');
					if(res.iine_id == ""){
						$("#user_name_iine_"+res.key).append('<img src="/ggxxsns/assets/img/bigsmile.gif"> ');				
					}
					$("#user_name_iine_"+res.key).append(res.name+"　");
                }
             });
	});
});



