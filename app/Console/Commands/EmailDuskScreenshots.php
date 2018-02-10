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
		$directory = __DIR__ . '/../../../tests/Browser/screenshots/';

		$files = File::allFiles($directory);

		\Mail::to('hugo_jeller@hotmail.com')->send(new DuskScreenshotMail($files));
	}
}
