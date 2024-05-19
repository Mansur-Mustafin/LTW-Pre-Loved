const dashboardCanvas = document.getElementById("dashboard-graph")

if(dashboardCanvas) {
    buildDashboard().then(r => null )
}

async function buildDashboard() {
    const response = await fetch('../api/transactions.php')
    const transactions = await response.json()
    transactionsByMonth(transactions,dashboardCanvas)
}

function transactionsByMonth(transactions,chartsSection) {
    let monthIndex = Array.from({length: 12}, (v, k) => k + 1);
    let formattedElements = transactions.map((e) => (new Date(1000 * e.created_at)))
    let monthsUsers = formattedElements
        .filter((e) => e.getFullYear() === new Date().getFullYear())
        .map((e) => e.getMonth())
    let monthsCountUsers = monthIndex.map((month) => monthsUsers.filter((e) => e === month).length)
    createGraphLine(chartsSection,months(monthsCountUsers.length),monthsCountUsers,"Transactions by Month")
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