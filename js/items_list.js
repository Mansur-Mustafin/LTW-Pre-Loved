function updateButton() {
    document.querySelectorAll('.item-action').forEach(function(link) {
        link.addEventListener('click', function(event) {

            if (link.target === "_blank") {
                return;
            }

            event.preventDefault();
            const url = link.getAttribute('href');

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else if (data.itemId) {
                            // Hide the item
                            const itemElement = document.querySelector(`.item[data-id='${data.itemId}']`);
                            if (itemElement) {
                                itemElement.style.display = 'none';
                            }
                        } else {
                            link.classList.toggle("selected");
                            if (link.textContent.trim() != ""){
                                if (link.classList.contains("selected")) {
                                    link.textContent = link.textContent.replace('Add', 'Remove');
                                } else {
                                    link.textContent = link.textContent.replace('Remove', 'Add');
                                }
                            }
                        }
                    } else {
                        alert('Houston, we have a problem') // TODO :-)
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    });
}
updateButton();