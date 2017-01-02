<?php

require_once __DIR__ . '/../../vendor/autoload.php';

/**
 * Class CRM_ExactOnline_APIClient.
 * Wrapper for Exact\Connection that handles getting/storing configuration in CiviCRM.
 *
 * @author Kevin Levie <kevin.levie@civicoop.org>
 * @package org.civicoop.exactonline
 * @license AGPL-3.0
 */
class CRM_ExactOnline_APIClient {

  /**
   * @var $connection \Picqer\Financials\Exact\Connection
   */
  private $connection;

  /**
   * CRM_ExactOnline_APIClient constructor.
   * Initialise this class and configure the Exact API connection.
   */
  public function __construct() {
    $this->connection = new \Picqer\Financials\Exact\Connection;
    $this->initConnection();
  }

  /**
   * Configure the Exact API connection from settings saved in CiviCRM.
   * @throws CRM_ExactOnline_Exception Thrown when API key is not configured
   */
  protected function initConnection() {

    // Set redirect URL
    $redirectUrl = CRM_Utils_System::url('civicrm/financial/exactonline/oauth', ['reset' => 1], TRUE);
    $this->connection->setRedirectUrl($redirectUrl);

    // Get API settings as configured by admin
    $clientId = CRM_ExactOnline_Config::get('exactonline_client_id');
    $clientSecret = CRM_ExactOnline_Config::get('exactonline_client_secret');

    if (empty($clientId) || empty($clientSecret)) {
      throw new CRM_ExactOnline_Exception(ts('Could not connect to Exact: API key not configured!'));
    }

    $this->connection->setExactClientId($clientId);
    $this->connection->setExactClientSecret($clientSecret);

    // Get stored OAuth token data if available - stored in session temporarily for testing
    $authorizationCode = CRM_ExactOnline_Config::getSession('exactonline_authorization_code');
    if (!empty($authorizationCode)) {
      $this->connection->setAuthorizationCode($authorizationCode);
    }

    $accessToken = CRM_ExactOnline_Config::getSession('exactonline_access_token');
    if (!empty($accessToken)) {
      $this->connection->setAccessToken($accessToken);
    }

    $refreshToken = CRM_ExactOnline_Config::getSession('exactonline_refresh_token');
    if (!empty($refreshToken)) {
      $this->connection->setRefreshToken($refreshToken);
    }

    $expiresIn = CRM_ExactOnline_Config::getSession('exactonline_expires_in');
    if (!empty($expiresIn)) {
      $this->connection->setTokenExpires($expiresIn);
    }

    // Set callback to save newly generated tokens
    $this->connection->setTokenUpdateCallback(function (\Picqer\Financials\Exact\Connection $connection) {
      // Save new tokens for next connections, and the expire time
      CRM_ExactOnline_Config::setSession('exactonline_access_token', $connection->getAccessToken());
      CRM_ExactOnline_Config::setSession('exactonline_refresh_token', $connection->getRefreshToken());
      CRM_ExactOnline_Config::setSession('exactonline_expires_in', $connection->getTokenExpires());
    });
  }

  /**
   * Check if we're authorized, or if we just got an authorization code from Exact.
   * If not, redirect for authorization. (For interactive requests only, see Page/Oauth.)
   */
  public function checkAuth() {

    $authorizationCode = CRM_ExactOnline_Config::getSession('exactonline_authorization_code');
    if (is_null($authorizationCode)) {
      if (isset($_GET['code'])) {
        CRM_ExactOnline_Config::setSession('exactonline_authorization_code', $_GET['code']);
        $this->connection->setAuthorizationCode($_GET['code']);
      } else {
        $this->connection->redirectForAuthorization();
      }
    }
  }

  /**
   * Connect to the Exact API, and reauthorise if necessary.
   * @param bool $interactive Interactive? (ie: is this a HTTP request or backend/cron?)
   * @throws CRM_ExactOnline_Exception Thrown if we could not connect
   */
  public function connect($interactive = TRUE) {

    // If we're running from cron and we have no valid oauth tokens, there's not much we can do
    if (!$interactive && $this->connection->needsAuthentication()) {
      throw new CRM_ExactOnline_Exception(ts('Could not connect to Exact: OAuth authentication needed!'));
    }

    // Otherwise, connect (and perform reauth / redirect if necessary)
    try {
      $this->connection->connect();
    } catch (\Exception $e) {
      throw new CRM_ExactOnline_Exception(ts('Could not connect to Exact') . ': ' . $e->getMessage());
    }
  }

  /**
   * Get connection object.
   * @return \Picqer\Financials\Exact\Connection
   */
  public function getConnection() {
    return $this->connection;
  }

  /**
   * Get an Exact API Entity by name, to use it to perform API requests.
   * @param string $entityName Entity Name
   * @return \Picqer\Financials\Exact\Model Exact API Entity
   * @throws CRM_ExactOnline_Exception Thrown if an invalid object name is passed
   */
  public function getEntity($entityName) {
    $className = '\\Picqer\\Financials\\Exact\\' . $entityName;
    if (class_exists($className)) {
      return new $className($this->connection);
    } else {
      throw new CRM_ExactOnline_Exception(ts('Exact API class does not exist:') . ' ' . $className);
    }
  }

}