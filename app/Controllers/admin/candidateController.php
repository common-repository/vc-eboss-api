<?php namespace eBossApi\Controllers\admin;

use Herbert\Framework\Http;

class candidateController
{
    public function pageCandidate(Http $http)
    {
        $cand = array();
        $current_page = ($http->get('paged')) ? $http->get('paged') - 1 : 0;
        $result_per_page = 6;

        $params = array(
            'sortname' => 'first_name',
            'is_active' => 1,
            'sortorder' => 'desc',
            'page' => $current_page,
            'rp' => $result_per_page,
        );

        $candidates = \eBossApi\ListEntity::getCandidateList($params);
        $countries = \eBossApi\ListEntity::getCountry()['result'];
        $regions = \eBossApi\ListEntity::getRegion()['result'];

        foreach ($candidates['result'] as $candidate) {
            $cand[$candidate['id']] = $candidate;
            $cand[$candidate['id']]['country_id'] = \eBossApi\ListEntity::getCountry(array(
                'is_active' => 1,
                'id' => $candidate['country_id']
            ))['result'][0]['name'];
            $cand[$candidate['id']]['region_id'] = \eBossApi\ListEntity::getRegion(array(
                'is_active' => 1,
                'id' => $candidate['region_id']
            ))['result'][0]['name'];
        }

        $big = 999999999;
        $translated = __('Page', 'eBossApi');

        $args = array(
            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format' => 'page/%#%',
            'current' => max(1, $http->get('paged')),
            'total' => ($candidates['total'] / $result_per_page) + 1,
            'show_all' => true,
            'type' => 'array',
            'before_page_number' => '<span class="screen-reader-text sr-only">' . $translated . ' </span>'
        );

        $paginate = paginate_links($args);

        return view('@eBossApiAdmin/listCandidates.twig', [
            'candidates' => $cand,
            'paginate' => $paginate
        ]);
    }
}
