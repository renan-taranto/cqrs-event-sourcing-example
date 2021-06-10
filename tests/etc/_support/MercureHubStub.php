<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests;

use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Jwt\StaticTokenProvider;
use Symfony\Component\Mercure\Jwt\TokenFactoryInterface;
use Symfony\Component\Mercure\Jwt\TokenProviderInterface;
use Symfony\Component\Mercure\Update;

/**
 * Decorates the default Mercure hub service so no updates are actually sent while testing
 *
 * Class MercureHubStub
 * @package Taranto\ListMaker\Tests\etc\_support
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class MercureHubStub implements HubInterface
{
    public function getUrl(): string
    {
        return '';
    }

    public function getPublicUrl(): string
    {
        return '';
    }

    public function getProvider(): TokenProviderInterface
    {
        return new StaticTokenProvider('');
    }

    public function getFactory(): ?TokenFactoryInterface
    {
        return null;
    }

    public function publish(Update $update): string
    {
        return '';
    }
}
