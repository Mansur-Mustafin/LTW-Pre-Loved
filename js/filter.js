const filterBody = document.querySelector(".filter-body");
const openFilterButton = document.querySelector(".open-filter");

if (openFilterButton) {
    openFilterButton.addEventListener('click', function (el) {
        el.target.classList.toggle('open');
        filterBody.classList.toggle('open');
    });
}
