
function setActiveButton(id) {
    const buttons = document.querySelectorAll('button');
    buttons.forEach(btn => {
        btn.classList.remove('bg-primary', 'text-white');
        btn.classList.add('text-black', 'bg-transparent');
    });

    const activeBtn = document.getElementById(id);
    activeBtn.classList.remove('text-black', 'bg-transparent');
    activeBtn.classList.add('bg-primary', 'text-white');
}
// function toggleDropdown(index) {
//     const dropdown = document.getElementById(`dropdownContent${index}`);
//     const iconUp = document.getElementById(`iconUp${index}`);
//     const iconDown = document.getElementById(`iconDown${index}`);

//     const isVisible = dropdown.style.display === "block";
//     dropdown.style.display = isVisible ? "none" : "block";
//     iconUp.style.display = isVisible ? "none" : "inline-block";
//     iconDown.style.display = isVisible ? "inline-block" : "none";
// }

// window.onload = function () {
//     toggleDropdown(1);
// };
document.addEventListener("DOMContentLoaded", function () {
    // Feature Slider
    let featureIndex = 1;
    showSlidesFeature(featureIndex);

    window.currentSlideFeature = function (n) {
        showSlidesFeature(featureIndex = n);
    };

    function showSlidesFeature(n) {
        const slides = document.getElementsByClassName("mySlides-feature");
        const dots = document.getElementsByClassName("dot-feature");

        if (n > slides.length) featureIndex = 1;
        if (n < 1) featureIndex = slides.length;

        for (let i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }

        for (let i = 0; i < dots.length; i++) {
            dots[i].classList.remove("bg-primary");
            dots[i].classList.add("bg-gray-300");
        }

        slides[featureIndex - 1].style.display = "block";
        dots[featureIndex - 1].classList.add("bg-primary");
        dots[featureIndex - 1].classList.remove("bg-gray-300");
    }

    // Testimonials Slider
    let testimonialsIndex = 1;
    showSlidesTestimonials(testimonialsIndex);

    window.currentSlideTestimonials = function (n) {
        showSlidesTestimonials(testimonialsIndex = n);
    };

    function showSlidesTestimonials(n) {
        const slides = document.getElementsByClassName("mySlides-testimonials");
        const dots = document.getElementsByClassName("dot-testimonials");

        if (n > slides.length) testimonialsIndex = 1;
        if (n < 1) testimonialsIndex = slides.length;

        for (let i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }

        for (let i = 0; i < dots.length; i++) {
            dots[i].classList.remove("bg-primary");
            dots[i].classList.add("bg-gray-300");
        }

        slides[testimonialsIndex - 1].style.display = "block";
        dots[testimonialsIndex - 1].classList.add("bg-primary");
        dots[testimonialsIndex - 1].classList.remove("bg-gray-300");
    }

    // Who it’s For Slider
    let whoForIndex = 1;
    showSlidesWhoFor(whoForIndex);

    window.currentSlideWhoFor = function (n) {
        showSlidesWhoFor(whoForIndex = n);
    }

    function showSlidesWhoFor(n) {
        const slides = document.getElementsByClassName("mySlides-who-for");
        const dots = document.getElementsByClassName("dot-who-for");

        if (n > slides.length) whoForIndex = 1;
        if (n < 1) whoForIndex = slides.length;

        for (let i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }

        for (let i = 0; i < dots.length; i++) {
            dots[i].classList.remove("bg-primary");
            dots[i].classList.add("bg-gray-300");
        }

        slides[whoForIndex - 1].style.display = "block";
        dots[whoForIndex - 1].classList.add("bg-primary");
        dots[whoForIndex - 1].classList.remove("bg-gray-300");
    }

    // See Our Blogs Slider
    let blogsIndex = 1;
    showSlidesBlogs(blogsIndex);

    window.currentSlideBlogs = function (n) {
        showSlidesBlogs(blogsIndex = n);
    }

    function showSlidesBlogs(n) {
        const slides = document.getElementsByClassName("mySlides-blogs");
        const dots = document.getElementsByClassName("dot-blogs");

        if (n > slides.length) blogsIndex = 1;
        if (n < 1) blogsIndex = slides.length;

        for (let i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }

        for (let i = 0; i < dots.length; i++) {
            dots[i].classList.remove("bg-primary");
            dots[i].classList.add("bg-gray-300");
        }

        slides[blogsIndex - 1].style.display = "block";
        dots[blogsIndex - 1].classList.add("bg-primary");
        dots[blogsIndex - 1].classList.remove("bg-gray-300");
    }
});