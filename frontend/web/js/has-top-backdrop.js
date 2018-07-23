function navIndexChange() {
  if ($(window).scrollTop() >= 50) {
    $('.dropdown-menu, .dropdown-toggle')
      .css('background-color','#f8f8f8');

    $('.list-group-item').css({
      'color': '#141517',
      'background-color': '#f8f8f8',
      'border-color': '#ddd',
    });

    $(`#sidebar .list-group-item.active, #sidebar
      .list-group-item.active:hover, #sidebar
      .list-group-item.active:focus`).css({
      'color': '#fff',
      'background-color': '#55AE3A',
      'border-color': '#55AE3A'
    });

  } else {
    $('.dropdown-menu, .dropdown-toggle')
      .css('background-color', 'transparent');

    $('.list-group-item').css({
      'color': '#fff',
      'background-color': 'rgba(0, 0, 0, 0.5)',
      'border-color': 'rgba(0, 0, 0, 0.5)',
    });

    $(`#sidebar .list-group-item.active, #sidebar
      .list-group-item.active:hover, #sidebar
      .list-group-item.active:focus`).css({
        'background-color': 'rgba(85, 174, 58, 0.7)',
        'border-color': 'rgba(85, 174, 58, 0.7)'
      });

  } 
}
$('document').ready(function() {
  navIndexChange();
});

$(window).scroll(function () {
  navIndexChange();
});