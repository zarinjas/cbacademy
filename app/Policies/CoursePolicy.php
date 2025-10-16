<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view courses list
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Course $course): bool
    {
        // Users can only view published courses
        return $course->is_published;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admins can create courses
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Course $course): bool
    {
        // Only admins can update courses
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Course $course): bool
    {
        // Only admins can delete courses
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Course $course): bool
    {
        // Only admins can restore courses
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Course $course): bool
    {
        // Only admins can permanently delete courses
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can publish/unpublish the course.
     */
    public function togglePublish(User $user, Course $course): bool
    {
        // Only admins can publish/unpublish courses
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can manage course content.
     */
    public function manageContent(User $user, Course $course): bool
    {
        // Only admins can manage course content
        return $user->isAdmin();
    }
}
