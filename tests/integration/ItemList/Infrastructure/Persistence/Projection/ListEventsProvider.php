<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Integration\ItemList\Infrastructure\Persistence\Projection;

use Taranto\ListMaker\ItemList\Domain\Event\ListArchived;
use Taranto\ListMaker\ItemList\Domain\Event\ListCreated;
use Taranto\ListMaker\ItemList\Domain\Event\ListMoved;
use Taranto\ListMaker\ItemList\Domain\Event\ListRestored;
use Taranto\ListMaker\ItemList\Domain\Event\ListTitleChanged;
use Taranto\ListMaker\ItemList\Domain\ListId;

/**
 * Trait ListEventsProvider
 * @package Taranto\ListMaker\Tests\Integration\ItemList\Infrastructure\Persistence\Projection
 * @author Renan Taranto <renantaranto@gmail.com>
 */
trait ListEventsProvider
{
    /**
     * @param string $boardId
     * @return ListCreated
     * @throws \Exception
     */
    private function listCreatedEvent(string $boardId): ListCreated
    {
        return new ListCreated((string) ListId::generate(), 'Staging', 1, $boardId);
    }

    /**
     * @param string $listId
     * @return ListArchived
     */
    private function listArchivedEvent(string $listId): ListArchived
    {
        return new ListArchived($listId);
    }

    /**
     * @param string $listId
     * @return ListRestored
     */
    private function listRestoredEvent(string $listId): ListRestored
    {
        return new ListRestored($listId);
    }

    /**
     * @param string $listId
     * @return ListTitleChanged
     */
    private function listTitleChangedEvent(string $listId): ListTitleChanged
    {
        return new ListTitleChanged($listId, 'Testing');
    }

    /**
     * @param string $listId
     * @param int $position
     * @param string $boardId
     * @return ListMoved
     */
    private function listMovedEvent(string $listId, int $position, string $boardId): ListMoved
    {
        return new ListMoved($listId, $position, $boardId);
    }
}
