<?php

namespace Modules\Announcements\Actions;

use App\Models\Announcement;

/**
 * Handles updating announcement records.
 *
 * Module: Announcements
 * Layer: Action
 */
class UpdateAnnouncementAction
{
    /**
     * Update an existing announcement.
     *
     * @param Announcement $announcement
     * @param array<string, mixed> $data
     * @return Announcement
     */
    public function execute(
        Announcement $announcement,
        array $data
    ): Announcement {
        $announcement->update($data);

        return $announcement;
    }
}