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
            $countArray = [];
            foreach ($wordListTasks as $wordListTask) {
                $taskData = \json_decode($wordListTask['task_data'], true);

                if (is_array($taskData)) {
                    if (isset($taskData[0]) && is_array($taskData[0])) {
                        if (isset($taskData[0][0]) && is_array($taskData[0][0])) {
                            $maxRating = max(count($taskData[0]), count($taskData));
                        } else {
                            $maxRating = count($taskData);
                        }
                    } else {
                        $maxRating = count($taskData);
                    }
                }

                $wordListTaskModel = WordListTask::find($wordListTask['id']);
                $wordListTaskModel->count = $maxRating;
                $wordListTaskModel->save();
                $sum += $maxRating;

                $countArray[$wordListTask['task_id']] =  $maxRating;
            }

            $ratingArray = $this->getMaxRatingForRequestedArray($countArray);
            foreach ($wordListTasks as $wordListTask) {
                $maxRating = $ratingArray[$wordListTask['task_id']];
                $wordListTaskModel = WordListTask::find($wordListTask['id']);
                $wordListTaskModel->max_rating = $maxRating;
                $wordListTaskModel->save();
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

        $this->wordLists = WordList::where(
            [
                ['list', 'neq', NULL],
                ['max_rating', 'eq', 0]
            ]
        )->get()->toArray();

        return $this->wordLists;
    }

    public function getWordListTasks(Int $id): array
    {
        return WordListTask::where('word_list_id', $id)->get()->toArray();
    }

    public function getMaxRatingForRequestedArray($countArray): array
    {
        print_r($countArray);
        $n = $countArray[1];
        $m = $countArray[2];
        $k = $countArray[3];

        if ($n + $m + $k <= self::RATING_MAX) {
            $n1 = $n;
            $m1 = $m;
            $k1 = $k;
        } else {
            $n1 = $m1 = $k1 = min(intval(12/3), $n, $m, $k);
            $remaining_points = 12 - ($n1 + $m1 + $k1);

            while ($remaining_points > 0) {
                if ($n1 < $n) {
                    $n1++;
                    $remaining_points--;
                }
                if ($m1 < $m && $remaining_points > 0) {
                    $m1++;
                    $remaining_points--;
                }
                if ($k1 < $k && $remaining_points > 0) {
                    $k1++;
                    $remaining_points--;
                }
            }
        }

        return [
            1 => $n1,
            2 => $m1,
            3 => $k1
        ];
    }
}
