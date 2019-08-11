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

use Taranto\ListMaker\Shared\Domain\Aggregate\IdentifiesAggregate;
use Taranto\ListMaker\Shared\Domain\ValueObject\Title;

/**
 * Interface BoardProjection
 * @package Taranto\ListMaker\Board\Infrastructure\Persistence\Projection
 * @author Renan Taranto <renantaranto@gmail.com>
 */
interface BoardProjection
{
    /**
     * @param IdentifiesAggregate $aggregateId
     * @param Title $title
     */
    public function createBoard(IdentifiesAggregate $aggregateId, Title $title): void;

    /**
     * @param IdentifiesAggregate $aggregateId
     * @param Title $changedTitle
     */
    public function changeBoardTitle(IdentifiesAggregate $aggregateId, Title $changedTitle): void;

    /**
     * @param IdentifiesAggregate $aggregateId
     */
    public function closeBoard(IdentifiesAggregate $aggregateId): void;

    /**
     * @param IdentifiesAggregate $aggregateId
     */
    public function reopenBoard(IdentifiesAggregate $aggregateId): void;
}
