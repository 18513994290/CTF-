<?hh // strict
class Batch extends Modal{
    private function __construct(
        private int $id,
        private string $batch_number,
        private string  $start_ts,
        private string  $end_ts,
        private int  $enabled,
        private int  $status,
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
    public function getStatus(): string {
        return $this->status;    
   }
    public function getTs(): string {
        return $this->create_ts;
    }
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
  public static async function genAllBatchs(){
   return true;
  }
   //create batchs
   public static async function genBatchCreate(
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



}
