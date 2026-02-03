<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'users',
            'clients',
            'besdexs',
            'myleads',
            'proposals',
            'attendancerecords',
            'mylead_histories',
            'contacts',
            'help_and_supports',
            'tasks',
            'task_user',
            'links',
            'reports',
            'conversations',
            'messages',
            'notes',
            'salaries',
            'salary_details',
            'salary_configs',
            'holidays',
            'leaves',
            'employee_portals',
            'project_managements',
            'my_attendances',
            'invoices',
            'invoice_items',
            'todos',
            'roles',
            'my_attendance_breaks',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                if (!Schema::hasColumn($table->getTable(), 'company_id')) {
                    $table->foreignId('company_id')
                          ->after('id')
                          ->constrained()
                          ->cascadeOnDelete();
                }
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'users',
            'clients',
            'besdexs',
            'myleads',
            'proposals',
            'attendancerecords',
            'mylead_histories',
            'contacts',
            'help_and_supports',
            'tasks',
            'task_user',
            'links',
            'reports',
            'conversations',
            'messages',
            'notes',
            'salaries',
            'salary_details',
            'salary_configs',
            'holidays',
            'leaves',
            'employee_portals',
            'project_managements',
            'my_attendances',
            'invoices',
            'invoice_items',
            'todos',
            'my_attendance_breaks',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                if (Schema::hasColumn($table->getTable(), 'company_id')) {
                    $table->dropForeign(['company_id']);
                    $table->dropColumn('company_id');
                }
            });
        }
    }
};
