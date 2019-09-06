<?php

namespace App\Console\Commands;

use http\Env\Request;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LogAnysis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create logs & write into database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::table('loganyses')->truncate();
        $logcontents = explode("\n",Storage::disk ('public')->get('logs/log.txt'));
        $mlogcontents = explode("\n",Storage::disk ('public')->get('logs/mlog.txt'));
        foreach ($logcontents as $logcontent)
        {
            \App\AdminModel\Loganysis::create(['infos'=>$logcontent]);
        }
        foreach ($mlogcontents as $mlogcontent)
        {
            \App\AdminModel\Loganysis::create(['infos'=>$mlogcontent,'mid'=>1]);
        }
    }
}
