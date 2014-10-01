<? 
function updateMalariaSnapshot($lastModified) {
	/* Put obs table records into the snapshot 
	 * Latest results (02/11/2014) for rapid result malaria testing in the primary care forms:
	select value_numeric, count(*) from obs o, concept c where o.concept_id = c.concept_id and short_name = 'malariaResultRapid' group by 1;
	+---------------+----------+
	| value_numeric | count(*) |
	+---------------+----------+
	|             1 |       21 |  positive
	|             2 |        1 |  negative
	+---------------+----------+
	*/
	$sql = "select e.visitdate, patientID, short_name, o.value_numeric 
	from obs o, dw_encounter_snapshot e, concept c 
	where c.short_name in (
	'malariaDxA', 'malariaDx', 'malariaDxG', 'malariaDxSuspectedA', 'malariaDxSuspected', 'malariaDxSuspectedG', 
	'sym_malariaLT', 'sym_malariaGT', 'feverLess2', 'feverGreat2', 'convulsion', 'lethargy', 'hematuria', 'ictere', 
	'anemia', 'anemiaA', 'anemiaG', 'malariaTest', 'malariaTestRapid', 'malariaResultRapid', 'hospitalisation' ) and
	c.concept_id = o.concept_id and 
	o.encounter_id = e.encounter_id and 
	concat(o.location_id,o.person_id) = e.patientid and
	o.value_boolean is true and
	e.lastModified >= ?";
	if (DEBUG_FLAG) echo "<br>Obs Query: " . $sql . "<br>";
	$arr = databaseSelect()->query($sql,array($lastModified))->fetchAll(PDO::FETCH_ASSOC);
	$j = 0;
	$k = 0;
	foreach ($arr as $i => $row) {
		if ($row['patientID'] == "" || $row['visitdate'] == "") continue;
		if ($row['short_name'] == 'malariaResultRapid') {
			if ($row['value_numeric'] == 1) $row['short_name'] = 'rapidResultNegative';
			else if ($row['value_numeric'] == 2) $row['short_name'] = 'rapidResultPositive';
			else continue;  
		}
		$qry = 'insert into dw_malaria_snapshot (patientid, visitdate, ' . $row['short_name'] . ') values (?,?,1) on duplicate key update ' . $row['short_name'] . ' = 1';
		$rc = database()->query($qry, array($row['patientID'],$row['visitdate']))->rowCount();
		$j++;
		if ($rc > 0) $k++;
	} 
        if (DEBUG_FLAG) {
		echo "<br>Obs elements: " . $k . " updated of " . $j . " found<br>";
		echo "<br>" . date('h:i:s') . "<br>";
	}
	/* Put prescription records into the snapshot
	 * TODO: open vs. dispensed??? vs. blank (non-encountertype) 
	*/ 
	$sql = "select e.visitdate, p.patientID, 
	case when drugid = 60 then 'chloroquine' 
	when drugid = 62 then 'quinine' 
	when drugid = 85 then 'primaquine' end as malariaDrug 
	from a_prescriptions p, dw_encounter_snapshot e 
	where e.encounter_id = p.encounter_id and 
	e.dbSite = p.dbSite and 
	drugid in (60,62,85) and
	e.lastModified >= ?"; 
	if (DEBUG_FLAG) echo "<br>Prescriptions Query: " . $sql . "<br>";
	$arr = databaseSelect()->query($sql,array($lastModified))->fetchAll(PDO::FETCH_ASSOC);
	$j = 0;
	$k = 0;
	foreach ($arr as $i => $row) {
		if ($row['patientID'] == "" || $row['visitdate'] == "") continue;
		$qry = 'insert into dw_malaria_snapshot (patientid, visitdate, ' . $row['malariaDrug'] . ') values (?,?,1) on duplicate key update ' . $row['malariaDrug'] . ' = 1';
		$rc = database()->query($qry, array($row['patientID'],$row['visitdate']))->rowCount();  
	       	$j++;
		if ($rc > 0) $k++;
	}
	if (DEBUG_FLAG) {
		echo "<br>Prescription elements: " . $k . " updated of " . $j . " found<br>"; 
		echo "<br>" . date('h:i:s') . "<br>"; 
	}  
	/* Put condition records into the snapshot (HIV form malaria diagnoses)
	 * TODO: adult vs. pediatric conditionids 
	 */
	$j = 0;
	$k = 0; 
	$sql = "select case when isdate(ymdtodate(conditionyy,conditionmm,'01')) = 1 then ymdtodate(conditionyy,conditionmm,'01') else ymdtodate(c.visitdateyy,c.visitdatemm,c.visitdatedd) end as visitdate, c.patientID, 
	case when (conditionActive IN (1,4,5) AND encounterType IN (16,17)) OR (conditionActive IN (1,4,5) AND encounterType IN (1,2)) OR (conditionActive = 1 AND encounterType IN (24,25)) and conditionid in (45,335,717) then 'malariaDx' 
	when (conditionActive in (2,6) AND encounterType IN (16,17)) OR (conditionActive not IN (1,4,5) AND encounterType IN (1,2)) OR (conditionActive <> 1 AND encounterType IN (24,25)) and conditionid in (45,335,717) then 'malariaDxG' 
	when (conditionActive IN (1,4,5) AND encounterType IN (16,17)) OR (conditionActive IN (1,4,5) AND encounterType IN (1,2)) OR (conditionActive = 1 AND encounterType IN (24,25)) and conditionid = 716 then 'malariaDxSuspected'
	when (conditionActive in (2,6) AND encounterType IN (16,17)) OR (conditionActive not IN (1,4,5) AND encounterType IN (1,2)) OR (conditionActive <> 1 AND encounterType IN (24,25)) and conditionid = 716 then 'malariaDxSuspectedG' 
	else '' end as 'dxColumn'
	from a_conditions c, dw_encounter_snapshot e 
	where e.encounter_id = c.encounter_id and 
	e.dbSite = c.dbSite and 
	c.conditionID in (45,335,716,717) and
	e.lastModified >= ?"; 
	if (DEBUG_FLAG) echo "<br>HIV Conditions Query: " . $sql . "<br>"; 
	$arr = databaseSelect()->query($sql,array($lastModified))->fetchAll(PDO::FETCH_ASSOC); 
	$j = 0;
	$k = 0;
	foreach ($arr as $i => $row) {
		if ($row['patientID'] == "" || $row['visitdate'] == "" || $row['dxColumn'] == "") continue;
		$qry = 'insert into dw_malaria_snapshot (patientid, visitdate, ' . $row['dxColumn'] . ') values (?,?,1) on duplicate key update ' . $row['dxColumn'] . ' = 1';
		$rc = database()->query($qry, array($row['patientID'],$row['visitdate']))->rowCount();  
		$j++;
		if ($rc > 0) $k++;
	}
	if (DEBUG_FLAG) {
		echo "<br>HIV Form Condition elements: " . $k . " updated of " . $j . " found<br>"; 
		echo "<br>" . date('h:i:s') . "<br>"; 
	} 
	/* Put lab orders/results into the snapshot
	 */
	$sql = "select distinct p.visitdate as visitdate, p.patientID
		from a_labs p, dw_encounter_snapshot e 
		where e.encounter_id = p.encounter_id and e.dbSite = p.dbSite 
		and labid in ( 132, 173, 1208, 1209, 1210, 1211, 1559, 1560, 1610, 1613) and e.lastModified >= ?";
	if (DEBUG_FLAG) echo "<br>Lab orders Query: " . $sql . "<br>";		
	$arr = databaseSelect()->query($sql,array($lastModified))->fetchAll(PDO::FETCH_ASSOC); 
	$j = 0;
	foreach ($arr as $i => $row) {
		if ($row['patientID'] == "" || $row['visitdate'] == "") continue;
		$qry = 'insert into dw_malaria_snapshot (patientid, visitdate, testsOrdered) values (?,?,1) on duplicate key update testsOrdered = 1';
		$rc = database()->query($qry, array($row['patientID'],$row['visitdate']))->rowCount();  
		$j++;
	} 
	if (DEBUG_FLAG) {
		echo "<br>Lab orders: " . $j . " found<br>"; 
		echo "<br>After lab orders: " . date('h:i:s') . "<br>";
	} 
	
	/* Put lab tests with results into the snapshot
	 * Latest counts (04/09/2014) of the various test results for malaria 
	 * +---------------------+----------+
	| testtype            | count(*) |
	+---------------------+----------+
	| rapidResultNegative |    12900 | 
	| rapidResultPositive |      226 | 
	| smearResultNegative |      177 | 
	| smearResultPositive |      277 | 
	+---------------------+----------+ 
	 */
	$sql = "select e.visitdate, p.patientID, p.labID as labid, p.testNameFr as testNameFr, p.result, p.result2, p.result3, 
	case when labid = 173 then
	        case when result = 1 then 'smearResultPositive' when result = 2 then 'smearResultNegative' else 'notMalariaTest' end
	when labid = 132 then
	        case when result = 1 then 'rapidResultPositive' when result = 2 then 'rapidResultNegative' else 'notMalariaTest' end 
	when labid in (1208,1209,1210,1211,1559) then
	        case when result not like '%neg%' or result like '%+%' then 'smearResultPositive' else 'smearResultNegative' end
	when labid in (1560,1610,1613) then
	        case when result not like '%neg%' or result like '%+%' then 'rapidResultPositive' else 'rapidResultNegative' end
	else
	       'notMalariaTest'
	end as 'testType'
	from a_labs p, dw_encounter_snapshot e 
	where e.encounter_id = p.encounter_id and e.dbSite = p.dbSite and p.result is not null and ltrim(rtrim(p.result)) <> '' and p.result not in ('0','8') and 
	p.labid in (132,173,1208,1209,1210,1211,1559,1560,1610,1613) and e.lastModified >= ?";
	if (DEBUG_FLAG) echo "<br>Lab Test Query: " . $sql . "<br>";		
	$arr = databaseSelect()->query($sql,array($lastModified))->fetchAll(PDO::FETCH_ASSOC); 
	$i = 0;
	$j = 0;
	$k = 0;
	$l = 0; 
	$binArray = array(1 => 'FT',2 => 'FG',4 => 'Vx', 8 => 'Ov', 16 => 'Mai'); 
	foreach ($arr as $i => $row) {
		if ($row['patientID'] == "" || $row['visitdate'] == "" || $row['testNameFr'] == "") continue;
		if ($row['testType'] != "notMalariaTest") {
			$qry = 'insert into dw_malaria_snapshot (patientid, visitdate, ' . $row['testType'] . ') values (?,?,1) on duplicate key update ' . $row['testType'] . ' = 1';
			$rc = database()->query($qry, array($row['patientID'],$row['visitdate']))->rowCount(); 
			switch ($row['testType']) {
				case 'rapidResultNegative': 
					$i++;
					break;
				case 'rapidResultPositive':
					$j++;
					break;
				case 'smearResultNegative':
					$k++;
					break;
				case 'smearResultPositive':
					// figure out which organisms might be checked 
					if ($row['labid'] == '173' && $row['result2'] > 0) 
						whichOrganisms($row['patientID'],$row['visitdate'],$row['result2'], $binArray);
					else 
						openElisOrganisms($row['patientID'],$row['visitdate'],$row['labid'], $row['result']);
					$l++;
					break; 
			} 
		}
	} 
	if (DEBUG_FLAG) {
		echo "<br>Rapid negative: " . $i . "<br>"; 
		echo "<br>Rapid positive: " . $j . "<br>";
		echo "<br>Smear negative: " . $k . "<br>";
		echo "<br>Smear positive: " . $l . "<br>";
		echo "<br>Total results : " . $i+$j+$k+$l . "<br>";
								
		echo "<br>After labs: " . date('h:i:s') . "<br>";
	}
	
	// pregnancy 
	$retVal = database()->query("update dw_malaria_snapshot a, dw_pregnancy_ranges p set isPregnant = 1 
	where a.patientid = p.patientid and a.visitdate between p.startdate and p.stopdate")->rowCount(); 
	if (DEBUG_FLAG) {
		echo "<br>Found " . $retVal . " rows where patients were pregnant";
	}
	 
	/* get rid of any snapshot records where patient either isn't suspected or confirmed and has not had any treatments or tests 
	$sql = "drop table if exists nonMalaria; create table nonMalaria select distinct patientid from dw_malaria_snapshot where malariaDx = 0 and malariaDxA = 0 and malariaDxG = 0 and malariaDxSuspected = 0 and malariaDxSuspectedA = 0 and malariaDxSuspectedG = 0 and rapidResultPositive = 0 and rapidResultNegative = 0 and smearResultPositive = 0 and smearResultNegative = 0 and malariaTest = 0 and malariaTestRapid = 0 and chloroquine = 0 and quinine = 0 and primaquine = 0; create index pnon on nonMalaria (patientid); delete from dw_malaria_snapshot where patientid in (select patientid from nonMalaria)";
	$rc = database()->query($sql)->rowCount();
	if (DEBUG_FLAG) {
		echo "<br>Eliminate stray patients query: " . $sql . " Rows removed: " . $rc . "<br>";
		echo "<br>" . date('h:i:s') . "<br>"; 
	}  */        
}

function whichOrganisms ($pat, $vd, $val, $binArray) {
	$binstring = strrev (decbin ($val)); 
        foreach ($binArray as $bin => $org) {
		$retVal = (!empty ($binstring{log ($bin, 2)}) && $binstring{log ($bin, 2)} == 1) ? 1 : 0;
		if ($retVal) { 
			echo "Val: " . $val . " Bin: " . $bin . " Org: " . $org . "\n";
			$qry = 'insert into dw_malaria_snapshot (patientid, visitdate, ' . $org . ') values (?,?,1) on duplicate key update ' . $org . ' = 1';
			$rc = database()->query($qry, array($pat,$vd))->rowCount();
		} 
	} 
}

function openElisOrganisms($pat, $vd, $labid, $result) {
	/*$binArray = array(1559 => 'FT', 1208 => 'FG', 1209 => 'Vx', 1210 => 'Ov', 1211 => 'Mai'); 
	echo "Pat: " . $pat . " Labid: " . $labid . " Org: " . $org . "\n";
	$qry = 'insert into dw_malaria_snapshot (patientid, visitdate, ' . $binArray[$labid] . ') values (?,?,1) on duplicate key update ' . $binArray[$labid] . ' = 1';
	$rc = database()->query($qry, array($pat,$vd))->rowCount(); */
} 

function malariaSlices($key, $orgType, $time_period) {
	/***  $indicatorQueries array
	 Arg zero is code for type of computation:
	     0 - simple, implies no item two
	     1 - percent or ratio, implies both a numerator (value) and a denominator in the record. Non-1 records have zero in the denominator
	     2 - "among" calculation, implies two or more separate queries combined together
	 Args 1 and 2 are either simple qualifications for calculations or pointers to previous calculations
	 The pointers are in the form of subarrays referencing 1 or 2 previously calculated indicators. 
	 A pointer array containing 2 indicators always includes an operator (union, join, not) in its third slot, indicating that it is a combination of the two indicator calculations
	***/ 
/*
truncate table dw_malariaReportLookup;
INSERT INTO `dw_malariaReportLookup` (`indicator`, `indicatorType`, `nameen`, `namefr`, `definitionen`, `definitionfr`, `subGroupEn`, `subGroupFr`, `indicatorDenominator`) VALUES
( 1, 1, 'Percentage of all patients with a fever of less than 2 weeks', 'Pourcentage de patients ayant eu la fièvre au cours des 2 dernières semaines (OMS)', 'Numerator: Number of fever cases <2 weeks Denominator: number of total clinic visits for the time period.', 'Numerator: Nombre de cas fébriles <2 semaines. Denominator: Nombre total de visites cliniques.', 'Total', 'Total', -1),
( 2, 1, 'Percentage of non-confirmed cases treated with either chloroquine, primaquine or quinine.', 'Pourcentage de patients avec fièvre (cas suspect) ayant reçu  un antipaludique (OMS)', 'Numerator: Number of patients who do not fit the definition of a confirmed case treated with either chloroquine, primaquine or quinine. Denominator: number of total clinic visits for the time period.', 'Numerator: Nombre de cas suspect ayant reçu un antipaludique. Denominator: Nombre total de visites cliniques.', 'Total', 'Total', -1),
( 3, 1, 'Pourcentage de patients  avec malaria confirmée ayant reçu un traitement conforme aux normes nationales du MSPP (OMS)', 'Pourcentage de patients  avec malaria confirmée ayant reçu un traitement conforme aux normes nationales du MSPP (OMS)', 'Number of confirmed cases treated with chloroquine and primaquine', 'Nombre de cas confirmés pour la malaria et mis sous traitement selon les normes- cas malaria simple', 'Total', 'Total', -3),
( 4, 1, 'Percentage of suspected cases tested.', 'Pourcentage de patients avec fièvre ayant été testés pour la malaria (OMS)','', '', 'Total', 'Total', -1),
( 5, 0, 'Confirmed cases', 'Nombre de cas de la malaria confirmés', 'A clinical diagnosis of confirmed malaria or a positive malaria test whether rapid test or microscopy.', 'Impression clinique de Malaria (paludisme) confirmée ou test positif de malaria qu’il s’agisse d’une microscopie ou d’un test rapide.', 'Total', 'Total', 0),
( 6, 0, 'Patients tested with microscopy', 'Nombre de patients ayant bénéficié un test microscopique pour la malaria', 'Number of patients tested with microscopy', 'Nombre de patients ayant bénéficié un test microscopique pour la malaria', 'Total', 'Total', 0),
( 7, 0, 'Positive microscopy results', 'Nombre de patients ayant un test microscopique positif pour la malaria', 'Number of positive microscopy results', 'Nombre de patients ayant un test microscopique positif pour la malaria', 'Total', 'Total', 0),
( 8, 0, 'Patients with a positive microscopy test with plasmodium falciparum.', 'Nombre de patients ayant un test microscopique positif à Plasmodium falciparum.', '', '', 'Total', 'Total', 0),
( 9, 0, 'Patients with a positive microscopy test with plasmodium falciparum and another parasite, either Vx,Ov,Mal.', 'Nombre de patients ayant un test microscopique positif mixte à Plasmodium (OMS)', '', '', 'Total', 'Total', 0),
(10, 0, 'Patients with a positive microscopy test other than falciparum ', 'Nombre de patients ayant un test microscopique positif à Plasmodium autres que falciparum', '', '', 'Total', 'Total', 0),
(11, 0, 'Patients tested with rapid test', 'Nombre de patients ayant bénéficié d’un TDR pour la malaria', 'Number of patients tested with rapid test', 'Nombre de patients ayant bénéficié d’un TDR pour la malaria', 'Total', 'Total', 0),
(12, 0, 'Positive RDT results', 'Nombre de patients ayant un TDR positif pour la malaria', 'Number of positive RDT results', 'Nombre de patients ayant un TDR positif pour la malaria', 'Total', 'Total', 0),
(13, 0, 'Febrile cases < 2 weeks with tests ordered for malaria (microscopy or RDT)', 'Nombre de cas suspects de la malaria (fièvre) dépistée (test demande)','Number of febrile cases < 2 weeks with tests ordered for malaria (microscopy or RDT)', 'Nombre de cas suspects (fièvre) de malaria dépistée (test demande)', 'Total', 'Total', 0),
(14, 0, 'Confirmed cases', 'Nombre de cas de la malaria confirmée', 'A clinical diagnosis of confirmed malaria or a positive malaria test whether rapid test or microscopy.', 'Impression clinique de Malaria (paludisme) confirmée ou test positif de malaria qu’il s’agisse d’une microscopie ou d’un test rapide.', 'Total', 'Total', 0),
(15, 0, 'Nombre de cas confirmés pour la malaria sévère et mis sous traitement selon les normes nationales du MSPP', 'Nombre de cas confirmés pour la malaria sévère et mis sous traitement selon les normes nationales du MSPP', '', '', 'Total', 'Total', 0),
(16, 0, 'Confirmed cases hospitalized', 'Nombre de patients avec Malaria hospitalisés', '', '', 'Total', 'Total', 0),

(17, 1, 'Percentage of all patients with a fever of less than 2 weeks', 'Pourcentage de patients ayant eu la fièvre au cours des 2 dernières semaines (OMS)', 'Numerator: Number of fever cases <2 weeks Denominator: number of total clinic visits for the time period.', 'Numerator: Nombre de cas fébriles <2 semaines. Denominator: Nombre total de visites cliniques.', 'Pregnant', 'Pregnant', -10),
(18, 1, 'Percentage of non-confirmed cases treated with either chloroquine, primaquine or quinine.', 'Pourcentage de patients avec fièvre (cas suspect) ayant reçu  un antipaludique (OMS)', 'Numerator: Number of patients who do not fit the definition of a confirmed case treated with either chloroquine, primaquine or quinine. Denominator: number of total clinic visits for the time period.', 'Numerator: Nombre de cas suspect ayant reçu un antipaludique. Denominator: Nombre total de visites cliniques.', 'Pregnant', 'Pregnant', -10),
(19, 1, 'Pourcentage de patients  avec malaria confirmée ayant reçu un traitement conforme aux normes nationales du MSPP (OMS)', 'Pourcentage de patients  avec malaria confirmée ayant reçu un traitement conforme aux normes nationales du MSPP (OMS)', 'Number of confirmed cases treated with chloroquine and primaquine', 'Nombre de cas confirmés pour la malaria et mis sous traitement selon les normes- cas malaria simple', 'Pregnant', 'Pregnant', -30),
(20, 1, 'Percentage of suspected cases tested.', 'Pourcentage de patients avec fièvre ayant été testés pour la malaria (OMS)','', '', 'Pregnant', 'Pregnant', -10),
(21, 0, 'Confirmed cases', 'Nombre de cas de la malaria confirmés', 'A clinical diagnosis of confirmed malaria or a positive malaria test whether rapid test or microscopy.', 'Impression clinique de Malaria (paludisme) confirmée ou test positif de malaria qu’il s’agisse d’une microscopie ou d’un test rapide.', 'Pregnant', 'Pregnant', 0),
(22, 0, 'Patients tested with microscopy', 'Nombre de patients ayant bénéficié un test microscopique pour la malaria', 'Number of patients tested with microscopy', 'Nombre de patients ayant bénéficié un test microscopique pour la malaria', 'Pregnant', 'Pregnant', 0),
(23, 0, 'Positive microscopy results', 'Nombre de patients ayant un test microscopique positif pour la malaria', 'Number of positive microscopy results', 'Nombre de patients ayant un test microscopique positif pour la malaria', 'Pregnant', 'Pregnant', 0),
(24, 0, 'Patients with a positive microscopy test with plasmodium falciparum.', 'Nombre de patients ayant un test microscopique positif à Plasmodium falciparum.', '', '', 'Pregnant', 'Pregnant', 0),
(25, 0, 'Patients with a positive microscopy test with plasmodium falciparum and another parasite, either Vx,Ov,Mal.', 'Nombre de patients ayant un test microscopique positif mixte à Plasmodium (OMS)', '', '', 'Pregnant', 'Pregnant', 0),
(26, 0, 'Patients with a positive microscopy test other than falciparum ', 'Nombre de patients ayant un test microscopique positif à Plasmodium autres que falciparum', '', '', 'Pregnant', 'Pregnant', 0),
(27, 0, 'Patients tested with rapid test', 'Nombre de patients ayant bénéficié d’un TDR pour la malaria', 'Number of patients tested with rapid test', 'Nombre de patients ayant bénéficié d’un TDR pour la malaria', 'Pregnant', 'Pregnant', 0),
(28, 0, 'Positive RDT results', 'Nombre de patients ayant un TDR positif pour la malaria', 'Number of positive RDT results', 'Nombre de patients ayant un TDR positif pour la malaria', 'Pregnant', 'Pregnant', 0),
(29, 0, 'Febrile cases < 2 weeks with tests ordered for malaria (microscopy or RDT)', 'Nombre de cas suspects de la malaria (fièvre) dépistée (test demande)','Number of febrile cases < 2 weeks with tests ordered for malaria (microscopy or RDT)', 'Nombre de cas suspects (fièvre) de malaria dépistée (test demande)', 'Pregnant', 'Pregnant', 0),
(30, 0, 'Confirmed cases', 'Nombre de cas de la malaria confirmée', 'A clinical diagnosis of confirmed malaria or a positive malaria test whether rapid test or microscopy.', 'Impression clinique de Malaria (paludisme) confirmée ou test positif de malaria qu’il s’agisse d’une microscopie ou d’un test rapide.', 'Pregnant', 'Pregnant', 0),
(31, 0, 'Nombre de cas confirmés pour la malaria sévère et mis sous traitement selon les normes nationales du MSPP', 'Nombre de cas confirmés pour la malaria sévère et mis sous traitement selon les normes nationales du MSPP', '', '', 'Pregnant', 'Pregnant', 0),
(32, 0, 'Confirmed cases hospitalized', 'Nombre de patients avec Malaria hospitalisés', '', '', 'Pregnant', 'Pregnant', 0); 
update dw_malariaReportLookup set nameen = concat('T: ', nameen), namefr = concat('T: ', namefr) where indicator between 1 and 16; 
update dw_malariaReportLookup set nameen = concat('P: ', nameen), namefr = concat('P: ', namefr) where indicator between 17 and 32;
 */

	$indicatorQueries = array( 
		-2 => array(0, "where chloroquine+primaquine+quinine = 3", NULL),
		-1 => array(0, "where 1=1", NULL), 
  		-3 => array(0, "where malariaDx+malariaDxA+rapidResultPositive+smearResultPositive > 0 and ((chloroquine = 1 and quinine+primaquine = 0) or (primaquine = 1 and quinine+chloroquine = 0) or (quinine = 1 and chloroquine+primaquine = 0))", NULL), 
		-20 => array(0, "where chloroquine+primaquine+quinine = 3 and isPregnant = 1", NULL),
		-10 => array(0, ", dw_pregnancy_ranges p where s.patientid = p.patientid and s.visitdate between p.startdate and p.stopdate", NULL), 
  		-30 => array(0, "where isPregnant = 1 and malariaDx+malariaDxA+rapidResultPositive+smearResultPositive > 0 and ((chloroquine = 1 and quinine+primaquine = 0) or (primaquine = 1 and quinine+chloroquine = 0) or (quinine = 1 and chloroquine+primaquine = 0))", NULL), 
		 1 => array(1, "where feverLess2 = 1", array(-1)),
		 2 => array(1, "where patientid in (select patientid from dw_malaria_snapshot where malariaDx+malariaDxA+rapidResultPositive+smearResultPositive = 0) and chloroquine+primaquine+quinine > 0", array(-1)), 
		 3 => array(1, array(-2), array(-3)),
		 4 => array(1, "where feverLess2 = 1 and testsOrdered = 1", array(-1)),
		 5 => array(0, "where malariaDx+malariaDxA+rapidResultPositive+smearResultPositive > 0", NULL),
		 6 => array(0, "where smearResultPositive+smearResultNegative > 0", NULL),
		 7 => array(0, "where smearResultPositive = 1", NULL),
		 8 => array(0, "where smearResultPositive = 1 and FT+FG > 0 and Vx+Ov+Mai = 0", NULL), 
		 9 => array(0, "where smearResultPositive = 1 and FT+FG > 0 and Vx+Ov+Mai > 0", NULL), 
		10 => array(0, "where smearResultPositive = 1 and FT+FG = 0 and Vx+Ov+Mai > 0", NULL), 
		11 => array(0, "where rapidResultPositive+rapidResultNegative > 0", NULL), 
		12 => array(0, "where rapidResultPositive = 1", NULL),
		13 => array(0, "where feverLess2 = 1 and testsOrdered = 1", NULL),
		14 => array(0, "where malariaDx+malariaDxA+rapidResultPositive+smearResultPositive > 0", NULL),
		15 => array(0, array(5,-2,"join"), NULL),
		16 => array(0, "where isPregnant = 1 and malariaDx+malariaDxA+rapidResultPositive+smearResultPositive > 0 and hospitalisation = 1", NULL),
		17 => array(1, "where isPregnant = 1 and feverLess2 = 1", array(-10)),
		18 => array(1, "where isPregnant = 1 and patientid in (select patientid from dw_malaria_snapshot where malariaDx+malariaDxA+rapidResultPositive+smearResultPositive = 0) and chloroquine+primaquine+quinine > 0", array(-10)), 
		19 => array(1, array(-20), array(-30)),
		20 => array(1, "where isPregnant = 1 and feverLess2 = 1 and testsOrdered = 1", array(-10)),
		21 => array(0, "where isPregnant = 1 and malariaDx+malariaDxA+rapidResultPositive+smearResultPositive > 0", NULL),
		22 => array(0, "where isPregnant = 1 and smearResultPositive+smearResultNegative > 0", NULL),
		23 => array(0, "where isPregnant = 1 and smearResultPositive = 1", NULL),
		24 => array(0, "where isPregnant = 1 and smearResultPositive = 1 and FT+FG > 0 and Vx+Ov+Mai = 0", NULL), 
		25 => array(0, "where isPregnant = 1 and smearResultPositive = 1 and FT+FG > 0 and Vx+Ov+Mai > 0", NULL), 
		26 => array(0, "where isPregnant = 1 and smearResultPositive = 1 and FT+FG = 0 and Vx+Ov+Mai > 0", NULL), 
		27 => array(0, "where isPregnant = 1 and rapidResultPositive+rapidResultNegative > 0", NULL), 
		28 => array(0, "where isPregnant = 1 and rapidResultPositive = 1", NULL),
		29 => array(0, "where isPregnant = 1 and feverLess2 = 1 and testsOrdered = 1", NULL),
		30 => array(0, "where isPregnant = 1 and malariaDx+malariaDxA+rapidResultPositive+smearResultPositive > 0", NULL),
		31 => array(0, array(21,-20,"join"), NULL),
		32 => array(0, "where isPregnant = 1 and malariaDx+malariaDxA+rapidResultPositive+smearResultPositive > 0 and hospitalisation = 1", NULL)
	);
	
	if (DEBUG_FLAG) echo "<br>Generate Patient Lists start: " . date('h:i:s') . "<br>";
	// store the patientid lists; don't need any reference to org, since pid contains site info
	foreach ($indicatorQueries as $indicator => $query) {
		foreach ($time_period as $period) {
			if ($period == "Week") $period_value = $period . "(s.visitdate,2) ";
			else $period_value = $period . "(s.visitdate) ";
			if (!is_array($query[1])) {
				if ($indicator == -1 || $indicator == -10) $sql = "insert into dw_malaria_patients select distinct " . $indicator . ",?, year(s.visitdate), " . $period_value . ", s.patientid from encValidNeg s " . $query[1]; 
				else $sql = "insert into dw_malaria_patients select distinct " . $indicator . ",?, year(s.visitdate), " . $period_value . ", s.patientid from dw_malaria_snapshot s " . $query[1]; 
				$rc = database()->query($sql,array($period))->rowCount();
				if (DEBUG_FLAG) echo "<br>Generate Pid List for: " . $indicator . " :" . $sql . " Rows inserted: " . $rc . "<br>"; 
			} else { 
				// anytime $query[1] isn't simple, previous calculations can be used
				generatePidLists("malaria", $indicator, $query[1], $period);
			}
		} 
	}	 
	if (DEBUG_FLAG) echo "<br>Generate Patient Lists end/Indicator slices start: " . date('h:i:s') . "<br>";
	// store the indicators     
	foreach ($indicatorQueries as $indicator => $query) {
		if ($indicator < 1) continue;  // don't need slices for these
		foreach ($orgType as $org_unit => $org_value) { 
			switch ($query[0]) {
			case 0: // simple calculation
				$sql = "insert into dw_malaria_slices select ?, " . $org_value . ", " . $indicator . ", time_period, year, period, t.sex, count(distinct p.patientid), 0 from dw_malaria_patients p, patient t";
				if ($org_unit != "Haiti") 
					$sql .= ", clinicLookup c where c.sitecode = left(p.patientid,5) and ";
				else 
					$sql .= " where ";
				$sql .= " indicator = ? and p.patientid = t.patientid and t.sex in (1,2) group by 1,2,3,4,5,6,7";
				if ($org_unit == 'Commune') $argArray = array($org_unit, "-", $indicator);
				else $argArray = array($org_unit, $indicator); 
				$rc = database()->query($sql, $argArray)->rowCount();
				if (DEBUG_FLAG) {
					echo "<br>Generate simple slices for: " . $indicator . " :" . $sql . " Rows inserted: " . $rc . "<br>"; 
					print_r ($argArray);
				} 
				break;
			case 1: // percent
				generatePercents('malaria', $indicator, $org_unit, $org_value, $query);
				break;
			case 2: // this among that
				generateAmongSlices("malaria", $indicator, $org_unit, $org_value, $query);
				break;
			}
		} 
	}  
	if (DEBUG_FLAG) echo "<br>Indicator slices end: " . date('h:i:s') . "<br>";       	
}
?>