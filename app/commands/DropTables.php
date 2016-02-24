    <?php
     
    use Illuminate\Console\Command;
    use Symfony\Component\Console\Input\InputOption;
    use Symfony\Component\Console\Input\InputArgument;
     
    class DropTables extends Command {
     
    /**
    * The console command name.
    *
    * @var string
    */
    protected $name = 'migrate:droptables';
     
    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Clears all the tables.';
     
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
    public function fire()
    {
    $tables = [];
     
    DB::statement( 'SET FOREIGN_KEY_CHECKS=0' );
     
    foreach (DB::select('SHOW TABLES') as $k => $v) {
    $tables[] = array_values((array)$v)[0];
    }
     
    foreach($tables as $table) {
    Schema::drop($table);
    echo "Table ".$table." has been dropped.".PHP_EOL;
    }
     
    }
     
    }
