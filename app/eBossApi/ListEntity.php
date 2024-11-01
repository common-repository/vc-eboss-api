<?php namespace eBossApi;

/**
 *
 * @Author: ebossrecruitment.com
 * @Date: 11/12/14
 * @Time: 2:27 PM
 */
class ListEntity extends eBossApiClass
{

    /**
     * Get country list
     * @param array $params
     * @return mixed
     */
    public static function getCountry($params = array())
    {
        $params = self::getDefaultParams($params);

        $client = new self();
        $client->setEndpoint('/list_country/list/');
        $client->setParams($params);
        return $client->getResult();
    }

    /**
     *
     * sortname - field to sort
     * sortorder - order of sort
     *   asc = ascending
     *   desc = descending
     * rp = mysql limit or report per page
     *
     * @param array $params
     *
     * @return array
     */
    public static function getDefaultParams($params = array())
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

        return $params;
    }

    /**
     * Get Industry list
     *
     * @param array $params
     * @return mixed
     */
    public static function getIndustry($params = array())
    {
        $params = self::getDefaultParams($params);

        $client = new self();
        $client->setEndpoint('/list_industry/list/');
        $client->setParams($params);
        return $client->getResult();
    }

    /**
     * Get region list
     * @param array $params
     *
     * @return mixed
     */
    public static function getRegion($params = array())
    {
        $params = self::getDefaultParams($params);

        $client = new self();
        $client->setEndpoint('/list_region/list/');
        $client->setParams($params);
        return $client->getResult();
    }

    /**
     * Get Job Title list
     *
     * @param array $params
     * @return mixed
     */
    public static function getJobTitle($params = array())
    {
        $params = self::getDefaultParams($params);

        $client = new self();
        $client->setEndpoint('/list_job_title/list/');
        $client->setParams($params);
        return $client->getResult();
    }

    /**
     * Get skill 1 list
     * @param array $params
     *
     * @return mixed
     */
    public static function getSkill1($params = array())
    {
        $params = self::getDefaultParams($params);

        $client = new self();
        $client->setEndpoint('/list_skill1/list/');
        $client->setParams($params);
        return $client->getResult();
    }

    /**
     * Get all jobs list
     *
     * @param array $params
     *
     * @return mixed
     */
    public static function getJobList($params = array())
    {
        $params = self::getDefaultParams($params);

        $client = new self();
        $client->setEndpoint('/job/list/');
        $client->setParams($params);
        return $client->getResult();
    }

    /**
     * get Job Type List
     *
     *
     * @return void
     * @author Vinay Shah
     **/
    public static function getJobTypeList($params = array())
    {
        $params = self::getDefaultParams($params);

        $client = new self();
        $client->setEndpoint('/job_type/list/');
        $client->setParams($params);
        return $client->getResult();
    }

    public static function getCandidateList($params = array())
    {
        $params = self::getDefaultParams($params);

        $client = new self();
        $client->setEndpoint('/candidate/list/');
        $client->setParams($params);

        return $client->getResult();
    }

    public static function getClientList($params = array())
    {
        $params = self::getDefaultParams($params);

        $client = new self();
        $client->setEndpoint('/client_contact/list/');
        $client->setParams($params);

        return $client->getResult();
    }

    public static function getSalutation($params = array())
    {
        $params - self::getDefaultParams($params);

        $client = new self();
        $client->setEndpoint('/list_salutation/list');
        $client->setParams($params);
        return $client->getResult();
    }
}
