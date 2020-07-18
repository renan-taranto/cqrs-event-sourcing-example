<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Item\Application\Command;

use Taranto\ListMaker\Item\Domain\ItemId;
use Taranto\ListMaker\ItemList\Domain\ListId;
use Taranto\ListMaker\Shared\Domain\Aggregate\IdentifiesAggregate;
use Taranto\ListMaker\Shared\Domain\Message\Command;
use Taranto\ListMaker\Shared\Domain\ValueObject\Position;

/**
 * Class MoveItem
 * @package Taranto\ListMaker\Item\Application\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class MoveItem extends Command
{
    /**
     * @var int|null
     */
    private $position;

    /**
     * @var string|null
     */
    private $listId;

    /**
     * MoveItem constructor.
     * @param string|null $aggregateId
     * @param int|null $position
     * @param string|null $listId
     */
    public function __construct(string $aggregateId = null, int $position = null, string $listId = null)
    {
        parent::__construct($aggregateId);
        $this->position = $position;
        $this->listId = $listId;
    }

    /**
     * @return ItemId
     */
    public function aggregateId(): IdentifiesAggregate
    {
        return ItemId::fromString($this->aggregateId);
    }

    /**
     * @return Position
     */
    public function position(): Position
    {
        return Position::fromInt($this->position);
    }

    /**
     * @return ListId
     */
    public function listId(): ListId
    {
        return ListId::fromString($this->listId);
    }
}
