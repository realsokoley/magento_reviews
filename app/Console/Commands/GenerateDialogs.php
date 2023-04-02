<?php

namespace App\Console\Commands;

use App\Helper\ChatGPTRequest;
use App\Models\Dialog;
use App\Models\Level;
use App\Models\Task;
use App\Models\Topic;
use App\Models\TopicLevel;
use App\Models\WordList;
use App\Models\WordListTask;
use Illuminate\Console\Command;

class GenerateDialogs extends GenerateTopicLevelTasksFillBlanks
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-dialogs';
    protected string $jsonTemplate = '{"1": [{"dialog": {"person1":"Moi!", "person2":"Moi! Hauska tavata"}, "translated_dialog": {"person1":"Hi!", "person2":"Hi! Nice to meet you"}}]}';

    protected array $dialogMap = [];

    public function handle(): void
    {
        $wordListArray = WordList::whereNotNull('list')->get()->toArray();
        foreach ($wordListArray as $wordListJson) {
            $dialogMap = $this->getDialogMap();
            if (
                isset($dialogMap[$wordListJson['id']])
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

            $this->generateDialog($words, $wordListJson['id']);
        }
    }

    public function generateDialog($words, $id) {
        $promt = $this->getDialogPromt($words);
        print_r($promt);
        $jsonResult = $this->chatGPTRequest->ask($promt, $this->model);
        print_r($jsonResult);

        $array = json_decode($jsonResult, true);

        if (!$this->validateArray($array)) {
            $this->generateDialog($words, $id);

            return;
        }

        $arrayNew = $this->generateSpecificThemeArray($array);

        $dialogString = json_encode($arrayNew);
        $dialogString = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
            return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
        }, $dialogString);


        $dialog = new Dialog();
        $dialog->word_list_id = $id;
        $dialog->dialog_data = $dialogString;
        $dialog->save();
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

    public function getDialogPromt($words)
    {
        $promt = 'Please generate me at least 5 simple finnish dialogs with english translations containing the words "' . $words . '"' . $this->getSpecificTaskCondition() . '. You should return JSON with following template ' . $this->jsonTemplate;

        return $promt;
    }

    public function getDialogMap(): array
    {
        if ($this->dialogMap) {
            return $this->dialogMap;
        }

        $dialogWordLists = Dialog::whereNotNull('dialog_data')->get()->toArray();
        foreach ($dialogWordLists as $dialogWordList) {
            $this->dialogMap[$dialogWordList['word_list_id']] =
                $dialogWordList['dialog_data'];
        }

        return $this->dialogMap;
    }

    public function validateArray($array): bool
    {
        if (!$array) {
            return false;
        }

        foreach ($array as $dialogDictionary) {
            if (!isset($dialogDictionary[0])) {
                return false;
            }

            if (!isset($dialogDictionary[0]['dialog']) ||
                !isset($dialogDictionary[0]['translated_dialog']) ||
                !isset($dialogDictionary[0]['dialog']['person1']) ||
                !isset($dialogDictionary[0]['translated_dialog']['person1']) ||
                !isset($dialogDictionary[0]['dialog']['person2']) ||
                !isset($dialogDictionary[0]['translated_dialog']['person2'])
            ) {
                return false;
            }
        }

        return true;
    }
}
