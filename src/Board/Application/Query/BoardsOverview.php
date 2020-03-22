<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Board\Application\Query;

use Taranto\ListMaker\Shared\Domain\Message\Query;

/**
 * Class BoardsOverview
 * @package Taranto\ListMaker\Board\Application\Query
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class BoardsOverview extends Query
{
    /**
     * @return bool|null
     */
    public function open(): ?bool
    {
        if (!isset($this->payload['open'])) {
            return null;
        }

        return filter_var($this->payload['open'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }
}
