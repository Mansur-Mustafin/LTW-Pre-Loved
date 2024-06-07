const itemTags = document.getElementById('item-tag-wrapper');
let currentTags = []

if(itemTags) {
    itemTags.innerHTML = ''

    const itemTagsSelect = document.createElement('select')
    itemTagsSelect.classList.add('hidden')
    itemTagsSelect.id = "item-tags"
    itemTagsSelect.name = "item-tags[]"
    itemTags.appendChild(itemTagsSelect)

    tagsUL = document.createElement('ul')
    tagsUL.classList.add("ul-tags")
    itemTags.appendChild(tagsUL)

    console.log(tagsUL)

    label = document.createElement("label")
    label.textContent = "Tags"
    itemTags.appendChild(label)
    tagsInput = document.createElement('input')
    tagsInput.type = "text"
    tagsInput.id = "item-current-tag"
    tagsInput.name = 'tag'
    itemTags.appendChild(tagsInput)

    dropDown = document.createElement('ul')
    dropDown.classList.add("dropdown-tags")
    itemTags.appendChild(dropDown)


    tagsInput.addEventListener('input', async function () {
        let input = this.value
        await displayAutoComplete(dropDown, tagsUL, input, itemTagsSelect, itemTags)
    })
}

async function displayAutoComplete(dropDown,tagsUL,input,itemTagsSelect,parent) {
    dropDown.innerHTML = ''
    let response = await fetch('../api/entities.php?search=Tags')
    let tags = await response.json()
    let matchingTags = tags.filter((tag) => tag.name.toLowerCase().startsWith(input.toLowerCase() ?? '') && !currentTags.includes(tag.name)).map((tag) => tag.name)
    matchingTags.forEach(element => {

        const item = document.createElement('li')
        item.textContent = element
        item.addEventListener('click', () => {
            tagsInput.innerHTML = ''
            dropDown.innerHTML = ''
            const option = document.createElement('option')
            option.text = element
            itemTagsSelect.add(option)
            currentTags.push(element)
            renderTags(tagsUL,currentTags,itemTagsSelect)
        })
        dropDown.appendChild(item)
    });
}

function removeTag(tagsUL,tag,currentTags,itemTagsSelect) {
    const index = currentTags.indexOf(tag)
    currentTags.splice(index,1)
    const option = document.createElement('option')
    option.text = tag
    itemTagsSelect.remove(option)
    renderTags(tagsUL,currentTags,itemTagsSelect)
}

function renderTags(tagsUL,currentTags,itemTagsSelect) {
    tagsUL.innerHTML = ''
    currentTags.forEach( (tag) => {
        const item = document.createElement('li')
        const itemText = document.createElement('p')
        itemText.textContent = tag
        item.appendChild(itemText)
        const deleteButton = document.createElement('button')
        deleteButton.textContent = "X"
        deleteButton.addEventListener('click', () => {
            console.log(itemTagsSelect.childNodes)
            removeTag(tagsUL,tag,currentTags,itemTagsSelect)
        })
        item.appendChild(deleteButton)
        tagsUL.appendChild(item)
    })
}