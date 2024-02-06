import 'slick-carousel';

console.log('Carousel module');

let carousels = document.querySelectorAll('#carousel');

if (carousels.length > 0) {
  $('#carousel .row').slick({
    dots: false,
    infinite: true,
    centerMode: true,
    slidesToShow: 3,
    slidesToScroll: 1,
    prevArrow: $('#carousel .prevArrow'),
    nextArrow: $('#carousel .nextArrow'),
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
$('#starList .row').slick({
  dots: false,
  infinite: true,
  centerMode: true,
  slidesToShow: 1,
  prevArrow: $('#starList .prevArrow'),
  nextArrow: $('#starList .nextArrow'),
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
