<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryService $service,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $type = $request->get('type', 'expense');
        $categories = $this->service->getUserCategories(auth()->id(), $type);

        return response()->json($categories);
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $category = $this->service->createCategory(
                auth()->id(),
                $request->validated()
            );
            DB::commit();

            return response()->json([
                'message' => 'Categoría creada exitosamente.',
                'data' => $category,
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al crear la categoría.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(UpdateCategoryRequest $request, int $id): JsonResponse
    {
        DB::beginTransaction();

        try {
            $category = $this->service->updateCategory(
                auth()->id(),
                $id,
                $request->validated()
            );

            if (! $category) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Categoría no encontrada.',
                ], 404);
            }

            DB::commit();

            return response()->json([
                'message' => 'Categoría actualizada exitosamente.',
                'data' => $category,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al actualizar la categoría.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->service->deleteCategory(auth()->id(), $id);

        if (! $deleted) {
            return response()->json([
                'message' => 'Categoría no encontrada.',
            ], 404);
        }

        return response()->json([
            'message' => 'Categoría eliminada exitosamente.',
        ]);
    }
}
