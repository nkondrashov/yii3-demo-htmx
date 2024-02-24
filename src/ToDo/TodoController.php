<?php

declare(strict_types=1);

namespace App\ToDo;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Arrays\ArrayHelper;
use Yiisoft\Http\Method;
use Yiisoft\Router\CurrentRoute;
use Yiisoft\Yii\View\ViewRenderer;

final class TodoController
{
    public function __construct(private ViewRenderer $viewRenderer, private TodoRepository $repo)
    {
        $this->viewRenderer = $viewRenderer->withControllerName('todo');
    }

    public function index(): ResponseInterface
    {
        return $this->viewRenderer->render('index');
    }

    public function list()
    {
        $todos = $this->repo->findAll();
        $hasCompleted = count($this->repo->findCompleted()) > 0;

        return $this->viewRenderer->renderPartial('list', ['todos' => $todos, 'hasCompleted' => $hasCompleted]);
    }

    public function check(ServerRequestInterface $request, CurrentRoute $currentRoute)
    {
        $id = $currentRoute->getArgument('id');
        $todo = $this->repo->findOne($id);

        if ($request->getMethod() == Method::PUT) {
            //Why framework do not parse PUT-body??
            parse_str($request->getBody()->getContents(), $parsedBody);
            $todo->is_complete = ArrayHelper::getValue($parsedBody, 'is_complete', 'off') == 'on';
            $this->repo->save($todo);
        }

        return $this->viewRenderer->renderPartial('controls/check', ['todo' => $todo]);
    }

    public function note(ServerRequestInterface $request, CurrentRoute $currentRoute)
    {
        $id = $currentRoute->getArgument('id');
        $todo = $this->repo->findOne($id);

        //Why framework do not parse PUT-body??
        parse_str($request->getBody()->getContents(), $parsedBody);
        if ($parsedBody) {
            $todo->note = ArrayHelper::getValue($parsedBody, 'note');
            $this->repo->save($todo);
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
            $todo = $this->repo->getNew($parsedBody);
            $this->repo->save($todo);
        }

        $todo = $this->repo->getNew();

        return $this->viewRenderer->renderPartial('note/create', ['todo' => $todo]);
    }

    public function delete(CurrentRoute $currentRoute)
    {
        $id = $currentRoute->getArgument('id');
        $todo = $this->repo->findOne($id);
        $this->repo->delete($todo);

        return $this->viewRenderer->renderPartial('controls/delete', ['todo' => $todo]);
    }

    public function deleteCompleted()
    {
        $todos = $this->repo->findCompleted();
        foreach ($todos as $todo) {
            $this->repo->delete($todo);
        }

        return $this->viewRenderer->renderPartial('controls/delete-completed');
    }
}
