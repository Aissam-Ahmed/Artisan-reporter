<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;


class GenerateReportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:generate {table} {--from=} {--to=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a CSV report from a database table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get the table name and optional date range
        $table = $this->argument('table');
        $from = $this->option('from');
        $to = $this->option('to');

        // Check if the table exists
        if (!Schema::hasTable($table)) {
            $this->error("الجدول '$table' غير موجود.");
            return 1;
        }

        // Check if the table has a created_at column for date filtering
        if (!Schema::hasColumn($table, 'created_at')) {
            $this->error("لا يمكن تطبيق الفلترة بالتاريخ لأن الجدول لا يحتوي على عمود 'created_at'.");
            return 1;
        }

        // Build the query with optional date filters
        $query = DB::table($table);
        if ($from) {
            $query->where('created_at', '>=', $from);
        }
        if ($to) {
            $query->where('created_at', '<=', $to);
        }

        // Fetch the data
        $data = $query->get();

        if ($data->isEmpty()) {
            $this->warn("No data found within the selected date range.");
            return 0;
        }

        // Make sure the 'public/reports' directory exists
        $reportsPath = public_path('reports');
        if (!File::exists($reportsPath)) {
            File::makeDirectory($reportsPath, 0755, true);
        }

        // Create the CSV file
        $csv = fopen(public_path("reports/{$table}_report.csv"), 'w');

        // Write the CSV headers (column names)
        fputcsv($csv, array_keys((array) $data[0]));

        // Write each row to the CSV
        foreach ($data as $row) {
            fputcsv($csv, (array) $row);
        }

        // Close the CSV file
        fclose($csv);


        // Output success message and file path
        $this->newLine();
        $this->info("▬▬ The report has been created !");
        $this->info("▬▬ Local Path: public/reports");
        // Display the filtered data in a table format in the console
        $this->info("▬▬ Data in the report without password:");

        // Remove 'password' column if they exist in the result
        $data = $data->map(function ($row) {
            // Convert the stdClass object to an array and remove the unwanted columns
            $rowArray = (array) $row;
            unset($rowArray['password']);
            return $rowArray;
        });
        $this->table(
            array_keys((array) $data[0]),  // Column headers
            $data->toArray()               // Data rows
        );

        // Show the full public URL for downloading the file
        $this->info("▬▬ Download URL : ". url('/') ."/reports/{$table}_report.csv");

        return 0;
    }
}
