<?php

use Phalcon\Di;
use Phalcon\Loader;
use Phalcon\Http\Response;
use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;
use Phalcon\Validation\Message;
use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;

$loader = new Loader();
$loader->registerNamespaces(
    [
        'App\Models' => __DIR__ . '/models/',
    ]
);
$loader->register();


$container = new FactoryDefault();
$container->set(
    'db',
    function () {
        return new PdoMysql(
            [
                'host'     => 'db',
                'username' => 'dev',
                'password' => 'plokijuh',
                'dbname'   => 'hiring',
            ]
        );
    }
);
$container->set('errorMessageFormatter', function (array $messages): array {
    $errorMessageArray = [];
    foreach ($messages as $message) {
        $errorMessageArray[] = [
            'detail' => $message->getMessage(),
            'source' => [
                'pointer' => "data/attributes/{$message->getField()}",
            ],
        ];
    }
    return $errorMessageArray;
});

$app = new Micro($container);

$app->get(
    '/',
    function () {
      header('Content-type: application/json');
      echo json_encode([
        'available REST endpoints:',
        'GET /api/applicants',
        'GET /api/applicants/{id}',
        'POST /api/applicants',
      ]);
    }
);

$app->get(
  '/api/applicants',
  function () use ($app) {
    $phql = "SELECT id, name, age FROM App\Models\Candidates ORDER BY age";
    $candidates = $app
      ->modelsManager
      ->executeQuery($phql)
    ;

    $data = [];

    foreach ($candidates as $cand) {
      $data[] = [
        'type' => 'applicant',
        'id'   => $cand->id,
        'attributes' => [
        'name' => $cand->name,
        'age' => $cand->age,
      ]
      ];
    }

    header('Content-type: application/vnd.api+json'); // JSON API
    echo json_encode(['data' => $data]);
  }
);

$app->post('/api/applicants', function() use ($errorFormatter) {
    $request = $this->request->getJSONRawBody();
    if (!$request || !property_exists($request, 'data') || $request->data->type !== 'applicants') {
        return (new Response())->setStatusCode(400)->setJsonContent(['message' => 'Improperly formatted Request.'])->send();
    }
    $candidate = (new \App\Models\Candidates());
    $candidate->assign((array)$request->data->attributes);
    if (!$candidate->create()) {
        return (new Response())->setStatusCode(422)->setJSONContent([
            'errors' => $this->getDi()->get('errorMessageFormatter', [
                $candidate->getMessages()
            ]),
        ])->send();
    }
    $candidate->type = 'applicant';
    return (new Response())->setJSONContent(['data' => [
        'type' => 'applicant',
        'id' => $candidate->id,
        'attributes' => $candidate->toArray([
            'name',
            'age',
        ]),
    ]])->send();
});

$app->handle($_SERVER['REQUEST_URI']);
