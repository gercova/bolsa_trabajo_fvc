<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountBalanceImportRequest;
use App\Imports\AccountBalanceImport;
use App\Models\AccountBalance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            ->paginate(25)
            ->withQueryString();

        // KPI aggregates (same filters, no pagination)
        $baseQuery = AccountBalance::query()
            ->filterByYear($year)
            ->filterByMonth($month)
            ->filterByCategory($category)
            ->search($search);

        $totalRecords   = $baseQuery->count();
        $totalAmount    = (float) $baseQuery->sum('amount');

        // Available filter options
        $availableYears      = AccountBalance::availableYears();
        $availableMonths     = AccountBalance::availableMonths();
        $availableCategories = AccountBalance::availableCategories();

        return view('admin.account-balances.index', compact(
            'records',
            'totalRecords',
            'totalAmount',
            'availableYears',
            'availableMonths',
            'availableCategories',
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
                $msg .= ", {$importer->skippedCount} filas omitidas (vacías).";
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

        // Paginated table data
        $records = AccountBalance::query()
            ->filterByYear($selectedYear)
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        // KPI aggregates
        $baseQuery    = AccountBalance::query()->filterByYear($selectedYear);
        $totalRecords = $baseQuery->count();
        $totalAmount  = (float) $baseQuery->sum('amount');

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
            'totalRecords',
            'totalAmount',
            'monthlyTotals',
            'categoryTotals',
            'availableYears',
            'selectedYear',
        ));
    }
}
