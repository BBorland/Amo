<?php

declare(strict_types=1);

namespace Sync\Handlers;

use AmoCRM\Client\AmoCRMApiClient;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sync\Core\Controllers\AccountController;
use Sync\Kommo\AuthService;
use Sync\Models\Account;

class AuthHandler extends AuthService implements RequestHandlerInterface
{
    /** @var array  */
    private array $connection;

    public function __construct(array $connection) //TODO: PHPDocs
    {
        $this->connection = $connection;
    }

    /**
     * @throws Exception
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $a = $request->getQueryParams()['name'];
        // TODO: файлы больше не должны использоваться
        if (!isset(json_decode(file_get_contents('./tokens.json'), true)[$a]) or
            !(Account::where('account_name', $a)->exists())) {
            (new AuthService())->auth();
            return new JsonResponse([
                /*
                 * TODO: 'name' => (new AuthService())->auth()
                 * см. 112 строку в AuthService
                 */
                ['name' => $_SESSION['name']]
            ]);
        }
        return new JsonResponse([
            ['name' => $a]
        ]);
    }
}
