<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Application\CommandHandler\Board;

use PHPUnit\Framework\TestCase;
use Taranto\ListMaker\Application\CommandHandler\Board\CreateBoardHandler;
use Taranto\ListMaker\Domain\Model\Board\Board;
use Taranto\ListMaker\Domain\Model\Board\BoardId;
use Taranto\ListMaker\Domain\Model\Board\BoardRepository;
use Taranto\ListMaker\Domain\Model\Board\Command\CreateBoard;

/**
 * Class CreateBoardHandlerTest
 * @package Taranto\ListMaker\Tests\Application\CommandHandler\Board
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class CreateBoardHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function it_adds_a_new_board_to_the_repository()
    {
        $command = CreateBoard::request((string) BoardId::generate(), ['title' => 'To-Dos']);
        $board = Board::create($command->aggregateId(), $command->title());

        $repository = $this->prophesize(BoardRepository::class);
        $repository->save($board)->shouldBeCalled();

        $handler = new CreateBoardHandler($repository->reveal());
        $handler($command);
    }
}
