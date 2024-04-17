var form = document.getElementById('message_form')
var messages = document.getElementById('messages')
var message_template = document.getElementById('message_template')
var message_template_partner = document.getElementById('message_template_partner')
var chat_id_field = document.getElementById('chat_id_field')
var to_user_id = document.getElementById('to_user_id').value
var last_message_id = form.dataset.last_message_id ?? null
scrollMessagesDown()


form.addEventListener("submit", function (e){
    e.preventDefault()
    var form_data = new FormData(form)
    if(form_data.get("text") == "")
        return
    fetch("../actions/action_send_message.php", {
        method: 'post',
        body: form_data,
    })
        .then(r =>  r.json().then(data => ({status: r.status, body: data})))
        .then(obj => {
            if(obj.status == 200){
                if(last_message_id == null)
                    refreshPageIfNewChatId(obj.body.chat_id)
                addNewMyMessage(form_data, obj.body.id)
                if(chat_id_field.value == "0"){
                    chat_id_field.value = obj.body.chat_id
                    form.dataset.chat_id = obj.body.chat_id
                }
                clearMessageField()
                scrollMessagesDown()
            }
        })
})
checkNewMessages()
setInterval(checkNewMessages, 2000)

function checkNewMessages() {
    var chat_id = form.dataset.chat_id;
    fetch("../actions/action_check_new_messages_in_chat.php", {
        method: 'post',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(
            {last_message_id:last_message_id, chat_id:chat_id, to_user_id:to_user_id}
        ),
    })
        .then(r =>  r.json().then(data => ({status: r.status, body: data})))
        .then(obj => {
            if(obj.status == 200){
                if(obj.body.length > 0)
                    addNewPartnerMessage(obj.body)
            }
        })
}

function addNewMyMessage(data, id){
    last_message_id = id
    var text = data.get("text")
    var new_message = message_template.cloneNode(true)
    new_message.removeAttribute("id")
    new_message.querySelector(".message_text").innerText = text
    new_message.querySelector(".message_time").innerText = Math.floor(Date.now() / 1000);
    messages.appendChild(new_message)
    scrollMessagesDown()
}

function addNewPartnerMessage(data){
    last_message_id = data[data.length-1].id
    for(var i = 0; i < data.length; i++){
        var text = data[i].text
        var date_time = data[i].date_time
        var new_message = message_template_partner.cloneNode(true)
        new_message.removeAttribute("id")
        new_message.querySelector(".text").innerText = text
        new_message.querySelector(".message_time").innerText = date_time
        messages.appendChild(new_message)
    }
    scrollMessagesDown()
}

function refreshPageIfNewChatId(id){
    let url = window.location.href;
    if (url.indexOf('?') > -1){
        url += '&chat_id='+id
        window.location.href = url;
    }

}

function scrollMessagesDown(){
    messages.scrollTo(0, messages.scrollHeight);

}

function clearMessageField(){
    form.querySelector("#message_field").value = ""
}
