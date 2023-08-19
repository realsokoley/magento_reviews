<?php

namespace App\Console\Commands;

use App\Models\PrivateTopic;

class GeneratePrivateTopicDescriptions extends GenerateDescriptions
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-private-topic-descriptions';

    public function handle(): void
    {
        $topicArray = PrivateTopic::where('description', '')->get()->toArray();
        foreach ($topicArray as $topic) {
            $this->generateTopicDescription($topic['id'], $topic['topic']);
        }
    }

    public function generateTopicDescription($id, $topic) {
        $promt = $this->getDescriptionPromt($topic);
        $result = $this->chatGPTRequest->ask($promt, $this->model);

        $topic = PrivateTopic::find($id);
        $topic->description = $result;
        $topic->save();
    }
}
