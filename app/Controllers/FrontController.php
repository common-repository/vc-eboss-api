<?php namespace eBossApi\Controllers;

use Herbert\Framework\Http;

/**
 * FrontEnd Controller for shortcodes
 */
class FrontController
{
    protected $options;

    protected $resultPage;
    protected $uploadCV;
    protected $assetsUrl;

    protected $applicationPage;
    protected $cvparsePage;
    protected $thankyouPage;

    public function __construct()
    {
        $this->getWPPages();
        add_action('wp_ajax_ajaxRegionAction', array($this, 'ajaxRegionAction'));
        add_action('wp_ajax_nopriv_ajaxRegionAction', array($this, 'ajaxRegionAction'));
        wp_localize_script('ajax-script', 'ajax_object',
            array('ajaxurl' => admin_url('admin-ajax.php')));// setting ajaxurl
    }

    public function getWPPages()
    {
        $this->options = get_option('eBossApi');
        $this->resultPage = get_permalink($this->options['eBossApi_resultPage']);
        $this->uploadCV = get_permalink($this->options['eBossApi_uploadCV']);
        $this->assetsUrl = \eBossApi\Helper::assetUrl('images/ajax-loader.gif');

        $this->applicationPage = get_permalink(165);
        $this->cvparsePage = get_permalink(27);
        $this->thankyouPage = get_permalink(169);
    }

    /**
     * @TODO : ajaxHandling for country region
     */
    public function ajaxRegionAction(Http $http)
    {
        global $wpdb;

        if ($http->has('country_id')) {
            $regions = \eBossApi\ListEntity::getRegion(array(
                'country_id' => $http->get('country_id'),
                'is_active' => 1
            ));
            wp_send_json($regions);
        }
        wp_die();
    }

    public function basicSearch(Http $http)
    {

        $jobTitles = \eBossApi\ListEntity::getJobTitle(array('is_active' => 1))['result'];
        $industries = \eBossApi\ListEntity::getIndustry(array('is_active' => 1))['result'];
        $salaryRange = \eBossApi\Helper::get('salaryRange');
        $countries = \eBossApi\ListEntity::getCountry(array('is_active' => 1))['result'];
        $regions = \eBossApi\ListEntity::getRegion(array('country_id' => 216))['result'];

        return view('@eBossApiFront/basicSearch.twig', [
            'jobTitles' => $jobTitles,
            'industries' => $industries,
            'salaryRange' => $salaryRange,
            'countries' => $countries,
            'regions' => $regions,
            'assetsUrl' => $this->assetsUrl,
            'resultPage' => $this->resultPage,
            'ajaxURL' => $http->root() . '/region'
        ]);
    }

    public function radiusSearch()
    {

        return view('@eBossApiFront/radiusSearch.twig', [
            'assetsUrl' => $this->assetsUrl,
            'resultPage' => $this->resultPage
        ]);
    }

    public function singleSearch()
    {

        return view('@eBossApiFront/singleSearch.twig', [
            'assetsUrl' => $this->assetsUrl,
            'resultPage' => $this->resultPage,
        ]);
    }

    public function registerCV()
    {

        return view('@eBossApiFront/registerCV.twig', [
            'assetsUrl' => $this->assetsUrl,
            'resultPage' => $this->resultPage
        ]);
    }

    public function register()
    {
        $jobTitles = \eBossApi\ListEntity::getJobTitle()['result'];
        $industries = \eBossApi\ListEntity::getIndustry()['result'];
        $salaryRange = \eBossApi\Helper::get('salaryRange');
        $countries = \eBossApi\ListEntity::getCountry()['result'];
        $regions = \eBossApi\ListEntity::getRegion()['result'];

        return view('@eBossApiFront/register.twig', [
            'jobTitles' => $jobTitles,
            'industries' => $industries,
            'salaryRange' => $salaryRange,
            'countries' => $countries,
            'regions' => $regions,
            'assetsUrl' => $this->assetsUrl,
            'resultPage' => $this->resultPage
        ]);

    }

    public function cvParsePage(Http $http)
    {
        if ($http->exists(array('job_id', 'uid', 'cv'))) {
            if ($http->hasFile('cv')) {
                // print_r($http->file('cv')->getContents());
                // $data = base64_encode( file_get_contents( $_FILES['cv']['tmp_name'] ) );

                $eBossClient = new \eBossApi\eBossApiClass();
                $cv = $eBossClient->parseCv($data);

                if ($cv) {
                    $params = array(
                        'first_name' => ($cv['first_name']) ? $cv['first_name'] : '',
                        'surname' => ($cv['last_name']) ? $cv['last_name'] : '',
                        'email' => ($cv['email']) ? $cv['email'] : '',
                        'main_phone' => ($cv['phone']) ? $cv['phone'] : '',
                        'mobile_phone' => ($cv['mobile_phone']) ? $cv['mobile_phone'] : '',
                        'work_phone' => ($cv['work_phone']) ? $cv['work_phone'] : '',
                        'address1' => ($cv['address1']) ? $cv['address1'] : '',
                        'address2' => ($cv['address2']) ? $cv['address2'] : '',
                        'added_datetime' => date('Y-m-d'),
                        'created_from' => 'web',
                        'current_duties' => ($cv['employment_history']) ? addslashes($cv['employment_history']) : '',
                        'UCNMCNotes' => ($cv['education_history']) ? addslashes($cv['education_history']) : '',
                    );

                    $result = \eBossApi\StandardEntity::registerCandidate($params);

                }
            }
        }

        return view('@eBossApiFront/cvParse.twig', [
            'assetsUrl' => $this->assetsUrl,
            'resultPage' => $this->resultPage
        ]);
    }

    public function thankyouPage(Http $http)
    {
        if ($http) {

        }
    }

    /**
     * Job Application Page
     *
     * @return void
     * @author
     **/
    public function jobApplicationPage(Http $http)
    {
        // print_r(file_get_contents($_FILES['cv']['tmp_name']));
        if ($http->hasFile('cv')) {

            $data = base64_encode(file_get_contents($_FILES['cv']['tmp_name']));
            $eBossClient = new \eBossApi\eBossApiClass();
            $cv = $eBossClient->parseCV($data);

            //@note changes this what's on your server
            $upload_dir = wp_upload_dir();
            $baseTempFilePath = $upload_dir['path'];
            $filePath = trailingslashit($baseTempFilePath) . $_FILES['cv']['name'];

            $fileUpload = $http->file('cv')->move($baseTempFilePath, $http->file('cv')->getClientOriginalName());
        }

        return view('@eBossApiFront/jobApplication.twig', []);
    }

    /**
     * uploadCV Page controller
     *
     * @TODO upload and registering with CV
     * @return void
     * @author
     **/
    public function uploadcvPage(Http $http)
    {

        return view('@eBossApiFront/uploadCV.twig', [
            'uid' => $http->get('uid'),
            'id' => $http->get('id'),
            'assetsUrl' => $this->assetsUrl,
            'jobApplication' => $this->applicationPage,
            'cvparsePage' => $this->cvparsePage,
        ]);
    }

    /**
     * topJobs
     *
     * @return void
     * @author
     **/
    public function topJobs($rp)
    {
        $params = array(
            'sortname' => 'salary',
            'sortorder' => 'desc',
            'l' => 0,
            'o' => 'gt',
            'rp' => $rp,
        );
        $topJobs = \eBossApi\ListEntity::getJobList($params)['result'];

        return view('@eBossApiFront/topJobs.twig', [
            'topJobs' => $topJobs,
        ]);
    }

    /**
     * undocumented function
     *
     * @return void
     * @author
     **/
    public function pageJobListing()
    {
        global $wp_query;
        $current_page = (get_query_var('paged')) ? get_query_var('paged') - 1 : 0;
        $result_per_page = 6;

        $params = array(
            'sortname' => 'salary',
            'sortorder' => 'desc',
            'page' => $current_page,
            'rp' => $result_per_page,
        );
        $jobsList = \eBossApi\ListEntity::getJobList($params);

        $big = 999999999;
        $translated = __('Page', 'eBossApi');

        $args = array(
            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format' => 'page/%#%',
            'current' => max(1, get_query_var('paged')),
            'total' => ($jobsList['total'] / $result_per_page) + 1,
            'show_all' => true,
            'type' => 'array',
            'before_page_number' => '<span class="screen-reader-text sr-only">' . $translated . ' </span>'
        );

        $paginate = paginate_links($args);

        return view('@eBossApiFront/page_job_listing.twig', [
            'jobs' => $jobsList['result'],
            'uploadCV' => $this->uploadCV,
            'paginate' => $paginate
        ]);
    }

    public function getRegion(Http $http)
    {
        echo '<pre>';
        var_dump($http->get());
        //            json_encode();
    }

    /**
     * undocumented function
     *
     * @return void
     * @author
     **/
    public function shortcodeJobSearch()
    {

        $raw_countries = \eBossApi\ListEntity::getCountry()['result'];
        $regions = \eBossApi\ListEntity::getRegion()['result'];
        $industries = \eBossApi\ListEntity::getIndustry()['result'];

        $locations = array();
        $countries = array();

        foreach ($raw_countries as $country) {
            $locations[$country['id']] = $country;
        }

        foreach ($regions as $region) {
            if (isset($locations[$region['country_id']])) {
                $locations[$region['country_id']]['regions'][$region['id']] = $region;
            }
        }

        return view('@eBossApiFront/form_job_search.twig', [
            'locations' => $locations,
            'industries' => $industries,
            'assetsUrl' => $this->assetsUrl,
            'resultPage' => $this->resultPage
        ]);

    }

    /**
     * resultDisplay Page
     *
     * @TODO uploadCV remaining
     * @return view
     * @author VinayShah
     **/
    public function resultPage(Http $http)
    {
        $jobs = array();
        // print_r($http->all());
        if ($http->has('search-type')) {

            if ($http->get('search-type') === 'keyword-search') {
                $params['keyword'] = $http->get('keyword', '');
                $jobs = \eBossApi\JobSearch::getKeywordSearchResult($params);
            } elseif ($http->get('search-type') === 'radius-search') {
                $params = array(
                    'mile-radius' => $http->get('mile-radius', ''),
                    'postal-code' => $http->get('postal-code', '')
                );

                $jobs = \eBossApi\JobSearch::getRadiusSearchResult($params);
            } elseif ($http->get('search-type') === 'keyword-region-search') {
                $params = array(
                    'keyword' => $http->get('keyword', ''),
                    'region' => $http->get('region', ''),
                    'industry' => $http->get('industry', '')
                );

                $jobs = \eBossApi\JobSearch::getKeywordRegionSearchResult($params);
            } else {
                $params = array(
                    'job_title' => $http->get('job-title', ''),
                    'country' => $http->get('country', ''),
                    'region' => $http->get('region', ''),
                    'industry' => $http->get('industry', ''),
                    'skill' => $http->get('skill', ''),
                    'salary' => $http->get('salary', '')
                );

                $jobs = \eBossApi\JobSearch::getBasicSearchResult($params);
            }
        }

        return view('@eBossApiFront/results.twig', [
            'jobs' => $jobs,
            'uploadCV' => $this->uploadCV,
        ]);
    }

}
