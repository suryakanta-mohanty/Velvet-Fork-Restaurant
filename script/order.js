const buttons = document.querySelectorAll('.filter_btns button');
const foodCards = document.querySelectorAll('.food-card');

buttons.forEach(button =>{
  button.addEventListener('click', ()=>{
    const filter = button.innerText.toLowerCase();
    let modifiedFilter = filter;
    if(filter === 'non veg') modifiedFilter = 'non-veg';
    
    foodCards.forEach(foodCard =>{
      const category = foodCard.getAttribute('data-category');
      
      if (modifiedFilter === 'show all'){
        foodCard.style.display = 'block';

      }else{
        foodCard.style.display = category === modifiedFilter ? 'block' : 'none';

      }
    });

    buttons.forEach(btn => btn.style.backgroundColor = 'white');
    buttons.forEach(btn => btn.style.color = 'black');
    button.style.backgroundColor = '#E50046';
    button.style.color = 'white';
  });
});


document.addEventListener('DOMContentLoaded', () => {
  const addButtons = document.querySelectorAll('.add-to-cart, .pricing button');

  addButtons.forEach(button => {
    button.addEventListener('click', () => {
      const foodCard = button.closest('.food-card');
      
      const name = foodCard.querySelector('.food-card-title h2').innerText.trim();
      const isVeg = foodCard.querySelector('h2 img').getAttribute('src').includes('veg') ? 'Veg' : 'Non-Veg';
      const priceText = foodCard.querySelector('.pricing p').innerText.trim();
      const price = priceText.replace(/[^\d]/g, ''); // Remove currency symbol and keep only number
      const imageUrl = foodCard.querySelector('.food-card-img img').getAttribute('src');

      console.log({
        name,
        type: isVeg,
        price: Number(price),
        imageUrl
      });
    });
  });
});


document.addEventListener('DOMContentLoaded', ()=>{
  const addButtons = document.querySelectorAll('.food-card Button')
  
  addButtons.forEach(button =>{
    button.addEventListener('click', ()=>{
      const card = button.closest('.food-card');
      const name = card.querySelector('.food-card-title h2').innerText;
      const price = card.querySelector('.pricing p').innerText;
      const foodImage = card.querySelector('.food-card-img img').getAttribute('src');

      let cart = JSON.parse(localStorage.getItem('cart')) || [];

      cart.push({name, price, foodImage});

      localStorage.setItem('cart', JSON.stringify(cart));

      alert(`${name} added to Cart!`);
    });
  });
});