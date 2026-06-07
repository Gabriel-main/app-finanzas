<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class TransactionController extends Controller
{
    public function __construct(
        private readonly TransactionService $service,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only([
            'search', 'category_id', 'type',
            'date_from', 'date_to',
            'sort_field', 'sort_direction', 'per_page',
        ]);

        $transactions = $this->service->getUserTransactions(auth()->id(), $filters);

        return response()->json($transactions);
    }

    public function store(StoreTransactionRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $transaction = $this->service->createTransaction($request->validated());
            DB::commit();

            return response()->json([
                'message' => 'Transacción registrada exitosamente.',
                'data' => $transaction->load(['category', 'account']),
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al registrar la transacción.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        $transaction = $this->service->getTransaction($id);

        if (! $transaction) {
            return response()->json([
                'message' => 'Transacción no encontrada.',
            ], 404);
        }

        return response()->json($transaction);
    }

    public function update(UpdateTransactionRequest $request, string $id): JsonResponse
    {
        DB::beginTransaction();

        try {
            $transaction = $this->service->updateTransaction($id, $request->validated());

            if (! $transaction) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Transacción no encontrada.',
                ], 404);
            }

            DB::commit();

            return response()->json([
                'message' => 'Transacción actualizada exitosamente.',
                'data' => $transaction->load(['category', 'account']),
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al actualizar la transacción.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        $deleted = $this->service->deleteTransaction($id);

        if (! $deleted) {
            return response()->json([
                'message' => 'Transacción no encontrada.',
            ], 404);
        }

        return response()->json([
            'message' => 'Transacción eliminada exitosamente.',
        ]);
    }
}
