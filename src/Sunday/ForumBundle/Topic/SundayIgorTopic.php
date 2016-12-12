<?php
namespace Sunday\ForumBundle\Topic;

use Gos\Bundle\WebSocketBundle\Router\WampRequest;
use Gos\Bundle\WebSocketBundle\Topic\PushableTopicInterface;
use Gos\Bundle\WebSocketBundle\Topic\TopicInterface;
use Gos\Bundle\WebSocketBundle\Client\ClientManipulatorInterface;
use Gos\Bundle\WebSocketBundle\Client\ClientStorageInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;

class SundayIgorTopic implements TopicInterface
{

    protected $clientManipulator;

    /**
     * @param ClientManipulatorInterface $clientManipulator
     */
    public function __construct(ClientManipulatorInterface $clientManipulator)
    {
        $this->clientManipulator = $clientManipulator;
    }

    /**
     * @param ConnectionInterface $connection
     * @param Topic $topic
     * @param WampRequest $request
     */
    public function onSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request)
    {
        //$topic->broadcast(['msg' => $connection->resourceId ."has joined" . $topic->getId()]);
    }

    /**
     * @param ConnectionInterface $connection
     * @param Topic $topic
     * @param WampRequest $request
     */
    public function onUnSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request)
    {
        //$topic->broadcast(['msg' => $connection->resourceId . " has left " . $topic->getId()]);
    }

    /**
     * @param ConnectionInterface $connection
     * @param Topic $topic
     * @param WampRequest $request
     * @param $event
     * @param array $exclude
     * @param array $eligible
     */
    public function onPublish(ConnectionInterface $connection, Topic $topic, WampRequest $request, $event, array $exclude, array $eligible)
    {
        $data = json_decode($event);
        $user = $this->clientManipulator->findByUsername($topic, $data->username);

        if(false !== $user) {
            $topic->broadcast(['msg' => $data->data], [], [$user['connection']->WAMP->sessionId]);
        } else {
            $topic->broadcast(['msg' => 'no way']);
        }
    }

    /**
     * @param Topic        $topic
     * @param WampRequest  $request
     * @param array|string $data
     * @param string       $provider The name of pusher who push the data
     */
    public function onPush(Topic $topic, WampRequest $request, $data, $provider)
    {
        $user = $this->clientManipulator->findByUsername($topic, 'tony');
        if(false !== $user) {
            $topic->broadcast('push message', [], [$user['connection']->WAMP->sessionId]);
        }
    }

    public function getName()
    {
        return 'sunday.igor.topic';
    }
}