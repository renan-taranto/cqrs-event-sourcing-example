<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Integration\Board\Infrastructure\Persistence\Projection;

use Taranto\ListMaker\Board\Domain\BoardId;
use Taranto\ListMaker\Board\Domain\Event\BoardClosed;
use Taranto\ListMaker\Board\Domain\Event\BoardCreated;
use Taranto\ListMaker\Board\Domain\Event\BoardReopened;
use Taranto\ListMaker\Board\Domain\Event\BoardTitleChanged;

/**
 * Trait BoardEventsProvider
 * @package Taranto\ListMaker\Tests\Integration\Board\Infrastructure\Persistence\Projection
 * @author Renan Taranto <renantaranto@gmail.com>
 */
trait BoardEventsProvider
{
    /**
     * @return BoardCreated
     * @throws \Exception
     */
    private function boardCreatedEvent(): BoardCreated
    {
        return BoardCreated::occur(
            (string) BoardId::generate(),
            ['title' => 'To-Dos']
        );
    }

    /**
     * @param string $boardId
     * @return BoardTitleChanged
     */
    private function boardTitleChangedEvent(string $boardId): BoardTitleChanged
    {
        return BoardTitleChanged::occur(
            $boardId,
            ['title' => 'Tasks to be done']
        );
    }

    /**
     * @param string $boardId
     * @return BoardClosed
     */
    private function boardClosedEvent(string $boardId): BoardClosed
    {
        return BoardClosed::occur($boardId);
    }

    /**
     * @param string $boardId
     * @return BoardReopened
     */
    private function boardReopenedEvent(string $boardId): BoardReopened
    {
        return BoardReopened::occur($boardId);
    }
}