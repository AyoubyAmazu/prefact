$(document).ready(function () {
  $("body > #header").after("<div id='title'> <div> <div class='op l'> <div class='btn min refresh'> <a title='Rafraichir les données manuelement' ><div class='ico'><i class='fa-solid fa-rotate'></i></div ></a> </div> </div> <div class='main'> <div class='adr'> <div class='txt' title='Nom du dossier : Sample Text 21'> Sample Text 21 </div> <div class='code' title='Code du dossier : 20249'>20249</div> <div class='sep'>-</div> <div class='select grp' code=''> <div class='data'> <a title='Groupe : Group 21' ><div class='main'> <div class='txt'>Group 21</div> <div class='code'></div> </div> <div class='ico'><i class='fa-solid fa-angle-down'></i></div ></a> <div class='list'> <div class='input'> <div class='data'> <input type='text' title='Rechercher...' placeholder='Rechercher...' /> </div> </div> <div class='option' code='20249'> <a title='Sample Text 21' href='http://prefact.fr/resultat.php?d=21' ><div class='txt'>Sample Text 21</div> <div class='code'>20249</div></a > </div> </div> </div> </div> </div> <div class='op'> <div class='btn desc toggle' code='desc'> <a title='Description' ><div class='ico'><i class='fa-solid fa-angle-down'></i></div> <div class='txt'>Description</div></a > </div> <div class='btn resp toggle' code='resp'> <a title='Responsables' ><div class='ico'><i class='fa-solid fa-angle-down'></i></div> <div class='txt'>Responsables</div></a > </div> <div class='btn min solde'> <a title='Afficher le détail du solde : 4 000,00 €' target='_blank' ><div class='txt'><span>Solde :</span><b>4 000,00 €</b></div></a > </div> <div class='btn segment'> <a title='Modifier la segmentation' ><div class='ico'><i class='fa-solid fa-pencil'></i></div> <div class='txt'>Segmentation</div></a > </div> <div class='btn crm'> <a title='Ouvrir sur le CRM' target='_blank' ><div class='ico'> <i class='fa-solid fa-up-right-from-square'></i> </div> <div class='txt'>CRM</div></a > </div> </div> <div class='det off' code='desc'> <div class='display'> <div class='data'> <div class='txt' title='Description du dossier dans le CRM'> 123 </div> </div> </div> </div> <div class='det off' code='resp'> <div class='display rd'> <div class='label' title='Responsable Déontologique (RD) : Road 21 (RD21)' > RD </div> <div class='data'> <div class='txt' title='Responsable Déontologique (RD) : Road 21 (RD21)' > Road 21 </div> <div class='code'>RD21</div> </div> </div> <div class='display re'> <div class='label' title='Responsable Encadrement (RE) : Reader 21 (RE21)' > RE </div> <div class='data'> <div class='txt' title='Responsable Encadrement (RE) : Reader 21 (RE21)' > Reader 21 </div> <div class='code'>RE21</div> </div> </div> <div class='display rc'> <div class='label' title='Responsable Collaborateur (RC)'>RC</div> <div class='data'> <div class='txt' title='Responsable Collaborateur (RC)'></div> <div class='code'></div> </div> </div> <div class='display ra'> <div class='label' title='Responsable Auxiliaire (RA)'>RA</div> <div class='data'> <div class='txt' title='Responsable Auxiliaire (RA)'></div> <div class='code'></div> </div> </div> <div class='display res'> <div class='label' title='Responsable Encadrement Social (RES)'> RES </div> <div class='data'> <div class='txt' title='Responsable Encadrement Social (RES)' ></div> <div class='code'></div> </div> </div> <div class='display rs'> <div class='label' title='Responsable Social (RS)'>RS</div> <div class='data'> <div class='txt' title='Responsable Social (RS)'></div> <div class='code'></div> </div> </div> <div class='display rj'> <div class='label' title='Responsable Juridique (RJ)'>RJ</div> <div class='data'> <div class='txt' title='Responsable Juridique (RJ)'></div> <div class='code'></div> </div> </div> <div class='display rfp'> <div class='label' title='Responsable Fiscalité Personnel (RFP)'> RFP </div> <div class='data'> <div class='txt' title='Responsable Fiscalité Personnel (RFP)' ></div> <div class='code'></div> </div> </div> <div class='display tgr'> <div class='label' title='Responsable à Tanger (TGR)'>TGR</div> <div class='data'> <div class='txt' title='Responsable à Tanger (TGR)'></div> <div class='code'></div> </div> </div> <div class='display tgra'> <div class='label' title='Assistant à Tanger (TGRA)'>TGRA</div> <div class='data'> <div class='txt' title='Assistant à Tanger (TGRA)'></div> <div class='code'></div> </div> </div> </div> </div> <div class='op r'> <div class='btn min cancel'> <a title='Revenir à la liste des dossiers' ><div class='ico'><i class='fa-solid fa-xmark'></i></div ></a> </div> </div> </div> </div>");
  $(
    "body > #cont >  div .top .first-line .select.selection_facture_list .data a"
  )
    .off("click")
    .on("click", function (event) {
      formSelectInit($(event.target).parents(".select"));
    });
  $(
    "body #cont >  div .top .first-line .select.selection_facture_list .data .list .input.filter .data input"
  )
    .off("input")
    .on("input", function (event) {
      sortColSelectFilter($(event.target).parents(".select"));
    });
  $(
    "body #cont >  div .top .first-line .select.selection_facture_list .data .list .option a"
  )
    .off("click")
    .on("click", function (event) {
      sortColSelectOption($(event.target).parents(".option"));
    });

  $(
    "body > #cont >  div > .top > .first-line > .checkbox.bool > .data > .list > .option > a"
  ).on("click", function (event) {
    sortDirCheckboxUnique($(event.target).parents(".option"));
  });

  $(document).on(
    "click",
    "body #cont >  div .content fieldset legend .select.selection_facture_list .data a",
    function (event) {
      formSelectInit($(event.target).parents(".select"));
    }
  );

  $(document).on(
    "input",
    "body #cont >  div .content fieldset legend .select.selection_facture_list .data .list .input.filter .data input",
    function (event) {
      sortColSelectFilter($(event.target).parents(".select"));
    }
  );

  $(document).on(
    "click",
    "body #cont >  div .content fieldset legend .select.selection_facture_list .data .list .option a",
    function (event) {
      sortColSelectOption($(event.target).parents(".option"));
    }
  );
  $("#cont >  div > .content > fieldset > .heart > table > tbody > tr > td > .btn.min.operation-delete > a").on("click", function () {deletePres($(this).closest("tr"));});
  $("#cont >  div > .content > fieldset > .legend3 > .btn.min.categorie-remove > a").on("click",function () {deleteDetail($("#cont >  div > .content > fieldset"));});

    // enoyer validation
    $("#cont > div > .top > div > .envoyer-valid").on("click", function () {envoyer_validation();});


  });
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
  var sortDir = $(div).attr("code");
  if (sortDir == undefined || sortDir == null || sortDir == "") sortDir = "oui";
  var obj = { index: { sortDir: sortDir } };
  cookieSave(obj, true);
}
// the end of the checkbox script //

// the start of the new fieldset script //
var fieldsetCount = 1;
$(document).on(
  "click",
  "body > #cont >  div > .btn-last > .btn > a",
  function () {
    console.log("Button 2 clicked");

    var newFieldsetStructure = $(
      "body > #cont >  div > .content > fieldset.second-field"
    ).first();

    if (newFieldsetStructure.length > 0) {
      var newFieldsetStructureCopy = newFieldsetStructure.clone();

      newFieldsetStructureCopy.removeClass("second-field");
      newFieldsetStructureCopy.addClass("third-field-" + fieldsetCount);

      var destinationLocation2 = $("body > #cont >  div > .content");
      destinationLocation2.append(newFieldsetStructureCopy);

      fieldsetCount++;
    } else {
      console.log("Cloned section not found");
    }
  }
);

// the end of the new fieldset script //

// the start of the new table script //

$(document).on(
  "click",
  "body > #cont >  div > .content > fieldset > .btn-out > .btn.min.categorie-add > a",
  function () {
    var clonedTitleSection = $(this)
      .closest("fieldset")
      .find(".heart > .title.hide");
    var newTableStructure = $(this)
      .closest("fieldset")
      .find(".heart > table.show");

    if (clonedTitleSection.length > 0 && newTableStructure.length > 0) {
      var clonedTitleSectionCopy = clonedTitleSection.clone();
      var newTableStructureCopy = newTableStructure.clone();
      newTableStructureCopy.removeClass("show");
      clonedTitleSectionCopy.removeClass("hide");

      var destinationLocation = $(this).closest("fieldset").find(".heart");
      destinationLocation.append(clonedTitleSectionCopy);
      destinationLocation.append(newTableStructureCopy);
    }
  }
);

// the end of the new table script //

// the start of removing a specific table //

$(document).on(
  "click",
  "body > #cont >  div > .content > fieldset > .heart > .title > .operation-remove > .btn.operation > a",
  function (event) {
    event.preventDefault();
    console.log("remove button got clicked");

    var title = $(this).closest(".title");
    var table = $(this).closest(".title").next("table");

    title.remove();
    table.remove();
  }
);
// the end of removing a specific table //

// the start of the textarea script //
function autoResize(textarea) {
  textarea.style.height = "18px";
  textarea.style.height = textarea.scrollHeight + "px";
}

$(document).on(
  "input",
  "body > #cont >  div > .content > fieldset > .heart > table > tbody > tr > td > .textarea.textarea-container > .data > textarea",
  function () {
    autoResize(this);
  }
);
// the end of the textarea script //

// the start of fill in the comment //
$(document).on(
  "click",
  "body > #cont >  div > .content > fieldset > .heart > table > tbody > tr > td > .btn.min.operation > a",
  function () {
    var $container = $(this).closest(
      "body > #cont >  div > .content > fieldset > .heart > table > tbody > tr"
    );
    var content = $container.find(".titre").text();

    var textarea = $(this)
      .closest("table")
      .find(
        "tbody > tr > td > .textarea.textarea-container > .data > textarea"
      );

    if (textarea.val() === "") {
      textarea.val(content);
    } else {
      textarea.val(textarea.val() + "\n" + "\n" + content);
    }
    autoResize(textarea[0]);

    $("body").on("input propertychange", "textarea", function () {
      autoResize(this);
    });
  }
);
// the end of fill in the comment //

$(document).on(
  "click",
  "body #cont >  div .content fieldset .heart table tbody tr th .btn.min.action a",
  function () {
    var $table = $(this).closest("table");
    var rows = $table.find("tbody > tr").get();
    rows.sort(function (a, b) {
      var keyA = new Date($(a).children("td").eq(1).text());
      var keyB = new Date($(b).children("td").eq(1).text());

      if (keyA < keyB) return 1;
      if (keyA > keyB) return -1;
      return 0;
    });

    $.each(rows, function (index, row) {
      $table.find("tbody").append(row);
    });
  }
);

/**
 * sends ajax request with the id of prest row to be deleted
 */
function deletePres(row) {
  row.remove();

  // $.ajax({
  //   type: "POST",
  //   url: "./affiche_fact.php",
  //   data: { delete_prest: row.attr("id") },
  //   dataType: "json",
  //   beforeSend: function () {
  //     loaderShow();
  //   },
  //   complete: function () {
  //     loaderHide();
  //   },
  //   success: function (data) {
  //       console.log(data);
        
  //       if(data.success == 200){
  //           row.remove();
  //       }
  //       else popError("An error occurred while processing your request.");
  //   },
  //   error: function (jqXHR, textStatus, errorThrown) {
  //     console.error("AJAX Error: " + textStatus, errorThrown);
  //     popError("An error occurred while processing your request."); // Display a user-friendly error message
  //   },
  // });
}

/**
 * sends ajax request with the id of detail field to be deleted 
 */
function deleteDetail(field) {
  field.remove();

    // $.ajax({
    //   type: "POST",
    //   url: "./affiche_fact.php",
    //   data: { delete_detail: field.attr("id") },
    //   dataType: "json",
    //   beforeSend: function () {
    //     loaderShow();
    //   },
    //   complete: function () {
    //     loaderHide();
    //   },
    //   success: function (data) {
    //       console.log(data);
          
    //       if(data.success == 200){
    //           field.remove();
    //       }
    //       else popError("An error occurred while processing your request.");
    //   },
    //   error: function (jqXHR, textStatus, errorThrown) {
    //     console.error("AJAX Error: " + textStatus, errorThrown);
    //     popError("An error occurred while processing your request."); // Display a user-friendly error message
    //   },
    // });
  }


  function envoyer_validation(){
    let id = $("#cont > div > .content > .first-field ").attr("id");
    let location = $("#cont > div > .top > .first-line > .envoyer-valid > a").attr("href");
    
   $.ajax({
        url: "affiche_fact.php"
        ,type:"POST"
        ,data:{facture_id: "1"}
        , beforeSend: function() { loaderShow(); }
        , complete: function() { loaderHide(); }
        , success: function(data){
            try { var result = JSON.parse(data); }
            catch(error) { popError(); return; }
            if(result["code"] == 200) { popUp(result["html"]); window.location.href = location;  return; }
            popError(result["txt"], result["btn"]);
        }
    });
  }
