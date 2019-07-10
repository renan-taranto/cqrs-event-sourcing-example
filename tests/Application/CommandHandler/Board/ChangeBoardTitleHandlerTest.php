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
use Taranto\ListMaker\Application\CommandHandler\Board\ChangeBoardTitleHandler;
use Taranto\ListMaker\Domain\Model\Board\Board;
use Taranto\ListMaker\Domain\Model\Board\BoardId;
use Taranto\ListMaker\Domain\Model\Board\BoardRepository;
use Taranto\ListMaker\Domain\Model\Board\Command\ChangeBoardTitle;
use Taranto\ListMaker\Domain\Model\Board\Exception\BoardNotFound;

/**
 * Class ChangeBoardTitleHandlerTest
 * @package Taranto\ListMaker\Tests\Application\CommandHandler\Board
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ChangeBoardTitleHandlerTest extends TestCase
{
    /**
     * @var BoardId
     */
    private $boardId;

    /**
     * @var Board
     */
    private $board;

    /**
     * @var BoardRepository
     */
    private $boardRepository;

    /**
     * @var ChangeBoardTitleHandler
     */
    private $changeBoardTitleHandler;

    protected function setUp(): void
    {
        $this->boardId = BoardId::generate();
        $this->board = $this->prophesize(Board::class);
        $this->boardRepository = $this->prophesize(BoardRepository::class);
        $this->changeBoardTitleHandler = new ChangeBoardTitleHandler($this->boardRepository->reveal());
    }

    /**
     * @test
     */
    public function it_changes_the_title_of_a_board(): void
    {
        $command = ChangeBoardTitle::request(
            (string) $this->boardId,
            ['title' => 'Changed Title']
        );

        $this->boardRepository->get($command->aggregateId())->willReturn($this->board)->shouldBeCalled();
        $this->board->changeTitle($command->title())->shouldBeCalled();
        $this->boardRepository->save($this->board)->shouldBeCalled();

        ($this->changeBoardTitleHandler)($command);
    }

    /**
     * @test
     */
    public function it_throws_when_board_is_not_found(): void
    {
        $command = ChangeBoardTitle::request(
            (string) BoardId::generate(),
            ['title' => 'Changed Title']
        );

        $this->boardRepository->get($command->aggregateId())->willReturn(null)->shouldBeCalled();
        $this->expectExceptionObject(BoardNotFound::withBoardId($command->aggregateId()));

        ($this->changeBoardTitleHandler)($command);
    }
}
