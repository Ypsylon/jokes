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

    /** @var array|null|JokeData[] */
    protected ?array $memeDataSubset = null;

    /** @var array|null|JokeData[]  */
    protected ?array $sameInitialsSubset = null;

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

    public function getMemeData(): array
    {
        // If we have already cached data we can use them, otherwise we need to filter data first
        if ($this->memeDataSubset) {
            return $this->memeDataSubset;
        }

        $this->memeDataSubset = array_filter($this->data, fn($item) => $item->isValidMemeJoke(self::MEME_MAX_LENGTH));
        return $this->memeDataSubset;
    }

    public function getSameInitialsData(): array
    {
        // If we have already cached data we can use them, otherwise we need to filter data first
        if ($this->sameInitialsSubset) {
            return $this->sameInitialsSubset;
        }

        $this->sameInitialsSubset = array_filter($this->data, fn($item) => $item->hasSameInitials());
        return $this->sameInitialsSubset;
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