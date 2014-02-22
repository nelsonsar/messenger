<?php

namespace Messenger\Provider\Controller;

use Messenger\Repository\Conversation as ConversationRepository;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class Conversation implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers->get('/all', function (Application $app) {
            $conversationRepository = new ConversationRepository($app['db']);
            $user = $app['session']->get('user');
            if (null === $user) {
                return $app->redirect('/');
            }

            $conversations = $conversationRepository->retrieveAll($user['login']);

            return $app['twig']->render('conversations.html.twig', array(
                'title' => 'Conversas',
                'user' => $user['login'],
                'conversations' => $conversations,
            ));
        });

        $controllers->get('/new', function (Application $app) {
            $user = $app['session']->get('user');
            if (null === $user) {
                return $app->redirect('/');
            }

            $users = $app['rabbitmq']->getAllQueuesFromVirtualHost('/');

            return $app['twig']->render('new_conversation.html.twig', array(
                'title' => 'Nova conversa',
                'user' => $user['login'],
                'available_users' => $users,
            ));
        });

        $controllers->post('/', function(Request $request) use ($app) {
            $user = $app['session']->get('user');
            if (null === $user) {
                return $app->redirect('/');
            }

            $userToTalk = $request->get('user');

            return $app->redirect('/conversation/' . $userToTalk);
        });

        $controllers->get('/{name}', function (Application $app, Request $request) {
            $user = $app['session']->get('user');
            if (null === $user) {
                return $app->redirect('/');
            }

            $userTalkingTo = $app->escape($request->attributes->get('name'));

            $messages = array();
            $messages = $app['message_vault']->retrieveAll($user['login'], $userTalkingTo);

            return $app['twig']->render('conversation.html.twig', array(
                'title' => 'Conversando com ' . $userTalkingTo,
                'user' => $user['login'],
                'messages' => $messages,
                'receiver' => $userTalkingTo
            ));
        });

        $controllers->post('/{name}/message/new', function (Request $request) use ($app) {
            $user = $app['session']->get('user');
            if (null === $user) {
                return $app->redirect('/');
            }

            $message = $request->get('message');
            $userTalkingTo = $app->escape($request->attributes->get('name'));

            if (true === empty($message)) {
                return $app->redirect('/conversation/' . $userTalkingTo);
            }

            $app['message_vault']->store($user['login'], $userTalkingTo, $message);

            return $app->redirect('/conversation/' . $userTalkingTo);
        });

        return $controllers;
    }
}
