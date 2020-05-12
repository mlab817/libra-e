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

    <h3 class="ml-2 p-1 text-primary"><b>Edit Accession</b></h3>
  </div>

  @include('inc.errors_all')

  @include('inc.book_exist')
  
  <div class="row mx-1 mt-4 p-5 card bg-white box_form">

    <div class="col-12">
      	
      <div class="form-row">
        <div class="form-group col-12 p-1">
          <h3 class="text-primary"><b>Change Book Accession</b></h3>
        </div>
      </div>

      <form action="{{route('admin.books.add_new_accession')}}" method="POST">
        @csrf
        @method('PUT')
        
        <input type="number" style="display:none;" name="id" value="{{$accession->accession_no_id}}" /> 

        <div class="form-row ">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Accession No.*</b></span>
          </div>

          <div class="form-group col-md-6">
            <input type="text" name="accession_no" value="{{$accession->accession_no}}" class="form-control text-secondary" placeholder="Accession No." readonly> 
          </div>
        </div>

        <div class="form-row animated fadeIn">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Select Book*</b></span>
          </div>

          <div class="form-group col-md-6">
            <select name="book" value="{{ old('book') }}" class="form-control text-secondary @error('book') is-invalid @enderror">
              <option value="">---Select Book---</option>
              @foreach ( $accessions_all as $book)
                <option value="{{$book->accession_id}}" {{ old('book') == $book->accession_id  ? "selected":""}}>
                  {{$book->book_title}}&nbsp;|
                  {{$book->author_name}}&nbsp;| 
                  {{$book->publisher_name}} 
                </option>
              @endforeach
            </select>

            @error('author_select')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col offset-md-1">
            <a href="{{route('admin.books.accessioning')}}" class="btn btn-secondary">Cancel</a>
            <input type="submit" class="btn btn-primary" />
          </div>    
        </div>
      </form>

      <hr />

      <form action="{{route('admin.books.store_accession')}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <input type="number" style="display:none;" name="accession_no_id" value="{{$accession->accession_no_id}}" /> 

        <input type="number" style="display:none;" name="accession_id" value="{{$accession->accession_id}}" /> 

        <div class="form-row">
          <div class="form-group col-12 p-1">
            <h3 class="text-primary"><b>Edit Book & Accession</b></h3>
          </div>
        </div>

        <div class="form-row ">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Accession No.*</b></span>
          </div>

          <div class="form-group col-md-6">
            <input type="text" name="accession_no" value="{{$accession->accession_no}}" class="form-control text-secondary" placeholder="Accession No." readonly> 
          </div>
        </div>

        <div class="form-row">
          <div class="form-group text-secondary col offset-md-1 p-1">
            <div onclick="select_radio('author', 2)" class="custom-control custom-radio custom-control-inline">
              <input type="radio" id="author_radio2" value="2" name="author_radio" @if(old('author_radio') == 2) checked @endif class="custom-control-input" checked>
              <label class="custom-control-label" for="author_radio2"><b>Author</b></label>
            </div>
            <div onclick="select_radio('author', 1)" class="custom-control custom-radio custom-control-inline">
              <input type="radio" id="author_radio" value="1" name="author_radio" @if(old('author_radio') == 1) checked @endif class="custom-control-input">
              <label class="custom-control-label" for="author_radio"><b>New Author</b></label>
            </div>
          </div>
        </div>

        <div id="exist_author" class="form-row animated fadeIn">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Author*</b></span>
          </div>

          <div class="form-group col-md-6">
            <select name="author_select" value="{{ $accession->author_id }}" class="form-control text-secondary @error('author_select') is-invalid @enderror">
              <option value="">---Select Author---</option>
              @foreach ( $file_maintenance['authors'] as $author)
                <option value="{{$author->id}}" {{ $accession->author_id == $author->id  ? "selected":""}}>
                  @if($author->author_name == '') Unknown Author 
                  @else {{$author->author_name}} 
                  @endif
                </option>
              @endforeach
            </select>

            @error('author_select')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div id="new_author" style="display:none" class="form-row animated fadeIn">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>New Author*</b></span>
          </div>

          <div class="form-group col-md-6">
            <input name="author" type="text" value="{{ old('author') }}" class="form-control text-secondary @error('author') is-invalid @enderror" placeholder="Author"> 

            @error('author')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div id="new_book_title" class="form-row animated fadeIn">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Book Title*</b></span>
          </div>

          <div class="form-group col-md-6">
            <input type="text" name="book_title" value="{{ $accession->book_title }}" class="form-control text-secondary @error('book_title') is-invalid @enderror" placeholder="Book Title"> 

            @error('book_title')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group text-secondary col offset-md-1 p-1">
            <div onclick="select_radio('publisher', 2)" class="custom-control custom-radio custom-control-inline">
              <input type="radio" id="publisher_radio2" value="2" name="publisher_radio" @if(old('publisher_radio') == 2) checked @endif class="custom-control-input" checked>
              <label class="custom-control-label" for="publisher_radio2"><b>Publisher</b></label>
            </div>
            <div onclick="select_radio('publisher', 1)" class="custom-control custom-radio custom-control-inline">
              <input type="radio" id="publisher_radio" value="1" name="publisher_radio" @if(old('publisher_radio') == 1) checked @endif class="custom-control-input">
              <label class="custom-control-label" for="publisher_radio"><b>New Publisher</b></label>
            </div>
          </div>
        </div>

        <div id="exist_publisher" class="form-row animated fadeIn">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Existing Publisher*</b></span>
          </div>

          <div class="form-group col-md-6">
            <select name="publisher_select" value="{{ $accession->publisher_id }}" class="form-control text-secondary @error('publisher_select') is-invalid @enderror">
              <option value="">---Select Publisher---</option>
                @foreach ( $file_maintenance['publishers'] as $publisher)
                  <option value="{{$publisher->id}}" {{$accession->publisher_id == $publisher->id  ? "selected":""}}>
                    {{$publisher->name}}
                  </option>
                @endforeach
            </select>

            @error('publisher_select')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div id="new_publisher" style="display:none" class="form-row animated fadeIn">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>New Publisher*</b></span>
          </div>

          <div class="form-group col-md-6">
            <input name="publisher" value="{{ old('publisher') }}" type="text" class="form-control text-secondary @error('publisher') is-invalid @enderror" placeholder="Publisher"> 

            @error('publisher')
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
            <input type="number" min="1800" max="3000" name="copyright" value="{{ $accession->copyright }}" class="form-control text-secondary @error('copyright') is-invalid @enderror" placeholder="Copyright" required> 

            @error('copyright')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>
        
        <hr />
        
        <div class="form-row">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>ISBN</b></span>
          </div>

          <div class="form-group col-md-6">
            <input type="number" min="1000000000000" max="9999999999999" name="isbn" value="{{ $accession->isbn }}" class="form-control text-secondary @error('isbn') is-invalid @enderror" placeholder="ISBN"> 

            @error('isbn')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Main Picture</b></span>
          </div>
        
          <div class="form-group col-md-6">
            <input name="pic_name" value="{{$accession->pic_url}}" style="display:none;" />
            @if ($accession->pic_url == null)
              <img src="{{asset('storage/images/accession_images/noimage.png')}}" alt="..." class="img-thumbnail">
            @else
              <img src="{{asset('storage/images/accession_images/' . $accession->pic_url)}}" alt="..." class="img-thumbnail">
            @endif
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Change Picture</b></span>
          </div>
        
          <div class="form-group col-md-6">
            <input type="file" name="pic_file" accept="image/x-png,image/jpeg" value="{{ old('pic_file') }}" class="form-control-file @error('pic_file') is-invalid @enderror" id="pic_cover">

            @error('pic_file')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Classifications*</b></span>
          </div>

          <div class="form-group col-md-6">
            <select name="classification" value="{{ $accession->classification_id }}" class="form-control text-secondary @error('classification') is-invalid @enderror">
              <option value="">---Select Classification---</option>
                @foreach ( $file_maintenance['classifications'] as $classification)
                  <option value="{{$classification->id}}" {{ $accession->classification_id == $classification->id  ? "selected":""}}>
                    {{$classification->name}}
                  </option>
                @endforeach
            </select>

            @error('classification')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Category*</b></span>
          </div>

          <div class="form-group col-md-6">
            <select name="category" value="{{ $accession->category_id }}" class="form-control text-secondary @error('category') is-invalid @enderror">
              <option value="">---Select Category---</option>
                @foreach ( $file_maintenance['categories'] as $category)
                  <option value="{{$category->id}}" {{ $accession->category_id == $category->id  ? "selected":""}}>
                    <b class="text-secondary">{{$category->code}}</b>
                    &nbsp;{{$category->name}}
                  </option>
                @endforeach
            </select>

            @error('category')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Category 2*</b></span>
          </div>

          <div class="form-group col-md-6">
            <select name="category_2" value="{{ $accession->category_id_2 }}" class="form-control text-secondary @error('category') is-invalid @enderror">
              <option value="">---Select Category---</option>
                @foreach ( $file_maintenance['categories'] as $category)
                  <option value="{{$category->id}}" {{ $accession->category_id_2 == $category->id  ? "selected":""}}>
                    <b class="text-secondary">{{$category->code}}</b>
                    &nbsp;{{$category->name}}
                  </option>
                @endforeach
            </select>

            @error('category')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Illustration*</b></span>
          </div>

          <div class="form-group col-md-6">
            <select name="illustration" value="{{ $accession->illustration_id }}" class="form-control text-secondary @error('illustration') is-invalid @enderror">
              <option value="">---Select Illustration---</option>
                @foreach ( $file_maintenance['illustrations'] as $illustration )
                  <option value="{{$illustration->id}}" {{$accession->illustration_id == $illustration->id  ? "selected":""}}>
                    {{$illustration->name}}
                  </option> 
                @endforeach
            </select>

            @error('illustration')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>
        
        <div class="form-row">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Edition</b></span>
          </div>

          <div class="form-group col-md-6">
            <input type="number" min="0" max="999" name="edition" value="{{ $accession->edition }}" class="form-control text-secondary @error('edition') is-invalid @enderror" placeholder="Edition"> 

            @error('edition')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Volume</b></span>
          </div>

          <div class="form-group col-md-6">
            <input type="number" min="0" max="999" name="volume" value="{{ $accession->volume }}" class="form-control text-secondary @error('volume') is-invalid @enderror" placeholder="Volume"> 

            @error('volume')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>
        
        <div class="form-row">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Pages</b></span>
          </div>

          <div class="form-group col-md-6">
            <input type="number" min="0" max="99999" name="pages" value="{{ $accession->pages }}" class="form-control text-secondary @error('pages') is-invalid @enderror" placeholder="Pages"> 

            @error('pages')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Filipiniana</b></span>
          </div>
        
          <div class="form-group text-secondary col-md-6 p-1">
            <div class="custom-control custom-radio custom-control-inline">
              <input type="radio" id="filipiniana_radio" value="1" name="filipiniana_radio" @if($accession->filipiniana == 1) checked @endif class="custom-control-input">
              <label class="custom-control-label" for="filipiniana_radio"><b>Filipiniana</b></label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
              <input type="radio" id="filipiniana_radio2" value="2" name="filipiniana_radio" @if($accession->filipiniana == 2) checked @endif  class="custom-control-input">
              <label class="custom-control-label" for="filipiniana_radio2"><b>Non-Filipiniana</b></label>
            </div>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Source of Fund</b></span>
          </div>
        
          <div class="form-group text-secondary col-md-6 p-1">
            <div onclick="select_fund(1)" class="custom-control custom-radio custom-control-inline">
              <input type="radio" id="source_fund_radio" value="1" name="source_fund_radio" @if($accession->source_of_fund == 1) checked @endif class="custom-control-input">
              <label class="custom-control-label" for="source_fund_radio"><b>Donation</b></label>
            </div>
            <div onclick="select_fund(2)" class="custom-control custom-radio custom-control-inline">
              <input type="radio" id="source_fund_radio2" value="2" name="source_fund_radio" @if($accession->source_of_fund == 2) checked @endif class="custom-control-input">
              <label class="custom-control-label" for="source_fund_radio2"><b>Purchased</b></label>
            </div>
            <div onclick="select_fund(3)" class="custom-control custom-radio custom-control-inline">
              <input type="radio" id="source_fund_radio3" value="3" name="source_fund_radio" @if($accession->source_of_fund == 3) checked @endif class="custom-control-input">
              <label class="custom-control-label" for="source_fund_radio3"><b>Others</b></label>
            </div>
          </div>
        </div>

        <div id="cost_price" @if($accession->source_of_fund != 2) style="display:none;"  @endif class="form-row animated fadeIn">
          <div class="form-group col-md-2 text-right p-1">
            <span class="text-info"><b>Cost Price</b></span>
          </div>

          <div class="form-group col-md-6">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">&#8369;</span>
              </div>

              <input type="number" min="0" name="cost_price" value="{{ $accession->cost_price }}" class="form-control text-secondary @error('cost_price') is-invalid @enderror" placeholder="Cost Price"> 

              @error('cost_price')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col offset-md-1">
            <a href="{{route('admin.books.accessioning')}}" class="btn btn-secondary">Cancel</a>
            <input type="submit" class="btn btn-primary" />
          </div>    
        </div>
      </form>
    </div>  
    
  </div>


  <script type="application/javascript">
    
    function select_radio(field, pick) {

      var to_hide;
      var to_show;

      
      if(pick == 1){
        
        to_show = 'new_' + field;
        to_hide = 'exist_' + field;
        
      }

      else if(pick == 2){
        
        to_show = 'exist_' + field;
        to_hide = 'new_' + field;
        
      }

      $("#" + to_show).show();

      $("#" + to_hide).hide();

    }

    function select_fund(select){
      
      if(select == 2){

        $("#cost_price").show();

      }else{
        
        $("#cost_price").hide();

      }
    }

  </script>

  <script type="application/javascript">
    setTimeout( function(){
      $(document).ready(function() {
        @if (old('author_radio'))
          select_radio('author', {{old('author_radio')}});
        @endif

        @if (old('publisher_radio'))
          select_radio('publisher', {{old('publisher_radio')}});
        @endif

        @if (old('source_fund_radio') == 2)
          select_fund(2);
        @endif
      });
    }, 900 );
  </script>

@endsection



