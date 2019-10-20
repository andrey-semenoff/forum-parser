<?php
namespace Core;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use stringEncode\Exception;

class Worker
{
    public function __construct(array $config, \PDO $db_connection)
    {
        $connection = new AMQPStreamConnection($config['host'], $config['port'], $config['user'], $config['password']);
        $channel = $connection->channel();
        $channel->queue_declare('messages_queue', false, true, false, false);
        echo " [*] Waiting for messages. To exit press CTRL+C\n";
        $channel->basic_qos(null, 1, null);

        $callback = function($data) use ($db_connection) {
            $msg = unserialize($data->body);
            echo ' [x] Received message from user ', $msg->author, "\n";
            try {
                if( $msg->save() ) {
                    echo 'Message has been saved into DB!', "\n";
                }
            } catch (Exception $e) {
                print_r($e);
            }
//            sleep(1);
            $data->delivery_info['channel']->basic_ack($data->delivery_info['delivery_tag']);
        };

        $channel->basic_consume('messages_queue', '', false, false, false, false, $callback);

        while ($channel->is_consuming()) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }
}


