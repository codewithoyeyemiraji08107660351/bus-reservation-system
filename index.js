
const heroImages = [
    './admin/uploads/bus1.jpg',  
    './admin/uploads/bus2.jpg',
    './admin/uploads/bus3.jpg',
    './admin/uploads/bus4.jpg',
    './admin/uploads/bus5.jpg',
];


function changeHeroBackground() {
    const randomIndex = Math.floor(Math.random() * heroImages.length);
    const selectedImage = heroImages[randomIndex];

    document.querySelector('.hero').style.backgroundImage = `url('${selectedImage}')`;
}

setInterval(changeHeroBackground, 5000);

window.onload = changeHeroBackground;
