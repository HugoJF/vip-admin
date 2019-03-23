<?php

namespace App\Console\Commands;

use App\Mail\DuskScreenshotMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class EmailDuskScreenshots extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dusk:email-screenshots';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Emails every error screenshot taken during testing';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $directory = base_path('tests/Browser/screenshots/');

        $files = File::allFiles($directory);
        if (count($files) > 0) {
            \Mail::to(config('app.admin-email'))->send(new DuskScreenshotMail($files));
        }
    }
}
