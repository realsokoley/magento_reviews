<?php

namespace App\Console\Commands;

use App\Helper\ChatGPTRequest;
use App\Models\Level;
use App\Models\Task;
use App\Models\Topic;
use App\Models\TopicLevel;
use App\Models\WordList;
use App\Models\WordListTask;
use Illuminate\Console\Command;

class GenerateTopicLevelMatchTranslation extends GenerateTopicLevelTasksFillBlanks
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-topic-level-tasks-match-translation';
    protected $taskName = 'match_translation';
    protected $jsonTemplate = '{"1": [{"task": "Autumn", "translated_task": "Autumn", "answer":"Syksy", "translated_answer":"Autumn"}]}';

    public function specificTaskValidation($dictionary) {
        return true;
    }

    public function getSpecificTaskCondition() {
        return '';
    }

    public function generateSpecificThemeArray($array) {
        $arrayNew = [];

        foreach ($array as $taskDictionary) {
            $arrayNew[] = $taskDictionary;
        }

        return $arrayNew;
    }
}
