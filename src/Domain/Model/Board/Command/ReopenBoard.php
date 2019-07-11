<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Domain\Model\Board\Command;

use Taranto\ListMaker\Domain\Model\Board\BoardId;
use Taranto\ListMaker\Domain\Model\Common\Command;
use Taranto\ListMaker\Domain\Model\Common\IdentifiesAggregate;

/**
 * Class ReopenBoard
 * @package Taranto\ListMaker\Domain\Model\Board\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ReopenBoard extends Command
{
    /**
     * @return IdentifiesAggregate
     */
    public function aggregateId(): IdentifiesAggregate
    {
        return BoardId::fromString($this->aggregateId);
    }
}
