<?php
namespace Apo100l\Quest;
require_once('./src/classes/QuestAbstract.php');

class Quest extends QuestAbstract
{
	public function requestStatistic($db,$argv)
    {
    	if($argv[1]=='statistic')
		{
			echo 'Please enter start date: ';
			$start=substr(`read answer1;echo \$answer1`, 0, -1);
			echo 'Please enter end date: ';
			$end=substr(`read answer2;echo \$answer2`, 0, -1);
			echo "+-------+---------+\n| count | amount  |\n+-------+---------+\n";
			foreach($argv as $key=>$value)
			{
				if($argv[$key]=='--without-documents')
				{
					$this->amountWithoutDocuments($db,$start,$end);
				}
				if($argv[$key]=='--with-documents')
				{
					$this->amountWithDocuments($db,$start,$end);
				}	
			}
		}
		echo "+-------+---------+\n$$\n";
		$this->helpUser($argv);
    }

    private function amountWithDocuments($db,$start,$end)
	{
		$sql="SELECT COUNT(pay.id) as count, SUM(pay.amount) as amount FROM payments as pay LEFT JOIN documents as doc ON pay.id=entity_id WHERE pay.finish_time BETWEEN '$start' AND '${end}' and doc.id is NULL";
		$stmt = $db->query($sql) or die('ERROR in query');
		while ($row = $stmt->fetch())
		{
			echo '|'.$row['count'].'    | '.$row['amount']."   |\n";
		}
	}

	private function amountWithoutDocuments($db,$start,$end)
	{
		$sql="SELECT COUNT(pay.id) as count, SUM(pay.amount) as amount FROM payments as pay JOIN documents as doc ON pay.id=entity_id WHERE doc.create_ts BETWEEN '$start' AND '${end}' and doc.id is not NULL";
		$stmt = $db->query($sql) or die('ERROR in query');
		while ($row = $stmt->fetch())
		{
			echo '|'.$row['count'].'    | '.$row['amount']."    |\n";
		}
	}

	private function helpUser($argv)
	{
		switch($argv[1])
		{
			case 'help': echo "Use argument 'statistic' to statistic inforamtion\n";break;
			case '-help': echo "Use argument 'statistic' to statistic inforamtion\n";break;
			case '--help': echo "Use argument 'statistic' to statistic inforamtion\n";break;
			case 'h': echo "Use argument 'statistic' to statistic inforamtion\n";break;
			case '-h': echo "Use argument 'statistic' to statistic inforamtion\n";break;
		}
	}
}

$quest = new Quest();
$db=$quest->getDb();
$quest->requestStatistic($db,$argv);
