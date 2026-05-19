<?php

namespace Modules\Announcements\Services;

use App\Models\Announcement;
use App\Support\Files\FileCleanup;
use Illuminate\Http\UploadedFile;
use Modules\Announcements\Actions\CreateAnnouncementAction;
use Modules\Announcements\Actions\DeleteAnnouncementAction;
use Modules\Announcements\Actions\UpdateAnnouncementAction;

/**
 * Handles announcement persistence operations.
 *
 * Module: Announcements
 * Layer: Service
 */
class AnnouncementService
{
    /**
     * Create a new announcement record.
     *
     * @param array<string, mixed> $data
     * @param UploadedFile|null $image
     * @return Announcement
     */
    public function create(array $data, ?UploadedFile $image = null): Announcement
    {
        unset($data['image']);

        if (($data['status'] ?? null) === 'published' && empty($data['posted_at'])) {
            $data['posted_at'] = now();
        }

        if ($image) {
            $data['image_path'] = $this->storeImage($image);
        }

        return app(CreateAnnouncementAction::class)
            ->execute($data);
    }

    /**
     * Update an existing announcement record.
     *
     * @param Announcement $announcement
     * @param array<string, mixed> $data
     * @param UploadedFile|null $image
     * @return Announcement
     */
    public function update(
        Announcement $announcement,
        array $data,
        ?UploadedFile $image = null
    ): Announcement {
        unset($data['image']);

        if (($data['status'] ?? null) === 'published' && empty($data['posted_at'])) {
            $data['posted_at'] = now();
        }
        
        if ($image) {
            FileCleanup::deletePublicFile($announcement->image_path);

            $data['image_path'] = $this->storeImage($image);
        }

        return app(UpdateAnnouncementAction::class)
            ->execute($announcement, $data);
    }

    /**
     * Delete an existing announcement record and its image.
     *
     * @param Announcement $announcement
     * @return void
     */
    public function delete(Announcement $announcement): void
    {
        FileCleanup::deletePublicFile($announcement->image_path);

        app(DeleteAnnouncementAction::class)
            ->execute($announcement);
    }

    /**
     * Store an announcement image under public/uploads.
     *
     * @param UploadedFile $image
     * @return string
     */
    protected function storeImage(UploadedFile $image): string
    {
        $uploadPath = public_path('uploads/announcements');

        if (! file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $filename = 'announcement_'
            . time()
            . '_'
            . uniqid()
            . '.'
            . $image->getClientOriginalExtension();

        $image->move($uploadPath, $filename);

        return 'uploads/announcements/' . $filename;
    }
}