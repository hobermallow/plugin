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
  $(".row-store-mod").remove();
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
  $(row).append("<td class='column-columnname'><input type='text' name='label' value='"+label+"' ></td>")
  $(row).append("<td class='column-columnname'><input type='text' name='url' value='"+url+"' ></td>")
  $(row).append("<td class='column-columnname'><input type='text' name='starting_ip' value='"+starting_ip+"' ></td>")
  $(row).append("<td class='column-columnname'><input type='text' name='ending_ip' value='"+ending_ip+"' ></td>")
  $(row).append("<td class='column-columnname'><input type='button' class='button' value='Salva' name='Salva' onclick='modStore("+id+")' /></td>")
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
