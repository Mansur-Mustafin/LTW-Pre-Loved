const entitiesSection = document.querySelector("#entities-admin")
const addButton = document.querySelector("#add-tag")
const type = document.querySelector("#type")
if(type)
    type.style.display = "none"

if(entitiesSection && addButton) {
    addButton.addEventListener("click",  function () {
        const button = document.createElement("article")
        button.id = "add-entity-article"
        addButton.style.display = "none";


        createForm("../actions/action_add_entity.php","new_entity",type.textContent,button)
        entitiesSection.prepend(button)
    })
}

function createForm(action,name,value,parent) {
    const form = document.createElement("form")
    form.id = "add-entity-form"
    form.action = action
    form.method = "post"

    const input = document.createElement("input")
    input.name = name

    const button = document.createElement("button")
    button.type = "submit"
    button.name = "type"
    button.value = value // TYPE
    button.textContent = "Add"

    const csrfToken = document.createElement("input")
    csrfToken.type = "hidden"
    csrfToken.name = "csrf_token"
    csrfToken.value = document.body.dataset?.csrfToken;

    form.appendChild(input)
    form.appendChild(csrfToken)
    form.appendChild(button)
    parent.appendChild(form)
}
