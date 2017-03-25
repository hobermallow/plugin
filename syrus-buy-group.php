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
    wp_enqueue_script("buyg_bootstrap",'/wp-content/plugins/syrus-buy-group/admin/js/bootstrap.min.js');
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
    wp_enqueue_style("buyg_bootstrap_css",'/wp-content/plugins/syrus-buy-group/admin/css/bootstrap.min.css');
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
      $wpdb->query("UPDATE $table SET `deleted` = 1 WHERE `id` IN $users");
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


function buyg_requests_page_html() {
  global $wpdb;
  $table = $wpdb->prefix."maddaai_notifications";

  if($_SERVER['REQUEST_METHOD'] === 'POST') {

    //recuero gli utenti
    // $users = $_POST['user'];
    // $users = join(", ", $users);
    // $users = "( ".$users." )";
    //recupero l'azione
    // $action = $_POST['action'];
    // if($action == "buyg_activate_users") {
      // $wpdb->query("UPDATE $table SET `active` = 1 WHERE `id` IN $users");
    // }
    // else if($action == "buyg_deactivate_users") {
      // $wpdb->query("UPDATE $table SET `active` = 0 WHERE `id` IN $users");
    // }
    // else if($action == "buyg_delete_users") {
      // $wpdb->query("UPDATE $table SET `deleted` = 1 WHERE `id` IN $users");
    // }
  }
  $notification_list = new Notifications_List_Table();
  $notification_list->prepare_items();
  echo "<div class='wrap'>";
  echo "<form method='post'>";
  $notification_list->display();
  echo "</form>";
  echo "</div>";

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
      'buyg_requests',
      'buyg_requests_page_html'
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
      'buyg_settings',
      'buyg_settings_page_html'
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

function buyg_settings_page_html() {
  //riprendo i valori nel db

  // check user capabilities
 if ( ! current_user_can( 'manage_options' ) ) {
 return;
 }

 // add error/update messages

 // check if the user have submitted the settings
 // wordpress will add the "settings-updated" $_GET parameter to the url
 if ( isset( $_GET['settings-updated'] ) ) {
  //  echo "Impostazioni Aggiornate";
 // add settings saved message with the class of "updated"
 add_settings_error( 'buyg_messages', 'buyg_message', 'Impostazioni Salvate', 'updated' );
 }

 // show error/update messages
 settings_errors( 'buyg_messages' );
 ?>
 <div class="wrap">
 <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
 <form action="options.php" method="post">
 <?php
 settings_fields( 'buyg_settings' );
 // output setting sections and their fields
 // (sections are registered for "wporg", each field is registered to a specific section)
 do_settings_sections( 'buyg_settings' );
 // output save settings button
 submit_button( 'Salva Impostazioni' );
 ?>
 </form>
 </div>
 <?php
}



/**
 * custom option and settings
 */
function buyg_settings_init() {
 // register a new setting for "wporg" page
 register_setting( 'buyg_settings', 'buyg_options' );

 // register a new section in the "wporg" page
 add_settings_section(
 'buyg_settings_section',
 "Impostazioni Account Magento",
 'buyg_settings_section_cb',
 'buyg_settings'
 );

 // register a new field in the "wporg_section_developers" section, inside the "wporg" page
 add_settings_field(
 'buyg_settings_username', // as of WP 4.6 this value is used only internally
 // use $args' label_for to populate the id inside the callback
 "Username",
 'buyg_settings_username_cb',
 'buyg_settings',
 'buyg_settings_section',
 [
 'label_for' => 'buyg_settings_username',
 ]
 );

 add_settings_field(
 'buyg_settings_password', // as of WP 4.6 this value is used only internally
 // use $args' label_for to populate the id inside the callback
 "Password",
 'buyg_settings_password_cb',
 'buyg_settings',
 'buyg_settings_section',
 [
 'label_for' => 'buyg_settings_password',
 ]
 );
}

/**
 * register our wporg_settings_init to the admin_init action hook
 */
add_action( 'admin_init', 'buyg_settings_init' );

/**
 * custom option and settings:
 * callback functions
 */

// developers section cb

// section callbacks can accept an $args parameter, which is an array.
// $args have the following keys defined: title, id, callback.
// the values are defined at the add_settings_section() function.
function buyg_settings_sections_cb( $args ) {
 ?>
 <p id="<?php echo esc_attr( $args['id'] ); ?>">Follow the white rabbit</p>
 <?php
}

// pill field cb

// field callbacks can accept an $args parameter, which is an array.
// $args is defined at the add_settings_field() function.
// wordpress has magic interaction with the following keys: label_for, class.
// the "label_for" key value is used for the "for" attribute of the <label>.
// the "class" key value is used for the "class" attribute of the <tr> containing the field.
// you can add custom key value pairs to be used inside your callbacks.
function buyg_settings_password_cb( $args ) {
 // get the value of the setting we've registered with register_setting()
 $options = get_option( 'buyg_options' );
 // output the field
 ?>
 <input id="<?php echo esc_attr($args['label_for']) ?>"
 type="text"
 name="buyg_options[<?php echo esc_attr($args['label_for']); ?>]"
  value="<?php echo isset( $options[ $args['label_for'] ] ) ? $options[ $args['label_for'] ] : ''; ?>" >
 <?php
}

function buyg_settings_username_cb( $args ) {
 // get the value of the setting we've registered with register_setting()
 $options = get_option( 'buyg_options' );
 // output the field
 ?>
 <input id="<?php echo esc_attr($args['label_for']) ?>"
 type="text"
 name="buyg_options[<?php echo esc_attr($args['label_for']); ?>]"
  value="<?php echo isset( $options[ $args['label_for'] ] ) ? $options[ $args['label_for'] ] : ''; ?>" >
 <?php
}





/**
 * top level menu:
 * callback functions
 */




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
  ) ;";

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
  	  create_date timestamp not null default current_timestamp,
      deleted tinyint(1) not null default 0,
      primary key(id)

  ) ;";

  dbDelta($sql);


  //creo la tabella per gli utenti
  $table_notifications = $wpdb->prefix."maddaai_notifications";

  $sql = "CREATE TABLE $table_notifications (
      id bigint(20) NOT NULL AUTO_INCREMENT,
      name varchar(255) NOT NULL,
  	  surname VARCHAR(255) NOT NULL,
  	  store varchar(255)  NULL,
  	  link VARCHAR(255) NOT NULL,
  	  price VARCHAR(255) NOT NULL,
  	  read tinyint(1) not null default 0,
  	  create_date timestamp not null default current_timestamp,
      primary key(id)
  ) ;";

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
  global $wpdb;
  $table = $wpdb->prefix."maddaai_users";
  $table_stores = $wpdb->prefix."maddaai_magento_stores";
  $options = get_option("buyg_options");
  $userMag = $options['buyg_settings_username'];
  $passMag = $options['buyg_settings_password'];

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

  $rows = $wpdb->get_results("SELECT * from $table WHERE mail = '$mail' AND deleted = 0 ");
  //se non e' vuoto
  if(count($rows)> 0) {
    echo json_encode(array("status" => 0, "msg" => "Mail inserita gia' utilizzata"));
    wp_die();
  }

  //altrimenti tutto ok
  //hash di password e codice fiscale
    $clearPassword = $password;
    $password = wp_hash_password($password);
    $cf = wp_hash_password($cf);

    //recupero lo store
    $store = $wpdb->get_row("select * from $table_stores where id = $id_store");
    if(is_null($store)){
      echo json_encode(array('status' => 0, "msg" => "Store non trovato" ));
      wp_die();
    }

    //creo l'account su magento
        $userData = array("username" => $userMag, "password" => $passMag);
        $ch = curl_init("http://".$store->url."/rest/V1/integration/admin/token");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Content-Lenght: " . strlen(json_encode($userData))));
        //la preghiera
        $token = curl_exec($ch);
        // echo var_dump($token);


        //comincio a creare l'utente
        $user = array();
        $user['customer'] = array();
        $user['customer']['id'] = 0;
        $user['customer']['firstname'] = $name;
        $user['customer']['lastname'] = $surname;
        $user['customer']['email'] = $mail;
        $user['password'] = $clearPassword;

        $ch = curl_init("http://".$store->url."/rest/V1/customers");
        // $ch = curl_init("http://magento.syrus.it/rest/V1/customers");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($user));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . json_decode($token)));

        $result = curl_exec($ch);
        //controllo che l'utente sia stato creato correttamente
        if($result === false) {
          echo json_encode(array('status' => 0, "msg" => "Errore nel contattare lo store Magento" ));
      	  wp_die();
        }
        //altrimenti controllo che l'aggiunta dell'utente su magento abbia avuto successo
        $result = json_decode($result);
        // echo var_dump($result);
        //se e' settato il message, errore
        if(property_exists($result->message)) {
          echo json_encode(array('status' => 0, "msg" => $result->message ));
      	  wp_die();
        }
        else {
          $wpdb->insert($table, array("id_store" => $store->id,"name"=>$name, "surname" => $surname, "mail" => $mail, "cf" => $cf, "pwd" => $password ));
        }

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
  // $mail = $_POST['mail'];
  // $cf = $_POST['cf'];
  // $id_store = $_POST['id_store'];

  //setto id_store a 0 se stringa  vuota
  // if($id_store == "" || is_null($id_store)) {
    // $id_store = "0";
  // }


  //controllo se la label e' gia' utilizzata
  global $wpdb;
  $table = $wpdb->prefix."maddaai_users";

  // $rows = $wpdb->get_results("SELECT * from $table WHERE mail = '$mail' and id != $id");
  //se non e' vuoto
  // if(count($rows)> 0) {
    // echo json_encode(array("status" => 0, "msg" => "Mail inserita gia' utilizzata"));
    // wp_die();
  // }
  //controllo se sto effettivamente effettuando delle modifiche (se non ci sono differenze, l'update di wp da' errore)
  $user = $wpdb->get_row("SELECT * FROM $table WHERE id = $id");
  if($user != false) {
    if($user->name == $name && $user->surname == $surname  ) {
      echo json_encode(array("status" => 0, "msg" => "Non hai effettuato alcuna modifica :)"));
      wp_die();
    }
  }

  //posso modificare il db
  $ret = $wpdb->update($table, array("name" => $name, "surname" => $surname), array('id' => $id));
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


function registration_form_html() {
  ?>  <p></p>
                  <form style="margin: 0 auto; width: 300px; margin-top: 100px !important" id="formRegistrazione" action="<?php  echo esc_url( $_SERVER['REQUEST_URI'] ); ?>" method="post" class="" >

                        <input type="text" name="buyg_name" id="buyg_name" value="" size="40"  placeholder="Nome">


                        <input type="text" name="buyg_surname" id="buyg_surname" value="" size="40" placeholder="Cognome">

                        <input type="email" name="buyg_mail" id="buyg_mail" value="" size="40"  placeholder="Email">


                        <input type="text" name="buyg_cf" id="buyg_cf" value="" size="40" placeholder="Codice Fiscale">


                        <input type="text" name="buyg_password" id="buyg_password" value="" size="40" placeholder="Password">


                        <input type="text" name="buyg_password_confirm" id="buyg_password_confirm" value="" size="40" placeholder="Conferma Password">

                    <p style="text-align:center;">
                      <input type="submit" onclick="document.getElementById('formRegistrazione').submit()" value="Send"  name="buyg_submitted">
                    </p>
                  </form>






  <?php
}

function registration_form_submit() {
  global $wpdb;
  $table = $wpdb->prefix."maddaai_users";
  $table_stores = $wpdb->prefix."maddaai_magento_stores";
  $options = get_option("buyg_options");
  $userMag = $options['buyg_settings_username'];
  $passMag = $options['buyg_settings_password'];
  // if the submit button is clicked, send the email
	if ( isset( $_POST['buyg_name'] ) ) {
    //recupero i vari campi
    $flag = true;
    $name = sanitize_text_field($_POST['buyg_name']);
    $surname = sanitize_text_field($_POST['buyg_surname']);
    $mail = sanitize_text_field($_POST['buyg_mail']);
    $cf = sanitize_text_field($_POST['buyg_cf']);
    $password = sanitize_text_field($_POST['buyg_password']);
    $password_confirm = sanitize_text_field($_POST['buyg_password_confirm']);
    //controllo che la mail inserita sia validata
    if(!is_email($mail)) {
      //boh
      $flag = false;
      echo "mail non valida";
    }
    //controllo che la mail non sia gia' stata utilizzata
    $rows = $wpdb->get_results("select * from $table where mail = '$mail' AND deleted = 0 ");
    if(count($rows) > 0) {
      //boh
      echo "mail usata";
      $flag = false;
    }
    //controllo che le password inserite siano uguali
    if($password != $password_confirm) {
      //boh
      echo "password inserite non uguali";
      $flag = false;
    }
    //controllo che la password sia lunga almeno 8 caratteri e contenga un carattere maiuscolo, uno minuscolo ed un carattere speciale
    if (strlen($password) <= '8') {
        echo "la password deve contenere almeno 8 caratteri";
        $flag = false;
    }
    elseif(!preg_match("#[0-9]+#",$password)) {
        echo "la password deve contenere almeno un numero ";
        $flag = false;
    }
    elseif(!preg_match("#[A-Z]+#",$password)) {
        echo "la password deve contenere almeno una lettera maiuscola";
        $flag = false;
    }
    elseif(!preg_match("#[a-z]+#",$password)) {
        echo "la password deve contenere almeno una lettera minuscola";
        $flag = false;
    }
    //hash di password e codice fiscale
    $clearPassword = $password;
    $password = wp_hash_password($password);
    $cf = wp_hash_password($cf);


    //recupero l'ip della richiesta
    $ip = $_SERVER['REMOTE_ADDR'];
    //converto l'ip in long
    $ip = ip2long($ip);
    //recupero l'id dello store corrispondente
    $store = $wpdb->get_row("select * from $table_stores where starting_ip <= $ip and ending_ip >= $ip");
    if(is_null($store)){
      echo "non esiste uno store a te corrispondente";
      $flag = false;
    }


      if($flag) {
        //creo l'account su magento
        $userData = array("username" => $userMag, "password" => $passMag);
        // $ch = curl_init("http://magento.syrus.it/rest/V1/integration/admin/token");
        $ch = curl_init("http://".$store->url."/rest/V1/integration/admin/token");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Content-Lenght: " . strlen(json_encode($userData))));
        //la preghiera
        $token = curl_exec($ch);
        //echo var_dump($token);


        //comincio a creare l'utente
        $user = array();
        $user['customer'] = array();
        $user['customer']['id'] = 0;
        $user['customer']['firstname'] = $name;
        $user['customer']['lastname'] = $surname;
        $user['customer']['email'] = $mail;
	$user['customer']['dob'] = "01/01/1900";
	$user['customer']['gender'] = 0;
        $user['password'] = $clearPassword;

        $ch = curl_init("http://".$store->url."/rest/V1/customers");
        // $ch = curl_init("http://magento.syrus.it/rest/V1/customers");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($user));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . json_decode($token)));

        $result = curl_exec($ch);
        //controllo che l'utente sia stato creato correttamente
        if($result === false) {
          echo "errore nella creazione dell'utente";
          return;
        }
        //altrimenti controllo che l'aggiunta dell'utente su magento abbia avuto successo
        $result = json_decode($result);
        //se e' settato il message, errore
        if(property_exists($result->message)) {
          echo $result->message;
          return;
        }
        else {
          $wpdb->insert($table, array("id_store" => $store->id,"name"=>$name, "surname" => $surname, "mail" => $mail, "cf" => $cf, "pwd" => $password ));
	if(!$wpdb->insert_id) {
		echo "Errore nell'inserimento dell'utente nel database";
		return;
	}
	else {
		echo "Utente creato correttamente";
	}
        }


      }
      else {
        echo "errore";
      }
    }
    //salvo

}

function registration_form() {
  ob_get_clean();
  registration_form_html();
  registration_form_submit();
  ob_clean();
}
add_shortcode("buyg_registration_form", "registration_form");

function login_form_html() {
?>

                    <form style="width: 300px ; margin: 0 auto; margin-top: 100px !important" id="formLogin" action="<?php  echo esc_url( $_SERVER['REQUEST_URI'] ); ?>" method="post" class="" >
			  <label for="buyg_mail">Email</label>
                          <input type="email" name="buyg_mail" id="buyg_mail" value="" size="40" placeholder="Email"><br>
                          <label for="buyg_password">Password</label>
		          <input type="text" name="buyg_password" id="buyg_password" value="" size="40" placeholder="Password">
                      <p>
                        <input type="button" onclick="submitLoginForm();" value="Send" name="buyg_submitted">
                      </p>
                    </form>


  <script>
  function submitLoginForm() {
    //recupero username e password
    var username = jQuery("#buyg_mail").val();
    var password = jQuery("#buyg_password").val();


    if(username == "" || username == null) {
      return false;
    }
    if(password == "" || password == null) {
      return false;
    }

    var url = "<?php echo admin_url( 'admin-ajax.php'); ?>";

    //altrimenti, post con la richiesta
    jQuery.ajax({ //POST request
            type: "POST",
            url: url,
            // _ajax_nonce: my_ajax_obj.nonce,
            data: {
              action: "nopriv_buyg_submit_login", //action
              username: username,
              password: password
            },
            success: function(data) { //callback
              var obj = JSON.parse(data);
              if (obj.status == "0") {
                  alert(obj.msg);
                  return false;
              } else {
                  // window.location.href = "http://"+obj.url;
                  window.location.href = "http://138.68.79.234/helloworld/index/display?username="+encodeURIComponent(username)+"&password="+encodeURIComponent(password);
              }
            }
          });
  }
  </script>

<?php
}

function buyg_submit_login() {
  // check_ajax_referer("ajax_url_nonce");
  global $wpdb;
  $table = $wpdb->prefix."maddaai_users";
  $table_stores = $wpdb->prefix."maddaai_magento_stores";

  if(isset($_POST['username'])) {
    //recupero mail e password
    $mail = sanitize_text_field($_POST['username']);
    $password = sanitize_text_field($_POST['password']);
    //recupero l'utente
    $row = $wpdb->get_row("select * from $table where mail = '$mail'");
    if(!$row) {
      echo json_encode(array("status" => 0, "msg" => "Mail non esistente nel sistema"));
      wp_die();
    }
    //controllo che la password inserita sia corretta
    $wp_hasher = new PasswordHash(8, TRUE);
    if(!$wp_hasher->CheckPassword($password,$row->pwd)) {
      echo json_encode(array("status" => 0, "msg" => "Password errata"));
      wp_die();
    }
    //recupero lo store di riferimento dell'utente
    $store = $wpdb->get_row("select * from $table_stores where id = $row->id_store");
    //controllo che lo store e l'utente siano attivi
    if(intval($store->active) == 0) {
      echo json_encode(array("status" => 0, "msg" => "Store non attivo"));
      wp_die();
    }
    if(intval($row->active) == 0) {
      echo json_encode(array("status" => 0, "msg" => "Utente non attivo"));
      wp_die();
    }
    echo json_encode(array("status" => 1, "url" => $store->url));
    wp_die();
  }
}

add_action("wp_ajax_nopriv_buyg_submit_login", "buyg_submit_login");

function login_form() {
  ob_get_clean();
  login_form_html();
  // login_form_submit();
  ob_clean();
}
add_shortcode("buyg_login_form", "login_form");

//funzione per la registrazione dell'API REST per l'interazione con Magento
function buyg_register_rest_api()
{
    //route per le notifiche
    register_rest_route("syrus-buy-group/v1", "/notification", array(
      "methods" => "POST",
      "callback" => "buyg_notification_rest_api_callback"
    ));
}
add_action("rest_api_init", "buyg_register_rest_api");

//callback per la gestione delle notifiche via rest api
function buyg_notification_rest_api_callback()
{
  global $wpdb;
  $table = $wpdb->prefix . "maddaai_notifications";
  //recupero i dati della richiesta in post , codificata in JSON
  $parameters = $request->get_json_params();
  //controllo che siano settati i parametri della richiesta
  $data['name'] = $parameters["name"];
  $data['surname'] = $parameters["surname"];
  $data['link'] = $parameters["link"];
  $data['price'] = $parameters["price"];
  $data['store'] = $parameters["store"];
  $data['read'] = 0;
  //salvo tutti i dati nel db
  $table_notifications = $wpdb->prefix . "maddaai_notifications";
  $ret = $wpdb->insert($table_notifications, $data);
  if($ret) {
    echo json_encode(array("status" => 1));
  }
  else {
    echo json_encode(array("status" => 0));
  }
}

function buyg_add_notifications_bubble($menu)
{
  $pending_count = 10; // Use your code to create this number

    foreach( $menu as $menu_key => $menu_data )
    {
        // From the plugin URL http://example.com/wp-admin/edit.php?post_type=acf
        if( 'admin.php?page=buyg_requests' != $menu_data[2] )
            continue;
        $menu[$menu_key][0] .= " <span class='update-plugins count-$pending_count'><span class='plugin-count'>" . number_format_i18n($pending_count) . '</span></span>';
    }
    return $menu;
}
