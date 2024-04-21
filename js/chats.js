var last_message_id = document.getElementById("last_message_id").value
var user_id = document.getElementById("current_user_id").value
setInterval(checkNewMessages, 2000)

function checkNewMessages() {
    fetch("../actions/action_check_new_messages.php", {
        method: 'post',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(
            {last_message_id:last_message_id, user_id: user_id}
        ),
    })
        .then(r =>  r.json().then(data => ({status: r.status, body: data})))
        .then(obj => {
            if(obj.status == 200){
                console.log(obj.body);
                if(obj.body.length > 0)
                    window.location.reload()
            }
        })
}
