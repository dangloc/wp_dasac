document.addEventListener('DOMContentLoaded', function() {
    // Initialize Flickity carousel
    var elem = document.querySelector('.carousel');
    if (elem) {
        // Detect screen width and set groupCells accordingly
        var groupCells = window.innerWidth < 568 ? 2 : 6;
        
        var flkty = new Flickity(elem, {
            cellAlign: 'center',
            contain: true,
            pageDots: false,
            prevNextButtons: false,
            autoPlay: 4000,
            groupCells: groupCells
        });
        
        // Handle resize events
        window.addEventListener('resize', function() {
            var newGroupCells = window.innerWidth < 568 ? 2 : 6;
            if (newGroupCells !== groupCells) {
                groupCells = newGroupCells;
                flkty.destroy();
                flkty = new Flickity(elem, {
                    cellAlign: 'center',
                    contain: true,
                    pageDots: false,
                    prevNextButtons: false,
                    autoPlay: 4000,
                    groupCells: groupCells
                });
            }
        });
    }
}); 

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Swiper with Grid
    new Swiper('.swiper-latest-top-sidebar', {
        slidesPerView: 1,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        grid: {
            rows: 3,
            fill: 'row'
        },
        spaceBetween: 24,
        navigation: {
            nextEl: '.swiper-latest-top-sidebar ~ .swiper-button-next, .swiper-latest-top-sidebar .swiper-button-next',
            prevEl: '.swiper-latest-top-sidebar ~ .swiper-button-prev, .swiper-latest-top-sidebar .swiper-button-prev',
        },
        breakpoints: {
            0: { 
                slidesPerView: 1,
                grid: {
                    rows: 2
                }
            },
            576: { 
                slidesPerView: 1,
                grid: {
                    rows: 3
                }
            },
            992: { 
                slidesPerView: 1,
                grid: {
                    rows: 3
                }
            }
        }
    });

    // Swiper cho Truyện mới
    new Swiper('.swiper-latest', {
        slidesPerView: 4,
        autoplay: {
            delay: 4700,
            disableOnInteraction: false,
        },
        spaceBetween: 24,
        navigation: {
            nextEl: '.swiper-latest ~ .swiper-button-next, .swiper-latest .swiper-button-next',
            prevEl: '.swiper-latest ~ .swiper-button-prev, .swiper-latest .swiper-button-prev',
        },
        breakpoints: {
            0: { slidesPerView: 2 },
            576: { slidesPerView: 3 },
            992: { slidesPerView: 4 }
        }
    });

    // Swiper cho Truyện hot
    new Swiper('.swiper-popular', {
        slidesPerView: 4,
        autoplay: {
            delay: 4800,
            disableOnInteraction: false,
        },
        spaceBetween: 24,
        navigation: {
            nextEl: '.swiper-popular ~ .swiper-button-next, .swiper-popular .swiper-button-next',
            prevEl: '.swiper-popular ~ .swiper-button-prev, .swiper-popular .swiper-button-prev',
        },
        breakpoints: {
            0: { slidesPerView: 2 },
            576: { slidesPerView: 3 },
            992: { slidesPerView: 4 }
        }
    });
    // Swiper cho Thể loại 3
    new Swiper('.swiper-tax3', {
        slidesPerView: 4,
        autoplay: {
            delay: 4900,
            disableOnInteraction: false,
        },
        spaceBetween: 24,
        navigation: {
            nextEl: '.swiper-tax3 ~ .swiper-button-next, .swiper-tax3 .swiper-button-next',
            prevEl: '.swiper-tax3 ~ .swiper-button-prev, .swiper-tax3 .swiper-button-prev',
        },
        breakpoints: {
            0: { slidesPerView: 2 },
            576: { slidesPerView: 3 },
            992: { slidesPerView: 4 }
        }
    });

    // Swiper cho Thể loại 4
    new Swiper('.swiper-tax4', {
        slidesPerView: 5,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        spaceBetween: 24,
        navigation: {
            nextEl: '.swiper-tax4 ~ .swiper-button-next, .swiper-tax4 .swiper-button-next',
            prevEl: '.swiper-tax4 ~ .swiper-button-prev, .swiper-tax4 .swiper-button-prev',
        },
        breakpoints: {
            0: { slidesPerView: 2 },
            576: { slidesPerView: 3 },
            992: { slidesPerView: 4 }
        }
    });

    new Swiper('.category-slider', {
        slidesPerView: 8,
        loop: true,
        speed: 6000,
        autoplay: {
            delay: 0,
            disableOnInteraction: false,
            pauseOnMouseEnter: true
        },
        allowTouchMove: true,
        spaceBetween: 24,
        freeMode: true,
        grabCursor: true,
        breakpoints: {
            320: {
                slidesPerView: 2,
                spaceBetween: 5
            },
            480: {
                slidesPerView: 3,
                spaceBetween: 12
            },
            768: {
                slidesPerView: 5,
                spaceBetween: 16
            },
            1024: {
                slidesPerView: 7,
                spaceBetween: 24
            }
        },
        on: {
            autoplay: function () {
                document.querySelector('.category-slider .swiper-wrapper').style.transitionTimingFunction = 'linear';
            },
            touchEnd: function() {
                this.autoplay.start();
            }
        }
    });

}); 