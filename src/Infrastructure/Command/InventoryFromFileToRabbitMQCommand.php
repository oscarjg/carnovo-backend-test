<?php

namespace App\Infrastructure\Command;

use App\Domain\Cars\Contract\CarRepositoryInterface;
use App\Infrastructure\Helpers\FileLinesToArray;
use App\Infrastructure\Helpers\FileLineToBrandModelCar;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class InventoryFromFileToRabbitMQCommand
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Infrastructure\Command
 */
class InventoryFromFileToRabbitMQCommand extends AbstractConsumerCommand
{
    /**
     * @var CarRepositoryInterface
     */
    private $carRepository;

    /**
     * ImportCarsFromFileSystemCommand constructor.
     *
     * @param CarRepositoryInterface $carRepository
     */
    public function __construct(CarRepositoryInterface $carRepository)
    {
        parent::__construct('carnovo:inventory:file-to-rabbitmq');

        $this->carRepository = $carRepository;
    }

    public function configure()
    {
        $this
            ->setDefinition([
                new InputOption(
                    'path',
                    null,
                    InputOption::VALUE_REQUIRED,
                    "Absolute system file to the file"
                ),
                new InputOption(
                    'brandModelColumn',
                    null,
                    InputOption::VALUE_OPTIONAL,
                    "Column where brand and model pairs are",
                    3
                ),
                new InputOption(
                    'host',
                    null,
                    InputOption::VALUE_REQUIRED,
                    "Rabbit host",
                    "rabbit"
                ),
                new InputOption(
                    'username',
                    null,
                    InputOption::VALUE_REQUIRED,
                    "Rabbit user name",
                    "rabbitmq"
                ),
                new InputOption(
                    'password',
                    null,
                    InputOption::VALUE_REQUIRED,
                    "Rabbit password",
                    "rabbitmq"
                ),
                new InputOption(
                    'port',
                    null,
                    InputOption::VALUE_REQUIRED,
                    "Rabbit port",
                    5672
                ),
            ])
            ->setDescription('A command to fetch cars data and import to database');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $linesToArray = new FileLinesToArray($input->getOption('path'));

        $connection = new AMQPStreamConnection(
            $input->getOption('host'),
            $input->getOption('port'),
            $input->getOption('username'),
            $input->getOption('password')
        );

        $channel = $connection->channel();
        $exchange = self::DEFAULT_EXCHANGE;

        $channel->exchange_declare(
            $exchange,
           'topic',
            false,
            true,
            false
        );

        foreach ($linesToArray() as $key => $line) {
            if ($key === 0) {
                continue;
            }

            $carModel = (new FileLineToBrandModelCar($line,  $input->getOption('brandModelColumn')))();

            $channel->basic_publish(
                new AMQPMessage(
                    json_encode(["brand" => $carModel->getBrand(), "model" => $carModel->getModel()]),
                    [
                        'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
                        'timestamp'     => time()
                    ]
                ),
                $exchange,
                "carnovo.inventory.1.cars.imported"
            );
        }

        $channel->close();
        $connection->close();

        return 0;
    }
}
