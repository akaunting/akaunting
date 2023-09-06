<?php
declare(strict_types=1);

namespace Plank\Mediable;

use Carbon\Carbon;
use GuzzleHttp\Psr7\Utils;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Arr;
use Plank\Mediable\Exceptions\MediaMoveException;
use Plank\Mediable\Exceptions\MediaUrlException;
use Plank\Mediable\Helpers\File;
use Plank\Mediable\UrlGenerators\TemporaryUrlGeneratorInterface;
use Plank\Mediable\UrlGenerators\UrlGeneratorInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Media Model.
 * @property int|string|null $id
 * @property string|null $disk
 * @property string|null $directory
 * @property string|null $filename
 * @property string|null $extension
 * @property string|null $basename
 * @property string|null $mime_type
 * @property string|null $aggregate_type
 * @property string|null $variant_name
 * @property int|string|null $original_media_id
 * @property int|null $size
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Pivot $pivot
 * @property Collection|Media[] $variants
 * @property Media $originalMedia
 * @method static Builder inDirectory(string $disk, string $directory, bool $recursive = false)
 * @method static Builder inOrUnderDirectory(string $disk, string $directory)
 * @method static Builder whereBasename(string $basename)
 * @method static Builder forPathOnDisk(string $disk, string $path)
 * @method static Builder unordered()
 * @method static Builder whereIsOriginal()
 * @method static Builder whereIsVariant(string $variant_name = null)
 */
class Media extends Model
{
    const TYPE_IMAGE = 'image';
    const TYPE_IMAGE_VECTOR = 'vector';
    const TYPE_PDF = 'pdf';
    const TYPE_VIDEO = 'video';
    const TYPE_AUDIO = 'audio';
    const TYPE_ARCHIVE = 'archive';
    const TYPE_DOCUMENT = 'document';
    const TYPE_SPREADSHEET = 'spreadsheet';
    const TYPE_PRESENTATION = 'presentation';
    const TYPE_OTHER = 'other';
    const TYPE_ALL = 'all';

    const VARIANT_NAME_ORIGINAL = 'original';

    protected $table = 'media';

    protected $guarded = [
        'id',
        'disk',
        'directory',
        'filename',
        'extension',
        'size',
        'mime_type',
        'aggregate_type',
        'variant_name',
        'original_media_id'
    ];

    protected $casts = [
        'size' => 'int',
    ];

    /**
     * {@inheritdoc}
     */
    public static function boot()
    {
        parent::boot();

        //remove file on deletion
        static::deleted(function (Media $media) {
            $media->handleMediaDeletion();
        });
    }

    /**
     * Retrieve all associated models of given class.
     * @param  string $class FQCN
     * @return MorphToMany
     */
    public function models(string $class): MorphToMany
    {
        return $this
            ->morphedByMany(
                $class,
                'mediable',
                config('mediable.mediables_table', 'mediables')
            )
            ->withPivot('tag', 'order');
    }

    /**
     * Relationship to variants derived from this file
     * @return HasMany
     */
    public function variants(): HasMany
    {
        return $this->hasMany(
            get_class($this),
            'original_media_id'
        );
    }

    /**
     * Relationship to the file that this file was derived from
     * @return BelongsTo
     */
    public function originalMedia(): BelongsTo
    {
        return $this->belongsTo(
            get_class($this),
            'original_media_id'
        );
    }

    /**
     * Retrieve all other variants and originals of the media
     * @return Collection|Media[]
     */
    public function getAllVariants(): Collection
    {
        // if we have an original, then the variants relation should contain all others
        if ($this->isOriginal()) {
            return $this->variants->keyBy('variant_name');
        }

        // otherwise, get the original's variants, remove this one and add the original
        $collection = $this->originalMedia->variants->except($this->getKey())
            ->keyBy('variant_name');
        $collection->offsetSet(self::VARIANT_NAME_ORIGINAL, $this->originalMedia);

        return $collection;
    }

    public function getAllVariantsAndSelf(): Collection
    {
        if ($this->isOriginal()) {
            $collection = $this->variants->keyBy('variant_name');
            $collection->offsetSet(self::VARIANT_NAME_ORIGINAL, $this);
            return $collection;
        }

        // otherwise, get the original's variants, remove this one and add the original
        $collection = $this->originalMedia->variants->keyBy('variant_name');
        $collection->offsetSet(self::VARIANT_NAME_ORIGINAL, $this->originalMedia);

        return $collection;
    }

    public function hasVariant(string $variantName): bool
    {
        return $this->findVariant($variantName) !== null;
    }

    public function findVariant(string $variantName): ?Media
    {
        $filter = function (Media $media) use ($variantName) {
            return $media->variant_name === $variantName;
        };

        if ($this->isOriginal()) {
            return $this->variants->first($filter);
        }

        if ($variantName == $this->variant_name) {
            return $this;
        }

        if ($this->originalMedia) {
            return $this->originalMedia->variants->first($filter);
        }

        return null;
    }

    public function findOriginal(): Media
    {
        if ($this->isOriginal()) {
            return $this;
        }

        return $this->originalMedia;
    }

    /**
     * Retrieve the file extension.
     * @return string
     */
    public function getBasenameAttribute(): string
    {
        return $this->filename . '.' . $this->extension;
    }

    /**
     * Query scope for to find media in a particular directory.
     * @param  Builder $q
     * @param  string $disk Filesystem disk to search in
     * @param  string $directory Path relative to disk
     * @param  bool $recursive (_optional_) If true, will find media in or under the specified directory
     * @return void
     */
    public function scopeInDirectory(Builder $q, string $disk, string $directory, bool $recursive = false): void
    {
        $q->where('disk', $disk);
        if ($recursive) {
            $directory = str_replace(['%', '_'], ['\%', '\_'], $directory);
            $q->where('directory', 'like', $directory . '%');
        } else {
            $q->where('directory', '=', $directory);
        }
    }

    /**
     * Query scope for finding media in a particular directory or one of its subdirectories.
     * @param  Builder|Media $q
     * @param  string $disk Filesystem disk to search in
     * @param  string $directory Path relative to disk
     * @return void
     */
    public function scopeInOrUnderDirectory(Builder $q, string $disk, string $directory): void
    {
        $q->inDirectory($disk, $directory, true);
    }

    /**
     * Query scope for finding media by basename.
     * @param  Builder $q
     * @param  string $basename filename and extension
     * @return void
     */
    public function scopeWhereBasename(Builder $q, string $basename): void
    {
        $q->where('filename', pathinfo($basename, PATHINFO_FILENAME))
            ->where('extension', pathinfo($basename, PATHINFO_EXTENSION));
    }

    /**
     * Query scope finding media at a path relative to a disk.
     * @param  Builder $q
     * @param  string $disk
     * @param  string $path directory, filename and extension
     * @return void
     */
    public function scopeForPathOnDisk(Builder $q, string $disk, string $path): void
    {
        $q->where('disk', $disk)
            ->where('directory', File::cleanDirname($path))
            ->where('filename', pathinfo($path, PATHINFO_FILENAME))
            ->where('extension', pathinfo($path, PATHINFO_EXTENSION));
    }

    /**
     * Query scope to remove the order by clause from the query.
     * @param  Builder $q
     * @return void
     */
    public function scopeUnordered(Builder $q): void
    {
        $query = $q->getQuery();
        if ($query->orders) {
            $query->orders = null;
        }
    }

    public function scopeWhereIsOriginal(Builder $q): void
    {
        $q->whereNull('original_media_id');
    }

    public function scopeWhereIsVariant(Builder $q, string $variant_name = null)
    {
        $q->whereNotNull('original_media_id');
        if ($variant_name) {
            $q->where('variant_name', $variant_name);
        }
    }

    /**
     * Calculate the file size in human readable byte notation.
     * @param  int $precision (_optional_) Number of decimal places to include.
     * @return string
     */
    public function readableSize(int $precision = 1): string
    {
        return File::readableSize($this->size, $precision);
    }

    /**
     * Get the path to the file relative to the root of the disk.
     * @return string
     */
    public function getDiskPath(): string
    {
        return ltrim(File::joinPathComponents((string)$this->directory, (string)$this->basename), '/');
    }

    /**
     * Get the absolute filesystem path to the file.
     * @return string
     */
    public function getAbsolutePath(): string
    {
        return $this->getUrlGenerator()->getAbsolutePath();
    }

    /**
     * Check if the file is located below the public webroot.
     * @return bool
     */
    public function isPubliclyAccessible(): bool
    {
        return $this->getUrlGenerator()->isPubliclyAccessible();
    }

    /**
     * Get the absolute URL to the media file.
     * @throws MediaUrlException If media's disk is not publicly accessible
     * @return string
     */
    public function getUrl(): string
    {
        return $this->getUrlGenerator()->getUrl();
    }

    public function getTemporaryUrl(\DateTimeInterface $expiry): string
    {
        $generator = $this->getUrlGenerator();
        if ($generator instanceof TemporaryUrlGeneratorInterface) {
            return $generator->getTemporaryUrl($expiry);
        }

        throw MediaUrlException::temporaryUrlsNotSupported($this->disk);
    }

    /**
     * Check if the file exists on disk.
     * @return bool
     */
    public function fileExists(): bool
    {
        return $this->storage()->has($this->getDiskPath());
    }

    /**
     *
     * @return bool
     */
    public function isVisible(): bool
    {
        return $this->storage()->getVisibility($this->getDiskPath()) === 'public';
    }

    public function makePrivate(): void
    {
        $this->storage()->setVisibility($this->getDiskPath(), 'private');
    }

    public function makePublic(): void
    {
        $this->storage()->setVisibility($this->getDiskPath(), 'public');
    }

    /**
     * Retrieve the contents of the file.
     * @return string
     */
    public function contents(): string
    {
        return $this->storage()->get($this->getDiskPath());
    }

    /**
     * Get a read stream to the file
     * @return StreamInterface
     */
    public function stream()
    {
        $stream = $this->storage()->readStream($this->getDiskPath());
        if (method_exists(Utils::class, 'streamFor')) {
            return Utils::streamFor($stream);
        }
        return \GuzzleHttp\Psr7\stream_for($stream);
    }

    /**
     * Verify if the Media is an original file and not a variant
     * @return bool
     */
    public function isOriginal(): bool
    {
        return $this->original_media_id === null;
    }

    /**
     * Verify if the Media is a variant of another
     * @param string|null $variantName if specified, will check if the model if a specific kind of variant
     * @return bool
     */
    public function isVariant(string $variantName = null): bool
    {
        return $this->original_media_id !== null
            && (!$variantName || $this->variant_name === $variantName);
    }

    /**
     * Convert the model into an original.
     * Detaches the Media for its previous original and other variants
     * @return $this
     */
    public function makeOriginal(): self
    {
        if ($this->isOriginal()) {
            return $this;
        }

        $this->variant_name = null;
        $this->original_media_id = null;

        return $this;
    }

    public function makeVariantOf($media, string $variantName): self
    {
        if (!$media instanceof static) {
            $media = $this->newQuery()->findOrFail($media);
        }

        $this->variant_name = $variantName;
        $this->original_media_id = $media->isOriginal()
            ? $media->getKey()
            : $media->original_media_id;

        return $this;
    }

    /**
     * Move the file to a new location on disk.
     *
     * Will invoke the `save()` method on the model after the associated file has been moved to prevent synchronization errors
     * @param  string $destination directory relative to disk root
     * @param  string $filename filename. Do not include extension
     * @return void
     * @throws MediaMoveException
     */
    public function move(string $destination, string $filename = null): void
    {
        $this->getMediaMover()->move($this, $destination, $filename);
    }

    /**
     * Rename the file in place.
     * @param  string $filename
     * @return void
     * @see Media::move()
     */
    public function rename(string $filename): void
    {
        $this->move($this->directory, $filename);
    }

    /**
     * Copy the file from one Media object to another one.
     *
     * Will invoke the `save()` method on the model after the associated file has been copied to prevent synchronization errors
     * @param  string $destination directory relative to disk root
     * @param  string $filename optional filename. Do not include extension
     * @return Media
     * @throws MediaMoveException
     */
    public function copyTo(string $destination, string $filename = null): self
    {
        return $this->getMediaMover()->copyTo($this, $destination, $filename);
    }

    /**
     * Move the file to a new location on another disk.
     *
     * Will invoke the `save()` method on the model after the associated file has been moved to prevent synchronization errors
     * @param  string $disk the disk to move the file to
     * @param  string $directory directory relative to disk root
     * @param  string $filename filename. Do not include extension
     * @return void
     * @throws MediaMoveException If attempting to change the file extension or a file with the same name already exists at the destination
     */
    public function moveToDisk(
        string $disk,
        string $destination,
        string $filename = null,
        array $options = []
    ): void {
        $this->getMediaMover()
            ->moveToDisk($this, $disk, $destination, $filename, $options);
    }

    /**
     * Copy the file from one Media object to another one on a different disk.
     *
     * This method creates a new Media object as well as duplicates the associated file on the disk.
     *
     * @param  Media $media The media to copy from
     * @param  string $disk the disk to copy the file to
     * @param  string $directory directory relative to disk root
     * @param  string $filename optional filename. Do not include extension
     *
     * @return Media
     * @throws MediaMoveException If a file with the same name already exists at the destination or it fails to copy the file
     */
    public function copyToDisk(
        string $disk,
        string $destination,
        string $filename = null,
        array $options = []
    ): self {
        return $this->getMediaMover()
            ->copyToDisk($this, $disk, $destination, $filename, $options);
    }

    protected function getMediaMover(): MediaMover
    {
        return app('mediable.mover');
    }

    protected function handleMediaDeletion(): void
    {
        // optionally detach mediable relationships on soft delete
        if (static::hasGlobalScope(SoftDeletingScope::class) && !$this->forceDeleting) {
            if (config('mediable.detach_on_soft_delete')) {
                $this->newBaseQueryBuilder()
                    ->from(config('mediable.mediables_table', 'mediables'))
                    ->where('media_id', $this->getKey())
                    ->delete();
            }
        // unlink associated file on delete
        } elseif ($this->storage()->has($this->getDiskPath())) {
            $this->storage()->delete($this->getDiskPath());
        }
    }

    /**
     * Get the filesystem object for this media.
     * @return Filesystem
     */
    protected function storage(): Filesystem
    {
        return app('filesystem')->disk($this->disk);
    }

    /**
     * Get a UrlGenerator instance for the media.
     * @return UrlGeneratorInterface
     */
    protected function getUrlGenerator(): UrlGeneratorInterface
    {
        return app('mediable.url.factory')->create($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getConnectionName()
    {
        return config('mediable.connection_name', parent::getConnectionName());
    }
}
