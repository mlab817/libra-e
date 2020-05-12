@extends('layouts.appAdmin')

@section('content')

  @php
    session(['sidebar_nav' => 'books']);     
    session(['sidebar_nav_lev_2' => '']); 
    session(['point_arrow' => 'accessioning']);
  @endphp

  <div class="row">
    <button onclick="toggleMenu()" class="btn btn-primary" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </button>

    <h3 class="ml-2 p-1 text-primary"><b>View Accession</b></h3>
  </div>

  @include('inc.errors_all')

  
  <div class="row mx-1 mt-4 p-5 card bg-white box_form">
    <div class="col-12">
      <div class="row">
        <div class="col-6">

          <div class="form-row">
            <div class="form-group col-12 p-1">
              <h3 class="text-primary"><b>View Accession</b></h3>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Accession No:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">{{$accession->accession_no}}</span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Author Name:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">{{$accession->author_name}}</span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Book Title:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">{{$accession->book_title}}</span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Publisher Name:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">{{$accession->publisher_name}}</span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>ISBN:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">
                @if ($accession->isbn == null)
                  <span class="text-warning">Unassigned</span>
                @else
                  {{$accession->isbn}}
                @endif
              </span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Classification:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">
                @if ($accession->classification_name == null)
                  <span class="text-warning">Unassigned</span>
                @else
                  {{$accession->classification_name}}
                @endif
              </span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Category:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">
                @if ($accession->category_name == null)
                  <span class="text-warning">Unassigned</span>
                @else
                  {{$accession->category_name}}
                @endif
              </span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Category 2:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">
                @if ($category_2 == null)
                  <span class="text-warning">Unassigned</span>
                @else
                  {{$category_2->name}}
                @endif
              </span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Illustration:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">
                @if ($accession->illustration_name == null)
                  <span class="text-warning">Unassigned</span>
                @else
                  {{$accession->illustration_name}}
                @endif
              </span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Edition:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">
                @if ($accession->edition == null)
                  <span class="text-warning">Unassigned</span>
                @else
                  {{$accession->edition}}
                @endif
              </span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Volume:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">
                @if ($accession->volume == null)
                  <span class="text-warning">Unassigned</span>
                @else
                  {{$accession->volume}}
                @endif
              </span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Pages:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">
                @if ($accession->pages == null)
                  <span class="text-warning">Unassigned</span>
                @else
                  {{$accession->pages}}
                @endif
              </span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Cost Price:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">
                @if ($accession->cost_price == null)
                  <span class="text-warning">Unassigned</span>
                @else
                  {{$accession->cost_price}}
                @endif
              </span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Copyright:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">
                @if ($accession->copyright == null)
                  <span class="text-warning">Unassigned</span>
                @else
                  {{$accession->copyright}}
                @endif
              </span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Filipiniana</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">
                @if ($accession->filipiniana == null)
                  <span class="text-warning">Unassigned</span>
                @else
                  @if ($accession->filipiniana == 1)
                    Filipiniana
                  @elseif ($accession->filipiniana == 2)
                    Non-Filipiniana
                  @endif
                @endif
              </span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 text-right p-1">
              <span class="text-info"><b>Source of Fund:</b></span>
            </div>

            <div class="col-md-6 p-1">
              <span class="text-secondary">
                @if ($accession->source_of_fund == null)
                  <span class="text-warning">Unassigned</span>
                @else
                  @if ($accession->source_of_fund == 1)
                    Donation
                  @elseif ($accession->source_of_fund == 2)
                    Purchased
                  @elseif ($accession->source_of_fund == 3)
                    Others
                  @endif
                @endif
              </span>
            </div>
          </div>
          
          <div class="form-row mt-2">
            <div class="form-group col offset-md-1">
              <a href="{{route('admin.books.accessioning')}}" class="btn btn-primary">
                <i class="fas fa-arrow-circle-left"></i>&nbsp;Back
              </a>
            </div>    
          </div>
        </div>

        <div class="col-6">
          <div class="row">
            <div class="col-12 p-1 text-center">
              <span class="text-secondary float-left">
                <span class="text-info"><b>Picture</b></span>
                @if ($accession->pic_url == null)
                  <img src="{{asset('storage/images/accession_images/noimage.png')}}" alt="..." class="img-thumbnail">
                @else
                  <img src="{{asset('storage/images/accession_images/' . $accession->pic_url)}}" alt="..." class="img-thumbnail">
                @endif
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection



