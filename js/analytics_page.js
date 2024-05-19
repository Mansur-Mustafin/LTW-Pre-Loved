async function getItems() {
    const response = await fetch('../api/items.php?search=')
    return await response.json()
}

async function getUsers() {
    const response = await fetch('../api/users.php?search=')
    return await response.json()
}

async function getEntities(type) {
    const response = await fetch('../api/entities.php?search='+type)
    return await response.json()
}

async function getTransactions() {
    const response = await fetch('../api/transactions.php')
    return await response.json()
}


const MONTHS = [
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July',
    'August',
    'September',
    'October',
    'November',
    'December'
  ];
  
function months(config) {
let cfg = config || {};
let count = cfg.count || 12;
let section = cfg.section;
let values = [];
let i, value;

for (i = 0; i < count; ++i) {
    value = MONTHS[Math.ceil(i) % 12];
    values.push(value.substring(0, section));
}

return values;
}

async function buildPage() {
    const analyticsSection = document.getElementById("analytics-admin")
    const chartsSection = document.getElementById("analytics-charts")

    let users;
    let items;
    let transactions;
    let tags;
    let categories;
    let sizes;
    let models;
    let brands;
    let conditions;
    let countCategories;
    let countSizes;
    let countConditions;
    let countBrands;
    let categoriesGraph;
    let sizeGraph;
    let conditionGraph;
    let brandGraph;
    if (analyticsSection && chartsSection) {
        users = await getUsers()
        items = await getItems()
        transactions = await getTransactions()
        tags = await getEntities("Tags")
        categories = await (getEntities("Categories"))
        sizes = await (getEntities("Size"))
        models = await (getEntities("Models"))
        brands = await (getEntities("Brands"))
        conditions = await (getEntities("Condition"))

        createParagraphMap("Users: ", users.length, analyticsSection)
        createParagraphMap("Banned Users: ", users.filter((value) => value.banned).length, analyticsSection)
        createParagraphMap("Admin Users: ", users.filter((value) => value.admin_flag).length, analyticsSection)
        createParagraphMap("Items: ", items.length, analyticsSection)
        createParagraphMap("Tags: ", tags.length, analyticsSection)
        createParagraphMap("Categories: ", categories.length, analyticsSection)
        createParagraphMap("Sizes: ", sizes.length, analyticsSection)
        createParagraphMap("Models: ", models.length, analyticsSection)
        createParagraphMap("Brands: ", brands.length, analyticsSection)
        createParagraphMap("Conditions: ", conditions.length, analyticsSection)

        countCategories = categories.map((category) => {
            return {key: category.name, value: items.filter((value) => value.category === category.name).length}
        })
        countSizes = sizes.map((size) => {
            return {key: size.name, value: items.filter((value) => value.size === size.name).length}
        })
        countConditions = conditions.map((condition) => {
            return {key: condition.name, value: items.filter((value) => value.condition === condition.name).length}
        })
        countBrands = brands.map((brand) => {
            return {key: brand.name, value: items.filter((value) => value.brand === brand.name).length}
        })

        drawTransactionsByMonthGraph(transactions, chartsSection)
        drawUserByMonthGraph(chartsSection)
        drawGraphByDay(chartsSection, items, "New Items by Day")
        categoriesGraph = createGraph(chartsSection, "Categories", countCategories, "doughnut")
        sizeGraph = createGraph(chartsSection, "Sizes", countSizes, "doughnut")
        conditionGraph = createGraph(chartsSection, "Conditions", countConditions, "doughnut")
        brandGraph = createGraph(chartsSection, "Brands", countBrands, "doughnut")
    }
}

function drawTransactionsByMonthGraph(transactions,chartsSection) {
    monthIndex = Array.from({length: 12}, (v, k) => k+1);
    let formattedElements = transactions.map((e) => (new Date(1000 * e.created_at)))
    let monthsUsers = formattedElements
        .filter((e) => e.getFullYear() === new Date().getFullYear())
        .map((e) => e.getMonth())
    let monthsCountUsers = monthIndex.map((month) => monthsUsers.filter((e) => e === month).length)
    createGraphLine(chartsSection,months(monthsCountUsers.length),monthsCountUsers,"Transactions by Month")
}

function drawUserByMonthGraph(chartsSection) {
    let monthIndex = Array.from({length: 12}, (v, k) => k + 1);
    let formattedCreatedAtUsers = users.map((e) => (new Date(1000 * e.created_at)))
    let monthsUsers = formattedCreatedAtUsers
        .filter((e) => e.getFullYear() === new Date().getFullYear())
        .map((e) => e.getMonth())
    let monthsCountUsers = monthIndex.map((month) => monthsUsers.filter((e) => e === month).length)
    createGraphLine(chartsSection,months(monthsCountUsers.length),monthsCountUsers,"Users by Month")
}

function drawGraphByDay(chartsSection,values,name) {
    let daysInCurrentMonth = new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0).getDate()
    let currentYear = new Date().getFullYear()
    let dayIndex = Array.from({length: daysInCurrentMonth}, (v, k) => k + 1)
    let formattedData = values.map((e) => (new Date(1000 * e.created_at)))
    let daysItems = formattedData
        .filter((e) => e.getFullYear() === new Date().getFullYear() && e.getMonth() === new Date().getMonth())
    let daysCountItems = dayIndex.map((day) => daysItems.filter((e) => e.getDate() === day).length)
    console.log(daysCountItems)
    createGraphLine(chartsSection,dayIndex,daysCountItems,name)
}

function createGraph(parent,name,countArray,type) {
    const ctx = document.createElement("canvas")
    const div = document.createElement("div")
    div.classList.add("graph",type)
    div.appendChild(ctx)
    parent.appendChild(div)

    let labels = countArray.map((e) => e.key);
    let values = countArray.map((e) => e.value);
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

function createParagraphMap(key,value,parent,className) {
    let paragraph = document.createElement("p")
    paragraph.textContent = key

    const valueSpan = document.createElement("span")
    valueSpan.textContent = value
    valueSpan.className = "count"
    paragraph.appendChild(valueSpan)

    paragraph.classList.add ("item",className)
    parent.appendChild(paragraph)
}

function createGraphLine(parent, labels,values,name) {
    const ctx = document.createElement("canvas")
    const div = document.createElement("div")
    div.classList.add("graph","line")
    div.appendChild(ctx)
    parent.appendChild(div)

    const data = {
        labels: labels,
        datasets: [{
            label: name,
            data: values,
            fill: true,
            tension: 0.1
        }]
    }

    const config = {
        type: 'line',
        data: data,
    };

    return new Chart(ctx,config)
}

buildPage().then(r => null )
