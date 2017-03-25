<?php

/**
 *
 */
class Store_List_Table extends WP_List_Table
{

  function __construct()
  {
    parent::__construct( array(
      'singular'=> 'wp_list_text_store', //Singular label
      'plural' => 'wp_list_test_stores', //plural label, also this well be one of the table css class
      'ajax'   => false //We won't support Ajax for this table
      ) );
  }

  //funzione per impostare le colonne della tabella
  function get_columns() {
   return $columns= array(
      'col_store_cb' => '',
      'col_store_id'=> 'ID',
      'col_store_label'=> 'Store',
      'col_store_url'=> 'Url Store',
      'col_store_starting_ip'=> 'Classe Ip',
      'col_store_ending_ip'=> 'Range Classe Ip',
      'col_store_active'=> 'Status',
      'col_store_actions'=> 'Azioni',
   );
 }

 //funzione per indicare quali colonne sono ordinabili
 function get_sortable_columns() {
   return $columns = array(
     'col_store_id' => array('id', false),
     'col_store_label' => array('label', false)
   );
 }

//funzione per preparare gli elementi della tabella
function prepare_items() {
   global $wpdb, $_wp_column_headers;
   $screen = get_current_screen();
   $table = $wpdb->prefix. "maddaai_magento_stores";

        $query = "SELECT $table.id, $table.label, $table.url, INET_NTOA($table.starting_ip) AS starting_ip, INET_NTOA($table.ending_ip) AS ending_ip, $table.active  FROM $table";
        // ricerca del termine
        $search = isset($_POST["s"]) ? sanitize_text_field($_POST["s"]) : '';
        $reset = isset($_POST["reset"]) ? sanitize_text_field($_POST["reset"]) : '';
        if(!empty($search) && empty($reset)) {
          $query .= " WHERE label LIKE '%".$search."%' ";
        }
        else {
          unset($_POST['s']);
          $orderby = isset($_GET["orderby"]) ? sanitize_text_field($_GET["orderby"]) : 'ASC';
          $order = isset($_GET["order"]) ? sanitize_text_field($_GET["order"]) : '';

          if(!empty($orderby) & !empty($order)){ $query.=' ORDER BY '.$orderby.' '.$order; }


      /* -- Pagination parameters -- */
           //Number of elements in your table?
           $totalitems = $wpdb->query($query); //return the total number of affected rows
           //How many to display per page?
           $perpage = 5;
           //Which page is this?
           $paged = isset($_GET["paged"]) ? sanitize_text_field($_GET["paged"]) : '';
           //Page Number
           if(empty($paged) || !is_numeric($paged) || $paged<=0 ){
             $paged=1;
           }
          /*How many pages do we have in total? */
           $totalpages = ceil($totalitems/$perpage);
           /*adjust the query to take pagination into account */
           if(!empty($paged) && !empty($perpage)){
             $offset=($paged-1)*$perpage;
             $query.=' LIMIT '.(int)$offset.','.(int)$perpage;
           }
            /* -- Register the pagination -- */
           $this->set_pagination_args( array(
            "total_items" => $totalitems,
            "total_pages" => $totalpages,
            "per_page" => $perpage,
          ));
        }

      //The pagination links are automatically built according to those parameters

   /* -- Register the Columns -- */
      $columns = $this->get_columns();
      $_wp_column_headers[$screen->id]=$columns;

   /* -- Fetch the items -- */
      $this->items = $wpdb->get_results($query);
      //setto i column headers
      $this->_column_headers = array($this->get_columns(), array(), $this->get_sortable_columns(), "col_store_id");
}

//display delle righe
function display_rows() {

   //Get the records registered in the prepare_items method
   $records = $this->items;
   $screen = get_current_screen();
  //  $columns = $_wp_column_headers[$screen->id];
  //  $hidden = array();


   //Get the columns registered in the get_columns and get_sortable_columns methods
   list( $columns, $hidden ) = $this->get_column_info();

   //Loop for each record
   if(!empty($records)){foreach($records as $rec){

      //Open the line
        echo '<tr  id="record_'.$rec->id.'">';
      foreach ( $columns as $column_name => $column_display_name ) {
         //Style attributes for each col
         $class = "class='$column_name column-$column_name'";
         $style = "";
         if ( in_array( $column_name, $hidden ) ) $style = ' style="display:none;"';
         $attributes = $class . $style;

         //edit link
         $editlink  = '/wp-admin/link.php?action=edit&link_id='.(int)$rec->id;
         //Display the cell
         switch ( $column_name ) {
            case "col_store_cb" : echo '<td '.$attributes.'><input type="checkbox" name="store[]" value="'.$rec->id.'" ></td>'; break;
            case "col_store_id":  echo '<td '.$attributes.'>'.stripslashes($rec->id);
                                  echo $this->column_col_store_id($rec).'</td>';
                                  break;
            case "col_store_label": echo '<td '.$attributes.'>'.stripslashes($rec->label).'</td>'; break;
            case "col_store_url": echo '<td '.$attributes.'>'.stripslashes($rec->url).'</td>'; break;
            case "col_store_starting_ip": echo '<td '.$attributes.'>'.$rec->starting_ip.'</td>'; break;
            case "col_store_ending_ip": echo '<td '.$attributes.'>'.$rec->ending_ip.'</td>'; break;
            case "col_store_active": echo '<td '.$attributes.'>'.($rec->active == "1" ? "Attivo" :  "Non Attivo").' </td>'; break;
            // case "col_store_actions": echo '<td '.$attributes.'><input type="button" class="button" value="Modifica" onclick="event.stopPropagation(); prepareModStore('.$rec->id.')" >'; break;
         }
      }

      //Close the line
      echo'</tr>';
    }
    }
  }

  function get_bulk_actions() {
    $actions = array(
      'buyg_activate_stores' => 'Attiva',
      'buyg_deactivate_stores' => 'Disattiva',
      'buyg_delete_stores'    => 'Elimina'
    );
    return $actions;
  }

  function column_col_store_id($item) {
        $actions = array(
            'edit'      => '<a onclick="prepareModStore('.$item->id.')">Modifica</a>',
        );
        return $this->row_actions($actions);
  }


}


class Users_List_Table extends WP_List_Table
{

  function __construct()
  {
    parent::__construct( array(
      'singular'=> 'wp_list_text_user', //Singular label
      'plural' => 'wp_list_test_users', //plural label, also this well be one of the table css class
      'ajax'   => false //We won't support Ajax for this table
      ) );
  }

  //funzione per impostare le colonne della tabella
  function get_columns() {
   return $columns= array(
      'col_user_cb' => '',
      'col_user_id'=> 'ID',
      'col_user_name'=> 'Nome',
      'col_user_surname'=> 'Cognome',
      'col_user_mail'=> 'Mail',
      'col_user_warning'=> 'Bid Non Pagate',
      'col_user_active'=> 'Attivo',
      'col_user_magento_store'=> 'Store',
      'col_user_actions'=> 'Azioni',
   );
 }

 //funzione per indicare quali colonne sono ordinabili
 function get_sortable_columns() {
   return $columns = array(
     'col_user_id' => array('id', false),
     'col_user_name' => array('name', false),
     'col_user_surname' => array('surname', false)
   );
 }

//funzione per preparare gli elementi della tabella
function prepare_items() {
   global $wpdb, $_wp_column_headers;
   $screen = get_current_screen();
   $table = $wpdb->prefix. "maddaai_users";
   $table_join = $wpdb->prefix. "maddaai_magento_stores";

        $query = "SELECT $table.*, $table_join.label FROM $table LEFT JOIN $table_join ON $table.id_store = $table_join.id";
        // ricerca del termine
        $search = isset($_POST["s"]) ? sanitize_text_field($_POST["s"]) : '';
        $reset = isset($_POST["reset"]) ? sanitize_text_field($_POST["reset"]) : '';
        if(!empty($search) && empty($reset)) {
          $query .= " WHERE (name LIKE '%".$search."%' OR surname LIKE '%".$search."%) AND deleted = 0' ";
        }
        else {
          $query .= " WHERE deleted = 0 ";
          unset($_POST['s']);
          $orderby = isset($_GET["orderby"]) ? sanitize_text_field($_GET["orderby"]) : 'ASC';
          $order = isset($_GET["order"]) ? sanitize_text_field($_GET["order"]) : '';

          if(!empty($orderby) & !empty($order)){ $query.=' ORDER BY '.$orderby.' '.$order; }


      /* -- Pagination parameters -- */
           //Number of elements in your table?
           $totalitems = $wpdb->query($query); //return the total number of affected rows
           //How many to display per page?
           $perpage = 5;
           //Which page is this?
           $paged = isset($_GET["paged"]) ? sanitize_text_field($_GET["paged"]) : '';
           //Page Number
           if(empty($paged) || !is_numeric($paged) || $paged<=0 ){
             $paged=1;
           }
          /*How many pages do we have in total? */
           $totalpages = ceil($totalitems/$perpage);
           /*adjust the query to take pagination into account */
           if(!empty($paged) && !empty($perpage)){
             $offset=($paged-1)*$perpage;
             $query.=' LIMIT '.(int)$offset.','.(int)$perpage;
           }
            /* -- Register the pagination -- */
           $this->set_pagination_args( array(
            "total_items" => $totalitems,
            "total_pages" => $totalpages,
            "per_page" => $perpage,
          ));
        }

      //The pagination links are automatically built according to those parameters

   /* -- Register the Columns -- */
      $columns = $this->get_columns();
      $_wp_column_headers[$screen->id]=$columns;

   /* -- Fetch the items -- */
      $this->items = $wpdb->get_results($query);
      //setto i column headers
      $this->_column_headers = array($this->get_columns(), array(), $this->get_sortable_columns(), "col_user_id");
}

//display delle righe
function display_rows() {

   //Get the records registered in the prepare_items method
   $records = $this->items;
   $screen = get_current_screen();
  //  $columns = $_wp_column_headers[$screen->id];
  //  $hidden = array();


   //Get the columns registered in the get_columns and get_sortable_columns methods
   list( $columns, $hidden ) = $this->get_column_info();

   //Loop for each record
   if(!empty($records)){foreach($records as $rec){

      //Open the line
        echo '<tr  id="record_'.$rec->id.'">';
      foreach ( $columns as $column_name => $column_display_name ) {
         //Style attributes for each col
         $class = "class='$column_name column-$column_name'";
         $style = "";
         if ( in_array( $column_name, $hidden ) ) $style = ' style="display:none;"';
         $attributes = $class . $style;

         //edit link
         $editlink  = '/wp-admin/link.php?action=edit&link_id='.(int)$rec->id;
         //Display the cell
         switch ( $column_name ) {
            case "col_user_cb" : echo '<td width="5%" '.$attributes.'><input type="checkbox" name="user[]" value="'.$rec->id.'" ></td>'; break;
            case "col_user_id":  echo '<td width="5%" '.$attributes.'>';
                                 echo stripslashes($rec->id);
                                 echo $this->column_col_user_id($rec).'</td>';
                                 break;
            case "col_user_name":  echo '<td width="10%" '.$attributes.'>'.stripslashes($rec->name).'</td>';   break;
            case "col_user_surname": echo '<td width="10%" '.$attributes.'>'.stripslashes($rec->surname).'</td>'; break;
            case "col_user_mail": echo '<td width="20%" '.$attributes.'>'.stripslashes($rec->mail).'</td>'; break;
            case "col_user_warning": echo '<td width="5%" '.$attributes.'>'.$rec->warning.'</td>'; break;
            case "col_user_magento_store": echo '<td width="10%" '.$attributes.'>'.$rec->label.'<input type="hidden" name="id_store" value="'.$rec->id_store.'"></td>'; break;
            case "col_user_active": echo '<td width="5%" '.$attributes.'>'.($rec->active == "1" ? "Attivo" :  " Non attivo").' </td>'; break;
         }
      }

      //Close the line
      echo'</tr>';
    }
    }
  }

  function get_bulk_actions() {
  $actions = array(
    'buyg_activate_users' => 'Attiva',
    'buyg_deactivate_users' => 'Disattiva',
    'buyg_delete_users'    => 'Elimina'
  );
  return $actions;
  }

  function column_col_user_id($item) {
        $actions = array(
            'edit'      => '<a onclick="prepareModUser('.$item->id.')">Modifica</a>',
        );
        return $this->row_actions($actions);
    }


}


class Notifications_List_Table extends WP_List_Table
{

  function __construct()
  {
    parent::__construct( array(
      'singular'=> 'wp_list_text_notification', //Singular label
      'plural' => 'wp_list_text_notifications', //plural label, also this well be one of the table css class
      'ajax'   => false //We won't support Ajax for this table
      ) );
  }

  //funzione per impostare le colonne della tabella
  function get_columns() {
   return $columns= array(
      'col_notification_cb' => '',
      'col_notification_id'=> 'ID',
      'col_notification_name'=> 'Nome',
      'col_notification_surname'=> 'Cognome',
      'col_notification_link'=> 'Link',
      'col_notification_price'=> 'Prezzo',
      'col_notification_store'=> 'Store',
      'col_notification_create_date'=> 'Data Creazione',
      'col_notification_read'=> 'Letta?',
      'col_notification_actions'=> 'Azioni',
   );
 }

 //funzione per indicare quali colonne sono ordinabili
 function get_sortable_columns() {
   return $columns = array(
     'col_notification_id' => array('id', false),
     'col_notification_name' => array('name', false),
     'col_notification_surname' => array('surname', false),
     'col_notification_create_date' => array('create_date', false),
   );
 }

//funzione per preparare gli elementi della tabella
function prepare_items() {
   global $wpdb, $_wp_column_headers;
   $screen = get_current_screen();
   $table = $wpdb->prefix. "maddaai_notifications";

        $query = "SELECT * FROM $table";
        $orderby = isset($_GET["orderby"]) ? sanitize_text_field($_GET["orderby"]) : 'ASC';
        $order = isset($_GET["order"]) ? sanitize_text_field($_GET["order"]) : '';

        if(!empty($orderby) & !empty($order)){ $query.=' ORDER BY '.$orderby.' '.$order; }


      /* -- Pagination parameters -- */
           //Number of elements in your table?
           $totalitems = $wpdb->query($query); //return the total number of affected rows
           //How many to display per page?
           $perpage = 5;
           //Which page is this?
           $paged = isset($_GET["paged"]) ? sanitize_text_field($_GET["paged"]) : '';
           //Page Number
           if(empty($paged) || !is_numeric($paged) || $paged<=0 ){
             $paged=1;
           }
          /*How many pages do we have in total? */
           $totalpages = ceil($totalitems/$perpage);
           /*adjust the query to take pagination into account */
           if(!empty($paged) && !empty($perpage)){
             $offset=($paged-1)*$perpage;
             $query.=' LIMIT '.(int)$offset.','.(int)$perpage;
           }
            /* -- Register the pagination -- */
           $this->set_pagination_args( array(
            "total_items" => $totalitems,
            "total_pages" => $totalpages,
            "per_page" => $perpage,
          ));
        

      //The pagination links are automatically built according to those parameters

   /* -- Register the Columns -- */
      $columns = $this->get_columns();
      $_wp_column_headers[$screen->id]=$columns;

   /* -- Fetch the items -- */
      $this->items = $wpdb->get_results($query);
      //setto i column headers
      $this->_column_headers = array($this->get_columns(), array(), $this->get_sortable_columns(), "col_notification_id");
}

//display delle righe
function display_rows() {

   //Get the records registered in the prepare_items method
   $records = $this->items;
   $screen = get_current_screen();
  //  $columns = $_wp_column_headers[$screen->id];
  //  $hidden = array();


   //Get the columns registered in the get_columns and get_sortable_columns methods
   list( $columns, $hidden ) = $this->get_column_info();

   //Loop for each record
   if(!empty($records)){foreach($records as $rec){

      //Open the line
        echo '<tr  id="record_'.$rec->id.'">';
      foreach ( $columns as $column_name => $column_display_name ) {
         //Style attributes for each col
         $class = "class='$column_name column-$column_name'";
         $style = "";
         if ( in_array( $column_name, $hidden ) ) $style = ' style="display:none;"';
         $attributes = $class . $style;

         //edit link
         $editlink  = '/wp-admin/link.php?action=edit&link_id='.(int)$rec->id;
         //Display the cell
         switch ( $column_name ) {
            case "col_notification_cb" : echo '<td width="5%" '.$attributes.'><input type="checkbox" name="notification[]" value="'.$rec->id.'" ></td>'; break;
            case "col_notification_id":  echo '<td width="5%" '.$attributes.'>';
                                 echo stripslashes($rec->id);
                                 echo $this->column_col_notification_id($rec).'</td>';
                                 break;
            case "col_notification_name":  echo '<td width="10%" '.$attributes.'>'.stripslashes($rec->name).'</td>';   break;
            case "col_notification_surname": echo '<td width="10%" '.$attributes.'>'.stripslashes($rec->surname).'</td>'; break;
            case "col_notification_link": echo '<td width="10%" '.$attributes.'>'.stripslashes($rec->link).'</td>'; break;
            case "col_notification_price": echo '<td width="10%" '.$attributes.'>'.stripslashes($rec->price).'</td>'; break;
            case "col_notification_store": echo '<td width="10%" '.$attributes.'>'.$rec->store.'</td>'; break;
            case "col_notification_create_date": echo '<td width="10%" '.$attributes.'>'.$rec->create_date.'</td>'; break;
            case "col_notification_read": echo '<td width="5%" '.$attributes.'>'.($rec->read == "1" ? "Letta" :  " Non letta").' </td>'; break;
         }
      }

      //Close the line
      echo'</tr>';
    }
    }
  }

  function get_bulk_actions() {
  $actions = array(
    'buyg_read_notifications' => 'Segna come lette',
    'buyg_unread_notifications' => 'Segna come non lette',
    'buyg_delete_notifications'    => 'Elimina'
  );
  return $actions;
  }

  function column_col_notification_id($item) {
        $actions = array(
            'edit'      => '<a onclick="prepareModNotification('.$item->id.')">Modifica</a>',
        );
        return $this->row_actions($actions);
    }


}
