const filterItems = document.getElementById("filter")
const itemsSection = document.getElementById("items")

const searchBarItem = document.getElementById("item-search")
const itemList = document.getElementById("item-list") 

const title = document.getElementById("title")?.textContent

const sessionId = document.getElementById("session_id")?.textContent

const slider = document.getElementById("slider")

if(slider) {
    let slider =document.getElementById('slider')
    slider.addEventListener('change', async function() {
        document.getElementById('item-list').innerHTML = ''
        const response = await fetch('../api/items.php?search=')
        const items = await response.json()
        let filteredItems = items.filter((item) => {
            return item.price < this.value && item.user_id != sessionId
        })
        const sessionValue = sessionId.textContent == '' ? {isLoggedIn:false} : {isLoggedIn:true,id:sessionId};
        filteredItems.forEach((filteredItem) => {

            drawItem(filteredItem,sessionValue,'Find what you want to buy!',false,false,itemList)
        })
        let price = document.getElementById('current-max-price')
        price.textContent = this.value
    })
}



if(filterItems) {
   filterItems.addEventListener("submit",async function(e) {
        e.preventDefault();
        let inWishList = false
        let inCart = false
        let wishList = []
        let cart = []
        const responseWishList = await fetch("../api/wishlist.php")
        wishList = await responseWishList.json()
        const responseCart = await fetch('../api/shopping_cart.php')
        cart = await responseCart.json()

        const formData = new FormData(e.target)
       let output = Object.entries(Object.fromEntries(formData))
       let filterMap = (output
           .map(([k, v]) => k)
           .map((v) => {
                   return {key: v.split("/")[0], value: v.split("/")[1]}
               }
           ))
        // TODO : REFACTOR (THIS IS ALMOST A CRIME)
       let categories = filterMap.filter((e) => e.key == "category").map((e) => e.value);
       let brands = filterMap.filter((e) => e.key == "brand").map((e) => e.value);
       let sizes = filterMap.filter((e) => e.key == "size").map((e) => e.value);
       let conditions = filterMap.filter((e) => e.key == "condition").map((e) => e.value);

        const response = await fetch('../api/items.php?search=')
        const items = await response.json()
       let filteredItems = (items.filter((item) => {
           const checkCategory = categories.length === 0 || categories.includes(item.category)
           const checkBrand = brands.length === 0 || brands.includes(item.brand)
           const checkSize = sizes.length === 0 || sizes.includes(item.size)
           const checkConditions = conditions.length === 0 || conditions.includes(item.condition)
           return checkCategory && checkBrand && checkSize && checkConditions && item.user_id !== sessionId
       }))

        itemList.innerHTML = ''
        filteredItems.forEach((e) => e.images = (e.images.slice(2,e.images.length - 2).split(",")))

        const sessionValue = sessionId.textContent === '' ? {isLoggedIn:false} : {isLoggedIn:true,id:sessionId};

        filteredItems.forEach((filteredItem) => {
            drawItem(filteredItem,sessionValue,'Find what you want to buy!',inCart,inWishList,itemList)
        })
        updateButton();
    })

}

if(searchBarItem) {
    searchBarItem.addEventListener('input',async function() {
        const response = await fetch('../api/items.php?search=' + this.value)
        let items = await response.json()
        const session = await getSession()
        let inWishList = false
        let inCart = false
        let wishList = []
        let cart = []
        let userItems = [] 
        if(session.isLoggedIn) {
            const responseWishList = await fetch("../api/wishlist.php")
            wishList = await responseWishList.json()
            const responseCart = await fetch('../api/shopping_cart.php')
            cart = await responseCart.json()
            const responseUserItems = await fetch("../api/user_items.php")
            userItems = await responseUserItems.json()
        }
        
        const userItemsIds = userItems.map((e) => e.id)

        if(title == 'Your Wishlist!') {
            items = items.filter((item) => wishList.includes(item.id))
        } else if(title == 'Time to buy!') {
            items = items.filter((item) => cart.includes(item.id))
        } else if(title == 'Your items to sell') {
            items = items.filter((item) => userItemsIds.includes(item.id))
        }

        itemList.innerHTML = ''
        items.forEach((item) => {
            if(session.isLoggedIn) {
               inWishList = wishList.includes(item.id) 
               inCart = cart.includes(item.id)
            }
            drawItem(item,session,title, inCart,inWishList, itemList)
        })
        updateButton()
    })
}


async function getSession() {
    const r = await fetch('../api/session.php')
    const response = await r.json();
    let session = {};
    session.isLoggedIn = response[2]
    session.id = response[1]
    return session
}

function getTimePassed(createdAt) {
    const now = new Date();
    const eventTime = new Date(createdAt * 1000); // Assuming createdAt is a Unix timestamp
    const interval = now - eventTime;

    const years = Math.floor(interval / (1000 * 60 * 60 * 24 * 365));
    if (years > 0) {
        return `${years} year${years === 1 ? '' : 's'} ago`;
    }

    const months = Math.floor(interval / (1000 * 60 * 60 * 24 * 30));
    if (months > 0) {
        return `${months} month${months === 1 ? '' : 's'} ago`;
    }

    const days = Math.floor(interval / (1000 * 60 * 60 * 24));
    if (days > 0) {
        return `${days} day${days === 1 ? '' : 's'} ago`;
    }

    const hours = Math.floor(interval / (1000 * 60 * 60));
    if (hours > 0) {
        return `${hours} hour${hours === 1 ? '' : 's'} ago`;
    }

    const minutes = Math.floor(interval / (1000 * 60));
    if (minutes > 0) {
        return `${minutes} minute${minutes === 1 ? '' : 's'} ago`;
    }

    return 'Just now';
}


function drawItem(item, session, title, inCart, inWishList,parent) {
    if(typeof item.images == 'string') {
        item.images = item.images.substring(1,item.images.length - 1).split(",").map((i) => i.substring(1,i.length - 1))
    }
    const mainImage = item.images[0];
    const article = document.createElement('article');
    article.className = 'item fly';

    const img = document.createElement('img');
    img.src = mainImage;
    img.alt = 'Item Image';
    article.appendChild(img);

    const link = document.createElement('a');
    link.href = `../pages/item.php?item_id=${item.id}`;
    const h3 = document.createElement('h3');
    h3.textContent = item.title;
    link.appendChild(h3);
    article.appendChild(link);

    const div = document.createElement('div');
    const ul = document.createElement('ul');
    ul.className = 'tags';

    item.tags.forEach(tag => {
        const li = document.createElement('li');
        li.textContent = tag;
        ul.appendChild(li);
    });

    if (item.condition) {
        const li = document.createElement('li');
        li.textContent = item.condition;
        ul.appendChild(li);
    }

    if (item.size) {
        const li = document.createElement('li');
        li.textContent = item.size;
        ul.appendChild(li);
    }

    const tradableLi = document.createElement('li');
    tradableLi.textContent = item.tradable ? 'Tradable' : 'Not tradable';
    ul.appendChild(tradableLi);

    div.appendChild(ul);

    const descriptionP = document.createElement('p');
    descriptionP.textContent = item.description.length > 100 ? `${item.description.substring(0, 100)}...` : item.description;
    div.appendChild(descriptionP);

    const timeP = document.createElement('p');
    timeP.textContent = getTimePassed(item.created_at); // Assuming getTimePassed is defined elsewhere
    div.appendChild(timeP);

    article.appendChild(div);

    const topRightElement = document.createElement('div');
    topRightElement.className = 'top-right-element';
    const priceP = document.createElement('p');
    priceP.textContent = `${item.price.toFixed(2)} $`;
    topRightElement.appendChild(priceP);
    article.appendChild(topRightElement);

    drawButtonsItem(item, session, title, inCart, inWishList,article);  
    // Assuming you have a container where you want to append the article
    parent.appendChild(article);
}

function drawButtonsItem(item, session, title, inCart, inWishList,parent) {
     if (session.isLoggedIn) { // Assuming session is an object with an isLoggedIn property
        const form = document.createElement('form');

        const userIdInput = document.createElement('input');
        userIdInput.type = 'hidden';
        userIdInput.name = 'user-id';
        userIdInput.value = session.id; // Assuming session has an id property
        form.appendChild(userIdInput);

        const itemIdInput = document.createElement('input');
        itemIdInput.type = 'hidden';
        itemIdInput.name = 'item-id';
        itemIdInput.value = item.id;
        form.appendChild(itemIdInput);

        const baseUrl = `../actions/action_item_status.php?item-id=${item.id}&`
        if (title === 'Find what you want to buy!' || title === 'Your Wishlist!' || title === 'Time to buy!') {
            const cartButton = document.createElement('a');
            cartButton.href = baseUrl + `action=cart-toggle`;
            cartButton.classList.add("item-action","button");
            if(inCart) cartButton.classList.add("selected");
            const cartImg = document.createElement('img');
            cartImg.src = '../assets/img/shopping-cart.svg';
            cartImg.alt = 'Add to Cart';
            cartButton.appendChild(cartImg);
            form.appendChild(cartButton);

            const wishlistButton = document.createElement('a');
            wishlistButton.href = baseUrl + `action=wishlist-toggle`;
            wishlistButton.classList.add("item-action","button")
            if(inWishList) wishlistButton.classList.add("selected");
            const wishlistImg = document.createElement('img');
            wishlistImg.src = '../assets/img/love.svg';
            wishlistImg.alt = 'Add to Wishlist';
            wishlistButton.appendChild(wishlistImg);
            form.appendChild(wishlistButton);
        } else if (title === 'Your items to sell') {
            const deleteButton = document.createElement('a');
            deleteButton.href = baseUrl + `action=delete`;
            deleteButton.classList.add("item-action","button")
            const deleteImg = document.createElement('img');
            deleteImg.src = '../assets/img/trash.svg';
            deleteImg.alt = 'Delete Item';
            deleteButton.appendChild(deleteImg);
            form.appendChild(deleteButton);
        }

        parent.appendChild(form);
    }
}
