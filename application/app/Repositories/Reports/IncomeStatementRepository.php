<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for templates
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories\Reports;

use App\Models\Category;
use App\Models\Invoice;
use Illuminate\Http\Request;

class IncomeStatementRepository {

    /**
     * The repository instance.
     */
    protected $invoice;
    protected $category;

    /**
     * Inject dependecies
     */
    public function __construct(Invoice $invoice, Category $category) {
        $this->invoice = $invoice;
        $this->category = $category;

    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object invoices collection
     */
    public function getMonths() {

        $report = [];
        $total_income = 0;
        $total_expenses = 0;
        $total_profit = 0;

        $months = [
            1 => __('lang.january'),
            2 => __('lang.february'),
            3 => __('lang.march'),
            4 => __('lang.april'),
            5 => __('lang.may'),
            6 => __('lang.june'),
            7 => __('lang.july'),
            8 => __('lang.august'),
            9 => __('lang.september'),
            10 => __('lang.october'),
            11 => __('lang.november'),
            12 => __('lang.december'),
        ];

        for ($i = 1; $i < 13; $i++) {

            //income
            $income = \App\Models\Payment::whereMonth('payment_date', $i)
                ->whereYear('payment_date', request('filter_year'))
                ->sum('payment_amount');

            //expenses
            $expenses = \App\Models\Expense::whereMonth('expense_date', $i)
                ->whereYear('expense_date', request('filter_year'))
                ->sum('expense_amount');

            //running totaks
            $total_income += $income;
            $total_expenses += $expenses;
            $total_profit += $total_income - $total_expenses;

            //report
            $report[$i] = [
                'month' => $months[$i],
                'income' => $income,
                'expenses' => $expenses,
                'profit' => ($income - $expenses),
            ];

            //final
            if ($i == 12) {
                $report['totals'] = [
                    'income' => $total_income,
                    'expenses' => $total_expenses,
                    'profit' => ($total_income - $total_expenses),
                ];
            }
        }

        return $report;
    }

    /**
     * get a range of years to use in the dropdown filter. Will be based on the oldest invoice and a 3 year buffer
     * @return array year
     */
    public function getYearsRange() {

        if (Invoice::count() > 0) {
            $oldest_payment_date = Invoice::oldest('bill_date')->value('bill_date');
        }

        // check if there are any invoices in the database
        if (Invoice::count() > 0) {

            // get the oldest invoice date from the invoices table
            $oldest_payment_date = Invoice::oldest('bill_date')->value('bill_date');
            $oldest_expense_date = Invoice::oldest('bill_date')->value('bill_date');

            // Determine the current year
            $current_year = now()->year;

            // add 2 years from the oldest invoice year to create a buffer
            $oldest_year = date('Y', strtotime($oldest_invoice_date));
            $buffered_year = $oldest_year - 3;

            // get the range of years
            $years = range($buffered_year, $current_year);

            // Reverse the array
            $years = array_reverse($years);

        } else {

            // if there are no invoices, set default values
            $current_year = now()->year;
            $years = range($current_year, $current_year);
        }

        return $years;
    }

}