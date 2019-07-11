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
use Taranto\ListMaker\Domain\Model\Common\ValueObject\Title;

/**
 * Class CreateBoardHandlerTest
 * @package Taranto\ListMaker\Tests\Application\CommandHandler\Board
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class CreateBoardHandlerTest extends TestCase
{
    /**
     * @var BoardId
     */
    private $boardId;

    /**
     * @var Title
     */
    private $boardTitle;

    /**
     * @var Board
     */
    private $board;

    /**
     * @var BoardRepository
     */
    private $boardRepository;

    /**
     * @var CreateBoardHandler
     */
    private $createBoardHandler;

    protected function setUp(): void
    {
        $this->boardId = BoardId::generate();
        $this->boardTitle = Title::fromString('Board Title');
        $this->board = Board::create($this->boardId, $this->boardTitle);
        $this->boardRepository = $this->prophesize(BoardRepository::class);
        $this->createBoardHandler = new CreateBoardHandler($this->boardRepository->reveal());
    }

    /**
     * @test
     */
    public function it_adds_a_new_board_to_the_repository(): void
    {
        $command = CreateBoard::request(
            (string) $this->boardId,
            ['title' => (string) $this->boardTitle]
        );

        ($this->createBoardHandler)($command);

        $this->boardRepository->save($this->board)->shouldHaveBeenCalled();
    }
}
