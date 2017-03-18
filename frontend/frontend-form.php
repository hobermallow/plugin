<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Nuovo Utente</title>
    <script
      src="https://code.jquery.com/jquery-3.1.1.js"
      integrity="sha256-16cdPddA6VdVInumRGo6IbivbERE8p7CQR3HzTBuELA="
      crossorigin="anonymous"></script>
    <link
      href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
      crossorigin="anonymous">
    <script
      src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
      integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
      crossorigin="anonymous">
      </script>
    <script type="text/javascript" src="js/jquery-mask.js">
    </script>
    <script type="text/javascript" src="js/npm.js">
    </script>
    <script type="text/javascript" src="js/sweetalert2.js">
    </script>
    <link rel="stylesheet" href="css/sweetalert2.css">
    <link rel="stylesheet" href="css/fontawesome/css/font-awesome.css">
    <style media="screen">
    /* centered columns styles */
    .row-centered {
      text-align:center;
    }
    .col-centered {
      display:inline-block;
      float:none;
      /* reset the text-align */
      text-align:left;
      /* inline-block space fix */
      margin-right:-4px;
    }

    </style>
  </head>
  <body>
    <div class="container bg-white">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Nuovo Utente</h3>
        </div>
        <div class="panel-body">
          <form class="" action="index.html" method="post">
            <div class="row row-centered">
              <div class="col-lg-4 col-centered">
                <div class="form-group form-group-default">
                  <label for="nome">Nome</label>
                  <input type="text" required class="form-control" id="nome" placeholder="Nome">
                </div>
              </div>
            </div>
            <div class="row row-centered">
              <div class="col-lg-4 col-centered">
                <div class="form-group">
                  <label for="cognome">Cognome</label>
                  <input type="text" class="form-control" id="cognome" placeholder="Cognome">
                </div>
              </div>
            </div>
            <div class="row row-centered">
              <div class="col-lg-4 col-centered">
                <div class="form-group">
                  <label for="mail">Mail</label>
                  <input type="text" class="form-control" id="mail" placeholder="Mail">
                </div>
              </div>
            </div>
            <div class="row row-centered">
              <div class="col-lg-4 col-centered">
                <div class="form-group">
                  <label for="password">Password</label>
                  <input type="password" class="form-control" id="password" placeholder="">
                </div>
              </div>
            </div>
            <div class="row row-centered">
              <div class="col-lg-4 col-centered">
                <div class="form-group">
                  <label for="password_confirm">Conferma Password</label>
                  <input type="password" class="form-control" id="password_confirm" placeholder="">
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="panel-footer">
          <div class="row">
            <div class="col-lg-12" style="text-align:right">
              <button type="button" name="button" class="btn btn-danger"><i class="fa fa-arrow-left"></i> Annulla</button>
              <button type="button" class="btn btn-success" name="button"><i class="fa fa-check"></i> Salva</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
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

    </script>
  </body>
</html>
