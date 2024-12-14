<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $departments = Department::all();
        $monthlyTickets = $this->getLast12MonthsData();

        $departmentTickets = Department::withCount('tickets')->get();

        $departmentName = $departmentTickets->map(function ($value, $key) {
            return $value->title;
        });
        $departmentTickets = $departmentTickets->map(function ($value, $key) {
            return $value->tickets_count;
        });

        $departmentStats['name'] = array_values($departmentName->toArray());
        $departmentStats['tickets'] = array_values($departmentTickets->toArray());

        $ticketsStatusCountData = Ticket::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        $ticketStatus = $ticketsStatusCountData->map(function ($value, $key) {
            return $value->status;
        });
        $ticketStatusCount = $ticketsStatusCountData->map(function ($value, $key) {
            return $value->count;
        });

        $ticketsStats['status'] = array_values($ticketStatus->toArray());
        $ticketsStats['count'] = array_values($ticketStatusCount->toArray());

        return view('dashboard.index', compact('departments', 'monthlyTickets', 'departmentTickets', 'departmentStats', 'ticketsStats'));
    }

    public function getLast12MonthsData()
    {
        // Get the current date
        $endDate = Carbon::now();

        // Generate the list of the last 12 months
        $data = [];
        for ($i = 0; $i < 12; $i++) {
            $startOfMonth = $endDate->copy()->subMonths($i)->startOfMonth();
            $endOfMonth = $endDate->copy()->subMonths($i)->endOfMonth();

            // Get the month name
            $monthName = $startOfMonth->format('F');

            // Get the data for the month
            $count = Ticket::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            $data [$monthName] = $count;
        }

        $allMonths = [
            'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
        ];

        $sortedData = [];
        foreach ($allMonths as $month) {
            $sortedData[$month] = $data[$month] ?? 0;
        }

        return $sortedData;
    }
}
