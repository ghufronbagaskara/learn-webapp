<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\BlogPost;
use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
  public function __construct(private readonly CommentService $commentService) {}

  /**
   * Display a listing of the resource.
   */
  public function index(): View
  {
    $comments = Comment::query()->with(['user', 'blogPost'])->latest()->paginate(20);

    return view('blog.comments.index', compact('comments'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create(): RedirectResponse
  {
    return redirect()
      ->route('blog.index')
      ->with('status', 'Gunakan form komentar pada halaman artikel.');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(StoreCommentRequest $request, BlogPost $blogPost): RedirectResponse
  {
    $this->commentService->create($blogPost, $request->validated(), (int) $request->user()->id);

    return back()->with('status', 'Komentar berhasil dikirim dan menunggu moderasi.');
  }

  /**
   * Display the specified resource.
   */
  public function show(Comment $comment): View
  {
    return view('blog.comments.show', compact('comment'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Comment $comment): View
  {
    return view('blog.comments.edit', compact('comment'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Comment $comment): RedirectResponse
  {
    $this->commentService->moderate(
      comment: $comment,
      isApproved: (bool) $request->boolean('is_approved'),
    );

    return back()->with('status', 'Moderasi komentar berhasil diperbarui.');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Comment $comment): RedirectResponse
  {
    $this->commentService->delete($comment);

    return back()->with('status', 'Komentar berhasil dihapus.');
  }
}
