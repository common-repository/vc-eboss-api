<?php namespace eBossApi;

class JobSearch
{

    /**
     * Search jobs through keywords
     * @param array $params
     *
     * @return array|mixed
     * @author
     **/
    public static function getKeywordSearchResult($params = array())
    {
        $keyword = trim($params['keyword']);

        if (empty($keyword)) {
            return array();
        }

        unset($params['keyword']);

        $client = new StandardEntity();

        foreach (array('job_title', 'salary', 'country', 'region', 'industry', 'skill', 'salary', 'details') as $list) {
            $data = $client->processCustomCall(self::generateSearchQuery(array($list => $keyword),
                array('isKeywordSearch' => 1)));

            if (isset($data['result'])) {
                return $data['result'];
            }
        }

        return array();
    }

    /**
     * generate sql statement query based on parameters given
     * @param array $params
     * @param array $options
     *
     * @return string
     */
    public static function generateSearchQuery($params = array(), $options = array())
    {
        $query = " SELECT  job.*, link_job2region.*,  list_region.*, list_country.* , link_job2industry.*,link_job2job_title.*, list_job_title.*
					FROM job
					LEFT JOIN link_job2region ON (link_job2region.job_id = job.id)
					LEFT JOIN link_job2industry ON (link_job2industry.job_id = job.id)
					LEFT JOIN link_job2skill1 ON (link_job2skill1.job_id = job.id)

					LEFT JOIN  list_region ON (list_region.id = link_job2region.region_id)
					LEFT JOIN list_country ON (list_country.id = list_region.country_id)
					LEFT JOIN list_skill1 ON (link_job2skill1.skill1_id = list_skill1.id)
					LEFT JOIN list_industry ON (link_job2industry.industry_id = list_industry.id )
					LEFT JOIN link_job2job_title ON (link_job2job_title.job_id = job.id )
					LEFT JOIN list_job_title ON (list_job_title.industry_id = list_industry.id )
		";

        $criteria = 'WHERE 1 = 1';

        if (isset($params['job_title']) && !empty($params['job_title'])) {
            $criteria .= ' AND job.job_title LIKE "%' . $params['job_title'] . '%" OR list_job_title.name LIKE "%' . $params['job_title'] . '% "';
        }

        if (isset($params['country']) && !empty($params['country'])) {
            if (isset($options['isKeywordSearch']) && $options['isKeywordSearch']) {
                $criteria .= ' AND list_country.name LIKE "%' . $params['country'] . '%"';
            } else {
                $criteria .= ' AND list_country.id =' . $params['country'];
            }
        }

        if (isset($params['region']) && !empty($params['region'])) {
            if (isset($options['isKeywordSearch']) && $options['isKeywordSearch']) {
                $criteria .= ' AND list_region.name LIKE "%' . $params['region'] . '%"';
            } else {
                $criteria .= ' AND list_region.id = ' . $params['region'];
            }
        }

        if (isset($params['industry']) && !empty($params['industry'])) {
            if (isset($options['isKeywordSearch']) && $options['isKeywordSearch']) {
                $criteria .= ' AND list_industry.name LIKE "%' . $params['industry'] . '%"';
            } else {
                $criteria .= ' AND link_job2region.region_id = ' . $params['industry'];
            }
        }

        if (isset($params['skill']) && !empty($params['skill'])) {
            if (isset($options['isKeywordSearch']) && $options['isKeywordSearch']) {
                $criteria .= ' AND list_skill1.name LIKE "%' . $params['skill'] . '%"';
            } else {
                $criteria .= ' AND list_skill1.id = ' . $params['skill'];
            }
        }

        if (isset($params['salary']) && !empty($params['salary'])) {
            $range = explode('-', $params['salary']);
            if (sizeof($range) == 2) {
                $criteria .= ' AND job.salary BETWEEN ' . $range[0] . ' AND ' . $range[1];
            } else {
                $criteria .= ' AND job.salary = ' . $range[0];
            }
        }

        if (isset($params['coordinates']) && !empty($params['coordinates'])) {
            if (isset($params['coordinates']['minLat']) && isset($params['coordinates']['maxLat'])) {
                $criteria .= ' AND job.latitude BETWEEN  ' . $params['coordinates']['minLat'] . ' AND ' . $params['coordinates']['maxLat'];
            }
            if (isset($params['coordinates']['minLong']) && isset($params['coordinates']['maxLong'])) {
                $criteria .= ' AND job.longitude BETWEEN ' . $params['coordinates']['minLong'] . ' AND ' . $params['coordinates']['maxLong'];
            }
        }

        if (isset($params['details']) && !empty($params['details'])) {
            $criteria .= ' AND job.detail LIKE "% ' . $params['details'] . ' %"';
        }

        $query .= $criteria;

        return $query;
    }

    public static function getKeywordRegionSearchResult($params = array())
    {
        $keyword = trim($params['keyword']);

        if (empty($keyword)) {
            return array();
        }

        unset($params['keyword']);

        $client = new StandardEntity();

        foreach (array('job_title', 'salary', 'country', 'region', 'industry', 'skill', 'salary', 'details') as $list) {
            $data = $client->processCustomCall(self::generateSearchQuery(array($list => $keyword),
                array('isKeywordSearch' => 1)));

            if (isset($data['result'])) {
                return $data['result'];
            }
        }

        return array();
    }

    /**
     * get basic search results
     *
     * @param array $params
     *
     * @return array
     */
    public static function getBasicSearchResult($params = array())
    {
        $client = new StandardEntity();
        $data = $client->processCustomCall(self::generateSearchQuery($params));

        if (isset($data['result'])) {
            return $data['result'];
        }

        return array();
    }

    /**
     * Get Radius Search
     *
     * @param array $params
     *
     * @return array
     * @author
     **/
    public static function getRadiusSearchResult($params = array())
    {

        if ((!isset($params['postal-code']) && !$params['postal-code'])
            || (!isset($params['mile-radius']) && !$params['mile-radius'])) {
            return array();
        }

        $coordinates = self::getCoordinatesByPostCode($params['postal-code']);
        $getMaxCoordinates = self::getMileRadius($coordinates['lat'], $coordinates['lat'], $params['mile-radius']);

        $client = new StandardEntity();
        $data = $client->processCustomCall(self::generateSearchQuery(array('coordinates' => $getMaxCoordinates)));

        if ($data['result']) {
            $radiusDistance = (float)$params['mile-radius'];

            foreach ($data['result'] as $key => $item) {
                $resultDistance = self::computeRadiusDistance($coordinates['lat'], $coordinates['long'],
                    $item['job.latitude'], $item['job.longitude']);

                if ($resultDistance > $radiusDistance) {
                    unset($data['result'][$key]);
                }
            }
            return $data['result'];
        }
        return array();
    }

    /**
     * forward geocoding
     * @see https://www.geocodefarm.com
     * @param int $postCode
     *
     * @return array
     */
    public static function getCoordinatesByPostCode($postCode = 0)
    {
        $geocodeFarmUrl = 'http://www.geocodefarm.com/api/forward/json/999e26fa6d65e8807d5bc8dc78457a9fb74855fa/';

        $codes = json_decode(file_get_contents($geocodeFarmUrl . $postCode));

        if (isset($codes->geocoding_results->COORDINATES)) {
            return array(
                'lat' => isset($codes->geocoding_results->COORDINATES->latitude) ? $codes->geocoding_results->COORDINATES->latitude : 0,
                'long' => isset($codes->geocoding_results->COORDINATES->longitude) ? $codes->geocoding_results->COORDINATES->longitude : 0
            );
        }
        return array('lat' => 0, 'long' => 0);
    }

    /**
     *
     * Get radius coordinates by mile
     *
     * @see http://stackoverflow.com/questions/8135243/finding-towns-within-a-10-mile-radius-of-postcode-google-maps-api
     *
     * @param int $latitude
     * @param int $longitude
     * @param int $distance
     *
     * @return array
     */
    public static function getMileRadius($latitude = 0, $longitude = 0, $distance = 0)
    {
        $radius = 3958.761; //radius in miles (6371.009 in kilometers)


        // latitude boundaries
        $maxLat = (float)$latitude + rad2deg((float)$distance / $radius);
        $minLat = (float)$latitude - rad2deg((float)$distance / $radius);

        // longitude boundaries (longitude gets smaller when latitude increases)
        $maxLng = (float)$longitude + rad2deg((float)$distance / $radius / cos(deg2rad((float)$latitude)));
        $minLng = (float)$longitude - rad2deg((float)$distance / $radius / cos(deg2rad((float)$latitude)));

        return array(
            'minLat' => $minLat,
            'maxLat' => $maxLat,
            'minLong' => $minLng,
            'naxLong' => $maxLng
        );
    }

    /**
     *
     * Compute job coordinates against the mile radius coordinates
     * this will remove coordinates that is not within radius
     *
     * @param $lat1
     * @param $lng1
     * @param $lat2
     * @param $lng2
     *
     * @return float
     */
    public static function computeRadiusDistance($lat1, $lng1, $lat2, $lng2)
    {
        // convert latitude/longitude degrees for both coordinates
        // to radians: radian = degree * π / 100
        $lat1 = deg2rad($lat1);
        $lng1 = deg2rad($lng1);
        $lat2 = deg2rad($lat2);
        $lng2 = deg2rad($lng2);

        //calculate great-circle distance
        $distance = acos(sin($lat1) * sin($lat2) + cos($lat1) * cos($lat2) * cos($lng1 - $lng2));

        //distance in human-readable format:
        //earth's radius in km = ~6371 ( 3958.761 miles )
        return 3958.761 * $distance;
    }

}
