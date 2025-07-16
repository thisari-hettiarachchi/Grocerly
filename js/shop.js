// 🛒 Add to cart
const cartCount = document.getElementById('cart-count');
document.querySelectorAll('.btn-cart, .btn-buy').forEach(btn => {
  btn.addEventListener('click', () => {
    cartCount.textContent = parseInt(cartCount.textContent) + 1;
  });
});

// ❤️ Toggle like
document.querySelectorAll('.btn-like').forEach(btn => {
  btn.addEventListener('click', () => {
    const icon = btn.querySelector('i');
    icon.classList.toggle('fa-regular');
    icon.classList.toggle('fa-solid');
    icon.style.color = icon.classList.contains('fa-solid') ? 'var(--pri)' : '#888';
  });
});

// 👁️ Quick View
document.querySelectorAll('.btn-view').forEach(btn => {
  btn.addEventListener('click', () => {
    alert('Quick View Coming Soon!');
  });
});
