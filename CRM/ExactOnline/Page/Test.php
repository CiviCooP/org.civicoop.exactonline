<?php
/**
 * Class CRM_ExactOnline_Page_Test.
 * Test page to confirm the Exact API connection is working properly.
 *
 * @author Kevin Levie <kevin.levie@civicoop.org>
 * @package org.civicoop.exactonline
 * @license AGPL-3.0
 */

require_once 'CRM/Core/Page.php';

class CRM_ExactOnline_Page_Test extends CRM_Core_Page {

  function run() {

    // Connect to Exact
    $client = new CRM_ExactOnline_APIClient;
    $client->connect(TRUE);

    // Get some data to show!

    $this->assign('oauth_expires', CRM_ExactOnline_Config::getSession('exactonline_expires_in'));

    /** @var \Picqer\Financials\Exact\Contact $contacts */
    $contacts = $client->getEntity('Contact');
    $this->assign('contacts', $contacts->get());

    /** @var \Picqer\Financials\Exact\SalesInvoice $invoices */
    $invoices = $client->getEntity('SalesInvoice');
    $this->assign('invoices', $invoices->get());

    /** @var \Picqer\Financials\Exact\Item $items */
    $items = $client->getEntity('Item');
    $this->assign('items', $items->get());

    /** @var \Picqer\Financials\Exact\Journal $journals */
    $journals = $client->getEntity('Journal');
    $this->assign('journals', $journals->get());

    /** @var \Picqer\Financials\Exact\Me $me */
    $me = $client->getEntity('Me');
    $this->assign('me', $me->find());

    parent::run();
  }

}
