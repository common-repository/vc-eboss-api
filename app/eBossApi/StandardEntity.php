<?php namespace eBossApi;

/**
 *
 * @see http://demo.api.recruits-online.com/#page:activity
 * @Author: ebossrecruitment.com
 * @Date: 11/12/14
 * @Time: 2:36 PM
 */

class StandardEntity extends eBossApiClass
{

    /**
     *
     * @see http://demo.api.recruits-online.com/#page:candidate-info
     * @param array $params
     * @return mixed
     */
    public static function createCandidateInfo($params = array())
    {
        $client = new self();
        $client->setEndpoint('/candidate_info/edit/');
        $result = $client->postRequest(null, $params);
        return json_decode($result, true);
    }


    /**
     *
     * @see http://demo.api.recruits-online.com/#page:xstatus
     * @param array $params
     * @return mixed
     */

    public static function createXstatus($params = array())
    {
        $client = new self();
        $client->setEndpoint('/xstatus/edit/');
        $result = $client->postRequest(null, $params);
        return json_decode($result, true);
    }

    /**
     *
     * Create Candidate
     * @see http://demo.api.recruits-online.com/#page:candidate,header:candidate-create-candidate
     * @param array $params
     *
     * @return mixed
     */
    public static function registerCandidate($params = array())
    {
        $client = new self();
        $client->setEndpoint('/candidate/edit/');
        $result = $client->postRequest($client->getUri(), $params);
        return json_decode($result, true);
    }


    /**
     * get jobs
     * @param array $params
     *
     * @return mixed
     */
    public static function getJobs($params = array())
    {
        if ($params['salary']) {
            $range = explode('-', $params['salary']);
            if (sizeof($range) == 2) {
                $params['field'] = 'salary';
                $params['l'] = (int)$range[0];
                $params['h'] = (int)$range[1];
                unset($params['salary']);
            } else {
                $params['salary'] = (int)$range[0];
            }
        }

        if (!isset($params['rp'])) {
            $params['rp'] = self::DEFAULT_RESULT_PER_PAGE;
        }

        $client = new self();
        $client->setEndpoint('/job/list/');
        $client->setParams($params);

        return $client->getResult();
    }

    /**
     *
     * get job type
     * @param array $params
     *
     * @return mixed
     */
    public static function getJobType($params = array())
    {
        if (!isset($params['sortname'])) {
            $params['sortname'] = 'name';
        }

        if (!isset($params['sortorder'])) {
            $params['sortorder'] = 'asc';
        }

        if (!isset($params['rp'])) {
            $params['rp'] = self::DEFAULT_RESULT_PER_PAGE;
        }

        $client = new self();
        $client->setEndpoint('/job_type/list/');
        $client->setParams($params);

        return $client->getResult();
    }

    /**
     *
     * @see http://demo.api.recruits-online.com/#page:candidate-info
     * @param array $params
     * @return mixed
     */
    public static function registerCompany($params = array())
    {
        $client = new self();
        $client->setEndpoint('/companies/edit/');
        $result = $client->postRequest(null, $params);
        return json_decode($result, true);
    }

}
