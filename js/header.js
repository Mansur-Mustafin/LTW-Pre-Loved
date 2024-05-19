const sidebar = document.getElementById('related');
const openSidebarButton = document.querySelector(".open-sidebar");
const closeSidebarButton = document.querySelector(".close-sidebar");
const openMenuButton = document.querySelector(".open-menu");

openSidebarButton?.addEventListener('click', function (el) {
    sidebar?.classList.toggle('open');
});

closeSidebarButton?.addEventListener('click', function (el) {
    sidebar?.classList.toggle('open');
});

if (!sidebar) {
    openSidebarButton.setAttribute('disabled', 'disabled');
}

openMenuButton.addEventListener('click', function (el) {
    el.target.closest('.open-menu')?.classList.toggle('open');
    el.target.closest('header')?.classList.toggle('open');
});
