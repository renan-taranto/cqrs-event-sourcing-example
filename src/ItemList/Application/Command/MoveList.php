<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\ItemList\Application\Command;

use Taranto\ListMaker\Board\Domain\BoardId;
use Taranto\ListMaker\ItemList\Domain\ListId;
use Taranto\ListMaker\Shared\Domain\Aggregate\IdentifiesAggregate;
use Taranto\ListMaker\Shared\Domain\Message\Command;
use Taranto\ListMaker\Shared\Domain\ValueObject\Position;

/**
 * Class MoveList
 * @package Taranto\ListMaker\ItemList\Application\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class MoveList extends Command
{
    /**
     * @var int|null
     */
    private $position;

    /**
     * @var string|null
     */
    private $boardId;

    /**
     * MoveList constructor.
     * @param string|null $aggregateId
     * @param int|null $position
     * @param string|null $boardId
     */
    public function __construct(
        string $aggregateId = null,
        int $position = null,
        string $boardId = null
    ) {
        parent::__construct($aggregateId);
        $this->position = $position;
        $this->boardId = $boardId;
    }

    /**
     * @return ListId
     */
    public function aggregateId(): IdentifiesAggregate
    {
        return ListId::fromString($this->aggregateId);
    }

    /**
     * @return Position
     */
    public function position(): Position
    {
        return Position::fromInt($this->position);
    }

    /**
     * @return BoardId
     */
    public function boardId(): BoardId
    {
        return BoardId::fromString($this->boardId);
    }
}
