//create a new message
function create(text, nature){
    let message="";
    if (nature === "RECEIVER"){
         message = "<div class=\"row message-body\"> <div class=\"col-sm-12 message-main-receiver\"> <div class=\"receiver\"> <div class=\"message-text\">"+text+"</div> </div></div> </div>"
    }
    else{
         message = "<div class=\"row message-body\"> <div class=\"col-sm-12 message-main-sender\"> <div class=\"sender\"> <div class=\"message-text\">"+text+"</div> </div></div> </div>"
    }
    return message;
}

//insert the enw message
function insert(text){
    const conversation = document.getElementById("conversation");
    conversation.innerHTML += text;

}

// ajax code to handle message sent request
    let x = document.getElementById("reply");
    let button = document.getElementById("my-button")
    button.onclick = (()=>{
        $.ajax({
            url: `/chat/${window.chat.id}/publish/`,
            type: 'POST',
            data:{
              value: x.value,
                sender: window.chat.author
            },
            success: function(response) {
                console.log(response);
            },
            error: function(error) {
                console.log(error);
            }
        })
        x.value="";
    });


