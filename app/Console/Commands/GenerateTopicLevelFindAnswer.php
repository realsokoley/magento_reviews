<?php

namespace App\Console\Commands;

class GenerateTopicLevelFindAnswer extends GenerateTopicLevelTasksFillBlanks
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-topic-level-tasks-find-answer';
    protected $taskName = 'find_answer';

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
        $promt = 'Please find not single root synonyms phrases that can describe following words:  "'.$words.'" in '.env('LANGUAGE').' and in English. You should return JSON with following template '. $this->getJsonTemplate() . '. "task" value must be a sentence which contain more than 1 word. "task" value must not contain "answer" value as well as "translated_task" value must not contain "translated_answer" value.';
        return $promt;
    }

    public function getJsonTemplate(): string
    {
        return \file_get_contents(\base_path('resources/data/json_templates/' . env('LANGUAGE') . '/find_answer_task'));
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
