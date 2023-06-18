<?php

namespace App\Console\Commands;

use App\Helper\ChatGPTRequest;
use App\Models\Task;
use App\Models\WordList;
use App\Models\WordListTask;
use Illuminate\Console\Command;

class GenerateTopicLevelTasksFillBlanks extends Command
{
    protected ChatGPTRequest $chatGPTRequest;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-topic-level-tasks-fill-blanks';

    protected $taskName = 'fill_blanks';
    protected string $jsonTemplate = '{"1": [{"task": "Ota se ____ pois, sillä on liian kuuma.","translated_task": "Take off that _____, it"s too hot.","answer": "takki","translated_answer": "jacket"}]}';
    protected $model = 'davinci:ft-personal-2023-04-01-17-07-52';
    protected array $wordListTaskMap = [];
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
        $task = Task::where('name', $this->taskName)->first();
        $wordListArray = WordList::whereNotNull('list')->get()->toArray();
        foreach ($wordListArray as $wordListJson) {
            $wordListTaskMap = $this->getWordListTaskMap();
            if (
                isset($wordListTaskMap[$wordListJson['id']]) &&
                isset($wordListTaskMap[$wordListJson['id']][$task->id])
            ) {
                continue;
            }

            $wordList = json_decode($wordListJson['list'], true);
            $i = 1;
            $words = '';
            foreach ($wordList as $dictionary) {
                $words .= $i == count($wordList) ? $dictionary['word'] : $dictionary['word'] . ', ';
                $i++;
            }

            $this->generateTopicWordListTask($task, $words, $wordListJson['id']);
        }
    }

    public function generateTopicWordListTask($task, $words, $id)
    {
        $promt = $this->getPromt($task->task, $words);
        print $promt;
        $jsonResult = $this->chatGPTRequest->ask($promt, $this->model);
        print_r($jsonResult);

        $array = json_decode($jsonResult, true);

        if (!$this->validateArray($array)) {
            $this->generateTopicWordListTask($task, $words, $id);

            return;
        }

        $arrayNew = $this->generateSpecificThemeArray($array);

        $wordListTaskString = json_encode($arrayNew);
        $wordListTaskString = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
            return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
        }, $wordListTaskString);


        $wordListTask = new WordListTask();
        $wordListTask->word_list_id = $id;
        $wordListTask->task_id = $task->id;
        $wordListTask->task_data = $wordListTaskString;
        $wordListTask->max_rating = 0;
        $wordListTask->count = 0;
        $wordListTask->save();
    }

    public function validateArray($array): bool
    {
        if (!$array) {
            return false;
        }

        foreach ($array as $taskDictionary) {
            if (!isset($taskDictionary[0])) {
                return false;
            }

            if (!isset($taskDictionary[0]['task']) ||
                !isset($taskDictionary[0]['translated_task']) ||
                !isset($taskDictionary[0]['answer']) ||
                !isset($taskDictionary[0]['translated_answer'])
            ) {
                return false;
            }

            if (!$this->specificTaskValidation($taskDictionary[0])) {
                return false;
            }
        }

        return true;
    }

    public function generateSpecificThemeArray($array)
    {
        $arrayNew = [];
        $answersArray = [];

        foreach ($array as $taskDictionary) {
            if (
                !str_contains($taskDictionary[0]['task'], $taskDictionary[0]['answer']) &&
                !in_array($taskDictionary[0]['answer'], $answersArray)
            ) {
                $arrayNew[] = $taskDictionary;
                $answersArray[] = $taskDictionary[0]['answer'];
            }
        }

        return $arrayNew;
    }

    public function getPromt($task, $words)
    {
        $promt = 'Please generate me an exercise "' . $task . '" for words "' . $words . '" . ' . $this->getSpecificTaskCondition() . ' Sentences from the assignment should be meaningful and varied. You should return JSON with following template ' . $this->jsonTemplate;

        return $promt;
    }

    public function getSpecificTaskCondition() {
        return 'For blank please use "______".';
    }

    public function specificTaskValidation($dictionary) {
        if (!str_contains($dictionary['task'], '____')) {
            return false;
        }

        if (!str_contains($dictionary['translated_task'], '____')) {
            return false;
        }

        return true;
    }

    public function getWordListTaskMap(): array
    {
        if ($this->wordListTaskMap) {
            return $this->wordListTaskMap;
        }

        $wordListTasks = WordListTask::whereNotNull('task_data')->get()->toArray();
        foreach ($wordListTasks as $wordListTask) {
            $this->wordListTaskMap[$wordListTask['word_list_id']][$wordListTask['task_id']] =
                $wordListTask['task_data'];
        }

        return $this->wordListTaskMap;
    }
}
