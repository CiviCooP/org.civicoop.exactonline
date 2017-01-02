<?php
/**
 * Class CRM_ExactOnline_Form_Settings.
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 *
 * @author Kevin Levie <kevin.levie@civicoop.org>
 * @package org.civicoop.exactonline
 * @license AGPL-3.0
 */

require_once 'CRM/Core/Form.php';

class CRM_ExactOnline_Form_Settings extends CRM_Core_Form {

  /**
   * @var $myFields array Settings Form Fields
   */
  private $myFields = [
    ['type' => 'text', 'name' => 'exactonline_client_id', 'label' => 'Client ID', 'attributes' => [], 'required' => TRUE],
    ['type' => 'text', 'name' => 'exactonline_client_secret', 'label' => 'Client Secret', 'attributes' => [], 'required' => TRUE],
    ['type' => 'text', 'name' => 'exactonline_webhook_secret', 'label' => 'Webhook Secret', 'attributes' => [], 'required' => TRUE],
  ];

  /**
   * Build form based on the field array above.
   */
  public function buildQuickForm() {

    foreach ($this->myFields as $field) {
      $this->add($field['type'], $field['name'], ts($field['label']), $field['attributes'], $field['required']);
    }

    $this->addButtons([['type' => 'submit', 'name' => ts('Submit'), 'isDefault' => TRUE]]);

    $this->assign('elementNames', $this->getRenderableElementNames());
    parent::buildQuickForm();
  }

  /**
   * Get default values from settings.
   * @return array Values
   */
  public function setDefaultValues() {

    $values = [];
    parent::setDefaultValues();

    foreach ($this->myFields as $field) {
      $values[$field['name']] = CRM_ExactOnline_Config::get($field['name']);
    }

    return $values;
  }

  /**
   * Process form: save all settings.
   */
  public function postProcess() {

    $values = $this->exportValues();
    parent::postProcess();

    foreach ($this->myFields as $field) {
      CRM_ExactOnline_Config::set($field['name'], $values[$field['name']]);
    }

    CRM_Core_Session::setStatus(ts('Exact API Settings Saved'), ts('Exact Integration Settings'), 'success');
    CRM_Utils_System::redirect(CRM_Utils_System::url('civicrm/financial/exactonline'));
  }

  /**
   * Get the fields/elements defined in this form.
   * @return array (string)
   */
  protected function getRenderableElementNames() {
    // The _elements list includes some items which should not be
    // auto-rendered in the loop -- such as "qfKey" and "buttons".  These
    // items don't have labels.  We'll identify renderable by filtering on
    // the 'label'.
    $elementNames = [];
    foreach ($this->_elements as $element) {
      /** @var HTML_QuickForm_Element $element */
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }

}
