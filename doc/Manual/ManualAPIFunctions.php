<?php

/* $Id: ManualAPIFunctions.php 3152 2009-12-11 14:28:49Z tim_schofield $ */

$PageSecurity = 1;
$PathPrefix= $_SERVER['HTTP_HOST'].$rootpath.'/../../';
//include('../../includes/session.inc');
include('../../xmlrpc/lib/xmlrpc.inc');
include('../../api/api_errorcodes.php');

$title = 'API documentation';

echo '<html xmlns="http://www.w3.org/1999/xhtml"><head><title>' . $title . '</title>';
echo '<link REL="shortcut icon" HREF="'. $rootpath.'/favicon.ico">';
echo '<link REL="icon" HREF="' . $rootpath.'/favicon.ico">';
echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
echo '<link href="'.$rootpath. '/../../css/'. $_SESSION['Theme'] .'/default.css" REL="stylesheet" TYPE="text/css">';
echo '</head>';

echo '<body>';

$kwamojauser = $_SESSION['UserID'];
$sql="SELECT password FROM www_users WHERE userid='".$kwamojauser."'";
$result=DB_query($sql, $db);
$myrow=DB_fetch_array($result);
$kwamojapassword = $myrow[0];

$ServerString = $_SERVER['HTTP_HOST'].$rootpath;
$FirstBitOfURL = mb_substr($ServerString,0,mb_strpos($ServerString,'/doc/Manual'));


$ServerURL = "http://".  $FirstBitOfURL ."/api/api_xml-rpc.php";
$DebugLevel = 0; //Set to 0,1, or 2 with 2 being the highest level of debug info

$msg = new xmlrpcmsg("system.listMethods", array());

$client = new xmlrpc_client($ServerURL);
$client->setDebug($DebugLevel);

$response = $client->send($msg);
$answer = php_xmlrpc_decode($response->value());

for ($i=0; $i<sizeof($answer); $i++) {
	echo '<br /><table border="1" width="80%"><tr><th colspan="3"><h4>'._('Method name')._('  -  ').'<b>'.$answer[$i].'</b></h4></th></tr>';
	$method = php_xmlrpc_encode($answer[$i]);
	$msg = new xmlrpcmsg("system.methodHelp", array($method));

	$client = new xmlrpc_client($ServerURL);
	$client->setDebug($DebugLevel);

	$response = $client->send($msg);
	$signature = php_xmlrpc_decode($response->value());
	echo $signature.'<br />';
}

echo '</body>';

?>