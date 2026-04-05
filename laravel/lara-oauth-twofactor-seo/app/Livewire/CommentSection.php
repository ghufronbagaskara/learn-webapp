<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\BlogPost;
use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CommentSection extends Component
{
  public BlogPost $post;

  #[Validate('required|string|max:5000')]
  public string $content = '';

  #[Validate('nullable|integer|exists:comments,id')]
  public ?int $parent_id = null;

  /** @var Collection<int, Comment> */
  public Collection $comments;

  /**
   * Mount component.
   */
  public function mount(BlogPost $post): void
  {
    $this->post = $post;
    $this->refreshComments();
  }

  /**
   * Submit new comment.
   */
  public function submit(CommentService $commentService): void
  {
    if (! Auth::check()) {
      $this->addError('content', 'Silakan login terlebih dahulu untuk menulis komentar.');

      return;
    }

    $validated = $this->validate();

    $commentService->create($this->post, $validated, (int) Auth::id());

    $this->reset(['content', 'parent_id']);
    $this->refreshComments();
    session()->flash('status', 'Komentar berhasil dikirim dan menunggu moderasi.');
  }

  /**
   * Set reply target.
   */
  public function replyTo(int $commentId): void
  {
    $this->parent_id = $commentId;
  }

  /**
   * Clear reply target.
   */
  public function cancelReply(): void
  {
    $this->parent_id = null;
  }

  /**
   * Render component view.
   */
  public function render(): View
  {
    return view('livewire.comment-section');
  }

  /**
   * Refresh comments list.
   */
  private function refreshComments(): void
  {
    $this->comments = $this->post->comments()
      ->approved()
      ->whereNull('parent_id')
      ->with(['user', 'replies.user'])
      ->latest()
      ->get();
  }
}
