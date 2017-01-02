<?php
/**
 * Class CRM_ExactOnline_Upgrader.
 * Collection of upgrade steps.
 *
 * @author Kevin Levie <kevin.levie@civicoop.org>
 * @package org.civicoop.exactonline
 * @license AGPL-3.0
 */

class CRM_ExactOnline_Upgrader extends CRM_ExactOnline_Upgrader_Base {

  public function install() {

    // Check if Composer has run to install the Exact API client
    $extroot = realpath(__DIR__ . '/../../');
    if(!file_exists($extroot . '/vendor') || !file_exists($extroot . '/vendor/autoload.php')) {
      throw new CRM_ExactOnline_Exception('Run composer to install the Exact API client before enabling this extension!');
    }

  }

  /*
  public function uninstall() {}
  public function enable() {}
  public function disable() {}
  public function upgrade_4700() {}
  */

}
