<?php

namespace App\Console\Commands;

use App\Models\Topic;
use Illuminate\Console\Command;

class GenerateTopics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-topics';

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
        $topicsArray = \file(\base_path('resources/data/topics'));
        foreach ($topicsArray as $topic) {
            $topicModel = new Topic();
            $topicModel->topic = $topic;
            $topicModel->save();
        }
    }
}
