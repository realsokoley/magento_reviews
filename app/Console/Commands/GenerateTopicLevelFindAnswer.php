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

class GenerateTopicLevelFindAnswer extends GenerateTopicLevelTasksFillBlanks
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-topic-level-tasks-find-answer';
    protected $taskName = 'find_answer';
    protected string $jsonTemplate = '{"1": [{"task": "Tervehdys, jota käytetään aamulla.", "translated_task": "A greeting used in the morning.", "answer":"Hyvää huomenta", "translated_answer":"Good morning"}]}';

    public function specificTaskValidation($dictionary) {
        if ($dictionary['task'] == $dictionary['answer']) {
            return false;
        }
        return true;
    }

    public function getSpecificTaskCondition() {
        return '';
    }

    public function getPromt($task, $words) {
        $promt = 'Please find not single root synonyms phrases that can describe following words:  "'.$words.'" in Finnish and in English. You should return JSON with following template '. $this->jsonTemplate . '. "task" value must be a sentence which contain more than 1 word. "task" value must not contain "answer" value as well as "translated_task" value must not contain "translated_answer" value.';
        return $promt;
    }

    public function generateSpecificThemeArray($array)
    {
        $arrayNew = [];
        $answersArray = [];

        foreach ($array as $taskDictionary) {
            if (strlen($taskDictionary[0]['task']) < strlen($taskDictionary[0]['answer'])) {
                $taskDictionaryNew[0]['task'] = $taskDictionary[0]['answer'];
                $taskDictionaryNew[0]['answer'] = $taskDictionary[0]['task'];
                $taskDictionaryNew[0]['translated_task'] = $taskDictionary[0]['translated_answer'];
                $taskDictionaryNew[0]['translated_answer'] = $taskDictionary[0]['translated_task'];
            } else {
                $taskDictionaryNew = $taskDictionary;
            }
            if (
                !str_contains(strtolower(trim($taskDictionaryNew[0]['task'], '.')), strtolower($taskDictionaryNew[0]['answer'])) &&
                !str_contains(strtolower(trim($taskDictionaryNew[0]['translated_task'], '.')), strtolower($taskDictionaryNew[0]['translated_answer'])) &&
                !str_contains(strtolower($taskDictionaryNew[0]['translated_task']), 'english') &&
                !in_array($taskDictionaryNew[0]['answer'], $answersArray)
            ) {
                $arrayNew[] = $taskDictionaryNew;
                $answersArray[] = $taskDictionaryNew[0]['answer'];
            }
        }

        return $arrayNew;
    }
}
