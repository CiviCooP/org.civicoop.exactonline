<?php
/**
 * Class CRM_ExactOnline_Page_Oauth.
 * This page handles OAuth callbacks / redirects.
 *
 * @author Kevin Levie <kevin.levie@civicoop.org>
 * @package org.civicoop.exactonline
 * @license AGPL-3.0
 */

require_once 'CRM/Core/Page.php';

class CRM_ExactOnline_Page_Oauth extends CRM_Core_Page {

  function run() {

    // Check if we're authorized, and redirect if we're not
    $client = new CRM_ExactOnline_APIClient;
    $client->checkAuth();

    // Redirect to the test page to check if the API connection is working properly
    CRM_Utils_System::redirect(CRM_Utils_System::url('civicrm/financial/exactonline/test', ['reset' => 1]));
    parent::run();
  }

}
