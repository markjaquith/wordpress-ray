<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Spatie\WordPressRay\Symfony\Component\Console\Descriptor;

use Spatie\WordPressRay\Symfony\Component\Console\Application;
use Spatie\WordPressRay\Symfony\Component\Console\Command\Command;
use Spatie\WordPressRay\Symfony\Component\Console\Exception\InvalidArgumentException;
use Spatie\WordPressRay\Symfony\Component\Console\Input\InputArgument;
use Spatie\WordPressRay\Symfony\Component\Console\Input\InputDefinition;
use Spatie\WordPressRay\Symfony\Component\Console\Input\InputOption;
use Spatie\WordPressRay\Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Jean-François Simon <jeanfrancois.simon@sensiolabs.com>
 *
 * @internal
 */
abstract class Descriptor implements DescriptorInterface
{
    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * {@inheritdoc}
     */
    public function describe(OutputInterface $output, $object, array $options = [])
    {
        $this->output = $output;

        switch (true) {
            case $object instanceof InputArgument:
                $this->describeInputArgument($object, $options);
                break;
            case $object instanceof InputOption:
                $this->describeInputOption($object, $options);
                break;
            case $object instanceof InputDefinition:
                $this->describeInputDefinition($object, $options);
                break;
            case $object instanceof Command:
                $this->describeCommand($object, $options);
                break;
            case $object instanceof Application:
                $this->describeApplication($object, $options);
                break;
            default:
                throw new InvalidArgumentException(sprintf('Object of type "%s" is not describable.', get_debug_type($object)));
        }
    }

    /**
     * Writes content to output.
     */
    protected function write(string $content, bool $decorated = false)
    {
        $this->output->write($content, false, $decorated ? OutputInterface::OUTPUT_NORMAL : OutputInterface::OUTPUT_RAW);
    }

    /**
     * Describes an InputArgument instance.
     *
     * @return string|mixed
     */
    abstract protected function describeInputArgument(InputArgument $argument, array $options = []);

    /**
     * Describes an InputOption instance.
     *
     * @return string|mixed
     */
    abstract protected function describeInputOption(InputOption $option, array $options = []);

    /**
     * Describes an InputDefinition instance.
     *
     * @return string|mixed
     */
    abstract protected function describeInputDefinition(InputDefinition $definition, array $options = []);

    /**
     * Describes a Command instance.
     *
     * @return string|mixed
     */
    abstract protected function describeCommand(Command $command, array $options = []);

    /**
     * Describes an Application instance.
     *
     * @return string|mixed
     */
    abstract protected function describeApplication(Application $application, array $options = []);
}
