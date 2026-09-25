<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountBalanceImportRequest;
use App\Imports\AccountBalanceImport;
use App\Models\AccountBalance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class AccountBalanceController extends Controller
{
    // ─── Admin ────────────────────────────────────────────────────────────────

    /**
     * Display the admin listing of account balance records with filters.
     */
    public function index(Request $request): View
    {
        $year     = $request->integer('year')     ?: null;
        $month    = $request->string('month')->toString()    ?: null;
        $category = $request->string('category')->toString() ?: null;
        $search   = $request->string('search')->toString()   ?: null;

        $records = AccountBalance::query()
            ->filterByYear($year)
            ->filterByMonth($month)
            ->filterByCategory($category)
            ->search($search)
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        // KPI aggregates (filtered query, no pagination)
        $baseQuery = AccountBalance::query()
            ->filterByYear($year)
            ->filterByMonth($month)
            ->filterByCategory($category)
            ->search($search);

        $totalRecords = $baseQuery->count();
        $totalAmount  = (float) $baseQuery->sum('amount');

        // Unfiltered total records in entire table
        $totalTableRecords = AccountBalance::count();

        // Available filter options
        $availableYears      = AccountBalance::availableYears();
        $availableMonths     = AccountBalance::availableMonths();
        $availableCategories = AccountBalance::availableCategories();

        // Record counts grouped by year for modal and direct clear buttons
        $yearCounts = AccountBalance::query()
            ->selectRaw('YEAR(date) as yr, COUNT(*) as cnt')
            ->whereNotNull('date')
            ->groupBy('yr')
            ->pluck('cnt', 'yr')
            ->toArray();

        return view('admin.account-balances.index', compact(
            'records',
            'totalRecords',
            'totalAmount',
            'totalTableRecords',
            'availableYears',
            'availableMonths',
            'availableCategories',
            'yearCounts',
        ));
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(AccountBalance $accountBalance): RedirectResponse
    {
        $accountBalance->delete();

        return redirect()
            ->route('admin.account-balances.index')
            ->with('success', 'Registro eliminado correctamente.');
    }

    /**
     * Clear records from the account_balances table:
     * - If 'year' parameter is provided (e.g. 2025), deletes only records for that period.
     * - If 'all' or empty, truncates the entire table.
     * Restricted to users with the 'gestionar-inversiones' permission
     * or administrative roles (Director / Administrador / Admin).
     */
    public function truncateTable(Request $request): JsonResponse|RedirectResponse
    {
        $user = auth()->user();
        $isAuthorized = $user && (
            in_array($user->role, ['Admin', 'Administrador', 'Director'])
            || (method_exists($user, 'hasAnyRole') && $user->hasAnyRole(['Admin', 'Administrador', 'Director']))
            || $user->can('gestionar-inversiones')
        );

        if (! $isAuthorized) {
            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para realizar esta acción.',
                ], 403);
            }
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        $year = $request->input('year');

        try {
            if ($year && $year !== 'all') {
                $yearInt = (int) $year;
                $count = AccountBalance::whereYear('date', $yearInt)->delete();

                $message = $count === 1
                    ? "Se ha eliminado 1 registro correspondiente al período {$yearInt}."
                    : "Se han eliminado exitosamente {$count} registros correspondientes al período {$yearInt}.";

                if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => $message,
                        'count'   => $count,
                        'year'    => $yearInt,
                    ], 200);
                }

                return redirect()
                    ->route('admin.account-balances.index')
                    ->with('success', $message);
            }

            $totalCount = AccountBalance::count();
            if ($totalCount === 0) {
                $message = 'La tabla de Inversión y Gastos ya se encuentra vacía.';
                if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => $message,
                        'count'   => 0,
                    ], 200);
                }
                return redirect()->route('admin.account-balances.index')->with('info', $message);
            }

            try {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                AccountBalance::truncate();
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            } catch (\Exception $truncateEx) {
                AccountBalance::query()->delete();
            }

            $message = "La tabla de Inversión y Gastos ha sido vaciada completamente ({$totalCount} registros eliminados).";

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'count'   => $totalCount,
                ], 200);
            }

            return redirect()
                ->route('admin.account-balances.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            $errorMsg = 'Error al procesar la eliminación: ' . $e->getMessage();
            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMsg,
                ], 500);
            }

            return redirect()
                ->route('admin.account-balances.index')
                ->with('error', $errorMsg);
        }
    }

    /**
     * Import account balance records in bulk from an Excel (.xlsx/.xls) or CSV file.
     * Columns A–J of the source file are mapped to the account_balances table fields.
     */
    public function import(AccountBalanceImportRequest $request): RedirectResponse
    {
        try {
            $importer = new AccountBalanceImport();
            Excel::import($importer, $request->file('file'));

            $msg = "Importación completada: {$importer->importedCount} registros importados";
            if ($importer->skippedCount > 0) {
                $msg .= ", {$importer->skippedCount} filas omitidas (encabezados o vacías).";
            } else {
                $msg .= '.';
            }

            return redirect()
                ->route('admin.account-balances.index')
                ->with('success', $msg);

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = collect($e->failures())
                ->map(fn ($f) => "Fila {$f->row()}: " . implode(', ', $f->errors()))
                ->take(10)
                ->implode(' | ');

            return redirect()
                ->route('admin.account-balances.index')
                ->with('error', "Error de validación en el archivo: {$failures}");

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.account-balances.index')
                ->with('error', 'Error al procesar el archivo: ' . $e->getMessage());
        }
    }

    // ─── Public ───────────────────────────────────────────────────────────────

    /**
     * Public transparency view: Inversión y Gestión.
     * Supports filtering by year via GET parameter.
     */
    public function publicIndex(Request $request): View
    {
        $selectedYear = $request->integer('year') ?: null;

        // KPI aggregates
        $baseQuery    = AccountBalance::query()->filterByYear($selectedYear);
        $totalRecords = $baseQuery->count();
        $totalAmount  = (float) $baseQuery->sum('amount');

        // Grouped by Category (Column F) with summary metrics
        $categoryGroups = AccountBalance::query()
            ->filterByYear($selectedYear)
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->selectRaw('category, COUNT(*) as count, SUM(amount) as total_amount')
            ->groupBy('category')
            ->orderByDesc('total_amount')
            ->get();

        // Detailed records grouped by category for the accordion/grouped view
        $recordsByCategory = AccountBalance::query()
            ->filterByYear($selectedYear)
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get()
            ->groupBy(function ($item) {
                return !empty($item->category) ? trim($item->category) : 'OTROS / SIN CATEGORÍA';
            });

        // Flat paginated table data
        $records = AccountBalance::query()
            ->filterByYear($selectedYear)
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        // Chart data: monthly totals (month name → total amount)
        $monthlyTotals = AccountBalance::query()
            ->filterByYear($selectedYear)
            ->selectRaw('month, SUM(amount) as total')
            ->groupBy('month')
            ->orderByRaw('MIN(date)')
            ->get()
            ->mapWithKeys(fn ($row) => [$row->month => (float) $row->total]);

        // Chart data: category distribution
        $categoryTotals = AccountBalance::query()
            ->filterByYear($selectedYear)
            ->whereNotNull('category')
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get()
            ->mapWithKeys(fn ($row) => [$row->category => (float) $row->total]);

        $availableYears = AccountBalance::availableYears();

        return view('transparency.investment-and-management', compact(
            'records',
            'categoryGroups',
            'recordsByCategory',
            'totalRecords',
            'totalAmount',
            'monthlyTotals',
            'categoryTotals',
            'availableYears',
            'selectedYear',
        ));
    }
}
