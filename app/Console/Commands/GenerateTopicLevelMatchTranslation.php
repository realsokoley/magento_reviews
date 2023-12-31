<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Models\WordList;
use App\Models\WordListTask;

class GenerateTopicLevelMatchTranslation extends GenerateTopicLevelTasksFillBlanks
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-topic-level-tasks-match-translation';
    protected $taskName = 'match_translation';

    public function handle(): void
    {
        $task = Task::where('name', $this->taskName)->first();
        $wordListArray = WordList::whereNotNull('list')->get()->toArray();
        foreach ($wordListArray as $wordListJson) {
            $wordList = json_decode($wordListJson['list'], true);
            $dictionaryNew = [];
            foreach ($wordList as $dictionary) {
                $dictionaryNew[] = [
                    'task' => $dictionary['word'],
                    'answer' => $dictionary['translation']
                ];
            }
            $wordListTaskString = json_encode($dictionaryNew);
            $wordListTaskString = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
                return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
            }, $wordListTaskString);


            $wordListTask = new WordListTask();
            $wordListTask->word_list_id = $wordListJson['id'];
            $wordListTask->task_id = $task->id;
            $wordListTask->task_data = $wordListTaskString;
            $wordListTask->max_rating = 0;
            $wordListTask->count = count($wordList);
            $wordListTask->save();
        }

    }

    public function getJsonTemplate(): string
    {
        return \file_get_contents(\base_path('resources/data/json_templates/' . env('LANGUAGE') . '/match_translation_task'));
    }


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

    public function getPromt($task, $words)
    {
        $promt = 'Please generate me an exercise "' . $task . '" for words "' . $words . '" . ' . $this->getSpecificTaskCondition() . '. You should return JSON with following template ' . $this->getJsonTemplate();

        return $promt;
    }

}
