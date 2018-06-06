$(document).ready(function () {
  $('select').select2();
  $('.dupplicate-item').hide();

  $('.dupplicate-action').click(function (e) {
    const target = e.target;
    const item = target.parentNode.querySelector('.dupplicate-item');
    $('select', item).select2('destroy');
    $('select, input', item).val('');

    if (!item) return;
    const newItem = item.cloneNode(true);
    newItem.classList.remove('dupplcate-item');
    target.parentNode.insertBefore(newItem, target);
    $(newItem).removeClass('dupplicate-item').show();

    $('select').select2();
  });

  $('#searcher').on('keyup change', function (e) {
    const target = e.target;
    $('.searcher-item').show();
    if (target.value) {
      $('.searcher-item').not('[data-searcher*="' + target.value.replace(/"/g, '&quot;').toLowerCase() + '"]').hide();
    }
  });
});
