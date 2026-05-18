<?php

namespace Modules\Announcements\Services;

use App\Models\Announcement;
use Modules\Announcements\Actions\CreateAnnouncementAction;
use Modules\Announcements\Actions\DeleteAnnouncementAction;
use Modules\Announcements\Actions\UpdateAnnouncementAction;

/**
 * Handles announcement persistence operations.
 *
 * This service keeps announcement-related write logic outside the controller
 * while preserving the existing model, validation, database schema, and
 * business behavior.
 *
 * Module: Announcements
 * Layer: Service
 */
class AnnouncementService
{
    /**
     * Create a new announcement record.
     *
     * Expected input is already validated by the controller or form request.
     * This method does not perform validation to avoid changing the existing
     * request handling behavior during migration.
     *
     * @param array<string, mixed> $data
     * @return Announcement
     */
    public function create(array $data): Announcement
    {
        return app(CreateAnnouncementAction::class)
            ->execute($data);
    }

    /**
     * Update an existing announcement record.
     *
     * Expected input is already validated before reaching this service.
     *
     * @param Announcement $announcement
     * @param array<string, mixed> $data
     * @return Announcement
     */
    public function update(Announcement $announcement, array $data): Announcement 
    {
        return app(UpdateAnnouncementAction::class)
            ->execute($announcement, $data);
    }

    /**
     * Delete an existing announcement record.
     *
     * This preserves the current hard-delete behavior. Do not change this
     * to soft deletes unless the schema and business rule are updated later.
     *
     * @param Announcement $announcement
     * @return void
     */
    public function delete(Announcement $announcement): void
    {
        app(DeleteAnnouncementAction::class)
            ->execute($announcement);
    }
}