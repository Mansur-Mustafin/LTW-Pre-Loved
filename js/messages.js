const csrfToken = document.body.dataset?.csrfToken

let form = document.getElementById('message_form')
let messages = document.getElementById('messages')
let message_template = document.getElementById('message_template')
let message_template_partner = document.getElementById('message_template_partner')
let chat_id_field = document.getElementById('chat_id_field')
let to_user_id = document.getElementById('to_user_id').value
let last_message_id = form.dataset.last_message_id ?? null
let offer_exchange_icon = document.getElementById("offer_exchange")
let offer_exchange_field = document.getElementById("offer_exchange_field")
let attach_file_icon = document.getElementById("attach_file")
let attach_file_field = document.getElementById("attach_file_field")
let message_field = document.getElementById('message_field');

setTimeout(scrollMessagesDown, 30)
checkNewMessages()
setInterval(checkNewMessages, 2000)

form.addEventListener("submit", function (e){
    e.preventDefault()

    const csrfToken = document.createElement("input")
    csrfToken.type = "hidden"
    csrfToken.name = "csrf_token"
    csrfToken.value = document.body.dataset?.csrfToken;

    form.appendChild(csrfToken)

    sendMessage()
})


function sendMessage(isOfferExchange = null){
    let form_data = new FormData(form)
    if(attach_file_field.value != ""){
        form.submit()
        return
    }
    if(form_data.get("text") == "" && !isOfferExchange)
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
}

function checkNewMessages() {
    let chat_id = form.dataset.chat_id;
    fetch("../actions/action_check_new_messages_in_chat.php", {
        method: 'post',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(
            {
                last_message_id:last_message_id,
                chat_id:chat_id,
                to_user_id: to_user_id,
                csrfToken
            }
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
    let text = data.get("text")
    let new_message = message_template.cloneNode(true)
    new_message.removeAttribute("id")
    new_message.querySelector(".text").innerText = text
    new_message.querySelector(".message_time").innerText = 'Just now' // Math.floor(Date.now() / 1000) TODO, mas tipo, pode se deixar assim
    messages.appendChild(new_message)
    scrollMessagesDown()
}

function addNewPartnerMessage(data){
    last_message_id = data[data.length-1].id
    for(let i = 0; i < data.length; i++){
        if(data[i].item_id_exchange != 0 || data[i].files != "") {
            window.location.reload()
        }
        let text = data[i].text
        let date_time = data[i].date_time
        let new_message = message_template_partner.cloneNode(true)
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

offer_exchange_icon.addEventListener("click", function (e){
    offer_exchange_field.value = 1;
    sendMessage(true)
    window.location.reload()
})

attach_file_icon.addEventListener("click", function(){
    attach_file_field.click();
})

attach_file_field.onchange = function (e) {
    let file = e.target.files[0];
    let name_block = document.getElementById("attached_file_name")
    name_block.innerText = file.name
    name_block.style.display = "block";
};

message_field.addEventListener('input', function() {
    this.style.height = 'auto'; 
    this.style.height = (this.scrollHeight > 300 ? 300 : this.scrollHeight) + 'px';
});

message_field.addEventListener('keydown', function(event) {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault();
        sendMessage();
    }
});

function scrollMessagesDown(){
    messages.scrollTo(0, messages.scrollHeight);
}

function clearMessageField(){
    form.querySelector("#message_field").value = ""
}
