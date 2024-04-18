const searchUser = document.querySelector("#admin-search-bar")


if(searchUser) {
    searchUser.addEventListener('input',async function (){
        let response = await fetch('../api/get_users.php?search=' + this.value)
        let users = await response.json()

        const section = document.querySelector("#users-admin")
        section.innerHTML = ''
    
        console.log(section)

        for(const user of users) {
            drawUserInfo(section,user)
        }

    })
}

function drawUserInfo(section,user) {
    const article = document.createElement("article")
    article.classList.add("element","item")

    const userImg = document.createElement("img")
    userImg.classList.add("profile-img")
    userImg.src = user.image_path
    userImg.alt = "User Image"

    const userInfo = document.createElement("div")
    userInfo.className = "user-info"

    const userTags = document.createElement("div")
    userTags.className = "user-tags"
    console.log(user.username)
    createParagraph(user.username,userTags,"username")
    if(user.admin_flag) {
        adminIcon = document.createElement("img")
        adminIcon.src =  "../assets/img/star.svg"
        adminIcon.alt = "Admin Tag"
        userTags.appendChild(adminIcon)
    }
    if(user.banned) {
        bannedIcon = document.createElement("img")
        bannedIcon.src = "../assets/img/banned.svg"
        bannedIcon.alt = "Banned Tag"
        userTags.appendChild(bannedIcon)
    }

    userInfo.appendChild(userTags)

    createParagraph(user.email,userInfo,"email")
    createParagraph(user.phonenumber,userInfo,"phonenumber")
    createParagraph(user.address,userInfo,"address")

    article.appendChild(userImg)
    article.appendChild(userInfo)

    drawButtons(article,user)

    section.appendChild(article)
}

function drawButtons(article,user) {
    buttonDiv = document.createElement("div")
    buttonDiv.className = "buttons"
    if(!user.banned && !user.admin_flag ) {
        makeAdminAction = "../actions/action_make_user_admin.php"
        banAction = "../actions/action_ban_user.php"
        createButton(makeAdminAction,"username",user.username,"Make Admin",buttonDiv)
        createButton(banAction,"username",user.username,"Ban",buttonDiv)
    } else if(user.banned && !user.admin_flag) {
        unbanUser = "../actions/action_unban_user.php"
        createButton(unbanUser,"username",user.username,"Unban",buttonDiv)
    }

    article.appendChild(buttonDiv)
}


function createButton(action,name,value,text,parent) {
    form = document.createElement("form")
    form.action = action
    form.method = "post"

    button = document.createElement("button")
    button.type = "submit"
    button.name = name
    button.value = value
    button.textContent = text

    form.appendChild(button)
    parent.appendChild(form)
}