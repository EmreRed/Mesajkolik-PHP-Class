<?php

class Mesajkolik {
  private static $header;
  private static $apikey;

  public function __construct($username, $password, $header='') {
    self::$apikey = md5($username.$password);
    self::$header = $header;
  }

  private function call($action, $data=null){
    $url = "https://organikapi.com/v2/".self::$apikey."/$action/";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    if($data!==null){
      $postData = is_array($data) || is_object($data) ? json_encode($data) : $data;
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json; charset=utf-8']);
    $result = curl_exec($ch);
    $result_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $result_code==200 ? json_decode($result) : false;
  }

  public function getBalance(){
    $result = self::call('balance');
    return $result!==false && $result->response->result ? $result->response->data : false;
  }

  public function getHeaders(){
    $result = self::call('headers');
    return $result!==false && $result->response->result ? $result->response->data : false;
  }

  public function getGroups(){
    $result = self::call('groups');
    return $result!==false && $result->response->result ? $result->response->data : false;
  }

  public function getUsers(){
    $result = self::call('users');
    return $result!==false && $result->response->result ? $result->response->data : false;
  }

  public function sendsms($gsm, $message, $header=null, $isUniq=1){
    if($header === null) $header = self::$header;
    $gsm = is_array($gsm) ? $gsm : explode(',', $gsm);
    $gsm = array_filter($gsm);
    $data = '{
      "data": {
        "deliveries": [
          {
            "options": {
              "header": "'.$header.'",
              "gsm_isUnique": '.$isUniq.'
            },
            "recipients": {
              "gsms": ['.implode(',',$gsm).']
            },
            "message": "'.base64_encode($message).'"
          }
        ]
      }
    }';
    $result = self::call('sendsms', $data);
    return $result!==false ? $result->response : false;
  }

  public function advancedsms($sms, $header, $isUniq=1){
    $data = new stdClass();
    $data->data = new stdClass();
    $data->global_options = new stdClass();
    $data->global_options->header = $header;
    $data->global_options->gsm_isUnique = $isUniq;
    $data->data->deliveries = [];
    foreach($sms as $key){
      $delivery['recipients'] = ['gsms' => is_array($key['gsm']) ? $key['gsm'] : [$key['gsm']]];
      $delivery['message'] = base64_encode($key['message']);
      $delivery['options'] = ['header' => $header];
      $data->data->deliveries[] = $delivery;
    }
    $result = self::call('sendsms', $data);
    return $result!==false ? $result->response : false;
  }

  public function groupadd($groupname){
    $data = new stdClass();
    $data->data = new stdClass();
    $data->data->groups = is_array($groupname) ? $groupname : [$groupname];
    $result = self::call('groupadd', $data);
    return $result!==false ? $result->response : false;
  }

  public function personadd($persons, $groupid=null){
    if(!is_array($persons)) return false;
    $data = new stdClass();
    $data->data = new stdClass();
    $data->data->persons = $persons;
    $result = self::call('groupcontentadd', $data);
    return $result!==false ? $result->response : false;
  }

  public function keywords(){
    $result = self::call('keywords');
    return $result!==false ? $result->response : false;
  }

  public function inbox($keyword=null, $begin=null, $end=null){
    $data = new stdClass();
    $data->data = new stdClass();
    $data->data->date = new stdClass();
    if($keyword !== null) $data->data->rootKeyword = $keyword;
    if($begin !== null) $data->data->date->begin = $begin;
    if($end !== null) $data->data->date->end = $end;
    $result = self::call('inbox', $data);
    return $result!==false ? $result->response : false;
  }

  public function report($id){
    $data = new stdClass();
    $data->data = new stdClass();
    $data->data->transaction_ids = !is_array($id) ? [$id] : $id;
    $result = self::call('basicreport', $data);
    return $result!==false ? $result->response : false;
  }

  public function raw($action,$data){
    $result = self::call($action, $data);
    return $result!==false ? $result->response : false;
  }

}
