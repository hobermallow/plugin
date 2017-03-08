<?php
/*
Plugin Name: Syrus Buy Group
*/

//controllo che non si possa accedere direttamente al file del plugin
defined( 'ABSPATH' ) or die("Non e' possibile accedere al file");

function buyg_options_page_html() {
  global $wpdb;
  //recupero gli store dal database
  $table = $wpdb->prefix."magento_stores";

  $stores = $wpdb->get_results("SELECT * FROM $table");
  ?>
  <div class="wrap">
        <h1><?= esc_html(get_admin_page_title()); ?></h1>
        <?php foreach($stores as $store): ?>
          <div class="row">
            <?php echo var_dump($store); ?>
          </div>
        <?php endforeach; ?>
        <form action="options.php" method="post">
            <?php
            // output save settings button
            submit_button('Save Settings');
            ?>
        </form>
    </div>
    <?php
}
//funzione per l'aggiunta della pagina di gestione del plugin alla voce del menu tools
function buyg_options_page()
{
    add_submenu_page(
        'tools.php',
        'Buy Group Options',
        'Buy Group Options',
        'manage_options',
        'buyg',
        'buyg_options_page_html'
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
