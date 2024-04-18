const searchItem = document.querySelector("#admin-search-bar");
if(searchItem) {
    searchItem.addEventListener('input',async function() {
        const response = await fetch('../api/get_items.php?search=' + this.value)
        const items = await response.json()

        const section = document.querySelector("#items-admin")
        section.innerHTML = ''

         for(const item of items) {
            const mainImage = JSON.parse(item.images)[0]
            const jointTags = item.tags.join(', ')
            const article = document.createElement('article')
            article.classList.add("element","product","item")
            
            const img = document.createElement('img')
            img.src = mainImage
            img.alt = "Product Picture"
            const productInfo = document.createElement('div')
            productInfo.classList.add("product-info")

            const productId = document.createElement("span")
            productId.classList.add("product-id")
            productId.textContent = item.id

            const anchor = document.createElement("a")
            anchor.classList.add("product-title")
            anchor.textContent = item.title

            const mySpan = document.createElement("span")
            mySpan.classList.add("product-id")
            mySpan.textContent = item.id

            anchor.appendChild(mySpan)

            productInfo.appendChild(anchor)

            createParagraph(item.brand,productInfo,"")
            createParagraph(item.price,productInfo,"product-price")
            createParagraph(item.created_at,productInfo,"")
            createParagraph(item.condition,productInfo,"")
            createParagraph(item.model,productInfo,"")
            createParagraph(item.category,productInfo,"")
            createParagraph(item.size,productInfo,"")
            createParagraph(jointTags,productInfo,"")
            createParagraph(item.description,productInfo,"")
            
            buttonsDiv = document.createElement("div")
            buttonsDiv.className = "buttons"

            /* DOES NOT WORK PROPERLY */

            form = document.createElement("form")
            form.action = "../actions/action_delete_product.php"
            form.method = "post"

            button = document.createElement("button")
            button.type = "submit"
            button.name = "product-id"
            button.value = item.id
            button.textContent = "Remove"


            form.appendChild(button)
            buttonsDiv.appendChild(form)

            article.appendChild(img)
            article.appendChild(productInfo)
            article.appendChild(buttonsDiv)
            section.appendChild(article)


        }
    })
}

function createParagraph(element,parent,className) {
    paragraph = document.createElement("p")
    paragraph.textContent = element
    paragraph.className = className
    parent.appendChild(paragraph)
}