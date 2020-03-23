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
use Symfony\Component\HttpFoundation\UrlHelper;

/**
 * Class EntryPointController
 * @package Taranto\ListMaker\Shared\Ui\Web\Controller
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class EntryPointController
{
    /**
     * @var UrlHelper
     */
    private $urlHelper;

    /**
     * EntryPointController constructor.
     * @param UrlHelper $urlHelper
     */
    public function __construct(UrlHelper $urlHelper)
    {
        $this->urlHelper = $urlHelper;
    }

    /**
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        return new JsonResponse([
            'name' => 'List Maker',
            'description' => 'DDD, CQRS and Event Sourcing in PHP',
            'swagger-ui' => $this->urlHelper->getAbsoluteUrl('doc/'),
            'github' => 'https://github.com/renan-taranto/list-maker',
            'author' => 'Renan Taranto',
            'email' => 'renantaranto@gmail.com'
        ]);
    }
}
