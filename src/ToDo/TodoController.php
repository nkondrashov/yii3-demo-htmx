<?php

declare(strict_types=1);

namespace App\ToDo;

use App\Exception\NotHTMXRequestException;
use Nkondrashov\Yii3\Htmx\HTMXHeaderManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Arrays\ArrayHelper;
use Yiisoft\Http\Method;
use Yiisoft\Router\CurrentRoute;
use Yiisoft\Yii\View\ViewRenderer;

final class TodoController
{
    public function __construct(private ViewRenderer      $viewRenderer,
                                private CurrentRoute      $currentRoute,
                                private TodoRepository    $repository,
                                private HTMXHeaderManager $headerManager)
    {
        $this->viewRenderer = $viewRenderer->withControllerName('todo');

        if ($this->headerManager->isHtmxRequest()) {
            //Example: This request without htmx header
        }
    }

    public function index(): ResponseInterface
    {
        return $this->viewRenderer->render('index');
    }

    public function list()
    {
        $todos = $this->repository->findAll();
        $hasCompleted = count($this->repository->findCompleted()) > 0;

        return $this->viewRenderer->renderPartial('list', ['todos' => $todos, 'hasCompleted' => $hasCompleted]);
    }

    public function check(ServerRequestInterface $request)
    {
        $id = $this->currentRoute->getArgument('id');
        $todo = $this->repository->findOne($id);

        if ($request->getMethod() == Method::PUT) {
            //Why framework do not parse PUT-body??
            parse_str($request->getBody()->getContents(), $parsedBody);
            $todo->is_complete = ArrayHelper::getValue($parsedBody, 'is_complete', 'off') == 'on';
            $this->repository->save($todo);

            //example: send event right here (need use HTMXHeaderManager)
            $this->headerManager->triggerCustomEventAfterSwap('updateList');
        }

        return $this->viewRenderer->renderPartial('controls/check', ['todo' => $todo]);
    }

    public function note(ServerRequestInterface $request)
    {
        $id = $this->currentRoute->getArgument('id');
        $todo = $this->repository->findOne($id);

        //Why framework do not parse PUT-body??
        parse_str($request->getBody()->getContents(), $parsedBody);
        if ($parsedBody) {
            $todo->note = ArrayHelper::getValue($parsedBody, 'note');
            $this->repository->save($todo);

            //example: send event right here (need use HTMXHeaderManager)
            $this->headerManager->triggerCustomEventAfterSwap('updateList');
        }

        if ($request->getMethod() == Method::PUT && !$parsedBody) {
            return $this->viewRenderer->renderPartial('note/edit', ['todo' => $todo]);
        } else {
            return $this->viewRenderer->renderPartial('note/view', ['todo' => $todo]);
        }
    }

    public function create(ServerRequestInterface $request)
    {
        $parsedBody = $request->getParsedBody();
        if ($parsedBody) {
            $todo = $this->repository->getNew($parsedBody);
            $this->repository->save($todo);

            //example: send event right here (need use HTMXHeaderManager)
            $this->headerManager->triggerCustomEventAfterSwap('updateList');
        }

        $todo = $this->repository->getNew();

        return $this->viewRenderer->renderPartial('note/create', ['todo' => $todo]);
    }

    public function delete()
    {
        $id = $this->currentRoute->getArgument('id');
        $todo = $this->repository->findOne($id);
        $this->repository->delete($todo);

        //example: send event right here (need use HTMXHeaderManager)
        $this->headerManager->triggerCustomEventAfterSettle('updateList');

        return $this->viewRenderer->renderPartial('controls/delete', ['todo' => $todo]);
    }

    public function deleteCompleted()
    {
        $todos = $this->repository->findCompleted();
        foreach ($todos as $todo) {
            $this->repository->delete($todo);

            //as example: send event right here via native method (need use HTMXHeaderManager)
            $this->headerManager->sendHXHeader('Trigger', 'updateList');
        }

        return $this->viewRenderer->renderPartial('controls/delete-completed');
    }
}
