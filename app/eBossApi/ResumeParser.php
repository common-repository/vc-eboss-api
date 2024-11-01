<?php namespace eBossApi;

class ResumeParser
{
    /**
     * Function to make parse a resume.
     * @author Abhishek <abhishek@siteonphp.com>
     * @param resource file
     * @return array of parsed data
     */
    public static function convertXmlToArray($xmlstr)
    {
        try {
            $stringData = str_replace('xmlns="http://ns.hr-xml.org/2006-02-28"', 'xmlns=""', $xmlstr);
            $stringData = str_replace('xsi:schemaLocation="http://ns.hr-xml.org/2006-02-28 Resume.xsd http://sovren.com/hr-xml/2006-02-28 SovrenResumeExtensions.xsd"',
                '', $stringData);
            $stringData = str_replace('<sov:', '<', $stringData);
            $stringData = str_replace('</sov:', '</', $stringData);
            $xml = new \SimpleXMLElement($stringData);
        } catch (Exception $e) {
            echo $e->libxml_get_errors();
            return false;
        }

        if (!empty($xml->StructuredXMLResume->ContactInfo->PersonName)) {
            $cv_data['first_name'] = (string)$xml->StructuredXMLResume->ContactInfo->PersonName->GivenName;
            $cv_data['last_name'] = (string)$xml->StructuredXMLResume->ContactInfo->PersonName->FamilyName;
        }

        //get education history
        if ($xml->StructuredXMLResume->EducationHistory) {
            $educationHistory = (array)$xml->StructuredXMLResume->EducationHistory;
            $education = array();
            foreach ($educationHistory['SchoolOrInstitution'] as $list) {
                $comments = (array)$list->Degree->Comments;
                $education[] = $comments[0];
            }
            $cv_data['education_history'] = implode(' <BR/> <BR/>', $education);
        }

        //get employment history
        if ($xml->StructuredXMLResume->EmploymentHistory) {
            $employmentHistory = (array)$xml->StructuredXMLResume->EmploymentHistory;
            $employment = array();

            foreach ($employmentHistory['EmployerOrg'] as $list) {
                $description = (array)$list->PositionHistory->Description;
                $employment [] = $description[0];
            }

            $cv_data['employment_history'] = implode(' <BR/> <BR/> ', $employment);
        }

        $contact = $xml->StructuredXMLResume->ContactInfo->ContactMethod;

        if ($contact) {
            foreach ($contact as $c) {

                if ($c->PostalAddress->CountryCode) {
                    $cv_data['country'] = (string)$c->PostalAddress->CountryCode;
                } // country
                if ($c->PostalAddress->Region) {
                    $cv_data['region'] = (string)$c->PostalAddress->Region; // region
                    $cv_data['city'] = (string)$c->PostalAddress->Municipality; // region
                }

                if ($c->PostalAddress->Municipality) {
                    $cv_data['city'] = (string)$c->PostalAddress->Municipality; // city
                }

                if ($c->PostalAddress->PostalCode) {
                    $cv_data['postcode'] = (string)$c->PostalAddress->PostalCode;
                }

                if ($c->PostalAddress->DeliveryAddress) {
                    $postalcode = $c->PostalAddress->DeliveryAddress;

                    foreach ($postalcode as $pc) {
                        $cv_data['address1'] = (string)$pc->AddressLine[0];
                        $cv_data['address2'] = (string)$pc->AddressLine[1];
                    }
                }

                if ($c->Telephone) {
                    $cv_data['phone'] = (string)$c->Telephone->FormattedNumber;
                }

                if ($c->Mobile) {
                    $cv_data['mobile_phone'] = (string)$c->Mobile->FormattedNumber;
                }

                if ((string)$c->Use == 'business') {
                    $cv_data['work_phone'] = (string)$c->Telephone->FormattedNumber;
                }

                if ((string)$c->Use == 'personal' and (string)$c->Location == 'onPerson') {
                    $cv_data['email'] = (string)$c->InternetEmailAddress;
                }
            }
        }

        $history = '';
        $ehistory = $xml->StructuredXMLResume->EmploymentHistory->EmployerOrg;
        if ($ehistory) {
            foreach ($ehistory as $e) {
                $history .= (string)$e->EmployerOrgName . "\n" . (string)$e->PositionHistory->Title . "\n" . (string)$e->PositionHistory->Description . "\n\n";
            }
        }

        $cv_data['UCCurrentDuties'] = $history;

        $qualify = $xml->StructuredXMLResume->Qualifications->QualificationSummary;
        $skillsFound = array();
        if (!empty($xml->StructuredXMLResume->Qualifications->Competency)) {

            foreach ($xml->StructuredXMLResume->Qualifications->Competency as $node) {
                $skillsFound[] = (string)$node['name'];
            }
        }

        $education = '';
        $eeducation = $xml->StructuredXMLResume->EducationHistory->SchoolOrInstitution;
        if ($eeducation) {
            foreach ($eeducation as $e) {
                $education .= (string)$e->School->SchoolName . "\n" . (string)$e->Degree->Comments . "\n\n";
            }
        }

        $cv_data['UCExperience'] = (string)$xml->StructuredXMLResume->Objective;
        $cv_data['UCNMCNotes'] = $qualify . "\n\n" . $education;
        $cv_data['UCKeyWord2'] = $stringData;

        if (!empty($xml->UserArea->ResumeUserArea->PersonalInformation)) {
            $cv_data['UCBirthDay'] = (string)$xml->UserArea->ResumeUserArea->PersonalInformation->DateOfBirth;
            $cv_data['UCWorkPermit'] = (string)$xml->UserArea->ResumeUserArea->PersonalInformation->VisaStatus;
            $cv_data['gender'] = (string)$xml->UserArea->ResumeUserArea->PersonalInformation->Gender;
            $cv_data['hourly_rate'] = (string)$xml->UserArea->ResumeUserArea->PersonalInformation->RequiredSalary;
            $cv_data['CurrentLocation'] = (string)$xml->UserArea->ResumeUserArea->PersonalInformation->CurrentLocation;
        }

        if (!empty($xml->StructuredXMLResume->ContactInfo->ContactMethod)) {
            $cv_data['CountryCode'] = (string)$xml->StructuredXMLResume->ContactInfo->ContactMethod->CountryCode;
        }

        return $cv_data;
    }


}
