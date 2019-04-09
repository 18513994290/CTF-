<?hh // strict

require_once ($_SERVER['DOCUMENT_ROOT'].'/../vendor/autoload.php');

class BatchDataController extends DataController {
  public async function genGenerateData(): Awaitable<void> {

    /* HH_IGNORE_ERROR[1002] */
    SessionUtils::sessionStart();
    SessionUtils::enforceLogin();

    $data = array();

    $all_batchs = await Batch::genAllBatchs();
    foreach ($all_batchs as $batch){
      $number =
          (object) array('batch_number' => $batch->getBatchNumber(), 'start_ts' => $batch->getStartTs(),'end_ts'=>getEndTs());
      array_push($data, $number);
    }

    $this->jsonSend($data);
  }
}

/* HH_IGNORE_ERROR[1002] */

$batchsData = new BatchDataController();
$batchsData->sendData();
