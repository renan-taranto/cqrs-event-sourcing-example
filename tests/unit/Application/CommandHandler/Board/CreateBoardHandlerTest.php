<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\unit\Application\CommandHandler\Board;

use Codeception\Specify;
use Codeception\Test\Unit;
use Hamcrest\Core\IsEqual;
use Taranto\ListMaker\Application\CommandHandler\Board\CreateBoardHandler;
use Taranto\ListMaker\Domain\Model\Board\Board;
use Taranto\ListMaker\Domain\Model\Board\BoardId;
use Taranto\ListMaker\Domain\Model\Board\BoardRepository;
use Taranto\ListMaker\Domain\Model\Board\Command\CreateBoard;
use Taranto\ListMaker\Domain\Model\Common\ValueObject\Title;

/**
 * Class CreateBoardHandlerTest
 * @package Taranto\ListMaker\Tests\unit\Application\CommandHandler\Board
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class CreateBoardHandlerTest extends Unit
{
    use Specify;

    /**
     * @var BoardRepository
     */
    private $repository;

    /**
     * @var CreateBoardHandler
     */
    private $handler;

    /**
     * @var CreateBoard
     */
    private $command;

    /**
     * @var Board
     */
    private $board;

    protected function _before(): void
    {
        $this->repository = \Mockery::spy(BoardRepository::class);
        $this->handler = new CreateBoardHandler($this->repository);

        $boardId = BoardId::generate();
        $title = Title::fromString('To-Dos');
        $this->command = CreateBoard::request((string) $boardId, ['title' => (string) $title]);
        $this->board = Board::create($boardId, $title);
    }

    /**
     * @test
     */
    public function createBoard(): void
    {
        $this->describe("Create Board", function() {
            $this->should("create and save the Board", function() {
                ($this->handler)($this->command);

                $this->repository->shouldHaveReceived('save')->with(isEqual::equalTo($this->board));
            });
        });
    }
}
