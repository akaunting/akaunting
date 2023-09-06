<?php
declare(strict_types=1);

namespace Plank\Mediable\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Filesystem\FilesystemManager;
use Plank\Mediable\Exceptions\MediaUploadException;
use Plank\Mediable\Helpers\File;
use Plank\Mediable\Media;
use Plank\Mediable\MediaUploader;

/**
 * Import Media Artisan Command.
 */
class ImportMediaCommand extends Command
{
    /**
     * {@inheritdoc}
     * @var string
     */
    protected $signature = 'media:import {disk : the name of the filesystem disk.}
        {--d|directory= : import files in or below a given directory.}
        {--non-recursive : only import files in the specified directory.}
        {--f|force : re-process existing media.}';

    /**
     * {@inheritdoc}
     * @var string
     */
    protected $description = 'Create a media entity for each file on a disk';

    /**
     * Filesystem Manager instance.
     * @var FilesystemManager
     */
    protected $filesystem;

    /**
     * Uploader instance.
     * @var MediaUploader
     */
    protected $uploader;

    /**
     * Various counters of files being modified.
     * @var array
     */
    protected $counters = [
        'created' => 0,
        'updated' => 0,
        'skipped' => 0,
    ];

    /**
     * Constructor.
     * @param FilesystemManager $filesystem
     * @param MediaUploader $uploader
     */
    public function __construct(FileSystemManager $filesystem, MediaUploader $uploader)
    {
        parent::__construct();
        $this->filesystem = $filesystem;
        $this->uploader = $uploader;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->resetCounters();

        $disk = $this->argument('disk');
        $directory = $this->option('directory') ?: '';
        $recursive = !$this->option('non-recursive');
        $force = (bool)$this->option('force');

        $files = $this->listFiles($disk, $directory, $recursive);
        $existing_media = $this->makeModel()
            ->newQuery()
            ->inDirectory($disk, $directory, $recursive)
            ->get();

        foreach ($files as $path) {
            if ($record = $this->getRecordForFile($path, $existing_media)) {
                if ($force) {
                    $this->updateRecordForFile($record, $path);
                }
            } else {
                $this->createRecordForFile($disk, $path);
            }
        }

        $this->outputCounters();
    }

    /**
     * Generate a list of all files in the specified directory.
     * @param  string $disk
     * @param  string $directory
     * @param  bool $recursive
     * @return array
     */
    protected function listFiles(string $disk, string $directory = '', bool $recursive = true): array
    {
        if ($recursive) {
            return $this->filesystem->disk($disk)->allFiles($directory);
        } else {
            return $this->filesystem->disk($disk)->files($directory);
        }
    }

    /**
     * Search through the record list for one matching the provided path.
     * @param  string $path
     * @param  Collection $existingMedia
     * @return Media|null
     */
    protected function getRecordForFile(string $path, Collection $existingMedia): ?Media
    {
        $directory = File::cleanDirname($path);
        $filename = pathinfo($path, PATHINFO_FILENAME);
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        return $existingMedia->filter(function (Media $media) use ($directory, $filename, $extension) {
            return $media->directory == $directory && $media->filename == $filename && $media->extension == $extension;
        })->first();
    }

    /**
     * Generate a new media record.
     * @param  string $disk
     * @param  string $path
     * @return void
     */
    protected function createRecordForFile(string $disk, string $path): void
    {
        try {
            $this->uploader->importPath($disk, $path);
            ++$this->counters['created'];
            $this->info("Created Record for file at {$path}", 'v');
        } catch (MediaUploadException $e) {
            $this->warn($e->getMessage(), 'vvv');
            ++$this->counters['skipped'];
            $this->info("Skipped file at {$path}", 'v');
        }
    }

    /**
     * Update an existing media record.
     * @param  \Plank\Mediable\Media $media
     * @param  string $path
     * @return void
     */
    protected function updateRecordForFile(Media $media, string $path): void
    {
        try {
            if ($this->uploader->update($media)) {
                ++$this->counters['updated'];
                $this->info("Updated record for {$path}", 'v');
            } else {
                ++$this->counters['skipped'];
                $this->info("Skipped unmodified file at {$path}", 'v');
            }
        } catch (MediaUploadException $e) {
            $this->warn($e->getMessage(), 'vvv');
            ++$this->counters['skipped'];
            $this->info("Skipped file at {$path}", 'v');
        }
    }

    /**
     * Send the counter total to the console.
     * @return void
     */
    protected function outputCounters(): void
    {
        $this->info(sprintf('Imported %d file(s).', $this->counters['created']));
        if ($this->counters['updated'] > 0) {
            $this->info(sprintf('Updated %d record(s).', $this->counters['updated']));
        }
        if ($this->counters['skipped'] > 0) {
            $this->info(sprintf('Skipped %d file(s).', $this->counters['skipped']));
        }
    }

    /**
     * Reset the counters of processed files.
     * @return void
     */
    protected function resetCounters(): void
    {
        $this->counters = [
            'created' => 0,
            'updated' => 0,
            'skipped' => 0,
        ];
    }

    /**
     * Generate an instance of the `Media` class.
     * @return Media
     */
    private function makeModel(): Media
    {
        $class = config('mediable.model', Media::class);

        return new $class;
    }
}
