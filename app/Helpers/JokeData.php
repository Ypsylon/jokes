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
            intval($arr['firstNumber']),
            intval($arr['secondNumber']),
            intval($arr['thirdNumber']),
            intval($arr['calculation']),
            $arr['joke'],
            DateTime::from($arr['createdAt']),
        );
    }

    public function __construct(
        protected int $id,
        protected string $name,
        protected int $firstNumber,
        protected int $secondNumber,
        protected int $thirdNumber,
        protected int $calculation,
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

    public function getFirstNumber(): int
    {
        return $this->firstNumber;
    }

    public function setFirstNumber(int $firstNumber): void
    {
        $this->firstNumber = $firstNumber;
    }

    public function getSecondNumber(): int
    {
        return $this->secondNumber;
    }

    public function setSecondNumber(int $secondNumber): void
    {
        $this->secondNumber = $secondNumber;
    }

    public function getThirdNumber(): int
    {
        return $this->thirdNumber;
    }

    public function setThirdNumber(int $thirdNumber): void
    {
        $this->thirdNumber = $thirdNumber;
    }

    public function getCalculation(): int
    {
        return $this->calculation;
    }

    public function setCalculation(int $calculation): void
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