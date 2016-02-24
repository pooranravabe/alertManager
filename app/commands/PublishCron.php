<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Event\CompleteEvent;

class PublishCron extends Command {

    public $rc = null;
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:PublishCron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish posts';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire() {
        // $contents = Content::where('status', '=', 'SCHEDULED')->where('schedule_date','<=',date('Y-m-d H:i:s'))->select('id', 'schedule_date')->get();
        $contents = Content::where(function($query){
            $query->where('status', 'SCHEDULED')
            ->where('schedule_date','<=',date('Y-m-d H:i:s'));
          })->orWhere(function($query){
            $query->where('status', 'PUBLISHED')
            ->where('schedule_date','<=',date('Y-m-d H:i:s'));
          })->select('id', 'schedule_date')->get();
          
        $this->info('Initiating RaVaBe Cron..');
        Log::info("Initiating RaVaBe Cronjob...");
        $this->rc = new RollingCurl(array($this, "request_callback"));
        if ($contents->count() > 0) {
            foreach ($contents as $content) {
                $approvals = Approvals::where('content_id', $content->id)->whereIn('approvel_status', ['0','2'])->get();
                if($approvals->count() >= 1){
                    continue;
                }
                $cmd = 'php '.__DIR__.'/../../artisan command:PublishRequest --content_id='.$content->id;
                exec('nohup '.$cmd.' > /dev/null 2>&1 &');
            }
        } else {
            $this->info('No Records Found');
            Log::info('No Records Found');
        }
    }




    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments() {
//		return array(
//			array('example', InputArgument::REQUIRED, 'An example argument.'),
//		);
        return [];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions() {
//		return array(
//			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
//		);
        return [];
    }


}
