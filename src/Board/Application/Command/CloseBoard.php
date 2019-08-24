<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Board\Application\Command;

use Taranto\ListMaker\Board\Domain\BoardId;
use Taranto\ListMaker\Shared\Domain\Aggregate\IdentifiesAggregate;
use Taranto\ListMaker\Shared\Domain\Message\Command;

/**
 * Class CloseBoard
 * @package Taranto\ListMaker\Board\Application\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class CloseBoard extends Command
{
    /**
     * @return BoardId
     */
    public function aggregateId(): IdentifiesAggregate
    {
        return BoardId::fromString($this->aggregateId);
    }
}
