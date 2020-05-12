@extends('layouts.appAdmin')

@section('content')

  @php
    session(['sidebar_nav' => 'file_maintenance']);     
    session(['sidebar_nav_lev_2' => 'file_maintenance_books_ul']); 
    session(['point_arrow' => 'categories']);
  @endphp

  <div class="row">
    <button onclick="toggleMenu()" class="btn btn-primary" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </button>

    <h3 class="ml-2 p-1 text-primary"><b>Categories</b></h3>
  </div>

  @include('inc.status')

  @include('inc.search')

  <div class="row mx-1 mt-4">

    <div class="col-6">

      <div class="row">
        <div class="col p-4 card bg-white box_form">

          <div class="row">
            <div class="col">
              <h5><b class="text-primary">All Categories (Dewey Decimal)</b></h5>
            </div>

            <div class="col-auto my-1">
              <a href="{{route('admin.file_maintenance.categories')}}" class="btn btn-sm btn-primary m-1 font-weight-bold">
                Refresh&nbsp;<i class="fas fa-sync-alt"></i>
              </a>
            </div>
          </div>

          <div class="row">
            <div class="col">

              <ul class="categories_ul">
                @foreach ( $categories_all['first'] as $first_cat )
                  @foreach( $first_cat as $f_cat )
                    <li>
                      <a 
                        data-toggle="collapse" 
                        href="#first_{{$f_cat->code}}" 
                        role="button" 
                        aria-expanded="false" 
                        aria-controls="#first_{{$f_cat->code}}"
                      >
                        <b>{{$f_cat->code}}</b>
                        :&nbsp;<span class="text-secondary">{{$f_cat->name}}</span> 
                      </a>

                       <ul class="categories_ul collapse" id="first_{{$f_cat->code}}">
                          @foreach( $categories_all['second'] as $second_cat )
                            @if ( $second_cat['parent_code'] == $f_cat->code )
                              @foreach( $second_cat['second_category'] as $s_cat )
                                <li>
                                  <a 
                                    data-toggle="collapse" 
                                    href="#second_{{$s_cat->code}}" 
                                    role="button" 
                                    aria-expanded="false" 
                                    aria-controls="#second_{{$s_cat->code}}"
                                  >
                                    <b>{{$s_cat->code}}</b>
                                    :&nbsp;<span class="text-secondary">{{$s_cat->name}}</span> 
                                  </a>

                                  <ul class="categories_ul collapse" id="second_{{$s_cat->code}}">
                                    @foreach( $categories_all['third'] as $third_cat )
                                      @if ( $third_cat['parent_code'] == $s_cat->code )
                                        @foreach( $third_cat['third_category'] as $t_cat )
                                          <li>
                                            <a 
                                              data-toggle="collapse" 
                                              href="#third_{{$t_cat->code}}" 
                                              role="button" 
                                              aria-expanded="false" 
                                              aria-controls="#third_{{$t_cat->code}}"
                                            >
                                              <b>{{$t_cat->code}}</b>
                                              :&nbsp;<span class="text-secondary">{{$t_cat->name}}</span> 
                                            </a>

                                            <ul class="categories_ul collapse" id="third_{{$t_cat->code}}">
                                              <li>
                                                <a href="{{route('admin.file_maintenance.edit_category_view') . '/' . $t_cat->id}}" class="btn btn-sm btn-warning">
                                                  <b>Edit&nbsp;<i class="far fa-edit"></i></b>
                                                </a>
                                              </li>
                                            </ul>
                                          </li>
                                        @endforeach
                                      @endif
                                    @endforeach
                                  </ul>
                                </li>
                              @endforeach
                            @endif
                          @endforeach
                       </ul>
                    </li>  
                  @endforeach
                @endforeach
              </ul>

            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="col-5 ml-2">
      <div class="row">
        <div class="col p-4 card bg-white box_form">

          <div class="row">
            <div class="col"><h5><b class="text-primary">Search Category</b></h5></div>
          </div>

          <form class="pt-1" action="{{route('admin.file_maintenance.search_category')}}" method="get">
            <div class="form-group">
              <label for="search" class="text-secondary"><b>Name</b></label>
              <input type="text" name="search" class="form-control" id="search" placeholder="Search Category" required>
            </div>

            <div class="form-group">
              <button type="submit" class="btn btn-success btn-sm"><b>Search&nbsp;<i class="fas fa-search"></i></b></button>
            </div>
          </form>

        </div>
      </div>
      
      @if($search_categories)
        <div class="row mt-2">
          <div class="col p-4 card bg-white box_form" style="max-height:500px">
            <table class="table table-hover table-responsive">
              <thead>
                <tr>
                  <th scope="col">Code</th>
                  <th scope="col">Name</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                @if( session('error_status') )
                  <tr>
                    <td colspan="3">{{session()->pull('error_status')}}</td>
                  </tr>
                @else
                  @foreach ($search_categories as $s_category)
                    <tr>
                      <td>{{$s_category->code}}</td>
                      <td>{{$s_category->name}}</td>
                      <td>
                        <a href="{{route('admin.file_maintenance.edit_category_view') . '/' . $s_category->id}}" 
                          class="btn btn-warning btn-sm">
                            <b>Edit&nbsp;<i class="far fa-edit"></i>
                          </b>
                        </a>
                      </td>
                    </tr>
                  @endforeach
                @endif
              </tbody>
            </table>
          </div>
        </div>
      @endif

    </div>
  </div>
  

  
@endsection