<?php

namespace Jakmall\Recruitment\Calculator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class PowCommand extends Command
{
    /**
     * @var string
     */
    protected $signature;

    /**
     * @var string
     */
    protected $description;

    public function __construct($description = null, $signature = null)
    {
        $commandVerb = $this->getCommandVerb();

        $this->signature = sprintf(
            '%s {base : The base number}{exp : The exp number}',
            $commandVerb,
            $this->getCommandPassiveVerb()
        );
        $this->description = sprintf('Exponent the given number', ucfirst($commandVerb));

        parent::__construct($this);
    }

    protected function getCommandVerb(): string
    {
        return 'pow';
    }

    protected function getCommandPassiveVerb(): string
    {
        return 'number';
    }

    public function handle(): void
    {
        $numbers   = (array) $this->getInput();
        $data      = Arr::only($numbers, ['base', 'exp']);
        $description    = $this->generateCalculationDescription($data);
        $result    = $this->calculateAll($data);

        $this->comment(sprintf('%s = %s', $description, $result));
    }

    protected function getInput(): array
    {
        return $this->argument();
    }

    protected function generateCalculationDescription(array $numbers): string
    {
        $operator = $this->getOperator();
        $glue = sprintf(' %s ', $operator);

        return implode($glue, $numbers);
    }

    protected function getOperator(): string
    {
        return '^';
    }

    /**
     * @param array $numbers
     *
     * @return float|int
     */
    protected function calculateAll(array $numbers)
    {
        $number = array_pop($numbers);

        if (count($numbers) <= 0) {
            return $number;
        }

        return $this->calculate($this->calculateAll($numbers), $number);
    }

    /**
     * @param int|float $number1
     * @param int|float $number2
     *
     * @return int|float
     */
    protected function calculate($number1, $number2)
    {   
        $result = pow($number1, $number2);
        return $result;
    }
}
