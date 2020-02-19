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

use Taranto\ListMaker\ItemList\Domain\ListId;
use Taranto\ListMaker\Shared\Domain\Aggregate\IdentifiesAggregate;
use Taranto\ListMaker\Shared\Domain\Message\Command;
use Taranto\ListMaker\Shared\Domain\ValueObject\Title;

/**
 * Class ChangeListTitle
 * @package Taranto\ListMaker\ItemList\Application\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ChangeListTitle extends Command
{
    /**
     * @return ListId
     */
    public function aggregateId(): IdentifiesAggregate
    {
        return ListId::fromString($this->aggregateId);
    }

    /**
     * @return Title
     */
    public function title(): Title
    {
        return Title::fromString($this->payload['title']);
    }
}
