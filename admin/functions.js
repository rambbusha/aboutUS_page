// Get the modal
var modal = document.getElementById('myModal');

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on the button, open the modal
function modal_staff(value) {
    modal.style.display = "block";
    var strLink = "../include/rm_tuple_staff.inc.php?id=" + value;
    document.getElementById("mytext").setAttribute("href",strLink);
}

function modal_sponsors(value) {
    modal.style.display = "block";
    var strLink = "../include/rm_tuple_sponsors.inc.php?id=" + value;
    document.getElementById("mytext").setAttribute("href",strLink);
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

function trim_data(string) {
    console.log(string)
}

// scroll to add input function
function scrollToAdd() {
  document.querySelector('.input-section').scrollIntoView({
		behavior: 'smooth'
  })
}

// table search function
var $rows = $('#table tbody tr');

$('#search').keyup(debounce(function() {
  var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase().split(' ');

    $rows.hide().filter(function() {
      var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
      var matchesSearch = true;
      $(val).each(function(index, value) {
        matchesSearch = (!matchesSearch) ? false : ~text.indexOf(value);
      });
      return matchesSearch;
    }).show();
}, 300));

// this function set the time for the search, so it does performance optimization
function debounce(func, wait, immediate) {
  var timeout;
  return function() {
    var context = this,
      args = arguments;
    var later = function() {
      timeout = null;
      if (!immediate) func.apply(context, args);
    };
    var callNow = immediate && !timeout;
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
    if (callNow) func.apply(context, args);
  };
};
