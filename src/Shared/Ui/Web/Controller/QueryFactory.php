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
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class QueryFactory
 * @package Taranto\ListMaker\Shared\Ui\Web\Controller
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class QueryFactory
{
    const QUERY_CLASS_ATTRIBUTE = 'query_class';
    const ROUTE_PARAMS_ATTRIBUTE = '_route_params';
    const METHOD_ATTRIBUTE = 'method';
    const FORMAT_ATTRIBUTE = '_format';

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * QueryFactory constructor.
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param Request $request
     * @return object
     * @throws \Exception
     */
    public function fromHttpRequest(Request $request)
    {
        return $this->serializer->deserialize(
            json_encode($this->getQueryData($request)),
            $this->getQueryClass($request),
            'json'
        );
    }

    /**
     * @param Request $request
     * @return string
     * @throws \Exception
     */
    private function getQueryClass(Request $request): string
    {
        $queryClass = $request->attributes->get(self::QUERY_CLASS_ATTRIBUTE);
        if ($queryClass === null) {
            throw new \Exception('The "query_class" route attribute must be defined in order to create a query from the request.');
        }

        return $queryClass;
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getQueryData(Request $request): array
    {
        return array_merge(
            $this->getPathParams($request),
            $this->getQueryParams($request)
        );
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getPathParams(Request $request): array
    {
        $pathParams = $request->attributes->all()[self::ROUTE_PARAMS_ATTRIBUTE];
        unset($pathParams[self::QUERY_CLASS_ATTRIBUTE]);
        unset($pathParams[self::METHOD_ATTRIBUTE]);
        unset($pathParams[self::FORMAT_ATTRIBUTE]);

        return $pathParams;
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getQueryParams(Request $request): array
    {
        return $request->query->all();
    }
}
