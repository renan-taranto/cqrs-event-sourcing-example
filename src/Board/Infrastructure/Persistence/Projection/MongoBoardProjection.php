<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Board\Infrastructure\Persistence\Projection;

use MongoDB\Collection;
use Taranto\ListMaker\Shared\Domain\Aggregate\IdentifiesAggregate;
use Taranto\ListMaker\Shared\Domain\ValueObject\Title;

/**
 * Class MongoBoardProjection
 * @package Taranto\ListMaker\Board\Infrastructure\Persistence\Projection
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class MongoBoardProjection implements BoardProjection
{
    /**
     * @var Collection
     */
    private $boardCollection;

    /**
     * MongoBoardProjection constructor.
     * @param Collection $boardCollection
     */
    public function __construct(Collection $boardCollection)
    {
        $this->boardCollection = $boardCollection;
    }

    /**
     * @param IdentifiesAggregate $aggregateId
     * @param Title $title
     */
    public function createBoard(IdentifiesAggregate $aggregateId, Title $title): void
    {
        $this->boardCollection->insertOne([
            'boardId' => (string) $aggregateId,
            'title' => (string) $title,
            'isOpen' => true
        ]);
    }

    /**
     * @param IdentifiesAggregate $aggregateId
     * @param Title $changedTitle
     */
    public function changeBoardTitle(IdentifiesAggregate $aggregateId, Title $changedTitle): void
    {
        $this->boardCollection->updateOne(
          ['boardId' => (string) $aggregateId],
          ['$set' => ['title' => (string) $changedTitle]]
        );
    }

    /**
     * @param IdentifiesAggregate $aggregateId
     */
    public function closeBoard(IdentifiesAggregate $aggregateId): void
    {
        $this->boardCollection->updateOne(
            ['boardId' => (string) $aggregateId],
            ['$set' => ['isOpen' => false]]
        );
    }

    /**
     * @param IdentifiesAggregate $aggregateId
     */
    public function reopenBoard(IdentifiesAggregate $aggregateId): void
    {
        $this->boardCollection->updateOne(
            ['boardId' => (string) $aggregateId],
            ['$set' => ['isOpen' => true]]
        );
    }
}
