<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class MaintenanceController extends Controller
{
    public const COMMANDS = [
        'financershub:preflight' => 'Check deployment configuration',
        'schedule:list' => 'List scheduled tasks',
        'about' => 'Application information',
        'env' => 'Current environment',
        'list' => 'List Artisan commands',
        'route:list' => 'List registered routes',
        'event:list' => 'List registered events',
        'event:cache' => 'Cache event discovery',
        'event:clear' => 'Clear event discovery cache',
        'config:cache' => 'Cache application configuration',
        'clear-compiled' => 'Remove compiled class files',
        'migrate:status' => 'Check migration status',
        'migrate' => 'Apply pending database migrations',
        'queue:restart' => 'Ask queue workers to restart',
        'schedule:interrupt' => 'Interrupt the current scheduler run',
        'auth:clear-resets' => 'Remove expired password reset tokens',
        'view:clear' => 'Clear compiled views',
        'view:cache' => 'Compile views',
        'route:clear' => 'Clear route cache',
        'route:cache' => 'Build route cache',
        'config:clear' => 'Clear configuration cache',
    ];

    public function index(Request $request)
    {
        $this->authorize('manage-settings');

        $query = DB::table('maintenance_runs')->leftJoin('users', 'users.id', '=', 'maintenance_runs.user_id')->select('maintenance_runs.*', 'users.name as actor_name');
        if (array_key_exists((string) $request->query('command'), self::COMMANDS)) {
            $query->where('command', $request->query('command'));
        }
        if ($request->query('outcome') === 'success') {
            $query->where('exit_code', 0);
        }
        if ($request->query('outcome') === 'attention') {
            $query->where(fn ($q) => $q->where('exit_code', '!=', 0)->orWhereNull('exit_code'));
        }

        $catalog = collect(Artisan::all())->map(fn ($command, $name) => ['name' => $name, 'description' => $command->getDescription(), 'runnable' => array_key_exists($name, self::COMMANDS)])->sortKeys()->all();
        $descriptions = collect(self::COMMANDS)->map(fn ($label, $name) => $catalog[$name]['description'] ?? $label)->all();
        $descriptions['migrate'] = 'Apply pending migrations in production too. Back up the database first; migrations may change stored data.';

        return view('cms.maintenance', ['catalog' => $catalog, 'descriptions' => $descriptions, 'commands' => self::COMMANDS, 'runs' => $query->latest('maintenance_runs.id')->paginate(10)->withQueryString(),
            'totalRuns' => DB::table('maintenance_runs')->count(), 'attentionRuns' => DB::table('maintenance_runs')->where(fn ($q) => $q->where('exit_code', '!=', 0)->orWhereNull('exit_code'))->count(),
            'selected' => array_key_exists((string) old('command'), self::COMMANDS) ? old('command') : 'financershub:preflight']);
    }

    public function run(Request $request)
    {
        $this->authorize('manage-settings');
        $data = $request->validate(['command' => ['required', Rule::in(array_keys(self::COMMANDS))], 'password' => 'required|string', 'confirm_migration' => 'accepted_if:command,migrate']);
        if (! Hash::check($data['password'], $request->user()->password)) {
            throw \Illuminate\Validation\ValidationException::withMessages(['password' => 'Your password was not accepted.']);
        }
        $lock = Cache::lock('admin-maintenance', 120);
        abort_unless($lock->get(), 409, 'A maintenance task is already running.');
        try {
            $id = DB::table('maintenance_runs')->insertGetId(['user_id' => $request->user()->id, 'command' => $data['command'], 'created_at' => now(), 'updated_at' => now()]);
            try {
                $code = $data['command'] === 'migrate' ? Artisan::call('migrate', ['--force' => true, '--no-interaction' => true]) : Artisan::call($data['command']);
                $output = mb_substr(Artisan::output(), 0, 12000);
            } catch (\Throwable $error) {
                $code = 1;
                $output = 'Command failed. Review restricted application logs on the server.';
                report($error);
            }
            DB::table('maintenance_runs')->where('id', $id)->update(['exit_code' => $code, 'output' => $output, 'updated_at' => now()]);
        } finally {
            $lock->release();
        }

        return redirect()->route('admin.maintenance')->with('maintenance_run_id', $id)->with('status', $code === 0 ? 'Task completed successfully.' : 'Task finished with findings. Review its output below.');
    }
}
