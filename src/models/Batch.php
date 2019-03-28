<?hh // strict
class Batch extends Modal{
    protected static string $MC_KEY = 'batch:';
    protected static Map<string, string>
    $MC_KEYS = Map {
    'ALL_BATCHS' => 'all_batchs',
    'ALL_BATCHS_BY_ID' => 'all_batch_by_id',
    'ALL_BATCHS_FOR_MAP' => 'all_batchs_for_map',
    'ALL_ENABLED_BATCHS' => 'all_enabled_batchs',
    'ALL_ENABLED_BATCHS_FOR_MAP' => 'all_enabled_batchs_for_map',
    'BATCHEXISTSE'=>'Batch_Exists',
    };
    private function __construct(
        private int $id,
        private string $batch_number,
        private string  $start_ts,
        private string  $end_ts,
        private int  $enabled,
        private string $create_ts,

      ) {}

  public function getId(): int {
    return $this->id;
    }

  public function getEnabled(): bool {
    return $this->enabled === 1;
    }

      public function getBatchnumber(): string {
        return $this->batch_number;
    }
      public function getStartTs(): string {
        return $this->start_ts;
    }
      public function getEndTs(): string {
        return $this->end_ts;
    }
      public function getCreatTs(): string {
        return $this->create_ts;
    }
     //get all batch

    //form
    private static function BatchFromRow(
        Map<string, string> $row,
        ):Batch{
        return new Batch(
            intval(must_have_idx($row, 'id')),
            must_have_idx($row, 'batch_number'),
            must_have_idx($row, 'start_ts'),
            must_have_idx($row, 'end_ts'),
            must_have_idx($row, 'enabled'),
            must_have_idx($row, 'create_ts'),
        );
    }

  // All batchs.
  public static async function genAllBatchs(
    bool $refresh = false,
  ): Awaitable<array<Batch>> {
    $mc_result = self::getMCRecords('ALL_BATCHS');
    if (!$mc_result || count($mc_result) === 0 || $refresh) {
      $db = await self::genDb();
      $all_batchs = Map {};
      $result = await $db->queryf('SELECT * FROM batchs ORDER BY id');
      foreach ($result->mapRows() as $row) {
          $all_batchs->add(
              Pair {intval($row->get('id')), self::BatchFromRow($row)},
        );
      }
      self::setMCRecords('ALL_BATCHS', new Map($all_batchs));
      $batchs = array();
      $batchs = $all_batchs->toValuesArray();
      return $batchs;
    } else {
        $batchs = array();
        invariant(
            $mc_result instanceof Map,
            'cache return should be of type Map',
      );
        $batchs = $mc_result->toValuesArray();
        return $batchs;
        }
    }
   //create batchs
    public static async function genCreate(
         string $batch_number,
         string $start_ts,
         string $end_ts,
        ): Awaitable<void> {
        $db = await self::genDb();
         await $db->queryf('INSERT INTO batchs (create_ts,start_ts,end_ts,batch_number) VALUES (NOW(),%s,%s,%s)',
                $start_ts,
                $end_ts,
                $batch_number,
        );
    self::invalidateMCRecords(); // Invalidate Memcached ActivityLog data.
  }
 
 //test
  public static async function test(){
     /**
       $db = await self::genDb();
      $all_batchs=await $db->queryf('SELECT * FROM batchs ORDER BY id DESC LIMIT 1'); 
      $batchs = $all_batchs->toValuesArray();
      return $batchs; 
   */
   return false;
  }


}
