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
use Taranto\ListMaker\Shared\Domain\Aggregate\IdentifiesAggregate;
use Taranto\ListMaker\Shared\Domain\Message\Command;
use Taranto\ListMaker\Shared\Domain\ValueObject\Title;

/**
 * Class ChangeItemTitle
 * @package Taranto\ListMaker\Item\Application\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ChangeItemTitle extends Command
{
    /**
     * @var string|null
     */
    private $title;

    /**
     * ChangeItemTitle constructor.
     * @param string|null $aggregateId
     * @param string|null $title
     */
    public function __construct(string $aggregateId = null, string $title = null)
    {
        parent::__construct($aggregateId);
        $this->title = $title;
    }

    /**
     * @return ItemId
     */
    public function aggregateId(): IdentifiesAggregate
    {
        return ItemId::fromString($this->aggregateId);
    }

    /**
     * @return Title
     */
    public function title(): Title
    {
        return Title::fromString($this->title);
    }
}
