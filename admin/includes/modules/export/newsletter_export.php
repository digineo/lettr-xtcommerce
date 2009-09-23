<?php
	/* -----------------------------------------------------------------------------------------
	Newsletter Export Script für XT_Commerce 3.04
	Digineo GmbH 2009 | www.digineo.de
	Author: Tim Kretschmer
	Version 1.0
	Lizenz: GNU 3
	---------------------------------------------------------------------------------------*/

	defined( '_VALID_XTC' ) or die( 'Direct Access to this location is not allowed.' );

	define('MODULE_DIGILETTER_TEXT_DESCRIPTION', 'Export - E-Mail Adressen');
	define('MODULE_DIGILETTER_TEXT_TITLE', 'DigiLetter Export');
	define('MODULE_DIGILETTER_PASSWORD_TITLE' , '<hr noshade>Passwort');
	define('MODULE_DIGILETTER_PASSWORD_DESC' , 'Geben Sie ein sicheres Passwort an. Mit diesem wird Ihre Datei zum Schutz Ihrer Kundendaten gesichert.');
	define('MODULE_DIGILETTER_NO_SPAM_TITLE' , '<hr noshade>Newsletterkunden');
	define('MODULE_DIGILETTER_NO_SPAM_DESC' , 'Wollen Sie nur Newsletter-Kunden exportieren?');
	define('MODULE_DIGILETTER_STATUS_DESC','Modulstatus');
	define('MODULE_DIGILETTER_STATUS_TITLE','Status');
	define('DATE_FORMAT_EXPORT', '%d.%m.%Y');  

  class newsletter_export {
    var $code, $title, $description, $enabled;

    function newsletter_export() {
      global $order;

      $this->code = 'newsletter_export';
      $this->title = MODULE_DIGILETTER_TEXT_TITLE;
      $this->description = MODULE_DIGILETTER_TEXT_DESCRIPTION;
      $this->enabled = ((MODULE_DIGILETTER_STATUS == 'True') ? true : false);
      $this->CAT=array();
      $this->PARENT=array();

    }
	
	function display() {
		$customers_statuses_array = xtc_get_customers_statuses();		
		return array('text' => '<br />' . xtc_button("Speichern") .
						  xtc_button_link(BUTTON_CANCEL, xtc_href_link(FILENAME_MODULE_EXPORT, 'set=' . $_GET['set'] . '&module=newsletter_export')));
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = xtc_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_DIGILETTER_STATUS'");
        $this->_check = xtc_db_num_rows($check_query);
      }
      return $this->_check;
    }

	function install() {
		xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_DIGILETTER_PASSWORD', 'yourePassword', '6', '1', '', now())");
		xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_DIGILETTER_NO_SPAM', 'True', '6', '1', 'xtc_cfg_select_option(array(\'True\', \'False\'), ', now())");
		xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_DIGILETTER_STATUS', 'True',  '6', '1', 'xtc_cfg_select_option(array(\'True\', \'False\'), ', now())");
		
	}

    function remove() {
      xtc_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
	  xtc_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_DIGILETTER_SHIPPING_COST'");
    }

    function keys() {
      return array('MODULE_DIGILETTER_STATUS','MODULE_DIGILETTER_PASSWORD', 'MODULE_DIGILETTER_NO_SPAM');
    }
 
  }
?>