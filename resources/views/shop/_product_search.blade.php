<product-search
query="{{ Request::get('query') }}"
key="{{ $searchKey }}"
app-id="{{ config('searchindex.algolia.application-id') }}"
index-name="{{ config('searchindex.algolia.defaultIndexName') }}"
></product-search>
