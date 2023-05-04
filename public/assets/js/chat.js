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

function add(text, nature){
    let message= create(text, nature);
    insert(message);
}
//code for textarea when clicking enter:
//send to ajax requests:
//request1 send a request to the chat publisher that pushes a new update to the mercure HUB
//request2 sends a request the Message controller that instantiates a new message entity and saves it to the database.
let x = document.getElementById("reply");
function submitOnEnter(event) {
    let request1=null;
    let request2=null;
    if (event.keyCode === 13 && !event.shiftKey) { // 13 is the "Enter" key code
        event.preventDefault(); // prevent default behavior of creating a new line
        request1= $.ajax({
            url: `/publish`,
            type: 'POST',
            data:{
                value: x.value,
                sender: window.chat.author,
                convo_id: window.chat.id
            }
        })
        request2= $.ajax({
            url: `/newMessage`,
            type: 'POST',
            data:{
                value: x.value,
                sender: window.chat.author,
                conversation_id: window.chat.id
            }
        })
        x.value="";
    }

    Promise.all([request2, request1]).then(r =>console.log("success") )
}


