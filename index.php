<?php

require 'lib/MessageApi.php';

$clientId = '********';
$clientPass = '********';

// New instance of the BasicHttpClient
$sms = new SMS();
$sms->setAuthCredentials($clientId, $clientPass);

$senderID = 'Test';
$to = '+233---------';
$message = 'Hi theres!';
$time = '2018-01-11 19:18:00';
$starttime = '2018-01-11 19:18:00';
$endtime = '2018-01-11 19:18:00';
//$messageId = '0437e965-51e9-42c1-a930-1f03ddd4b23a';

/*
$sms->sendQuickSMS($senderID, $to, $message);
echo '<br>Response Code: '.$sms->getResponseResultsCode();
echo '<br>Response Status: '.$sms->getResponseBodyStatus();
echo '<br>Network ID: '.$sms->getResponseBodyNetworkId();
echo '<br>SMS ID: '.$sms->getResponseBodyMessageId();
*/
/*
$sms->scheduleSMS($senderID, $to, $message, $time);
echo '<br>Response Code: '.$sms->getResponseResultsCode();
echo '<br>Response Status: '.$sms->getResponseBodyStatus();
echo '<br>SMS ID: '.$sms->getResponseBodyMessageId();
*/
//$messageId = "0c2de4a3-9baa-46f8-8a31-20e480be86f0";
//376f54f9-f494-4b0c-8815-a8e857122903

/*
$sms->rescheduleSMS($messageId, $time);
echo '<br>Time: '.$sms->getResponseBodyTime();
*/

/*
$sms->getMessageByID($messageId);
echo '<br>Content: '.$sms->getResponseBodyContent();
echo '<br>Time: '.$sms->getResponseBodyTime();
*/

/*
$sms->getMessageByID($messageId);
echo '<br>Content: '.$sms->getResponseBodyContent();
echo '<br>Time: '.$sms->getResponseBodyTime();
*/

/*
$sms->getMessageAllMessages($messageId);
echo '<br>Content: '.$sms->getResponseBodyContent();
*/
