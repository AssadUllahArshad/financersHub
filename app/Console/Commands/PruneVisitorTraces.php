<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
class PruneVisitorTraces extends Command {
    protected $signature='analytics:prune';
    protected $description='Delete visitor traces older than 90 days';
    public function handle(): int {
        $count=DB::table('visitor_traces')->where('visited_at','<',now()->subDays(90))->delete();
        $this->info("Deleted {$count} expired visitor traces."); return self::SUCCESS;
    }
}
