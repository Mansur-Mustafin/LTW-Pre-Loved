const searchItem = document.querySelector("#item-admin-search");
if(searchItem) {
    searchItem.addEventListener('input',async function() {
        const response = await fetch('../api/items.php?search=' + this.value)
        const items = await response.json()

        const  itemsAdminSection = document.querySelector("#items-admin")
        if(itemsAdminSection) itemsAdminSection.innerHTML = ''

        items.forEach(item => {
        const mainImage = JSON.parse(item.images)[0];
        const joinedTags = item.tags.join(", ");

        const itemArticle = document.createElement('article');
        itemArticle.className = 'element product item';

        const itemImage = document.createElement('img');
        itemImage.src = mainImage;
        itemImage.alt = 'product-picture';
        itemArticle.appendChild(itemImage);

        const itemLink = document.createElement('a');
        itemLink.href = `../pages/item.php?item_id=${item.id}`;
        const itemTitle = document.createElement('h3');
        itemTitle.textContent = item.title;
        const itemIdSpan = document.createElement('span');
        itemIdSpan.className = 'product-id';
        itemIdSpan.textContent = item.id;
        itemTitle.appendChild(itemIdSpan);
        itemLink.appendChild(itemTitle);
        itemArticle.appendChild(itemLink);

        const productInfo = document.createElement('div');
        productInfo.className = 'product-info';
        productInfo.innerHTML = `
            <p>Brand: ${item.brand}</p>
            <p>Price: <span class="product-price">${item.price}</span></p>
            <p>Tradable: ${item.tradable? "Tradable" : "not Tradable"}</p>
            <p>Created-at: ${new Date(item.created_at).toLocaleString()}</p>
            <p>Condition: ${item.condition}</p>
            <p>Model: ${item.model}</p>
            <p>Category: ${item.category}</p>
            <p>Size: ${item.size}</p>
            <p>Tags: ${joinedTags}</p>
            <p>Description: ${item.description}</p>
        `;
        itemArticle.appendChild(productInfo);

        const topRightElement = document.createElement('div');
        topRightElement.className = "top-right-element"
        itemArticle.appendChild(topRightElement);

        const deleteForm = document.createElement('form');
        deleteForm.action = '../actions/action_delete_product.php';
        deleteForm.method = 'post';
        const deleteButton = document.createElement('button');
        deleteButton.type = 'submit';
        deleteButton.name = 'product_id';
        deleteButton.value = item.id;
        deleteButton.textContent = 'Remove';
        deleteForm.appendChild(deleteButton);
        itemArticle.appendChild(deleteForm);

        itemsAdminSection.appendChild(itemArticle);
    })
})
}
