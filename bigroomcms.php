<?php
//base cms system for demo website, simple cms modified. 
class bigroomCMS {
//swap these for other server info when reusing
  var $host = 'localhost';
  var $username = 'root';
  var $password = '@Brainbender1.';
  var $table ='demo';

  public function display_public() {
    $query = "SELECT * FROM demoDB ORDER BY created DESC LIMIT 10";
    $m = mysql_query($query);

    if ( $r !== false && mysql_num_rows($m) > 0 ) {
      while ( $a = mysql_fetch_assoc($m) ) {
        $title = stripslashes($a['title']);
        $bodytext = stripslashes($a['bodytext']);

        $entry_display .= <<<ENTRY_DISPLAY

    <div class="post">
    	<h2>
    		$title
    	</h2>
	    <p>
	      $bodytext
	    </p>
	</div>

ENTRY_DISPLAY;
      }
    } else {
      $entry_display = <<<ENTRY_DISPLAY

    <h2> Welcome </h2>
    <p>
      You have not posted anything yet. <BR>Please click the
      link below to add an entry!
    </p>

ENTRY_DISPLAY;
    }
    $entry_display .= <<<ADMIN_OPTION

    <p class="admin_link">
      <a href="{$_SERVER['PHP_SELF']}?admin=1">Add a new entry.</a>
    </p>

ADMIN_OPTION;

    return $entry_display;
  }

  public function display_admin() {
    return <<<ADMIN_FORM

    <form action="{$_SERVER['PHP_SELF']}" method="post">
    
      <label for="title">Title:</label><br />
      <input name="title" id="title" type="text" maxlength="250" />
      <div class="clear"></div>
     
      <label for="bodytext">Body Text:</label><br />
      <textarea name="bodytext" id="bodytext"></textarea>
      <div class="clear"></div>
      
      <input type="submit" value="Submit this entry." />
    </form>
    
    <br />
    
    <a href="display.php">Home</a>

ADMIN_FORM;
  }

  public function write($p) {
    if ( $_POST['title'] )
      $title = mysql_real_escape_string($_POST['title']);
    if ( $_POST['bodytext'])
      $bodytext = mysql_real_escape_string($_POST['bodytext']);
    if ( $title && $bodytext ) {
      $created = date('l dS F Y');;
      $sql = "INSERT INTO demoDB VALUES('$title','$bodytext','$created')";
      return mysql_query($sql);
    } else {
      return false;
    }
  }

  public function connect() {
    mysql_connect($this->host,$this->username,$this->password) or die("Connection or authentication failure." . mysql_error());
    mysql_select_db($this->table) or die("Could not select database. " . mysql_error());

    return $this->buildDB();
  }

  private function buildDB() {
    $sql = <<<MySQL_QUERY
CREATE TABLE IF NOT EXISTS demoDB (
title		VARCHAR(50),
bodytext	TEXT,
created		VARCHAR(50)
)
MySQL_QUERY;

    return mysql_query($sql);
  }

}

?>
