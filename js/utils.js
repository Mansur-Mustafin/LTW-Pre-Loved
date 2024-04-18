function createParagraph(element,parent,className) {
    paragraph = document.createElement("p")
    paragraph.textContent = element
    paragraph.className = className
    parent.appendChild(paragraph)
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

function createForm(action,name,parent) {
    form = document.createElement("form")
    form.action = action
    form.method = "post"

    input = document.createElement("input")
    input.type = "submit"
    input.name = name

    form.appendChild(input)
    parent.appendChild(form)
}