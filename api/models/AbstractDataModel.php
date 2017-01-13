<?php
require_once 'db/config.php';
require_once 'vendor/autoload.php';

abstract class AbstractDataModel {

   private $dbh = null;

   private $row_limit = 50;

   function __construct() {

   }

   public function get_row_limit() {
      return $this->row_limit;
   }
   
   function connect_portal_db() {
      // Establish a connection to the database server.
      if($this->portal_dbh == null) {
         $this->portal_dbh = mysqli_connect(DB_PORTAL_SERVER, DB_PORTAL_USER, DB_PORTAL_PASS, DB_PORTAL_NAME, DB_PORTAL_PORT);
         if (mysqli_connect_errno()) {
            $err_params = array();
            $err_params['sql_error'] = mysqli_connect_error($this->portal_dbh);
            $err_params['db_host'] = DB_PORTAL_SERVER;
            $err_params['db_name'] = DB_PORTAL_NAME;
            return false;
         }
      }
      return true;
   }
   function get_portal_dbh() {
      if($this->portal_dbh == null) {
         $this->connect_portal_db();
      }
      return $this->portal_dbh;
   }
   
   function connect_itop_db() {
      // Establish a connection to the database server.
      if($this->itop_dbh == null) {
         $this->itop_dbh = mysqli_connect(DB_ITOP_SERVER, DB_ITOP_USER, DB_ITOP_PASS, DB_ITOP_NAME, DB_ITOP_PORT);
         if (mysqli_connect_errno()) {
            $err_params = array();
            $err_params['sql_error'] = mysqli_connect_error($this->itop_dbh);
            $err_params['db_host'] = DB_ITOP_SERVER;
            $err_params['db_name'] = DB_ITOP_NAME;
            return false;
         }
      }
      return true;
   }
   function get_itop_dbh() {
      if($this->itop_dbh == null) {
         $this->connect_itop_db();
      }
      return $this->itop_dbh;
   }
   
   function do_curl($uri) {
      // inject common variables to data container
        $data['tid'] = 1;
        $data['type'] = "rpc";
        // fetch authorization cookie
        $ch = curl_init("https://monitor.coredial.com:443/zport/acl_users/cookieAuthHelper/login");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, "jfifer:zB7JTp");
        curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        curl_exec($ch);
        // execute xmlrpc action
        curl_setopt($ch, CURLOPT_URL, "https://monitoring.coredial:443{$uri}");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $result = curl_exec($ch);
        // error handling
        if($result===false)
            throw new Exception('Curl error: ' . curl_error($ch));
        // cleanup
        curl_close($ch);
        return $result;
   }
   
   function convert_to_array2($dataResource) {
      $newArray = array();
      $var_type = gettype($dataResource);
      if ($var_type == "object") {
         for ($i = 0; $i < mysqli_num_rows($dataResource); $i++) {
            $data = mysqli_fetch_assoc($dataResource);
            foreach ($data as $key => $value) {
               $newArray[$i][$key] = $value;
            }
         }
      }
      return $newArray;
   }
   
   function last_insert_id() {
        return $this->get_dbh()->insert_id;
    }
};
