window.addEventListener('scroll', function() {
    const navbar = document.getElementById('navbar');
    if (window.scrollY > 0) {
        navbar.classList.add('fixed');
    } else {
        navbar.classList.remove('fixed');
    }
});

const prevBtn = document.querySelector('.prev-btn');
const nextBtn = document.querySelector('.next-btn');
const cardContainer = document.querySelector('.card-container');
let scrollPosition = 0;

prevBtn.addEventListener('click', () => {
    scrollPosition = Math.max(scrollPosition - 1, 0);
    cardContainer.style.transform = `translateX(-${scrollPosition * 33.333}%)`;
});

nextBtn.addEventListener('click', () => {
    scrollPosition = Math.min(scrollPosition + 1, 4); // Adjust max value based on the number of cards
    cardContainer.style.transform = `translateX(-${scrollPosition * 33.333}%)`;
});

document.getElementById('search-bar').addEventListener('input', function() {
    const searchText = this.value.toLowerCase();
    const cards = document.querySelectorAll('.card');

    cards.forEach(card => {
        const title = card.querySelector('h3').textContent.toLowerCase();
        if (title.includes(searchText)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});