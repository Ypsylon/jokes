<?php declare(strict_types = 1);

namespace App\Helpers;

use Nette\Utils\DateTime;

class JokeData
{
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