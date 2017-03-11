function validateEmail(email) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}

function addUser() {
  var name = $('#name');
  var surname = $('#surname');
  var mail = $('#mail');
  var cf = $('#cf');
  var pwd = $("#pwd");
  var pwd_confirm = $("#pwd_confirm");
  var id_store = $("#id_store");

  var flag = true;

  if ($(name).val() == undefined || $(name).val() == "") {
      flag = false;
  }
  if ($(surname).val() == undefined || $(surname).val() == "") {
      flag = false;
  }
  if ($(mail).val() == undefined || $(mail).val() == "") {
      flag = false;
  }
  if ($(cf).val() == undefined || $(cf).val() == "") {
      flag = false;
  }
  if ($(pwd).val() == undefined || $(pwd).val() == "") {
      flag = false;
  }
  if ($(pwd_confirm).val() == undefined || $(pwd_confirm).val() == "") {
      flag = false;
  }
  if (!flag) {
      sweetAlert("Ops...", "Compila tutti i campi", "error");
      return false;
  }

  //controllo che le due password corrispondano
  if($(pwd).val() != $(pwd_confirm).val()) {
    sweetAlert("Ops...", "Le password inserite non corrispondono", "error");
    return false;
  }

  //controllo che la mail inserita sia valida
  if(!validateEmail($(mail).val())) {
    sweetAlert("Ops...", "Inserisci un indirizzo mail valido", "error");
    return false;
  }

  //controllo che la password contenga tutti i caratteri richiesti
  if(!$(pwd).val().match(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/)) {
    sweetAlert("Ops...", "Inserisci una password lunga almeno 8 caratteri contenente un carattere minuscolo, un carattere maiuscolo ed una cifra", "error");
    return false;
  }
  //altrimenti, post con la richiesta
  $.post(my_ajax_obj.ajax_url, { //POST request
          _ajax_nonce: my_ajax_obj.nonce, //nonce
          action: "buyg_add_user", //action
          name: $(name).val(),
          surname: $(surname).val(),
          mail: $(mail).val(),
          id_store: $(id_store).val(),
          cf: $(cf).val(),
          pwd: $(pwd).val() //data
      }, function(data) { //callback
          var obj = JSON.parse(data);
          if (obj.status == "0") {
              sweetAlert("Ops..", obj.msg, "error");
              return false;
          } else {
              swal({
                  title: "Ok!",
                  text: "Salvataggio effettuato con successo!",
                  type: "success",
                  showCancelButton: false,
                  confirmButtonColor: "#DD6B55",
                  confirmButtonText: "Ok",
                  preConfirm: function() {
                      return new Promise(function(resolve) {
                          setTimeout(function() {
                              resolve();
                          }, 1000);
                      });
                  }
              }).then(function() {
                window.location = obj.url+'';
              });

          }
      } //insert server response
  );
}

function prepareModUser(id) {
  //nascondo tutte le altre eventuali righe di modifica
  $("[class^=row-user-mod]").remove();
  $("tr[id^=record]").show();
  //recupero i dati dello store
  var userRow = $("#record_"+id);
  var name = $(userRow).find("td.col_user_name").html();
  var surname = $(userRow).find("td.col_user_surname").html();
  var mail = $(userRow).find("td.col_user_mail").html();
  var cf = $(userRow).find("td.col_user_cf").html();
  var magento_store = $(userRow).find("td.col_user_magento_store").text();
  var id_store = $(userRow).find("td.col_user_magento_store").find("input[name=id_store]").val();
  //creo la riga per il form
  row = document.createElement("tr");
  $(row).addClass("row-user-mod-"+id);
  $(row).append("<input name='id' value='"+id+"' type='hidden'>");
  $(row).append("<td></td>");
  $(row).append("<td></td>");
  $(row).append("<td class='column-columnname'><input type='text' name='name' value='"+name+"' ></td>");
  $(row).append("<td class='column-columnname'><input type='text' name='surname' value='"+surname+"' ></td>");
  $(row).append("<td class='column-columnname'><input type='text' name='mail' value='"+mail+"' ></td>");
  $(row).append("<td class='column-columnname'><input type='text' name='cf' value='"+cf+"' ></td>");
  $(row).append("<td class='column-columnname'><input type='hidden' name='id_store' value='"+id_store+"' /><input type='text' id='magento_store' name='magento_store' value='"+magento_store+"'/></td>");
  $(row).append("<td class='column-columnname'></td>");
  $(row).append("<td class='column-columnname'><input type='button' class='button' value='Annulla' onclick='$(this).closest(\"tr\").hide(); $(\"tr[id^=record]\").show() ' ><input type='button' class='button' value='Salva' name='Salva' onclick='modUser("+id+")' /></td>");
  $(row).find("input[name=magento_store]").autocomplete({
    appendTo: "#magento_store",
    source: function(request, response) {
      //recupero gli store disponibili
      $.post(my_ajax_obj.ajax_url, { //POST request
              _ajax_nonce: my_ajax_obj.nonce, //nonce
              action: "buyg_autocomplete_stores", //action
              term: request.term,
          }, function(data) {
              var dat = JSON.parse(data);
              response(dat);
          });
      },
      select: function(event, ui) {
        $("input[name=id_store]").val(ui.item.id);
      },
      change: function(event, ui) {
        if(ui.item == null) {
          $("input[name=id_store]").val("0");
        }
      }
  });
  $(userRow).after($(row));
  $(userRow).hide();
}

function modUser(id) {
    $(".has-error").removeClass("has-error");
    //recupero la riga
    var row =   $(".row-user-mod-"+id);
    //controllo che siano stati riempiti tutti i campi
    var name = $(row).find("input[name=label]");
    var surname = $(row).find("input[name=url]");
    var mail = $(row).find("input[name=starting_ip]");
    var cf = $(row).find("input[name=ending_ip]");
    var flag = true;

    if ($(label).val() == undefined || $(label).val() == "") {
        flag = false;
    }
    if ($(url).val() == undefined || $(url).val() == "") {
        flag = false;
    }
    if ($(starting_ip).val() == undefined || $(starting_ip).val() == "") {
        flag = false;
    }
    if ($(ending_ip).val() == undefined || $(ending_ip).val() == "") {
        flag = false;
    }
    if (!flag) {
        sweetAlert("Ops...", "Compila tutti i campi", "error");
        return false;
    }
    //altrimenti, post con la richiesta
    $.post(my_ajax_obj.ajax_url, { //POST request
            _ajax_nonce: my_ajax_obj.nonce, //nonce
            action: "buyg_mod_store", //action
            id: id,
            label: $(label).val(),
            url: $(url).val(),
            starting_ip: $(starting_ip).val(),
            ending_ip: $(ending_ip).val() //data
        }, function(data) { //callback
            var obj = JSON.parse(data);
            if (obj.status == "0") {
                sweetAlert("Ops..", obj.msg, "error");
                return false;
            } else {
                console.log(obj.url);
                swal({
                    title: "Ok!",
                    text: "Salvataggio effettuato con successo!",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ok",
                    preConfirm: function() {
                        return new Promise(function(resolve) {
                            setTimeout(function() {
                                resolve();
                            }, 1000);
                        });
                    }
                }).then(function() {
                  location.reload();
                });

            }
        } //insert server response
    );

}


function addStore() {
  var label = $('#label');
  var url = $('#url');
  var starting_ip = $('#starting_ip');
  var ending_ip = $('#ending_ip');

  var flag = true;

  if ($(label).val() == undefined || $(label).val() == "") {
      flag = false;
  }
  if ($(url).val() == undefined || $(url).val() == "") {
      flag = false;
  }
  if ($(starting_ip).val() == undefined || $(starting_ip).val() == "") {
      flag = false;
  }
  if ($(ending_ip).val() == undefined || $(ending_ip).val() == "") {
      flag = false;
  }
  if (!flag) {
      sweetAlert("Ops...", "Compila tutti i campi", "error");
      return false;
  }

  //altrimenti, post con la richiesta
  $.post(my_ajax_obj.ajax_url, { //POST request
          _ajax_nonce: my_ajax_obj.nonce, //nonce
          action: "buyg_add_store", //action
          label: $(label).val(),
          url: $(url).val(),
          starting_ip: $(starting_ip).val(),
          ending_ip: $(ending_ip).val() //data
      }, function(data) { //callback
          var obj = JSON.parse(data);
          if (obj.status == "0") {
              sweetAlert("Ops..", obj.msg, "error");
              return false;
          } else {
              swal({
                  title: "Ok!",
                  text: "Salvataggio effettuato con successo!",
                  type: "success",
                  showCancelButton: false,
                  confirmButtonColor: "#DD6B55",
                  confirmButtonText: "Ok",
                  preConfirm: function() {
                      return new Promise(function(resolve) {
                          setTimeout(function() {
                              resolve();
                          }, 1000);
                      });
                  }
              }).then(function() {
                window.location = obj.url+'';
              });

          }
      } //insert server response
  );
}

function modStore(id) {
    $(".has-error").removeClass("has-error");
    //recupero la riga
    var row =   $(".row-store-mod-"+id);
    //controllo che siano stati riempiti tutti i campi
    var label = $(row).find("input[name=label]");
    var url = $(row).find("input[name=url]");
    var starting_ip = $(row).find("input[name=starting_ip]");
    var ending_ip = $(row).find("input[name=ending_ip]");
    var flag = true;

    if ($(label).val() == undefined || $(label).val() == "") {
        flag = false;
    }
    if ($(url).val() == undefined || $(url).val() == "") {
        flag = false;
    }
    if ($(starting_ip).val() == undefined || $(starting_ip).val() == "") {
        flag = false;
    }
    if ($(ending_ip).val() == undefined || $(ending_ip).val() == "") {
        flag = false;
    }
    if (!flag) {
        sweetAlert("Ops...", "Compila tutti i campi", "error");
        return false;
    }
    //altrimenti, post con la richiesta
    $.post(my_ajax_obj.ajax_url, { //POST request
            _ajax_nonce: my_ajax_obj.nonce, //nonce
            action: "buyg_mod_store", //action
            id: id,
            label: $(label).val(),
            url: $(url).val(),
            starting_ip: $(starting_ip).val(),
            ending_ip: $(ending_ip).val() //data
        }, function(data) { //callback
            var obj = JSON.parse(data);
            if (obj.status == "0") {
                sweetAlert("Ops..", obj.msg, "error");
                return false;
            } else {
                console.log(obj.url);
                swal({
                    title: "Ok!",
                    text: "Salvataggio effettuato con successo!",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ok",
                    preConfirm: function() {
                        return new Promise(function(resolve) {
                            setTimeout(function() {
                                resolve();
                            }, 1000);
                        });
                    }
                }).then(function() {
                  location.reload();
                });

            }
        } //insert server response
    );

}

function delStore(id) {

    swal({
        title: "Attenzione !",
        text: "Sei sicuro di voler cancellare lo store? Tutti gli utenti legati a questo store non potranno piu' accedere al proprio negozio",
        type: "success",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Elimina",
        preConfirm: function() {
            return new Promise(function(resolve) {
                setTimeout(function() {
                    resolve();
                }, 2000);
            });
        }
    }).then(function(result) {
        //effettuo l'eliminazione
        $.post(my_ajax_obj.ajax_url, { //POST request
                _ajax_nonce: my_ajax_obj.nonce, //nonce
                action: "buyg_del_store", //action
                id: id,
            }, function(data) { //callback
                var obj = JSON.parse(data);
                if (obj.status == "0") {
                    sweetAlert("Ops..", obj.msg, "error");
                    return false;
                } else {
                    location.reload();
                }
            } //insert server response
        );
    }, function(dismiss) {

    });

}

function prepareModStore(id) {
  //nascondo tutte le altre eventuali righe di modifica
  $("[class^=row-store-mod]").remove();
  $("tr[id^=record]").show();
  //recupero i dati dello store
  var storeRow = $("#record_"+id);
  var label = $(storeRow).find("td.col_store_label").html();
  var url = $(storeRow).find("td.col_store_url").html();
  var starting_ip = $(storeRow).find("td.col_store_starting_ip").html();
  var ending_ip = $(storeRow).find("td.col_store_ending_ip").html();
  //creo la riga per il form
  row = document.createElement("tr");
  $(row).addClass("row-store-mod-"+id);
  $(row).append("<input name='id' value='"+id+"' type='hidden'>");
  $(row).append("<td></td>");
  $(row).append("<td></td>");
  $(row).append("<td class='column-columnname'><input type='text' name='label' value='"+label+"' ></td>");
  $(row).append("<td class='column-columnname'><input type='text' name='url' value='"+url+"' ></td>");
  $(row).append("<td class='column-columnname'><input type='text' placeholder='255.255.255.255' name='starting_ip' value='"+starting_ip+"' ></td>");
  $(row).append("<td class='column-columnname'><input type='text' placeholder='255.255.255.255' name='ending_ip' value='"+ending_ip+"' ></td>");
  $(row).append("<td class='column-columnname'></td>");
  $(row).append("<td class='column-columnname'><input type='button' class='button' value='Annulla' onclick='$(this).closest(\"tr\").hide(); $(\"tr[id^=record]\").show() ' ><input type='button' class='button' value='Salva' name='Salva' onclick='modStore("+id+")' /></td>");
  $(row).find("input[name=starting_ip]").mask('0ZZ.0ZZ.0ZZ.0ZZ', {
      translation: {
      'Z': {
        pattern: /[0-9]/, optional: true
      },
    }
  });
  $(row).find("input[name=ending_ip]").mask('0ZZ.0ZZ.0ZZ.0ZZ', {
      translation: {
      'Z': {
        pattern: /[0-9]/, optional: true
      },
    }
  });
  $(storeRow).after($(row));
  $(storeRow).hide();
}

function toggleStore(el) {
  var active = $(el).is(":checked");
  var id = $(el).val();
  $.post(my_ajax_obj.ajax_url, { //POST request
          _ajax_nonce: my_ajax_obj.nonce, //nonce
          action: "buyg_toggle_store", //action
          id: id,
          active : active ? 1 : 0
      }, function(data) { //callback
          var obj = JSON.parse(data);
          if (obj.status == "0") {
              sweetAlert("Ops..", obj.msg, "error");
              return false;
          } else {
              if(active) {
                $(el).prop("checked", true);
              }
              else {
                $(el).prop("checked", false);
              }
          }
      } //insert server response
  );
}
