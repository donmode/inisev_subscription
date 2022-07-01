<?php

namespace App\Jobs;

use App\Mail\NotifyMail;
use App\Models\Post;
use App\Models\Subscription;
use App\Models\Website;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $website;
    private $post;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Post $post, Website $website)
    {
        $this->website = $website;
        $this->post = $post;
    }

    /**
     * The number of seconds after which the job's unique lock will be released.
     *
     * @var int
     */
    public $uniqueFor = 200;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public function uniqueId()
    {
        return $this->post->id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::error($this->website->id);
        Log::error($this->post->id);
        $subscribers = Subscription::where('website_id', '=', $this->website->id)->with('user')->get();
        foreach ($subscribers as $subscriber) {
            $data = [
                'subject' => 'New Post Made on ' . $this->website->url,
                'receiver_email' => $subscriber->user->email,
                'title' => $this->post->title,
                'description' => $this->post->description,
                'website' => $this->website->url,
            ];
            $sent = Mail::to($data['receiver_email'])->send(new NotifyMail($data));
            if (!$sent) {
                Log::info("Mail for subscriber with User Id:  " . $subscriber->user->id . " on post " . $this->post->title . " with Post Id: " . $this->post->id . " not sent successfully");
            }
        }

    }
}
