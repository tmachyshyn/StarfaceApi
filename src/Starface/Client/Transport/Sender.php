<?php

namespace Starface\Client\Transport;

use fXmlRpc\Transport\TransportInterface;
use Starface\Client\Transport\Exception as TransportException;

class Sender implements TransportInterface
{
    public function send($endpoint, $payload)
    {
        $ch = curl_init($endpoint);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: text/xml; charset=UTF-8',
            'Content-Length: ' . strlen($payload),
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new TransportException('cURL Error: ' . curl_error($ch));
        }

        curl_close($ch);

        return $response;
    }
}
