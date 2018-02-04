<?php

require 'lib/httpclient/BasicHttpClient.php';

class SMS
{
    private $baseUrl = 'https://api.smsgh.com/v3';
    private $endpoint = '/messages/';
    private $params = [];
    private $result;
    private $messageId;
    private $networkId;
    private $rate;
    private $status;
    private $msgStatus;
    private $time;
    private $content;
    private $http;
    private $allMessages = [];

    /**
     *  Set Basic Authentication Credentials.
     *
     * @param string $clientId   Hubtel Provided Client ID
     * @param string $clientPass Hubtel Provided Client password
     */
    public function setAuthCredentials($clientId, $clientPass)
    {
        $this->http = BasicHttpClient::init($this->baseUrl);

        $this->http->setConnectionTimeout(0);
        $this->http->setReadTimeout(0);

        //Setting Basic Authentication for Hubtels Api's
        return $this->http->setBasicAuth($clientId, $clientPass);
    }

    /**
     *  Send a Quick SMS.
     *
     * @param string $senderID The messenge Sener Name or ID
     * @param string $to       Reciepients telephone number (+223)
     * @param string $message  The actual message to be sent
     */
    public function sendQuickSMS($senderID, $to, $message)
    {
        if (is_null($senderID)) {
            throw new ErrorException("Parameter 'Sender ID' cannot be null");
        } elseif (!is_string($senderID)) {
            throw new ErrorException("Parameter 'Sender ID' must be a string");
        }
        if (is_null($to)) {
            throw new ErrorException("Parameter 'to' cannot be null");
        } elseif (!is_string($to) || !is_numeric($to)) {
            throw new ErrorException("Parameter 'to' must be a string or a numeric string");
        }
        if (is_null($message)) {
            throw new ErrorException("Parameter 'Message' cannot be null");
        } elseif (!is_string($message)) {
            throw new ErrorException("Parameter 'Message' must be a string");
        }

        // Paramters to pass through API
        $this->params = array(
            'From' => $senderID,
            'To' => $to,
            'Content' => $message,
            'RegisteredDelivery' => 'true',
        );

        //Making an API Post Request
        $response = $this->http->post($this->endpoint, $this->params);

        //Getting the Response Body
        $responseBody = $response->getBody();
        // Http Status code
        $this->result = $response->getHttpStatus();

        if ($this->result == 201) {
            //Decoding the Repsonse Body
            $responseBody = json_decode($responseBody, true);

            //Set Message ID
            $this->messageId = $responseBody['MessageId'];
            //Set Message network ID
            $this->networkId = $responseBody['NetworkId'];
            //Set Message Rate
            $this->rate = $responseBody['Rate'];
            //Set Message Status
            $this->status = $responseBody['Status'];
        }

        return null;
    }

    /**
     *  Schedule an SMS.
     *
     * @param string    $senderID The messenge Sener Name or ID
     * @param string    $to       Reciepients telephone number (+223)
     * @param string    $message  The actual message to be sent
     * @param date/time $time     Time to send Message(YYYY-MM-DD HH:MM:SS) eg: '2014-02-02 10:00:00'
     */
    public function scheduleSMS($senderID, $to, $message, $time)
    {
        if (is_null($senderID)) {
            throw new ErrorException("Parameter 'Sender ID' cannot be null");
        } elseif (!is_string($senderID)) {
            throw new ErrorException("Parameter 'Sender ID' must be a string");
        }
        if (is_null($to)) {
            throw new ErrorException("Parameter 'to' cannot be null");
        } elseif (!is_string($to) || !is_numeric($to)) {
            throw new ErrorException("Parameter 'to' must be a string or a numeric string");
        }
        if (is_null($message)) {
            throw new ErrorException("Parameter 'Message' cannot be null");
        } elseif (!is_string($message)) {
            throw new ErrorException("Parameter 'Message' must be a string");
        }
        if (is_null($time)) {
            throw new ErrorException("Parameter 'Time' cannot be null");
        } elseif (!is_string($time)) {
            throw new ErrorException("Parameter 'Time' must be a string");
        }

        // Paramters to pass through API
        $this->params = array(
            'From' => $senderID,
            'To' => $to,
            'Content' => $message,
            'RegisteredDelivery' => 'true',
            'Time' => $time,
        );

        //Making an API Post Request
        $response = $this->http->post($this->endpoint, $this->params);

        //Getting the Response Body
        $responseBody = $response->getBody();
        // Http Status code
        $this->result = $response->getHttpStatus();

        if ($this->result == 201) {
            //Decoding the Repsonse Body
            $responseBody = json_decode($responseBody, true);

            //Set Message ID
            $this->messageId = $responseBody['MessageId'];
            //Set Message network ID
            $this->networkId = $responseBody['NetworkId'];
            //Set Message Rate
            $this->rate = $responseBody['Rate'];
            //Set Message Status
            $this->status = $responseBody['Status'];
        }
    }

    /**3
     *  Re-Schedule an SMS.
     *
     * @param string    $messageId The Message ID of the Message
     * @param date/time $time      Time to send Message eg: '2014-02-02 10:00:00'
     */
    public function rescheduleSMS($messageId, $time)
    {
        if (is_null($messageId)) {
            throw new ErrorException("Parameter 'Message ID' cannot be null");
        } elseif (!is_string($messageId)) {
            throw new ErrorException("Parameter 'Message ID' must be a string");
        }
        if (is_null($time)) {
            throw new ErrorException("Parameter 'Time' cannot be null");
        } elseif (!is_string($time)) {
            throw new ErrorException("Parameter 'Time' must be a string");
        }

        $this->endpoint .= $messageId;

        // Paramters to pass through API
        $this->params = array(
            'Time' => $time,
        );

        //Making an API Post Request
        $response = $this->http->put($this->endpoint, $this->params);
        //Getting the Response Body
        $responseBody = $response->getBody();

        // Http Status code
        $this->result = $response->getHttpStatus();

        //Decoding the Repsonse Body
        $responseBody = json_decode($responseBody, true);
        //Getting the Updated Time
        $this->time = $responseBody['Time'];
    }

    /**
     *  Cancel a Scheduled SMS.
     *
     * @param string $messageId The Message ID of the Message
     */
    public function cancelScheduledSMS($messageId)
    {
        $this->endpoint .= $messageId;

        //Making an API Post Request
        $response = $this->http->delete($this->endpoint);

        // Http Status code
        $this->result = $response->getHttpStatus();
    }

    /**
     *  Retrieve a Message.
     *
     * @param string $messageId The Message ID of the Message
     */
    public function getMessageByID($messageId)
    {
        if (is_null($messageId)) {
            throw new ErrorException("Parameter 'Message ID' cannot be null");
        } elseif (!is_string($messageId)) {
            throw new ErrorException("Parameter 'Message ID' must be a string");
        }

        $this->endpoint .= $messageId;

        //Making an API Post Request
        $response = $this->http->get($this->endpoint);

        //Getting the Response Body
        $responseBody = $response->getBody();
        //Decoding the Repsonse Body
        $responseBody = json_decode($responseBody, true);

        //Set Message ID
        $this->messageId = $responseBody['MessageId'];
        //Set Message network ID
        $this->networkId = $responseBody['NetworkId'];
        //Set Message Rate
        $this->rate = $responseBody['Rate'];
        //Set Message Status
        $this->msgStatus = $responseBody['Status'];
        //Set Message Content
        $this->content = $responseBody['Content'];
        //Getting the Time
        $this->time = $responseBody['Time'];

        // Http Status code
        $this->result = $response->getHttpStatus();
    }

    /**
     *  Retrieve all Messages within a specific time.
     *
     * @param string $startTime The date to start querying from (YYYY-MM-DD HH:MM:SS)
     * @param string $endTime   The last possible time in the query (YYYY-MM-DD HH:MM:SS)
     */
    public function getMessageAllMessages($startTime, $endTime, $index = null, $limit = null, $pending = null, $direction = null)
    {
        //Making an API Post Request
        $response = $this->http->get($this->endpoint);

        //Getting the Response Body
        $responseBody = $response->getBody();
        $this->allMessages = array($responseBody);
        // Http Status code
        $this->result = $response->getHttpStatus();
    }

    /*
    *Get Results Status Code in the Response Body
    */
    public function getResponseResultsCode()
    {
        return $this->result;
    }

    /*
    *Get Time of message in the Response Body
    */
    public function getResponseBodyTime()
    {
        return $this->time;
    }

    /*
    *Get Response Body Content of the
    *getmessagebyID method in the Response Body
    */
    public function getResponseBodyContent()
    {
        return $this->content;
    }

    /*
    *Get Status Code in the Response Body
    */
    public function getResponseBodyStatus()
    {
        return $this->status;
    }

    /*
    *Get Rate of SMS in the Response Body
    */
    public function getResponseBodyRate()
    {
        return $this->rate;
    }

    /*
    *Get the Network Message ID in the Response Body
    */
    public function getResponseBodyNetworkId()
    {
        return $this->networkId;
    }

    /*
    *Get the MessageID in the Response Body
    */
    public function getResponseBodyMessageId()
    {
        return $this->messageId;
    }

    public function getAllMessage()
    {
        return $this->allMessages;
    }

    /**
     *   Get more Info about the Results of Response.
     *
     * @param string $mesg The log message
     */
    public function getResultsInfo()
    {
        switch ($this->result) {
                                 case '200':
                                             return 'The Request was Successfully Handled by the API';
                                 break;

                                 case '201':
                                            if ($this->status == '0') {
                                                return 'The request was successful, and the messages has been sent for onward delivery.';
                                            }

                                 break;
                                 case '400':
                                            if ($this->status == '100') {
                                                return 'General invalid request. Returned when no data is sent or a malformed request is received.';
                                            } elseif ($this->status == '1') {
                                                return 'Invalid Destination address received. The phone number recipient is not a valid phone number.';
                                            } elseif ($this->status == '2') {
                                                return 'Invalid Source Address was sent. You need to be aware of the sender address restrictions.';
                                            } elseif ($this->status == '3') {
                                                return 'The message body was too long.';
                                            } elseif ($this->status == '4') {
                                                return 'The message is not routable on the Hubtel gateway.';
                                            } elseif ($this->status == '5') {
                                                return 'The delivery time specified was not a valid time.';
                                            } elseif ($this->status == '6') {
                                                return 'The message content was rejected or is invalid.';
                                            } elseif ($this->status == '7') {
                                                return 'One or more parameters are not allowed in the message. Details will be provided as part of the response.';
                                            } elseif ($this->status == '8') {
                                                return 'One or more parameters are not valid for the message. Details will be provided as part of the response.';
                                            }
                                 break;

                                 case '401':
                                             return 'The request authorization failed.';
                                 break;

                                 case '402':
                                             return 'Your account does not have enough messaging credits to send the message.';
                                 break;

                                 case '403':
                                             return 'Forbidden. It means that the recipient has not given his/her approval to receive messages.';
                                 break;

                                 case '404':
                                             return 'The specified message was not found.';
                                 break;

                                 case '500':
                                             return 'The request failed on the server.';
                                  break;
                              }//End Of Switch Statement
    }
}
