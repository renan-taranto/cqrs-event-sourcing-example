<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Shared\Ui\Web\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Taranto\ListMaker\Shared\Infrastructure\MessageBus\QueryBus;

/**
 * Class QueryController
 * @package Taranto\ListMaker\Shared\Ui\Web\Controller
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class QueryController
{
    /**
     * @var QueryBus
     */
    private $queryBus;

    /**
     * @var QueryFactory
     */
    private $queryFactory;

    /**
     * QueryController constructor.
     * @param QueryBus $queryBus
     * @param QueryFactory $queryFactory
     */
    public function __construct(QueryBus $queryBus, QueryFactory $queryFactory)
    {
        $this->queryBus = $queryBus;
        $this->queryFactory = $queryFactory;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function __invoke(Request $request): JsonResponse
    {
        $query = $this->queryFactory->fromHttpRequest($request);
        $response = $this->queryBus->query($query);

        return new JsonResponse($response, 200);
    }
}
