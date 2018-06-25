$(document).ready(function () {
  $('select[data-tags=true]').select2({ tags: true, width: '100%' });
  $('select').not('[data-tags=true]').select2({ tags: false, width: '100%' });
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
    if (!item) return;

    $('select.select2-hidden-accessible', item).select2('destroy');

    $('select, input', item).val('');

    const newItem = $(item).clone().insertBefore(target).removeClass('dupplicate-item').show();

    $('.move-up', newItem).click(moveUp);
    $('.move-down', newItem).click(moveDown);
    console.log(target.parentNode);

    $(target.parentNode).each(function () {
      $('select, option', this).removeAttr('data-select2-id');
    });

    $('select[data-tags=true]', target.parentNode).select2({ tags: true, width: '100%' });
    $('select', target.parentNode).not('[data-tags=true]').select2({ tags: false, width: '100%' });
  });

  $('[data-create-contact-modal]').click(function (e) {
    e.preventDefault();
    let $item = $('select[name^=contacts]');
    let $modal = $('#createContactModal');
    let $form = $('form#createContactModalForm');
    let $btn = $('#createContactModalBtn');

    $('select', $modal).select2('destroy');
    $form.get(0).reset();
    $btn.prop('disabled', false);

    $btn.click(function (e) {

      $.post("/contacts/newAjax", $form.serialize(), function (data) {
        if (data.success) {
          let optionVal = data.id;
          let optionTxt = data.name;

          if (!$item.find("option[value='" + optionVal + "']").length) {
            var newOption = new Option(optionTxt, optionVal, true, true);
            $item.append(newOption).trigger('change');
          }
        }
      }, "json");

      $modal.modal('hide');
      $btn.prop('disabled', true);
    });

    $modal.modal();
    $('select', $modal).select2({
      tags: true,
      width: '100%',
      dropdownParent: $modal
    });
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

  $('input[type=file]').change(function (e) {
    const target = e.target;
    let fileName = 'Choisir un fichier...';
    if (target.files && target.files.length > 0) {
      fileName = target.files[0].name;
    }
    console.log($(target).next('label').text(fileName));
  })
});
