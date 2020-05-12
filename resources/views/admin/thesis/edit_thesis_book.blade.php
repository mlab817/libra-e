@extends('layouts.appAdmin')

@section('content')

  @php
    session(['sidebar_nav' => 'books']);     
    session(['sidebar_nav_lev_2' => '']); 
    session(['point_arrow' => 'thesis_books']);
  @endphp

  <div class="row">
    <button onclick="toggleMenu()" class="btn btn-primary" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </button>

    <h3 class="ml-2 p-1 text-primary"><b>Edit Thesis book</b></h3>
  </div>

  @include('inc.errors_all')
  
  @include('inc.status')
  
  <div class="row mx-1 mt-4 p-5 card bg-white box_form">

    <div class="col-12">
      	
      <div class="form-row">
        <div class="form-group col-12 p-1">
          <h3 class="text-primary"><b>Edit Thesis book</b></h3>
        </div>
      </div>

      <form action="{{route('admin.thesis.store_thesis_book')}}" method="POST">
        @csrf
        @method('PUT')

        <input name="id" value="{{$thesis_book->id}}" style="display:none;" />

        <div class="form-row ">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Thesis book No.*</b></span>
          </div>

          <div class="form-group col-md-6">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1"><b>T-</b></span>
              </div>

              <input type="text" name="acc_no" value="{{$thesis_book->acc_no}}" class="form-control text-secondary" readonly> 
            </div>
          </div>
        </div>

        <div class="form-row ">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Call No.*</b></span>
          </div>

          <div class="form-group col-md-6">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1"><b>T'</b></span>
              </div>

              <input type="text" name="call_no" value="{{$thesis_book->call_no}}" class="form-control text-secondary" readonly> 
              
            </div>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Authors*</b></span>
          </div>
          
          <input name="authors" value="{{$authors_count}}" style="display: none;"></input>

          <div class="form-group col-md-2">
            <div class="dropdown p-1">
              <button class="btn btn-sm btn-primary dropdown-toggle font-weight-bold" type="button" id="per_page_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>
                {{$authors_count}}
              </button>
              <div class="dropdown-menu" aria-labelledby="per_page_btn">
                <a class="dropdown-item" href="{{route('admin.thesis.add_thesis_book_view') . '/' . 1}}">1</a>
                <a class="dropdown-item" href="{{route('admin.thesis.add_thesis_book_view') . '/' . 2}}">2</a>
                <a class="dropdown-item" href="{{route('admin.thesis.add_thesis_book_view') . '/' . 3}}">3</a>
                <a class="dropdown-item" href="{{route('admin.thesis.add_thesis_book_view') . '/' . 4}}">4</a>
              </div>
            </div>          
          </div>
        </div>

        <div id="new_book_title" class="form-row animated fadeIn">
          @php $i = 1; @endphp
          
          @foreach ($thesis_authors as $author)
            <div class="form-group col-md-2 text-right p-1">
              <span class="text-info"><b>Author {{$i}}*</b></span>
            </div>

            <div class="form-group col-md-6">
              <input type="text" name="author_{{$i}}" value="{{ $author->name }}" class="form-control text-secondary @error('author_' . $i) is-invalid @enderror" placeholder="Author {{$i}}" required> 
              
              <input name="author_id_{{$i}}" value="{{$author->id}}" style="display:none;" />

              @error('author_{{$i}}')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>

            <div class="col-md-4"></div>
            
            @php $i++; @endphp

          @endforeach

          @for ($add = $i ; $add <= 4 ; $add++)
            <div class="form-group col-md-2 text-right p-1">
              <span class="text-info"><b>Author {{$add}}</b></span>
            </div>

            <div class="form-group col-md-6">
              <input type="text" name="author_{{$add}}" class="form-control text-secondary @error('author_' . $add) is-invalid @enderror" placeholder="Add Author {{$add}}"> 

              @error('author_{{$add}}')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>

            <div class="col-md-4"></div>
          @endfor
        </div>

        <div id="new_book_title" class="form-row animated fadeIn">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Title*</b></span>
          </div>

          <div class="form-group col-md-6">
            <input type="text" name="title" value="{{ $thesis_book->title }}" class="form-control text-secondary @error('title') is-invalid @enderror" placeholder="Title" required> 

            @error('title')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div id="new_book_title" class="form-row animated fadeIn">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Month Added*</b></span>
          </div>

          <div class="form-group col-md-6">
            <input type="month" id="month" name="month" value="{{$month}}" class="border border-info rounded text-secondary @error('month') is-invalid @enderror" required> 

            @error('month')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Copyright*</b></span>
          </div>

          <div class="form-group col-md-6">
            <input type="number" min="1800" max="3000" name="copyright" value="{{ $thesis_book->copyright }}" class="form-control text-secondary @error('copyright') is-invalid @enderror" placeholder="Copyright" required> 

            @error('copyright')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>System Type*</b></span>
          </div>

          <div class="form-group col-md-6">
            <select name="system_type" value="{{ $thesis_book->thesis_type_id }}" class="form-control text-secondary @error('system_type') is-invalid @enderror" required>
              <option value="">---Select System type---</option>
                @foreach ( $file_maintenance['thesis_types'] as $types)
                  <option value="{{$types->id}}" {{$thesis_book->thesis_type_id == $types->id  ? "selected":""}}>
                    {{$types->name}}
                  </option>
                @endforeach
            </select>

            @error('system_type')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Thesis Category Type*</b></span>
          </div>

          <div class="form-group col-md-6">
            <select name="thesis_category" value="{{ $thesis_book->thesis_category_id }}" class="form-control text-secondary @error('thesis_category') is-invalid @enderror" required>
              <option value="">---Select System type---</option>
                @foreach ( $file_maintenance['thesis_categories'] as $category)
                  <option value="{{$category->id}}" {{$thesis_book->thesis_category_id == $category->id  ? "selected":""}}>
                    {{$category->name}}
                  </option>
                @endforeach
            </select>

            @error('thesis_category')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Thesis Subject*</b></span>
          </div>

          <div class="form-group col-md-6">
            <select name="thesis_subject" value="{{ $thesis_book->thesis_subject_id }}" class="form-control text-secondary @error('thesis_subject') is-invalid @enderror" required>
              <option value="">---Select System type---</option>
                @foreach ( $file_maintenance['thesis_subjects'] as $subject)
                  <option value="{{$subject->id}}" {{$thesis_book->thesis_subject_id == $subject->id  ? "selected":""}}>
                    {{$subject->name}}
                  </option>
                @endforeach
            </select>

            @error('thesis_subject')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Program*</b></span>
          </div>

          <div class="form-group col-md-6">
            <select name="program" value="{{ $thesis_book->program_id }}" class="form-control text-secondary @error('program') is-invalid @enderror" required>
              <option value="">---Select Program---</option>
                @foreach ( $file_maintenance['programs'] as $program)
                  <option value="{{$program->id}}" {{$thesis_book->program_id == $program->id  ? "selected":""}}>
                    {{$program->code}}&nbsp;|&nbsp;{{$program->name}}
                  </option>
                @endforeach
            </select>

            @error('program')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col offset-md-1">
            <a href="{{route('admin.thesis.thesis_books')}}" class="btn btn-secondary">Cancel</a>
            <input type="submit" class="btn btn-primary" />
          </div>    
        </div>
      </form>
    </div>  
  </div>
@endsection



