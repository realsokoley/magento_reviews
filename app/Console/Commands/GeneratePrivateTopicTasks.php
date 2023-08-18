<?php

namespace App\Console\Commands;

use App\Helper\ChatGPTRequest;
use App\Models\Level;
use App\Models\PrivateTopic;
use App\Models\PrivateTopicLevel;
use App\Models\Task;
use App\Models\WordList;
use App\Models\WordListTask;
use Illuminate\Console\Command;

class GeneratePrivateTopicTasks extends Command
{
    protected array $topics = [];
    protected array $levels = [];
    protected array $wordLists = [];

    protected string $levelsString = '';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-private-topic-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    private GenerateTopicLevelTasksFillBlanks $generateTopicLevelTasksFillBlanks;
    private GenerateTopicLevelFindAnswer $generateTopicLevelFindAnswer;

    public function __construct(
        GenerateTopicLevelTasksFillBlanks $generateTopicLevelTasksFillBlanks,
        GenerateTopicLevelFindAnswer $generateTopicLevelFindAnswer,
        GenerateTopicLevelMatchTranslation $generateTopicLevelMatchTranslation
   ) {
        $this->generateTopicLevelTasksFillBlanks = $generateTopicLevelTasksFillBlanks;
        $this->generateTopicLevelFindAnswer = $generateTopicLevelFindAnswer;
        $this->generateTopicLevelMatchTranslation = $generateTopicLevelMatchTranslation;

        parent::__construct();
   }
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $privateTopic = $this->getPendingTasksPrivateTopic();
        $privateTopic->state = 3;
        $privateTopic->save();
        $this->generateTopicTasks($privateTopic);
    }

    public function generateTopicTasks($topic)
    {
        $wordListIdArray = [];
        $privateTopicLevelArray = PrivateTopicLevel::where('topic_id', $topic->id)->get()->toArray();
        foreach ($privateTopicLevelArray as $value) {
            $wordListIdArray[] = $value['word_list_id'];
        }

        $wordListArray = WordList::whereIn('id', $wordListIdArray)->get()->toArray();

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
                $dictionaryNew[] = [
                    'task' => $dictionary['word'],
                    'answer' => $dictionary['translation']
                ];
                $i++;
            }
            $task = Task::where('name', 'fill_blanks')->first();
            $this->generateTopicLevelTasksFillBlanks->generateTopicWordListTask($task, $words, $wordListJson['id']);
            $task = Task::where('name', 'find_answer')->first();
            $this->generateTopicLevelFindAnswer->generateTopicWordListTask($task, $words, $wordListJson['id']);
            $task = Task::where('name', 'match_translation')->first();
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

    public function getPendingTasksPrivateTopic(): PrivateTopic
    {
        return PrivateTopic::where('state', 2)->first();
    }
}
