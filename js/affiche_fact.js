
//the start of list select open script
function sortColSelectFilter(div) {
    formSelectFilter(div);
}

function sortColSelectOption(div) {
    formSelectOption(div);
}

//the end of list select open script


// the start of the checkbox script //
function sortDirCheckboxUnique(div) {
    formCheckboxUnique(div);
    var sortDir = $(div).attr("code"); if (sortDir == undefined || sortDir == null || sortDir == "") sortDir = "oui";
    var obj = { index: { sortDir: sortDir } };
    cookieSave(obj, true);
}
// the end of the checkbox script //



// the start of the new fieldset script //
var fieldsetCount = 1;
$(document).on('click', 'body > .cont > .data > .main > div > .btn-last > .btn > a', function () {
    console.log('Button 2 clicked');

    var newFieldsetStructure = $('body > .cont > .data > .main > div > .content > fieldset.second-field').first();

    if (newFieldsetStructure.length > 0) {
        var newFieldsetStructureCopy = newFieldsetStructure.clone();

        newFieldsetStructureCopy.removeClass('second-field');
        newFieldsetStructureCopy.addClass('third-field-' + fieldsetCount);

        var destinationLocation2 = $('body > .cont > .data > .main > div > .content');
        destinationLocation2.append(newFieldsetStructureCopy);

        fieldsetCount++;

    } else {
        console.log('Cloned section not found');
    }
});



// the end of the new fieldset script //


// the start of the new table script //

$(document).on('click', 'body > .cont > .data > .main > div > .content > fieldset > .btn-out > .btn.min.categorie-add > a', function () {
    console.log('Button clicked');

    var clonedTitleSection = $(this).closest('fieldset').find('.heart > .title.hide');
    var newTableStructure = $(this).closest('fieldset').find('.heart > table.show');

    if (clonedTitleSection.length > 0 && newTableStructure.length > 0) {
        var clonedTitleSectionCopy = clonedTitleSection.clone();
        var newTableStructureCopy = newTableStructure.clone();
        newTableStructureCopy.removeClass('show');
        clonedTitleSectionCopy.removeClass('hide');

        var destinationLocation = $(this).closest('fieldset').find('.heart');
        destinationLocation.append(clonedTitleSectionCopy);
        destinationLocation.append(newTableStructureCopy);
    } else {
        console.log('Cloned section not found');
    }
});

// the end of the new table script //


// the start of removing a specific table //

$(document).on('click', 'body > .cont > .data > .main > div > .content > fieldset > .heart > .title > .operation-remove > .btn.operation > a', function (event) {
    event.preventDefault();
    console.log('remove button got clicked');

    var title = $(this).closest('.title');
    var table = $(this).closest('.title').next('table');

    title.remove();
    table.remove();
});
// the end of removing a specific table //


// the start of removing a specific row //

$(document).on('click', 'body > .cont > .data > .main > div > .content > fieldset > .heart > table > tbody > tr > td > .btn.min.operation-delete > a', function (event) {
    event.preventDefault();
    console.log('delete button got clicked');

    var row = $(this).closest('tr');
    row.remove();
});

// the end of removing a specific row //


// the start of removing a specific fieldset //

$(document).on('click', 'body > .cont > .data > .main > div > .content > fieldset > .legend3 > .btn.min.categorie-remove > a', function (event) {
    event.preventDefault();
    console.log('delete fieldset button got clicked');
    var row = $(this).closest('fieldset');
    row.remove();
});

// the end of removing a specific fieldset //



// the start of the textarea script //
function autoResize(textarea) {
    textarea.style.height = '18px';
    textarea.style.height = textarea.scrollHeight + 'px';
  }

$(document).on('input', 'body > .cont > .data > .main > div > .content > fieldset > .heart > table > tbody > tr > td > .textarea.textarea-container > .data > textarea', function () {
    autoResize(this);
});
// the end of the textarea script //




// the start of fill in the comment //
$(document).on('click', 'body > .cont > .data > .main > div > .content > fieldset > .heart > table > tbody > tr > td > .btn.min.operation > a' ,function() {
    var $container = $(this).closest('body > .cont > .data > .main > div > .content > fieldset > .heart > table > tbody > tr');
    var content = $container.find('.titre').text();

    var textarea = $(this).closest('table').find('tbody > tr > td > .textarea.textarea-container > .data > textarea');

    if (textarea.val() === '') {
    textarea.val(content);
    } else {
    textarea.val(textarea.val() + '\n' + '\n' + content);
    }
    autoResize(textarea[0]);

    $('body').on('input propertychange', 'textarea', function () {
        autoResize(this);
    });
});
// the end of fill in the comment //



$(document).on('click', 'body .cont .data .main div .content fieldset .heart table tbody tr th .btn.min.action a', function() {
    console.log('hello there');
    var $table = $(this).closest('table');
    var rows = $table.find('tbody > tr').get();
    rows.sort(function(a, b) {
      var keyA = new Date($(a).children('td').eq(1).text());
      var keyB = new Date($(b).children('td').eq(1).text());

      if (keyA < keyB) return 1;
      if (keyA > keyB) return -1;
      return 0;
    });

    $.each(rows, function(index, row) {
      $table.find('tbody').append(row);
    });

  });


// the start of the dynamically added field select //

    $(document).on('click', 'body .cont .data .main div .content fieldset legend .select.selection_facture_list .data a', function (event) {
        formSelectInit($(event.target).parents(".select"));
    });

    $(document).on('input', 'body .cont .data .main div .content fieldset legend .select.selection_facture_list .data .list .input.filter .data input', function (event) {
        sortColSelectFilter($(event.target).parents(".select"));
    });

    $(document).on('click', 'body .cont .data .main div .content fieldset legend .select.selection_facture_list .data .list .option a', function (event) {
        sortColSelectOption($(event.target).parents(".option"));
    });
// the start of the dynamically added field select //


$(document).ready(function () {
    $("body .cont .data .main div .top .first-line .select.selection_facture_list .data a").off("click").on("click", function (event) {
        formSelectInit($(event.target).parents(".select"));
    });
    $("body .cont .data .main div .top .first-line .select.selection_facture_list .data .list .input.filter .data input").off("input").on("input", function (event) {
        sortColSelectFilter($(event.target).parents(".select"));
    });
    $("body .cont .data .main div .top .first-line .select.selection_facture_list .data .list .option a").off("click").on("click", function (event) {
        sortColSelectOption($(event.target).parents(".option"));
    });

    $("body > .cont > .data > .main > div > .top > .first-line > .checkbox.bool > .data > .list > .option > a").off("click").on("click", function (event) { sortDirCheckboxUnique($(event.target).parents(".option")); });

});
