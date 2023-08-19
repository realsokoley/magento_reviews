<?php

namespace App\Console\Commands;

use App\Helper\ChatGPTRequest;
use App\Models\Level;
use App\Models\PrivateTopic;
use App\Models\PrivateTopicLevel;
use App\Models\WordList;
use Illuminate\Console\Command;

class GeneratePrivateTopicWordLists extends Command
{
    protected array $topics = [];
    protected array $levels = [];
    protected array $wordLists = [];

    protected string $levelsString = '';

    protected ChatGPTRequest $chatGPTRequest;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-private-topic-word-lists';

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
        $processingTopics = PrivateTopic::where('state', 1)->get()->toArray();
        if (count($processingTopics) > 0) {
            return;
        }

        $privateTopic = $this->getPendingPrivateTopic();
        $privateTopic->state = 1;
        $privateTopic->save();
        $this->generateTopicWordList($privateTopic);
    }

    public function generateTopicWordList($topic)
    {
        PrivateTopicLevel::where('topic_id', '=', $topic['id'])->delete();
        $levels = $this->getLevels();
        $levelsString = $this->getLevelsString($levels);
        print 'Generating response for topic "' . $topic['topic'] . '"';

        $jsonResult = $this->chatGPTRequest->ask("Please split at least 24 Finnish words for topic \"" . $topic['topic'] . "\" to ". $levelsString ." and share me a result with translations as a json string. For the JSON template please use " . $this->getJsonTemplate());
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
            $topicLevel = new PrivateTopicLevel();
            $topicLevel->level_id = $level['id'];
            $topicLevel->topic_id = $topic['id'];
            $topicLevel->word_list_id = $wordList->id;
            $topicLevel->save();
        }

        $topic->state = 2;
        $topic->save();
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

    public function getPendingPrivateTopic(): PrivateTopic
    {
        return PrivateTopic::where(
            [
                ['state', 0],
                ['ai_words', 0]
            ]
        )->first();
    }
}
