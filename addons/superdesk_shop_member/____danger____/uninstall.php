<?php




$sql = "


DROP TABLE 
`ims_business_dongyuantangguoyiguang_department`, 
`ims_business_dongyuantangguoyiguang_doctor_automated`, 
`ims_business_dongyuantangguoyiguang_doctor_doc`, 
`ims_business_dongyuantangguoyiguang_patient_appointment`, 
`ims_business_dongyuantangguoyiguang_patient_diagnosis`, 
`ims_business_dongyuantangguoyiguang_patient_doc`;
    
        
";
pdo_run($sql);


?>