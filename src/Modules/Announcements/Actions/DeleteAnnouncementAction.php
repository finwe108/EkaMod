<?php

namespace Modules\Announcements\Actions;

use App\Models\Announcement;

/**
 * Handles deletion of announcement records.
 *
 * Module: Announcements
 * Layer: Action
 */
class DeleteAnnouncementAction
{
    /**
     * Delete an existing announcement.
     *
     * @param Announcement $announcement
     * @return void
     */
    public function execute(Announcement $announcement): void
    {
        $announcement->delete();
    }
}