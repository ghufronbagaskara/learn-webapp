<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{
  public function __construct(private readonly CategoryService $categoryService) {}

  /**
   * Display a listing of the resource.
   */
  public function index(): View
  {
    $categories = Category::query()->latest()->paginate(15);

    return view('blog.categories.index', compact('categories'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create(): View
  {
    return view('blog.categories.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(StoreCategoryRequest $request): RedirectResponse
  {
    $this->categoryService->create($request->validated());

    return redirect()
      ->route('categories.index')
      ->with('status', 'Kategori berhasil dibuat.');
  }

  /**
   * Display the specified resource.
   */
  public function show(Category $category): View
  {
    return view('blog.categories.show', compact('category'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Category $category): View
  {
    return view('blog.categories.edit', compact('category'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
  {
    $this->categoryService->update($category, $request->validated());

    return redirect()
      ->route('categories.index')
      ->with('status', 'Kategori berhasil diperbarui.');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Category $category): RedirectResponse
  {
    $this->categoryService->delete($category);

    return redirect()
      ->route('categories.index')
      ->with('status', 'Kategori berhasil dihapus.');
  }
}
