<?php

namespace App\Traits;

use App\Models\Common\Media as MediaModel;
use App\Utilities\Date;
use Illuminate\Support\Str;
use MediaUploader;

trait Uploads
{
    public function getMedia($file, $folder = 'settings', $company_id = null)
    {
        $path = '';

        if (! $file || ! $file->isValid()) {
            return $path;
        }

        $path = $this->getMediaFolder($folder, $company_id);

        $file_name = $this->getMediaFileName($file);

        return MediaUploader::makePrivate()
                            ->beforeSave(function(MediaModel $media) {
                                $media->company_id = company_id();
                                $media->created_from = source_name();
                                $media->created_by = user_id();
                            })
                            ->fromSource($file)
                            ->toDirectory($path)
                            ->useFilename($file_name)
                            ->upload();
    }

    public function importMedia($file, $folder = 'settings', $company_id = null, $disk = null)
    {
        $path = '';

        if (! $disk) {
            $disk = config('mediable.default_disk');
        }

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
}
