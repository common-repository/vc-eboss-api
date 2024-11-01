<?php namespace eBossApi\Controllers\admin;

use Herbert\Framework\Http;

class clientController
{
    public function pageClient(Http $http)
    {
        // $current_page    = (get_query_var('paged'))?get_query_var('paged')-1:0;
        $current_page = ($http->get('paged')) ? $http->get('paged') - 1 : 0;
        $result_per_page = 6;

        $params = array(
            // 'sortname'  => 'name',
            'is_active' => 1,
            // 'sortorder' => 'desc',
            'page' => $current_page,
            'rp' => $result_per_page,
        );

        $clients = \eBossApi\ListEntity::getClientList($params);

        $big = 999999999;
        $translated = __('Page', 'eBossApi');

        $args = array(
            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format' => 'page/%#%',
            'current' => max(1, $http->get('paged')),
            'total' => ($clients['total'] / $result_per_page) + 1,
            'show_all' => true,
            'type' => 'array',
            'before_page_number' => '<span class="screen-reader-text sr-only">' . $translated . ' </span>',
        );

        $paginate = paginate_links($args);

        return view('@eBossApiAdmin/listClients.twig', [
            'clients' => $clients['result'],
            'paginate' => $paginate
        ]);
    }

}
