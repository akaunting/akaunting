<?php

namespace App\Traits;

use App\Models\Common\Media as MediaModel;
use App\Utilities\Date;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use MediaUploader;
use Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait Uploads
{
    public function getMedia($file, $folder = 'settings', $company_id = null, $allowed_mimes = null, bool $normalize_image = false, string $error_field = 'file')
    {
        $path = '';

        if (! $file || ! $file->isValid()) {
            return $path;
        }

        $this->validateUploadedFile($file, $allowed_mimes, $error_field);

        $normalized_file = $normalize_image ? $this->normalizeImageUpload($file, $error_field) : $file;

        $path = $this->getMediaFolder($folder, $company_id);

        $file_name = $this->getMediaFileName($normalized_file);

        try {
            return MediaUploader::makePrivate()
                                ->beforeSave(function(MediaModel $media) {
                                    $media->company_id = company_id();
                                    $media->created_from = source_name();
                                    $media->created_by = user_id();
                                })
                                ->fromSource($normalized_file)
                                ->toDirectory($path)
                                ->useFilename($file_name)
                                ->upload();
        } finally {
            if ($normalized_file !== $file) {
                @unlink($normalized_file->getPathname());
            }
        }
    }

    protected function normalizeImageUpload($file, string $error_field)
    {
        if (! Str::startsWith((string) $file->getMimeType(), 'image/')) {
            return $file;
        }

        $temporary_file = null;
        $preserve_temporary_file = false;

        try {
            $max_width = (int) config('filesystems.max_width');
            $max_height = (int) config('filesystems.max_height');
            $image = Image::make($file)->orientate();

            if ($image->width() === $image->height() && $image->width() <= $max_width && $image->height() <= $max_height) {
                return $file;
            }

            $extension = Str::lower($this->extension($file));
            $format = in_array($extension, ['jpg', 'jpeg'], true) ? 'jpg' : $extension;
            $temporary_path = tempnam(sys_get_temp_dir(), 'akaunting_logo_');

            if ($temporary_path === false) {
                throw new \RuntimeException('Unable to create a temporary logo file.');
            }

            $temporary_file = $temporary_path . '.' . $extension;

            if (! rename($temporary_path, $temporary_file)) {
                @unlink($temporary_path);

                throw new \RuntimeException('Unable to prepare a temporary logo file.');
            }

            $size = min($max_width, $max_height, $image->width(), $image->height());

            $image->fit($size, $size, function ($constraint) {
                $constraint->upsize();
            })->save($temporary_file, $format === 'png' ? 9 : 90);

            $preserve_temporary_file = true;

            return new UploadedFile(
                $temporary_file,
                $file->getClientOriginalName(),
                $file->getMimeType(),
                null,
                true,
            );
        } catch (\Throwable $exception) {
            throw ValidationException::withMessages([
                $error_field => trans('validation.image', ['attribute' => trans('settings.company.logo')]),
            ]);
        } finally {
            if ($temporary_file && ! $preserve_temporary_file && is_file($temporary_file)) {
                @unlink($temporary_file);
            }
        }
    }

    public function importMedia($file, $folder = 'settings', $company_id = null, $disk = null, $allowed_mimes = null)
    {
        $path = '';

        if (! $disk) {
            $disk = config('mediable.default_disk');
        }

        $this->validateImportedFilePath($file, $allowed_mimes);

        $path = $this->getMediaFolder($folder, $company_id) . '/' . basename($file);

        return MediaUploader::makePrivate()
                            ->beforeSave(function(MediaModel $media) {
                                $media->company_id = company_id();
                                $media->created_from = source_name();
                                $media->created_by = user_id();
                            })
                            ->importPath($disk, $path);
    }

    public function deleteMediaModel($model, $parameter, $request = null)
    {
        $medias = $model->$parameter;

        if (! $medias) {
            return;
        }

        $multiple = true;

        if ($medias instanceof \App\Models\Common\Media) {
            $multiple = false;

            $medias = [$medias];
        }

        $already_uploaded = [];

        if ($request && isset($request['uploaded_' . $parameter])) {
            $uploaded = ($multiple) ? $request['uploaded_' . $parameter] : [$request['uploaded_' . $parameter]];

            if (count($medias) == count($uploaded)) {
                return;
            }

            foreach ($uploaded as $old_media) {
                $already_uploaded[] = $old_media['id'];
            }
        }

        foreach ((array) $medias as $media) {
            if (in_array($media->id, $already_uploaded)) {
                continue;
            }

            MediaModel::where('id', $media->id)->delete();
        }
    }

    public function getMediaFolder($folder, $company_id = null)
    {
        if (! $company_id) {
            $company_id = company_id();
        }

        $date = Date::now()->format('Y/m/d');

        // 2021/04/09/34235/invoices
        return $date . '/' . $company_id . '/' . $folder;
    }

    public function getMediaPathOnStorage($media)
    {
        if (! is_object($media)) {
            return false;
        }

        $path = $media->getDiskPath();

        if (! $media->fileExists()) {
            return false;
        }

        return $path;
    }

    public function streamMedia($media, $disposition = 'attachment')
    {
        return response()->streamDownload(
            function() use ($media) {
                $stream = $media->stream();

                while ($bytes = $stream->read(1024)) {
                    echo $bytes;
                }
            },
            $media->basename,
            [
                'Content-Type'      => $media->mime_type,
                'Content-Length'    => $media->size,
            ],
            $disposition,
        );
    }

    public function isLocalStorage()
    {
        return config('filesystems.disks.' . config('filesystems.default') . '.driver') == 'local';
    }

    public function getMediaFileName($file): string
    {
        $file_name = $this->filename($file);

        if (Str::length($file_name) > '110') {
            $file_name = Str::limit($file_name, 110);
        }

        return $file_name;
    }

    /**
     * {@inheritdoc}
     */
    public function filename($file): string
    {
        return pathinfo((string)$file->getClientOriginalName(), PATHINFO_FILENAME);
    }

    /**
     * {@inheritdoc}
     */
    public function extension($file): string
    {
        $extension = $file->getClientOriginalExtension();

        if ($extension) {
            return $extension;
        }

        return (string)$file->guessExtension();
    }

    protected function validateUploadedFile($file, $allowed_mimes = null, string $error_field = 'file'): void
    {
        $mimes = implode(',', $this->getAllowedMimes($allowed_mimes));
        $maxSize = (int) config('filesystems.max_size') * 1024;

        $validator = validator(
            ['file' => $file],
            ['file' => 'file|mimes:' . $mimes . '|max:' . $maxSize]
        );

        if ($validator->fails()) {
            throw ValidationException::withMessages([
                $error_field => $validator->errors()->first('file'),
            ]);
        }
    }

    protected function validateImportedFilePath($file, $allowed_mimes = null): void
    {
        $extension = Str::lower(pathinfo((string) $file, PATHINFO_EXTENSION));

        $allowed = $this->getAllowedMimes($allowed_mimes);

        if (empty($extension) || ! in_array($extension, $allowed, true)) {
            throw ValidationException::withMessages([
                'file' => trans('validation.mimes', ['values' => implode(', ', $allowed)]),
            ]);
        }
    }

    /**
     * Get the file types allowed for the upload.
     *
     * The caller knows what it accepts, so it may pass its own file types,
     * otherwise the global whitelist is used.
     */
    protected function getAllowedMimes($allowed_mimes = null): array
    {
        $mimes = ! empty($allowed_mimes) ? $allowed_mimes : config('filesystems.mimes');

        if (is_string($mimes)) {
            $mimes = explode(',', $mimes);
        }

        return collect((array) $mimes)
                    ->map(fn ($mime) => Str::lower(trim((string) $mime)))
                    ->filter()
                    ->unique()
                    ->values()
                    ->all();
    }
}
