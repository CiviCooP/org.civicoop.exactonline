<?php

/**
 * Class CRM_ExactOnline_Config.
 *
 * @author Kevin Levie <kevin.levie@civicoop.org>
 * @package org.civicoop.exactonline
 * @license AGPL-3.0
 */
class CRM_ExactOnline_Config {

  const EXTENSION_NAME = 'org.civicoop.exactonline';

  protected static $instance;

  /**
   * @return static Get instance
   */
  public static function getInstance() {
    if (empty(static::$instance)) {
      static::$instance = new static;
    }
    return static::$instance;
  }

  /**
   * Add Exact Online Dashboard to the Contributions menu. Called from hook_navigationMenu.
   * @see http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
   * @param mixed $params Parameters
   */
  public function addMenuItems(&$params) {

    foreach ($params as &$menu) {
      if (array_key_exists('attributes', $menu) && $menu['attributes']['name'] == 'Contributions') {
        $maxKey = (max(array_keys($menu['child'])));
        $menu['child'][$maxKey + 1] = [
          'attributes' => [
            'label'      => ts('Exact Online Integration'),
            'name'       => ts('Exact Online Integration'),
            'url'        => 'civicrm/financial/exactonline',
            'permission' => 'administer CiviCRM',
            'operator'   => NULL,
            'separator'  => NULL,
            'parentID'   => 2,
            'navID'      => $maxKey + 1,
            'active'     => 1,
          ],
        ];
      }
    }
  }

  /**
   * Get extension setting (shorthand).
   * @param string $name Name
   * @return mixed Value
   */
  public static function get($name) {
    return CRM_Core_BAO_Setting::getItem(static::EXTENSION_NAME, $name);
  }

  /**
   * Set extension setting (shorthand).
   * @param string $name Name
   * @param mixed $value Value
   */
  public static function set($name, $value) {
    CRM_Core_BAO_Setting::setItem($value, static::EXTENSION_NAME, $name);
  }

  /**
   * Get OAuth variables from session.
   */
  public static function getSession($name) {
    return CRM_Core_Session::singleton()->get($name);
  }

  /**
   * Save OAuth variables to session (replace with more permanent storage later!).
   * @param string $name Name
   * @param mixed $value Value
   */
  public static function setSession($name, $value) {
    CRM_Core_Session::singleton()->set($name, $value);
  }

}