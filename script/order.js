const cards = document.querySelectorAll('.card')

const addToCart = document.querySelector('.add-to-cart');

let cartNo = 0;
addToCart.addEventListener('click', ()=>{
  cartNo ++;
  console.log(cartNo);
});