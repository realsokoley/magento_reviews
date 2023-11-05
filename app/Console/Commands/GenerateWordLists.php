<?php

namespace App\Console\Commands;

use App\Helper\ChatGPTRequest;
use App\Models\Level;
use App\Models\Topic;
use App\Models\TopicLevel;
use App\Models\WordList;
use Illuminate\Console\Command;

class GenerateWordLists extends Command
{
    protected array $topics = [];
    protected array $levels = [];
    protected array $wordLists = [];

    protected array $topicLevelMap = [];
    protected string $levelsString = '';

    protected ChatGPTRequest $chatGPTRequest;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-word-lists';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    public function __construct(
        ChatGPTRequest $chatGPTRequest
   ) {
        $this->chatGPTRequest = $chatGPTRequest;
        parent::__construct();
   }
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $topics = $this->getTopics();
        $levels = $this->getLevels();
        foreach ($topics as $topic) {
            $levelsExisted = true;
            foreach ($levels as $level) {
                $topicLevelMap = $this->getTopicLevelMap();
                if (
                    isset($topicLevelMap[$topic['id']]) &&
                    isset($topicLevelMap[$topic['id']][$level['id']])
                ) {
                    continue;
                }

                $levelsExisted = false;
            }

            if (!$levelsExisted) {
                $this->generateTopicWordList($topic);
            }
        }
    }

    public function generateTopicWordList($topic)
    {
        TopicLevel::where('topic_id', '=', $topic['id'])->delete();
        $levels = $this->getLevels();
        $levelsString = $this->getLevelsString($levels);
        print 'Generating response for topic "' . $topic['topic'] . '"';

        $jsonResult = $this->chatGPTRequest->ask("Please split at least 24 ".env('LANGUAGE')." words for topic \"" . $topic['topic'] . "\" to ". $levelsString ." and share me a result with translations as a json string. For the JSON template please use " . $this->getJsonTemplate());
        //print $jsonResult;
        $array = json_decode($jsonResult, true);
        if (!$this->validateArray($array)) {
            $this->generateTopicWordList($topic);

            return;
        }

        foreach ($levels as $level) {
            $wordListString = json_encode($array[$level['level']]);
            $wordListString = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
                return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
            }, $wordListString);
            $wordList = new WordList();
            $wordList->list = $wordListString;
            $wordList->max_rating = 0;
            $wordList->save();
            $topicLevel = new TopicLevel();
            $topicLevel->level_id = $level['id'];
            $topicLevel->topic_id = $topic['id'];
            $topicLevel->word_list_id = $wordList->id;
            $topicLevel->save();
        }
    }

    public function getJsonTemplate(): string
    {
        return '{"basic": [{"word": "Hei","translation": "Hello"}], "medium": [{"word": "Hyvää iltaa","translation": "Good Evening"}], "advanced": [{"word": "Hyvää viikonloppua","translation": "Have a good weekend"}]}';
    }

    public function validateArray($array): bool
    {
        $levels = $this->getLevels();
        foreach ($levels as $level) {
            if (!isset($array[$level['level']])) {
                return false;
            }

            foreach ($array[$level['level']] as $levelWord) {
                if (!isset($levelWord['word']) || !isset($levelWord['translation'])) {
                    return false;
                }
            }
        }

        return true;
    }

    public function getLevels(): array
    {
        if ($this->levels) {
            return $this->levels;
        }

        $this->levels = Level::whereNotNull('level')->get()->toArray();
        return $this->levels;
    }

    public function getLevelsString($levels): string
    {
        if ($this->levelsString) {
            return $this->levelsString;
        }

        $i = 1;
        foreach ($levels as $level) {
            if ($i == count($levels)) {
                $this->levelsString .= $level['level'];
            } else if ($i == count($levels) - 1) {
                $this->levelsString .= $level['level'] . ' and ';
            } else {
                $this->levelsString .= $level['level'] . ', ';
            }
            $i++;
        }

        return $this->levelsString;
    }

    public function getTopics(): array
    {
        if ($this->topics) {
            return $this->topics;
        }

        $this->topics = Topic::whereNotNull('topic')->get()->toArray();
        return $this->topics;
    }

    public function getTopicLevelMap(): array
    {
        if ($this->topicLevelMap) {
            return $this->topicLevelMap;
        }

        $topicLevelWordLists = TopicLevel::whereNotNull('word_list_id')->get()->toArray();
        foreach ($topicLevelWordLists as $topicLevelWordList) {
            $this->topicLevelMap[$topicLevelWordList['topic_id']][$topicLevelWordList['level_id']] =
                $topicLevelWordList['word_list_id'];
        }

        return $this->topicLevelMap;
    }
}
