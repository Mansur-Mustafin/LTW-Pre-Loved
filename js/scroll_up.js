let scrollUp = document.getElementById('back-button');

scrollUp.addEventListener('click', function(event){
  event.preventDefault();
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });
});