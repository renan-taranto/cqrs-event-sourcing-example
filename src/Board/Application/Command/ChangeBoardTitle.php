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
use Taranto\ListMaker\Shared\Domain\ValueObject\Title;

/**
 * Class ChangeBoardTitle
 * @package Taranto\ListMaker\Board\Application\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ChangeBoardTitle extends Command
{
    /**
     * @var string|null
     */
    private $title;

    /**
     * ChangeBoardTitle constructor.
     * @param string|null $aggregateId
     * @param string|null $title
     */
    public function __construct(string $aggregateId = null, string $title = null)
    {
        parent::__construct($aggregateId);
        $this->title = $title;
    }

    /**
     * @return BoardId
     */
    public function aggregateId(): IdentifiesAggregate
    {
        return BoardId::fromString($this->aggregateId);
    }

    /**
     * @return Title
     */
    public function title(): Title
    {
        return Title::fromString($this->title);
    }
}
