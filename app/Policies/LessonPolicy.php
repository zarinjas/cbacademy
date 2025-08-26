<?php

namespace App\Policies;

use App\Models\Lesson;
use App\Models\User;

class LessonPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view lessons list
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Lesson $lesson): bool
    {
        // Users can only view published lessons
        return $lesson->is_published;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admins can create lessons
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Lesson $lesson): bool
    {
        // Only admins can update lessons
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Lesson $lesson): bool
    {
        // Only admins can delete lessons
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Lesson $lesson): bool
    {
        // Only admins can restore lessons
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Lesson $lesson): bool
    {
        // Only admins can permanently delete lessons
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can publish/unpublish the lesson.
     */
    public function togglePublish(User $user, Lesson $lesson): bool
    {
        // Only admins can publish/unpublish lessons
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can toggle free preview status.
     */
    public function toggleFreePreview(User $user, Lesson $lesson): bool
    {
        // Only admins can toggle free preview status
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can access the lesson content.
     */
    public function accessContent(User $user, Lesson $lesson): bool
    {
        // Users can access published lessons
        if (!$lesson->is_published) {
            return false;
        }

        // Free preview lessons are accessible to all
        if ($lesson->is_free_preview) {
            return true;
        }

        // For non-free lessons, check if user has access (future implementation)
        // For now, all authenticated users can access published lessons
        return true;
    }

    /**
     * Determine whether the user can manage lesson content.
     */
    public function manageContent(User $user, Lesson $lesson): bool
    {
        // Only admins can manage lesson content
        return $user->isAdmin();
    }
}
