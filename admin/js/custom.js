function modStore(id) {
    $(".has-error").removeClass("has-error");
    //controllo che siano stati riempiti tutti i campi
    var label = $("#label");
    var url = $("#url");
    var starting_ip = $("#starting_ip");
    var ending_ip = $("#ending_ip");
    var flag = true;

    if ($(label).val() == undefined || $(label).val() == "") {
        $(label).parent().addClass("has-error");
        flag = false;
    }
    if ($(url).val() == undefined || $(url).val() == "") {
        $(url).parent().addClass("has-error");
        flag = false;
    }
    if ($(starting_ip).val() == undefined || $(starting_ip).val() == "") {
        $(starting_ip).parent().addClass("has-error");
        flag = false;
    }
    if ($(ending_ip).val() == undefined || $(ending_ip).val() == "") {
        $(ending_ip).parent().addClass("has-error");
        flag = false;
    }
    if (!flag) {
        sweetAlert("Ops...", "Compila i campi evidenziati", "error");
        return false;
    }
    //altrimenti, post con la richiesta
    $.post(my_ajax_obj.ajax_url, { //POST request
            _ajax_nonce: my_ajax_obj.nonce, //nonce
            action: "buyg_mod_store", //action
            id: id,
            label: $("#label").val(),
            url: $("#url").val(),
            starting_ip: $("#starting_ip").val(),
            ending_ip: $("#ending_ip").val() //data
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
                            }, 2000);
                        });
                    }
                }).then(function() {
                    console.log("ciao");
                    window.location = "" + obj.url;
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

function prepareModStore(target, id) {
  //recupero i dati dello store
  var storeRow = $(target).closest(".row-store");
  var id = $(storeRow).find("input[name=id_mod]").val();
  var label = $(storeRow).find("input[name=label_mod]").val();
  var url = $(storeRow).find("input[name=url_mod]").val();
  var starting_ip = $(storeRow).find("input[name=starting_ip_mod]").val();
  var ending_ip = $(storeRow).find("input[name=ending_ip_mod]").val();
  //creo la riga per il form
  row = document.createElement("tr");
  $(row).addClass("row-store-mod");
  $(row).append("<input name='id' value='"+id+"' type='hidden'>");
  $(row).append("<td></td>");
  $(row).append("<td class='column-columnname'><input type='text' name='label' value='"+label+"' ></td>")
  $(row).append("<td class='column-columnname'><input type='text' name='url' value='"+url+"' ></td>")
  $(row).append("<td class='column-columnname'><input type='text' name='starting_ip' value='"+starting_ip+"' ></td>")
  $(row).append("<td class='column-columnname'><input type='text' name='ending_ip' value='"+ending_ip+"' ></td>")
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
