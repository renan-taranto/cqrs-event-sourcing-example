<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Unit\Shared\Infrastructure\SsePublisher;

use Codeception\Test\Unit;
use Hamcrest\Core\IsEqual;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Taranto\ListMaker\Shared\Infrastructure\SsePublisher\MercureSsePublisher;

/**
 * Class MercureSsePublisherTest
 * @package Taranto\ListMaker\Tests\Unit\Shared\Infrastructure\SsePublisher
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class MercureSsePublisherTest extends Unit
{
    private const URL = 'https://internal/.well-known/mercure';

    private const DATA = '{ id: "foo" }';

    /**
     * @var HubInterface
     */
    private $hub;

    /**
     * @var MercureSsePublisher
     */
    private $mercureSsePublisher;

    protected function _before()
    {
        $this->hub = \Mockery::spy(HubInterface::class);
        $this->mercureSsePublisher = new MercureSsePublisher($this->hub);
    }

    /**
     * @test
     */
    public function it_publishes_data_to_the_given_topic_url(): void
    {
        $this->mercureSsePublisher->publish(self::URL, self::DATA);

        $this->hub->shouldHaveReceived('publish')->with(isEqual::equalTo(new Update(self::URL, self::DATA)));
    }
}
