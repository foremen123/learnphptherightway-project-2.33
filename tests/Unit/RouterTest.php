<?php

use App\Exceptions\RouteNotFoundException;
use App\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{

    public  function setUp(): void
    {
        parent::setUp();

        $this->router = new Router();
    }



    public function test_it_register_a_post_route(): void
    {
        $this->router->post('/users', ['User', 'store',]);

        $expected = [
            'post' => [
                '/users' => ['User', 'store'],
            ],
        ];

        $this->assertEquals($expected, $this->router->routes());
    }

    public function test_it_register_a_get_route(): void
    {
        $this->router->get( '/users', ['User', 'index',]);

        $expected = [
            'get' => [
                '/users' => ['User', 'index'],
            ],
        ];

        $this->assertEquals($expected, $this->router->routes());
    }

    public function test_there_are_no_when_router_is_created(): void
    {
        $this->assertEmpty((new Router())->routes());
    }

    /**
     * @dataProvider \Tests\DateProvider\RouterDataProvider::routeNotFoundCases
    */

    public function test_it_throws_exception_when_route_not_found_exception(
        string $requestUri,
        string $requestMethod,
    ): void
    {
        $users = new class() {
            public function delete(): bool
            {
                return true;
            }
        };

        $this->router->post('/users', [$users::class, 'store']);
        $this->router->get('/users', ['Users', 'index']);

        $this->expectException(RouteNotFoundException::class);
        $this->router->resolve($requestUri, $requestMethod);
    }

    public function test_it_resolves_route_from_a_closure(): void
    {
        $this->router->get('/users', fn() => [1, 2, 3]);

        $this->assertEquals(
            [1, 2, 3],
            $this->router->resolve('/users', 'get'));
    }

    public function test_it_resolves_route(): void
    {
        $users = new class() {
            public function index(): array
            {
                return [1, 2, 3];
            }
        };

        $this->router->get('/users', [$users::class, 'index']);

        $this->assertEquals([1, 2, 3], $this->router->resolve('/users', 'get'));
    }

}