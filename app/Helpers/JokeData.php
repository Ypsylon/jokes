<?php declare(strict_types = 1);

namespace App\Helpers;

use Nette\Utils\DateTime;
use Tracy\Debugger;

class JokeData
{
    // It does not seem like we need '/' and '*' but better safe then sorry
    protected const SYMBOLS = [
        '+',
        '-',
        '*',
        '/',
    ];

    public static function fromArray(array $arr): static
    {
        return new static(
            intval($arr['id']),
            $arr['name'],
            floatval($arr['firstNumber']),
            floatval($arr['secondNumber']),
            floatval($arr['thirdNumber']),
            $arr['calculation'],
            $arr['joke'],
            DateTime::from($arr['createdAt']),
        );
    }

    public function __construct(
        protected int $id,
        protected string $name,
        protected float $firstNumber,
        protected float $secondNumber,
        protected float $thirdNumber,
        protected string $calculation,
        protected string $joke,
        protected DateTime $createdAt,
    )
    {
    }

    public function isChallengeCorrect(): bool
    {
        // Hodně simple verze která se hodí jenom na výpočty z testovacích dat
        // Prakticky každá změna formátu (třebas i jenom 1+1=2)
        $exploded = explode(' ', $this->getCalculation());
        $equalIndex = array_search('=', $exploded);
        if ($equalIndex === false) return false;

        $firstHalf = array_splice($exploded, 0, $equalIndex);
        $secondHalf = $exploded;
        unset($secondHalf[0]);

        return $this->computeArray($firstHalf) == $this->computeArray($secondHalf);
    }

    protected function computeArray(array $arr): float
    {
        $number = 0;
        $operation = null;

        foreach ($arr as $symbol) {
            if (in_array($symbol, self::SYMBOLS)) {
                $operation = $symbol;
                continue;
            }

            if ($operation !== null) {
                $number = match ($operation) {
                    '-' => $number -= intval($symbol),
                    '*' => $number *= intval($symbol),
                    '/' => $number /= intval($symbol),
                    default => $number += intval($symbol),
                };

                continue;
            }

            $number = intval($symbol);
        }

        return $number;
    }

    public function isRecent(): bool
    {
        $now = DateTime::from('now');
        $start = $now->modifyClone('-1 month');
        $end = $now->modifyClone('+1 month');

        return $this->getCreatedAt() >= $start && $this->getCreatedAt() <= $end;
    }

    public function isThirdNumberEven(): bool
    {
        return intval($this->getThirdNumber()) == $this->getThirdNumber() && $this->getThirdNumber() % 2 === 0;
    }

    public function isCalculationCorrect(): bool
    {
        return $this->getFirstNumber() / $this->getSecondNumber() == $this->getThirdNumber();
    }

    /**
     * Return's true if initials of birth name and surname are the same
     *
     * @return bool
     */
    public function hasSameInitials(): bool
    {
        $exploded = explode(' ', $this->name);
        if (count($exploded) < 2) return false;

        $l1 = strtolower(mb_substr($exploded[0], 0, 1));
        $l2 = strtolower(mb_substr($exploded[count($exploded) - 1], 0, 1));

        return $l1 === $l2;
    }

    /**
     * Return's array of two halves of joke
     *
     * @return array
     */
    public function getJokeInTwoLines(): array
    {
        $length = mb_strlen($this->getJoke());
        $middle = floor($length / 2);
        $endOfMiddleWord = strpos($this->getJoke(), ' ', intval($middle));

        if ($endOfMiddleWord === false) {
            $endOfMiddleWord = $length;
        }

        $firstHalf = substr($this->getJoke(), 0, $endOfMiddleWord);
        $secondHalf = substr($this->getJoke(), $endOfMiddleWord);

        return array($firstHalf, $secondHalf);
    }

    /**
     * Return's true if strlen is lesser or equal to maxJokeLength
     *
     * @param int $maxJokeLength
     * @return bool
     */
    public function isValidMemeJoke(int $maxJokeLength): bool
    {
        return mb_strlen($this->getJoke()) <= $maxJokeLength;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getFirstNumber(): float
    {
        return $this->firstNumber;
    }

    public function setFirstNumber(float $firstNumber): void
    {
        $this->firstNumber = $firstNumber;
    }

    public function getSecondNumber(): float
    {
        return $this->secondNumber;
    }

    public function setSecondNumber(float $secondNumber): void
    {
        $this->secondNumber = $secondNumber;
    }

    public function getThirdNumber(): float
    {
        return $this->thirdNumber;
    }

    public function setThirdNumber(float $thirdNumber): void
    {
        $this->thirdNumber = $thirdNumber;
    }

    public function getCalculation(): string
    {
        return $this->calculation;
    }

    public function setCalculation(string $calculation): void
    {
        $this->calculation = $calculation;
    }

    public function getJoke(): string
    {
        return $this->joke;
    }

    public function setJoke(string $joke): void
    {
        $this->joke = $joke;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}