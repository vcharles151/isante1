<?
/* last date modified :  sept 29th */
set_time_limit (0); 
ini_set ('memory_limit','1024M');
require_once ("pregnancyRanges.php");
require_once ("malaria.php");
require_once ("tb.php");
require_once ("nutrition.php"); 
require_once ("hivstatus.php");
require_once ("obgyn.php");
require_once ("dataquality.php");

$tempTableNames = array ();

function updateDataWarehouse ($key, $truncate, $lastModified) {
  if (DEBUG_FLAG) echo "<br>Starting update of warehouse key '$key': " . date('h:i:s') . "<br>";
  if ($truncate) {
    truncateTable ('dw_' . $key . '_snapshot');
    $lastModified = "2003-01-01";
  }
  if ($key == "nutrition") {
    truncateTable ("dw_pregnancy_ranges");
    updatePregnancyRanges();

    $GLOBALS['tempTableNames'] = createTempTables ("tempNutrition", 5, array ("patientID varchar(11), dob date", "patientID varchar(11), visitDate date, dob date, ageInMos smallint unsigned, vitalWeight decimal(5,2), pedVitBirWt decimal(5,2), vitalHeight decimal(4,2), pedVitBirLen decimal(4,2), pedVitCurBracCirc decimal(5,2)", "patientID varchar(11), visitDate date", "patientID varchar(11), visitDate date, prevHtDate date", "patientID varchar(11), visitDate date, prevHt decimal(4,2)"), "pat_idx::patientID");
  }
 if ($key == "dataquality") {
    $GLOBALS['tempTableNames'] = createTempTables ("tempQuality", 1, array ("patientID varchar(11),maxDate date,concept_id varchar(7)"), "pat_idx::patientID");
  }
 
  call_user_func ("update" . ucfirst ($key) . "Snapshot", $lastModified);
  refreshSlices ($key);
  dropTempTables ($GLOBALS['tempTableNames']);
}

function truncateTable ($name) {
  database()->exec ('TRUNCATE TABLE ' . $name);
}

function refreshSlices($key) {
	$orgType = array(
		"Haiti" => "1", 
		"Department" => "department",
		"Commune" => "concat(department,?,commune)",
		"Network" => "network",
		"Sitecode" => "sitecode"
	); 
	$time_period = array(
		"Year",
		"Month",
		"Week"
	);
	
	// re-compute slices daily
	truncateTable ("dw_" . $key . "_slices");
	truncateTable ("dw_" . $key . "_patients"); 

	switch ($key) {
		case "nutrition":
		nutritionSlices($key, $orgType, $time_period);
		break;
		case "malaria":
		malariaSlices($key, $orgType, $time_period);
		break;
		case "tb":
		tbSlices($key, $orgType, $time_period);
		case "hivstatus":
		hivstatusSlices($key, $orgType, $time_period);
		break;
		case "obgyn":
		obgynSlices($key, $orgType, $time_period);
		break;
		case "dataquality":
		dataqualitySlices($key, $orgType, $time_period);
		break;
		
	}
}

function generatePidLists ($key, $indicator, $query, $period) {
	if (count($query) == 1) { 
		$inda = $query[0];
		$operator = "x";       
	} else {
		$inda = $query[0];
		$indb = $query[1];
		$operator = $query[2];
	} 
	$sql = "insert into dw_" . $key . "_patients 
			select " . $indicator . ", time_period, year, period, patientid 
			from dw_" . $key . "_patients 
			where time_period = ? ";
	$argArray = array($period);
	switch ($operator) {
		case 'x': 
		$sql .= " and indicator = " . $inda;
		break;
	case 'join':  
		$sql .= " and indicator = " . $inda . " and patientid in (
			select distinct patientid from dw_" . $key . "_patients where indicator = " . $indb . " and time_period = ?)";
		$argArray[] = $period; 
		break;
	case 'not': 
		$sql .= " and indicator = " . $inda . " and patientid not in (
			select distinct patientid from dw_" . $key . "_patients where indicator = " . $indb . " and time_period = ?)";
		$argArray[] = $period; 
		break;
	case 'union':
		$sql .= " and indicator in (" . $inda . "," . $indb . ")";
		break;
	}
	$rc = database()->query($sql, $argArray)->rowCount();
	if (DEBUG_FLAG) {
		echo "<br>Generate Pid List for: " . $indicator . " :" . $sql . " Rows inserted: " . $rc . "<br>";
		print_r ($argArray); 
	}
} 

function generatePercents ($key, $indicator, $org_unit, $org_value, $query) {
	if ($key == 'tb') $inda = $query[1][0];
	else $inda = $indicator;
	$indb = $query[2][0]; 
	if (DEBUG_FLAG) echo "Indicator: " . $indicator . " IndicatorA: " . $inda . " IndicatorB: " . $indb . "<br>";
	$sql = "insert into dw_" . $key . "_slices 
		select ?, a.ov, " . $indicator . ", a.time_period, a.year, a.period, b.sex, a.value, b.denominator from 
		(select " . $org_value . " as ov, time_period, year, period, t.sex, count(distinct p.patientid) as value from dw_" . $key . "_patients p, clinicLookup, patient t where p.patientid = t.patientid and t.sex in (1,2) and sitecode = left(p.patientid,5) and indicator = ? group by 1,2,3,4,5) a right join
		(select " . $org_value . " as ov, time_period, year, period, t.sex, count(distinct p.patientid) as denominator from dw_" . $key . "_patients p, clinicLookup, patient t where p.patientid = t.patientid and t.sex in (1,2) and sitecode = left(p.patientid,5) and indicator = ? group by 1,2,3,4,5) b
		on a.ov = b.ov and a.time_period = b.time_period and a.year = b.year and a.period = b.period and a.sex = b.sex group by 1,2,3,4,5,6,7";
	if ($org_unit == 'Commune') $qArray = array($org_unit, "-", $inda, "-", $indb);
	else $qArray = array($org_unit, $inda, $indb); 
	$rc = database()->query($sql, $qArray)->rowCount();
	if (DEBUG_FLAG) {
		echo "<br>Generate Percents for: " . $indicator . " :" . $sql . " Rows inserted: " . $rc . "<br>";
		print_r($qArray); 
	}
}  

function generateAmongSlices ($key, $indicator, $org_unit, $org_value, $query) {
	if ($query[2] == NULL) { 
		$sql = "insert into dw_" . $key . "_slices 
			select ?, " . $org_value . ", " . $indicator . ", time_period, year, period, t.sex, count(distinct p.patientid), 0 
			from dw_" . $key . "_patients p, clinicLookup c, patient t 
			where p.patientid = t.patientid and t.sex in (1,2) and indicator = ? and c.sitecode = left(p.patientid,5) group by 1,2,3,4,5,6,7";
		if ($org_unit == 'Commune') $argArray = array($org_unit, "-", $indicator);
		else $argArray = array($org_unit, $indicator);
	} else {  
		$sql = "insert into dw_" . $key . "_slices 
			select ?, " . $org_value . ", " . $indicator . ", p.time_period, p.year, p.period, t.sex, count(distinct p.patientid), 0 
			from dw_" . $key . "_patients p, dw_" . $key . "_patients q, clinicLookup c, patient t 
			where p.patientid = t.patientid and t.sex in (1,2) and p.indicator = ? and q.indicator = ? and 
			p.time_period = q.time_period and p.year = q.year and 
			p.period = q.period and c.sitecode = left(p.patientid,5) and 
			p.patientid = q.patientid group by 1,2,3,4,5,6,7";
		if ($org_unit == 'Commune') $argArray = array($org_unit, "-", $indicator, $query[2][0]);
		else $argArray = array($org_unit, $indicator, $query[2][0]); 
	} 
	$rc = database()->query($sql, $argArray)->rowCount();
       	if (DEBUG_FLAG) {
		echo "<br>Generate among for: " . $indicator . " :" . $sql . " Rows inserted: " . $rc . "<br>"; 
		print_r ($argArray);
	}  
}

?>
