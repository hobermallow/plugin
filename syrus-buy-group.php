<?php
/*
Plugin Name: Maddaai Multistores
*/

//controllo che non si possa accedere direttamente al file del plugin
defined( 'ABSPATH' ) or die("Non e' possibile accedere al file");

//includo la classe per le tabelle di wordpress
if(!class_exists('WP_List_Table')){
   require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

require_once("class.php");

//funzione per l'aggiunta dei file necessari di bootstrap
function buyg_enqueue_scripts()
{
    // JS
    // wp_enqueue_script("buyg_bootstrap",'/wp-content/plugins/syrus-buy-group/admin/js/bootstrap.min.js');
    wp_enqueue_script("buyg_jquery",'/wp-content/plugins/syrus-buy-group/admin/js/jquery-3.1.1.js');
    wp_enqueue_script("buyg_jquery_mask",'/wp-content/plugins/syrus-buy-group/admin/js/jquery-mask.js');
    wp_enqueue_script("buyg_jquery_ui",'/wp-content/plugins/syrus-buy-group/admin/plugins/jquery-ui-1.12.1.custom/jquery-ui.js', array('buyg_jquery'));
    wp_enqueue_script("buyg_sweetalert",'/wp-content/plugins/syrus-buy-group/admin/js/sweetalert2.js');
    //il mio script per tutte le funzioni ajax
    wp_enqueue_script( 'buyg_ajax_script',
        '/wp-content/plugins/syrus-buy-group/admin/js/custom.js',
        array( 'buyg_jquery' )
    );
    // CSS
    // wp_enqueue_style("buyg_bootstrap_css",'/wp-content/plugins/syrus-buy-group/admin/css/bootstrap.min.css');
    wp_enqueue_style("buyg_fontawesome_css",'/wp-content/plugins/syrus-buy-group/admin/css/fontawesome/css/font-awesome.css');
    wp_enqueue_style("buyg_sweetalert",'/wp-content/plugins/syrus-buy-group/admin/css/sweetalert2.css');
    wp_enqueue_style("buyg_jquery_ui",'/wp-content/plugins/syrus-buy-group/admin/plugins/jquery-ui-1.12.1.custom/jquery-ui.css');

    $title_nonce = wp_create_nonce( 'ajax_url_nonce' );
    wp_localize_script( 'buyg_ajax_script', 'my_ajax_obj', array(
       'ajax_url' => admin_url( 'admin-ajax.php' ),
       'nonce'    => $title_nonce,
    ) );
}


//hook per aggiungere gli script all'head di ogni pagina
add_action('admin_enqueue_scripts', 'buyg_enqueue_scripts');

function buyg_options_page_html() {

  global $wpdb;
  $table = $wpdb->prefix."maddaai_magento_stores";

  if($_SERVER['REQUEST_METHOD'] === 'POST') {

    //recuero gli store
    $stores = $_POST['store'];
    $stores = join(", ", $stores);
    $stores = "( ".$stores." )";
    //recupero l'azione
    $action = $_POST['action'];
    if($action == "buyg_activate_stores") {
      $wpdb->query("UPDATE $table SET `active` = 1 WHERE `id` IN $stores");
    }
    else if($action == "buyg_deactivate_stores") {
      $wpdb->query("UPDATE $table SET `active` = 0 WHERE `id` IN $stores");
    }
    else if($action == "buyg_delete_stores") {
      $wpdb->query("DELETE FROM $table WHERE `id` IN $stores");
    }
  }
  $store_list = new Store_List_Table();
  $store_list->prepare_items();
  echo "<div class='wrap'>";
  echo "<form method='post'>";
  $store_list->search_box("Cerca", "search_id");
  ?>
  <input class="button" style="float:right" type="submit" name="reset" value="reset">
  <?php
  $store_list->display();
  echo "</form>";
  echo "</div>";
  $link = add_query_arg(
    array(
      'page' => 'buyg_add_store', // as defined in the hidden page
    ),
    admin_url('admin.php')
  );
   ?>
  <button type="button" class="button" onclick="window.location = '<?php echo $link; ?>'" name="button"><i class="fa fa-plus"></i> Aggiunti Store</button>
<?php

}

function buyg_users_page_html() {

  global $wpdb;
  $table = $wpdb->prefix."maddaai_users";

  if($_SERVER['REQUEST_METHOD'] === 'POST') {

    //recuero gli utenti
    $users = $_POST['user'];
    $users = join(", ", $users);
    $users = "( ".$users." )";
    //recupero l'azione
    $action = $_POST['action'];
    if($action == "buyg_activate_users") {
      $wpdb->query("UPDATE $table SET `active` = 1 WHERE `id` IN $users");
    }
    else if($action == "buyg_deactivate_users") {
      $wpdb->query("UPDATE $table SET `active` = 0 WHERE `id` IN $users");
    }
    else if($action == "buyg_delete_users") {
      $wpdb->query("DELETE FROM $table WHERE `id` IN $users");
    }
  }
  $user_list = new Users_List_Table();
  $user_list->prepare_items();
  echo "<div class='wrap'>";
  echo "<form method='post'>";
  $user_list->search_box("Cerca", "search_id");
  ?>
  <input class="button" style="float:right" type="submit" name="reset" value="reset">
  <?php
  $user_list->display();
  echo "</form>";
  echo "</div>";
  $link = add_query_arg(
    array(
      'page' => 'buyg_add_user', // as defined in the hidden page
    ),
    admin_url('admin.php')
  );
   ?>
  <button type="button" class="button" onclick="window.location = '<?php echo $link; ?>'" name="button"><i class="fa fa-plus"></i> Aggiunti Utente</button>
<?php


}


function buyg_add_user_page_html() {
  //aggiungo bootstrap
  //db
  global $wpdb;
  //nome della table
  $table = $wpdb->prefix."maddaai_users";
  $table_stores = $wpdb->prefix."maddaai_magento_stores";
  //recupero gli stores disponibili
  $stores = $wpdb->get_results("SELECT $table_stores.id, $table_stores.label FROM $table_stores ");

  ?>
  <div class="wrap">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"></h3>
      </div>
      <div class="panel-body">
        <form class="" action="index.html" method="post">
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="name">Nome</label>
                <input type="text" value="" class="form-control" name="name" id="name" placeholder="">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="surname">Cognome</label>
                <input type="text" value="" class="form-control" name="surname" id="surname">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="mail">Mail</label>
                <input type="text" value="" class="form-control" name="mail" id="mail">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="id_store">Store</label>
                <select name="id_store" id="id_store" class="form-control" >
                  <option value=""></option>
                  <?php foreach ($stores as $key => $value): ?>
                    <option value="<?php echo $value->id; ?>"><?php echo $value->label; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="cf">Codice Fiscale</label>
                <input type="text" value="" class="form-control" name="cf" id="cf">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="pwd">Password</label>
                <input type="password" value="" class="form-control" name="pwd" id="pwd">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="pwd_confirm">Conferma Password</label>
                <input type="password" value="" class="form-control" name="pwd_confirm" id="pwd_confirm">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 " style="text-align:right">
              <button class="button" type="button" name="button" onclick="window.location = '<?php echo add_query_arg(
                array(
                  'page' => 'buyg_users', // as defined in the hidden page
                ),
                admin_url('admin.php')
              ); ?>'"><i class="fa fa-arrow-left"></i> Torna alla lista</button>
              <button class="button" onclick="addUser()" type="button" name="button"><i class="fa fa-check"></i> Salva</button>
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>
  <script type="text/javascript">
    $(document).ready(function () {
      $('#ending_ip').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
          translation: {
          'Z': {
            pattern: /[0-9]/, optional: true
          },
        }
      });
    $('#starting_ip').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
        translation: {
        'Z': {
          pattern: /[0-9]/, optional: true
        },
      }
    });
    });

  </script>

  <?php
}



function buyg_add_store_page_html() {
  //aggiungo bootstrap
  //db
  global $wpdb;
  //nome della table
  $table = $wpdb->prefix."maddaai_magento_stores";

  ?>
  <div class="wrap">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"></h3>
      </div>
      <div class="panel-body">
        <form class="" action="index.html" method="post">
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="label">Label</label>
                <input type="text" value="" class="form-control" name="label" id="label" placeholder="">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="url">Url</label>
                <input type="text" value="" class="form-control" name="url" id="url" placeholder="">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="starting_ip">Ip Iniziale</label>
                <input type="text" value="" class="form-control" name="starting_ip" id="starting_ip" placeholder="255.255.255.255">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="ending_ip">Ip Finale</label>
                <input type="text" value="" class="form-control" name="ending_ip" id="ending_ip" placeholder="255.255.255.255">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 " style="text-align:right">
              <button class="button" type="button" name="button" onclick="window.location = '<?php echo add_query_arg(
                array(
                  'page' => 'buyg', // as defined in the hidden page
                ),
                admin_url('admin.php')
              ); ?>'"><i class="fa fa-arrow-left"></i> Torna alla lista</button>
              <button class="button" onclick="addStore()" type="button" name="button"><i class="fa fa-check"></i> Salva</button>
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>
  <script type="text/javascript">
    $(document).ready(function () {
      $('#ending_ip').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
          translation: {
          'Z': {
            pattern: /[0-9]/, optional: true
          },
        }
      });
    $('#starting_ip').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
        translation: {
        'Z': {
          pattern: /[0-9]/, optional: true
        },
      }
    });
    });

  </script>

  <?php
}
//funzione per l'aggiunta della pagina di gestione del plugin alla voce del menu tools
function buyg_options_page()
{
    //aggiungo la pagina generale di gestione del plugin
    add_menu_page(
        'Maddaai Multistores',
        'Maddaai Multistores',
        'manage_options',
        'buyg',
        'buyg_options_page_html'
    );

    //aggiungo la pagina per gli utenti
    add_submenu_page(
      'buyg',
      'Utenti',
      'Utenti',
      'manage_options',
      'buyg_users',
      'buyg_users_page_html'
    );

    //aggiungo la pagina per le richieste
    add_submenu_page(
      'buyg',
      'Richieste',
      'Richieste',
      'manage_options',
      'buyg_requests'
    );

    //aggiungo la pagina per gli annunci
    add_submenu_page(
      'buyg',
      'Annunci',
      'Annunci',
      'manage_options',
      'buyg_news'
    );

    //aggiungo la pagina per le impostazioni
    add_submenu_page(
      'buyg',
      'Impostazioni',
      'Impostazioni',
      'manage_options',
      'buyg_settings'
    );

    //pagina per l'aggiunta di uno stores
    add_submenu_page(
      null,
      'Aggiungi Store',
      'Aggiungi Store',
      'manage_options',
      'buyg_add_store',
      'buyg_add_store_page_html'
    );

    //pagina per l'aggiunta di uno stores
    add_submenu_page(
      null,
      'Aggiungi Utente',
      'Aggiungi Utente',
      'manage_options',
      'buyg_add_user',
      'buyg_add_user_page_html'
    );

}
//aggiungo la funzione all'hook
add_action('admin_menu', 'buyg_options_page');


//funzione per la creazione della tabella di relationship fra utenti e store
//e della tabella per contenere la lista degli store con le relative classi di ip
function buyg_install_database() {
  global $wpdb;

  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

  //creo la seconda query per la creazione della tabella della gestione degli store
  $table_name_store = $wpdb->prefix . 'maddaai_magento_stores';

  $sql = "CREATE TABLE $table_name_store (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    label text NOT NULL,
    url varchar(255) NOT NULL,
    starting_ip int unsigned NULL,
    ending_ip int unsigned NULL,
    active tinyint(1) NOT NULL DEFAULT 0,
    create_date timestamp not null default current_timestamp,
    PRIMARY KEY (id)
  ) $charset_collate;";

  dbDelta($sql);

  //creo la tabella per gli utenti
  $table_name_users = $wpdb->prefix."maddaai_users";

  $sql = "CREATE TABLE $table_name_users (
      id bigint(20) NOT NULL AUTO_INCREMENT,
      name varchar(255) NOT NULL,
  	  surname VARCHAR(255) NOT NULL,
  	  id_store bigint(20) NOT NULL,
  	  pwd VARCHAR(255) NOT NULL,
  	  mail VARCHAR(255) NOT NULL,
  	  cf VARCHAR(255) NOT NULL,
  	  warning  bigint(20) not null default 0,
  	  active tinyint(1) not null default 0,
  	  last_login timestamp not null default current_timestamp,
  	  create_date timestamp not null default current_timestamp,
      primary key(id)

  ) $charset_collate";

  dbDelta($sql);

}

//registro la funzione di salvataggio delle tabelle nel db
register_activation_hook( __FILE__, 'buyg_install_database');

add_action( 'user_register', 'buyg_save_user_store', 10, 1 );

function buyg_save_user_store( $user_id ) {

        //update_user_meta($user_id, 'first_name', $_POST['first_name']);
        //recupero l'ip della richiesta
        $user_ip_address = $_SERVER['REMOTE_ADDR'];

}

//funzione per la modifica di uno store
function buyg_mod_store() {
  //controllo il nonce
  check_ajax_referer( 'ajax_url_nonce' );
  $id = $_POST['id'];
  $label = $_POST['label'];
  $url = $_POST['url'];
  $starting_ip = $_POST['starting_ip'];
  $ending_ip = $_POST['ending_ip'];

  $starting_ip = explode(".", $starting_ip);
  foreach ($starting_ip as $key => $value) {
    // if($value == "000") {
      $starting_ip[$key] = intval($value);
    // }
  }
  $starting_ip = join(".", $starting_ip);
  $starting_ip = ip2long($starting_ip);

  $ending_ip = explode(".", $ending_ip);
  foreach ($ending_ip as $key => $value) {
    // if($value == "000") {
      $ending_ip[$key] = intval($value);
    // }
  }
  $ending_ip = join(".", $ending_ip);
  $ending_ip = ip2long($ending_ip);
  //controllo l'ordine degli ip
  if($starting_ip > $ending_ip) {
    echo json_encode(array("status" => 0, "msg" => "Ip Iniziale maggiore di quello Finale"));
    wp_die();
  }
  //controllo se la label e' gia' utilizzata
  global $wpdb;
  $table = $wpdb->prefix."maddaai_magento_stores";

  $rows = $wpdb->get_results("SELECT * from $table WHERE label = '$label' AND id != $id");
  //se non e' vuoto
  if(count($rows)> 0) {
    echo json_encode(array("status" => 0, "msg" => "Label utilizzata gia' esistente"));
    wp_die();
  }
  //controllo se lo starting ip e' compreso nell'itervallo di un altro store
  $rows = $wpdb->get_results("SELECT * FROM $table where starting_ip <= $starting_ip and ending_ip >= $starting_ip and id != $id ");
  if(count($rows) >0 ) {
    echo json_encode(array("status" => 0, "msg" => "Ip Iniziale compreso nell'intervallo di un altro store"));
    wp_die();
  }
  //controllo se l'ending_ip e' compreso nell'itervallo di un altro store
  $rows = $wpdb->get_results("SELECT * FROM $table where starting_ip <= $ending_ip and ending_ip >= $ending_ip and id != $id ");
  if(count($rows) >0 ) {
    echo json_encode(array("status" => 0, "msg" => "Ip Finale compreso nell'intervallo di un altro store"));
    wp_die();
  }
  //controllo se abbia effettuato qualche modificare
  $store = $wpdb->get_row("SELECT * FROM $table WHERE id = $id");
  if($store != false) {
    if($store->label == $label && $store->url == $url && $store->starting_ip == $starting_ip && $store->ending_ip == $ending_ip ) {
      echo json_encode(array("status" => 0, "msg" => "Non hai effettuato alcuna modifica :)"));
      wp_die();
    }
  }

  //posso modificare il db
  if($id == "0") {
    $ret = $wpdb->insert($table, array("label" => $label, "url" => $url, "starting_ip" => $starting_ip, "ending_ip" => $ending_ip));
  }
  else {
    $ret = $wpdb->update($table, array("label" => $label, "url" => $url, "starting_ip" => $starting_ip, "ending_ip" => $ending_ip), array("id" => $id));
  }
  if($ret == false) {
    echo json_encode(array('status' => 0, "msg" => "Errore nel salvataggio dello store" ));
    wp_die();
  }
  //altrimenti tutto ok

  echo json_encode(array("status" => 1, "url" => add_query_arg(array('page' => 'buyg',),admin_url('admin.php'))));
  wp_die();
}
add_action( 'wp_ajax_buyg_mod_store', 'buyg_mod_store' );

//funzione per la modifica di uno store
function buyg_add_store() {
  //controllo il nonce
  check_ajax_referer( 'ajax_url_nonce' );
  $id = $_POST['id'];
  $label = $_POST['label'];
  $url = $_POST['url'];
  $starting_ip = $_POST['starting_ip'];
  $ending_ip = $_POST['ending_ip'];

  $starting_ip = explode(".", $starting_ip);
  foreach ($starting_ip as $key => $value) {
    // if($value == "000") {
      $starting_ip[$key] = intval($value);
    // }
  }
  $starting_ip = join(".", $starting_ip);
  $starting_ip = ip2long($starting_ip);

  $ending_ip = explode(".", $ending_ip);
  foreach ($ending_ip as $key => $value) {
    // if($value == "000") {
      $ending_ip[$key] = intval($value);
    // }
  }
  $ending_ip = join(".", $ending_ip);
  $ending_ip = ip2long($ending_ip);
  //controllo l'ordine degli ip
  if($starting_ip > $ending_ip) {
    echo json_encode(array("status" => 0, "msg" => "Ip Iniziale maggiore di quello Finale"));
    wp_die();
  }
  //controllo se la label e' gia' utilizzata
  global $wpdb;
  $table = $wpdb->prefix."maddaai_magento_stores";

  $rows = $wpdb->get_results("SELECT * from $table WHERE label = '$label' AND id != $id");
  //se non e' vuoto
  if(count($rows)> 0) {
    echo json_encode(array("status" => 0, "msg" => "Label utilizzata gia' esistente"));
    wp_die();
  }
  //controllo se lo starting ip e' compreso nell'itervallo di un altro store
  $rows = $wpdb->get_results("SELECT * FROM $table where starting_ip <= $starting_ip and ending_ip >= $starting_ip and id != $id ");
  if(count($rows) >0 ) {
    echo json_encode(array("status" => 0, "msg" => "Ip Iniziale compreso nell'intervallo di un altro store"));
    wp_die();
  }
  //controllo se l'ending_ip e' compreso nell'itervallo di un altro store
  $rows = $wpdb->get_results("SELECT * FROM $table where starting_ip <= $ending_ip and ending_ip >= $ending_ip and id != $id ");
  if(count($rows) >0 ) {
    echo json_encode(array("status" => 0, "msg" => "Ip Finale compreso nell'intervallo di un altro store"));
    wp_die();
  }

  //posso modificare il db
  $ret = $wpdb->insert($table, array("label" => $label, "url" => $url, "starting_ip" => $starting_ip, "ending_ip" => $ending_ip));
  if($ret == false) {
    echo json_encode(array('status' => 0, "msg" => "Errore nel salvataggio dello store" ));
    wp_die();
  }
  //altrimenti tutto ok

  echo json_encode(array("status" => 1, "url" => add_query_arg(array('page' => 'buyg',),admin_url('admin.php'))));
  wp_die();
}
add_action( 'wp_ajax_buyg_add_store', 'buyg_add_store' );


//funzione per l'aggiunta di un Utente
function buyg_add_user() {
  //controllo il nonce
  check_ajax_referer( 'ajax_url_nonce' );
  $name = $_POST['name'];
  $surname = $_POST['surname'];
  $mail = $_POST['mail'];
  $cf = $_POST['cf'];
  $pwd = $_POST['pwd'];
  $id_store = $_POST['id_store'];

  //setto id_store a 0 se stringa  vuota
  if($id_store == "" || is_null($id_store)) {
    $id_store = "0";
  }


  //controllo se la label e' gia' utilizzata
  global $wpdb;
  $table = $wpdb->prefix."maddaai_users";

  $rows = $wpdb->get_results("SELECT * from $table WHERE mail = '$mail' ");
  //se non e' vuoto
  if(count($rows)> 0) {
    echo json_encode(array("status" => 0, "msg" => "Mail inserita gia' utilizzata"));
    wp_die();
  }

  //posso modificare il db
  $ret = $wpdb->insert($table, array("name" => $name, "surname" => $surname, "mail" => $mail, "cf" => $cf, "pwd" => wp_hash_password($pwd), "id_store" => $id_store));
  if($ret == false) {
    echo json_encode(array('status' => 0, "msg" => "Errore nel salvataggio dell'utente" ));
    wp_die();
  }
  //altrimenti tutto ok

  echo json_encode(array("status" => 1, "url" => add_query_arg(array('page' => 'buyg_users',),admin_url('admin.php'))));
  wp_die();
}
add_action( 'wp_ajax_buyg_add_user', 'buyg_add_user' );


//funzione per l'aggiunta di un Utente
function buyg_mod_user() {
  //controllo il nonce
  check_ajax_referer( 'ajax_url_nonce' );
  $id = $_POST['id'];
  $name = $_POST['name'];
  $surname = $_POST['surname'];
  $mail = $_POST['mail'];
  $cf = $_POST['cf'];
  $id_store = $_POST['id_store'];

  //setto id_store a 0 se stringa  vuota
  if($id_store == "" || is_null($id_store)) {
    $id_store = "0";
  }


  //controllo se la label e' gia' utilizzata
  global $wpdb;
  $table = $wpdb->prefix."maddaai_users";

  $rows = $wpdb->get_results("SELECT * from $table WHERE mail = '$mail' and id != $id");
  //se non e' vuoto
  if(count($rows)> 0) {
    echo json_encode(array("status" => 0, "msg" => "Mail inserita gia' utilizzata"));
    wp_die();
  }
  //controllo se sto effettivamente effettuando delle modifiche (se non ci sono differenze, l'update di wp da' errore)
  $user = $wpdb->get_row("SELECT * FROM $table WHERE id = $id");
  if($user != false) {
    if($user->name == $name && $user->surname == $surname && $user->cf == $cf && $user->mail == $mail && $user->id_store == $id_store ) {
      echo json_encode(array("status" => 0, "msg" => "Non hai effettuato alcuna modifica :)"));
      wp_die();
    }
  }

  //posso modificare il db
  $ret = $wpdb->update($table, array("name" => $name, "surname" => $surname, "mail" => $mail, "cf" => $cf, "id_store" => $id_store), array('id' => $id));
  if($ret == false) {
    echo json_encode(array('status' => 0, "msg" => "Errore nel salvataggio dell'Utente" ));
    wp_die();
  }
  //altrimenti tutto ok

  echo json_encode(array("status" => 1, "url" => add_query_arg(array('page' => 'buyg_users',),admin_url('admin.php'))));
  wp_die();
}
add_action( 'wp_ajax_buyg_mod_user', 'buyg_mod_user' );



function buyg_del_store() {
  check_ajax_referer( 'ajax_url_nonce' );
  global $wpdb;
  $table = $wpdb->prefix."maddaai_magento_stores";
  //recupero l'id
  $id = $_POST['id'];
  $ret = $wpdb->delete($table, array('id' => $id ));
  if($ret) {
    echo json_encode(array("status" => 1, "url" => add_query_arg(array('page' => 'buyg',),admin_url('admin.php'))));
    wp_die();
  }
  else {
    echo json_encode(array('status' => 0, "msg" => "Errore nel salvataggio dello store" ));
    wp_die();
  }
}

add_action( 'wp_ajax_buyg_del_store', 'buyg_del_store' );


function buyg_toggle_store() {
  check_ajax_referer('ajax_url_nonce');
  global $wpdb;
  $table = $wpdb->prefix."maddaai_magento_stores";
  $id = $_POST['id'];
  $active = $_POST['active'];
  $ret = $wpdb->update($table, array("active" => $active), array("id" => $id));
  if($ret) {
    echo json_encode(array("status" => 1));
    wp_die();
  }
  else {
    echo json_encode(array("status" => 0, "msg" => "Errore nell'attivazione/disattivazione dello store"));
    wp_die();
  }
}

add_action('wp_ajax_buyg_toggle_store', 'buyg_toggle_store');

function buyg_toggle_user() {
  check_ajax_referer('ajax_url_nonce');
  global $wpdb;
  $table = $wpdb->prefix."maddaai_users";
  $id = $_POST['id'];
  $active = $_POST['active'];
  $ret = $wpdb->update($table, array("active" => $active), array("id" => $id));
  if($ret) {
    echo json_encode(array("status" => 1));
    wp_die();
  }
  else {
    echo json_encode(array("status" => 0, "msg" => "Errore nell'attivazione/disattivazione dell'utente"));
    wp_die();
  }
}

add_action('wp_ajax_buyg_toggle_user', 'buyg_toggle_user');


function buyg_autocomplete_stores() {
  check_ajax_referer('ajax_url_nonce');
  global $wpdb;
  $table = $wpdb->prefix."maddaai_magento_stores";
  $term = $_POST['term'];
  if(is_null($term) || $term == "")  {
    $where_clause = "";
  }
  else {
    $where_clause = " where label like '%$term%'";
  }
  $ret = $wpdb->get_results("select id, label from $table ".$where_clause);
  echo json_encode($ret);
  wp_die();
}
add_action("wp_ajax_buyg_autocomplete_stores", "buyg_autocomplete_stores");


add_action( 'init', 'wpse9870_init_internal' );
function wpse9870_init_internal()
{
    add_rewrite_rule( 'my-api.php$', 'index.php?wpse9870_api=1', 'top' );
}

//registra la nuova query variable in wp in modo che la conosca
add_filter( 'query_vars', 'wpse9870_query_vars' );
function wpse9870_query_vars( $query_vars )
{
    $query_vars[] = 'wpse9870_api';
    return $query_vars;
}

//aggiungo funzione per parsare la richiesta fatta a wp ed eventualmente lanciare
//il plugin ,bloccando l'esecuzione di wp
add_action( 'parse_request', 'wpse9870_parse_request' );
function wpse9870_parse_request( &$wp )
{
    if ( array_key_exists( 'wpse9870_api', $wp->query_vars ) ) {
        include 'my-api.php';
        exit();
    }
    return;
}
function buyg_flush(){
        flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'buyg_flush' );
