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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
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
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * QueryController constructor.
     * @param QueryBus $queryBus
     * @param QueryFactory $queryFactory
     * @param SerializerInterface $serializer
     */
    public function __construct(QueryBus $queryBus, QueryFactory $queryFactory, SerializerInterface $serializer)
    {
        $this->queryBus = $queryBus;
        $this->queryFactory = $queryFactory;
        $this->serializer = $serializer;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function __invoke(Request $request): Response
    {
        $query = $this->queryFactory->fromHttpRequest($request);
        $response = $this->queryBus->query($query);

        return new Response($this->serializer->serialize($response, 'json'), 200);
    }
}
