$(document).ready(function () {
  $('select').select2();
  $('.dupplicate-item').hide();

  const moveUp = function (e) {
    const itemToMove = $(e.target).closest('.move-item')[0];
    if (!itemToMove) return;

    if (itemToMove.previousElementSibling
      && $(itemToMove.previousElementSibling).hasClass('move-item')) {
      itemToMove.parentNode.insertBefore(
        itemToMove, itemToMove.previousElementSibling
      );
    }
  }
  const moveDown = function (e) {
    const itemToMove = $(e.target).closest('.move-item')[0];
    if (!itemToMove) return;

    if (itemToMove.nextElementSibling
      && itemToMove.nextElementSibling.nextElementSibling
      && $(itemToMove.nextElementSibling).hasClass('move-item')) {
      itemToMove.parentNode.insertBefore(
        itemToMove, itemToMove.nextElementSibling.nextElementSibling
      );
    }
  }

  $('.dupplicate-action').click(function (e) {
    const target = e.target;
    const item = target.parentNode.querySelector('.dupplicate-item');

    if ($('select', item).length > 0) {
      $('select', item).select2('destroy');
    }

    $('select, input', item).val('');

    if (!item) return;
    const newItem = item.cloneNode(true);
    // newItem.classList.remove('dupplcate-item');
    target.parentNode.insertBefore(newItem, target);
    $(newItem).removeClass('dupplicate-item').show();

    $('.move-up', newItem).click(moveUp);
    $('.move-down', newItem).click(moveDown);

    $('select').select2();
  });

  $('.move-up').click(moveUp);
  $('.move-down').click(moveDown);

  $('#searcher').on('keyup change', function (e) {
    const target = e.target;
    $('.searcher-item').show();
    if (target.value) {
      $('.searcher-item')
        .not('[data-searcher*="' + target.value.replace(/"/g, '&quot;')
        .toLowerCase() + '"]')
        .hide();
    }
  });
});
