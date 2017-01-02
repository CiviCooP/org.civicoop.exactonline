<?php
/**
 * Class CRM_ExactOnline_Page_Dashboard.
 *
 * @author Kevin Levie <kevin.levie@civicoop.org>
 * @package org.civicoop.exactonline
 * @license AGPL-3.0
 */

require_once 'CRM/Core/Page.php';

class CRM_ExactOnline_Page_Dashboard extends CRM_Core_Page {

  function run() {
    CRM_Utils_System::setTitle(ts('Exact Integration Dashboard'));
    parent::run();
  }

}
