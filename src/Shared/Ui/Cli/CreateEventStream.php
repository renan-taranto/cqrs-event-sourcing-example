<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Shared\Ui\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Taranto\ListMaker\Shared\Infrastructure\Persistence\EventStore\EventStore;

/**
 * Class CreateEventStream
 * @package Taranto\ListMaker\Shared\Ui\Cli
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class CreateEventStream extends Command
{
    /**
     * @var EventStore
     */
    private $eventStore;

    /**
     * CreateEventStream constructor.
     * @param EventStore $eventStore
     */
    public function __construct(EventStore $eventStore)
    {
        parent::__construct();

        $this->eventStore = $eventStore;
    }

    protected function configure()
    {
        $this->setName('event-stream:create')
            ->setDescription('Creates the event stream.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->eventStore->createEventStream();
        $output->writeln('<info>Event stream was created successfully.</info>');
    }
}
