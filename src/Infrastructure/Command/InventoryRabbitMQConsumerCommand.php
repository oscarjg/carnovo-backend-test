<?php

namespace App\Infrastructure\Command;

use App\Application\CarInventoryUseCase;
use App\Domain\Cars\Contract\CarRepositoryInterface;
use App\Domain\Cars\ValueObject\BrandModelCar;
use App\Infrastructure\Helpers\MongoUUIDCarIdGenerator;
use App\Infrastructure\Helpers\PriceUEResolver;
use App\Infrastructure\Helpers\PriceUSResolver;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class InventoryRabbitMQConsumerCommand
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Infrastructure\Command
 */
class InventoryRabbitMQConsumerCommand extends AbstractConsumerCommand
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
        parent::__construct('carnovo:inventory:rabbitmq:consumer');

        $this->carRepository = $carRepository;
    }

    public function configure()
    {
        $this
            ->setDefinition([
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
        $connection = new AMQPStreamConnection(
            $input->getOption('host'),
            $input->getOption('port'),
            $input->getOption('username'),
            $input->getOption('password'),
        );

        $channel = $this->buildChannel(
            $connection,
            self::DEFAULT_EXCHANGE,
            'carnovo.invetory_on_imported',
            "carnovo.inventory.1.cars.imported",
            $this->getClosure($output, self::DEFAULT_MAX_MESSAGES)
        );

        $output->writeln(
            "[*] Waiting for messages. To exit press CTRL+C",
            OutputInterface::VERBOSITY_VERBOSE
        );

        while (count($channel->callbacks)) {
            $channel->wait();
        }

        return 0;
    }

    /**
     * @param OutputInterface $output
     * @param int $maxMessages
     *
     * @return \Closure
     */
    private function getClosure(OutputInterface $output, int $maxMessages): \Closure
    {
        return function (AMQPMessage $msg) use ($output, $maxMessages) {
            /**
             * @var AMQPChannel $channel
             */
            $channel     = $msg->getChannel();
            $deliveryTag = $msg->getDeliveryTag();

            try {
                $output->writeln(
                    sprintf('[x] Received: %s', $msg->body),
                    OutputInterface::VERBOSITY_VERBOSE
                );

                $data = json_decode($msg->body, true);

                $carModel = new BrandModelCar($data["brand"], $data["model"]);
                $priceEU  = (new PriceUEResolver())->fetchPrice($carModel);
                $priceUS  = (new PriceUSResolver())->fetchPrice($carModel);

                $useCase = new CarInventoryUseCase(
                    $this->carRepository,
                    new MongoUUIDCarIdGenerator()
                );

                $useCase($carModel, $priceEU, $priceUS);

                $channel->basic_ack($deliveryTag);

                $output->writeln(
                    sprintf('Done - ack with tag %s', $deliveryTag),
                    OutputInterface::VERBOSITY_VERBOSE
                );

                $this->handleLimitMessages($output, $maxMessages);
            } catch (\Exception $e) {
                $channel->basic_reject($deliveryTag, false);

                $output->writeln(
                    sprintf('An error has been produced: %s', $e->getMessage()),
                    OutputInterface::VERBOSITY_VERBOSE
                );
            }
        };
    }
}
