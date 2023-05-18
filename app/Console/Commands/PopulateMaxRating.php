<?php

namespace App\Console\Commands;

use App\Models\WordList;
use App\Models\WordListTask;
use Illuminate\Console\Command;

class PopulateMaxRating extends Command
{
    private const RATING_MAX = 12;
    protected array $wordLists = [];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:populate-max-rating';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $wordLists = $this->getWordLists();
        foreach ($wordLists as $wordList) {
            $wordListTasks = $this->getWordListTasks($wordList['id']);
            $sum = 0;
            foreach ($wordListTasks as $wordListTask) {
                $taskData = \json_decode($wordListTask['task_data'], true);
                if ($wordListTask['task_id'] == 3) {
                    $maxRating = count($taskData[0]);
                } else {
                    $maxRating = isset($taskData[0]) ? count($taskData[0]) : 0;
                }

                $wordListTaskModel = WordListTask::find($wordListTask['id']);
                $wordListTaskModel->max_rating = $maxRating;
                $wordListTaskModel->save();
                $sum += $maxRating;
            }
            $maxSumRating = $sum <= self::RATING_MAX ? $sum : self::RATING_MAX;

            $wordListModel = WordList::find($wordList['id']);
            $wordListModel->max_rating = $maxSumRating;
            $wordListModel->save();
        }
    }

    public function getWordLists(): array
    {
        if ($this->wordLists) {
            return $this->wordLists;
        }

        $this->wordLists = WordList::whereNotNull('list')->get()->toArray();
        return $this->wordLists;
    }

    public function getWordListTasks(Int $id): array
    {
        return WordListTask::where('word_list_id', $id)->get()->toArray();
    }
}
