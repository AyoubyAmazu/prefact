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
  delet_empty_table();
  empty_message();
  ajouter_fact();
  button_affiche_fact();
  unfactTmps();

  // show special
  //  console.log($("#cont > div > .all > .right-div > .affiche-exep > a"))
  $("#cont > div > .all > .right-div > .affiche-exep > a").on("click", function () {
    filterSpecial();
  });
  $(".select").on("click",function(){selectAdapt($(this));});
  
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
        event.preventDefault();
        processButtonClickCollab($(this));
      }
    );
  });
  $("#cont > div > .all > .left-div > div > .select > .data > .list > .option")
  .on("click", function(){/*selectFact($(this).attr("code"))*/})
  // on click of affiche facture
  $("#cont > div > .all > .left-div > .btn.affiche_pre_facture").on("click", function () {
    afficheFact();
  });
});
/**
 * navigate to affiche fact page
 */
function afficheFact()
{
  let d = new URLSearchParams(window.location.search).get('d');
  $("#cont > div > .all > .left-div > .btn.affiche_pre_facture > a").on("click", function(){
    if($(this).closest(".btn.affiche_pre_facture").hasClass("readonly")) return;
    $(".popup.facts-check.hide > div > .checkbox > .data > .list > .option[code=nouvelle_facture]").css({display: "none"});
    $(".popup.facts-check.hide").removeClass("hide");
    $(".popup.facts-check > div > .op > .btn.cancel > a").on("click", function(){
      $(".popup.facts-check").addClass("hide");
      $(".popup.facts-check.hide > div > .checkbox > .data > .list > .option[code=nouvelle_facture]").css({display: "flex"});
    });
    $(".popup.facts-check > div > .op > .btn.save > a").on("click", function(){
      let d = new URLSearchParams(window.location.search).get('d');
      let f =  $(this).closest(".op").parent().find(".checkbox").find(".data > .list > .option.on").attr("code");
      window.location.href =`./affiche_fact.php?d=${d}&f=${f}`;

    });
  });
}
/**
 * sorts each table factories by date
 * @return {void}
 */
function filterSpecial() {
  $("#cont > div > .field > table").each(function () {
  let specials = [];
    $(this).find("tbody > .rw ").each(function (inex) {
      if ($(this).find(".prest-column > .prest_code > .data > input").val()[0] === "@")
        specials.push($(this));
    })
    if (specials.length < 1) {
      $(this).parent().remove();
      return
    }
    let tbody = $(this).find("tbody");
    tbody.empty();
    specials.forEach((item) => {
      tbody.append(item)
    })
  });
}

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
  var obj = {index: {sortCol: sortCol}};
  cookieSave(obj, true);
}

function sortColAdapt() {
  let code = $(
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
              .toggleClass("fa-check");
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
  ).on("click", function (event) {
      sortColSelectOption($(event.target).parents(".option"));
    });
    $("tr > td >.first-check > a").on("click" , function () {
      $(this.firstChild.firstChild).toggleClass("fa-check");
    });
});
// cheack all the cheacked
$(document).on("click", "thead .first-check", function () {
  $(this)
    .closest("table")
    .find("tbody .first-check")
    .find(".fa-solid")
    .toggleClass("fa-check");
});

/**
 *  sort table by prest
 */ 
function sortPrest(tableId) {
  let table = $("#" + tableId);
  let originalRows = table.html(); // Save the original table rows
  const $table = table;
  const $tbody = $table.find("tbody");
  const columnIndex = $table.find(".prest-header").index();

  // Check if the table is already grouped
  if ($tbody.find(".total-row-collab").length > 0) {
    // Reset to original table
    $tbody.html(originalRows);
    return;
  }

  const rows = $table.find("tbody tr").toArray(); // Get all rows in the tbody
  const groupedRows = {};

  // Group rows based on the clicked column's value
  rows.forEach((row) => {
    const cellValue = $(row).find(`td:eq(${columnIndex}) input`).val();
    if (!groupedRows[cellValue]) groupedRows[cellValue] = [];
    groupedRows[cellValue].push(row);
  });

  // Clear table body
  $tbody.empty();

  // Loop through each group and append rows to the table
  for (const group in groupedRows) {
    // Append rows belonging to the group
    groupedRows[group].forEach((row) => $tbody.append(row));

    // Calculate the total for the "Total_PV" column
    const total_pv = groupedRows[group].reduce((sum, row) => {
      const value = parseFloat($(row).find("td:last").text()) || 0;
      return sum + value;
    }, 0);

    // Calculate the total for the "Total_duree" column
    const total_duree = groupedRows[group].reduce((sum, row) => {
      const value = parseFloat($(row).find("td:eq(6)").text()) || 0;
      return sum + value;
    }, 0);

    // Calculate the total for the "Total_Qte" column
    const total_qte = groupedRows[group].reduce((sum, row) => {
      const value = parseFloat($(row).find("td:eq(7)").text()) || 0;
      return sum + value;
    }, 0);

    // Add a total row for the group
    $tbody.append(`
        <tr class="total-row-collab">
          <td>
            <div class="btn min first-check">
              <a data-collab="${group}">
                <div class="ico">
                  <i class="fa-solid fa-fa-circle"></i>
                </div>
              </a>
            </div>
          </td>
          <td colspan="5">Cocher tout :  ${group}</td>
          <td>${total_duree}</td>
          <td>${total_qte}</td>
          <td>${total_pv}</td>
        </tr>
        `);
  }
  handleCheckGroupPrest()
}
/**
 * Handels select temps group button 
 */
function handleCheckGroupPrest() {
  $("table > tbody > tr.total-row-collab > td > div > a").each(function () {
    // $(this).on("click",function(){console.log("done");})
    $(this).on("click", function () {
      $(this).find(".ico > i").toggleClass("fa-check");
      let code = $(this).attr("data-collab");
      $(this)
        .closest("tbody")
        .find(".rw > .prest-column > div > div > input")
        .each(function () {
          if ($(this).attr("value") === code) {
            $(this)
              .closest(".rw")
              .find("td > .first-check > a > .ico > i")
              .toggleClass("fa-check");
          }
        });
    });
  });
}
/**
 * Deletes fields that are empty of temps
 */
function delet_empty_table() {
  $("#cont > div > .field > table").each(function () {
    if ($(this).find("tbody > tr").length === 0) {
      $(this).parent().remove();
    }
    
  });
}
/**
 * if there is temps fieldset a message is desplayed
 */
function empty_message(){
      // Check if there are any tables left
      if ($("#cont > div > .field > table").length === 0) {
        // Display a message or hide a specific element
        $("#cont > div").append("<p>No tables available.</p>"); // Example: Display a message
        // or
        // $("#cont > div").hide(); // Example: Hide the container
    }
}
/**
 * send request to the server with data to handle facture creating
 */
function ajouter_fact(){
  $("#cont > div > .all > .left-div > .Ajouter-facture > a").on("click", function () {
    let checked = $("table > tbody > tr > td > div > a > div > .fa-check");
    let table = new URLSearchParams(window.location.search).get("t");
    let code_dossier=new URLSearchParams(window.location.search).get("d");
    let temps ={};
    if(table){
      temps = [];
      checked.each(function(){temps.push($(this).closest("tr").attr("rw-id"))});
      $.ajax({
        url: "./resultat.php?d="+code_dossier+`&t=${table}`,
        type: "POST",
        data: {"temps": temps}
        , beforeSend: function() { loaderShow(); }
        , complete: function() { loaderHide(); }
        , success: function(data)
        {
            try { var result = JSON.parse(data); } catch(error) { popError(); return; }
            if(result["code"] == 200) {
              let fact = new URLSearchParams(document.referrer).get("f");
              window.location.href = `./affiche_fact.php?d=${code_dossier}&f=${fact}`;
              return; 
            }
            popError(result["txt"], result["btn"]);
        }
      });
      return;
    }
    if(checked.length < 1)$(".popup.facts-check > div > .op > .btn.save").addClass("readonly");
    $(".popup.facts-check.hide").removeClass("hide");
    $(".popup.facts-check > div > .op > .btn.cancel > a").on("click", function(){
      $(".popup.facts-check").addClass("hide");
      $(".popup.facts-check > div > .op > .btn.save").removeClass("readonly");
    });
    $(".popup.facts-check > div > .op > .btn.save > a").on("click", function(){
      if($(this).closest(".btn.save").hasClass("readonly"))return;
      checked.each(function() {
        let rwId = $(this).closest("tr").attr("rw-id");
        let catId = $(this).closest("table").attr("id");
        if(catId in temps)temps[catId].push(rwId);
        else temps[catId] = [rwId]; 
      });
      let fact_id = $(this).closest(".op").parent().find(".checkbox").find(".data > .list > .option.on").attr("code");    
      $.ajax({
        url: "ajouter_facture.php?d="+code_dossier,
        type: "POST",
        data: {
      "fact_id": fact_id,
      "temps": temps,
      }
        , beforeSend: function() { loaderShow(); }
        , complete: function() { loaderHide(); }
        , success: function(data)
        {
            try { var result = JSON.parse(data); } catch(error) { popError(); return; }
            if(result["code"] == 200) { window.location.href = "affiche_fact.php?d="+code_dossier+"&f="+result['id_fact']; return; }
            popError(result["txt"], result["btn"]);
        }
      })
    });
})
}
/**
 * send to server with data to handle unfacting temps
 */
function unfactTmps()
{
  $("#cont > div > .all > .left-div > .btn.unfact > a").on("click", function(){
    let checked = $("table > tbody > tr > td > div > a > div > .fa-check");
    if(checked.length < 1)return;
    temps =[] 
    checked.each(function() {let rwId = $(this).closest("tr").attr("rw-id");if (rwId) temps.push(rwId);});
    $.ajax({
    url: "ajouter_facture.php",
    type: "POST",
    data: {
    "fact_id": "unfact",
    "temps": temps,
    }
    , beforeSend: function() { loaderShow(); }
    , complete: function() { loaderHide(); }
    , success: function(data)
    {
        try { var result = JSON.parse(data); } catch(error) { popError(); return; }
        if(result["code"] == 200) { location.reload();return; }
        popError(result["txt"], result["btn"]);
    }
    })
  });
}
/**
 * 
 */
function button_affiche_fact(){
  let facts = $(".popup.facts-check.hide > div > .checkbox > .data > .list > .option");
  if(facts.length < 2) $("#cont > div > div > .left-div > .btn.affiche_pre_facture").addClass("readonly");
}