$(document).ready(function () {
  $('select[data-tags=true]').select2({ tags: true });
  $('select').not('[data-tags=true]').select2({ tags: false });
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

  $('[data-project-fav]').click(function (e) {
    let favoritedData = $(this).data('project-favorited') || 0;
    let favorited = favoritedData == '1' ? true : false;
    let projectId = $(this).data('project-fav') || false;

    if (!projectId) return;

    let self = this;
    if (favorited) {
      $.get('/project/unfav/' + projectId, function() {
        $(self).data('project-favorited', '0');
        $(self).html('<i class="far fa-star"></i>');
      });
    } else {
      $.get('/project/fav/' + projectId, function() {
        $(self).data('project-favorited', '1');
        $(self).html('<i class="fas fa-star"></i>');
      });
    }
  });

  $('[data-confirm-delete-url]').click(function (e) {
    e.preventDefault();
    $('#modalDeleteUrl').attr('href', $(e.target).attr('href'));
    $('#confirmDelete').modal();
  });

  $('.request-runner').click(function (e) {
    const $_target = $(e.target);
    const url = $_target.data('request');
    $_target.prop('disabled', true);
    $.get(url, function () {
      $_target.prop('disabled', false);
    });
  });

  $('.row-select-all-checkbox').click(function (e) {
    $('input[type=checkbox]:enabled', $(e.target).closest('tr'))
      .not(e.target).prop('checked', e.target.checked);
  });
});
