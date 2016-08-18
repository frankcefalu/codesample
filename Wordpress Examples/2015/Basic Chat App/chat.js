var inprogress = false; 



$( document ).ready(function() {

$(" #btnchat " ).click(function(){
          submititem();
});



$(" #btn-input " ).keypress(function(e){
   if (e.keyCode == 13) {
        submititem();
    }
});



$(" .removemessage " ).click(function(){

  id = $(this).attr("id");
  $('message-'+id).remove();
  $.post("/submitmessage",{state:'delete', hash: nhash, mid:id});


});

});


function fetchMessages() {

        if(inprogress == false){
        fetchit();
        // Call the timeout at the end of the AJAX response
        // This prevents your race conditionasd
	}


        setTimeout(function(){
            fetchMessages();
        }, 1200);

  }


fetchMessages();


function append(data){

    if(data['admin'] == "true"){


    }

    message = '<li class="left clearfix messagechat message-'+data['mid']+'"><span class="chat-img pull-left">'+data['avatar']+'</span>';
    message += '<div class="chat-body clearfix"><div class="header"><strong class="primary-font">'+data['username']+'</strong>';
    message += '<small class="pull-right text-muted"><a href="#" class="removemessage" id="'+data['mid']+'"><span class="glyphicon glyphicon-remove"></span></a>';
    message += '<span class="glyphicon glyphicon-time"></span>'+data['date']+' ago</small></div>'
    message += '<p>'+data['message']+'</p></div></li>';

    $("#chatbody").append(message);
    $('.chatpanel').scrollTop($('#chatbody').height());

}

function htmlencode(str) {
    return str.replace(/[&<>"']/g, function($0) {
        return "&" + {"&":"amp", "<":"lt", ">":"gt", '"':"quot", "'":"#39"}[$0] + ";";
    });
}


function fetchit(){

  if(inprogress == false){

  inprogress = true; 
  

  if($.post("/submitmessage",{state:'fetch', hash: nhash},function (data) {
        if(lastmid == 0){
          lastmid = data[0]["mid"];
          data.reverse();
          for(var i = 0; i < data.length; i++){
              append(data[i]);
          }
        }else if(lastmid != data[0]["mid"]){
          lastmid = data[0]["mid"];
          append(data[0]);
        }
      },'json')){ inprogress = false;  }
   }
}


function submititem(){

$(" #btnchat " ).disabled = true;
  nmessage = htmlencode($("#btn-input").val());
  $("#btn-input").val("SENDING PLEASE WAIT...");
  $.post("/submitmessage",{state:'post', hash: nhash, message:nmessage});


  $("#btn-input").val("");
  $(" #btnchat " ).disabled = false;



}
