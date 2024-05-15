const searchUser = document.querySelector("#user-admin-search")


if(searchUser) {
    searchUser.addEventListener('input',async function (){
        let response = await fetch('../api/users.php?search=' + this.value)
        let users = await response.json()

        const section = document.querySelector("#users-admin")
        if(section) section.innerHTML = ''
    
        drawUserInfo(users)

    })
}

function drawUserInfo(users) {
    const usersAdminSection = document.getElementById('users-admin');
    users.forEach(user => {
        const userArticle = document.createElement('article');
        userArticle.className = 'item';

        const profileImg = document.createElement('img');
        profileImg.className = 'profile-img';
        profileImg.src = user.image_path;
        profileImg.alt = 'User Image';
        userArticle.appendChild(profileImg);

        const username = document.createElement('h3');
        username.textContent = user.username;
        userArticle.appendChild(username);

        const detailsDiv = document.createElement('div');
        if(user.admin_flag || user.banned) {
            const tagsList = document.createElement('ul');
            tagsList.className = "tags"
            if (user.admin_flag) {
                const adminTag = document.createElement('li');
                adminTag.className = 'red-tag';
                adminTag.textContent = 'Admin';
                tagsList.appendChild(adminTag);
            }
            if (user.banned) {
                const bannedTag = document.createElement('li');
                bannedTag.className = 'red-tag';
                bannedTag.textContent = 'Banned';
                tagsList.appendChild(bannedTag);
            }
            detailsDiv.appendChild(tagsList);
        }

        const detailsList = document.createElement('ul');
        detailsList.innerHTML = `
            <li><label>Email: ${user.email}</label></li>
            <li><label>Phonenumber: ${user.phonenumber}</label></li>
            <li><label>Address: ${user.address}</label></li>
        `;
        detailsDiv.appendChild(detailsList);
        userArticle.appendChild(detailsDiv);

        const topRightElement = document.createElement('div');
        topRightElement.className = 'top-right-element';
        const youLabel = document.createElement('h4');
        youLabel.textContent = '';
        topRightElement.appendChild(youLabel);
        
        userArticle.appendChild(topRightElement);

        const form = document.createElement('form');
        if (!user.banned &&!user.admin_flag) {
            const promoteButton = document.createElement('button');
            promoteButton.type = 'submit';
            promoteButton.name = 'username';
            promoteButton.value = user.username;
            promoteButton.formaction = '../actions/action_make_user_admin.php';
            promoteButton.formmethod = 'post';
            promoteButton.textContent = 'Promote';
            form.appendChild(promoteButton);

            const banButton = document.createElement('button');
            banButton.type = 'submit';
            banButton.name = 'username';
            banButton.value = user.username;
            banButton.formaction = '../actions/action_ban_user.php';
            banButton.formmethod = 'post';
            banButton.textContent = 'Ban';
            form.appendChild(banButton);
        } else if (user.banned &&!user.admin_flag) {
            const unbanButton = document.createElement('button');
            unbanButton.type = 'submit';
            unbanButton.name = 'username';
            unbanButton.value = user.username;
            unbanButton.formaction = '../actions/action_unban_user.php';
            unbanButton.formmethod = 'post';
            unbanButton.textContent = 'Unban';
            form.appendChild(unbanButton);
        }
        userArticle.appendChild(form);

        usersAdminSection.appendChild(userArticle);
    });
}