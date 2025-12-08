<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HandleFileStorage
{
    public function getFileStorageColumns(): array
    {
        return ['photo'];
    }

    public function __call($method, $parameters)
    {
        if (preg_match('/^get([^;]+)ObjectAttribute$/', $method, $match)) {
            $column = Str::snake($match[1]);
            if (array_search($column, $this->getFileStorageColumns()) !== false) {
                if (!$this->{$column}) {
                    return [];
                }

                return [
                    "name" => basename($this->{$column}),
                    "url" => Storage::url($this->{$column}) // ini baru bisa handle file public
                ];
            }
        }

        return parent::__call($method, $parameters);
    }

    public static function bootHandlesFileStorage()
    {
        static::saved(function ($model) {
            Log::info("SAVED");
            $model->deleteOldFile();
        });

        static::deleting(function ($model) {
            Log::info("DELETING");
            $model->deleteAllStoredFiles();
        });
    }

    /**
     * Delete the old file if the file_name attribute is dirty.
     */
    protected function deleteOldFile()
    {
        foreach ($this->getFileStorageColumns() as $column) {

            if ($this->wasChanged($column) && $this->getOriginal($column)) {
                $oldFilePath = $this->getOriginal($column);

                // Check if the old file exists before deleting
                if (Storage::exists($oldFilePath)) {
                    Storage::delete($oldFilePath);
                } else if (Storage::disk('public')->exists($oldFilePath)) {
                    Storage::disk('public')->delete($oldFilePath);
                }
            }
        }
    }

    /**
     * Delete the all file.
     */

    protected function deleteAllStoredFiles()
    {
        foreach ($this->getFileStorageColumns() as $column) {
            $filePath = $this->{$column};
            if (!$filePath) {
                continue;
            }
            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
            }
            elseif (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
        }
    }

    /**
     * Store and Handle file storage prefixing.
     */
    public function storeFile($file, $prefix = '', $disk = 'local')
    {
        if (!$prefix) {
            $prefix = $this->getTable();
        }
        $file_name = Str::random(10) . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs($prefix, $file_name, $disk);
        return $filePath;
    }
}
