document.addEventListener('DOMContentLoaded', () => {
    const track = document.querySelector('.carousel-track');
    const slides = Array.from(track.children);
    const nextButton = document.querySelector('.carousel-button.right');
    const prevButton = document.querySelector('.carousel-button.left');
    const dots = Array.from(document.querySelectorAll('.carousel-dot'));
    const slideWidth = slides[0].getBoundingClientRect().width;
    let currentIndex = 0;
    let autoSlideInterval;

    const moveToSlide = (index) => {
        const targetSlide = slides[index];
        track.style.transform = `translateX(-${slideWidth * index}px)`;
        slides[currentIndex].classList.remove('current-slide');
        dots[currentIndex].classList.remove('active');
        slides[index].classList.add('current-slide');
        dots[index].classList.add('active');
        currentIndex = index;
    };

    const moveToNextSlide = () => {
        const nextIndex = (currentIndex + 1) % slides.length;
        moveToSlide(nextIndex);
    };

    const startAutoSlide = () => {
        autoSlideInterval = setInterval(moveToNextSlide, 3000);
    };

    const stopAutoSlide = () => {
        clearInterval(autoSlideInterval);
    };

    nextButton.addEventListener('click', () => {
        stopAutoSlide();
        moveToNextSlide();
        startAutoSlide();
    });

    prevButton.addEventListener('click', () => {
        stopAutoSlide();
        const prevIndex = (currentIndex - 1 + slides.length) % slides.length;
        moveToSlide(prevIndex);
        startAutoSlide();
    });

    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            stopAutoSlide();
            moveToSlide(index);
            startAutoSlide();
        });
    });

    startAutoSlide();
});