class DateObject {
  constructor(date) {
    this.date = this.initDate(date);
  }

  initDate(date) {
    switch (typeof (date)) {
      case 'string':
      case 'number':
        return new Date(date);
      case 'object':
        return date;
      default:
        return new Date();
    }
  }

  format() {
    let year = this.date.getFullYear().toString();
    let month = (this.date.getMonth() + 1).toString();
    let day = this.date.getDate().toString();

    return year + '-' + (month[1] ? month : '0' + month[0]) + '-' + (day[1] ? day : '0' + day[0]);
  }

  allDaysTo(date) {
    let otherDate = this.initDate(date);

    let startDate = null;
    let endDate = null;

    if (this.date < otherDate) {
      startDate = new Date(this.date.toDateString());
      endDate = new Date(otherDate.toDateString());
    } else {
      endDate = new Date(this.date.toDateString());
      startDate = new Date(otherDate.toDateString());
    }

    let days = [];
    while (startDate <= endDate && startDate !== null && endDate !== null) {
      days.push(new DateObject(startDate.getTime()));
      startDate.setDate(startDate.getDate() + 1);
    }

    return days;
  }

  isWeekDay() {
    return !this.isWeekEnd();
  }

  isWeekEnd() {
    let day = this.date.getDay();
    return day === 6 || day === 0;
  }

  isHoliday() {
    let year = this.date.getFullYear();
    let month = this.date.getMonth() + 1;
    let day = this.date.getDate();

    // Easter
    let G = year % 19;
    let C = Math.floor(year / 100);
    let H = (C - Math.floor(C / 4) - Math.floor((8 * C + 13) / 25) + 19 * G + 15) % 30;
    let I = H - Math.floor(H / 28) * (1 - Math.floor(H / 28) * Math.floor(29 / (H + 1)) * Math.floor((21 - G) / 11));
    let J = (year * 1 + Math.floor(year / 4) + I + 2 - C + Math.floor(C / 4)) % 7;
    let L = I - J;
    let easterMonth = 3 + Math.floor((L + 40) / 44);
    let easterDay = L + 28 - 31 * Math.floor(easterMonth / 4);

    // Dates based on easter
    let Paques = new Date(year, easterMonth - 1, easterDay);
    let VendrediSaint = new Date(year, easterMonth - 1, easterDay - 2);
    let LundiPaques = new Date(year, easterMonth - 1, easterDay + 1);
    let Ascension = new Date(year, easterMonth - 1, easterDay + 39);
    let Pentecote = new Date(year, easterMonth - 1, easterDay + 49);
    let LundiPentecote = new Date(year, easterMonth - 1, easterDay + 50);

    // Paques
    if (month == Paques.getMonth() + 1 && day == Paques.getDate()) {
      return true;
    }

    // VendrediSaint
    if (month == VendrediSaint.getMonth() + 1 && day == VendrediSaint.getDate()) {
      return true;
    }

    // LundiPaques
    if (month == LundiPaques.getMonth() + 1 && day == LundiPaques.getDate()) {
      return true;
    }

    // Ascension
    if (month == Ascension.getMonth() + 1 && day == Ascension.getDate()) {
      return true;
    }

    // Pentecote
    if (month == Pentecote.getMonth() + 1 && day == Pentecote.getDate()) {
      return true;
    }

    // LundiPentecote
    if (month == LundiPentecote.getMonth() + 1 && day == LundiPentecote.getDate()) {
      return true;
    }

    // Nouvel an
    if (month == 1 && day == 1) {
      return true;
    }

    // Fête du travail
    if (month == 5 && day == 1) {
      return true;
    }

    // Victoire des alliés
    if (month == 5 && day == 8) {
      return true;
    }

    // Fête nationale
    if (month == 7 && day == 14) {
      return true;
    }

    // Assomption
    if (month == 8 && day == 15) {
      return true;
    }

    // Toussaint
    if (month == 11 && day == 1) {
      return true;
    }

    // Armistice
    if (month == 11 && day == 11) {
      return true;
    }

    // Noël
    if (month == 12 && day == 25) {
      return true;
    }

    // Saint-Etienne
    if (month == 12 && day == 26) {
      return true;
    }

    return false;
  }
}

function nbWorkingDaysBetween(date1, date2) {
  let dateInterval = new DateObject(date1).allDaysTo(date2);
  let weekDays = dateInterval.filter(d => d.isWeekDay());
  let workDays = weekDays.filter(d => !d.isHoliday());

  return workDays.length;
}

let leaveStart = document.getElementById('leaveStart');
let leaveEnd = document.getElementById('leaveEnd');
let leaveStartTime = document.getElementById('leaveStartTime');
let leaveEndTime = document.getElementById('leaveEndTime');
let leaveDays = document.getElementById('leaveDays');

function updateLeaveDays() {
  let start = leaveStart.value;
  let end = leaveEnd.value;

  let nbDays = nbWorkingDaysBetween(start, end);
  if (parseInt(leaveStartTime.value) > 9) {
    nbDays -= .5;
  }

  if (parseInt(leaveEndTime.value) < 18) {
    nbDays -= .5;
  }

  leaveDays.value = nbDays;
}

if (leaveStartTime) {
  $(leaveStartTime).on('change', function () {
    updateLeaveDays();
  });
}

if (leaveEndTime) {
  $(leaveEndTime).on('change', function () {
    updateLeaveDays();
  });
}

if (leaveStart && leaveEnd && leaveDays) {
  leaveStart.addEventListener('change', function () {
    leaveEnd.setAttribute('min', this.value);
    if (leaveEnd.value < leaveStart.value) {
      leaveEnd.value = leaveStart.value;
    }
    updateLeaveDays();
  });
  leaveEnd.addEventListener('change', function () {
    updateLeaveDays();
  })
}

let contractStart = document.getElementById('contractStart');
let contractEnd = document.getElementById('contractEnd');
let contractType = document.getElementById('contractType');
let contractDays = document.getElementById('contractDays');
let contractDaysGroup = document.getElementById('contractDaysGroup');

function updateContractDays() {
  if (contractType.value.toLowerCase() == 'stage') {
    $(contractDaysGroup).show();
    if (contractStart && contractEnd && contractStart.value != '' && contractEnd.value != '') {
      let nbDays = nbWorkingDaysBetween(contractStart.value, contractEnd.value);
      contractDays.value = nbDays;
    } else {
      contractDays.value = '';
    }
  } else {
    contractDays.value = '';
    $(contractDaysGroup).hide();
  }
}

if (contractType && contractDays && contractDaysGroup) {
  $(contractType).on('change', function () {
    updateContractDays();
  });
}

if (contractStart && contractEnd && contractDays) {
  contractStart.addEventListener('change', function () {
    updateContractDays();
  });
  contractEnd.addEventListener('change', function () {
    updateContractDays();
  })
}

$(document).ready(function () {
  $.fn.select2.defaults.set('theme', 'bootstrap4');
  $('select[data-tags=true]').select2({
    tags: true,
    width: '100%',
    minimumResultsForSearch: 5
  });
  $('select').not('[data-tags=true]').select2({
    tags: false,
    width: '100%',
    minimumResultsForSearch: 5
  });
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

    $('select[data-tags=true]', target.parentNode).select2({
      tags: true,
      width: '100%',
      minimumResultsForSearch: 5
    });
    $('select', target.parentNode).not('[data-tags=true]').select2({
      tags: false,
      width: '100%',
      minimumResultsForSearch: 5
    });
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
      minimumResultsForSearch: 5,
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
    $('#modalDeleteUrl').attr(
      'href',
      $(e.target).closest('[data-confirm-delete-url]').attr('href')
    );
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
