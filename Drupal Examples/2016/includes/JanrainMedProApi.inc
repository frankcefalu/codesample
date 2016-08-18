<?php

/**
 * @file
 * Class JanrainMedProApi.
 */

/**
 * Class JanrainMedProApi for connecting to medproApi.
 */
class JanrainMedProApi {

  private $WebServiceIDUrl;
  private $type;
  private $result;
  private $resultType;

  public $args = array();
  protected $response = NULL;
  protected $delimiter = '~';

  public $error = NULL;

  /**
   * JanrainMedProApi constructor.
   */
  public function __construct($url, $user, $pass) {
    $this->WebServiceIDUrl = $url;
    $this->WebServiceIDUser = $user;
    $this->WebServiceIDUPass = $pass;
  }

  /**
   * Known result types.
   */
  protected function getResultTypes() {
    return array(
      'delimiter' => t('Delimiter'),
      'xml' => t('XML'),
    );
  }

  /**
   * Build query.
   */
  public function buildQuery() {
    $query = array();

    $xml_output  = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:ser="http://service.medproid.com/"><soap:Header/><soap:Body><ser:FetchData><ser:xmlData><medpro-search-request><hcp>';

    foreach ($this->args as $key => $arg) {
      $xml_output .= sprintf("<%s>%s</%s>",$key,$arg,$key);
    }

    $xml_output .="</hcp></medpro-search-request></ser:xmlData></ser:FetchData></soap:Body></soap:Envelope>";

    return $xml_output;
  }

  /**
   * Calls MedPro Api.
   *
   * @return null|object
   *   Return response object.
   */
  protected function call() {
    $options = array(
     'data' => $this->buildQuery(),
      'headers' => array(
        'Content-Type' => 'application/soap+xml',
      ),
      'method' => 'POST',
    );

    $url = $this->WebServiceIDUrl;
    $this->response = drupal_http_request($url, $options);
    return $this->response;
  }

  /**
   * Validate.
   *
   * @return null|object
   *   Returns parsed result.
   */
  public function validate() {
    try {
      $this->call();
      // @todo: parse result.
      if ($this->response->code == 200 && !empty($this->response->data)) {
        $this->response->data = (string) $this->response->data;
      }


      $this->parseResult();
      $this->checkResult();
    }
    catch (Exception $e) {
      $this->error = $e->getMessage();
    }

    if (!empty($this->error)) {
      return array(
        'status' => 'error',
        'error_message' => $this->error,
        'result' => $this->result,
      );
    }



    return array(
      'status' => 'success',
      'result' => $this->result,
    );
  }

  /**
   * Check Result.
   */
  protected function checkResult() {
    // @todo: figure out when it should fail.
    if (empty($this->error)) {
      if ($this->result['Sampleability'] != "Y") {
        $this->error = !empty($this->result['ReasonCode']) ? $this->result['ReasonCode'] : t('Not Sampleable');
      }

      if (!empty($this->error) && !empty($this->result['ReasonCode'])) {
        switch ($this->result['ReasonCode']) {
          case "Cannot Find Practitioner":
            $this->error = t('Cannot Find Practitioner');
            break;
        }
      }
    }
  }


  /**
   * Validate Practitionist.
   *
   * @return null|object
   *   Returns parsed result.
   */
  public function validatePractitioner() {
    $this->type = 'ValPract';
    return $this->validate();
  }

  /**
   * Set Argument.
   *
   * @param string $key
   *   Key to set.
   * @param string $value
   *   Key value.
   */
  public function setArgument($key, $value) {
    $this->args[$key] = $value;
  }

  /**
   * Returns arguments.
   *
   * @return array
   *   Array containing arguments.
   */
  public function getArguments() {
    return $this->args;
  }

  /**
   * Set result Type.
   */
  public function setResultType($type) {
    $allowed_types = $this->getResultTypes();
    if (!empty($allowed_types[$type])) {
      $this->resultType = $type;
    }
    else {
      $this->error = t('Unknown Result Type');
    }
  }

  /**
   * Parse result.
   */
  public function parseResult() {

    switch ($this->resultType) {
      case "delimiter":
        $this->result = $this->parseResultDelimiter();
        break;

      case "xml":
        $this->result = $this->parseResultXml();
        break;

      default:
        $this->error = t('Unknown Result Type');
    }

    return $this->result;
  }

  /**
   * Parse xml result.
   */
  protected function parseResultXml() {
    $data = array();
    $xml =  $this->response->data;
    $response =  $this->response;


    if (!empty($xml)) {

    preg_match( "/\<samp-overall\>(.*?)\<\/samp-overall\>/", $xml, $samp );
    preg_match( "/\<error-code\>(.*?)\<\/error-code\>/", $xml, $errorcode );
    preg_match( "/\<error-message\>(.*?)\<\/error-message\>/", $xml, $errorcode );

      if($samp[1] == "Y"){

      $data['FirstName']     = $this->args['first-name'];
      $data['LastName']      = $this->args['last-name'];
      $data['StateLicNum']   = $this->args['sln-state-license-number'];
      $data['Sampleability'] = $samp[1];
      $data['ReasonCode']   = $response->code;

      }


    }
    else {
      $this->error = t('Empty data');
    }


   return $data;
  }

  /**
   * Parse result based on delimiter.
   */
  protected function parseResultDelimiter() {
    $data = array();
    $parsed = array();
    if (!empty($this->response->data)) {
      $parsed = explode($this->getDelimiter(), $this->response->data);
    }
    else {
      $this->error = t('Empty data');
    }

    $data['FirstName']     = !empty($parsed[0]) ? $parsed[0] : '';
    $data['LastName']      = !empty($parsed[1]) ? $parsed[1] : '';
    $data['StateLicNum']   = !empty($parsed[2]) ? $parsed[2] : '';
    $data['Sampleability'] = !empty($parsed[3]) ? $parsed[3] : '';
    $data['ReasonCode']   = !empty($parsed[4]) ? $parsed[4] : '';

    return $data;
  }

  /**
   * Set Delimiter.
   *
   * @param string $delimiter
   *   Delmiter for parsing result.
   */
  public function setDelimiter($delimiter) {
    $this->delimiter = $delimiter;
  }

  /**
   * Get Delimiter.
   */
  public function getDelimiter() {
    return $this->delimiter;
  }

  /**
   * Get result.
   */
  public function getResult() {
    return $this->result;
  }

}
