<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Infrastructure\Persistence\Projection\Board;

use Taranto\ListMaker\Domain\Model\Common\IdentifiesAggregate;
use Taranto\ListMaker\Domain\Model\Common\ValueObject\Title;

/**
 * Interface BoardProjection
 * @package Taranto\ListMaker\Infrastructure\Persistence\Projection\Board
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
