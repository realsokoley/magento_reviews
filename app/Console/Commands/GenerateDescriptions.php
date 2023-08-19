<?php

namespace App\Console\Commands;

use App\Models\MetaTopic;
use App\Models\Topic;

class GenerateDescriptions extends GenerateTopicLevelTasksFillBlanks
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-descriptions';

    public function handle(): void
    {
        $topicArray = Topic::where('description', '')->get()->toArray();
        $metaTopicArray = MetaTopic::where('description', '')->get()->toArray();
        foreach ($topicArray as $topic) {
            $this->generateTopicDescription($topic['id'], $topic['topic']);
        }

        foreach ($metaTopicArray as $topic) {
            $this->generateMetaTopicDescription($topic['id'], $topic['meta_topic']);
        }
    }

    public function generateTopicDescription($id, $topic) {
        $promt = $this->getDescriptionPromt($topic);
        $result = $this->chatGPTRequest->ask($promt, $this->model);

        $topic = Topic::find($id);
        $topic->description = $result;
        $topic->save();
    }

    public function generateMetaTopicDescription($id, $topic) {
        $promt = $this->getDescriptionPromt($topic);
        $result = $this->chatGPTRequest->ask($promt, $this->model);

        $topic = MetaTopic::find($id);
        $topic->description = $result;
        $topic->save();
    }


    public function getDescriptionPromt($topic)
    {
        $promt = 'I need to populate little vocabulary description. Please generate me short description for not more than 60 symbols to describe vocabulary of following topic: "' . $topic . '"';

        return $promt;
    }
}
