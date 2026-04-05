<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\BlogPost;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;

class CommentService
{
  /**
   * Store a new comment for a post.
   *
   * @param array<string, mixed> $data
   */
  public function create(BlogPost $post, array $data, int $userId): Comment
  {
    return DB::transaction(function () use ($post, $data, $userId): Comment {
      return $post->comments()->create([
        'user_id' => $userId,
        'parent_id' => $data['parent_id'] ?? null,
        'content' => $data['content'],
        'is_approved' => false,
      ]);
    });
  }

  /**
   * Update moderation state of a comment.
   */
  public function moderate(Comment $comment, bool $isApproved): Comment
  {
    $comment->update(['is_approved' => $isApproved]);

    return $comment->refresh();
  }

  /**
   * Delete a comment.
   */
  public function delete(Comment $comment): void
  {
    $comment->delete();
  }
}
