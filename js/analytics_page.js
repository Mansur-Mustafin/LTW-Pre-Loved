/* 
+ Top 10 Brands/Categories/Tags/Conditions (graph???)
+ Number of Items created based in the time passed (graph)

+ Top 3 items in Wishlist
+ Top 3 items in Shopping Cart (and not sold)
*/

async function getItems() {
    const response = await fetch('../api/get_items.php?search=')
    const items = await response.json()
    return items
}

async function getUsers() {
    const response = await fetch('../api/get_users.php?search=')
    const users = await response.json()
    return users
}

async function getEntities(type) {
    const response = await fetch('../api/get_entities.php?search='+type)
    const entities = await response.json()
    return entities
}


async function buildPage() {
    const analyticsSection = document.getElementById("analytics-admin")
    const chartsSection = document.getElementById("analytics-charts")

    if(analyticsSection && chartsSection) {
        users = await getUsers()
        items = await getItems()
        tags = await getEntities("Tags")
        categories = await (getEntities("Categories"))
        sizes = await (getEntities("Size"))
        models = await (getEntities("Models"))
        brands = await (getEntities("Brands"))
        conditions = await (getEntities("Condition")) 

        createParagraph("Users: ", users.length,analyticsSection)
        createParagraph("Banned Users: ", users.filter((value) => value.banned).length,analyticsSection)
        createParagraph("Admin Users: ", users.filter((value) => value.admin_flag).length,analyticsSection)
        createParagraph("Items: " ,items.length,analyticsSection)
        createParagraph("Tags: ", tags.length,analyticsSection)
        createParagraph("Categories: " , categories.length, analyticsSection)
        createParagraph("Sizes: " , sizes.length, analyticsSection)
        createParagraph("Models: " , models.length,analyticsSection)
        createParagraph("Brands: ", brands.length,analyticsSection)
        createParagraph("Conditions: " , conditions.length, analyticsSection)

        countTags = tags.map((tag) => {
            return {key: tag.name, value:items.filter((value) => value.tags.includes(tag.name)).length}
        })
        countCategories = categories.map((category) => {
            return {key: category.name,value: items.filter((value) => value.category == category.name).length}
        })
        countSizes = sizes.map((size) => {
            return {key: size.name,value: items.filter((value) => value.size == size.name).length}
        })
        countConditions = conditions.map((condition) => {
            return {key: condition.name,value: items.filter((value) => value.condition == condition.name).length}
        })
        countBrands = brands.map((brand) => {
            return {key: brand.name,value: items.filter((value) => value.brand == brand.name).length}
        })


        tagsGraph = createGraph(chartsSection,"Tags",countTags,"bar")
        categoriesGraph = createGraph(chartsSection,"Categories",countCategories,"doughnut")
        sizeGraph = createGraph(chartsSection,"Sizes",countSizes,"doughnut")
        conditionGraph = createGraph(chartsSection,"Conditions",countConditions,"doughnut")
        brandGraph = createGraph(chartsSection,"Brands",countBrands,"doughnut")
    }
}


function createGraph(parent,name,countArray,type) {
    const ctx = document.createElement("canvas")
    const div = document.createElement("div")
    div.classList.add("graph",type)
    div.appendChild(ctx)
    parent.appendChild(div) 

    labels = countArray.map((e) => e.key);
    values = countArray.map((e) => e.value);
    const data = {
        labels: labels,
        datasets: [{
            label: name,
            data: values,
        }],

    }
    const config = {
        responsive:true,
        type: type,
        data: data,
        options: {
            plugins: {
                title : {
                    display: true,
                    text: name
                },
                legend: {
                    display: false
                }
            }
        }
    }
    return new Chart(ctx,config)
}

function createParagraph(key,value,parent,className) {
    paragraph = document.createElement("p")
    paragraph.textContent = key

    const valueSpan = document.createElement("span")
    valueSpan.textContent = value
    valueSpan.className = "count"
    paragraph.appendChild(valueSpan)

    paragraph.classList.add ("item",className)
    parent.appendChild(paragraph)
}

buildPage()
