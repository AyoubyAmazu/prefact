// the start of checking specific lines //
$(document).ready(function () {
  // date sorting
  let isSorted = false;
  $("#cont > div > .field > table > thead > tr > .date").each(function () {
    $(this).on("click", function (event) {
      handleDateSorting($($(this).closest("table")), isSorted);
      isSorted = !isSorted;
    });
  });
  // group collab
  $("#cont > div > .field > table > thead > tr > .second-2.collab-header").each(
    function () {
      $(this).on("click", function () {
        groupCollab($(this).closest("table"));
      });
    }
  );

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
  rws.each(function () {
    table.children("tbody").append($(this));
  });
}

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

/**
 * group each table facts by collab
 * @param {HTMLElement} table
 */
function groupCollab(table) {
  let i;

  // let table = $("#"+tableId);
  table.find("tr > td > .btn.min.first-check > a > div > i").each(function () {
    $(this).removeClass("fa-check");
  });
  table.find(".total-row-collab").each(function () {
    table[0].deleteRow($(this[0]));
  });
  let rows = table.find("tr");
  table.find("tbody").html("");
  rows.sort((a, b) => {
    const acode = $(a).find(".code-row > a").text().toLowerCase();
    const bcode = $(b).find(".code-row > a").text().toLowerCase();
    return acode.localeCompare(bcode);
  });
  rows.each(function () {
    table.find("tbody").append($(this));
  });

  let currentcollab = null;
  let collabTotals = {};
  let dureeTotals = {};
  let qntTotals = {};

  rows.each(function () {
    let collabCell = $(this).find(".code-row");
    let amountCell = $(this).find(".amount");
    let dureeCell = $(this).find(".duree");
    let qntCell = $(this).find(".qnt");

    if (collabCell && amountCell && dureeCell && qntCell) {
      let collabCode = collabCell.text().trim();
      let amount = parseFloat(amountCell.text().replace(",", ""));
      let duree = parseFloat(dureeCell.text());
      let qnt = parseInt(qntCell.text());

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
  });

  for (i = rows.length - 1; i >= 1; i--) {
    let collab = rows[i].querySelector(".code-row").textContent;

    if (collab !== currentcollab) {
      currentcollab = collab;

      let newRow = table[0].insertRow(i + 1);

      newRow.classList.add("total-row-collab");

      let tdWithButton = document.createElement("td");

      tdWithButton.innerHTML = formBtn({
        key: "first-check",
        ico: "fa-circle",
      });
      tdWithButton.querySelector("a").setAttribute("data-collab", collab);

      newRow.appendChild(tdWithButton);

      let textCell = newRow.insertCell(1);
      textCell.textContent = "Cocher tout : " + collab;
      textCell.setAttribute("colspan", "5");

      let totalQntCell = newRow.insertCell(2);
      totalQntCell.textContent = qntTotals[collab];

      let totalDureeCell = newRow.insertCell(3);
      totalDureeCell.textContent = dureeTotals[collab];

      let totalAmountCell = newRow.insertCell(4);
      totalAmountCell.textContent = collabTotals[collab];
    }
  }
  handleCheckGroup();
}
/**
 * checks all factories related when checking the grouping row
 */
function handleCheckGroup() {
  $("table > tbody > tr.total-row-collab > td > div > a").each(function () {
    $(this).on("click", function () {
      let code = $(this).attr("data-collab");
      $(this)
        .closest("tbody")
        .find(".rw > .code-row > a")
        .each(function () {
          if ($(this).text() === code) {
            $(this)
              .closest(".rw")
              .find("td > .first-check > a > .ico > i")
              .addClass("fa-check");
          }
        });
    });
  });
}

function sortTableByPrest(tableId) {
  let table, rows, switching, i, shouldSwitch;
  table = document.getElementById(tableId);
  if (table === null) {
    console.log("Table not found with ID: " + tableId);
    return;
  }

  let prestRowsDelete = table.querySelectorAll(
    " tr > td > .btn.min.first-check > a > div > i"
  );

  prestRowsDelete.forEach(function (element) {
    element.classList.remove("clicked");
    element.classList.remove("fa-circle-check");
  });

  let prestRows = table.querySelectorAll(".total-row-collab");
  for (let i = 0; i < prestRows.length; i++) {
    table.deleteRow(prestRows[i].rowIndex);
  }

  switching = true;

  while (switching) {
    switching = false;
    rows = table.rows;

    for (i = 1; i < rows.length - 1; i++) {
      shouldSwitch = false;
      let x = rows[i].querySelector(".prest-column").textContent;
      let y = rows[i + 1].querySelector(".prest-column").textContent;

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

  let currentPrest = null;
  let prestTotals = {};
  let dureeTotals = {};
  let qntTotals = {};

  for (i = 1; i < rows.length; i++) {
    let prestCell = rows[i].querySelector(".prest-column");
    let amountCell = rows[i].querySelector(".amount");
    let dureeCell = rows[i].querySelector(".duree");
    let qntCell = rows[i].querySelector(".qnt");

    if (prestCell && amountCell && dureeCell && qntCell) {
      let prestCode = prestCell.textContent.trim();
      let amount = parseFloat(amountCell.textContent.replace(",", ""));
      let duree = parseFloat(dureeCell.textContent);
      let qnt = parseInt(qntCell.textContent);

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
    let prest = rows[i].querySelector(".prest-column").textContent;

    if (prest !== currentPrest) {
      currentPrest = prest;

      let newRow = table.insertRow(i + 1);

      newRow.classList.add("total-row-prest");

      let tdWithButton = document.createElement("td");

      tdWithButton.innerHTML = formBtn({
        key: "first-check",
        ico: "fa-circle",
      });
      tdWithButton.querySelector("a").setAttribute("data-prest", prest);

      newRow.appendChild(tdWithButton);

      let textCell = newRow.insertCell(1);
      textCell.textContent = "Cocher tout : " + prest;
      textCell.setAttribute("colspan", "5");

      let totalQntCell = newRow.insertCell(2);
      totalQntCell.textContent = qntTotals[prest];

      let totalDureeCell = newRow.insertCell(3);
      totalDureeCell.textContent = dureeTotals[prest];

      let totalAmountCell = newRow.insertCell(4);
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
    let targetElements = $(
      "body > .cont > .data > .main > div > fieldset > .customers > tbody > tr"
    );
    let button = $(this); // The button element

    if (targetElements.hasClass("hidden")) {
      targetElements.removeClass("hidden");
      button.addClass("clicked");
    } else {
      targetElements.each(function () {
        let prestCode = $(this).find("td > p > span").text();

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

$(document).on("click", ".first-check", function () {
  $(this.firstChild.firstChild.firstChild).toggleClass("fa-check");
});

//
