<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// REST

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group([
    'prefix' => '/api/clients',
    // 'namespace' => 'App\Http\Controllers'
], function () use ($router) {
    $router->get('','ClientsController@index');

    $router->get('{id}','ClientsController@show');

    $router->post('','ClientsController@store');

    $router->put('{id}','ClientsController@update');

    $router->delete('{id}','ClientsController@destroy');
});

$router->group([
    'prefix' => '/api/clients/{client}/address',
    // 'namespace' => 'App\Http\Controllers'
], function() use ($router) {
    $router->get('','AddressesController@index');

    $router->get('{id}','AddressesController@show');

    $router->post('','AddressesController@store');

    $router->put('{id}','AddressesController@update');

    $router->delete('{id}','AddressesController@destroy');
});

// SOAP

/* Usando SOAP de terceiro. */
$router->get('tcu', function () {
    $client = new \Zend\Soap\Client('http://contas.tcu.gov.br/debito/CalculoDebito?wsdl');

    echo "Informações do Servidor:";
    print_r($client->getOptions());

    echo "Funções:";
    print_r($client->getFunctions());

    echo "Tipos:";
    print_r($client->getTypes());

    echo "Resultado:";
    print_r($client->obterSaldoAtualizado([
        'parcelas' => [
            'parcela' => [
                'data' => '1995-01-01',
                'tipo' => 'D',
                'valor' => 35000
            ]
        ],
        'aplicaJuros' => true,
        'dataAtualizacao' => '2016-08-08'
    ]));
});

/* Usando SOAP local (Virtual Host no Apache do Wampserver. */
$uri = 'http://son-soap:80/public';

$router->get('son-soap.wsdl', function () use ($uri) {
    $autoDiscover = new \Zend\Soap\AutoDiscover();

    $autoDiscover->setUri("$uri/server");

    $autoDiscover->setServiceName('SONSOAP');

    $autoDiscover->addFunction('soma');

    $autoDiscover->handle();
});

/**
 * @param int $num1
 * @param int $num2
 * @return int
 */
function soma($num1, $num2)
{
    return $num1 + $num2;
}

/* Criando o meu SOAP. */
$router->post('server', function () use ($uri) {
    $server = new \Zend\Soap\Server("$uri/son-soap.wsdl", [
        'cache_wsdl' => WSDL_CACHE_NONE
    ]);

    $server->setUri("$uri/server");

    return $server->setReturnResponse(true)
        ->addFunction('soma')
        ->handle();
});

/* Usando o meu SOAP. */
$router->get('soap-test', function () use ($uri) {
    $client = new \Zend\Soap\Client("$uri/son-soap.wsdl", [
        'cache_wsdl' => WSDL_CACHE_NONE
    ]);

    print_r($client->soma(100, 200));
});
