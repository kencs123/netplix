@extends('layouts.app',['title' => 'Wachlist'])
@section('library')
    <link rel="stylesheet" href="{{ asset('css/watchlist.css') }}">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js"
        integrity="sha512-efUTj3HdSPwWJ9gjfGR71X9cvsrthIA78/Fvd/IN+fttQVy7XWkOAXb295j8B3cmm/kFKVxjiNYzKw9IQJHIuQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection
@section('content')
    <div class="watchlist">
        <div class="title-section cust-container cust-row">
            <i class="fa fa-bookmark bookmark-icon text--red"></i>
            <h1 class="cust-row">My<span class="text--red">Watchlist</span></h1>
        </div>

        <div class="search-section cust-container cust-col">
            <div class="search-section__bar cust-row">
                <input type="text" name="" id="search-input" placeholder="Search your watchlist..."
                    class="search-section__input">
                <i class="fa fa-search search-icon"></i>
            </div>

            <div class="search-section__filter cust-row">
                <i class="fas fa-filter filter-icon"></i>
                <select class="form-select filter-select">
                    <option value="all" selected>All</option>
                    <option value="planning">Planned</option>
                    <option value="watching">Watching</option>
                    <option value="finished">Finished</option>
                </select>
            </div>
        </div>
        <div class="watchlist-section cust-container cust-col">
            <div class="watchlist-title cust-row">
                <h4 class="row-item">Poster</h4>
                <h4 class="row-item">Title</h4>
                <h4 class="row-item">Status</h4>
                <h4 class="row-item">Your Rating</h4>
                <h4 class="row-item">Public Rating</h4>
                <h4 class="row-item--action">Action</h4>
            </div>
            <div id="watchlist-container">
            @foreach ($watchlists as $watchlist)
    <div class="watchlist-card cust-row">
        <a href="{{ route('show-movie', $watchlist->show_id) }}" class="watchlist-card__poster row-item"
            style="background-image : url('{{ $watchlist->image_url }}');background-size: contain;background-repeat: no-repeat;background-position: left;min-height: 150px;">
        </a>
        <h4 class="watchlist-card__title row-item">{{ $watchlist->title }}</h4>

        <h4
            class="watchlist-card__status row-item {{ ucfirst($watchlist->status) == 'Finished' ? 'textRed' : (ucfirst($watchlist->status) == 'Planning' ? 'textGreen' : 'textBlue') }}">
            {{ ucfirst($watchlist->status) }}</h4>
        <div class=" 
                            rating row-item ">
            <i class="fa fa-star"></i>
            {{ $watchlist->ownRating }}
        </div>
        <div class="rating row-item">
            <i class="fa fa-star"></i>
            {{ $watchlist->rating }}
        </div>
        <div class="actions row-item">
            <button type="button" class="btn btn-primary status-btn" data-bs-toggle="modal"
                data-bs-target="#{{ $watchlist->show_id }}">
                &#8943;
            </button>

            <div class="modal fade" id="{{ $watchlist->show_id }}" tabindex="-1"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Change Status</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <form action="{{ route('action-watchlist', [$watchlist->show_id, request()->page ?? 1]) }}"
                            method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="cbo-wrapper">
                                    <select name="status" class="status-cbo">
                                        <option value="planning">Planned</option>
                                        <option value="watching">Watching</option>
                                        <option value="finished" selected>Finished</option>
                                        <option value="remove">Remove</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary save-btn">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
            </div>
            {{ $watchlists->appends(request()->except('page'))->links() }}
        </div>
    </div>
    <script>
        const filterSelect = document.querySelector('.filter-select');
        filterSelect.addEventListener("change", function() {
            $('#paginator-container').remove();
            $.ajax({
                    url: '?filter=' + this.value,
                    type: "get",
                })
                .done(function(data) {
                    $("#watchlist-container").empty();
                    $("#watchlist-container").append(data.html);
                })
                .fail(function(jqXHR, ajaxOptions, thrownError) {
                    console.log(jqXHR.responseJSON);
                    alert('server not responding...');
                });
        });

        $('#search-input').keyup(function() {
            var search = $(this).val();
            $('#paginator-container').remove();
            if (filterSelect.value != 'all') {
                $.ajax({
                        url: '?search=' + search + '&filter=' + filterSelect.value,
                        type: "get",
                    })
                    .done(function(data) {
                        $("#watchlist-container").empty();
                        $("#watchlist-container").append(data.html);
                    })
                    .fail(function(jqXHR, ajaxOptions, thrownError) {
                        console.log(jqXHR.responseJSON);
                        alert('server not responding...');
                    });
            } else {
                $.ajax({
                        url: '?search=' + search,
                        type: "get",
                    })
                    .done(function(data) {
                        $("#watchlist-container").empty();
                        $("#watchlist-container").append(data.html);
                    })
                    .fail(function(jqXHR, ajaxOptions, thrownError) {
                        console.log(jqXHR.responseJSON);
                        alert('server not responding...');
                    });
            }
        });
    </script>
@endsection
