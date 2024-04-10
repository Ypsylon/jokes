<?php declare(strict_types = 1);

namespace App\Helpers;

use Nette\Utils\Json;
use Tracy\Debugger;

class DataHelper
{
    public const DATA_URL = 'https://www.digilabs.cz/hiring/data.php';

    public const MEME_MAX_LENGTH = 120;

    /** @var array|JokeData[] */
    protected array $data = [];

    /** @var array|JokeData[] */
    protected array $cachedData = [];

    public function __construct()
    {
        // Let's download latest data set
        $content = file_get_contents(self::DATA_URL);
        if (!$content) {
            Debugger::log('DataHelper.php could not load base data while using url: ' . self::DATA_URL);
        }

        $json = Json::decode($content, true);
        foreach ($json as $item) {
            $this->data[] = JokeData::fromArray($item);
        }
    }

    public function getData(): array
    {
        return $this->data;
    }

    protected function getCachedData(string $string, \Closure $filter): array
    {
        // If we have already cached data we can use them, otherwise we need to filter data first
        if (isset($this->cachedData[$string])) return $this->cachedData[$string];

        $this->cachedData[$string] = array_filter($this->getData(), $filter);
        return $this->cachedData[$string];
    }

    public function getMemeData(): array
    {
        return $this->getCachedData(
            'meme',
            fn($item) => $item->isValidMemeJoke(self::MEME_MAX_LENGTH),
        );
    }

    public function getSameInitialsData(): array
    {
        return $this->getCachedData(
            'initials',
            fn($item) => $item->hasSameInitials(),
        );
    }

    public function getCalculationData(): array
    {
        return $this->getCachedData(
            'calculation',
            fn($item) => $item->isCalculationCorrect() && $item->isThirdNumberEven(),
        );
    }

    public function getRecentData(): array
    {
        return $this->getCachedData(
            'recent',
            fn($item) => $item->isRecent(),
        );
    }

    public function getChallengeData(): array
    {
        return $this->getCachedData(
            'challenge',
            fn($item) => $item->isChallengeCorrect(),
        );
    }

    /**
     * Return's random joke that has character count less or equal to constant MEME_MAX_LENGTH
     *
     * @return JokeData|null
     */
    public function getRandomJoke(): ?JokeData
    {
        $data = $this->getMemeData();
        if (count($data) === 0) return null;

        return $data[array_rand($data)];
    }
}