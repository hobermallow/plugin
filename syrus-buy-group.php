<?php
/*
Plugin Name: Syrus Buy Group
*/

//controllo che non si possa accedere direttamente al file del plugin
defined( 'ABSPATH' ) or die("Non e' possibile accedere al file");

//funzione per l'aggiunta dei file necessari di bootstrap
function prefix_enqueue()
{
    // JS
    wp_register_script('buyg_bootstrap', '/wp-content/plugins/syrus-buy-group/admin/js/bootstrap.min.js');
    wp_enqueue_script('buyg_bootstrap');

    // CSS
    wp_register_style('buyg_bootstrap', '/wp-content/plugins/syrus-buy-group/admin/css/bootstrap.min.css');
    wp_enqueue_style('buyg_bootstrap');
}

//hook per aggiungere gli script all'head di ogni pagina

function buyg_options_page_html() {
  //aggiungo bootstrap
  prefix_enqueue();
  global $wpdb;
  //recupero gli store dal database
  $table = $wpdb->prefix."magento_stores";

  $stores = $wpdb->get_results("SELECT $table.id, $table.label, $table.url, INET_NTOA($table.starting_ip) AS starting_ip, INET_NTOA($table.ending_ip) AS ending_ip  FROM $table");
  // echo var_dump("SELECT $table.id, $table.lable, $table.url, INET_NTOA($table.starting_ip) AS starting_ip, INET_NTOA($table.ending_ip) AS ending_ip  FROM $table");
  ?>
  <div class="wrap">
    <div class="row">
      <div class="col-lg-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Lista degli store disponibili</h3>
      </div>
      <div class="panel-body">
        <table class="col-lg-12 table table-hover">
          <thead>
            <tr>
            <th>
              Id
            </th>
            <th>
              Label
            </th>
            <th>
              Url
            </th>
            <th>
              Ip Iniziale
            </th>
            <th>
              Ip Finale
            </th>
            <th>
              Azioni
            </th>
          </tr>
          </thead>
          <tbody>
            <?php foreach($stores as $store): ?>
              <?php
              $link = add_query_arg(
                array(
                  'page' => 'buyg-mod-details', // as defined in the hidden page
                  'id_store' => $store->id
                ),
                admin_url('admin.php')
              );
               ?>
              <tr style="cursor:pointer" onclick="window.location = '<?php echo $link; ?>'" >
                <td>
                  <?php echo $store->id; ?>
                </td>
                <td>
                  <?php echo $store->label; ?>
                </td>
                <td>
                  <?php echo $store->url; ?>
                </td>
                <td>
                  <?php echo $store->starting_ip; ?>
                </td>
                <td>
                  <?php echo $store->ending_ip; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  </div
        <h1><?= esc_html(get_admin_page_title()); ?></h1>

        <form action="options.php" method="post">
            <?php
            // output save settings button
            submit_button('Save Settings');
            ?>
        </form>
    </div>
    <?php
}

function buyg_options_mod_details_page_html() {
  //aggiungo bootstrap
  prefix_enqueue();
  //db
  global $wpdb;
  //recupero l'id dello store dalla richiesta
  $id_store = $_GET['id_store'];
  //recupero lo store dal db
  $store = $wpdb->get_row("SELECT $table.id, $table.label, $table.url, INET_NTOA($table.starting_ip) AS starting_ip, INET_NTOA($table.ending_ip) AS ending_ip  FROM $table WHERE id = $id_store");
  ?>
  <div class="wrap">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"></h3>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-lg-12">
            <div class="form-group">
              <label for="label">Label</label>
              <input type="text" value="<?php echo $store->label; ?>" class="form-control" name="label" id="label" placeholder="">
              <p class="help-block">Help text here.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php
}
//funzione per l'aggiunta della pagina di gestione del plugin alla voce del menu tools
function buyg_options_page()
{
    //aggiungo la pagina generale di gestione del plugin
    add_submenu_page(
        'tools.php',
        '',
        'Buy Group Options',
        'manage_options',
        'buyg',
        'buyg_options_page_html'
    );

    //aggiungo una pagina non visibile dal menu' per l'aggiunta di un nuovo store
    add_submenu_page(
      null,
      '',
      '',
      'manage_options',
      'buyg-mod-details',
      'buyg_options_mod_details_page_html'
     );
}
//aggiungo la funzione all'hook
add_action('admin_menu', 'buyg_options_page');


//funzione per la creazione della tabella di relationship fra utenti e store
//e della tabella per contenere la lista degli store con le relative classi di ip
function buyg_install_database() {
  global $wpdb;

  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	$table_name_relationship = $wpdb->prefix . 'relationships_users_stores';

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name_relationship (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
    user_id bigint(20) unsigned NOT NULL,
    store_id bigint(20) unsigned NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	dbDelta( $sql );

  //creo la seconda query per la creazione della tabella della gestione degli store
  $table_name_store = $wpdb->prefix . 'magento_stores';

  $sql = "CREATE TABLE $table_name_store (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    label text NOT NULL,
    url varchar(255) NOT NULL,
    starting_ip int unsigned NULL,
    ending_ip int unsigned NULL,
    PRIMARY KEY (id)
  ) $charset_collate;";

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
