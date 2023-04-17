import 'slick-carousel'

console.log('Carousel module')

let carousels = document.querySelectorAll('#carousel')

if (carousels.length > 0) {
    $('#carousel').slick({
    dots: false,
    infinite: true,
    centerMode: true,
    slidesToShow: 3,
    slidesToScroll: 1,
    prevArrow: $('.prevArrow'),
    nextArrow: $('.nextArrow'),
    responsive: [
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
        },
      },
      {
        breakpoint: 900,
        settings: {
          slidesToShow: 2,
        },
      },
      {
        breakpoint: 1200,
        settings: {
          centerMode: true,
          slidesToShow: 3,
        },
      },
    ],
  });
}

    /*****************/
    // All Stars Carousel
    /*****************/
    $('#starList').slick({
        dots: false,
        arrows: false,
        infinite: true,
        centerMode: true,
        slidesToShow: 1,
        autoplay: true,
        autoplaySpeed: 5000,
        responsive: [
          {
            breakpoint: 900,
            settings: {
              slidesToShow: 1,
            },
          },
          {
            breakpoint: 1100,
            settings: {
              slidesToShow: 4,
            },
          },
          {
            breakpoint: 99999,
            settings: {
              slidesToShow: 6,
            },
          },
        ],
      });
      
      /*****************/
      // Honorable mentions Carousel
      /*****************/
      $('#honorableWrap').slick({
        dots: false,
        arrows: false,
        infinite: true,
        centerMode: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        responsive: [
          {
            breakpoint: 900,
            settings: {
              slidesToShow: 1,
            },
          },
          {
            breakpoint: 99999,
            settings: 'unslick',
          },
        ],
      });