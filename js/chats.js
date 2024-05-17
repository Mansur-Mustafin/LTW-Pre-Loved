let last_message_id = document.getElementById("last_message_id").value
let user_id = document.getElementById("current_user_id").value
let current_item_id = document.getElementById("current_item_id").value

setInterval(checkNewMessages, 1000)

console.log(current_item_id)

function checkNewMessages() {
    fetch("../actions/action_check_new_messages.php", {
        method: 'post',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(
            {last_message_id:last_message_id, user_id: user_id, item_id: current_item_id}
        ),
    })
        .then(r =>  r.json().then(data => ({status: r.status, body: data})))
        .then(obj => {
            if(obj.status == 200){
                if(obj.body.length > 0)
                    window.location.reload()
            }
        })
}
