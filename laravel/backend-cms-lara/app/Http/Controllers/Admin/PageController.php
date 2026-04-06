<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Services\SeoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PageController extends Controller
{
  public function __construct(private readonly SeoService $seoService) {}

  public function index(): View
  {
    $this->authorize('viewAny', Page::class);

    return view('admin.pages.index', [
      'pages' => Page::with('creator', 'seoMeta')->latest()->paginate(10),
      'pageTitle' => 'Pages',
    ]);
  }

  public function create(): View
  {
    $this->authorize('create', Page::class);

    return view('admin.pages.create', ['pageTitle' => 'Create Page']);
  }

  public function store(Request $request): RedirectResponse
  {
    $this->authorize('create', Page::class);

    $validated = $request->validate([
      'title' => ['required', 'string', 'max:191'],
      'slug' => ['nullable', 'string', 'max:191', 'unique:pages,slug'],
      'content' => ['required', 'string'],
      'is_published' => ['nullable', 'boolean'],
      'order' => ['nullable', 'integer', 'min:0'],
      'meta_title' => ['nullable', 'string', 'max:191'],
      'meta_description' => ['nullable', 'string'],
      'meta_keywords' => ['nullable', 'string'],
      'og_title' => ['nullable', 'string', 'max:191'],
      'og_description' => ['nullable', 'string'],
      'canonical_url' => ['nullable', 'url', 'max:191'],
    ]);

    $slug = $validated['slug'] ?: Str::slug($validated['title']);
    $validated['slug'] = $this->makeUniqueSlug($slug);

    $page = Page::create([
      'title' => $validated['title'],
      'slug' => $validated['slug'],
      'content' => $validated['content'],
      'is_published' => (bool) ($validated['is_published'] ?? false),
      'order' => $validated['order'] ?? 0,
      'created_by' => $request->user()->id,
    ]);

    $this->seoService->save($page, $validated);

    session()->flash('success', 'Page created successfully.');

    return redirect()->route('admin.pages.index');
  }

  public function edit(Page $page): View
  {
    $this->authorize('update', $page);

    return view('admin.pages.edit', [
      'pageModel' => $page->load('seoMeta'),
      'pageTitle' => 'Edit Page',
    ]);
  }

  public function show(Page $page): RedirectResponse
  {
    $this->authorize('view', $page);

    return redirect()->route('admin.pages.edit', $page);
  }

  public function update(Request $request, Page $page): RedirectResponse
  {
    $this->authorize('update', $page);

    $validated = $request->validate([
      'title' => ['required', 'string', 'max:191'],
      'slug' => ['nullable', 'string', 'max:191', Rule::unique('pages', 'slug')->ignore($page->id)],
      'content' => ['required', 'string'],
      'is_published' => ['nullable', 'boolean'],
      'order' => ['nullable', 'integer', 'min:0'],
      'meta_title' => ['nullable', 'string', 'max:191'],
      'meta_description' => ['nullable', 'string'],
      'meta_keywords' => ['nullable', 'string'],
      'og_title' => ['nullable', 'string', 'max:191'],
      'og_description' => ['nullable', 'string'],
      'canonical_url' => ['nullable', 'url', 'max:191'],
    ]);

    $slug = $validated['slug'] ?: Str::slug($validated['title']);
    $validated['slug'] = $this->makeUniqueSlug($slug, $page->id);

    $page->update([
      'title' => $validated['title'],
      'slug' => $validated['slug'],
      'content' => $validated['content'],
      'is_published' => (bool) ($validated['is_published'] ?? false),
      'order' => $validated['order'] ?? 0,
    ]);

    $this->seoService->save($page, $validated);

    session()->flash('success', 'Page updated successfully.');

    return redirect()->route('admin.pages.index');
  }

  public function destroy(Page $page): RedirectResponse
  {
    $this->authorize('delete', $page);

    $page->delete();

    session()->flash('success', 'Page deleted successfully.');

    return redirect()->route('admin.pages.index');
  }

  private function makeUniqueSlug(string $slug, ?int $ignoreId = null): string
  {
    $candidate = $slug;
    $index = 1;

    while (Page::query()
      ->where('slug', $candidate)
      ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))
      ->exists()
    ) {
      $candidate = $slug . '-' . $index;
      $index++;
    }

    return $candidate;
  }
}
