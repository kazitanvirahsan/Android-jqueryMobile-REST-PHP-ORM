 $(document).ready(function(){
	 
	 /*
	 $('#pageone ul').append(
			    $('<li>').append(
			        $('<a>').attr('href','/user/messages').append(
			            $('<span>').attr('class', 'tab').append("Message center")
			)));
	 */
	 //$('.ui-content ul').append("<li class='ui-li ui-li-static ui-btn-up-c ui-first-child ui-lastchild'>kazxi</li>");
	 
	// alert($('#pageone').html()); 
	 $.ajax({
		 type : 'GET' , 
		 url: "http://XXXXX/articles",
		 dataType: 'json',
		 success : function(data) {
			 var html = '';
			 $.each( data, function( key, val ) {
				 html += "<li><a class='ui-link-inherit' href='#'>" + val.article_title + '</a></li>';  
			 });
			 
			 $('.ui-content ul').html(html);
			 $('.ui-content ul').listview('refresh')

		 }
		 });
	 

  });

	 
