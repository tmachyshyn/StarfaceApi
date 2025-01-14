<?php

namespace Starface\Client;

use fXmlRpc\ClientInterface;
use fXmlRpc\Parser\ParserInterface;
use fXmlRpc\Parser\XmlReaderParser;
use Starface\Client\Transport\Sender;
use fXmlRpc\Transport\TransportInterface;
use fXmlRpc\Serializer\SerializerInterface;
use fXmlRpc\Serializer\XmlWriterSerializer;

class Client implements ClientInterface
{
    private string $uri;

    private TransportInterface $transport;

    private ParserInterface $parser;

    private SerializerInterface $serializer;

    public function __construct(
        string $uri,
        TransportInterface $transport = null,
        ParserInterface $parser = null,
        SerializerInterface $serializer = null
    ) {
        $this->uri = $uri;
        $this->transport = $transport ?: new Sender();
        $this->parser = $parser ?: new XmlReaderParser();
        $this->serializer = $serializer ?: new XmlWriterSerializer();
    }

    public function call($method, array $arguments = [])
    {
        $payload = $this->serializer->serialize($method, $arguments);

        $response = $this->transport->send($this->uri, $payload);

        return $this->parser->parse($response);
    }

    public function multicall()
    {
    }
}
