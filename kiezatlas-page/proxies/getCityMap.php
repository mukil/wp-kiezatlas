<?php
  require_once("HTTP/Request.php");
  $workspaceId = $_GET['workspaceId'];
  $topicId = $_GET['mapId'];
  $body = '{"method": "getMapTopics", "params": ["'.$topicId.'" , "'.$workspaceId.'"]}';
  $req1 =& new HTTP_Request("http://www.kiezatlas.de:8080/rpc/");
  // $req1 =& new HTTP_Request("http://localhost:8080/kiezatlas/rpc/");
  // http://www.kiezatlas.de:8080/rpc/ --
  $req1->addHeader("Content-Type", "application/json");
  // $req2->addHeader("Charset", "utf-8");
  $req1->setBody($body);
  $req1->setMethod(HTTP_REQUEST_METHOD_POST);
  if (!PEAR::isError($req1->sendRequest())) {
          $resp1 = $req1->getResponseBody();
  } else {
          $resp1 = "Problems while loading CityMapTopics. Please retry.";
  }
  $mapTopics = utf8_encode($resp1);
  echo $mapTopics;
?>

