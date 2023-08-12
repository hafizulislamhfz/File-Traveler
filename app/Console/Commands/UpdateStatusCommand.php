<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\File;
use Illuminate\Support\Facades\Storage;

class UpdateStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the status of rows to 0 after 10 minutes and File delete from Public.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Calculate the time 10 minutes ago
        $timeThreshold = now()->subMinutes(10);

        // Update the status of rows created 10 minutes ago or earlier to 0
        File::where('created_at', '<=', $timeThreshold)
            ->update(['status' => 0]);

        //delete files from public which status is 0
        $fileNamesToDelete = File::where('status', 0)->pluck('name');

        foreach ($fileNamesToDelete as $fileName) {
            $fileToDelete = public_path('UploadedFiles/'.$fileName);
            if (file_exists($fileToDelete)) {
                unlink($fileToDelete);
            }
        }

        $this->info('Status updated successfully.');
        //this will happen at every one minute,check kernal.php
    }
}
