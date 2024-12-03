// the start of checking specific lines //
$(document).ready(function () {
    let isSorted = false;
  $("#cont > div > .field > table > thead > tr > .date").each(function () {
    $(this).on("click", function (event) {
      handleDateSorting($($(this).closest("table")), isSorted);
      isSorted = !isSorted;
    });
  });
  $(".checkbox .data .list")
    .children()
    .each(function (option) {
      $(this).on("click", function () {
        formCheckboxUnique($(this));
      });
    });
  const tableClasses = [
    "fieldset1",
    "fieldset2",
    "fieldset3",
    "fieldset4",
    "fieldset5",
  ];
  // the start of checking a specific prest line //
  function processButtonClickPrest(button) {
    const buttonTr = button.closest("tr");

    const number = button.data("prest");

    const rows = buttonTr.parent().find("tr");
    rows.each(function () {
      const row = $(this);
      const prestCode = row.find(".centered-td.prest-column span").text();

      if (prestCode == number) {
        const secondButton = row.find(".btn.min.first-check");
        const iconTd = secondButton.find("i");
        iconTd.toggleClass("clicked");

        if (iconTd.hasClass("clicked")) {
          iconTd.removeClass("fa-circle");
          iconTd.addClass("fa-circle-check");
          iconTd.css("color", "var(--dark)");
          iconTd.css("background-color", "var(--light)");
          iconTd.css("font-size", "16px");
        } else {
          iconTd.removeClass("fa-circle-check");
        }
      }
    });
  }

  tableClasses.forEach((tableClass) => {
    const container = $(
      "body > .cont > .data > .main > div > fieldset." +
        tableClass +
        " > .customers"
    );

    container.on(
      "click",
      "tbody .total-row-prest td .btn.min.first-check a",
      function (event) {
        console.log("hello");
        event.preventDefault();
        processButtonClickPrest($(this));
      }
    );
  });

  // the end of checking a specific prest line //

  // the start of checking a specific collab line //

  function processButtonClickCollab(button) {
    const buttonTr = button.closest("tr");

    const number = button.data("collab");

    const rows = buttonTr.parent().find("tr");
    rows.each(function () {
      const row = $(this);
      const prestCode = row.find(".centered-td.code-row a").text();

      if (prestCode == number) {
        const secondButton = row.find(".btn.min.first-check");
        const iconTd = secondButton.find("i");
        iconTd.toggleClass("clicked");

        if (iconTd.hasClass("clicked")) {
          iconTd.removeClass("fa-circle");
          iconTd.addClass("fa-circle-check");
          iconTd.css("color", "var(--dark)");
          iconTd.css("background-color", "var(--light)");
          iconTd.css("font-size", "16px");
        } else {
          iconTd.removeClass("fa-circle-check");
        }
      }
    });
  }

  tableClasses.forEach((tableClass) => {
    const container = $(
      "body > .cont > .data > .main > div > fieldset." +
        tableClass +
        " > .customers"
    );

    container.on(
      "click",
      "tbody .total-row-collab td .btn.min.first-check a",
      function (event) {
        console.log("hello 2");
        event.preventDefault();
        processButtonClickCollab($(this));
      }
    );
  });

  // the end of checking a specific collab line //
});

/**
 * sorts each table factories by date
 * @param {HTMLElement} table
 * @param {Boolean} isSorted
 */
function handleDateSorting(table, isSorted) {
  let rws = table.children("tbody").children("tr");
  table.children("tbody").html("");
  rws.sort((a, b) => {
    const aDate = new Date(a.querySelector(".date").textContent);
    const bDate = new Date(b.querySelector(".date").textContent);
    return isSorted ? bDate - aDate : aDate - bDate;
  });
  rws.each(function(){table.children("tbody").append($(this))});

  
}

// the start of the checkboxes script //

$(document).ready(function () {
  const tableClasses = [
    "fieldset1",
    "fieldset2",
    "fieldset3",
    "fieldset4",
    "fieldset5",
  ];

  tableClasses.forEach((tableClass) => {
    const container = $(
      "body > .cont > .data > .main > div > fieldset." +
        tableClass +
        " > .customers"
    );

    container.on("click", "tbody tr th .btn a", function (event) {
      event.preventDefault();
      const iconTh = $(this).find("i");
      iconTh.toggleClass("clicked");

      const checkboxLinksfirst_check = container.find("tbody tr td .btn a");
      checkboxLinksfirst_check.each(function () {
        const iconTd = $(this).find("i");
        if (iconTh.hasClass("clicked")) {
          iconTd.addClass("clicked");
          iconTd.removeClass("fa-circle");
          iconTd.addClass("fa-circle-check");
          iconTd.css("color", "var(--dark)");
          iconTd.css("background-color", "var(--light)");
          iconTd.css("font-size", "16px");
        } else {
          iconTd.removeClass("clicked");
          iconTd.removeClass("fa-circle-check");
        }
      });

      if (iconTh.hasClass("clicked")) {
        iconTh.css("color", "var(--dark5)");
      } else {
        iconTh.css("color", "var(--dark)");
      }
    });

    container.on("click", "tbody tr td .btn a", function (event) {
      event.preventDefault();
      const iconTd = $(this).find("i");
      iconTd.toggleClass("clicked");

      if (iconTd.hasClass("clicked")) {
        iconTd.removeClass("fa-circle");
        iconTd.addClass("fa-circle-check");
        iconTd.css("color", "var(--dark)");
        iconTd.css("background-color", "var(--light)");
        iconTd.css("font-size", "16px");
      } else {
        iconTd.removeClass("fa-circle-check");
      }
    });
  });
});

// the end of the checkboxes script //

//the start of the input script//

function myFunction(element) {
  var span = element.querySelector("span");
  var input = element.querySelector("input");

  if (input.style.display === "none" || input.style.display === "") {
    input.style.display = "block";
    input.style.border = "1px solid #your-color-here";
    input.value = span.textContent.trim();
    input.focus();
    input.select();
    span.style.display = "none";
  } else {
    if (input.value.trim() !== "") {
      span.textContent = input.value;
    }
    input.style.display = "none";
    span.style.display = "block";
  }

  window.addEventListener("click", function (event) {
    if (!element.contains(event.target)) {
      if (input.value.trim() !== "") {
        span.textContent = input.value;
      }
      input.style.display = "none";
      span.style.display = "block";
    }
  });
}
//the end of the input script//

//the start of list select open script
function sortColSelectFilter(div) {
  formSelectFilter(div);
  $(div)
    .children(".data")
    .children(".list")
    .children(".option")
    .not(".off")
    .each(function () {
      if ($(this).css("display") == "none") return;
      var code = $(this).attr("code");
      if (code == undefined || code == null || code == "") return;
      var parent = $(this).attr("parent");
      if (parent == undefined || parent == null) parent = "";
    });
}

function sortColSelectOption(div) {
  formSelectOption(div);
  var sortCol = $(div).attr("code");
  if (sortCol == undefined || sortCol == null || sortCol == "")
    sortCol = "year";
  // Assuming you want to update the text in an element with class "selected-element"
  $(sortSelected).text(sortCol); // Update the text
  var obj = { index: { sortCol: sortCol } };
  cookieSave(obj, true);
}

function sortColAdapt() {
  var code = $(
    "body .cont .data .main div .all .left-div .select.sortAnalyse"
  ).attr("code");
  if (code == undefined || code == null) code = "";
  var option = $(
    "body .cont .data .main div .all .left-div .select.sortAnalyse  > .data > .list > .option[code='" +
      code +
      "']"
  );
  if (
    option == undefined ||
    option == null ||
    option == "" ||
    option.length == 0
  )
    return;
}

//the end of list select open script

// the starts of the collab sort //

function sortTable(tableId) {
  var table, rows, switching, i, shouldSwitch;
  table = document.getElementById(tableId);
  if (table === null) {
    console.log("Table not found with ID: " + tableId);
    return;
  }

  var prestRowsDelete = table.querySelectorAll(
    " tr > td > .btn.min.first-check > a > div > i"
  );

  prestRowsDelete.forEach(function (element) {
    element.classList.remove("clicked");
    element.classList.remove("fa-circle-check");
  });

  var prestRows = table.querySelectorAll(".total-row-prest");
  for (var i = 0; i < prestRows.length; i++) {
    table.deleteRow(prestRows[i].rowIndex);
  }

  switching = true;

  while (switching) {
    switching = false;
    rows = table.rows;

    for (i = 1; i < rows.length - 1; i++) {
      shouldSwitch = false;
      var x = rows[i].querySelector(".code-row").textContent;
      var y = rows[i + 1].querySelector(".code-row").textContent;

      if (x.toLowerCase() > y.toLowerCase()) {
        shouldSwitch = true;
        break;
      }
    }

    if (shouldSwitch) {
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
    }
  }

  var currentcollab = null;
  var collabTotals = {};
  var dureeTotals = {};
  var qntTotals = {};

  for (i = 1; i < rows.length; i++) {
    var collabCell = rows[i].querySelector(".code-row");
    var amountCell = rows[i].querySelector(".amount");
    var dureeCell = rows[i].querySelector(".duree");
    var qntCell = rows[i].querySelector(".qnt");

    if (collabCell && amountCell && dureeCell && qntCell) {
      var collabCode = collabCell.textContent.trim();
      var amount = parseFloat(amountCell.textContent.replace(",", ""));
      var duree = parseFloat(dureeCell.textContent);
      var qnt = parseInt(qntCell.textContent);

      if (!isNaN(amount)) {
        collabTotals[collabCode] = (collabTotals[collabCode] || 0) + amount;
      }

      if (!isNaN(duree)) {
        dureeTotals[collabCode] = (dureeTotals[collabCode] || 0) + duree;
      }

      if (!isNaN(qnt)) {
        qntTotals[collabCode] = (qntTotals[collabCode] || 0) + qnt;
      }
    }
  }

  for (i = rows.length - 1; i >= 1; i--) {
    var collab = rows[i].querySelector(".code-row").textContent;

    if (collab !== currentcollab) {
      currentcollab = collab;

      var newRow = table.insertRow(i + 1);

      newRow.classList.add("total-row-collab");

      var tdWithButton = document.createElement("td");

      tdWithButton.innerHTML = formBtn({
        key: "first-check",
        ico: "fa-circle",
      });
      tdWithButton.querySelector("a").setAttribute("data-collab", collab);

      newRow.appendChild(tdWithButton);

      var textCell = newRow.insertCell(1);
      textCell.textContent = "Cocher tout : " + collab;
      textCell.setAttribute("colspan", "5");

      var totalQntCell = newRow.insertCell(2);
      totalQntCell.textContent = qntTotals[collab];

      var totalDureeCell = newRow.insertCell(3);
      totalDureeCell.textContent = dureeTotals[collab];

      var totalAmountCell = newRow.insertCell(4);
      totalAmountCell.textContent = collabTotals[collab];
    }
  }
}

// the end of the collab sort //

// the starts of the prest sort //

function sortTableByPrest(tableId) {
  var table, rows, switching, i, shouldSwitch;
  table = document.getElementById(tableId);
  if (table === null) {
    console.log("Table not found with ID: " + tableId);
    return;
  }

  var prestRowsDelete = table.querySelectorAll(
    " tr > td > .btn.min.first-check > a > div > i"
  );

  prestRowsDelete.forEach(function (element) {
    element.classList.remove("clicked");
    element.classList.remove("fa-circle-check");
  });

  var prestRows = table.querySelectorAll(".total-row-collab");
  for (var i = 0; i < prestRows.length; i++) {
    table.deleteRow(prestRows[i].rowIndex);
  }

  switching = true;

  while (switching) {
    switching = false;
    rows = table.rows;

    for (i = 1; i < rows.length - 1; i++) {
      shouldSwitch = false;
      var x = rows[i].querySelector(".prest-column").textContent;
      var y = rows[i + 1].querySelector(".prest-column").textContent;

      if (x.toLowerCase() > y.toLowerCase()) {
        shouldSwitch = true;
        break;
      }
    }

    if (shouldSwitch) {
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
    }
  }

  var currentPrest = null;
  var prestTotals = {};
  var dureeTotals = {};
  var qntTotals = {};

  for (i = 1; i < rows.length; i++) {
    var prestCell = rows[i].querySelector(".prest-column");
    var amountCell = rows[i].querySelector(".amount");
    var dureeCell = rows[i].querySelector(".duree");
    var qntCell = rows[i].querySelector(".qnt");

    if (prestCell && amountCell && dureeCell && qntCell) {
      var prestCode = prestCell.textContent.trim();
      var amount = parseFloat(amountCell.textContent.replace(",", ""));
      var duree = parseFloat(dureeCell.textContent);
      var qnt = parseInt(qntCell.textContent);

      if (!isNaN(amount)) {
        prestTotals[prestCode] = (prestTotals[prestCode] || 0) + amount;
      }

      if (!isNaN(duree)) {
        dureeTotals[prestCode] = (dureeTotals[prestCode] || 0) + duree;
      }

      if (!isNaN(qnt)) {
        qntTotals[prestCode] = (qntTotals[prestCode] || 0) + qnt;
      }
    }
  }

  for (i = rows.length - 1; i >= 1; i--) {
    var prest = rows[i].querySelector(".prest-column").textContent;

    if (prest !== currentPrest) {
      currentPrest = prest;

      var newRow = table.insertRow(i + 1);

      newRow.classList.add("total-row-prest");

      var tdWithButton = document.createElement("td");

      tdWithButton.innerHTML = formBtn({
        key: "first-check",
        ico: "fa-circle",
      });
      tdWithButton.querySelector("a").setAttribute("data-prest", prest);

      newRow.appendChild(tdWithButton);

      var textCell = newRow.insertCell(1);
      textCell.textContent = "Cocher tout : " + prest;
      textCell.setAttribute("colspan", "5");

      var totalQntCell = newRow.insertCell(2);
      totalQntCell.textContent = qntTotals[prest];

      var totalDureeCell = newRow.insertCell(3);
      totalDureeCell.textContent = dureeTotals[prest];

      var totalAmountCell = newRow.insertCell(4);
      totalAmountCell.textContent = prestTotals[prest];
    }
  }
}

// the end of the prest sort //

$(document).ready(function () {
  // the start of the affiche exep //

  $(
    "body > .cont > .data > .main > div > .all > .right-div > .affiche-exep > a"
  ).on("click", function () {
    var targetElements = $(
      "body > .cont > .data > .main > div > fieldset > .customers > tbody > tr"
    );
    var button = $(this); // The button element

    if (targetElements.hasClass("hidden")) {
      targetElements.removeClass("hidden");
      button.addClass("clicked");
    } else {
      targetElements.each(function () {
        var prestCode = $(this).find("td > p > span").text();

        if (prestCode.trim().startsWith("@")) {
          $(this).addClass("hidden");
        }
      });
      // Change button styles to clicked
      button.removeClass("clicked");
    }
  });

  // the end of the affiche exep //

  sortColAdapt();
  $("body .cont .data .main div .all .left-div .select.sortAnalyse .data a")
    .off("click")
    .on("click", function (event) {
      formSelectInit($(event.target).parents(".select"));
    });
  $(
    "body .cont .data .main div .all .left-div .select.sortAnalyse .data .list .input.filter .data input"
  )
    .off("input")
    .on("input", function (event) {
      sortColSelectFilter($(event.target).parents(".select"));
    });
  $(
    "body .cont .data .main div .all .left-div .select.sortAnalyse .data .list .option a"
  )
    .off("click")
    .on("click", function (event) {
      sortColSelectOption($(event.target).parents(".option"));
    });
});


//  the script of checkead and not cheacked

$(document).on('click', '.first-check', function () {
  $(this.firstChild.firstChild.firstChild).toggleClass('fa-check');
});

// 
