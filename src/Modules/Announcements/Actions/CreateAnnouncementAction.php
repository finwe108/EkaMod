<?php

namespace Modules\Announcements\Actions;

use App\Models\Announcement;

/**
 * Handles creation of announcement records.
 *
 * This action encapsulates the announcement creation process while
 * preserving the existing model behavior and database structure.
 *
 * Module: Announcements
 * Layer: Action
 */
class CreateAnnouncementAction
{
    /**
     * Execute the announcement creation process.
     *
     * Expected input must already be validated before reaching this action.
     *
     * @param array<string, mixed> $data
     * @return Announcement
     */
    public function execute(array $data): Announcement
    {
        return Announcement::create($data);
    }
}