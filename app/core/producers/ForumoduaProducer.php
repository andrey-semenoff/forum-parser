<?php
namespace Core;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class ForumoduaProducer implements Producer
{
    private $connection;
    private $channel;

    public function __construct(array $config)
    {
        $this->connection = new AMQPStreamConnection($config['host'], $config['port'], $config['user'], $config['password']);
        $this->channel = $this->connection->channel();
        $this->channel->queue_declare('messages_queue', false, true, false, false);
    }

    public function send(Message $message)
    {
        $data = serialize($message);

        $msg = new AMQPMessage(
            $data,
            array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT)
        );

        $this->channel->basic_publish($msg, '', 'messages_queue');

        echo ' [x] Sent ', $data, "\n";

//        $this->close();
    }

    public function close()
    {
        $this->channel->close();
        $this->connection->close();
    }
}