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
use Taranto\ListMaker\Shared\Domain\Message\Query;

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
     * @param Request $request
     * @return Query
     * @throws \Exception
     */
    public function fromHttpRequest(Request $request): Query
    {
        $queryClass = $this->getQueryClass($request);
        $queryParams = $this->getQueryPayload($request);

        return new $queryClass($queryParams);
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
            throw new \Exception('The "query_class" attribute was not found in the request.');
        }

        return $queryClass;
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getQueryPayload(Request $request): array
    {
        $payload = $request->attributes->all()[self::ROUTE_PARAMS_ATTRIBUTE];
        unset($payload[self::QUERY_CLASS_ATTRIBUTE]);
        unset($payload[self::METHOD_ATTRIBUTE]);
        unset($payload[self::FORMAT_ATTRIBUTE]);

        return $payload;
    }
}
