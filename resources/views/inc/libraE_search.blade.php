

@if (session('libraE_search'))
<div class="alert alert-info animated zoomIn row m-1 mt-2 p-1" role="alert">
  <b>
    <i class="fas fa-search"></i>&nbsp;Searching For:&nbsp;"{{session()->pull('libraE_search')}}"
    &nbsp; Found: {{session()->pull('libraE_search_count')}} data.
  </b>
</div>
@endif
