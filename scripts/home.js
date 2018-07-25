$(document).ready(function(){
  if (screen.width >= 1028){
    var nextimage=0;
    doSlideshow();

    function doSlideshow() {

      $(".header img").removeClass("opaque");
      $(".header .slogan").removeClass("bold--red");
      $(".header img").eq(nextimage).addClass("opaque");
      $(".header .slogan").eq(nextimage++).addClass("bold--red");

      if(nextimage>=4){nextimage=0;}

      setTimeout(doSlideshow, 7000);
    }
  }

  $(".toggle").click(function(){
    $(".header__menu").slideToggle(700);
  });

  currentSlide_htj(1);
  currentSlide_fa(1);

  $("#video1").click(function() {
    $("#video-main").attr("src", "https://www.youtube.com/embed/lxXGAbVd5sg")
  })

  $("#video2").click(function() {
    $("#video-main").attr("src", "https://www.youtube.com/embed/Tpre3lMmIns")
  })

  $("#video3").click(function() {
    $("#video-main").attr("src", "https://www.youtube.com/embed/W3dii56k5j8")
  })

  $("#video4").click(function() {
    $("#video-main").attr("src", "https://www.youtube.com/embed/Jv-qDUEkMC4")
  })

  $("#video5").click(function() {
    $("#video-main").attr("src", "https://www.youtube.com/embed/lxXGAbVd5sg")
  })

  var timelineSwiper = new Swiper ('.timeline .swiper-container', {
    direction: 'vertical',
    loop: false,
    speed: 1600,
    pagination: '.swiper-pagination',
    paginationBulletRender: function (swiper, index, className) {
      var year = document.querySelectorAll('.swiper-slide')[index].getAttribute('data-year');
      return '<span class="' + className + '">' + year + '</span>';
    },
    paginationClickable: true,
    nextButton: '.swiper-button-next',
    prevButton: '.swiper-button-prev',
    breakpoints: {
      768: {
        direction: 'horizontal',
      }
    }
  });


});
var slideIndex = 1;
var temp = 0;

// Next/previous controls
function plusSlides_htj(n) {
  temp = slideIndex + n;
  showSlides_htj(slideIndex + n);
}

// Thumbnail image controls
function currentSlide_htj(n) {
  temp = n;
  showSlides_htj(n);
}

function showSlides_htj(n) {
  var i;
  var slides = document.getElementsByClassName("how-to-join__mySlides");
  var dots = document.getElementsByClassName("dot--htj");
  var arrows = document.getElementsByClassName("arrow--htj");
  var amination = document.getElementsByClassName("how-to-join__table");

  if (slideIndex > temp) {
    amination[n-1].classList.add("moveToRight");
    amination[n-1].classList.remove("moveToLeft");
  }
  else {
    amination[n-1].classList.remove("moveToRight");
    amination[n-1].classList.add("moveToLeft");
  }

  slideIndex = temp;
  temp = 0;

  if (n > slides.length) {slideIndex = 1}
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active-grey", "");
  }

  if (slideIndex == slides.length) {arrows[1].style.display = "none";}
  else {arrows[1].style.display = "block";}

  if (slideIndex == 1) {arrows[0].style.display = "none";}
  else {arrows[0].style.display = "block";}

  slides[slideIndex-1].style.display = "block";
  dots[slideIndex-1].className += " active-grey";
}



var fa_slideIndex = 1;
var fa_temp = 0;

// Next/previous controls
function plusSlides_fa(n) {
  fa_temp = fa_slideIndex + n;
  showSlides_fa(fa_slideIndex + n);
}

// Thumbnail image controls
function currentSlide_fa(n) {
  fa_temp = n;
  showSlides_fa(n);
}

function showSlides_fa(n) {
  var i;
  var slides = document.getElementsByClassName("featured-articles__mySlides");
  var dots = document.getElementsByClassName("dot--fa");
  var arrows = document.getElementsByClassName("arrow--fa");
  var amination = document.getElementsByClassName("featured-articles__table");

  if (fa_slideIndex > fa_temp) {
    amination[n-1].classList.add("moveToRight");
    amination[n-1].classList.remove("moveToLeft");
  }
  else {
    amination[n-1].classList.remove("moveToRight");
    amination[n-1].classList.add("moveToLeft");
  }

  fa_slideIndex = fa_temp;
  fa_temp = 0;

  if (n > slides.length) {fa_slideIndex = 1}
  if (n < 1) {fa_slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active-blue", "");
  }

  if (fa_slideIndex == slides.length) {arrows[1].style.display = "none";}
  else {arrows[1].style.display = "block";}

  if (fa_slideIndex == 1) {arrows[0].style.display = "none";}
  else {arrows[0].style.display = "block";}

  slides[fa_slideIndex-1].style.display = "block";
  dots[fa_slideIndex-1].className += " active-blue";
}
