const entitiesSection = document.querySelector("#entities-admin")
const addButton = document.querySelector("#add-tag")

if(entitiesSection && addButton) {
    addButton.addEventListener("click",  function () {
        const button = document.createElement("article")
        button.classList.add("element","entity","item")
        addButton.style.display = "none";


        createForm("#","new_entity",button)
        entitiesSection.prepend(button)
    })
}

function createForm(action,name,parent) {
    const form = document.createElement("form")
    form.id = "add-entity-form"
    form.action = action
    form.method = "post"

    const input = document.createElement("input")
    input.name = name

    const button = document.createElement("button")
    button.type = "submit"
    button.textContent = "Add"

    form.appendChild(input)
    form.appendChild(button)
    parent.appendChild(form)
}