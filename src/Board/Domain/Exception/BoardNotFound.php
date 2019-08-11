<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Board\Domain\Exception;

use Taranto\ListMaker\Board\Domain\BoardId;
use Taranto\ListMaker\Shared\Domain\Aggregate\AggregateRootNotFound;

/**
 * Class BoardNotFound
 * @package Taranto\ListMaker\Board\Domain\Exception
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class BoardNotFound extends AggregateRootNotFound
{
    public static function withBoardId(BoardId $boardId): self
    {
        return new self("Board with id {$boardId} cannot be found.");
    }
}
