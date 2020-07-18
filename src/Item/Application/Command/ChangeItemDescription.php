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

use Taranto\ListMaker\Item\Domain\Description;
use Taranto\ListMaker\Item\Domain\ItemId;
use Taranto\ListMaker\Shared\Domain\Aggregate\IdentifiesAggregate;
use Taranto\ListMaker\Shared\Domain\Message\Command;

/**
 * Class ChangeItemDescription
 * @package Taranto\ListMaker\Item\Application\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ChangeItemDescription extends Command
{
    /**
     * @var string|null
     */
    private $description;

    /**
     * ChangeItemDescription constructor.
     * @param string|null $aggregateId
     * @param string|null $description
     */
    public function __construct(string $aggregateId = null, string $description = null)
    {
        parent::__construct($aggregateId);
        $this->description = $description;
    }

    /**
     * @return ItemId
     */
    public function aggregateId(): IdentifiesAggregate
    {
        return ItemId::fromString($this->aggregateId);
    }

    /**
     * @return Description
     */
    public function description(): Description
    {
        return Description::fromString($this->description);
    }
}
