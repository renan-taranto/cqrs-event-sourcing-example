<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Domain\Model\Board\Exception;

use Taranto\ListMaker\Domain\Model\Board\BoardId;
use Taranto\ListMaker\Domain\Model\Common\AggregateRootNotFound;

/**
 * Class BoardNotFound
 * @package Taranto\ListMaker\Domain\Model\Board\Exception
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class BoardNotFound extends AggregateRootNotFound
{
    public static function withBoardId(BoardId $boardId): self
    {
        return new self("Board with id {$boardId} cannot be found.");
    }
}
