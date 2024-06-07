const selectField = document.body.getElementsByClassName('select-field');

Array.prototype.forEach.call(selectField, el => {
    el.addEventListener("click", async function(e) {
        const selectField = e.target.closest('.select-field');
        selectField.classList.toggle('open');

        if (e.target.tagName === 'LI') {
            const selectField = e.target.closest('.select-field');
            const selectFieldLabel = selectField?.getElementsByTagName("span").item(0);
            const input = selectField?.getElementsByTagName("input").item(0);

            if (input) {
                input.value = e.target.dataset.value;
                selectFieldLabel.innerText = e.target.innerText;
            }
        }
    });
});
