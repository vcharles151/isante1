<? 
/* last date modified :  sept 29th */
function updateDataqualitySnapshot($lastModified) {

database()->query('INSERT INTO   ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id 
 FROM encValidAll e, obs o
 WHERE  o.encounter_id = e.encounter_id      
 AND e.encounterType IN (15)            
 GROUP BY 1 )p',array ('C1D'));
 
 
database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (

 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id
 FROM encValidAll e, obs o, patient p
 WHERE  o.encounter_id = e.encounter_id
 AND e.patientID = p.patientID      
 AND e.encounterType IN (15)
 AND (TRIM(p.dobDd) =? OR TRIM(p.dobMm)=? OR TRIM(p.dobYy=?) OR LOWER(TRIM(p.dobDd)) =? OR LOWER(TRIM(p.dobMm))=? OR LOWER(TRIM(p.dobYy)=?))
 GROUP BY 1 )p',array ('C1N','','','','xx','xx','xx'));


database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (
  
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id
 FROM encValidAll e, obs o
 WHERE  o.encounter_id = e.encounter_id      
 AND e.encounterType IN (10)            
 GROUP BY 1 )p',array ('C2D'));
 
database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (

 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id
 FROM encValidAll e, obs o, patient p
 WHERE  o.encounter_id = e.encounter_id
 AND e.patientID = p.patientID      
 AND e.encounterType IN (10)
 AND (TRIM(p.dobDd) =? OR TRIM(p.dobMm)=? OR TRIM(p.dobYy=?) OR LOWER(TRIM(p.dobDd)) =? OR LOWER(TRIM(p.dobMm))=? OR LOWER(TRIM(p.dobYy)=?))       
 GROUP BY 1 )p',array ('C2N','','','','xx','xx','xx'));
 
database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (
  
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id
 FROM encValidAll e, obs o
 WHERE  o.encounter_id = e.encounter_id      
 AND e.encounterType IN (15, 10)            
 GROUP BY 1 )p',array ('C3D'));

database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (

 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id
 FROM encValidAll e, obs o, patient p
 WHERE  o.encounter_id = e.encounter_id
 AND e.patientID = p.patientID      
 AND e.encounterType IN (15, 10)
 AND (TRIM(p.dobDd) =? OR TRIM(p.dobMm)=? OR TRIM(p.dobYy=?) OR LOWER(TRIM(p.dobDd)) =? OR LOWER(TRIM(p.dobMm))=? OR LOWER(TRIM(p.dobYy)=?))
 GROUP BY 1 )p',array ('C3N','','','','xx','xx','xx'));

database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (
  
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id
 FROM encValidAll e
 JOIN obs o ON  (o.encounter_id = e.encounter_id AND e.encounterType IN (16, 17, 29, 31))
 GROUP BY 1 )p',array ('C4D')); 
	
	
database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (

 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id
 FROM encValidAll e
 JOIN obs o ON  (o.encounter_id = e.encounter_id AND e.encounterType IN (16, 17, 29, 31))
 JOIN vitals v ON (e.patientID = v.patientID AND e.visitDateDd = v.visitDateDd AND e.visitDateMm = v.visitDateMm AND e.visitDateYy = v.visitDateYy)
 WHERE (TRIM(v.vitalHeight)=? OR TRIM(v.vitalHeight)=? OR v.vitalHeight IS NULL) AND (TRIM(v.vitalHeightCm)=? OR TRIM(v.vitalHeightCm)=? OR v.vitalHeightCm IS NULL)
 GROUP BY 1 )p',array ('C4N','','','','')); 


database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id
 FROM encValidAll e
 JOIN obs o ON  (o.encounter_id = e.encounter_id AND e.encounterType IN (1, 2, 27, 28))
 GROUP BY 1 )p',array ('C5D')); 



database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (

 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id
 FROM encValidAll e
 JOIN obs o ON  (o.encounter_id = e.encounter_id AND e.encounterType IN (1, 2, 27, 28))
 JOIN vitals v ON (e.patientID = v.patientID AND e.visitDateDd = v.visitDateDd AND e.visitDateMm = v.visitDateMm AND e.visitDateYy = v.visitDateYy)   
 WHERE (TRIM(v.vitalHeight)=? OR TRIM(v.vitalHeight)=? OR v.vitalHeight IS NULL) AND (TRIM(v.vitalHeightCm)=? OR TRIM(v.vitalHeightCm)=? OR v.vitalHeightCm IS NULL)
 GROUP BY 1 )p', array ('C5N','','0','','0')); 
 
 
database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (
  
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id 
 FROM encValidAll e
 JOIN obs o ON  (o.encounter_id = e.encounter_id AND e.encounterType IN (1, 2, 16, 17, 27, 28, 29, 31))
 GROUP BY 1 )p', array ('C6D'));
 
 
database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (

 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id
 FROM encValidAll e
 JOIN obs o ON  (o.encounter_id = e.encounter_id AND e.encounterType IN (1, 2, 16, 17, 27, 28, 29, 31))
 JOIN vitals v ON (e.patientID = v.patientID AND e.visitDateDd = v.visitDateDd AND e.visitDateMm = v.visitDateMm AND e.visitDateYy = v.visitDateYy)   
 WHERE (TRIM(v.vitalHeight)=? OR TRIM(v.vitalHeight)=? OR v.vitalHeight IS NULL) AND (TRIM(v.vitalHeightCm)=? OR TRIM(v.vitalHeightCm)=? OR v.vitalHeightCm IS NULL)
 GROUP BY 1 )p', array ('C6N','','0','','0'));
 
 
database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (
 
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id
 FROM encValidAll e
 JOIN obs o ON  (o.encounter_id = e.encounter_id AND e.encounterType IN (16, 17, 29, 31))
 GROUP BY 1 )p', array ('C7D'));
 
 
database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (

 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id
 FROM encValidAll e
 JOIN obs o ON  (o.encounter_id = e.encounter_id AND e.encounterType IN (16, 17, 29, 31))
 JOIN vitals v ON (e.patientID = v.patientID AND e.visitDateDd = v.visitDateDd AND e.visitDateMm = v.visitDateMm AND e.visitDateYy = v.visitDateYy)
 WHERE (TRIM(v.vitalWeight)=? OR TRIM(v.vitalWeight)=? OR v.vitalWeight IS NULL)
 GROUP BY 1 ) p', array ('C7N','','0'));
 
 
database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (
 
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id
 FROM encValidAll e
 JOIN obs o ON  (o.encounter_id = e.encounter_id AND e.encounterType IN (1, 2, 27, 28))
 GROUP BY 1 )p', array ('C8D'));
 
 
database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (

 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id
 FROM encValidAll e
 JOIN obs o ON  (o.encounter_id = e.encounter_id AND e.encounterType IN (1, 2, 27, 28))
 JOIN vitals v ON (e.patientID = v.patientID AND e.visitDateDd = v.visitDateDd AND e.visitDateMm = v.visitDateMm AND e.visitDateYy = v.visitDateYy)   
 WHERE (TRIM(v.vitalWeight)=? OR TRIM(v.vitalWeight)=? OR v.vitalWeight IS NULL)
 GROUP BY 1 )p', array ('C8N','','0'));
 
 
database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (
  
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id 
 FROM encValidAll e
 JOIN obs o ON  (o.encounter_id = e.encounter_id AND e.encounterType IN (1, 2, 16, 17, 27, 28, 29, 31))
 GROUP BY 1 )p', array ('C9D'));
 
 
database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (

 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id 
 FROM encValidAll e
 JOIN obs o ON  (o.encounter_id = e.encounter_id AND e.encounterType IN (1, 2, 16, 17, 27, 28, 29, 31))
 JOIN vitals v ON (e.patientID = v.patientID AND e.visitDateDd = v.visitDateDd AND e.visitDateMm = v.visitDateMm AND e.visitDateYy = v.visitDateYy)   
 WHERE (TRIM(v.vitalWeight)=? OR TRIM(v.vitalWeight)=? OR v.vitalWeight IS NULL)
 GROUP BY 1 )p', array ('C9N','','0'));
 
 
database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (

 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id
 FROM encValidAll e
 JOIN obs o ON (o.encounter_id = e.encounter_id AND e.encounterType IN (1,2, 27, 28))
 JOIN patient p ON e.patientID = p.patientID
 WHERE (YEAR(NOW()) - p.dobYy) BETWEEN 15 AND 45  
 AND p.sex = 1        
 GROUP BY 1 )p', array ('C10D'));
 
 
database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (
 
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id
 FROM encValidAll e
 JOIN obs o ON (o.encounter_id = e.encounter_id AND e.encounterType IN (1,2, 27, 28))
 JOIN patient p ON e.patientID = p.patientID
 JOIN vitals v ON (e.patientID = v.patientID AND e.visitDateDd = v.visitDateDd AND e.visitDateMm = v.visitDateMm AND e.visitDateYy = v.visitDateYy) 
 WHERE (YEAR(NOW()) - p.dobYy) BETWEEN 15 AND 45  
 AND p.sex = 1 
 AND v.pregnant NOT IN (1, 2, 4)       
 GROUP BY 1 )p', array ('C10N'));  
 
 
database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM ( 
   
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id
 FROM encValidAll e
 JOIN obs o ON (o.encounter_id = e.encounter_id AND e.encounterType IN (16, 17))
 JOIN  medicalEligARVs m ON (e.patientID = m.patientID AND e.visitDateDd = m.visitDateDd AND e.visitDateMm = m.visitDateMm AND e.visitDateYy = m.visitDateYy) 
 WHERE (m.medElig IS NULL OR m.medElig !=0)
 AND ((m.cd4LT200=0 OR m.cd4LT200 IS NULL) 
      AND (m.tlcLT1200=0 OR m.tlcLT1200 IS NULL) 
      AND (m.WHOIV=0 OR m.WHOIV IS NULL)
      AND (m.WHOIII=0 OR m.WHOIII IS NULL)
      AND (m.PMTCT=0 OR m.PMTCT IS NULL)
      AND (m.medEligHAART=0 OR m.medEligHAART IS NULL)
      AND (m.formerARVtherapy=0 OR m.formerARVtherapy IS NULL)
      AND (m.PEP=0 OR m.PEP IS NULL))
 GROUP BY 1 )p', array ('C11N'));
 
 
database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (  

 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id
 FROM encValidAll e
 JOIN obs o ON (o.encounter_id = e.encounter_id AND e.encounterType IN (1, 2))
 JOIN  medicalEligARVs m ON (e.patientID = m.patientID AND e.visitDateDd = m.visitDateDd AND e.visitDateMm = m.visitDateMm AND e.visitDateYy = m.visitDateYy) 
 WHERE (m.medElig IS NULL OR m.medElig !=0)
 AND ((m.cd4LT200=0 OR m.cd4LT200 IS NULL) 
      AND (m.tlcLT1200=0 OR m.tlcLT1200 IS NULL) 
      AND (m.WHOIV=0 OR m.WHOIV IS NULL)
      AND (m.WHOIII=0 OR m.WHOIII IS NULL)
      AND (m.PMTCT=0 OR m.PMTCT IS NULL)
      AND (m.medEligHAART=0 OR m.medEligHAART IS NULL)
      AND (m.formerARVtherapy=0 OR m.formerARVtherapy IS NULL)
      AND (m.PEP=0 OR m.PEP IS NULL))
 GROUP BY 1 )p', array ('C12N'));
 
 
database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM ( 
   
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id
 FROM encValidAll e
 JOIN obs o ON (o.encounter_id = e.encounter_id AND e.encounterType IN (1, 2, 16, 17))
 JOIN  medicalEligARVs m ON (e.patientID = m.patientID AND e.visitDateDd = m.visitDateDd AND e.visitDateMm = m.visitDateMm AND e.visitDateYy = m.visitDateYy) 
 WHERE (m.medElig IS NULL OR m.medElig !=0)
 AND ((m.cd4LT200=0 OR m.cd4LT200 IS NULL) 
      AND (m.tlcLT1200=0 OR m.tlcLT1200 IS NULL) 
      AND (m.WHOIV=0 OR m.WHOIV IS NULL)
      AND (m.WHOIII=0 OR m.WHOIII IS NULL)
      AND (m.PMTCT=0 OR m.PMTCT IS NULL)
      AND (m.medEligHAART=0 OR m.medEligHAART IS NULL)
      AND (m.formerARVtherapy=0 OR m.formerARVtherapy IS NULL)
      AND (m.PEP=0 OR m.PEP IS NULL))
 GROUP BY 1 )p', array ('C13N'));
 
   
database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (
 
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id
 FROM encValidAll e
 JOIN obs o ON (o.encounter_id = e.encounter_id AND e.encounterType IN (16, 17))
 JOIN  tbStatus t ON (e.patientID = t.patientID AND e.visitDateDd = t.visitDateDd AND e.visitDateMm = t.visitDateMm AND e.visitDateYy = t.visitDateYy)
 JOIN   pedHistory p ON (e.patientID = p.patientID AND e.visitDateDd = p.visitDateDd AND e.visitDateMm = p.visitDateMm AND e.visitDateYy = p.visitDateYy) 
 WHERE ((p.pedMotherHistRecentTb = 0 OR p.pedMotherHistRecentTb IS NULL) 
	       AND (p.pedMotherHistActiveTb =0 OR p.pedMotherHistActiveTb IS NULL)
	       AND (p.pedMotherHistTreatTb = 0 OR p.pedMotherHistTreatTb IS NULL))
	 AND (((t.asymptomaticTb = 0 OR t.asymptomaticTb IS NULL) 
	       AND (t.currentTreat = 0 OR t.currentTreat IS NULL)
	       AND (t.completeTreat =0 OR t.completeTreat IS NULL) 
	       AND (t.propINH =0 OR t.propINH IS NULL))
	 OR ((t.pedTbEvalRecentExp =0 OR t.pedTbEvalRecentExp IS NULL) 
	      OR (t.suspicionTBwSymptoms=0 OR t.suspicionTBwSymptoms IS NULL)
	      OR (t.presenceBCG = 0 OR t.presenceBCG IS NULL)
	      OR (t.noTBsymptoms = 0 OR t.noTBsymptoms IS NULL)))    
	 GROUP BY 1 )p', array ('C14N'));
	 
 
database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (
 
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id
 FROM encValidAll e
 JOIN obs o ON (o.encounter_id = e.encounter_id AND e.encounterType IN (16, 17))
 JOIN   tbStatus t ON (e.patientID = t.patientID AND e.visitDateDd = t.visitDateDd AND e.visitDateMm = t.visitDateMm AND e.visitDateYy = t.visitDateYy)
 JOIN  pedHistory p ON (e.patientID = p.patientID AND e.visitDateDd = p.visitDateDd AND e.visitDateMm = p.visitDateMm AND e.visitDateYy = p.visitDateYy) 
 GROUP BY 1 )p', array ('C14D'));

 
database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (
 
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id
 FROM encValidAll e
 JOIN obs o ON (o.encounter_id = e.encounter_id AND e.encounterType IN (1, 2))
 JOIN   tbStatus t ON (e.patientID = t.patientID AND e.visitDateDd = t.visitDateDd AND e.visitDateMm = t.visitDateMm AND e.visitDateYy = t.visitDateYy)
 JOIN  pedHistory p ON (e.patientID = p.patientID AND e.visitDateDd = p.visitDateDd AND e.visitDateMm = p.visitDateMm AND e.visitDateYy = p.visitDateYy) 
 WHERE (((t.asymptomaticTb = 0 OR t.asymptomaticTb IS NULL) 
	       AND (t.completeTreat =0 OR t.completeTreat IS NULL)
	       AND (t.currentTreat = 0 OR t.currentTreat IS NULL))
	 OR ((t.presenceBCG = 0 OR t.presenceBCG IS NULL) 
	      AND (t.recentNegPPD = 0 OR t.recentNegPPD IS NULL) 
	      AND (t.statusPPDunknown = 0 OR t.statusPPDunknown IS NULL) 
	      AND (t.propINH = 0 OR t.propINH IS NULL)
	      AND (t.suspicionTBwSymptoms = 0 OR t.suspicionTBwSymptoms IS NULL)
	      AND (t.noTBsymptoms = 0 OR t.noTBsymptoms IS NULL)))   
	 GROUP BY 1 )p',array ('C15N'));
 
database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (
 
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id
 FROM encValidAll e
 JOIN obs o ON (o.encounter_id = e.encounter_id AND e.encounterType IN (1, 2))
 JOIN   tbStatus t ON (e.patientID = t.patientID AND e.visitDateDd = t.visitDateDd AND e.visitDateMm = t.visitDateMm AND e.visitDateYy = t.visitDateYy)
 JOIN  pedHistory p ON (e.patientID = p.patientID AND e.visitDateDd = p.visitDateDd AND e.visitDateMm = p.visitDateMm AND e.visitDateYy = p.visitDateYy) 
 GROUP BY 1 )p', array ('C15D'));
 
 
database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id)
 
   SELECT patientID, maxDate, ? AS concept_id FROM
   (
	 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id
	 FROM encValidAll e
	 JOIN obs o ON (o.encounter_id = e.encounter_id AND e.encounterType IN (16, 17))
	 JOIN   tbStatus t ON (e.patientID = t.patientID AND e.visitDateDd = t.visitDateDd AND e.visitDateMm = t.visitDateMm AND e.visitDateYy = t.visitDateYy)
	 JOIN  pedHistory p ON (e.patientID = p.patientID AND e.visitDateDd = p.visitDateDd AND e.visitDateMm = p.visitDateMm AND e.visitDateYy = p.visitDateYy) 
	 WHERE ((p.pedMotherHistRecentTb = 0 OR p.pedMotherHistRecentTb IS NULL) 
		       AND (p.pedMotherHistActiveTb =0 OR p.pedMotherHistActiveTb IS NULL)
		       AND (p.pedMotherHistTreatTb = 0 OR p.pedMotherHistTreatTb IS NULL))
		 AND (((t.asymptomaticTb = 0 OR t.asymptomaticTb IS NULL) 
		       AND (t.currentTreat = 0 OR t.currentTreat IS NULL)
		       AND (t.completeTreat =0 OR t.completeTreat IS NULL) 
		       AND (t.propINH =0 OR t.propINH IS NULL))
		 OR ((t.pedTbEvalRecentExp =0 OR t.pedTbEvalRecentExp IS NULL) 
		      OR (t.suspicionTBwSymptoms=0 OR t.suspicionTBwSymptoms IS NULL)
		      OR (t.presenceBCG = 0 OR t.presenceBCG IS NULL)
		      OR (t.noTBsymptoms = 0 OR t.noTBsymptoms IS NULL)))    
		 GROUP BY 1
		 
	UNION 
	
	 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id
	 FROM encValidAll e
	 JOIN obs o ON (o.encounter_id = e.encounter_id AND e.encounterType IN (1, 2))
	 JOIN   tbStatus t ON (e.patientID = t.patientID AND e.visitDateDd = t.visitDateDd AND e.visitDateMm = t.visitDateMm AND e.visitDateYy = t.visitDateYy)
	 JOIN  pedHistory p ON (e.patientID = p.patientID AND e.visitDateDd = p.visitDateDd AND e.visitDateMm = p.visitDateMm AND e.visitDateYy = p.visitDateYy) 
	 WHERE (((t.asymptomaticTb = 0 OR t.asymptomaticTb IS NULL) 
		       AND (t.completeTreat =0 OR t.completeTreat IS NULL)
		       AND (t.currentTreat = 0 OR t.currentTreat IS NULL))
		 OR ((t.presenceBCG = 0 OR t.presenceBCG IS NULL) 
		      AND (t.recentNegPPD = 0 OR t.recentNegPPD IS NULL) 
		      AND (t.statusPPDunknown = 0 OR t.statusPPDunknown IS NULL) 
		      AND (t.propINH = 0 OR t.propINH IS NULL)
		      AND (t.suspicionTBwSymptoms = 0 OR t.suspicionTBwSymptoms IS NULL)
		      AND (t.noTBsymptoms = 0 OR t.noTBsymptoms IS NULL)))   
		 GROUP BY 1
       ) p
	 ', array ('C16N','C14N','C15N'));
       
	   
database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) 
 
 SELECT patientID, maxDate, ? AS concept_id FROM
 (
	 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id
	 FROM encValidAll e
	 JOIN obs o ON (o.encounter_id = e.encounter_id AND e.encounterType IN (16, 17))
	 JOIN   tbStatus t ON (e.patientID = t.patientID AND e.visitDateDd = t.visitDateDd AND e.visitDateMm = t.visitDateMm AND e.visitDateYy = t.visitDateYy)
	 JOIN  pedHistory p ON (e.patientID = p.patientID AND e.visitDateDd = p.visitDateDd AND e.visitDateMm = p.visitDateMm AND e.visitDateYy = p.visitDateYy) 
	 GROUP BY 1
	 
	 UNION
 
	  
	 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id
	 FROM encValidAll e
	 JOIN obs o ON (o.encounter_id = e.encounter_id AND e.encounterType IN (1, 2))
	 JOIN  tbStatus t ON (e.patientID = t.patientID AND e.visitDateDd = t.visitDateDd AND e.visitDateMm = t.visitDateMm AND e.visitDateYy = t.visitDateYy)
	 JOIN  pedHistory p ON (e.patientID = p.patientID AND e.visitDateDd = p.visitDateDd AND e.visitDateMm = p.visitDateMm AND e.visitDateYy = p.visitDateYy) 
	 GROUP BY 1

)p', array ('C16D','C14D','C15D'));

 
database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM ( 
 
  SELECT p.patientID, MAX(DATE(CONCAT(?, d.visitDateYy, ?, d.visitDateMm,?, d.visitDateDd))) AS maxDate, ? AS concept_id 
	 FROM patient p	 
	 JOIN discEnrollment d ON (p.patientID = d.patientID )	
	 JOIN encounter e ON (e.patientID = p.patientID AND e.encounterType IN (21))
	 WHERE d.partStop = 1 
	 AND (DATE(CONCAT(?, d.disEnrollYy,?, d.disEnrollMm, ?, d.disEnrollDd)) IS NULL
	      OR((d.reasonDiscNoFollowup = 0 OR d.reasonDiscNoFollowup IS NULL)
	          AND(d.reasonDiscTransfer = 0 OR d.reasonDiscTransfer IS NULL)
	          AND(d.reasonDiscDeath = 0 OR d.reasonDiscDeath IS NULL)
	          AND(d.reasonDiscOther = 0 OR d.reasonDiscOther IS NULL)
	          AND(d.reasonUnknownClosing = 0 OR reasonUnknownClosing IS NULL)))	   
  GROUP BY 1 )p', array ('20','-','-','C17N','20','-','-'));
  
  
 database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (
  
  SELECT p.patientID, MAX(DATE(CONCAT(?, d.visitDateYy, ?, d.visitDateMm,?, d.visitDateDd))) AS maxDate, ? AS concept_id 
	 FROM patient p	 
	 JOIN discEnrollment d ON (p.patientID = d.patientID )	
	 JOIN encounter e ON (e.patientID = p.patientID AND e.encounterType IN (21))
	 WHERE d.partStop = 1 	 	   
  GROUP BY 1 )p', array ('20','-','-','C17D'));
  
  
 database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (
 
 
  SELECT p.patientID, MAX(DATE(CONCAT(?, d.visitDateYy, ?, d.visitDateMm,?, d.visitDateDd))) AS maxDate, ? AS concept_id 
	 FROM patient p	 
	 JOIN discEnrollment d ON (p.patientID = d.patientID )	
	 JOIN encounter e ON (e.patientID = p.patientID AND e.encounterType IN (12))
	 WHERE d.partStop = 1 
	 AND (DATE(CONCAT(?, d.disEnrollYy,?, d.disEnrollMm, ?, d.disEnrollDd)) IS NULL
	      OR((d.reasonDiscNoFollowup = 0 OR d.reasonDiscNoFollowup IS NULL)
	          AND(d.reasonDiscTransfer = 0 OR d.reasonDiscTransfer IS NULL)
	          AND(d.reasonDiscDeath = 0 OR d.reasonDiscDeath IS NULL)
	          AND(d.reasonDiscOther = 0 OR d.reasonDiscOther IS NULL)
	          AND(d.reasonUnknownClosing = 0 OR reasonUnknownClosing IS NULL)))	   
  GROUP BY 1 )p', array ('20','-','-','C18N','20','-','-'));
  
  
database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (
 
 
  SELECT p.patientID, MAX(DATE(CONCAT(?, d.visitDateYy, ?, d.visitDateMm,?, d.visitDateDd))) AS maxDate, ? AS concept_id 
	 FROM patient p	 
	 JOIN discEnrollment d ON (p.patientID = d.patientID )	
	 JOIN encounter e ON (e.patientID = p.patientID AND e.encounterType IN (12))
	 WHERE d.partStop = 1 	 	   
  GROUP BY 1 )p', array ('20','-','-','C18D'));
  
  
database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM ( 
 
  SELECT p.patientID, MAX(DATE(CONCAT(?, d.visitDateYy, ?, d.visitDateMm,?, d.visitDateDd))) AS maxDate, ? AS concept_id 
	 FROM patient p	 
	 JOIN discEnrollment d ON (p.patientID = d.patientID )	
	 JOIN encounter e ON (e.patientID = p.patientID AND e.encounterType IN (12, 21))
	 WHERE d.partStop = 1 
	 AND (DATE(CONCAT(?, d.disEnrollYy,?, d.disEnrollMm,?, d.disEnrollDd)) IS NULL
	      OR((d.reasonDiscNoFollowup = 0 OR d.reasonDiscNoFollowup IS NULL)
	          AND(d.reasonDiscTransfer = 0 OR d.reasonDiscTransfer IS NULL)
	          AND(d.reasonDiscDeath = 0 OR d.reasonDiscDeath IS NULL)
	          AND(d.reasonDiscOther = 0 OR d.reasonDiscOther IS NULL)
	          AND(d.reasonUnknownClosing = 0 OR reasonUnknownClosing IS NULL)))	   
  GROUP BY 1 )p', array ('20','-','-','C19N','20','-','-'));
  
  
database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM ( 
 
  SELECT p.patientID, MAX(DATE(CONCAT(?, d.visitDateYy,?, d.visitDateMm,?, d.visitDateDd))) AS maxDate, ? AS concept_id 
	 FROM patient p	 
	 JOIN discEnrollment d ON (p.patientID = d.patientID )	
	 JOIN encounter e ON (e.patientID = p.patientID AND e.encounterType IN (12, 21))
	 WHERE d.partStop = 1 	 	   
  GROUP BY 1 )p',array ('20','-','-','C19D'));
  
  
  database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id 
 FROM encValidAll e, obs o
 WHERE  o.location_id = e.siteCode
 AND o.encounter_id = e.encounter_id      
 AND e.encounterType IN (10,15)            
 GROUP BY 1)p', array ('A1D'));
 
 
database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (

 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id 
 FROM encValidAll e, obs o, patient p
 WHERE  o.location_id = e.siteCode
 AND o.encounter_id = e.encounter_id
 AND e.patientID = p.patientID      
 AND e.encounterType IN (10,15)
 AND (CAST(TRIM(p.dobDd) AS UNSIGNED) >31 OR CAST(TRIM(p.dobMm)AS UNSIGNED) >12 OR CAST(TRIM(p.dobYy)AS UNSIGNED)<1904  OR CAST(TRIM(p.dobYy)AS UNSIGNED)> YEAR(CURRENT_DATE()))        
 GROUP BY 1)p', array ('A1N'));
 
 
 database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (
 
  SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate,?  AS concept_id
 FROM encValidAll e, obs o, patient p
 WHERE  o.location_id = e.siteCode
 AND o.encounter_id = e.encounter_id
 AND e.patientID = p.patientID      
 AND e.encounterType IN (10,15)
 AND ((DATEDIFF(DATE(e.visitDate), DATE(CONCAT(p.dobYy,?,p.dobMm,?,p.dobDd)) )) - p.ageYears*365) > 1068       
 GROUP BY 1)p', array ('A2N','-','-'));
 
 
 database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (
 
   SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id 
 FROM encValidAll e, obs o, patient p
 WHERE  o.location_id = e.siteCode
 AND o.encounter_id = e.encounter_id
 AND e.patientID = p.patientID      
AND e.encounterType IN (10,15)
 AND (SUBSTRING(p.nationalID,3,2) != TRIM(p.dobMm) OR SUBSTRING(p.nationalID,5,2) != SUBSTRING(p.dobYy,3,2))   
 GROUP BY 1)p', array ('A3N'));
 
 
database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (
 
 
   SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id
 FROM encValidAll e, obs o, patient p
 WHERE  o.location_id = e.siteCode
 AND o.encounter_id = e.encounter_id
 AND e.patientID = p.patientID      
 AND e.encounterType IN (10,15)
 AND ((( YEAR(DATE(e.visitDate))-YEAR(DATE(CONCAT(p.dobYy,?,p.dobMm,?,p.dobDd))) < 15) AND p.isPediatric =0 ) 
 OR  (( YEAR(DATE(e.visitDate))-YEAR(DATE(CONCAT(p.dobYy,?,p.dobMm,?,p.dobDd))) > 15) AND p.isPediatric =1))
 GROUP BY 1)p', array ('A4N','-','-','-','-'));
 
 
 database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM ( 
 
 
  SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id  
 FROM encValidAll e, obs o, patient p
 WHERE  o.location_id = e.siteCode
 AND o.encounter_id = e.encounter_id
 AND e.patientID = p.patientID      
 AND e.encounterType IN (10,15)
 AND (YEAR(DATE(e.visitDate))-YEAR(DATE(CONCAT(p.dobYy,?,p.dobMm,?,p.dobDd))) <15 ) 
 AND  p.maritalStatus IN (1,2,4,8)
 GROUP BY 1)p', array ('A5N','-','-'));
 
 
 database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (
 
    SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id 
 FROM encValidAll e, obs o, patient p
 WHERE  o.location_id = e.siteCode
 AND o.encounter_id = e.encounter_id
 AND e.patientID = p.patientID      
 AND e.encounterType IN (10,15)
 AND (
 ((YEAR(DATE(e.visitDate))-YEAR(DATE(CONCAT(p.dobYy,?,p.dobMm,?,p.dobDd))) <15 ) 
 AND  p.maritalStatus IN (1,2,4,8))
OR
(
((( YEAR(DATE(e.visitDate))-YEAR(DATE(CONCAT(p.dobYy,?,p.dobMm,?,p.dobDd))) < 15) AND p.isPediatric =0 ) 
 OR  (( YEAR(DATE(e.visitDate))-YEAR(DATE(CONCAT(p.dobYy,?,p.dobMm,?,p.dobDd))) > 15) AND p.isPediatric =1))
) 
 OR
 (
 (SUBSTRING(p.nationalID,3,2) != TRIM(p.dobMm) OR SUBSTRING(p.nationalID,5,2) != SUBSTRING(p.dobYy,3,2))   
 )
 OR
 (
 ((DATEDIFF(DATE(e.visitDate), DATE(CONCAT(p.dobYy,?,p.dobMm,?,p.dobDd)) )) - p.ageYears*365) > 1068
 )
 OR
 (
 (CAST(TRIM(p.dobDd) AS UNSIGNED) >31 OR CAST(TRIM(p.dobMm)AS UNSIGNED) >12 OR CAST(TRIM(p.dobYy)AS UNSIGNED)<1904  OR CAST(TRIM(p.dobYy)AS UNSIGNED)> YEAR(CURRENT_DATE())) 
 )
 )
 GROUP BY 1)p',array ('A6N','-','-','-','-','-','-','-','-'));
 
 
 database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (
 
 
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id 
 FROM encValidAll e, obs o
 WHERE  o.location_id = e.siteCode
 AND o.encounter_id = e.encounter_id      
 AND e.encounterType IN (1,2,27,28)            
 GROUP BY 1)p',array ('A7D'));
 
 
 database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (
 
 
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id 
 FROM encValidAll e, obs o, patient p
 WHERE  o.location_id = e.siteCode
 AND o.encounter_id = e.encounter_id
 AND e.patientID = p.patientID      
 AND e.encounterType IN (24,25,26)
AND p.sex = 2
 GROUP BY 1)p',array ('A8D')); 
 
 
  database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (
 
 
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id 
 FROM encValidAll e, obs o, patient p
 WHERE  o.location_id = e.siteCode
 AND o.encounter_id = e.encounter_id
 AND e.patientID = p.patientID      
 AND e.encounterType IN (5,18)
 GROUP BY 1)p',array ('A11D')); 
 
 
 database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (
 
 
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id 
 FROM encValidAll e,  prescriptions pr
 WHERE   
  pr.patientID =  e.patientID
 AND e.encounterType IN (5,18)
 AND
 (DATE(CONCAT(pr.visitDateYy,?,pr.visitDateMm,?,pr.visitDateDd)) > DATE(CONCAT(pr.dispDateYy,?,pr.dispDateMm,?,pr.dispDateDd))
OR DATEDIFF(DATE(CONCAT(pr.dispDateYy,?,pr.dispDateMm,?,pr.dispDateDd)),DATE(CONCAT(pr.visitDateYy,?,pr.visitDateMm,?,pr.visitDateDd))) > 30)
AND pr.drugID IN (1,8,10,12,20,29,31,33,34,11,23,5,6,16,17,21)
 GROUP BY 1)p',array ('A11N','-','-','-','-','-','-','-','-')); 
 
 
database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (
 
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id 
 FROM encValidAll e, obs o, patient p
 WHERE  o.location_id = e.siteCode
 AND o.encounter_id = e.encounter_id
 AND e.patientID = p.patientID 
 GROUP BY 1)p',array ('A14D'));
 
 
database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (
 

 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id 
 FROM encValidAll e, obs o, patient p
 WHERE  o.location_id = e.siteCode
 AND o.encounter_id = e.encounter_id
 AND e.patientID = p.patientID
 AND ( DATE(CONCAT(e.visitDateYy,?,visitDateMm,?,visitDateDd)) <?
 OR (DATE(CONCAT(e.visitDateYy,?,visitDateMm,?,visitDateDd)) > e.createDate))
 GROUP BY 1)p',array ('A14N', '-','-','2004-01-01','-','-'));
 

 
database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM ( 
 
  SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id 
 FROM encValidAll e,  prescriptions pr
 WHERE   
  pr.patientID =  e.patientID
 AND e.encounterType IN (5,18)
 AND
 (DATE(CONCAT(pr.visitDateYy,?,pr.visitDateMm,?,pr.visitDateDd)) > DATE(CONCAT(pr.dispDateYy,?,pr.dispDateMm,?,pr.dispDateDd))
 OR
 DATEDIFF(DATE(CONCAT(pr.dispDateYy,?,pr.dispDateMm,?,pr.dispDateDd)),DATE(CONCAT(pr.visitDateYy,?,pr.visitDateMm,?,pr.visitDateDd))) >= 30)
 GROUP BY 1)p',array ('A17N','-','-','-','-','-','-','-','-')); 
 
 
 database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (
 
 
   SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id
 FROM encValidAll e,  labs l
 WHERE   
  l.patientID =  e.patientID
 AND e.encounterType IN (5,18)
 AND
  (DATE(CONCAT(l.visitDateYy,?,l.visitDateMm,?,l.visitDateDd)) > DATE(CONCAT(l.resultDateYy,?,l.resultDateMm,?,l.resultDateDd))
  OR
DATEDIFF(DATE(CONCAT(l.resultDateYy,?,l.resultDateMm,?,l.resultDateDd)),DATE(CONCAT(l.visitDateYy,?,l.visitDateMm,?,l.visitDateDd))) > 30)
 GROUP BY 1)p',array ('A18N','-','-','-','-','-','-','-','-')); 
 
 
 database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (
 
 SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id 
 FROM encValidAll e,  labs l
 WHERE   
  l.patientID =  e.patientID
 AND e.encounterType IN (6,13,19)
 AND l.labID = 176
 AND (l.result IS NOT NULL OR l.result2 IS NOT NULL)
 GROUP BY 1)p',array ('A20D'));
 

database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (
 

  SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id
 FROM encValidAll e,  labs l
 WHERE   
  l.patientID =  e.patientID
 AND e.encounterType IN (6,13,19)
 AND l.labID = 176
 AND ((l.result <= 0 OR l.result > 2000)
 OR
 (l.result2 < 0 OR l.result2 >68))
 GROUP BY 1)p',array ('A20N'));

 
   database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (
 

  SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id 
 FROM encValidAll e, obs o, patient p
 WHERE  o.location_id = e.siteCode
 AND o.encounter_id = e.encounter_id
 AND e.patientID = p.patientID
 AND  DATEDIFF(e.createDate, DATE(CONCAT(e.visitDateYy,?,visitDateMm,?,visitDateDd))) >= 3
 GROUP BY 1)p',array ('t1N','-','-'));
  
 database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (


SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id
FROM encValidAll e, obs o, patient p, concept c
WHERE o.location_id = e.siteCode
AND o.encounter_id = e.encounter_id
AND e.patientID = p.patientID
AND o.concept_id = c.concept_id
AND e.encounterType IN (1,2,27,28)
AND ((o.concept_id IN (70189,70378)
AND p.sex = 1)
OR
(o.concept_id IN (70194, 70590,70010,70190,70192,70196,70377,70154, 70155,70079)
AND p.sex = 2))
GROUP BY 1)p',array ('A8N'));


database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (

SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id
FROM encValidAll e, obs o
WHERE o.location_id = e.siteCode
AND o.encounter_id = e.encounter_id
AND e.encounterType IN (6,13,19)
GROUP BY 1)p',array ('A9D'));


database()->query('INSERT INTO ' . $GLOBALS['tempTableNames'][1] . ' (patientID, maxDate, concept_id) SELECT * FROM (

SELECT e.patientID, MAX(DATE(e.visitDate)) AS maxDate, ? AS concept_id
FROM encValidAll e, obs o, patient p, concept c
WHERE o.encounter_id = e.encounter_id
AND e.patientID = p.patientID
AND o.concept_id = c.concept_id
AND e.encounterType IN (6,13,19)
AND ((o.concept_id IN (70074)
AND p.sex = 1)
OR
(o.concept_id IN (70399,70400,7073,70073)
AND p.sex = 2))
GROUP BY 1)p',array ('A9N'));


database()->query('
INSERT INTO  dw_dataquality_snapshot
SELECT patientID, maxDate AS visiteDate,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS A1D,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS A1N,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS A2N,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS A3N,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS A4N,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS A5N,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS A6N,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS A7N,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS A7D,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS A8N,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS A8D,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS A9N,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS A9D,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS A10N,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS A10D,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS A11D,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS A11N,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS A12N,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS A13N,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS A14D,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS A14N,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS A15N,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS A16N,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS A17N,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS A18N,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS A18D,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS A19N,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS A19D,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS A20D,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS A20N,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS C1D,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS C1N,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS C2D,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS C2N,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS C3D,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS C3N,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS C4D,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS C4N,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS C5D,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS C5N,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS C6D,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS C6N,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS C7D,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS C7N,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS C8D,
COUNT(CASE WHEN concept_id =  ? THEN patientID ELSE NULL END) AS C8N,
COUNT(CASE WHEN concept_id =  ? THEN patientID ELSE NULL END) AS C9D,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS C9N,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS C10D,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS C10N,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS C11N,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS C11D,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS C12N,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS C12D,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS C13N,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS C13D,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS C14N,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS C14D,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS C15N,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS C15D,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS C16N,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS C16D,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS C17N,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS C17D,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS C18N,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS C18D,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS C19N,
COUNT(CASE WHEN concept_id =  ? THEN patientID ELSE NULL END) AS C19D,
COUNT(CASE WHEN concept_id = ? THEN patientID ELSE NULL END) AS t1N
FROM ' . $GLOBALS['tempTableNames'][1] . '
GROUP BY patientID, maxDate', array('A1D','A1N', 'A2N','A3N','A4N','A5N','A6N','A7N','A7D','A8N','A8D','A9N','A9D','A10N','A10D','A11D','A11N','A12N','A13N','A14D','A14N','A15N' ,'A16N','A17N','A18N','A18D','A19N','A19D','A20D',
'A20N','C1D','C1N','C2D','C2N','C3D','C3N','C4D','C4N','C5D','C5N','C6D',
'C6N','C7D','C7N','C8D','C8N','C9D','C9N','C10D','C10N','C11N','C11D','C12N','C12D','C13N','C13D','C14N','C14D','C15N','C15D','C16N','C16D','C17N','C17D','C18N','C18D','C19N','C19D','t1N'));

}

function dataqualitySlices($key, $orgType, $time_period) { 
/*

*/	


$indicatorQueries = array (
   // Denominators
    -1 => array(0, "where C1D > 0", NULL), 
    -2 => array(0, "where C2D > 0", NULL), 
    -3 => array(0, "where C3D > 0", NULL),
    -4 => array(0, "where C4D > 0", NULL),   
    -5 => array(0, "where C5D > 0", NULL),
    -6 => array(0, "where C6D > 0", NULL),   
    -7 => array(0, "where C7D > 0", NULL),
/*	-8 => array(0, "where C8D > 0", NULL),
	-9 => array(0, "where C9D > 0", NULL),*/
	-10 => array(0, "where C10D > 0", NULL),
	/*-11 => array(0, "where C11D > 0", NULL),
	-12 => array(0, "where C12D > 0", NULL),*/
	-13 => array(0, "where C13D > 0", NULL),
	-14=> array(0, "where C14D > 0", NULL),
	-15 => array(0, "where C15D > 0", NULL),
	/*-16 => array(0, "where C16D > 0", NULL),*/
	-17 => array(0, "where C17D > 0", NULL),
	/*-18 => array(0, "where C18D > 0", NULL),*/
	-19 => array(0, "where C19D > 0", NULL),
	-20 => array(0, "where A1D > 0", NULL),
	-21 => array(0, "where A7D > 0", NULL),
	-22 => array(0, "where A8D > 0", NULL),
	-23 => array(0, "where A9D > 0", NULL),
	-24 => array(0, "where A11D > 0", NULL),
	-25 => array(0, "where A14D > 0", NULL),
	-26 => array(0, "where A18D > 0", NULL),
	-27 => array(0, "where A19D > 0", NULL),
	-28 => array(0, "where A20D > 0", NULL),
	-29 => array(0, "where A7D + A9D = 2", NULL),
	-30 => array(0, "where C4D + C5D = 1", NULL),
    // C 
    1 => array(1, " where C1N = 1", array(-1)), 
    2 => array(1, " where C2N = 1", array(-2)),
    3 => array(1, " where C3N = 1", array(-3)),
    4 => array(1, " where C4N = 1", array(-4)),
    5 => array(1, " where C5N = 1", array(-5)),       
    6 => array(1, " where C4N + C5N = 1", array(-30)), 
    7 => array(1, " where C7N = 1", array(-7)),
    8 => array(1, " where C8N = 1", array(-5) ),
    9 => array(1, " where C9N = 1", array(-6)),
   10 => array(1, " where C10N = 1", array(-10)),
    11 => array(1, " where C11N = 1", array(-1)), 
    12 => array(1, " where C12N = 1", array(-12)),
    13 => array(1, " where C13N = 1", array(-13)),
    14 => array(1, " where C14N = 1", array(-14)),
   15 => array(1, " where C15N = 1", array(-15)),
   16  => array(1, " where C14N + C15N= 1", array(-13)),
   17 => array(1, " where C17N = 1", array(-17)),
   18  => array(1, " where C18N = 1", array(-17)),
   19 => array(1, " where C19N = 1", array(-19)),
   // A
   20 => array(1, " where A1N = 1",    array(-20)), 
    21 => array(1, " where A2N = 1",   array(-20)),
    22 => array(1, " where A3N = 1",   array(-20)),
    23 => array(1, " where A4N = 1",   array(-20)),
    24 => array(1, " where A5N = 1",   array(-20)),       
    25 => array(1, " where A6N = 1",   array(-20)), 
    26 => array(1, " where A7N = 1",   array(-21)),
    27 => array(1, " where A8N = 1",   array(-22)),
    28 => array(1, " where A9N = 1",   array(-23)),
   29 => array(1, " where A10N = 1",   array(-29)),
    30 => array(1, " where A11N = 1",  array(-24)), 
    31 => array(1, " where A12N = 1",  array(-24)),
    32 => array(1, " where A13N = 1",  array(-24)),
    33 => array(1, " where A14N = 1",  array(-25)),
   34 => array(1, " where A15N = 1",   array(-25)),
   35  => array(1, " where A16N = 1",  array(-25)),
   36 => array(1, " where A17N = 1",   array(-24)),
   37  => array(1, " where A18N = 1",  array(-26)),
   38 => array(1, " where A19N = 1",   array(-27)),
   39 => array(1, " where A20N = 1",   array(-28)),
   // t 
   40 => array(1, "where t1N = 1",     array(-25)), 

);
	
	if (DEBUG_FLAG) echo "<br>Generate Patient Lists start: " . date('h:i:s') . "<br>";
	// store the patientid lists; don't need any reference to org, since pid contains site info

	foreach ($indicatorQueries as $indicator => $query) {
		foreach ($time_period as $period) {
			if ($period == "Week") $period_value = $period . "(s.visitdate,2) ";
			else $period_value = $period . "(s.visitdate) ";
			if (!is_array($query[1])) {
				$sql = "insert into dw_dataquality_patients select distinct " . $indicator . ",?, year(s.visitdate), " . $period_value . ", s.patientid from dw_dataquality_snapshot s " . $query[1]; 
				$rc = database()->query($sql,array($period))->rowCount();
				if (DEBUG_FLAG) echo "<br>Generate Pid List for: " . $indicator . " :" . $sql . " Rows inserted: " . $rc . "<br>"; 
			} else { 
				// anytime $query[1] isn't simple, previous calculations can be used
				generatePidLists("dataquality", $indicator, $query[1], $period);
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
				$sql = "insert into dw_dataquality_slices select ?, " . $org_value . ", " . $indicator . ", time_period, year, period, t.sex, count(distinct p.patientid), 0 from dw_dataquality_patients p, patient t";
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
				generatePercents('dataquality', $indicator, $org_unit, $org_value, $query);
				break;
			case 2: // this among that
				generateAmongSlices("dataquality", $indicator, $org_unit, $org_value, $query);
				break;
			}
		} 
	}  
	if (DEBUG_FLAG) echo "<br>Indicator slices end: " . date('h:i:s') . "<br>";
}  


?>