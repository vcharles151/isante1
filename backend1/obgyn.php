<?

/* last date modified :  sept 29th */
function updateObgynSnapshot($lastModified) {

	
	database()->query('INSERT INTO dw_obgyn_snapshot (patientID, visitDate, mammographDt, papTestResult, leucorhee, metrorragieSymptom, sexAgression, consult_obs, grossesseHautRisque, tetanosDtD1, hypertensionArteryA, hemorragieVaginale, membraneRupture, vacuum, laborMethod, laborMystery, laborDifficultBirth, vitalWeight1, ppVitalBp1, ironSup, utilisationPartogramme, beneficieGATPA, laborEvolution, plusDe30Ans, plusDe40Ans, 	femmesVuesPrenatal, suiviPrenatal, 	accouchement)
 SELECT patientID, maxDate AS visiteDate,
COUNT(CASE WHEN concept_id = 8039 THEN patientID ELSE NULL END) AS mammographDt,
COUNT(CASE WHEN concept_id = 7073 THEN patientID ELSE NULL END) AS papTestResult,
COUNT(CASE WHEN concept_id = 7886 THEN patientID ELSE NULL END) AS leucorhee,
COUNT(CASE WHEN concept_id = 70631 THEN patientID ELSE NULL END) AS metrorragieSymptom,
COUNT(CASE WHEN concept_id = 70176 THEN patientID ELSE NULL END) AS sexAgression,
COUNT(CASE WHEN concept_id = 70018 THEN patientID ELSE NULL END) AS consult_obs,
0 AS grossesseHautRisque,
COUNT(CASE WHEN concept_id = 71079 THEN patientID ELSE NULL END) AS tetanosDtD1,
COUNT(CASE WHEN concept_id = 70268 THEN patientID ELSE NULL END) AS hypertensionArteryA,
COUNT(CASE WHEN concept_id = 70190 THEN patientID ELSE NULL END) AS hemorragieVaginale,
COUNT(CASE WHEN concept_id = 70082 THEN patientID ELSE NULL END) AS membraneRupture,
COUNT(CASE WHEN concept_id = 7200 THEN patientID ELSE NULL END) AS vacuum,
COUNT(CASE WHEN concept_id = 7820 THEN patientID ELSE NULL END) AS laborMethod,
COUNT(CASE WHEN concept_id = 71137 THEN patientID ELSE NULL END) AS laborMystery,
COUNT(CASE WHEN concept_id = 71279 THEN patientID ELSE NULL END) AS laborDifficultBirth,
COUNT(CASE WHEN concept_id = 7280 THEN patientID ELSE NULL END) AS vitalWeight1,
COUNT(CASE WHEN concept_id = 7248 THEN patientID ELSE NULL END) AS ppVitalBp1,
COUNT(CASE WHEN concept_id = 70792 THEN patientID ELSE NULL END) AS ironSup,
0 AS utilisationPartogramme,
0 AS beneficieGATPA,
COUNT(CASE WHEN concept_id = 70521 THEN patientID ELSE NULL END) AS laborEvolution,
COUNT(CASE WHEN concept_id = 284746 THEN patientID ELSE NULL END) AS plusDe30Ans,
COUNT(CASE WHEN concept_id = 984746 THEN patientID ELSE NULL END) AS plusDe40Ans,
COUNT(CASE WHEN concept_id = 683632 THEN patientID ELSE NULL END) AS femmesVuesPrenatal, 
COUNT(CASE WHEN concept_id = 70729 THEN patientID ELSE NULL END) AS suiviPrenatal,
COUNT(CASE WHEN concept_id = 70478 THEN patientID ELSE NULL END) AS accouchement  
FROM
(
 
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id 
 FROM encValidAll e, obs o
 WHERE  o.location_id = e.siteCode
 AND o.encounter_id = e.encounter_id
 AND o.concept_id IN (8039)           
 AND e.encounterType IN (24, 25)            
 GROUP BY 1
 
 UNION  
 
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id 
 FROM encValidAll e, obs o
 WHERE  o.location_id = e.siteCode
 AND o.encounter_id = e.encounter_id
 AND o.concept_id IN (7073,70401)           
 AND e.encounterType IN (24, 25)            
 GROUP BY 1
 
 UNION 
 
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id 
 FROM encValidAll e, obs o
 WHERE  o.location_id = e.siteCode
 AND o.encounter_id = e.encounter_id
 AND o.concept_id IN (7886,70194 )           
 AND e.encounterType IN (24, 25)  
 GROUP BY 1
 
 UNION 
 
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id 
 FROM encValidAll e, obs o
 WHERE  o.location_id = e.siteCode
 AND o.encounter_id = e.encounter_id
 AND o.concept_id IN (70631, 70636,70083, 70152)           
 AND e.encounterType IN (24, 25)  
 GROUP BY 1
 
 UNION 
 
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id 
 FROM encValidAll e, obs o
 WHERE  o.location_id = e.siteCode
 AND o.encounter_id = e.encounter_id
 AND o.concept_id IN (70176,71131, 71132, 71133, 71134)           
 AND e.encounterType IN (24, 25)  
 GROUP BY 1
 
  
 UNION 
 
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id 
 FROM encValidAll e, obs o
 WHERE  o.location_id = e.siteCode
 AND o.encounter_id = e.encounter_id
 AND o.concept_id IN (70018)           
 AND e.encounterType IN (24, 25)  
 GROUP BY 1
 
  UNION 
 
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id  
 FROM encValidAll e, obs o
 WHERE  o.location_id = e.siteCode
 AND o.encounter_id = e.encounter_id
 AND o.concept_id IN (71079, 71080, 71081, 71082)           
 AND e.encounterType IN (24, 25)  
 GROUP BY 1
 
 UNION 
 
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id 
 FROM encValidAll e, obs o
 WHERE  o.location_id = e.siteCode
 AND o.encounter_id = e.encounter_id
 AND o.concept_id IN (70268)           
 AND e.encounterType IN (24, 25)  
 GROUP BY 1
 
  UNION 
 
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id  
 FROM encValidAll e, obs o
 WHERE  o.location_id = e.siteCode
 AND o.encounter_id = e.encounter_id
 AND o.concept_id IN (70190, 71135, 70635)           
 AND e.encounterType IN (24, 25)  
 GROUP BY 1
 
  UNION 
 
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id 
 FROM encValidAll e, obs o
 WHERE  o.location_id = e.siteCode
 AND o.encounter_id = e.encounter_id
 AND o.concept_id IN (70082)           
 AND e.encounterType IN (24, 25)  
 GROUP BY 1
 
 UNION 
 
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id 
 FROM encValidAll e, obs o
 WHERE  o.location_id = e.siteCode
 AND o.encounter_id = e.encounter_id
 AND o.concept_id IN (7200, 70524)           
 AND e.encounterType IN (24, 25)  
 GROUP BY 1
 
 
  UNION 
 
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id 
 FROM encValidAll e, obs o
 WHERE  o.location_id = e.siteCode
 AND o.encounter_id = e.encounter_id
 AND o.concept_id IN (7820)           
 AND e.encounterType IN (24, 25)  
 GROUP BY 1
 
 UNION 
 
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id 
 FROM encValidAll e, obs o
 WHERE  o.location_id = e.siteCode
 AND o.encounter_id = e.encounter_id
 AND o.concept_id IN (71137)           
 AND e.encounterType IN (24, 25)  
 GROUP BY 1
 
 UNION 
 
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id 
 FROM encValidAll e, obs o
 WHERE  o.location_id = e.siteCode
 AND o.encounter_id = e.encounter_id
 AND o.concept_id IN (71279,  71297, 7828, 71281)           
 AND e.encounterType IN (24, 25)  
 GROUP BY 1
 
 UNION 
 
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id 
 FROM encValidAll e, obs o
 WHERE  o.location_id = e.siteCode
 AND o.encounter_id = e.encounter_id
 AND o.concept_id IN (7280)           
 AND e.encounterType IN (24, 25)  
 GROUP BY 1
 
 
 UNION 
 
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id 
 FROM encValidAll e, obs o
 WHERE  o.location_id = e.siteCode
 AND o.encounter_id = e.encounter_id
 AND o.concept_id IN (7248, 7249)           
 AND e.encounterType IN (24, 25)  
 GROUP BY 1
 
  UNION 
 
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id 
 FROM encValidAll e, obs o
 WHERE  o.location_id = e.siteCode
 AND o.encounter_id = e.encounter_id
 AND o.concept_id IN (7937, 70699, 70772, 70777, 70782, 70787, 70792)           
 AND e.encounterType IN (24, 25)  
 GROUP BY 1
 
  UNION 
 
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id 
 FROM encValidAll e, obs o
 WHERE  o.location_id = e.siteCode
 AND o.encounter_id = e.encounter_id
 AND o.concept_id IN (70521)           
 AND e.encounterType IN (24, 25)  
 GROUP BY 1
 
 UNION
 
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id 
 FROM encValidAll e, obs o, patient p
 WHERE  o.location_id = e.siteCode
 AND e.patientID = p.patientID
 AND o.encounter_id = e.encounter_id
 AND YEAR(NOW())-dobYy >= 40         
 AND e.encounterType IN (24, 25)            
 GROUP BY 1
 
 UNION
 
 
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id
 FROM encValidAll e, obs o, patient p
 WHERE  o.location_id = e.siteCode
 AND e.patientID = p.patientID
 AND o.encounter_id = e.encounter_id
 AND YEAR(NOW())-dobYy >= 30        
 AND e.encounterType IN (24, 25)            
 GROUP BY 1
 
 UNION
 
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id 
 FROM encValidAll e, obs o, patient p
 WHERE  o.location_id = e.siteCode
 AND e.patientID = p.patientID
 AND o.encounter_id = e.encounter_id  
 AND e.encounterType IN (24, 25)            
 GROUP BY 1 
 
 UNION
 
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id 
 FROM encValidAll e, obs o
 WHERE  o.location_id = e.siteCode
 AND o.encounter_id = e.encounter_id
 AND o.concept_id IN (70729)           
 AND e.encounterType IN (24, 25)  
 GROUP BY 1
 
  UNION
 
 
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id 
 FROM encValidAll e, obs o, patient p
 WHERE  o.location_id = e.siteCode
 AND e.patientID = p.patientID
 AND o.encounter_id = e.encounter_id       
 AND ((e.encounterType IN (24, 25)  
 AND o.concept_id IN(70472, 70475, 70478) ) OR e.encounterType = 26)          
 GROUP BY 1
 )t
GROUP BY patientID, maxDate', array('8039','7073','7886','70631','70176','70018','71079','70268','70190','70082','7200','7820','71137','71279','7280','7248','70792','70521','984746','284746','683632','70729','70478'));
	

}



function obgynSlices($key, $orgType, $time_period) {
	$indicatorQueries = array( 
		-1 => array(0, "where plusDe40Ans >0 ", NULL),
		-2 => array(0, "where plusDe30Ans >0 ", NULL), 
  		-3 => array(0, "where suiviPrenatal >0  ", NULL), 
		-4 => array(0, "where femmesVuesPrenatal> 0 ", NULL),
		-5 => array(0, "where accouchement > 0", NULL),
		
		 
		1 => array(1, "where mammographDt = 1", array(-1)),
		2 => array(1, "where papTestResult = 1", array(-2)), 
  		3 => array(1, "where leucorhee =1 ", array(-4)), 
		4 => array(0, "where metrorragieSymptom = 1", NULL),
		5 => array(0, "where sexAgression = 1", NULL), 
		 6 => array(0, "where consult_obs = 1", NULL),
		 7 => array(0, "where grossesseHautRisque = 1",NULL), 
		 8 => array(0, "where tetanosDtD1 = 0", NULL),
		 9 => array(0, "where hypertensionArteryA = 1", NULL),
		 10 => array(0, "where hemorragieVaginale = 1", NULL),
		 11 => array(0, "where membraneRupture = 1", NULL),
		 12 => array(0, "where vacuum + laborMethod + laborMystery  > 0", NULL),
		 13 => array(0, "where  laborDifficultBirth = 1", NULL),
		 14 => array(1, "where vitalWeight1 = 1", array(-4)),
		 15 => array(1, "where ppVitalBp1 = 1", array(-4)),
		 16 => array(1, "where ironSup = 1", array(-4)),
		 17 => array(1, "where utilisationPartogramme = 0", array(-5)),
		 18=> array(1, "where beneficieGATPA = 1", array(-5)),
		 19 => array(0, "where hemorragieVaginale = 1", NULL),
		 20 => array(0, "where laborEvolution = 1", NULL)
	);
	
	if (DEBUG_FLAG) echo "<br>Generate Patient Lists start: " . date('h:i:s') . "<br>";
	// store the patientid lists; don't need any reference to org, since pid contains site info
	foreach ($indicatorQueries as $indicator => $query) {
		foreach ($time_period as $period) {
			if ($period == "Week") $period_value = $period . "(s.visitdate,2) ";
			else $period_value = $period . "(s.visitdate) ";
			if (!is_array($query[1])) {
				 $sql = "insert into dw_obgyn_patients select distinct " . $indicator . ",?, year(s.visitdate), " . $period_value . ", s.patientid from dw_obgyn_snapshot  s " . $query[1]; 
				$rc = database()->query($sql,array($period))->rowCount();
				if (DEBUG_FLAG) echo "<br>Generate Pid List for: " . $indicator . " :" . $sql . " Rows inserted: " . $rc . "<br>"; 
			} else { 
				// anytime $query[1] isn't simple, previous calculations can be used
				generatePidLists("obgyn", $indicator, $query[1], $period);
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
				$sql = "insert into dw_obgyn_slices select ?, " . $org_value . ", " . $indicator . ", time_period, year, period, t.sex, count(distinct p.patientid), 0 from dw_obgyn_patients p, patient t";
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
				generatePercents('obgyn', $indicator, $org_unit, $org_value, $query);
				break;
			case 2: // this among that
				generateAmongSlices("obgyn", $indicator, $org_unit, $org_value, $query);
				break;
			}
		} 
	}  
	if (DEBUG_FLAG) echo "<br>Indicator slices end: " . date('h:i:s') . "<br>";       	
}
?>